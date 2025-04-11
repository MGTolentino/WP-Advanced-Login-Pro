<?php
/**
 * La clase principal del plugin.
 *
 * Esta es la clase núcleo que gestiona todas las partes del plugin
 * y registra todos los hooks.
 */
class WP_ALP_Core {

    /**
     * El cargador que es responsable de mantener y registrar todos los hooks del plugin.
     */
    protected $loader;

    /**
     * La versión actual del plugin.
     */
    protected $version;

    /**
     * Define el nombre del plugin para identificarlo internamente.
     */
    protected $plugin_name;

    /**
 * La instancia de la clase social.
 */
protected $social;

    /**
     * Constructor de la clase.
     */
    public function __construct() {
        $this->plugin_name = 'wp-advanced-login-pro';
        $this->version = WP_ALP_VERSION;
    
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        
        // Crear instancia de la clase social
        $this->social = new WP_ALP_Social();
        
        // Registrar hooks REST
        $this->define_rest_hooks();
    }

    /**
     * Carga las dependencias requeridas para el plugin.
     */
    private function load_dependencies() {
        $this->loader = new WP_ALP_Loader();
    }

    /**
     * Define la configuración de localización para el plugin.
     */
    private function set_locale() {
        $plugin_i18n = new WP_ALP_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
 * Registra los hooks relacionados con la API REST.
 */
private function define_rest_hooks() {
    // Asegurarse de que los endpoints REST se registren
    $this->loader->add_action('rest_api_init', $this->social, 'register_rest_routes');
}

    /**
     * Registra todos los hooks relacionados con la funcionalidad admin.
     */
    private function define_admin_hooks() {
        $plugin_admin = new WP_ALP_Admin($this->get_plugin_name(), $this->get_version());
        
        // Enqueue scripts y estilos
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        
        // Añadir menú de administración
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_menu');
        
        // Registrar configuraciones
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
    }

    /**
     * Registra todos los hooks relacionados con la funcionalidad pública.
     */
    private function define_public_hooks() {
        $plugin_public = new WP_ALP_Public($this->get_plugin_name(), $this->get_version());
        
        // Enqueue scripts y estilos
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        
        // Shortcode para el modal de login
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
        
        // AJAX handlers
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_validate_user', $plugin_public, 'validate_user_ajax');
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_register_user', $plugin_public, 'register_user_ajax');
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_login_user', $plugin_public, 'login_user_ajax');
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_verify_code', $plugin_public, 'verify_code_ajax');
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_resend_code', $plugin_public, 'resend_code_ajax');
        $this->loader->add_action('wp_ajax_wp_alp_complete_profile', $plugin_public, 'complete_profile_ajax');
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_complete_profile', $plugin_public, 'complete_profile_ajax');
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_social_login', $plugin_public, 'social_login_ajax');
        $this->loader->add_action('wp_ajax_wp_alp_social_login', $plugin_public, 'social_login_ajax');

        $this->loader->add_action('wp_ajax_wp_alp_get_form', $plugin_public, 'get_form_ajax');
        $this->loader->add_action('wp_ajax_nopriv_wp_alp_get_form', $plugin_public, 'get_form_ajax');
        
        // Añadir modal al footer (para estar disponible en todas las páginas)
        $this->loader->add_action('wp_footer', $plugin_public, 'output_login_modal');

        $this->loader->add_action('wp_footer', $plugin_public, 'initialize_social_scripts', 20);
        // Insertar el botón de vendedor en el header
        $this->loader->add_action('kava_header_before', $plugin_public, 'output_vendor_button', 20);
    }

    /**
     * Ejecuta el cargador para ejecutar todos los hooks.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * El nombre del plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * El cargador que orquesta los hooks.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * La versión del plugin.
     */
    public function get_version() {
        return $this->version;
    }
}