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
    require plugin_dir_path(__FILE__) . 'wp-alp-enhancements.php';


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