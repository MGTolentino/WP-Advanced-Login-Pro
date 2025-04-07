<?php
/**
 * Template for the user registration form.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/public/templates
 *
 * Variables available:
 * @var array  $atts               Shortcode attributes.
 * @var string $nonce              Security nonce.
 * @var string $csrf_token         CSRF token.
 * @var array  $options            Plugin options.
 * @var string $recaptcha_site_key reCAPTCHA site key.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$redirect_to = !empty($atts['redirect']) ? $atts['redirect'] : '';
$show_title = filter_var($atts['show_title'], FILTER_VALIDATE_BOOLEAN);
$show_social = filter_var($atts['show_social'], FILTER_VALIDATE_BOOLEAN);
?>

<div class="wp-alp-form-container wp-alp-register-user-container">
    <?php if ($show_title) : ?>
        <h2 class="wp-alp-form-title"><?php esc_html_e('Create an Account', 'wp-alp'); ?></h2>
    <?php endif; ?>
    
    <div class="wp-alp-form-wrapper">
        <form id="wp-alp-register-user-form" class="wp-alp-form" method="post">
            <div class="wp-alp-form-inner">
                <div class="wp-alp-form-field">
                    <label for="wp-alp-email"><?php esc_html_e('Email', 'wp-alp'); ?> <span class="required">*</span></label>
                    <input type="email" id="wp-alp-email" name="email" required>
                </div>
                
                <div class="wp-alp-form-row">
                    <div class="wp-alp-form-field wp-alp-form-field-half">
                        <label for="wp-alp-first-name"><?php esc_html_e('First Name', 'wp-alp'); ?></label>
                        <input type="text" id="wp-alp-first-name" name="first_name">
                    </div>
                    
                    <div class="wp-alp-form-field wp-alp-form-field-half">
                        <label for="wp-alp-last-name"><?php esc_html_e('Last Name', 'wp-alp'); ?></label>
                        <input type="text" id="wp-alp-last-name" name="last_name">
                    </div>
                </div>
                
                <div class="wp-alp-form-field">
                    <label for="wp-alp-password"><?php esc_html_e('Password', 'wp-alp'); ?> <span class="required">*</span></label>
                    <div class="wp-alp-password-wrapper">
                        <input type="password" id="wp-alp-password" name="password" required>
                        <button type="button" class="wp-alp-toggle-password" aria-label="<?php esc_attr_e('Toggle password visibility', 'wp-alp'); ?>">
                            <span class="dashicons dashicons-visibility"></span>
                        </button>
                    </div>
                    <div class="wp-alp-password-strength" id="wp-alp-password-strength"></div>
                </div>
                
                <div class="wp-alp-form-field">
                    <label for="wp-alp-password-confirm"><?php esc_html_e('Confirm Password', 'wp-alp'); ?> <span class="required">*</span></label>
                    <div class="wp-alp-password-wrapper">
                        <input type="password" id="wp-alp-password-confirm" name="password_confirm" required>
                        <button type="button" class="wp-alp-toggle-password" aria-label="<?php esc_attr_e('Toggle password visibility', 'wp-alp'); ?>">
                            <span class="dashicons dashicons-visibility"></span>
                        </button>
                    </div>
                </div>
                
                <?php if (!empty($recaptcha_site_key)) : ?>
                    <div class="wp-alp-form-field wp-alp-recaptcha-field">
                        <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($recaptcha_site_key); ?>"></div>
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    </div>
                <?php endif; ?>
                
                <div class="wp-alp-form-field wp-alp-checkbox-field">
                    <input type="checkbox" id="wp-alp-terms" name="terms" value="1" required>
                    <label for="wp-alp-terms">
                        <?php
                        echo sprintf(
                            esc_html__('I agree to the %sTerms and Conditions%s and %sPrivacy Policy%s', 'wp-alp'),
                            '<a href="' . esc_url(get_privacy_policy_url()) . '" target="_blank">',
                            '</a>',
                            '<a href="' . esc_url(get_privacy_policy_url()) . '" target="_blank">',
                            '</a>'
                        );
                        ?>
                        <span class="required">*</span>
                    </label>
                </div>
                
                <?php
                // Add anti-bot fields
                echo $this->security->add_anti_bot_fields();
                ?>
                
                <input type="hidden" name="action" value="wp_alp_register_user">
                <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>">
                <input type="hidden" name="_csrf_token" value="<?php echo esc_attr($csrf_token); ?>">
                <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
                
                <div class="wp-alp-form-submit">
                    <button type="submit" class="wp-alp-button wp-alp-register-button">
                        <?php esc_html_e('Register', 'wp-alp'); ?>
                    </button>
                </div>
            </div>
        </form>
        
        <div class="wp-alp-form-links">
    <span><?php esc_html_e('Already have an account?', 'wp-alp'); ?></span>
    <a href="#" class="wp-alp-link wp-alp-modal-tab" data-tab="login">
        <?php esc_html_e('Login', 'wp-alp'); ?>
    </a>
    
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
                    <span><?php esc_html_e('Or register with', 'wp-alp'); ?></span>
                </div>
                
                <?php echo $this->social->render_social_buttons('register_user'); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="wp-alp-form-messages" id="wp-alp-register-user-messages"></div>
</div>