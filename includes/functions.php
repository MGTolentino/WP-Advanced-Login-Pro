<?php

/**
 * Verifica y crea un vendor si es necesario
 */
function check_create_vendor_ajax() {
    // Verificar nonce por seguridad
    if (!check_ajax_referer('create_vendor_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => __('Sesión expirada. Por favor, recarga la página.', 'wp-alp'),
            'code' => 'invalid_nonce'
        ));
        return;
    }
    
    // Verificar si el usuario está loggeado
    if (!is_user_logged_in()) {
        wp_send_json_error(array(
            'message' => __('Debes iniciar sesión para continuar.', 'wp-alp'),
            'code' => 'not_logged_in'
        ));
        return;
    }
    
    $user_id = get_current_user_id();
    $result = array('success' => false);
    
    // Verificar permisos del usuario
    // Solo usuarios con rol 'lead' o superior pueden crear vendors
    $user = get_user_by('ID', $user_id);
    if (!$user) {
        wp_send_json_error(array(
            'message' => __('Usuario no válido.', 'wp-alp'),
            'code' => 'invalid_user'
        ));
        return;
    }
    
    // Verificar roles permitidos
    $allowed_roles = array('lead', 'administrator', 'editor');
    $user_roles = (array) $user->roles;
    $has_permission = false;
    
    foreach ($allowed_roles as $role) {
        if (in_array($role, $user_roles)) {
            $has_permission = true;
            break;
        }
    }
    
    if (!$has_permission) {
        // Registrar intento no autorizado
        if (class_exists('WP_ALP_Security_Enhanced')) {
            WP_ALP_Security_Enhanced::log_security_event(
                'unauthorized_vendor_creation_attempt',
                array(
                    'user_id' => $user_id,
                    'user_roles' => $user_roles
                ),
                'warning'
            );
        }
        
        wp_send_json_error(array(
            'message' => __('No tienes permisos para crear un vendor.', 'wp-alp'),
            'code' => 'insufficient_permissions'
        ));
        return;
    }
    
    // Verificar rate limiting
    if (WP_ALP_Security::is_rate_limited($user_id, 'create_vendor', 3, 3600)) {
        wp_send_json_error(array(
            'message' => __('Has intentado crear un vendor demasiadas veces. Por favor, espera una hora.', 'wp-alp'),
            'code' => 'rate_limited'
        ));
        return;
    }
    
    // Verificar si el usuario ya es vendor
    $vendor = null;
    if (class_exists('HivePress\Models\Vendor')) {
        $vendor = \HivePress\Models\Vendor::query()->filter(
            array(
                'status' => array('auto-draft', 'draft', 'publish'),
                'user'   => $user_id,
            )
        )->get_first();
    }
    
    // Si no hay vendor, crearlo
    if (!$vendor && class_exists('HivePress\Models\Vendor')) {
        try {
            // Sanitizar datos del usuario
            $first_name = sanitize_text_field(get_user_meta($user_id, 'first_name', true));
            $last_name = sanitize_text_field(get_user_meta($user_id, 'last_name', true));
            $vendor_name = trim($first_name . ' ' . $last_name);
            
            // Si no hay nombre, usar el display name del usuario
            if (empty($vendor_name)) {
                $vendor_name = sanitize_text_field($user->display_name);
            }
            
            // Si aún no hay nombre, usar el email
            if (empty($vendor_name)) {
                $vendor_name = sanitize_text_field($user->user_email);
            }
            
            $vendor = new \HivePress\Models\Vendor();
            $vendor->fill(array(
                'user'   => $user_id,
                'status' => 'draft', // Iniciar como draft para revisión
                'name'   => $vendor_name,
            ));
            
            // Guardar el vendor
            if ($vendor->save()) {
                $result['created'] = true;
                
                // Registrar evento de seguridad
                if (class_exists('WP_ALP_Security_Enhanced')) {
                    WP_ALP_Security_Enhanced::log_security_event(
                        'vendor_created',
                        array(
                            'user_id' => $user_id,
                            'vendor_id' => $vendor->get_id(),
                            'vendor_name' => $vendor_name
                        ),
                        'info'
                    );
                }
            } else {
                throw new Exception('Failed to save vendor');
            }
        } catch (Exception $e) {
            // Registrar error
            if (class_exists('WP_ALP_Security_Enhanced')) {
                WP_ALP_Security_Enhanced::log_security_event(
                    'vendor_creation_error',
                    array(
                        'user_id' => $user_id,
                        'error' => $e->getMessage()
                    ),
                    'error'
                );
            }
            
            wp_send_json_error(array(
                'message' => __('Error al crear el vendor. Por favor, inténtalo de nuevo.', 'wp-alp'),
                'code' => 'creation_error'
            ));
            return;
        }
    }
    
    $result['success'] = true;
    $result['vendor_id'] = $vendor ? $vendor->get_id() : null;
    
    wp_send_json($result);
}
add_action('wp_ajax_check_create_vendor', 'check_create_vendor_ajax');

