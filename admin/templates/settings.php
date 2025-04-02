<?php
/**
 * Template for the settings page.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/admin/templates
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="wp-alp-admin-wrapper">
        <div class="wp-alp-admin-header">
            <h2 class="nav-tab-wrapper">
                <a href="?page=wp-advanced-login-pro" class="nav-tab nav-tab-active"><?php esc_html_e('General', 'wp-alp'); ?></a>
                <a href="?page=wp-advanced-login-pro-security" class="nav-tab"><?php esc_html_e('Security', 'wp-alp'); ?></a>
                <a href="?page=wp-advanced-login-pro-social" class="nav-tab"><?php esc_html_e('Social Login', 'wp-alp'); ?></a>
                <a href="?page=wp-advanced-login-pro-users" class="nav-tab"><?php esc_html_e('Users & Leads', 'wp-alp'); ?></a>
            </h2>
        </div>
        
        <div class="wp-alp-admin-content">
            <form method="post" action="options.php">
                <?php
                settings_fields('wp_alp_general_settings');
                do_settings_sections('wp_alp_general_settings');
                submit_button();
                ?>
            </form>
        </div>
        
        <div class="wp-alp-admin-sidebar">
            <div class="wp-alp-admin-box">
                <h3><?php esc_html_e('Shortcodes', 'wp-alp'); ?></h3>
                <p><?php esc_html_e('Use these shortcodes on your pages:', 'wp-alp'); ?></p>
                <ul>
                    <li><code>[wp_alp_login]</code> - <?php esc_html_e('Login Form', 'wp-alp'); ?></li>
                    <li><code>[wp_alp_register_user]</code> - <?php esc_html_e('User Registration Form', 'wp-alp'); ?></li>
                    <li><code>[wp_alp_register_vendor]</code> - <?php esc_html_e('Vendor Registration Form', 'wp-alp'); ?></li>
                    <li><code>[wp_alp_profile_completion]</code> - <?php esc_html_e('Profile Completion Form', 'wp-alp'); ?></li>
                </ul>
                
                <h4><?php esc_html_e('Shortcode Attributes:', 'wp-alp'); ?></h4>
                <ul>
                    <li><code>redirect</code> - <?php esc_html_e('URL to redirect after submission', 'wp-alp'); ?></li>
                    <li><code>show_title</code> - <?php esc_html_e('Show/hide form title (true/false)', 'wp-alp'); ?></li>
                    <li><code>show_social</code> - <?php esc_html_e('Show/hide social login buttons (true/false)', 'wp-alp'); ?></li>
                </ul>
                
                <h3><?php esc_html_e('Need Help?', 'wp-alp'); ?></h3>
                <p><?php esc_html_e('Check the documentation for detailed instructions on how to configure and customize the plugin.', 'wp-alp'); ?></p>
                <a href="#" class="button button-secondary" target="_blank"><?php esc_html_e('View Documentation', 'wp-alp'); ?></a>
            </div>
        </div>
    </div>
</div>