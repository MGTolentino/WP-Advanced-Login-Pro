<?php
/**
 * Plugin Name: WP Advanced Login Pro
 * Plugin URI: 
 * Description: Advanced login and registration system with separate flows for normal users and vendors, social login integration, and enhanced security.
 * Version: 1.0.0
 * Author: Miguel Tolentino
 * Author URI: 
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-alp
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('WP_ALP_VERSION', '1.0.0');
define('WP_ALP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_ALP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WP_ALP_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_wp_alp() {
    require_once WP_ALP_PLUGIN_DIR . 'includes/class-wp-alp-activator.php';
    WP_ALP_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wp_alp() {
    require_once WP_ALP_PLUGIN_DIR . 'includes/class-wp-alp-deactivator.php';
    WP_ALP_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wp_alp');
register_deactivation_hook(__FILE__, 'deactivate_wp_alp');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require WP_ALP_PLUGIN_DIR . 'includes/class-wp-alp-core.php';

/**
 * Begins execution of the plugin.
 */
function run_wp_alp() {
    $plugin = new WP_ALP_Core();
    $plugin->run();
}

run_wp_alp();