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

    public function ajax_login() {
        // Asegurar que estamos enviando el tipo de contenido correcto
        header('Content-Type: application/json; charset=UTF-8');
        
        // Prevenir redirecciones durante solicitudes AJAX
        add_filter('wp_redirect', '__return_false', 999);
        add_filter('wp_safe_redirect', '__return_false', 999);
        
        // Verificar si es una solicitud AJAX
        $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
        $is_modal = isset($_POST['is_modal']) && $_POST['is_modal'] === 'true';
        
        if (!$is_ajax) {
            // Si no es una solicitud AJAX, usar el flujo normal
            $this->process_login_form();
            return;
        }
        
        try {
            // Procesar el login
            $response = $this->forms->process_login($_POST);
            
            if (is_wp_error($response)) {
                // Método alternativo de envío de respuesta JSON
                echo json_encode(array(
                    'success' => false,
                    'data' => array(
                        'message' => $response->get_error_message()
                    )
                ));
                die();
            } else {
                // Verificar si el perfil necesita completarse
                $user_id = get_current_user_id();
                $profile_status = get_user_meta($user_id, 'wp_alp_profile_status', true);
                $needs_profile_completion = ($profile_status === 'incomplete');
    
                // Método alternativo de envío de respuesta JSON
                echo json_encode(array(
                    'success' => true,
                    'data' => array(
                        'message' => __('Login successful.', 'wp-alp'),
                        'redirect' => $response['redirect'],
                        'needs_profile_completion' => $needs_profile_completion,
                        'user_id' => $user_id
                    )
                ));
                die();
            }
        } catch (Exception $e) {
            // Método alternativo de envío de respuesta JSON
            echo json_encode(array(
                'success' => false,
                'data' => array(
                    'message' => 'Error: ' . $e->getMessage()
                )
            ));
            die();
        }
    }

    /**
 * AJAX user registration handler.
 *
 * @since    1.0.0
 */
public function ajax_register_user() {
    // Asegurar que estamos enviando el tipo de contenido correcto
    header('Content-Type: application/json; charset=UTF-8');
    
    // Prevenir redirecciones durante solicitudes AJAX
    add_filter('wp_redirect', '__return_false', 999);
    add_filter('wp_safe_redirect', '__return_false', 999);
    
    // Verificar si es una solicitud AJAX
    $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
    $is_modal = isset($_POST['is_modal']) && $_POST['is_modal'] === 'true';
    
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
        exit;
    }
    
    $response = $this->forms->process_user_registration($_POST, $this->user_manager);
    
    if (is_wp_error($response)) {
        wp_send_json_error(array(
            'message' => $response->get_error_message(),
        ));
    } else {
        wp_send_json_success(array(
            'message' => __('Registration successful.', 'wp-alp'),
            'redirect' => $response['redirect'],
            'user_id' => $response['user_id'],
            'needs_profile_completion' => true  // Nuevos registros siempre necesitan completar el perfil
        ));
    }
    exit;
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
    // Asegurar que estamos enviando el tipo de contenido correcto
    header('Content-Type: application/json; charset=UTF-8');
    
    // Prevenir redirecciones durante solicitudes AJAX
    add_filter('wp_redirect', '__return_false', 999);
    add_filter('wp_safe_redirect', '__return_false', 999);
    
    // Verificar si es una solicitud AJAX
    $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
    $is_modal = isset($_POST['is_modal']) && $_POST['is_modal'] === 'true';
    
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
        exit;
    }
    
    $response = $this->forms->process_profile_completion($_POST, $this->user_manager, $this->jetengine);
    
    if (is_wp_error($response)) {
        wp_send_json_error(array(
            'message' => $response->get_error_message(),
        ));
    } else {
        wp_send_json_success(array(
            'message' => __('Profile completed successfully. Redirecting...', 'wp-alp'),
            'redirect' => $response['redirect'],
            'lead_id' => $response['lead_id'],
            'profile_completed' => true
        ));
    }
    exit;
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
 * AJAX handler to get profile completion form HTML for modal.
 *
 * @since    1.0.0
 */
