<?php
/**
 * Fired during plugin activation.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/includes
 */

class WP_ALP_Activator {

    /**
     * Execute activation tasks.
     *
     * @since    1.0.0
     */
    public static function activate() {
        global $wpdb;
        
        // Create encryption key if not exists
        if (!get_option('wp_alp_encryption_key')) {
            update_option('wp_alp_encryption_key', bin2hex(random_bytes(32)));
        }
        
        // Set default options
        self::set_default_options();
        
        // Create pages
        self::create_pages();
        
        // Create database tables if needed
        self::create_tables();
        
        // Flush rewrite rules to enable custom endpoints
        flush_rewrite_rules();
    }
    
    /**
     * Set default plugin options.
     *
     * @since    1.0.0
     */
    private static function set_default_options() {
        // General options
        $general_options = get_option('wp_alp_general_options');
        
        if (!$general_options) {
            $general_options = array(
                'auto_login' => true,
                'override_wp_login' => true,
                'login_page' => 0,
                'register_user_page' => 0,
                'register_vendor_page' => 0,
                'profile_completion_page' => 0,
                'user_redirect' => '',
                'lead_redirect' => '',
                'vendor_redirect' => '',
            );
            
            update_option('wp_alp_general_options', $general_options);
        }
        
        // Security options
        $security_options = get_option('wp_alp_security_options');
        
        if (!$security_options) {
            $security_options = array(
                'max_login_attempts' => 5,
                'lockout_duration' => 1800, // 30 minutes
                'recaptcha_site_key' => '',
                'recaptcha_secret_key' => '',
            );
            
            update_option('wp_alp_security_options', $security_options);
        }
        
        // Social options
        $social_options = get_option('wp_alp_social_options');
        
        if (!$social_options) {
            $social_options = array(
                'google' => array(
                    'enabled' => false,
                    'client_id' => '',
                    'client_secret' => '',
                ),
                'facebook' => array(
                    'enabled' => false,
                    'client_id' => '',
                    'client_secret' => '',
                ),
                'apple' => array(
                    'enabled' => false,
                    'client_id' => '',
                    'client_secret' => '',
                ),
                'linkedin' => array(
                    'enabled' => false,
                    'client_id' => '',
                    'client_secret' => '',
                ),
            );
            
            update_option('wp_alp_social_options', $social_options);
        }
    }
    
    /**
     * Create necessary pages.
     *
     * @since    1.0.0
     */
    private static function create_pages() {
        $general_options = get_option('wp_alp_general_options');
        
        // Login page
        if (empty($general_options['login_page'])) {
            $login_page_id = wp_insert_post(array(
                'post_title' => __('Login', 'wp-alp'),
                'post_content' => '[wp_alp_login]',
                'post_status' => 'publish',
                'post_type' => 'page',
            ));
            
            if ($login_page_id && !is_wp_error($login_page_id)) {
                $general_options['login_page'] = $login_page_id;
            }
        }
        
        // Register user page
        if (empty($general_options['register_user_page'])) {
            $register_user_page_id = wp_insert_post(array(
                'post_title' => __('Register', 'wp-alp'),
                'post_content' => '[wp_alp_register_user]',
                'post_status' => 'publish',
                'post_type' => 'page',
            ));
            
            if ($register_user_page_id && !is_wp_error($register_user_page_id)) {
                $general_options['register_user_page'] = $register_user_page_id;
            }
        }
        
        // Register vendor page
        if (empty($general_options['register_vendor_page'])) {
            $register_vendor_page_id = wp_insert_post(array(
                'post_title' => __('Register as Vendor', 'wp-alp'),
                'post_content' => '[wp_alp_register_vendor]',
                'post_status' => 'publish',
                'post_type' => 'page',
            ));
            
            if ($register_vendor_page_id && !is_wp_error($register_vendor_page_id)) {
                $general_options['register_vendor_page'] = $register_vendor_page_id;
            }
        }
        
        // Profile completion page
        if (empty($general_options['profile_completion_page'])) {
            $profile_completion_page_id = wp_insert_post(array(
                'post_title' => __('Complete Your Profile', 'wp-alp'),
                'post_content' => '[wp_alp_profile_completion]',
                'post_status' => 'publish',
                'post_type' => 'page',
            ));
            
            if ($profile_completion_page_id && !is_wp_error($profile_completion_page_id)) {
                $general_options['profile_completion_page'] = $profile_completion_page_id;
            }
        }
        
        // Update options with new page IDs
        update_option('wp_alp_general_options', $general_options);
    }
    
    /**
     * Create necessary database tables.
     *
     * @since    1.0.0
     */
    private static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        // Login attempts table
        $table_name = $wpdb->prefix . 'wp_alp_login_attempts';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                ip_address varchar(100) NOT NULL,
                username varchar(100) NOT NULL,
                time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                status varchar(20) NOT NULL,
                PRIMARY KEY  (id),
                KEY ip_address (ip_address),
                KEY time (time)
            ) $charset_collate;";
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
        
        // Social profiles table
        $table_name = $wpdb->prefix . 'wp_alp_social_profiles';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                user_id bigint(20) NOT NULL,
                provider varchar(50) NOT NULL,
                provider_id varchar(255) NOT NULL,
                profile_data longtext NOT NULL,
                created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                PRIMARY KEY  (id),
                KEY user_id (user_id),
                KEY provider (provider),
                UNIQUE KEY provider_id_unique (provider, provider_id)
            ) $charset_collate;";
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
}