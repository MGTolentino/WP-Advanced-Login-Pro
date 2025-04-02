<?php
/**
 * Template for the security settings page.
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
                <a href="?page=wp-advanced-login-pro" class="nav-tab"><?php esc_html_e('General', 'wp-alp'); ?></a>
                <a href="?page=wp-advanced-login-pro-security" class="nav-tab nav-tab-active"><?php esc_html_e('Security', 'wp-alp'); ?></a>
                <a href="?page=wp-advanced-login-pro-social" class="nav-tab"><?php esc_html_e('Social Login', 'wp-alp'); ?></a>
                <a href="?page=wp-advanced-login-pro-users" class="nav-tab"><?php esc_html_e('Users & Leads', 'wp-alp'); ?></a>
            </h2>
        </div>
        
        <div class="wp-alp-admin-content">
            <form method="post" action="options.php">
                <?php
                settings_fields('wp_alp_security_settings');
                do_settings_sections('wp_alp_security_settings');
                submit_button();
                ?>
            </form>
        </div>
        
        <div class="wp-alp-admin-sidebar">
            <div class="wp-alp-admin-box">
                <h3><?php esc_html_e('Security Measures', 'wp-alp'); ?></h3>
                <p><?php esc_html_e('WP Advanced Login Pro includes the following security measures:', 'wp-alp'); ?></p>
                <ul>
                    <li><?php esc_html_e('Login attempt limiter', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('reCAPTCHA integration', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Anti-bot techniques', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('CSRF protection', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Password strength meter', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Email validation', 'wp-alp'); ?></li>
                </ul>
                
                <h3><?php esc_html_e('reCAPTCHA Setup', 'wp-alp'); ?></h3>
                <p><?php esc_html_e('To set up reCAPTCHA:', 'wp-alp'); ?></p>
                <ol>
                    <li><?php esc_html_e('Go to the Google reCAPTCHA admin console', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Register your site', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Choose reCAPTCHA v2 ("I\'m not a robot" checkbox)', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Copy the Site Key and Secret Key', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Paste them in the fields on this page', 'wp-alp'); ?></li>
                </ol>
                <a href="https://www.google.com/recaptcha/admin" class="button button-secondary" target="_blank"><?php esc_html_e('Get reCAPTCHA Keys', 'wp-alp'); ?></a>
            </div>
        </div>
    </div>
</div>