public function get_profile_completion_html() {
    // Check AJAX referer
    check_ajax_referer('wp_alp_public_nonce', 'security');
    
    // Add modal message container
    $html = '<div id="wp-alp-modal-messages" class="wp-alp-form-messages"></div>';
    
    // Get profile completion form
    $atts = array(
        'show_title' => 'false',
        'redirect' => '',
    );
    $html .= $this->forms->render_profile_completion_form($atts);
    
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

    /**
 * Get initial form HTML for modal.
 */
public function get_initial_form_html() {
    check_ajax_referer('wp_alp_public_nonce', 'security');
    
    $html = '<div id="wp-alp-modal-messages" class="wp-alp-form-messages"></div>';
    
    ob_start();
    include WP_ALP_PLUGIN_DIR . 'public/templates/initial-form.php';
    $form_html = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $html . $form_html
    ));
}

/**
 * Check user existence AJAX handler.
 */
public function check_identifier() {
    check_ajax_referer('wp_alp_public_nonce', 'security');
    
    $identifier = isset($_POST['identifier']) ? sanitize_text_field($_POST['identifier']) : '';
    $is_phone = false;
    $user_exists = false;
    $is_subscriber = false;
    $profile_incomplete = false;
    $email = '';
    $phone = '';
    
    // Check if identifier is an email or phone
    if (is_email($identifier)) {
        $email = $identifier;
        $user = get_user_by('email', $email);
    } else {
        // Assume it's a phone number
        $is_phone = true;
        $phone = $identifier;
        
        // Search for users with this phone number in meta
        $users = get_users(array(
            'meta_key' => 'wp_alp_phone',
            'meta_value' => $phone,
            'number' => 1
        ));
        
        $user = !empty($users) ? $users[0] : false;
        
        if ($user) {
            $email = $user->user_email;
        }
    }
    
    if ($user) {
        $user_exists = true;
        $user_roles = $user->roles;
        $is_subscriber = in_array('subscriber', $user_roles);
        
        if ($is_subscriber) {
            $profile_status = get_user_meta($user->ID, 'wp_alp_profile_status', true);
            $profile_incomplete = ($profile_status === 'incomplete');
        }
    }
    
    wp_send_json_success(array(
        'exists' => $user_exists,
        'is_subscriber' => $is_subscriber,
        'profile_incomplete' => $profile_incomplete,
        'is_phone' => $is_phone,
        'email' => $email,
        'phone' => $phone,
    ));
}

/**
 * Get combined registration and profile form.
 */
public function get_combined_form_html() {
    check_ajax_referer('wp_alp_public_nonce', 'security');
    
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $is_phone = isset($_POST['is_phone']) && $_POST['is_phone'] === 'true';
    $is_new_user = isset($_POST['is_new_user']) && $_POST['is_new_user'] === 'true';
    
    $html = '<div id="wp-alp-modal-messages" class="wp-alp-form-messages"></div>';
    
    // Generate nonce and CSRF token
    $nonce = $this->security->generate_nonce();
    $csrf_token = $this->security->generate_csrf_token();
    
    $atts = array(
        'show_title' => 'true',
        'redirect' => '',
    );
    
    ob_start();
    include WP_ALP_PLUGIN_DIR . 'public/templates/combined-form.php';
    $form_html = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $html . $form_html
    ));
}

/**
 * Process combined form (registration + profile).
 */