/**
 * Modifica los elementos del menú para mostrar u ocultar el botón de login
 * según el estado de autenticación del usuario.
 * 
 * @param string $items HTML de los elementos del menú.
 * @param object $args Argumentos del menú.
 * @return string HTML modificado de los elementos del menú.
 */
function wp_alp_modify_menu_items($items, $args) {
    // Si el usuario está autenticado, quitar el botón de login
    if (is_user_logged_in()) {
        // Buscar y eliminar el ítem de menú con clase wp-alp-login-trigger o data-wp-alp-trigger="login"
        $items = preg_replace(
            '/<li[^>]*class="[^"]*wp-alp-login-trigger[^"]*".*?<\/li>/i', 
            '', 
            $items
        );
        
        $items = preg_replace(
            '/<li[^>]*><a[^>]*data-wp-alp-trigger="login"[^>]*>.*?<\/a><\/li>/i', 
            '', 
            $items
        );
    }
    
    return $items;
}
add_filter('wp_nav_menu_items', 'wp_alp_modify_menu_items', 10, 2);

/**
 * Los estilos del botón de login se han movido al archivo custom-alp-styles.css
 * para mejorar el rendimiento y la mantenibilidad.
 */
function wp_alp_add_login_button_styles() {
    // Los estilos ahora están en el archivo CSS correspondiente
    // y se cargan mediante wp_enqueue_style
}
// La acción se mantiene por retrocompatibilidad pero no hace nada
add_action('wp_head', 'wp_alp_add_login_button_styles');

/**
 * Modifica la clase del cuerpo cuando se está en una página de vendor.
 * Agrega una clase específica para mejorar los estilos CSS.
 * 
 * @param array $classes Clases existentes.
 * @return array Clases modificadas.
 */
function wp_alp_add_body_classes($classes) {
    // Comprobar si estamos en una página de vendor
    if (is_page_template('templates/vendor-steps-template.php')) {
        $classes[] = 'wp-alp-vendor-page';
        $classes[] = 'wp-alp-template-page';
    }
    
    return $classes;
}
add_filter('body_class', 'wp_alp_add_body_classes');

/**
 * Enqueue estilos adicionales para las páginas de vendedor.
 * Estos estilos mejoran la apariencia de las secciones de listing.
 */
