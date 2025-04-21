<?php
/**
 * Funcionalidad específica de la administración del plugin.
 *
 * @link       https://yourwebsite.com
 * @since      1.0.0
 *
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/admin
 */

/**
 * Clase que gestiona la funcionalidad de administración del plugin.
 */
class WP_ALP_Admin {

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
     * Registra los estilos para el área de administración.
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/wp-alp-admin.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Registra los scripts para el área de administración.
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/wp-alp-admin.js',
            array('jquery'),
            $this->version,
            false
        );
    }

    /**
     * Añade el menú de administración del plugin.
     */
    public function add_admin_menu() {
        add_menu_page(
            __('WP Advanced Login Pro', 'wp-alp'),
            __('Login Avanzado', 'wp-alp'),
            'manage_options',
            'wp-advanced-login-pro',
            array($this, 'display_admin_page'),
            'dashicons-lock',
            30
        );

        add_submenu_page(
            'wp-advanced-login-pro',
            __('Configuración', 'wp-alp'),
            __('Configuración', 'wp-alp'),
            'manage_options',
            'wp-advanced-login-pro',
            array($this, 'display_admin_page')
        );

        add_submenu_page(
            'wp-advanced-login-pro',
            __('Social Login', 'wp-alp'),
            __('Social Login', 'wp-alp'),
            'manage_options',
            'wp-alp-social',
            array($this, 'display_social_page')
        );

        add_submenu_page(
            'wp-advanced-login-pro',
            __('Seguridad', 'wp-alp'),
            __('Seguridad', 'wp-alp'),
            'manage_options',
            'wp-alp-security',
            array($this, 'display_security_page')
        );
    }

    /**
     * Registra la configuración del plugin.
     */
    public function register_settings() {
        // Configuración general
        register_setting('wp_alp_general', 'wp_alp_enable_social_login', array(
            'type' => 'boolean',
            'default' => true,
        ));
        
        register_setting('wp_alp_general', 'wp_alp_enable_phone_login', array(
            'type' => 'boolean',
            'default' => true,
        ));
        
        register_setting('wp_alp_general', 'wp_alp_redirect_after_login', array(
            'type' => 'string',
            'default' => home_url(),
        ));
        
        register_setting('wp_alp_general', 'wp_alp_enable_email_verification', array(
            'type' => 'boolean',
            'default' => true,
        ));
        
        // Configuración de Social Login
        register_setting('wp_alp_social', 'wp_alp_google_client_id', array(
            'type' => 'string',
        ));
        
        register_setting('wp_alp_social', 'wp_alp_google_client_secret', array(
            'type' => 'string',
        ));
        
        register_setting('wp_alp_social', 'wp_alp_facebook_app_id', array(
            'type' => 'string',
        ));
        
        register_setting('wp_alp_social', 'wp_alp_facebook_app_secret', array(
            'type' => 'string',
        ));
        
        register_setting('wp_alp_social', 'wp_alp_apple_client_id', array(
            'type' => 'string',
        ));
        
        register_setting('wp_alp_social', 'wp_alp_apple_team_id', array(
            'type' => 'string',
        ));
        
        register_setting('wp_alp_social', 'wp_alp_apple_key_id', array(
            'type' => 'string',
        ));
        
        register_setting('wp_alp_social', 'wp_alp_apple_private_key', array(
            'type' => 'string',
        ));
        
        // Configuración de seguridad
        register_setting('wp_alp_security', 'wp_alp_max_login_attempts', array(
            'type' => 'integer',
            'default' => 5,
        ));
        
        register_setting('wp_alp_security', 'wp_alp_lockout_time', array(
            'type' => 'integer',
            'default' => 300, // 5 minutos
        ));
        
        register_setting('wp_alp_security', 'wp_alp_enable_captcha', array(
            'type' => 'boolean',
            'default' => false,
        ));
        
        register_setting('wp_alp_security', 'wp_alp_recaptcha_site_key', array(
            'type' => 'string',
        ));
        
        register_setting('wp_alp_security', 'wp_alp_recaptcha_secret_key', array(
            'type' => 'string',
        ));

         // Configuración de página de login
    register_setting('wp_alp_general_settings', 'wp_alp_login_page_id');
    
    add_settings_field(
        'wp_alp_login_page_id',
        __('Página de Login', 'wp-alp'),
        array($this, 'render_login_page_field'),
        'wp_alp_general_settings',
        'wp_alp_general_section'
    );
    }

    /**
     * Muestra la página principal de administración.
     */
    public function display_admin_page() {
        include_once plugin_dir_path(__FILE__) . 'templates/settings.php';
    }

    /**
     * Muestra la página de configuración de Social Login.
     */
    public function display_social_page() {
        include_once plugin_dir_path(__FILE__) . 'templates/social.php';
    }

    /**
     * Muestra la página de configuración de seguridad.
     */
    public function display_security_page() {
        include_once plugin_dir_path(__FILE__) . 'templates/security.php';
    }

/**
 * Renderiza el campo de selección de página de login.
 */
public function render_login_page_field() {
    $login_page_id = get_option('wp_alp_login_page_id', 0);
    
    wp_dropdown_pages(array(
        'name' => 'wp_alp_login_page_id',
        'echo' => 1,
        'show_option_none' => __('-- Seleccionar página --', 'wp-alp'),
        'option_none_value' => '0',
        'selected' => $login_page_id,
    ));
    
    echo '<p class="description">';
    _e('Selecciona la página que contiene el shortcode [wp_alp_login_page].', 'wp-alp');
    echo '</p>';
}
}