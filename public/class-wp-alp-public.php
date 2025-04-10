<?php
/**
 * Funcionalidad específica del lado público del plugin.
 *
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/public
 */

/**
 * Clase que maneja la funcionalidad del lado público.
 */
class WP_ALP_Public {

    /**
     * El ID del plugin.
     */
    private $plugin_name;

    /**
     * La versión actual del plugin.
     */
    private $version;

    /**
     * Inicializa la clase y establece sus propiedades.
     *
     * @param string $plugin_name El nombre del plugin.
     * @param string $version La versión del plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
 * Registra los estilos para el lado público.
 */
public function enqueue_styles() {
    wp_enqueue_style(
        $this->plugin_name,
        plugin_dir_url(__FILE__) . 'css/wp-alp-public.css',
        array(),
        $this->version,
        'all'
    );
    
    // Añadir estilos personalizados para sobreescribir conflictos
    wp_enqueue_style(
        $this->plugin_name . '-custom',
        plugin_dir_url(__FILE__) . 'css/custom-alp-styles.css',
        array($this->plugin_name),
        $this->version,
        'all'
    );
}

    /**
     * Registra los scripts para el lado público.
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/wp-alp-public.js',
            array('jquery'),
            $this->version,
            false
        );

        // Si está habilitado Social Login
if (get_option('wp_alp_enable_social_login', true)) {
    wp_enqueue_script(
        $this->plugin_name . '-social',
        plugin_dir_url(__FILE__) . 'js/social-login.js',
        array('jquery', $this->plugin_name), // Añadir dependencia al script principal
        $this->version,
        true
    );
}
        
wp_localize_script($this->plugin_name, 'wp_alp_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('wp_alp_nonce'),
    'home_url' => home_url(),
    'plugin_url' => plugin_dir_url(__FILE__),
    'enable_social' => get_option('wp_alp_enable_social_login', true),
    'enable_phone' => get_option('wp_alp_enable_phone_login', true),
    'google_client_id' => get_option('wp_alp_google_client_id', ''),
    'facebook_app_id' => get_option('wp_alp_facebook_app_id', ''),
    'apple_client_id' => get_option('wp_alp_apple_client_id', ''),
    'translations' => array(
                'error' => __('Error', 'wp-alp'),
                'success' => __('Éxito', 'wp-alp'),
                'loading' => __('Cargando...', 'wp-alp'),
                'invalid_email' => __('Por favor, introduce un email válido.', 'wp-alp'),
                'invalid_phone' => __('Por favor, introduce un número de teléfono válido.', 'wp-alp'),
                'required_field' => __('Este campo es obligatorio.', 'wp-alp'),
                'password_short' => __('La contraseña debe tener al menos 6 caracteres.', 'wp-alp'),
                'verify_code' => __('Por favor, introduce el código de verificación completo.', 'wp-alp'),
            ),
        ));
        
        // Si está habilitado Google reCAPTCHA
        if (get_option('wp_alp_enable_captcha', false)) {
            $site_key = get_option('wp_alp_recaptcha_site_key', '');
            if (!empty($site_key)) {
                wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true);
            }
        }
        
// Si está habilitado Social Login
if (get_option('wp_alp_enable_social_login', true)) {
    // Google Login - Usar la URL correcta
    if (!empty(get_option('wp_alp_google_client_id', ''))) {
        // Ya no cargamos scripts específicos aquí, lo hacemos en wp_footer
    }
    
    // Facebook Login
    if (!empty(get_option('wp_alp_facebook_app_id', ''))) {
        // Mover la carga del script a nuestro código personalizado
    }
    
    // Apple Login
    if (!empty(get_option('wp_alp_apple_client_id', ''))) {
        wp_enqueue_script('apple-api', 'https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js', array(), null, true);
    }
}
    }

    /**
     * Registra los shortcodes del plugin.
     */
    public function register_shortcodes() {
        add_shortcode('wp_alp_login_button', array($this, 'login_button_shortcode'));
        add_shortcode('wp_alp_login_page', array($this, 'login_page_shortcode'));

    }