function wp_alp_enqueue_vendor_styles() {
    if (is_page_template('templates/vendor-steps-template.php') || 
        is_page_template('templates/vendor-page-template.php')) {
        
        wp_enqueue_style(
            'wp-alp-vendor-styles',
            plugin_dir_url(dirname(__FILE__)) . 'public/css/custom-alp-styles.css',
            array(),
            WP_ALP_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'wp_alp_enqueue_vendor_styles', 999); // Prioridad alta para sobrescribir otros estilos

/**
 * AJAX Endpoints para el sistema de login estilo Airbnb
 */

/**
 * Obtiene el formulario inicial de login
 */
function wp_alp_get_initial_form_ajax() {
    // Verificar nonce
    if (!check_ajax_referer('wp_alp_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => __('Error de seguridad. Recarga la página.', 'wp-alp')
        ));
        return;
    }

    // Crear instancia de la clase de formularios
    $forms = new WP_ALP_Forms();
    $html = $forms->get_initial_form();

    wp_send_json_success(array(
        'html' => $html
    ));
}
add_action('wp_ajax_wp_alp_get_initial_form', 'wp_alp_get_initial_form_ajax');
add_action('wp_ajax_nopriv_wp_alp_get_initial_form', 'wp_alp_get_initial_form_ajax');

/**
 * Valida si existe un usuario con el email/teléfono proporcionado
 */
function wp_alp_validate_user_ajax() {
    // Verificar nonce
    if (!check_ajax_referer('wp_alp_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => __('Error de seguridad. Recarga la página.', 'wp-alp')
        ));
        return;
    }

    $identifier = sanitize_text_field($_POST['identifier'] ?? '');
    
    if (empty($identifier)) {
        wp_send_json_error(array(
            'message' => __('Email o teléfono requerido.', 'wp-alp')
        ));
        return;
    }

    // Verificar si es email o teléfono
    $is_email = is_email($identifier);
    $user_exists = false;

    if ($is_email) {
        // Buscar por email en usuarios de WordPress
        $user = get_user_by('email', $identifier);
        $user_exists = ($user !== false);
        
        // Si no existe en WordPress, buscar en leads de JetEngine
        if (!$user_exists && class_exists('WP_ALP_JetEngine')) {
            $jetengine = new WP_ALP_JetEngine();
            $lead = $jetengine->find_lead_by_email($identifier);
            $user_exists = ($lead !== false);
        }
    } else {
        // Buscar por teléfono en usuarios de WordPress
        $users = get_users(array(
            'meta_key' => 'phone',
            'meta_value' => $identifier,
            'number' => 1
        ));
        $user_exists = !empty($users);
        
        // Si no existe en WordPress, buscar en leads de JetEngine
        if (!$user_exists && class_exists('WP_ALP_JetEngine')) {
            $jetengine = new WP_ALP_JetEngine();
            $lead = $jetengine->find_lead_by_phone($identifier);
            $user_exists = ($lead !== false);
        }
    }

    // Flujo simplificado sin verificación SMS
    wp_send_json_success(array(
        'user_exists' => $user_exists,
        'is_email' => $is_email,
        'identifier' => $identifier,
        'needs_verification' => false
    ));
}
add_action('wp_ajax_wp_alp_validate_user', 'wp_alp_validate_user_ajax');
add_action('wp_ajax_nopriv_wp_alp_validate_user', 'wp_alp_validate_user_ajax');

/*
 * FUNCIONES DE VERIFICACIÓN SMS DESHABILITADAS
 * Comentado para usar flujo de registro normal
 */

/*
function wp_alp_send_phone_verification_ajax() {
    // Verificar nonce
    if (!check_ajax_referer('wp_alp_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => __('Error de seguridad. Recarga la página.', 'wp-alp')
        ));
        return;
    }

    $phone_full = sanitize_text_field($_POST['phone_full'] ?? '');
    $country_code = sanitize_text_field($_POST['country_code'] ?? '');
    $phone_number = sanitize_text_field($_POST['phone_number'] ?? '');
    $method = sanitize_text_field($_POST['method'] ?? 'sms');

    if (empty($phone_full)) {
        wp_send_json_error(array(
            'message' => __('Número de teléfono requerido.', 'wp-alp')
        ));
        return;
    }

    // Validar método
    if (!in_array($method, ['sms', 'whatsapp', 'call'])) {
        $method = 'sms';
    }

    // Instanciar verificador
    if (!class_exists('WP_ALP_Phone_Verification')) {
        require_once plugin_dir_path(__FILE__) . 'class-wp-alp-phone-verification.php';
    }

    $verifier = new WP_ALP_Phone_Verification();
    $result = $verifier->send_verification_code($phone_full, $country_code, $phone_number, $method);

    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);
    }
}
add_action('wp_ajax_wp_alp_send_phone_verification', 'wp_alp_send_phone_verification_ajax');
add_action('wp_ajax_nopriv_wp_alp_send_phone_verification', 'wp_alp_send_phone_verification_ajax');
*/

