/**
 * Public JavaScript for WP Advanced Login Pro
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/public/js
 */

(function($) {
    'use strict';

    /**
     * Initialize the plugin's public functionality
     */
    function init() {
        // Password strength meter
        initPasswordStrengthMeter();
        
        // Form submissions
        initFormSubmissions();
        
        // Social login handlers
        initSocialLogin();
    }

    /**
     * Initialize password strength meter
     */
    function initPasswordStrengthMeter() {
        $('.wp-alp-form').each(function() {
            var $form = $(this);
            var $passwordField = $form.find('input[name="password"]');
            var $confirmField = $form.find('input[name="password_confirm"]');
            var $strengthMeter = $form.find('.wp-alp-password-strength');
            
            if ($passwordField.length && $strengthMeter.length) {
                $passwordField.on('keyup', function() {
                    var password = $(this).val();
                    var strength = calculatePasswordStrength(password);
                    updateStrengthMeter($strengthMeter, strength);
                });
            }
            
            if ($passwordField.length && $confirmField.length) {
                $confirmField.on('keyup', function() {
                    if ($(this).val() !== $passwordField.val()) {
                        $(this).get(0).setCustomValidity(wp_alp_ajax.password_mismatch);
                    } else {
                        $(this).get(0).setCustomValidity('');
                    }
                });
            }
        });
    }

    /**
     * Calculate password strength
     * 
     * @param {string} password The password to check
     * @return {number} Strength score (0-5)
     */
    function calculatePasswordStrength(password) {
        var strength = 0;
        
        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]+/)) strength += 1;
        if (password.match(/[A-Z]+/)) strength += 1;
        if (password.match(/[0-9]+/)) strength += 1;
        if (password.match(/[^a-zA-Z0-9]+/)) strength += 1;
        
        return strength;
    }

    /**
     * Update strength meter display
     * 
     * @param {jQuery} $meter The strength meter element
     * @param {number} strength The strength score (0-5)
     */
    function updateStrengthMeter($meter, strength) {
        $meter.removeClass('weak medium strong');
        
        if (strength <= 1) {
            $meter.addClass('weak').text(wp_alp_ajax.weak_password);
        } else if (strength <= 3) {
            $meter.addClass('medium').text(wp_alp_ajax.medium_password);
        } else {
            $meter.addClass('strong').text(wp_alp_ajax.strong_password);
        }
    }

    /**
     * Initialize form submissions
     */
    function initFormSubmissions() {
        // Login form
        $('#wp-alp-login-form').on('submit', function(e) {
            e.preventDefault();
            handleFormSubmit($(this), 'login');
        });
        
        // Register user form
        $('#wp-alp-register-user-form').on('submit', function(e) {
            e.preventDefault();
            handleFormSubmit($(this), 'register_user');
        });
        
        // Register vendor form
        $('#wp-alp-register-vendor-form').on('submit', function(e) {
            e.preventDefault();
            handleFormSubmit($(this), 'register_vendor');
        });
        
        // Profile completion form
        $('#wp-alp-profile-completion-form').on('submit', function(e) {
            e.preventDefault();
            handleFormSubmit($(this), 'complete_profile');
        });
        
        // Skip profile completion
        $('#wp-alp-skip-profile').on('click', function(e) {
            e.preventDefault();
            var redirectTo = $(this).data('redirect') || wp_alp_ajax.home_url;
            window.location.href = redirectTo;
        });
    }

    /**
     * Handle form submission
     * 
     * @param {jQuery} $form The form element
     * @param {string} formType The type of form
     */
    function handleFormSubmit($form, formType) {
        var $messagesContainer = $('#wp-alp-' + formType + '-messages');
        var $submitButton = $form.find('button[type="submit"]');
        
        // Clear previous messages
        $messagesContainer.empty();
        
        // Disable submit button
        $submitButton.prop('disabled', true).addClass('wp-alp-button-loading');
        
        // Collect form data
        var formData = new FormData($form.get(0));
        
        // Send AJAX request
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Display success message
                    $messagesContainer.html(
                        '<div class="wp-alp-message wp-alp-message-success">' + 
                        response.data.message + 
                        '</div>'
                    );
                    
                    // Redirect if needed
                    if (response.data.redirect) {
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 1000);
                    }
                } else {
                    // Display error message
                    $messagesContainer.html(
                        '<div class="wp-alp-message wp-alp-message-error">' + 
                        response.data.message + 
                        '</div>'
                    );
                    
                    // Re-enable submit button
                    $submitButton.prop('disabled', false).removeClass('wp-alp-button-loading');
                }
            },
            error: function(xhr, status, error) {
                // Display error message
                $messagesContainer.html(
                    '<div class="wp-alp-message wp-alp-message-error">' + 
                    wp_alp_ajax.ajax_error + 
                    '</div>'
                );
                
                // Re-enable submit button
                $submitButton.prop('disabled', false).removeClass('wp-alp-button-loading');
                
                console.error('AJAX Error:', error);
            }
        });
    }

    /**
     * Initialize social login
     */
    function initSocialLogin() {
        // Handle social login button clicks
        $('.wp-alp-social-button').on('click', function(e) {
            // The actual redirection is handled by the href attribute
            // This function can be used for tracking or additional functionality
            
            // You could add analytics tracking here
            if (typeof gtag === 'function') {
                var provider = $(this).data('provider');
                gtag('event', 'social_login_click', {
                    'event_category': 'Authentication',
                    'event_label': provider
                });
            }
        });
        
        // Check for social login response in URL parameters
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('wp-alp-social-login') && urlParams.has('provider')) {
            var provider = urlParams.get('provider');
            var code = urlParams.get('code');
            var state = urlParams.get('state');
            
            if (code && state) {
                handleSocialLoginCallback(provider, code, state);
            }
        }
    }

    /**
     * Handle social login callback
     * 
     * @param {string} provider The social provider
     * @param {string} code The authorization code
     * @param {string} state The state parameter
     */
    function handleSocialLoginCallback(provider, code, state) {
        // Show loading overlay
        $('body').append('<div class="wp-alp-loading-overlay"><div class="wp-alp-loading-spinner"></div></div>');
        
        // Send AJAX request to process social login
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_social_login',
                provider: provider,
                code: code,
                state: state
            },
            success: function(response) {
                if (response.success && response.data.redirect) {
                    // Redirect to the specified URL
                    window.location.href = response.data.redirect;
                } else {
                    // Display error message
                    alert(response.data.message || wp_alp_ajax.social_login_error);
                    // Redirect to login page
                    window.location.href = wp_alp_ajax.login_url;
                }
            },
            error: function() {
                // Display error message
                alert(wp_alp_ajax.social_login_error);
                // Redirect to login page
                window.location.href = wp_alp_ajax.login_url;
            }
        });
    }

    /**
     * Helper function to validate email
     * 
     * @param {string} email The email to validate
     * @return {boolean} Whether the email is valid
     */
    function isValidEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    /**
     * Helper function to validate phone number
     * 
     * @param {string} phone The phone number to validate
     * @return {boolean} Whether the phone number is valid
     */
    function isValidPhone(phone) {
        var re = /^\+?[0-9]{10,15}$/;
        return re.test(String(phone).replace(/[\s\-\(\)]/g, ''));
    }

    // Initialize when the DOM is ready
    $(document).ready(init);

})(jQuery);