    /**
 * Shortcode para mostrar la página de login completa.
 *
 * @param array $atts Atributos del shortcode.
 * @return string HTML de la página de login.
 */
public function login_page_shortcode($atts) {
    $atts = shortcode_atts(array(
        'redirect' => '',
    ), $atts, 'wp_alp_login_page');
    
    // Si el usuario ya está logueado y no necesita completar perfil
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $user_type = get_user_meta($user_id, 'wp_alp_user_type', true);
        $profile_status = get_user_meta($user_id, 'wp_alp_profile_status', true);
        
        // Si es subscriber con perfil incompleto, mostrar formulario de completar perfil
        if (current_user_can('subscriber') && ($user_type === '' || $profile_status === 'incomplete')) {
            ob_start();
            include(plugin_dir_path(__FILE__) . 'templates/login-page-template.php');
            return ob_get_clean();
        }
        
        // Si hay redirect_to, redirigir allí
        if (isset($_GET['redirect_to']) && !empty($_GET['redirect_to'])) {
            $redirect_url = esc_url_raw($_GET['redirect_to']);
            echo '<script>window.location.href = "' . $redirect_url . '";</script>';
            return '<p>' . __('Redirigiendo...', 'wp-alp') . '</p>';
        }
        
        // Si no hay redirect, mostrar mensaje
        return '<p>' . __('Ya has iniciado sesión.', 'wp-alp') . ' <a href="' . esc_url(home_url()) . '">' . __('Ir a la página principal', 'wp-alp') . '</a></p>';
    }
    
    // Cargar la plantilla de página de login
    ob_start();
    include(plugin_dir_path(__FILE__) . 'templates/login-page-template.php');
    return ob_get_clean();
}

    /**
     * Shortcode para mostrar un botón de inicio de sesión.
     *
     * @param array $atts Atributos del shortcode.
     * @return string HTML del botón.
     */
    public function login_button_shortcode($atts) {
        $atts = shortcode_atts(array(
            'text' => __('Iniciar sesión', 'wp-alp'),
            'class' => '',
        ), $atts, 'wp_alp_login_button');
        
        $button_class = 'wp-alp-login-button';
        if (!empty($atts['class'])) {
            $button_class .= ' ' . esc_attr($atts['class']);
        }
        
        return '<button type="button" class="' . $button_class . '" data-wp-alp-trigger="login">' . esc_html($atts['text']) . '</button>';
    }

    /**
 * Outputea el script de inicialización social en el footer.
 */