/*
function wp_alp_verify_phone_code_ajax() {
    // Verificar nonce
    if (!check_ajax_referer('wp_alp_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => __('Error de seguridad. Recarga la página.', 'wp-alp')
        ));
        return;
    }

    $phone_full = sanitize_text_field($_POST['phone_full'] ?? '');
    $code = sanitize_text_field($_POST['code'] ?? '');

    if (empty($phone_full) || empty($code)) {
        wp_send_json_error(array(
            'message' => __('Teléfono y código requeridos.', 'wp-alp')
        ));
        return;
    }

    // Instanciar verificador
    if (!class_exists('WP_ALP_Phone_Verification')) {
        require_once plugin_dir_path(__FILE__) . 'class-wp-alp-phone-verification.php';
    }

    $verifier = new WP_ALP_Phone_Verification();
    $result = $verifier->verify_code($phone_full, $code);

    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);
    }
}
add_action('wp_ajax_wp_alp_verify_phone_code', 'wp_alp_verify_phone_code_ajax');
add_action('wp_ajax_nopriv_wp_alp_verify_phone_code', 'wp_alp_verify_phone_code_ajax');

function wp_alp_resend_verification_ajax() {
    // Verificar nonce
    if (!check_ajax_referer('wp_alp_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => __('Error de seguridad. Recarga la página.', 'wp-alp')
        ));
        return;
    }

    $phone_full = sanitize_text_field($_POST['phone_full'] ?? '');
    $country_code = sanitize_text_field($_POST['country_code'] ?? '');
    $phone_number = sanitize_text_field($_POST['phone_number'] ?? '');
    $method = sanitize_text_field($_POST['method'] ?? 'sms');

    if (empty($phone_full)) {
        wp_send_json_error(array(
            'message' => __('Número de teléfono requerido.', 'wp-alp')
        ));
        return;
    }

    // Instanciar verificador
    if (!class_exists('WP_ALP_Phone_Verification')) {
        require_once plugin_dir_path(__FILE__) . 'class-wp-alp-phone-verification.php';
    }

    $verifier = new WP_ALP_Phone_Verification();
    $result = $verifier->send_verification_code($phone_full, $country_code, $phone_number, $method);

    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);
    }
}
add_action('wp_ajax_wp_alp_resend_verification', 'wp_alp_resend_verification_ajax');
add_action('wp_ajax_nopriv_wp_alp_resend_verification', 'wp_alp_resend_verification_ajax');
*/

/**
 * Obtiene el formulario de login
 */
function wp_alp_get_login_form_ajax() {
    // Verificar nonce
    if (!check_ajax_referer('wp_alp_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => __('Error de seguridad. Recarga la página.', 'wp-alp')
        ));
        return;
    }

    $email = sanitize_email($_POST['email'] ?? '');
    
    if (empty($email)) {
        wp_send_json_error(array(
            'message' => __('Email requerido.', 'wp-alp')
        ));
        return;
    }

    // Crear instancia de la clase de formularios
    $forms = new WP_ALP_Forms();
    $html = $forms->get_login_form($email);

    wp_send_json_success(array(
        'html' => $html
    ));
}
add_action('wp_ajax_wp_alp_get_login_form', 'wp_alp_get_login_form_ajax');
add_action('wp_ajax_nopriv_wp_alp_get_login_form', 'wp_alp_get_login_form_ajax');

/**
 * Obtiene el formulario de registro
 */
function wp_alp_get_register_form_ajax() {
    // Verificar nonce
    if (!check_ajax_referer('wp_alp_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => __('Error de seguridad. Recarga la página.', 'wp-alp')
        ));
        return;
    }

    $email = sanitize_email($_POST['email'] ?? '');
    
    if (empty($email)) {
        wp_send_json_error(array(
            'message' => __('Email requerido.', 'wp-alp')
        ));
        return;
    }

    // Crear instancia de la clase de formularios
    $forms = new WP_ALP_Forms();
    $html = $forms->get_register_form($email);

    wp_send_json_success(array(
        'html' => $html
    ));
}
add_action('wp_ajax_wp_alp_get_register_form', 'wp_alp_get_register_form_ajax');
add_action('wp_ajax_nopriv_wp_alp_get_register_form', 'wp_alp_get_register_form_ajax');

/**
 * Procesa el login de usuario
 */
