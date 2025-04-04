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
        // Check AJAX referer
        check_ajax_referer('wp_alp_public_nonce', 'security');
        
        $response = $this->forms->process_login($_POST);
        
        if (is_wp_error($response)) {
            wp_send_json_error(array(
                'message' => $response->get_error_message(),
            ));
        } else {
            wp_send_json_success(array(
                'message' => __('Login successful. Redirecting...', 'wp-alp'),
                'redirect' => $response['redirect'],
            ));
        }
    }

    /**
     * AJAX user registration handler.
     *
     * @since    1.0.0
     */
    public function ajax_register_user() {
        // Check AJAX referer
        check_ajax_referer('wp_alp_public_nonce', 'security');
        
        $response = $this->forms->process_user_registration($_POST, $this->user_manager);
        
        if (is_wp_error($response)) {
            wp_send_json_error(array(
                'message' => $response->get_error_message(),
            ));
        } else {
            wp_send_json_success(array(
                'message' => __('Registration successful. Redirecting...', 'wp-alp'),
                'redirect' => $response['redirect'],
                'user_id' => $response['user_id'],
            ));
        }
    }

    /**
     * AJAX vendor registration handler.
     *
     * @since    1.0.0
     */
    public function ajax_register_vendor() {
        // Check AJAX referer
        check_ajax_referer('wp_alp_public_nonce', 'security');
        
        $response = $this->forms->process_vendor_registration($_POST, $this->user_manager);
        
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
    }

    /**
     * AJAX profile completion handler.
     *
     * @since    1.0.0
     */
    public function ajax_complete_profile() {
        // Check AJAX referer
        check_ajax_referer('wp_alp_public_nonce', 'security');
        
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
            ));
        }
    }

    /**
     * Handle social login.
     *
     * @since    1.0.0
     */
    public function handle_social_login() {
        if (isset($_POST['provider']) && isset($_POST['code']) && isset($_POST['state'])) {
            $provider = sanitize_text_field($_POST['provider']);
            $code = sanitize_text_field($_POST['code']);
            $state = sanitize_text_field($_POST['state']);
            
            $response = $this->forms->process_social_login($provider, $code, $state, $this->social, $this->user_manager);
            
            if (is_wp_error($response)) {
                wp_send_json_error(array(
                    'message' => $response->get_error_message(),
                ));
            } else {
                wp_send_json_success(array(
                    'message' => __('Social login successful. Redirecting...', 'wp-alp'),
                    'redirect' => $response['redirect'],
                    'user_id' => $response['user_id'],
                ));
            }
        } else {
            wp_send_json_error(array(
                'message' => __('Invalid social login request.', 'wp-alp'),
            ));
        }
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
}