public function initialize_social_scripts() {
    if (get_option('wp_alp_enable_social_login', true)) {
        ?>
        <script>
            jQuery(document).ready(function($) {
                $(document).on('wp_alp_modal_opened', function() {
                    if (typeof window.socialLoginModalOpened === 'function') {
                        window.socialLoginModalOpened();
                    }
                });
            });
        </script>
        <?php
    }
}

    /**
     * Genera y añade el modal de login al pie de página.
     */
    public function output_login_modal() {
        echo WP_ALP_Forms::get_modal_container();
    }

    /**
     * Valida la existencia de un usuario vía AJAX.
     */
    public function validate_user_ajax() {
        check_ajax_referer('wp_alp_nonce', 'nonce');
        
        if (!isset($_POST['identifier']) || empty($_POST['identifier'])) {
            wp_send_json_error(array(
                'message' => __('Por favor, introduce un correo electrónico o número de teléfono.', 'wp-alp'),
            ));
        }
        
        $identifier = sanitize_text_field($_POST['identifier']);
        $result = WP_ALP_Forms::process_check_user($identifier);
        
        if ($result['exists']) {
            // El usuario existe
            $data = array(
                'exists' => true,
                'user_id' => $result['user_id'],
                'user_type' => $result['user_type'],
                'profile_status' => $result['profile_status'],
                'found_by' => $result['found_by'],
                'html' => WP_ALP_Forms::get_login_form($identifier),
            );
            
            // Obtener el usuario de WordPress
            $user = get_user_by('ID', $result['user_id']);
            
            // Comprobar si es un subscriber con perfil incompleto - SOLO PARA REFERENCIA
            // en esta implementación, no mostramos el formulario de perfil hasta después del login
            if ($result['found_by'] === 'email' && 
                (in_array('subscriber', (array)$user->roles) || $result['user_type'] === 'subscriber') && 
                $result['profile_status'] === 'incomplete') {
                // Añadir flag pero NO cambiar el HTML aún
                $data['needs_profile'] = true;
            }
            
            // Filtro para modificar el resultado (usado desde functions.php)
            $data = apply_filters('wp_alp_validate_user_result', $data, $result);
            
            wp_send_json_success($data);
        } else {
            // El usuario no existe, mostrar formulario de registro
            wp_send_json_success(array(
                'exists' => false,
                'html' => WP_ALP_Forms::get_register_form($identifier),
            ));
        }
    }
    

    /**
     * Registra un nuevo usuario vía AJAX.
     */
    public function register_user_ajax() {
        check_ajax_referer('wp_alp_nonce', 'nonce');
        
        $required_fields = array('first_name', 'last_name', 'email', 'password', 'phone', 'event_type', 'event_date');
        
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                wp_send_json_error(array(
                    'message' => sprintf(__('El campo %s es obligatorio.', 'wp-alp'), $field),
                ));
            }
        }
        
        $data = array();
        foreach ($_POST as $key => $value) {
            $data[$key] = sanitize_text_field($value);
        }
        
        $result = WP_ALP_Forms::process_register_form($data);
        
        if ($result['success']) {
            $response = array(
                'success' => true,
                'message' => $result['message'],
                'user_id' => $result['user_id'],
            );
            
            if (isset($result['needs_verification']) && $result['needs_verification']) {
                $response['needs_verification'] = true;
                $response['html'] = WP_ALP_Forms::get_verification_form($data['email'], $result['user_id']);
            }
            
            wp_send_json_success($response);
        } else {
            wp_send_json_error(array(
                'message' => $result['message'],
            ));
        }
    }

    /**
     * Autentica a un usuario vía AJAX.
     */
    public function login_user_ajax() {
        check_ajax_referer('wp_alp_nonce', 'nonce');
        
        if (!isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['password']) || empty($_POST['password'])) {
            wp_send_json_error(array(
                'message' => __('El correo electrónico y la contraseña son obligatorios.', 'wp-alp'),
            ));
        }
        
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password']; // No sanitizar la contraseña
        
        $result = WP_ALP_Forms::process_login_form($email, $password);
        
        if ($result['success']) {
            $response = array(
                'success' => true,
                'message' => $result['message'],
                'user_id' => $result['user_id'],
            );
            
            // Verificar si el usuario necesita completar perfil
            $user = get_user_by('ID', $result['user_id']);
            $user_type = get_user_meta($result['user_id'], 'wp_alp_user_type', true);
            $profile_status = get_user_meta($result['user_id'], 'wp_alp_profile_status', true);
            
            if ($user && 
                (in_array('subscriber', (array)$user->roles) || $user_type === 'subscriber') && 
                $profile_status === 'incomplete') {
                
                // Usuario necesita completar su perfil
                $response['needs_profile'] = true;
                $response['html'] = WP_ALP_Forms::get_profile_completion_form($result['user_id']);
            } else {
                $response['redirect'] = get_option('wp_alp_redirect_after_login', home_url());
            }
            
            // Filtro para modificar el resultado (usado desde functions.php)
            $response = apply_filters('wp_alp_login_user_result', $response, $result['user_id']);
            
            wp_send_json_success($response);
        } else {
            wp_send_json_error(array(
                'message' => $result['message'],
            ));
        }
    }

    /**
     * Verifica un código de verificación vía AJAX.
     */
    public function verify_code_ajax() {
        check_ajax_referer('wp_alp_nonce', 'nonce');
        
        if (!isset($_POST['code']) || empty($_POST['code']) || !isset($_POST['user_id']) || empty($_POST['user_id'])) {
            wp_send_json_error(array(
                'message' => __('El código de verificación y el ID de usuario son obligatorios.', 'wp-alp'),
            ));
        }
        
        $code = sanitize_text_field($_POST['code']);
        $user_id = intval($_POST['user_id']);
        
        $result = WP_ALP_Forms::process_verification_code($user_id, $code);
        
        if ($result['success']) {
            // Verificar si el usuario necesita completar perfil
            $user_type = get_user_meta($user_id, 'wp_alp_user_type', true);
            $profile_status = get_user_meta($user_id, 'wp_alp_profile_status', true);
            
            $response = array(
                'success' => true,
                'message' => $result['message'],
            );
            
            if ($user_type === '' || $profile_status === 'incomplete') {
                $response['needs_profile'] = true;
                $response['html'] = WP_ALP_Forms::get_profile_completion_form($user_id);
            } else {
                $response['redirect'] = get_option('wp_alp_redirect_after_login', home_url());
            }
            
            wp_send_json_success($response);
        } else {
            wp_send_json_error(array(
                'message' => $result['message'],
            ));
        }
    }

    /**
     * Reenvía un código de verificación vía AJAX.
     */
    public function resend_code_ajax() {
        check_ajax_referer('wp_alp_nonce', 'nonce');
        
        if (!isset($_POST['user_id']) || empty($_POST['user_id'])) {
            wp_send_json_error(array(
                'message' => __('El ID de usuario es obligatorio.', 'wp-alp'),
            ));
        }
        
        $user_id = intval($_POST['user_id']);
        
        $result = WP_ALP_Forms::process_resend_code($user_id);
        
        if ($result['success']) {
            wp_send_json_success(array(
                'message' => $result['message'],
            ));
        } else {
            wp_send_json_error(array(
                'message' => $result['message'],
            ));
        }
    }