function wp_alp_login_ajax() {
    // Verificar nonce
    if (!check_ajax_referer('wp_alp_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => __('Error de seguridad. Recarga la página.', 'wp-alp')
        ));
        return;
    }

    $email = sanitize_email($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) && $_POST['remember'];

    if (empty($email) || empty($password)) {
        wp_send_json_error(array(
            'message' => __('Email y contraseña son requeridos.', 'wp-alp')
        ));
        return;
    }

    $credentials = array(
        'user_login' => $email,
        'user_password' => $password,
        'remember' => $remember
    );

    $user = wp_signon($credentials, false);

    if (is_wp_error($user)) {
        wp_send_json_error(array(
            'message' => __('Email o contraseña incorrectos.', 'wp-alp')
        ));
        return;
    }

    wp_send_json_success(array(
        'message' => __('¡Bienvenido! Redirigiendo...', 'wp-alp'),
        'redirect' => home_url()
    ));
}
add_action('wp_ajax_wp_alp_login', 'wp_alp_login_ajax');
add_action('wp_ajax_nopriv_wp_alp_login', 'wp_alp_login_ajax');

/**
 * Procesa el registro de usuario
 */
function wp_alp_register_ajax() {
    // Verificar nonce
    if (!check_ajax_referer('wp_alp_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => __('Error de seguridad. Recarga la página.', 'wp-alp')
        ));
        return;
    }

    $email = sanitize_email($_POST['email'] ?? '');
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name = sanitize_text_field($_POST['last_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $birthdate = sanitize_text_field($_POST['birthdate'] ?? '');

    // Validaciones
    if (empty($email) || empty($first_name) || empty($last_name) || empty($password)) {
        wp_send_json_error(array(
            'message' => __('Todos los campos obligatorios deben ser completados.', 'wp-alp')
        ));
        return;
    }

    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => __('Email no válido.', 'wp-alp')
        ));
        return;
    }

    if (username_exists($email) || email_exists($email)) {
        wp_send_json_error(array(
            'message' => __('Este email ya está registrado.', 'wp-alp')
        ));
        return;
    }

    if (strlen($password) < 6) {
        wp_send_json_error(array(
            'message' => __('La contraseña debe tener al menos 6 caracteres.', 'wp-alp')
        ));
        return;
    }

    // Crear usuario
    $user_id = wp_create_user($email, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array(
            'message' => __('Error al crear la cuenta. Inténtalo de nuevo.', 'wp-alp')
        ));
        return;
    }

    // Agregar meta datos del usuario
    update_user_meta($user_id, 'first_name', $first_name);
    update_user_meta($user_id, 'last_name', $last_name);
    if (!empty($phone)) {
        update_user_meta($user_id, 'phone', $phone);
    }
    if (!empty($birthdate)) {
        update_user_meta($user_id, 'birthdate', $birthdate);
    }

    // Datos del evento si están presentes
    $event_data = array();
    if (!empty($_POST['event_type'])) {
        $event_data['event_type'] = sanitize_text_field($_POST['event_type']);
    }
    if (!empty($_POST['event_date'])) {
        $event_data['event_date'] = sanitize_text_field($_POST['event_date']);
    }
    if (!empty($_POST['event_address'])) {
        $event_data['event_address'] = sanitize_text_field($_POST['event_address']);
    }
    if (!empty($_POST['guests'])) {
        $event_data['guests'] = intval($_POST['guests']);
    }
    if (!empty($_POST['details'])) {
        $event_data['details'] = sanitize_textarea_field($_POST['details']);
    }

    if (!empty($event_data)) {
        update_user_meta($user_id, 'event_info', $event_data);
    }

    // Login automático después del registro
    $credentials = array(
        'user_login' => $email,
        'user_password' => $password,
        'remember' => true
    );

    $user = wp_signon($credentials, false);

    if (is_wp_error($user)) {
        wp_send_json_error(array(
            'message' => __('Cuenta creada, pero error al iniciar sesión. Por favor, inicia sesión manualmente.', 'wp-alp')
        ));
        return;
    }

    wp_send_json_success(array(
        'message' => __('¡Cuenta creada exitosamente! Redirigiendo...', 'wp-alp'),
        'redirect' => home_url()
    ));
}
add_action('wp_ajax_wp_alp_register', 'wp_alp_register_ajax');
add_action('wp_ajax_nopriv_wp_alp_register', 'wp_alp_register_ajax');