<?php
/**
 * Template for the vendor registration form.
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

// Si el usuario está logueado, verificar si ya es vendedor
$is_logged_in = is_user_logged_in();
$is_already_vendor = false;
$current_user_data = array();

if ($is_logged_in) {
    $user_id = get_current_user_id();
    $vendor_id = get_user_meta($user_id, 'hp_vendor_id', true);
    
    if ($vendor_id) {
        $is_already_vendor = true;
    } else {
        // Obtener datos del usuario actual para prellenar el formulario
        $user = wp_get_current_user();
        $current_user_data = array(
            'email' => $user->user_email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        );
    }
}
?>

<style>
.wp-alp-password-field {
    position: relative;
    display: flex;
}
.wp-alp-password-field input {
    flex: 1;
}
.wp-alp-toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    color: #666;
    cursor: pointer;
    padding: 0;
    font-size: 18px;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}
.wp-alp-toggle-password:hover {
    color: #333;
}
</style>

<div class="wp-alp-form-container wp-alp-register-vendor-container">
    <?php if ($show_title) : ?>
        <h2 class="wp-alp-form-title"><?php esc_html_e('Register as Vendor', 'wp-alp'); ?></h2>
    <?php endif; ?>
    
    <?php if ($is_already_vendor) : ?>
        <div class="wp-alp-message wp-alp-message-info">
            <p><?php esc_html_e('You are already registered as a vendor.', 'wp-alp'); ?></p>
        </div>
    <?php else : ?>
        <div class="wp-alp-form-wrapper">
            <form id="wp-alp-register-vendor-form" class="wp-alp-form" method="post">
                <div class="wp-alp-form-inner">
                    <div class="wp-alp-form-section">
                        <h3 class="wp-alp-section-title"><?php esc_html_e('Account Information', 'wp-alp'); ?></h3>
                        
                        <div class="wp-alp-form-field">
                            <label for="wp-alp-email"><?php esc_html_e('Email', 'wp-alp'); ?> <span class="required">*</span></label>
                            <input type="email" id="wp-alp-email" name="email" value="<?php echo esc_attr($is_logged_in ? $current_user_data['email'] : ''); ?>" <?php echo $is_logged_in ? 'readonly' : ''; ?> required>
                        </div>
                        
                        <?php if (!$is_logged_in) : ?>
                            <div class="wp-alp-form-field">
                                <label for="wp-alp-password"><?php esc_html_e('Password', 'wp-alp'); ?> <span class="required">*</span></label>
                                <div class="wp-alp-password-field">
                                    <input type="password" id="wp-alp-password" name="password" required>
                                    <button type="button" class="wp-alp-toggle-password" aria-label="<?php esc_attr_e('Toggle password visibility', 'wp-alp'); ?>">
                                        <span class="dashicons dashicons-visibility"></span>
                                    </button>
                                </div>
                                <div class="wp-alp-password-strength" id="wp-alp-password-strength"></div>
                            </div>
                            
                            <div class="wp-alp-form-field">
                                <label for="wp-alp-password-confirm"><?php esc_html_e('Confirm Password', 'wp-alp'); ?> <span class="required">*</span></label>
                                <div class="wp-alp-password-field">
                                    <input type="password" id="wp-alp-password-confirm" name="password_confirm" required>
                                    <button type="button" class="wp-alp-toggle-password" aria-label="<?php esc_attr_e('Toggle password visibility', 'wp-alp'); ?>">
                                        <span class="dashicons dashicons-visibility"></span>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="wp-alp-form-section">
                        <h3 class="wp-alp-section-title"><?php esc_html_e('Personal Information', 'wp-alp'); ?></h3>
                        
                        <div class="wp-alp-form-row">
                            <div class="wp-alp-form-field wp-alp-form-field-half">
                                <label for="wp-alp-first-name"><?php esc_html_e('First Name', 'wp-alp'); ?> <span class="required">*</span></label>
                                <input type="text" id="wp-alp-first-name" name="first_name" value="<?php echo esc_attr($is_logged_in ? $current_user_data['first_name'] : ''); ?>" required>
                            </div>
                            
                            <div class="wp-alp-form-field wp-alp-form-field-half">
                                <label for="wp-alp-last-name"><?php esc_html_e('Last Name', 'wp-alp'); ?> <span class="required">*</span></label>
                                <input type="text" id="wp-alp-last-name" name="last_name" value="<?php echo esc_attr($is_logged_in ? $current_user_data['last_name'] : ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="wp-alp-form-field">
                            <label for="wp-alp-phone"><?php esc_html_e('Phone Number', 'wp-alp'); ?> <span class="required">*</span></label>
                            <input type="tel" id="wp-alp-phone" name="phone" required>
                        </div>
                    </div>
                    
                    <div class="wp-alp-form-section">
                        <h3 class="wp-alp-section-title"><?php esc_html_e('Business Information', 'wp-alp'); ?></h3>
                        
                        <div class="wp-alp-form-field">
                            <label for="wp-alp-company"><?php esc_html_e('Company Name', 'wp-alp'); ?></label>
                            <input type="text" id="wp-alp-company" name="company">
                        </div>
                        
                        <div class="wp-alp-form-field">
                            <label for="wp-alp-address"><?php esc_html_e('Address', 'wp-alp'); ?></label>
                            <textarea id="wp-alp-address" name="address" rows="3"></textarea>
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
                    
                    <div class="wp-alp-form-field wp-alp-checkbox-field">
                        <input type="checkbox" id="wp-alp-vendor-terms" name="vendor_terms" value="1" required>
                        <label for="wp-alp-vendor-terms">
                            <?php esc_html_e('I agree to the Vendor Terms of Service', 'wp-alp'); ?>
                            <span class="required">*</span>
                        </label>
                    </div>
                    
                    <?php
                    // Add anti-bot fields
                    echo $this->security->add_anti_bot_fields();
                    ?>
                    
                    <input type="hidden" name="action" value="wp_alp_register_vendor">
                    <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>">
                    <input type="hidden" name="_csrf_token" value="<?php echo esc_attr($csrf_token); ?>">
                    <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
                    <?php if ($is_logged_in) : ?>
                        <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">
                    <?php endif; ?>
                    
                    <div class="wp-alp-form-submit">
                        <button type="submit" class="wp-alp-button wp-alp-register-button">
                            <?php echo $is_logged_in ? esc_html__('Register as Vendor', 'wp-alp') : esc_html__('Register as Vendor', 'wp-alp'); ?>
                        </button>
                    </div>
                </div>
            </form>
            
            <div class="wp-alp-form-links">
                <span><?php esc_html_e('Already have an account?', 'wp-alp'); ?></span>
                <a href="<?php echo esc_url(get_permalink($options['login_page'])); ?>" class="wp-alp-link wp-alp-login-link">
                    <?php esc_html_e('Login', 'wp-alp'); ?>
                </a>
                
                <?php if (!empty($options['register_user_page'])) : ?>
                    <span class="wp-alp-separator">|</span>
                    <a href="<?php echo esc_url(get_permalink($options['register_user_page'])); ?>" class="wp-alp-link wp-alp-register-link">
                        <?php esc_html_e('Register as User', 'wp-alp'); ?>
                    </a>
                <?php endif; ?>
            </div>
            
            <?php if ($show_social && method_exists($this->social, 'render_social_buttons')) : ?>
                <div class="wp-alp-social-login">
                    <div class="wp-alp-separator-text">
                        <span><?php esc_html_e('Or register with', 'wp-alp'); ?></span>
                    </div>
                    
                    <?php echo $this->social->render_social_buttons('register_vendor'); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="wp-alp-form-messages" id="wp-alp-register-vendor-messages"></div>

        <script>
            (function() {
                var passwordField = document.getElementById('wp-alp-password');
                var confirmField = document.getElementById('wp-alp-password-confirm');
                var strengthMeter = document.getElementById('wp-alp-password-strength');
                
                // Password strength check (solo si el usuario no está logueado)
                if (passwordField && strengthMeter) {
                    passwordField.addEventListener('keyup', function() {
                        var password = this.value;
                        var strength = 0;
                        
                        if (password.length >= 8) strength += 1;
                        if (password.match(/[a-z]+/)) strength += 1;
                        if (password.match(/[A-Z]+/)) strength += 1;
                        if (password.match(/[0-9]+/)) strength += 1;
                        if (password.match(/[^a-zA-Z0-9]+/)) strength += 1;
                        
                        switch (strength) {
                            case 0:
                            case 1:
                                strengthMeter.className = 'wp-alp-password-strength weak';
                                strengthMeter.innerHTML = '<?php esc_html_e('Weak', 'wp-alp'); ?>';
                                break;
                            case 2:
                            case 3:
                                strengthMeter.className = 'wp-alp-password-strength medium';
                                strengthMeter.innerHTML = '<?php esc_html_e('Medium', 'wp-alp'); ?>';
                                break;
                            case 4:
                            case 5:
                                strengthMeter.className = 'wp-alp-password-strength strong';
                                strengthMeter.innerHTML = '<?php esc_html_e('Strong', 'wp-alp'); ?>';
                                break;
                        }
                    });
                }
                
                // Password confirmation check (solo si el usuario no está logueado)
                if (passwordField && confirmField) {
                    confirmField.addEventListener('keyup', function() {
                        if (this.value !== passwordField.value) {
                            this.setCustomValidity('<?php esc_html_e('Passwords do not match', 'wp-alp'); ?>');
                        } else {
                            this.setCustomValidity('');
                        }
                    });
                }
                
                // Password toggle functionality
                var toggleButtons = document.querySelectorAll('.wp-alp-toggle-password');
                toggleButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        var passwordField = this.parentNode.querySelector('input');
                        var icon = this.querySelector('.dashicons');
                        
                        if (passwordField.type === 'password') {
                            passwordField.type = 'text';
                            icon.classList.remove('dashicons-visibility');
                            icon.classList.add('dashicons-hidden');
                        } else {
                            passwordField.type = 'password';
                            icon.classList.remove('dashicons-hidden');
                            icon.classList.add('dashicons-visibility');
                        }
                    });
                });
                
                // Form submission
                document.getElementById('wp-alp-register-vendor-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    var form = this;
                    var messagesContainer = document.getElementById('wp-alp-register-vendor-messages');
                    var submitButton = form.querySelector('button[type="submit"]');
                    
                    // Clear previous messages
                    messagesContainer.innerHTML = '';
                    
                    // Disable submit button
                    submitButton.disabled = true;
                    submitButton.classList.add('wp-alp-button-loading');
                    
                    // Collect form data
                    var formData = new FormData(form);
                    
                    // Send AJAX request
                    fetch(wp_alp_ajax.ajax_url, {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(function(data) {
                        try {
                            if (data.success) {
                                // Display success message
                                messagesContainer.innerHTML = '<div class="wp-alp-message wp-alp-message-success">' + 
                                    (data.data && data.data.message ? data.data.message : '<?php esc_html_e("Registration successful!", "wp-alp"); ?>') + '</div>';
                                
                                // Redirect if needed
                                if (data.data && data.data.redirect) {
                                    setTimeout(function() {
                                        window.location.href = data.data.redirect;
                                    }, 1000);
                                }
                            } else {
                                // Display error message
                                messagesContainer.innerHTML = '<div class="wp-alp-message wp-alp-message-error">' + 
                                    (data.data && data.data.message ? data.data.message : '<?php esc_html_e("An error occurred during registration.", "wp-alp"); ?>') + '</div>';
                                
                                // Re-enable submit button
                                submitButton.disabled = false;
                                submitButton.classList.remove('wp-alp-button-loading');
                            }
                        } catch (e) {
                            console.error('Error processing response:', e);
                            messagesContainer.innerHTML = '<div class="wp-alp-message wp-alp-message-error"><?php esc_html_e("An unexpected error occurred. Please try again.", "wp-alp"); ?></div>';
                            submitButton.disabled = false;
                            submitButton.classList.remove('wp-alp-button-loading');
                        }
                    })
                    .catch(function(error) {
                        // Display error message
                        messagesContainer.innerHTML = '<div class="wp-alp-message wp-alp-message-error">' + 
                            '<?php esc_html_e('An error occurred. Please try again.', 'wp-alp'); ?>' + '</div>';
                        
                        // Re-enable submit button
                        submitButton.disabled = false;
                        submitButton.classList.remove('wp-alp-button-loading');
                        
                        console.error('Error:', error);
                    });
                });
            })();
        </script>
    <?php endif; ?>
</div>