/**
 * Completa el perfil de un usuario vía AJAX.
 */
public function complete_profile_ajax() {
    // Intentar verificar el nonce
    $nonce_verified = check_ajax_referer('wp_alp_nonce', 'nonce', false);
    
    // Si el nonce falla pero el usuario está logueado, permitir la acción
    if (!$nonce_verified && !is_user_logged_in()) {
        wp_send_json_error(array(
            'message' => __('Error de seguridad. Actualiza la página e intenta nuevamente.', 'wp-alp'),
        ));
        return;
    }
    
    $required_fields = array('user_id', 'event_type', 'event_date', 'event_address', 'guests');
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            wp_send_json_error(array(
                'message' => sprintf(__('El campo %s es obligatorio.', 'wp-alp'), $field),
            ));
            return;
        }
    }
    
    $data = array();
    foreach ($_POST as $key => $value) {
        $data[$key] = sanitize_text_field($value);
    }
    
    $user_manager = new WP_ALP_User_Manager();
    $result = $user_manager->complete_user_profile($data['user_id'], $data);
    
    if ($result['success']) {
        // Actualizar el rol del usuario a 'lead'
        $user = get_user_by('ID', $data['user_id']);
        if ($user) {
            $user->set_role('lead'); // Cambiar el rol a 'lead'
        }
        
        wp_send_json_success(array(
            'success' => true,
            'message' => $result['message'],
            'redirect' => get_option('wp_alp_redirect_after_login', home_url()),
        ));
    }
    else {
        // Registrar el error para debugging
        error_log('Error al completar perfil: ' . json_encode($result));
        
        wp_send_json_error(array(
            'message' => $result['message'],
        ));
    }
}

   /**
 * Maneja el login social vía AJAX.
 */
