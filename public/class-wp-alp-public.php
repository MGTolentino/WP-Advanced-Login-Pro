<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/public
 */

class WP_ALP_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The security manager instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      WP_ALP_Security    $security    The security manager instance.
     */
    private $security;

    /**
     * The social login manager instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      WP_ALP_Social    $social    The social login manager instance.
     */
    private $social;

    /**
     * The user manager instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      WP_ALP_User_Manager    $user_manager    The user manager instance.
     */
    private $user_manager;

    /**
     * The JetEngine integration instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      WP_ALP_JetEngine    $jetengine    The JetEngine integration instance.
     */
    private $jetengine;

    /**
     * The forms manager instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      WP_ALP_Forms    $forms    The forms manager instance.
     */
    private $forms;

    /**
     * URL de redirección capturada.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $redirect_url    URL de redirección capturada.
     */
    private $redirect_url;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string              $plugin_name       The name of the plugin.
     * @param    string              $version           The version of the plugin.
     * @param    WP_ALP_Security     $security          The security manager instance.
     * @param    WP_ALP_Social       $social            The social login manager instance.
     * @param    WP_ALP_User_Manager $user_manager      The user manager instance.
     * @param    WP_ALP_JetEngine    $jetengine         The JetEngine integration instance.
     */
    public function __construct($plugin_name, $version, $security, $social, $user_manager, $jetengine) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->security = $security;
        $this->social = $social;
        $this->user_manager = $user_manager;
        $this->jetengine = $jetengine;

        // Initialize forms manager
        $this->forms = new WP_ALP_Forms($security, $social);
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/wp-alp-public.css',
            array(),
            $this->version,
            'all'
        );
        
        // Add dashicons for the toggle password icon and loading spinner
        wp_enqueue_style('dashicons');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/wp-alp-public.js',
            array('jquery'),
            $this->version,
            false
        );

        wp_localize_script(
            $this->plugin_name,
            'wp_alp_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'home_url' => home_url(),
                'login_url' => $this->get_login_page_url(),
                'nonce' => wp_create_nonce('wp_alp_public_nonce'),
                'strong_password' => __('Strong', 'wp-alp'),
                'medium_password' => __('Medium', 'wp-alp'),
                'weak_password' => __('Weak', 'wp-alp'),
                'password_mismatch' => __('Passwords do not match', 'wp-alp'),
                'ajax_error' => __('An error occurred. Please try again.', 'wp-alp'),
                'social_login_error' => __('Social login failed. Please try again.', 'wp-alp'),
            )
        );
    }

    /**
     * Register shortcodes.
     *
     * @since    1.0.0
     */
    public function register_shortcodes() {
        add_shortcode('wp_alp_login', array($this, 'login_shortcode'));
        add_shortcode('wp_alp_register_user', array($this, 'register_user_shortcode'));
        add_shortcode('wp_alp_register_vendor', array($this, 'register_vendor_shortcode'));
        add_shortcode('wp_alp_profile_completion', array($this, 'profile_completion_shortcode'));
        
        // Add new shortcodes for modal triggers
        add_shortcode('wp_alp_login_button', array($this, 'login_button_shortcode'));
        add_shortcode('wp_alp_register_button', array($this, 'register_button_shortcode'));
    }

    /**
     * Login form shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            The rendered form.
     */
    public function login_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'redirect' => '',
            'show_title' => 'true',
            'show_social' => 'true',
        ), $atts, 'wp_alp_login');

        return $this->forms->render_login_form($atts);
    }

    /**
     * Login button shortcode that triggers the modal.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            The rendered button.
     */
    public function login_button_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'text' => __('Login', 'wp-alp'),
            'class' => '',
        ), $atts, 'wp_alp_login_button');
        
        if (is_user_logged_in()) {
            return '';
        }
        
        return '<a href="#" class="wp-alp-button wp-alp-login-trigger ' . esc_attr($atts['class']) . '">' . esc_html($atts['text']) . '</a>';
    }

    /**
     * Register button shortcode that triggers the modal.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            The rendered button.
     */
    public function register_button_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'text' => __('Register', 'wp-alp'),
            'class' => '',
        ), $atts, 'wp_alp_register_button');
        
        if (is_user_logged_in()) {
            return '';
        }
        
        return '<a href="#" class="wp-alp-button wp-alp-register-trigger ' . esc_attr($atts['class']) . '">' . esc_html($atts['text']) . '</a>';
    }

    /**
     * User registration form shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            The rendered form.
     */
    public function register_user_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'redirect' => '',
            'show_title' => 'true',
            'show_social' => 'true',
        ), $atts, 'wp_alp_register_user');

        return $this->forms->render_register_user_form($atts);
    }

    /**
     * Vendor registration form shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            The rendered form.
     */
    public function register_vendor_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'redirect' => '',
            'show_title' => 'true',
            'show_social' => 'true',
        ), $atts, 'wp_alp_register_vendor');

        return $this->forms->render_register_vendor_form($atts);
    }

    /**
     * Profile completion form shortcode.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            The rendered form.
     */
    public function profile_completion_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'redirect' => '',
            'show_title' => 'true',
        ), $atts, 'wp_alp_profile_completion');

        return $this->forms->render_profile_completion_form($atts);
    }

    /**
     * Override default WordPress login.
     *
     * @since    1.0.0
     */
    public function override_wp_login() {
        $options = get_option('wp_alp_general_options');
        
        if (isset($options['override_wp_login']) && $options['override_wp_login']) {
            // Only if login page is set
            if (!empty($options['login_page'])) {
                global $pagenow;
                
                // Check if it's the login page
                if ($pagenow === 'wp-login.php' && !isset($_GET['action']) && !isset($_GET['key'])) {
                    // Get login page URL
                    $login_page = get_permalink($options['login_page']);
                    
                    // Set redirect parameter if needed
                    if (isset($_GET['redirect_to'])) {
                        $login_page = add_query_arg('redirect_to', urlencode($_GET['redirect_to']), $login_page);
                    }
                    
                    // Redirect to custom login page
                    wp_redirect($login_page);
                    exit;
                }
                
                // Allow wp-login.php for these actions
                $allowed_actions = array('logout', 'lostpassword', 'rp', 'resetpass');
                
                // Check if it's an allowed action
                if ($pagenow === 'wp-login.php' && isset($_GET['action']) && !in_array($_GET['action'], $allowed_actions)) {
                    // Get login page URL
                    $login_page = get_permalink($options['login_page']);
                    
                    // Redirect to custom login page
                    wp_redirect($login_page);
                    exit;
                }
            }
        }
    }

    /**
     * Handle form submissions.
     *
     * @since    1.0.0
     */
    public function handle_form_submissions() {
        // Process login form
        if (isset($_POST['action']) && $_POST['action'] === 'wp_alp_login') {
            $this->process_login_form();
        }
        
        // Process user registration form
        if (isset($_POST['action']) && $_POST['action'] === 'wp_alp_register_user') {
            $this->process_register_user_form();
        }
        
        // Process vendor registration form
        if (isset($_POST['action']) && $_POST['action'] === 'wp_alp_register_vendor') {
            $this->process_register_vendor_form();
        }
        
        // Process profile completion form
        if (isset($_POST['action']) && $_POST['action'] === 'wp_alp_complete_profile') {
            $this->process_profile_completion_form();
        }
    }

    /**
     * Process login form submission.
     *
     * @since    1.0.0
     */
    private function process_login_form() {
        $response = $this->forms->process_login($_POST);
        
        if (is_wp_error($response)) {
            // Redirect back with error
            $redirect_url = $_SERVER['HTTP_REFERER'];
            $redirect_url = add_query_arg('login_error', urlencode($response->get_error_message()), $redirect_url);
            wp_redirect($redirect_url);
            exit;
        } else {
            // Redirect to specified URL or home
            $redirect_url = isset($response['redirect']) ? $response['redirect'] : home_url();
            wp_redirect($redirect_url);
            exit;
        }
    }

    /**
     * Process user registration form submission.
     *
     * @since    1.0.0
     */
    private function process_register_user_form() {
        $response = $this->forms->process_user_registration($_POST, $this->user_manager);
        
        if (is_wp_error($response)) {
            // Redirect back with error
            $redirect_url = $_SERVER['HTTP_REFERER'];
            $redirect_url = add_query_arg('register_error', urlencode($response->get_error_message()), $redirect_url);
            wp_redirect($redirect_url);
            exit;
        } else {
            // Redirect to specified URL or home
            $redirect_url = isset($response['redirect']) ? $response['redirect'] : home_url();
            wp_redirect($redirect_url);
            exit;
        }
    }

    /**
     * Process vendor registration form submission.
     *
     * @since    1.0.0
     */
    private function process_register_vendor_form() {
        $response = $this->forms->process_vendor_registration($_POST, $this->user_manager);
        
        if (is_wp_error($response)) {
            // Redirect back with error
            $redirect_url = $_SERVER['HTTP_REFERER'];
            $redirect_url = add_query_arg('register_error', urlencode($response->get_error_message()), $redirect_url);
            wp_redirect($redirect_url);
            exit;
        } else {
            // Redirect to specified URL or home
            $redirect_url = isset($response['redirect']) ? $response['redirect'] : home_url();
            wp_redirect($redirect_url);
            exit;
        }
    }

    /**
     * Process profile completion form submission.
     *
     * @since    1.0.0
     */
    private function process_profile_completion_form() {
        $response = $this->forms->process_profile_completion($_POST, $this->user_manager, $this->jetengine);
        
        if (is_wp_error($response)) {
            // Redirect back with error
            $redirect_url = $_SERVER['HTTP_REFERER'];
            $redirect_url = add_query_arg('profile_error', urlencode($response->get_error_message()), $redirect_url);
            wp_redirect($redirect_url);
            exit;
        } else {
            // Redirect to specified URL or home
            $redirect_url = isset($response['redirect']) ? $response['redirect'] : home_url();
            wp_redirect($redirect_url);
            exit;
        }
    }

    /**
     * AJAX login handler.
     *
     * @since    1.0.0
     */
    public function ajax_login() {
        // Asegurar que estamos enviando el tipo de contenido correcto
        header('Content-Type: application/json; charset=UTF-8');
        
        // Prevenir redirecciones durante solicitudes AJAX - no quitar este filtro
        add_filter('wp_redirect', '__return_false', 999);
        add_filter('wp_safe_redirect', '__return_false', 999);
        
        // Verificar si es una solicitud AJAX
        $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
        
        if (!$is_ajax) {
            // Si no es una solicitud AJAX, usar el flujo normal
            $this->process_login_form();
            return;
        }
        
        try {
            // Procesar el login independientemente de los nonces para facilitar el diagnóstico
            // en producción, restaura la verificación de nonce
            $response = $this->forms->process_login($_POST);
            
            // Manejar la respuesta AJAX independientemente del tipo de redirección
            if (is_wp_error($response)) {
                echo json_encode(array(
                    'success' => false,
                    'data' => array(
                        'message' => $response->get_error_message()
                    )
                ));
            } else {
                // Verificar si el perfil necesita completarse
                $user_id = get_current_user_id();
                $profile_status = get_user_meta($user_id, 'wp_alp_profile_status', true);

                echo json_encode(array(
                    'success' => true,
                    'data' => array(
                        'message' => __('Login successful. Redirecting...', 'wp-alp'),
                        'redirect' => $response['redirect'],
                        'needs_profile_completion' => ($profile_status === 'incomplete')
                    )
                ));
            }
        } catch (Exception $e) {
            // Capturar cualquier excepción y devolverla como JSON
            echo json_encode(array(
                'success' => false,
                'data' => array(
                    'message' => 'Error: ' . $e->getMessage()
                )
            ));
        }
        
        // Es crucial terminar la ejecución aquí para evitar que se envíe otro contenido
        exit;
    }

    /**
     * AJAX user registration handler.
     *
     * @since    1.0.0
     */
    public function ajax_register_user() {
        // Prevenir redirecciones durante solicitudes AJAX - no quitar este filtro
        add_filter('wp_redirect', '__return_false', 999);
        
        // Verificar si es una solicitud AJAX
        $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
        
        if (!$is_ajax) {
            // Si no es una solicitud AJAX, usar el flujo normal
            $this->process_register_user_form();
            return;
        }
        
        // Check AJAX referer
        $nonce = isset($_POST['security']) ? $_POST['security'] : (isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '');
        if (!wp_verify_nonce($nonce, 'wp_alp_public_nonce')) {
            wp_send_json_error(array(
                'message' => __('Security token expired. Please refresh the page and try again.', 'wp-alp'),
            ));
            return;
        }
        
        $response = $this->forms->process_user_registration($_POST, $this->user_manager);
        
        // Manejar la respuesta AJAX independientemente del tipo de redirección
        if (is_wp_error($response)) {
            wp_send_json_error(array(
                'message' => $response->get_error_message(),
            ));
        } else {
            wp_send_json_success(array(
                'message' => __('Registration successful. Redirecting...', 'wp-alp'),
                'redirect' => $response['redirect'],
                'user_id' => $response['user_id'],
                'needs_profile_completion' => true // Nuevos registros siempre necesitan completar el perfil
            ));
        }
        
        // Note: No eliminar el filtro de redirección para asegurar que no ocurran redirecciones
    }

    /**
     * AJAX vendor registration handler.
     *
     * @since    1.0.0
     */
    public function ajax_register_vendor() {
        // Prevenir redirecciones durante solicitudes AJAX - no quitar este filtro
        add_filter('wp_redirect', '__return_false', 999);
        
        // Verificar si es una solicitud AJAX
        $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
        
        if (!$is_ajax) {
            // Si no es una solicitud AJAX, usar el flujo normal
            $this->process_register_vendor_form();
            return;
        }
        
        // Check AJAX referer
        $nonce = isset($_POST['security']) ? $_POST['security'] : (isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '');
        if (!wp_verify_nonce($nonce, 'wp_alp_public_nonce')) {
            wp_send_json_error(array(
                'message' => __('Security token expired. Please refresh the page and try again.', 'wp-alp'),
            ));
            return;
        }
        
        $response = $this->forms->process_vendor_registration($_POST, $this->user_manager);
        
        // Manejar la respuesta AJAX independientemente del tipo de redirección
        if (is_wp_error($response)) {
            wp_send_json_error(array(
                'message' => $response->get_error_message(),
            ));
        } else {
            wp_send_json_success(array(
                'message' => __('Vendor registration successful. Redirecting...', 'wp-alp'),
                'redirect' => $response['redirect'],
                'user_id' => $response['user_id'],
                'vendor_id' => $response['vendor_id'],
            ));
        }
        
        // Note: No eliminar el filtro de redirección para asegurar que no ocurran redirecciones
    }

    /**
     * AJAX profile completion handler.
     *
     * @since    1.0.0
     */
    public function ajax_complete_profile() {
        // Prevenir redirecciones durante solicitudes AJAX - no quitar este filtro
        add_filter('wp_redirect', '__return_false', 999);
        
        // Verificar si es una solicitud AJAX
        $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
        
        if (!$is_ajax) {
            // Si no es una solicitud AJAX, usar el flujo normal
            $this->process_profile_completion_form();
            return;
        }
        
        // Check AJAX referer
        $nonce = isset($_POST['security']) ? $_POST['security'] : (isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '');
        if (!wp_verify_nonce($nonce, 'wp_alp_public_nonce')) {
            wp_send_json_error(array(
                'message' => __('Security token expired. Please refresh the page and try again.', 'wp-alp'),
            ));
            return;
        }
        
        $response = $this->forms->process_profile_completion($_POST, $this->user_manager, $this->jetengine);
        
        // Manejar la respuesta AJAX independientemente del tipo de redirección
        if (is_wp_error($response)) {
            wp_send_json_error(array(
                'message' => $response->get_error_message(),
            ));
        } else {
            wp_send_json_success(array(
                'message' => __('Profile completed successfully. Redirecting...', 'wp-alp'),
                'redirect' => $response['redirect'],
                'lead_id' => $response['lead_id'],
            ));
        }
        
        // Note: No eliminar el filtro de redirección para asegurar que no ocurran redirecciones
    }

    /**
     * Handle social login.
     *
     * @since    1.0.0
     */
    public function handle_social_login() {
        // Prevenir redirecciones durante solicitudes AJAX - no quitar este filtro
        add_filter('wp_redirect', '__return_false', 999);
        
        // Verificar si es una solicitud AJAX
        $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
        
        if (!$is_ajax) {
            // Procesar sin AJAX, pero prevenir redirecciones no controladas
            add_filter('wp_redirect', array($this, 'capture_redirect_url'), 10, 1);
            // Continuamos con el proceso normal
        }
        
        $provider = sanitize_text_field($_POST['provider']);
        $code = sanitize_text_field($_POST['code']);
        $state = sanitize_text_field($_POST['state']);
        
        $response = $this->forms->process_social_login($provider, $code, $state, $this->social, $this->user_manager);
        
        // Determinar cómo responder basado en si es AJAX o no
        if ($is_ajax) {
            if (is_wp_error($response)) {
                wp_send_json_error(array(
                    'message' => $response->get_error_message(),
                ));
            } else {
                // Verificar si el perfil necesita completarse
                $user_id = $response['user_id'];
                $profile_status = get_user_meta($user_id, 'wp_alp_profile_status', true);
                
                wp_send_json_success(array(
                    'message' => __('Social login successful. Redirecting...', 'wp-alp'),
                    'redirect' => $response['redirect'],
                    'user_id' => $response['user_id'],
                    'needs_profile_completion' => ($profile_status === 'incomplete')
                ));
            }
        } else {
            // Procesamiento normal para redirecciones de redes sociales
            if (is_wp_error($response)) {
                // Redirigir a la página de login con mensaje de error
                $redirect_url = add_query_arg('social_error', urlencode($response->get_error_message()), $this->get_login_page_url());
                $this->do_safe_redirect($redirect_url);
                exit;
            } else {
                // Redirigir a la URL de redirección
                $this->do_safe_redirect($response['redirect']);
                exit;
            }
        }
    }

    /**
     * AJAX handler to get form HTML for modal.
     *
     * @since    1.0.0
     */
    public function get_form_html() {
        // Check AJAX referer
        check_ajax_referer('wp_alp_public_nonce', 'security');
        
        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : 'login';
        $html = '';
        
        // Add modal tabs
        $html .= '<div class="wp-alp-modal-tabs">';
        $html .= '<a href="#" class="wp-alp-modal-tab ' . ($type === 'login' ? 'active' : '') . '" data-tab="login">' . __('Log In', 'wp-alp') . '</a>';
        $html .= '<a href="#" class="wp-alp-modal-tab ' . ($type === 'register' ? 'active' : '') . '" data-tab="register">' . __('Sign Up', 'wp-alp') . '</a>';
        $html .= '</div>';
        
        // Add message container
        $html .= '<div id="wp-alp-modal-messages" class="wp-alp-form-messages"></div>';
        
        // Get form HTML based on type
        if ($type === 'login') {
            $atts = array(
                'show_title' => 'false',
                'show_social' => 'true',
                'redirect' => '',
            );
            $html .= $this->forms->render_login_form($atts);
        } else {
            $atts = array(
                'show_title' => 'false',
                'show_social' => 'true',
                'redirect' => '',
            );
            $html .= $this->forms->render_register_user_form($atts);
        }
        
        wp_send_json_success(array(
            'html' => $html
        ));
    }

    /**
     * Get login page URL.
     *
     * @since    1.0.0
     * @return   string    The login page URL.
     */
    private function get_login_page_url() {
        $options = get_option('wp_alp_general_options');
        
        if (!empty($options['login_page'])) {
            return get_permalink($options['login_page']);
        }
        
        return wp_login_url();
    }

    /**
     * Check if the login button should be shown
     * 
     * @since    1.0.0
     * @return   bool    Whether to show the login button
     */
    public static function should_show_login_button() {
        // No mostrar el botón si el usuario ya está conectado
        return !is_user_logged_in();
    }

    /**
     * Captura la URL de redirección en lugar de realizar la redirección.
     * Este método se utiliza como callback para el filtro wp_redirect.
     *
     * @since    1.0.0
     * @param    string    $location    La URL de redirección.
     * @return   false                  Siempre devuelve false para prevenir la redirección.
     */
    public function capture_redirect_url($location) {
        $this->redirect_url = $location;
        return false;
    }

    /**
     * Realiza una redirección segura.
     * Este método debe usarse en lugar de wp_redirect para tener mayor control.
     *
     * @since    1.0.0
     * @param    string    $url    La URL a la que redirigir.
     * @return   void
     */
    private function do_safe_redirect($url) {
        // Remover el filtro para permitir la redirección
        remove_filter('wp_redirect', '__return_false', 999);
        remove_filter('wp_redirect', array($this, 'capture_redirect_url'), 10);
        
        // Realizar la redirección
        wp_safe_redirect($url);
        exit;
    }
}