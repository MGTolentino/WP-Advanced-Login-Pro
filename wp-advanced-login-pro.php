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
    require_once plugin_dir_path(__FILE__) . 'includes/functions.php';

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
        plugin_dir_path(__FILE__) . 'templates/vendor-page-template.php' => 'Página para Vendedores WP-ALP',
        plugin_dir_path(__FILE__) . 'templates/vendor-steps-template.php' => 'Pasos para Vendedores WP-ALP',
        plugin_dir_path(__FILE__) . 'templates/vendor-form-step1-template.php' => 'Formulario Vendedor Paso 1 WP-ALP',

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
        
        error_log('Template solicitada: ' . $template_name);
        
        // Verificar si es una de nuestras plantillas
        if (!empty($template_name) && (
            strpos($template_name, 'login-page-template.php') !== false ||
            strpos($template_name, 'vendor-page-template.php') !== false ||
            strpos($template_name, 'seller-page-template.php') !== false ||
            strpos($template_name, 'vendor-steps-template.php') !== false ||
            strpos($template_name, 'vendor-form-step1-template.php') !== false
        )) {
            // Determinar qué archivo de plantilla cargar
            $template_file = '';
            
            if (strpos($template_name, 'login-page-template.php') !== false) {
                $template_file = 'login-page-template.php';
            } else if (strpos($template_name, 'vendor-page-template.php') !== false || 
                       strpos($template_name, 'seller-page-template.php') !== false) {
                $template_file = 'vendor-page-template.php';
            } else if (strpos($template_name, 'vendor-steps-template.php') !== false) {
                $template_file = 'vendor-steps-template.php';
            } else if (strpos($template_name, 'vendor-form-step1-template.php') !== false) {
                $template_file = 'vendor-form-step1-template.php';
            }
            
            if (!empty($template_file)) {
                $file = plugin_dir_path(__FILE__) . 'templates/' . $template_file;
                
                error_log('Ruta corregida de plantilla: ' . $file);
                error_log('¿Existe el archivo? ' . (file_exists($file) ? 'SÍ' : 'NO'));
                
                if (file_exists($file)) {
                    return $file;
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


// Agregar atributo data-wp-alp-trigger="login" a elementos con clase wp-alp-login-trigger
function add_login_trigger_attribute($atts, $item, $args) {
    // Verifica si el elemento tiene la clase wp-alp-login-trigger
    if (is_object($item) && isset($item->classes) && in_array('wp-alp-login-trigger', $item->classes)) {
        $atts['data-wp-alp-trigger'] = 'login';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_login_trigger_attribute', 10, 3);