public function social_login_ajax() {
    error_log('WP_ALP: Procesando social_login_ajax con proveedor: ' . $_POST['provider']);

    check_ajax_referer('wp_alp_nonce', 'nonce');
    
    if (!isset($_POST['provider']) || empty($_POST['provider']) || !isset($_POST['token']) || empty($_POST['token'])) {
        wp_send_json_error(array(
            'message' => __('El proveedor y el token son obligatorios.', 'wp-alp'),
        ));
    }
    
    $provider = sanitize_text_field($_POST['provider']);
    $token = sanitize_text_field($_POST['token']);
    
    $data = array(
        'provider' => $provider,
        'token' => $token,
    );
    
    // Si es Apple, puede incluir datos adicionales
    if ($provider === 'apple' && isset($_POST['first_name']) && isset($_POST['last_name'])) {
        $data['first_name'] = sanitize_text_field($_POST['first_name']);
        $data['last_name'] = sanitize_text_field($_POST['last_name']);
    }
    
    $social = new WP_ALP_Social();
    $result = $social->process_social_data_ajax($data);
    
    if ($result['success']) {
        // Generar un nuevo nonce para el usuario autenticado
        $new_nonce = wp_create_nonce('wp_alp_nonce');
        
        $response = array(
            'success' => true,
            'message' => $result['message'],
            'user_id' => isset($result['user_id']) ? $result['user_id'] : 0,
            'new_nonce' => $new_nonce  // Incluir el nuevo nonce en la respuesta
        );
        
        if (isset($result['needs_profile']) && $result['needs_profile']) {
            $response['needs_profile'] = true;
            
            // Si el usuario necesita completar perfil, obtener el HTML del formulario
            if (!empty($result['user_id'])) {
                $forms = new WP_ALP_Forms();
                $response['html'] = $forms->get_profile_completion_form($result['user_id']);
            }
            
            $response['redirect'] = $result['redirect_url'];
        } else {
            $response['redirect'] = $result['redirect_url'];
        }
        
        wp_send_json_success($response);
    } else {
        wp_send_json_error(array(
            'message' => $result['message'],
        ));
        error_log('WP_ALP: Error en social_login_ajax: ' . $result['message']);
    }
}

/**
 * Devuelve el HTML del formulario solicitado vía AJAX.
 */
public function get_form_ajax() {
    // Registrar información para debugging
    error_log('WP_ALP: Solicitud get_form_ajax - Datos: ' . json_encode($_POST));
    
    // Verificar nonce de forma explícita
    if (!wp_verify_nonce($_POST['nonce'], 'wp_alp_nonce')) {
        error_log('WP_ALP: Error de verificación de nonce en get_form_ajax');
        error_log('WP_ALP: Nonce recibido: ' . $_POST['nonce']);
        
        // Enviar error específico
        wp_send_json_error(array(
            'message' => __('Error de verificación de seguridad. Por favor, recarga la página e intenta nuevamente.', 'wp-alp'),
            'code' => 'invalid_nonce'
        ), 403);
        return;
    }
    
    if (!isset($_POST['form']) || empty($_POST['form'])) {
        wp_send_json_error(array(
            'message' => __('Tipo de formulario no especificado.', 'wp-alp'),
        ));
    }
    
    $form = sanitize_text_field($_POST['form']);
    
    // Registrar más información para debugging
    error_log('WP_ALP: Procesando formulario tipo: ' . $form);
    
    switch ($form) {
        case 'profile':
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
            error_log('WP_ALP: Solicitud de formulario de perfil para usuario ID: ' . $user_id);
            
            if ($user_id > 0) {
                $user = get_user_by('ID', $user_id);
                if ($user) {
                    error_log('WP_ALP: Usuario encontrado, generando formulario de perfil');
                    $forms = new WP_ALP_Forms();
                    $html = $forms->get_profile_completion_form($user_id);
                    
                    wp_send_json_success(array(
                        'html' => $html,
                        'user_id' => $user_id
                    ));
                } else {
                    error_log('WP_ALP: Usuario no encontrado para ID: ' . $user_id);
                    wp_send_json_error(array(
                        'message' => __('Usuario no encontrado.', 'wp-alp'),
                    ));
                }
            } else {
                error_log('WP_ALP: ID de usuario no válido: ' . $user_id);
                wp_send_json_error(array(
                    'message' => __('ID de usuario no válido.', 'wp-alp'),
                ));
            }
            break;
            
        // Resto de casos aquí...
        
        default:
            wp_send_json_error(array(
                'message' => __('Tipo de formulario no válido.', 'wp-alp'),
            ));
            break;
    }
}
}