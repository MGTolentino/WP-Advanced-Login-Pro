<?php
/**
 * Plugin Name: WP Advanced Login Pro
 * Plugin URI: https://yourwebsite.com/wp-advanced-login-pro
 * Description: Un sistema avanzado de autenticación y registro estilo Airbnb con integración de JetEngine para leads.
 * Version: 1.0.0
 * Author: Tu Nombre
 * Author URI: https://yourwebsite.com
 * Text Domain: wp-alp
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Si este archivo es llamado directamente, abortar.
if (!defined('WPINC')) {
    die;
}

/**
 * Versión actual del plugin.
 */
define('WP_ALP_VERSION', '1.0.0');
define('WP_ALP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_ALP_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * La clase que orquesta la carga del plugin.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wp-alp-loader.php';

/**
 * Comienza la ejecución del plugin.
 */
function run_wp_advanced_login_pro() {
    // Carga las clases principales
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-alp-core.php';
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-alp-i18n.php';
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-alp-security.php';
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-alp-social.php';
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-alp-user-manager.php';
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-alp-jetengine.php';
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-alp-forms.php';
    require_once plugin_dir_path(__FILE__) . 'redirect-protection.php';

    // Carga las clases para el admin y parte pública
    require_once plugin_dir_path(__FILE__) . 'admin/class-wp-alp-admin.php';
    require_once plugin_dir_path(__FILE__) . 'public/class-wp-alp-public.php';

    $plugin = new WP_ALP_Core();
    $plugin->run();
}

// Hook de activación y desactivación
register_activation_hook(__FILE__, 'wp_alp_activate');
register_deactivation_hook(__FILE__, 'wp_alp_deactivate');

/**
 * Código ejecutado durante la activación del plugin.
 */
function wp_alp_activate() {
    // Crear tablas o inicializar opciones si es necesario
    update_option('wp_alp_version', WP_ALP_VERSION);
    
    // Verificar que JetEngine esté activo
    if (!class_exists('Jet_Engine')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Este plugin requiere que JetEngine esté instalado y activado.', 'Plugin Activación Error', array('back_link' => true));
    }
}

/**
 * Código ejecutado durante la desactivación del plugin.
 */
function wp_alp_deactivate() {
    // Limpiar datos temporales si es necesario
}

run_wp_advanced_login_pro();

/**
 * Registra las plantillas personalizadas del plugin.
 */
function wp_alp_register_templates($templates) {
    $plugin_templates = array(
        plugin_dir_path(__FILE__) . 'templates/login-page-template.php' => 'Página de Login WP-ALP',
        plugin_dir_path(__FILE__) . 'templates/seller-page-template.php' => 'Página para Vendedores WP-ALP',
        plugin_dir_path(__FILE__) . 'templates/login-page-template.php' => 'Página de Login WP-ALP',
        plugin_dir_path(__FILE__) . 'templates/vendor-page-template.php' => 'Página para Vendedores WP-ALP',
    );
    
    return array_merge($templates, $plugin_templates);
}
add_filter('theme_page_templates', 'wp_alp_register_templates');

/**
 * Carga la plantilla correcta si se selecciona una del plugin.
 */
function wp_alp_load_template($template) {
    global $post;
    
    if ($post && is_page()) {
        $template_name = get_post_meta($post->ID, '_wp_page_template', true);
        
        if (!empty($template_name)) {
            $plugin_templates = array(
                'templates/login-page-template.php',
                'templates/seller-page-template.php',
            );
            
            foreach ($plugin_templates as $plugin_template) {
                if (strpos($template_name, $plugin_template) !== false) {
                    $file = plugin_dir_path(__FILE__) . $template_name;
                    
                    if (file_exists($file)) {
                        return $file;
                    }
                }
            }
        }
    }
    
    return $template;
}
add_filter('template_include', 'wp_alp_load_template');

/**
 * Shortcode para incluir el formulario de login en cualquier página
 */
function wp_alp_login_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'redirect' => '',
    ), $atts, 'wp_alp_login_form');
    
    ob_start();
    
    // Incluir la hoja de estilo
    wp_enqueue_style('wp-alp-public');
    
    // Incluir scripts
    wp_enqueue_script('wp-alp-public');
    
    // Formulario
    echo '<div class="wp-alp-shortcode-form">';
    echo WP_ALP_Forms::get_initial_form();
    echo '</div>';
    
    if (!empty($atts['redirect'])) {
        echo '<script>
            jQuery(document).ready(function($) {
                $(".wp-alp-shortcode-form form").append(\'<input type="hidden" name="redirect_to" value="' . esc_url($atts['redirect']) . '">\');
            });
        </script>';
    }
    
    return ob_get_clean();
}
add_shortcode('wp_alp_login_form', 'wp_alp_login_form_shortcode');

/**
 * Redirigir usuarios no logueados a la página de login
 */
function wp_alp_redirect_to_login() {
    // No redirigir en la administración o si ya está logueado
    if (is_admin() || is_user_logged_in()) {
        return;
    }
    
    // No redirigir en estas páginas
    if (is_front_page() || is_home()) {
        return;
    }
    
    // Obtener la página de login
    $login_page_id = get_option('wp_alp_login_page_id');
    
    if (!$login_page_id) {
        // Buscar la página con la plantilla de login
        $login_pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'templates/login-page-template.php'
        ));
        
        if (!empty($login_pages)) {
            $login_page_id = $login_pages[0]->ID;
            update_option('wp_alp_login_page_id', $login_page_id);
        }
    }
    
    if ($login_page_id) {
        $login_url = get_permalink($login_page_id);
        $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        
        // Redirigir a la página de login con la URL actual como parámetro
        wp_redirect(add_query_arg('redirect_to', urlencode($current_url), $login_url));
        exit;
    }
}
add_action('template_redirect', 'wp_alp_redirect_to_login');