public function process_combined_form() {
    header('Content-Type: application/json; charset=UTF-8');
    
    // Prevent redirections during AJAX requests
    add_filter('wp_redirect', '__return_false', 999);
    add_filter('wp_safe_redirect', '__return_false', 999);
    
    $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
    
    if (!$is_ajax) {
        // Handle non-AJAX form submission
        return;
    }
    
    // Check security nonce
    $nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';
    if (!$this->security->verify_nonce($nonce)) {
        echo json_encode(array(
            'success' => false,
            'data' => array(
                'message' => __('Security check failed. Please refresh and try again.', 'wp-alp')
            )
        ));
        exit;
    }
    
    try {
        $is_new_user = isset($_POST['is_new_user']) && $_POST['is_new_user'] === '1';
        
        if ($is_new_user) {
            // Register new user first
            $registration_data = array(
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'password_confirm' => $_POST['password'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
            );
            
            if (isset($_POST['phone'])) {
                $registration_data['phone'] = $_POST['phone'];
            }
            
            $register_response = $this->forms->process_user_registration($registration_data, $this->user_manager);
            
            if (is_wp_error($register_response)) {
                echo json_encode(array(
                    'success' => false,
                    'data' => array(
                        'message' => $register_response->get_error_message()
                    )
                ));
                exit;
            }
            
            $user_id = $register_response['user_id'];
        } else {
            // User exists, get user ID from current session
            $user_id = get_current_user_id();
            
            if (!$user_id) {
                echo json_encode(array(
                    'success' => false,
                    'data' => array(
                        'message' => __('You must be logged in to complete your profile.', 'wp-alp')
                    )
                ));
                exit;
            }
        }
        
        // Now process profile completion
        $profile_data = array(
            'phone' => $_POST['phone'],
            'event_type' => $_POST['event_type'],
            'event_date' => $_POST['event_date'],
            'event_address' => $_POST['event_address'],
            'event_attendees' => $_POST['event_attendees'],
        );
        
        $profile_response = $this->forms->process_profile_completion($profile_data, $this->user_manager, $this->jetengine);
        
        if (is_wp_error($profile_response)) {
            echo json_encode(array(
                'success' => false,
                'data' => array(
                    'message' => $profile_response->get_error_message()
                )
            ));
            exit;
        }
        
        // Send verification email to new users
        if ($is_new_user) {
            $this->send_verification_email($user_id);
        }
        
        echo json_encode(array(
            'success' => true,
            'data' => array(
                'message' => __('Registration and profile completed successfully. Redirecting...', 'wp-alp'),
                'redirect' => $profile_response['redirect'],
                'lead_id' => $profile_response['lead_id']
            )
        ));
        exit;
        
    } catch (Exception $e) {
        echo json_encode(array(
            'success' => false,
            'data' => array(
                'message' => 'Error: ' . $e->getMessage()
            )
        ));
        exit;
    }
}

/**
 * Send verification email to user
 */
private function send_verification_email($user_id) {
    $user = get_user_by('ID', $user_id);
    if (!$user) {
        return false;
    }
    
    // Generate verification code and store it
    $verification_code = wp_generate_password(20, false);
    update_user_meta($user_id, 'wp_alp_email_verification_code', $verification_code);
    update_user_meta($user_id, 'wp_alp_email_verification_expiry', time() + 86400); // 24 hours
    
    $verify_url = add_query_arg(array(
        'action' => 'verify_email',
        'user_id' => $user_id,
        'code' => $verification_code
    ), home_url('/'));
    
    $subject = __('Verify your email address', 'wp-alp');
    
    $message = sprintf(
        __('Hello %s,', 'wp-alp') . "\n\n" .
        __('Thank you for registering. Please verify your email address by clicking the link below:', 'wp-alp') . "\n\n" .
        '%s' . "\n\n" .
        __('This link will expire in 24 hours.', 'wp-alp') . "\n\n" .
        __('Thank you,', 'wp-alp') . "\n" .
        get_bloginfo('name'),
        $user->display_name,
        $verify_url
    );
    
    return wp_mail($user->user_email, $subject, $message);
}

/**
 * Handle email verification
 */
public function verify_email() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'verify_email' || 
        !isset($_GET['user_id']) || !isset($_GET['code'])) {
        return;
    }
    
    $user_id = intval($_GET['user_id']);
    $code = sanitize_text_field($_GET['code']);
    
    $stored_code = get_user_meta($user_id, 'wp_alp_email_verification_code', true);
    $expiry = get_user_meta($user_id, 'wp_alp_email_verification_expiry', true);
    
    if (!$stored_code || !$expiry || time() > $expiry || $stored_code !== $code) {
        wp_redirect(add_query_arg('verification', 'failed', home_url()));
        exit;
    }
    
    // Mark email as verified
    update_user_meta($user_id, 'wp_alp_email_verified', 'yes');
    delete_user_meta($user_id, 'wp_alp_email_verification_code');
    delete_user_meta($user_id, 'wp_alp_email_verification_expiry');
    
    // Redirect to success page
    wp_redirect(add_query_arg('verification', 'success', home_url()));
    exit;
}
}