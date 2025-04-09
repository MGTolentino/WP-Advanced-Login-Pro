<?php
/**
 * Maneja las operaciones relacionadas con usuarios.
 *
 * Esta clase contiene métodos para registrar, autenticar,
 * actualizar y verificar usuarios.
 */
class WP_ALP_User_Manager {

    /**
     * El objeto JetEngine para manejar la integración.
     */
    private $jetengine;

    /**
     * Constructor de la clase.
     */
    public function __construct() {
        $this->jetengine = new WP_ALP_JetEngine();
    }

    /**
     * Verifica si un usuario existe por email o teléfono.
     *
     * @param string $identifier Email o teléfono.
     * @return array Resultado de la verificación.
     */
    public function check_user_exists($identifier) {
        $result = array(
            'exists' => false,
            'user_id' => 0,
            'user_type' => '',
            'profile_status' => '',
            'found_by' => '',
        );
        
        // Determinar si es email o teléfono
        if (is_email($identifier)) {
            // Buscar por email en WordPress
            $user = get_user_by('email', $identifier);
            
            if ($user) {
                $result['exists'] = true;
                $result['user_id'] = $user->ID;
                $result['user_type'] = get_user_meta($user->ID, 'wp_alp_user_type', true);
                $result['profile_status'] = get_user_meta($user->ID, 'wp_alp_profile_status', true);
                $result['found_by'] = 'email';
            }
        } else {
            // Sanitizar teléfono
            $phone = WP_ALP_Security::sanitize_phone_number($identifier);
            
            // Buscar en la tabla de leads de JetEngine
            $lead = $this->jetengine->find_lead_by_phone($phone);
            
            if ($lead) {
                $result['exists'] = true;
                $result['user_id'] = $lead['_user_id'];
                $result['user_type'] = 'lead';
                $result['profile_status'] = 'complete';
                $result['found_by'] = 'phone';
                $result['lead_id'] = $lead['_ID'];
            } else {
                // Buscar en wp_usermeta
                $user_query = new WP_User_Query(array(
                    'meta_key' => 'wp_alp_phone',
                    'meta_value' => $phone,
                ));
                
                if (!empty($user_query->results)) {
                    $user = $user_query->results[0];
                    $result['exists'] = true;
                    $result['user_id'] = $user->ID;
                    $result['user_type'] = get_user_meta($user->ID, 'wp_alp_user_type', true);
                    $result['profile_status'] = get_user_meta($user->ID, 'wp_alp_profile_status', true);
                    $result['found_by'] = 'phone_meta';
                }
            }
        }
        
        return $result;
    }

    /**
     * Registra un nuevo usuario.
     *
     * @param array $user_data Los datos del usuario.
     * @return array Resultado del registro.
     */
    public function register_user($user_data) {
        // Sanitizar datos
        $sanitized = WP_ALP_Security::sanitize_input_data($user_data);
        
        // Verificar que el email no exista
        if (email_exists($sanitized['email'])) {
            return array(
                'success' => false,
                'message' => __('Este correo electrónico ya está registrado.', 'wp-alp'),
            );
        }
        
        // Crear usuario
        $user_id = wp_insert_user(array(
            'user_login' => $sanitized['email'],
            'user_email' => $sanitized['email'],
            'user_pass' => $sanitized['password'],
            'first_name' => $sanitized['first_name'],
            'last_name' => $sanitized['last_name'],
            'display_name' => $sanitized['first_name'] . ' ' . $sanitized['last_name'],
            'role' => 'subscriber',
        ));
        
        if (is_wp_error($user_id)) {
            return array(
                'success' => false,
                'message' => $user_id->get_error_message(),
            );
        }
        
        // Guardar metadatos adicionales
        update_user_meta($user_id, 'wp_alp_phone', $sanitized['phone']);
        update_user_meta($user_id, 'wp_alp_profile_status', 'incomplete');
        
        // Si el formulario incluye los datos de evento, completar perfil
        if (isset($sanitized['event_type']) && !empty($sanitized['event_type'])) {
            $this->complete_user_profile($user_id, $sanitized);
        }
        
        // Generar código de verificación
        $verification_code = WP_ALP_Security::generate_verification_code();
        update_user_meta($user_id, 'wp_alp_email_verification_code', $verification_code);
        update_user_meta($user_id, 'wp_alp_email_verification_expiry', time() + 3600); // 1 hora
        update_user_meta($user_id, 'wp_alp_email_verified', 'no');
        
        // Enviar email de verificación
        $this->send_verification_email($sanitized['email'], $verification_code);
        
        // Iniciar sesión automáticamente
        wp_set_auth_cookie($user_id, true);
        
        return array(
            'success' => true,
            'user_id' => $user_id,
            'message' => __('Usuario registrado exitosamente. Verifica tu correo electrónico.', 'wp-alp'),
            'needs_verification' => true,
        );
    }

