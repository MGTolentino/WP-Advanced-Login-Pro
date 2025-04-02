<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/includes
 */

class WP_ALP_Core {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      WP_ALP_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * The security manager instance.
     *
     * @since    1.0.0
     * @access   protected
     * @var      WP_ALP_Security    $security    Handles security features.
     */
    protected $security;

    /**
     * The social login manager instance.
     *
     * @since    1.0.0
     * @access   protected
     * @var      WP_ALP_Social    $social    Handles social login integration.
     */
    protected $social;

    /**
     * The user manager instance.
     *
     * @since    1.0.0
     * @access   protected
     * @var      WP_ALP_User_Manager    $user_manager    Handles user management.
     */
    protected $user_manager;

    /**
     * The JetEngine integration instance.
     *
     * @since    1.0.0
     * @access   protected
     * @var      WP_ALP_JetEngine    $jetengine    Handles JetEngine integration.
     */
    protected $jetengine;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('WP_ALP_VERSION')) {
            $this->version = WP_ALP_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'wp-advanced-login-pro';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - WP_ALP_Loader. Orchestrates the hooks of the plugin.
     * - WP_ALP_i18n. Defines internationalization functionality.
     * - WP_ALP_Admin. Defines all hooks for the admin area.
     * - WP_ALP_Public. Defines all hooks for the public side of the site.
     * - WP_ALP_Security. Handles security features.
     * - WP_ALP_Social. Handles social login integration.
     * - WP_ALP_User_Manager. Handles user management.
     * - WP_ALP_JetEngine. Handles JetEngine integration.
     * - WP_ALP_Forms. Handles form rendering and processing.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once WP_ALP_PLUGIN_DIR . 'includes/class-wp-alp-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once WP_ALP_PLUGIN_DIR . 'includes/class-wp-alp-i18n.php';

        /**
         * The class responsible for handling security features.
         */
        require_once WP_ALP_PLUGIN_DIR . 'includes/class-wp-alp-security.php';

        /**
         * The class responsible for handling social login integration.
         */
        require_once WP_ALP_PLUGIN_DIR . 'includes/class-wp-alp-social.php';

        /**
         * The class responsible for handling user management.
         */
        require_once WP_ALP_PLUGIN_DIR . 'includes/class-wp-alp-user-manager.php';

        /**
         * The class responsible for handling JetEngine integration.
         */
        require_once WP_ALP_PLUGIN_DIR . 'includes/class-wp-alp-jetengine.php';

        /**
         * The class responsible for handling form rendering and processing.
         */
        require_once WP_ALP_PLUGIN_DIR . 'includes/class-wp-alp-forms.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once WP_ALP_PLUGIN_DIR . 'admin/class-wp-alp-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once WP_ALP_PLUGIN_DIR . 'public/class-wp-alp-public.php';

        $this->loader = new WP_ALP_Loader();
        $this->security = new WP_ALP_Security();
        $this->social = new WP_ALP_Social();
        $this->user_manager = new WP_ALP_User_Manager();
        $this->jetengine = new WP_ALP_JetEngine();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the WP_ALP_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new WP_ALP_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new WP_ALP_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        
        // Add settings page
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_settings_page');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new WP_ALP_Public($this->get_plugin_name(), $this->get_version(), $this->security, $this->social, $this->user_manager, $this->jetengine);

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        
        // Register shortcodes
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
        
        // Override default WordPress login
        $this->loader->add_action('init', $plugin_public, 'override_wp_login');
        
        // Handle form submissions
        $this->loader->add_action('init', $plugin_public, 'handle_form_submissions');
        
        // Add AJAX handlers
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_login', $plugin_public, 'ajax_login');
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_register_user', $plugin_public, 'ajax_register_user');
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_register_vendor', $plugin_public, 'ajax_register_vendor');
        $this->loader->add_action('wp_ajax_wp_alp_complete_profile', $plugin_public, 'ajax_complete_profile');
        
        // Add social login handlers
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_social_login', $plugin_public, 'handle_social_login');
        
        // Add security measures
        $this->loader->add_action('wp_login_failed', $this->security, 'handle_failed_login');
        $this->loader->add_filter('authenticate', $this->security, 'check_login_limiter', 30, 3);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    WP_ALP_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}