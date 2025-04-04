<?php
/**
 * Template for the login form.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/public/templates
 *
 * Variables available:
 * @var array  $atts           Shortcode attributes.
 * @var string $nonce          Security nonce.
 * @var string $csrf_token     CSRF token.
 * @var array  $options        Plugin options.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$redirect_to = !empty($atts['redirect']) ? $atts['redirect'] : '';
$show_title = filter_var($atts['show_title'], FILTER_VALIDATE_BOOLEAN);
$show_social = filter_var($atts['show_social'], FILTER_VALIDATE_BOOLEAN);
?>

<div class="wp-alp-form-container wp-alp-login-container">
    <?php if ($show_title) : ?>
        <h2 class="wp-alp-form-title"><?php esc_html_e('Login', 'wp-alp'); ?></h2>
    <?php endif; ?>
    
    <div class="wp-alp-form-wrapper">
        <form id="wp-alp-login-form" class="wp-alp-form" method="post">
            <div class="wp-alp-form-inner">
                <div class="wp-alp-form-field">
                    <label for="wp-alp-username-email"><?php esc_html_e('Username or Email', 'wp-alp'); ?></label>
                    <input type="text" id="wp-alp-username-email" name="username_email" required>
                </div>
                
                <div class="wp-alp-form-field">
    <label for="wp-alp-password"><?php esc_html_e('Password', 'wp-alp'); ?></label>
    <div class="wp-alp-password-wrapper">
        <input type="password" id="wp-alp-password" name="password" required>
        <button type="button" class="wp-alp-toggle-password" aria-label="<?php esc_attr_e('Toggle password visibility', 'wp-alp'); ?>">
            <span class="dashicons dashicons-visibility"></span>
        </button>
    </div>
</div>
                
                <div class="wp-alp-form-field wp-alp-checkbox-field">
                    <input type="checkbox" id="wp-alp-remember" name="remember" value="1">
                    <label for="wp-alp-remember"><?php esc_html_e('Remember Me', 'wp-alp'); ?></label>
                </div>
                
                <?php
                // Add anti-bot fields
                echo $this->security->add_anti_bot_fields();
                ?>
                
                <input type="hidden" name="action" value="wp_alp_login">
                <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>">
                <input type="hidden" name="_csrf_token" value="<?php echo esc_attr($csrf_token); ?>">
                <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
                
                <div class="wp-alp-form-submit">
                    <button type="submit" class="wp-alp-button wp-alp-login-button">
                        <?php esc_html_e('Login', 'wp-alp'); ?>
                    </button>
                </div>
            </div>
        </form>
        
        <div class="wp-alp-form-links">
            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="wp-alp-link wp-alp-lost-password-link">
                <?php esc_html_e('Forgot Password?', 'wp-alp'); ?>
            </a>
            
            <?php if (!empty($options['register_user_page'])) : ?>
                <span class="wp-alp-separator">|</span>
                <a href="<?php echo esc_url(get_permalink($options['register_user_page'])); ?>" class="wp-alp-link wp-alp-register-link">
                    <?php esc_html_e('Create an Account', 'wp-alp'); ?>
                </a>
            <?php endif; ?>
            
            <?php if (!empty($options['register_vendor_page'])) : ?>
                <span class="wp-alp-separator">|</span>
                <a href="<?php echo esc_url(get_permalink($options['register_vendor_page'])); ?>" class="wp-alp-link wp-alp-register-vendor-link">
                    <?php esc_html_e('Register as Vendor', 'wp-alp'); ?>
                </a>
            <?php endif; ?>
        </div>
        
        <?php if ($show_social && method_exists($this->social, 'render_social_buttons')) : ?>
            <div class="wp-alp-social-login">
                <div class="wp-alp-separator-text">
                    <span><?php esc_html_e('Or login with', 'wp-alp'); ?></span>
                </div>
                
                <?php echo $this->social->render_social_buttons('login'); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="wp-alp-form-messages" id="wp-alp-login-messages"></div>
</div>