    /**
     * Completa el perfil de un usuario y lo convierte en lead.
     *
     * @param int $user_id ID del usuario.
     * @param array $profile_data Datos del perfil y evento.
     * @return array Resultado de la operación.
     */
    public function complete_user_profile($user_id, $profile_data) {
        // Sanitizar datos
        $sanitized = WP_ALP_Security::sanitize_input_data($profile_data);
        
        // Obtener datos del usuario
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            return array(
                'success' => false,
                'message' => __('Usuario no encontrado.', 'wp-alp'),
            );
        }
        
        // Preparar datos para el lead
$lead_data = array(
    'lead_nombre' => $sanitized['first_name'] ?? $user->first_name,
    'lead_apellido' => $sanitized['last_name'] ?? $user->last_name,
    'lead_e_mail' => $sanitized['email'] ?? $user->user_email,
    'lead_celular' => $sanitized['phone'] ?? get_user_meta($user_id, 'wp_alp_phone', true),
    'cct_status' => 'publish',
    'cct_created' => current_time('mysql'),
    'cct_modified' => current_time('mysql'),
    'cct_author_id' => $user_id,
);

// Crear lead directamente en la tabla
global $wpdb;
$inserted = $wpdb->insert(
    $wpdb->prefix . 'jet_cct_leads',
    $lead_data,
    array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d')
);

if (!$inserted) {
    return array(
        'success' => false,
        'message' => __('Error al crear el lead.', 'wp-alp'),
    );
}

$lead_id = $wpdb->insert_id;
        
        if (!$lead_id) {
            return array(
                'success' => false,
                'message' => __('Error al crear el lead.', 'wp-alp'),
            );
        }
        
        // Convertir fecha a timestamp si no lo es
$event_date = $sanitized['event_date'];
if (!is_numeric($event_date)) {
    $event_date = strtotime($sanitized['event_date']);
    if ($event_date === false) {
        $event_date = time();
    }
}

// Preparar datos para el evento
$event_data = array(
    'lead_id' => $lead_id,
    'tipo_de_evento' => $sanitized['event_type'],
    'fecha_de_evento' => $event_date,
    'direccion_evento' => $sanitized['event_address'],
    'evento_asistentes' => intval($sanitized['guests']),
    'comentarios_evento' => $sanitized['details'] ?? '',
    'evento_status' => 'nuevo',
    'cct_status' => 'publish',
    'cct_created' => current_time('mysql'),
    'cct_modified' => current_time('mysql'),
);

// Crear evento directamente en la tabla
$event_inserted = $wpdb->insert(
    $wpdb->prefix . 'jet_cct_eventos',
    $event_data,
    array('%d', '%s', '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s')
);

$event_id = $event_inserted ? $wpdb->insert_id : 0;
        
        // Actualizar metadatos del usuario
        update_user_meta($user_id, 'wp_alp_user_type', 'lead');
        update_user_meta($user_id, 'wp_alp_profile_status', 'complete');
        update_user_meta($user_id, 'wp_alp_lead_id', $lead_id);
        
        if (!empty($sanitized['first_name'])) {
            wp_update_user(array(
                'ID' => $user_id,
                'first_name' => $sanitized['first_name'],
                'last_name' => $sanitized['last_name'],
                'display_name' => $sanitized['first_name'] . ' ' . $sanitized['last_name'],
            ));
        }
        
