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

// Include our custom save handler
require_once WP_ALP_PLUGIN_DIR . 'save-social-options.php';

// Get current options
$options = get_option('wp_alp_social_options', array(
    'google' => array('enabled' => false, 'client_id' => '', 'client_secret' => ''),
    'facebook' => array('enabled' => false, 'client_id' => '', 'client_secret' => ''),
    'apple' => array('enabled' => false, 'client_id' => '', 'client_secret' => ''),
    'linkedin' => array('enabled' => false, 'client_id' => '', 'client_secret' => '')
));

// Show success message if settings were updated
if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') {
    echo '<div class="notice notice-success is-dismissible"><p>' . __('Social login settings saved successfully!', 'wp-alp') . '</p></div>';
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
            <form method="post" action="">
                <?php wp_nonce_field('wp_alp_social_nonce'); ?>
                
                <h2><?php esc_html_e('Social Login Providers', 'wp-alp'); ?></h2>
                <p><?php esc_html_e('Configure social login providers to allow users to sign in with their social accounts.', 'wp-alp'); ?></p>
                
                <!-- Google Settings -->
                <h3><?php esc_html_e('Google', 'wp-alp'); ?></h3>
                <p><?php esc_html_e('Configure Google login integration.', 'wp-alp'); ?></p>
                <p><strong><?php esc_html_e('Redirect URI:', 'wp-alp'); ?></strong> <code><?php echo esc_url(home_url('wp-json/wp-alp/v1/social/google/callback')); ?></code></p>
                
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><?php esc_html_e('Enable Google Login', 'wp-alp'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="google_enabled" value="1" <?php checked(isset($options['google']['enabled']) ? $options['google']['enabled'] : false); ?>>
                                    <?php esc_html_e('Enabled', 'wp-alp'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Client ID', 'wp-alp'); ?></th>
                            <td>
                                <input type="text" name="google_client_id" value="<?php echo esc_attr(isset($options['google']['client_id']) ? $options['google']['client_id'] : ''); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Client Secret', 'wp-alp'); ?></th>
                            <td>
                                <input type="text" name="google_client_secret" value="<?php echo esc_attr(isset($options['google']['client_secret']) ? $options['google']['client_secret'] : ''); ?>" class="regular-text">
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Facebook Settings -->
                <h3><?php esc_html_e('Facebook', 'wp-alp'); ?></h3>
                <p><?php esc_html_e('Configure Facebook login integration.', 'wp-alp'); ?></p>
                <p><strong><?php esc_html_e('Redirect URI:', 'wp-alp'); ?></strong> <code><?php echo esc_url(home_url('wp-json/wp-alp/v1/social/facebook/callback')); ?></code></p>
                
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><?php esc_html_e('Enable Facebook Login', 'wp-alp'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="facebook_enabled" value="1" <?php checked(isset($options['facebook']['enabled']) ? $options['facebook']['enabled'] : false); ?>>
                                    <?php esc_html_e('Enabled', 'wp-alp'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('App ID', 'wp-alp'); ?></th>
                            <td>
                                <input type="text" name="facebook_client_id" value="<?php echo esc_attr(isset($options['facebook']['client_id']) ? $options['facebook']['client_id'] : ''); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('App Secret', 'wp-alp'); ?></th>
                            <td>
                                <input type="text" name="facebook_client_secret" value="<?php echo esc_attr(isset($options['facebook']['client_secret']) ? $options['facebook']['client_secret'] : ''); ?>" class="regular-text">
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- LinkedIn Settings -->
                <h3><?php esc_html_e('LinkedIn', 'wp-alp'); ?></h3>
                <p><?php esc_html_e('Configure LinkedIn login integration.', 'wp-alp'); ?></p>
                <p><strong><?php esc_html_e('Redirect URI:', 'wp-alp'); ?></strong> <code><?php echo esc_url(home_url('wp-json/wp-alp/v1/social/linkedin/callback')); ?></code></p>
                
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><?php esc_html_e('Enable LinkedIn Login', 'wp-alp'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="linkedin_enabled" value="1" <?php checked(isset($options['linkedin']['enabled']) ? $options['linkedin']['enabled'] : false); ?>>
                                    <?php esc_html_e('Enabled', 'wp-alp'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Client ID', 'wp-alp'); ?></th>
                            <td>
                                <input type="text" name="linkedin_client_id" value="<?php echo esc_attr(isset($options['linkedin']['client_id']) ? $options['linkedin']['client_id'] : ''); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Client Secret', 'wp-alp'); ?></th>
                            <td>
                                <input type="text" name="linkedin_client_secret" value="<?php echo esc_attr(isset($options['linkedin']['client_secret']) ? $options['linkedin']['client_secret'] : ''); ?>" class="regular-text">
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Apple Settings -->
                <h3><?php esc_html_e('Apple', 'wp-alp'); ?></h3>
                <p><?php esc_html_e('Configure Apple login integration.', 'wp-alp'); ?></p>
                <p><strong><?php esc_html_e('Redirect URI:', 'wp-alp'); ?></strong> <code><?php echo esc_url(home_url('wp-json/wp-alp/v1/social/apple/callback')); ?></code></p>
                
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><?php esc_html_e('Enable Apple Login', 'wp-alp'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="apple_enabled" value="1" <?php checked(isset($options['apple']['enabled']) ? $options['apple']['enabled'] : false); ?>>
                                    <?php esc_html_e('Enabled', 'wp-alp'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Service ID', 'wp-alp'); ?></th>
                            <td>
                                <input type="text" name="apple_client_id" value="<?php echo esc_attr(isset($options['apple']['client_id']) ? $options['apple']['client_id'] : ''); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Private Key', 'wp-alp'); ?></th>
                            <td>
                                <textarea name="apple_client_secret" rows="5" class="large-text"><?php echo esc_textarea(isset($options['apple']['client_secret']) ? $options['apple']['client_secret'] : ''); ?></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <input type="hidden" name="wp_alp_save_social_options" value="1">
                <?php submit_button(__('Save Social Settings', 'wp-alp')); ?>
            </form>
        </div>
        
        <div class="wp-alp-admin-sidebar">
            <!-- Contenido de la barra lateral (sin cambios) -->
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