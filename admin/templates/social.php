<?php
/**
 * Template for the social login settings page.
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
                <a href="?page=wp-advanced-login-pro-security" class="nav-tab"><?php esc_html_e('Security', 'wp-alp'); ?></a>
                <a href="?page=wp-advanced-login-pro-social" class="nav-tab nav-tab-active"><?php esc_html_e('Social Login', 'wp-alp'); ?></a>
                <a href="?page=wp-advanced-login-pro-users" class="nav-tab"><?php esc_html_e('Users & Leads', 'wp-alp'); ?></a>
            </h2>
        </div>
        
        <div class="wp-alp-admin-content">
            <form method="post" action="options.php">
                <?php
                settings_fields('wp_alp_social_settings');
                do_settings_sections('wp_alp_social_settings');
                submit_button();
                ?>
            </form>
        </div>
        
        <div class="wp-alp-admin-sidebar">
            <div class="wp-alp-admin-box">
                <h3><?php esc_html_e('Social Login Setup', 'wp-alp'); ?></h3>
                <p><?php esc_html_e('For each social provider, you need to create an application in the respective developer console and configure OAuth settings.', 'wp-alp'); ?></p>
                
                <h4><?php esc_html_e('Google', 'wp-alp'); ?></h4>
                <ol>
                    <li><?php esc_html_e('Go to the Google Cloud Console', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Create a project', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Configure OAuth consent screen', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Create OAuth client ID credentials', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Add the redirect URI shown on this page', 'wp-alp'); ?></li>
                </ol>
                <a href="https://console.cloud.google.com/" class="button button-secondary" target="_blank"><?php esc_html_e('Google Cloud Console', 'wp-alp'); ?></a>
                
                <h4><?php esc_html_e('Facebook', 'wp-alp'); ?></h4>
                <ol>
                    <li><?php esc_html_e('Go to the Facebook Developer portal', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Create a new app', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Add the Facebook Login product', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Configure the Valid OAuth Redirect URIs', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Copy the App ID and App Secret', 'wp-alp'); ?></li>
                </ol>
                <a href="https://developers.facebook.com/" class="button button-secondary" target="_blank"><?php esc_html_e('Facebook Developer Portal', 'wp-alp'); ?></a>
                
                <h4><?php esc_html_e('LinkedIn', 'wp-alp'); ?></h4>
                <ol>
                    <li><?php esc_html_e('Go to the LinkedIn Developer portal', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Create a new app', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Configure OAuth settings', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Add the redirect URI shown on this page', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Request the necessary API permissions', 'wp-alp'); ?></li>
                </ol>
                <a href="https://www.linkedin.com/developers/" class="button button-secondary" target="_blank"><?php esc_html_e('LinkedIn Developer Portal', 'wp-alp'); ?></a>
            </div>
        </div>
    </div>
</div>