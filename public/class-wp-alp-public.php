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
        
        wp_localize_script($this->plugin_name, 'wp_alp_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_alp_nonce'),
            'home_url' => home_url(),
            'plugin_url' => plugin_dir_url(__FILE__),
            'enable_social' => get_option('wp_alp_enable_social_login', true),
            'enable_phone' => get_option('wp_alp_enable_phone_login', true),
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
            // Google Login
            if (!empty(get_option('wp_alp_google_client_id', ''))) {
                wp_enqueue_script('google-api', 'https://apis.google.com/js/platform.js', array(), null, true);
            }
            
            // Facebook Login
            if (!empty(get_option('wp_alp_facebook_app_id', ''))) {
                wp_enqueue_script('facebook-api', 'https://connect.facebook.net/en_US/sdk.js', array(), null, true);
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
            
            if ($result['found_by'] === 'email' && $result['user_type'] === 'subscriber' && $result['profile_status'] === 'incomplete') {
                // Usuario que necesita completar perfil
                $data['needs_profile'] = true;
                $data['html'] = WP_ALP_Forms::get_profile_completion_form($result['user_id']);
            }
            
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
            
            if (isset($result['needs_profile']) && $result['needs_profile']) {
                $response['needs_profile'] = true;
                $response['html'] = WP_ALP_Forms::get_profile_completion_form($result['user_id']);
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
        check_ajax_referer('wp_alp_nonce', 'nonce');
        
        $required_fields = array('user_id', 'event_type', 'event_date', 'event_address', 'guests');
        
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
        
        $result = WP_ALP_Forms::process_profile_form($data);
        
        if ($result['success']) {
            wp_send_json_success(array(
                'success' => true,
                'message' => $result['message'],
                'redirect' => get_option('wp_alp_redirect_after_login', home_url()),
            ));
        } else {
            wp_send_json_error(array(
                'message' => $result['message'],
            ));
        }
    }

    /**
     * Maneja el login social vía AJAX.
     */
    public function social_login_ajax() {
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
            $response = array(
                'success' => true,
                'message' => $result['message'],
            );
            
            if (isset($result['needs_profile']) && $result['needs_profile']) {
                $response['needs_profile'] = true;
                $response['redirect'] = $result['redirect_url'];
            } else {
                $response['redirect'] = $result['redirect_url'];
            }
            
            wp_send_json_success($response);
        } else {
            wp_send_json_error(array(
                'message' => $result['message'],
            ));
        }
    }

    /**
 * Devuelve el HTML del formulario solicitado vía AJAX.
 */
public function get_form_ajax() {
    check_ajax_referer('wp_alp_nonce', 'nonce');
    
    if (!isset($_POST['form']) || empty($_POST['form'])) {
        wp_send_json_error(array(
            'message' => __('Tipo de formulario no especificado.', 'wp-alp'),
        ));
    }
    
    $form = sanitize_text_field($_POST['form']);
    
    switch ($form) {
        case 'initial':
            wp_send_json_success(array(
                'html' => WP_ALP_Forms::get_initial_form()
            ));
            break;
        case 'phone':
            wp_send_json_success(array(
                'html' => WP_ALP_Forms::get_phone_form()
            ));
            break;
        default:
            wp_send_json_error(array(
                'message' => __('Tipo de formulario no válido.', 'wp-alp'),
            ));
            break;
    }
}
}