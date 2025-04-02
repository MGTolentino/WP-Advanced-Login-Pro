<?php
/**
 * Fired during plugin deactivation.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/includes
 */

class WP_ALP_Deactivator {

    /**
     * Execute deactivation tasks.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Get options
        $general_options = get_option('wp_alp_general_options');
        
        // Restore default WordPress login behavior
        if (!empty($general_options['override_wp_login'])) {
            $general_options['override_wp_login'] = false;
            update_option('wp_alp_general_options', $general_options);
        }
        
        // Flush rewrite rules to remove custom endpoints
        flush_rewrite_rules();
        
        // Clear any transients created by the plugin
        self::clear_transients();
    }
    
    /**
     * Clear plugin-specific transients.
     *
     * @since    1.0.0
     */
    private static function clear_transients() {
        global $wpdb;
        
        // Delete login attempt transients
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_wp_alp_failed_login_%'");
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_wp_alp_failed_login_%'");
    }
}