        return array(
            'success' => true,
            'user_id' => $user_id,
            'lead_id' => $lead_id,
            'event_id' => $event_id,
            'message' => __('Perfil completado exitosamente.', 'wp-alp'),
        );
    }

    /**
     * Verifica un código enviado al email.
     *
     * @param int $user_id ID del usuario.
     * @param string $code Código de verificación.
     * @return array Resultado de la verificación.
     */
    public function verify_email_code($user_id, $code) {
        $stored_code = get_user_meta($user_id, 'wp_alp_email_verification_code', true);
        $expiry = get_user_meta($user_id, 'wp_alp_email_verification_expiry', true);
        
        if (empty($stored_code)) {
            return array(
                'success' => false,
                'message' => __('No hay código de verificación pendiente.', 'wp-alp'),
            );
        }
        
        if (time() > $expiry) {
            return array(
                'success' => false,
                'message' => __('El código ha expirado. Solicita uno nuevo.', 'wp-alp'),
            );
        }
        
        if ($code !== $stored_code) {
            return array(
                'success' => false,
                'message' => __('Código incorrecto. Intenta nuevamente.', 'wp-alp'),
            );
        }
        
        // Código válido, marcar email como verificado
        update_user_meta($user_id, 'wp_alp_email_verified', 'yes');
        delete_user_meta($user_id, 'wp_alp_email_verification_code');
        delete_user_meta($user_id, 'wp_alp_email_verification_expiry');
        
        return array(
            'success' => true,
            'message' => __('Email verificado exitosamente.', 'wp-alp'),
        );
    }

    /**
     * Envía un email de verificación.
     *
     * @param string $email Dirección de correo.
     * @param string $code Código de verificación.
     * @return bool Resultado del envío.
     */
    public function send_verification_email($email, $code) {
        $subject = __('Verifica tu correo electrónico', 'wp-alp');
        
        $message = sprintf(
            __('Tu código de verificación es: %s. Este código expirará en 1 hora.', 'wp-alp'),
            $code
        );
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        return wp_mail($email, $subject, $message, $headers);
    }

    /**
     * Reenvía el código de verificación.
     *
     * @param int $user_id ID del usuario.
     * @return array Resultado del reenvío.
     */
    public function resend_verification_code($user_id) {
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            return array(
                'success' => false,
                'message' => __('Usuario no encontrado.', 'wp-alp'),
            );
        }
        
        // Generar nuevo código
        $verification_code = WP_ALP_Security::generate_verification_code();
        update_user_meta($user_id, 'wp_alp_email_verification_code', $verification_code);
        update_user_meta($user_id, 'wp_alp_email_verification_expiry', time() + 3600); // 1 hora
        
        // Enviar email
        $email_sent = $this->send_verification_email($user->user_email, $verification_code);
        
        if (!$email_sent) {
            return array(
                'success' => false,
                'message' => __('Error al enviar el email. Intenta nuevamente.', 'wp-alp'),
            );
        }
        
        return array(
            'success' => true,
            'message' => __('Se ha enviado un nuevo código a tu correo.', 'wp-alp'),
        );
    }

    /**
     * Autentica a un usuario.
     *
     * @param string $username Nombre de usuario o email.
     * @param string $password Contraseña.
     * @return array Resultado de la autenticación.
     */
    public function login_user($username, $password) {
        $credentials = array(
            'user_login' => $username,
            'user_password' => $password,
            'remember' => true,
        );
        
        $user = wp_signon($credentials, is_ssl());
        
        if (is_wp_error($user)) {
            return array(
                'success' => false,
                'message' => $user->get_error_message(),
            );
        }
        
        // Verificar si el usuario necesita completar perfil
        $user_type = get_user_meta($user->ID, 'wp_alp_user_type', true);
        $profile_status = get_user_meta($user->ID, 'wp_alp_profile_status', true);
        
        $result = array(
            'success' => true,
            'user_id' => $user->ID,
            'message' => __('Inicio de sesión exitoso.', 'wp-alp'),
        );
        
        if ($user->roles[0] === 'subscriber' && ($user_type === '' || $profile_status === 'incomplete')) {
            $result['needs_profile'] = true;
        }
        
        return $result;
    }

    /**
     * Busca un usuario por teléfono.
     *
     * @param string $phone Número de teléfono.
     * @return WP_User|false Usuario encontrado o false.
     */
    public function find_user_by_phone($phone) {
        // Sanitizar teléfono
        $phone = WP_ALP_Security::sanitize_phone_number($phone);
        
        // Buscar primero en la tabla de leads
        $lead = $this->jetengine->find_lead_by_phone($phone);
        
        if ($lead && isset($lead['_user_id']) && !empty($lead['_user_id'])) {
            return get_user_by('ID', $lead['_user_id']);
        }
        
        // Buscar en wp_usermeta
        $user_query = new WP_User_Query(array(
            'meta_key' => 'wp_alp_phone',
            'meta_value' => $phone,
        ));
        
        if (!empty($user_query->results)) {
            return $user_query->results[0];
        }
        
        return false;
    }
}