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
        
        // Password visibility toggle
        initPasswordToggle();
        
        // Login/Register modal
        initAuthModal();
        
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
     * Initialize password visibility toggle
     */
    function initPasswordToggle() {
        $('.wp-alp-toggle-password').on('click', function(e) {
            e.preventDefault();
            var $button = $(this);
            var $icon = $button.find('.dashicons');
            var $input = $button.closest('.wp-alp-password-wrapper').find('input');
            
            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            } else {
                $input.attr('type', 'password');
                $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            }
        });
    }

    /**
     * Initialize login/register modal functionality
     */
    function initAuthModal() {
        // Add modal HTML to the body
        var modalHtml = `
            <div id="wp-alp-modal-overlay" class="wp-alp-modal-overlay">
                <div id="wp-alp-modal" class="wp-alp-modal">
                    <div class="wp-alp-modal-header">
                        <h2 id="wp-alp-modal-title">Login or Register</h2>
                        <button id="wp-alp-modal-close" class="wp-alp-modal-close">&times;</button>
                    </div>
                    <div id="wp-alp-modal-content" class="wp-alp-modal-content">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        `;
        $('body').append(modalHtml);

        // Handle login links
        $('.wp-alp-login-trigger').on('click', function(e) {
            e.preventDefault();
            showAuthModal('login');
        });

        // Handle register links
        $('.wp-alp-register-trigger').on('click', function(e) {
            e.preventDefault();
            showAuthModal('register');
        });

        // Close modal on overlay click or close button
        $('#wp-alp-modal-overlay, #wp-alp-modal-close').on('click', function(e) {
            if (e.target === this) {
                closeAuthModal();
            }
        });

        // Prevent modal from closing when clicking inside content
        $('#wp-alp-modal').on('click', function(e) {
            e.stopPropagation();
        });

        // Handle tab switching in modal
        $('body').on('click', '.wp-alp-modal-tab', function(e) {
            e.preventDefault();
            var tabType = $(this).data('tab');
            showAuthModal(tabType);
        });
    }

    /**
     * Show authentication modal with specified type
     * 
     * @param {string} type The type of modal to show ('login' or 'register')
     */
    function showAuthModal(type) {
        var $modal = $('#wp-alp-modal-overlay');
        var $content = $('#wp-alp-modal-content');
        var $title = $('#wp-alp-modal-title');
        
        // Update title
        if (type === 'login') {
            $title.text('Log In');
        } else {
            $title.text('Sign Up');
        }
        
        // Show loading indicator
        $content.html('<div class="wp-alp-loading"><span class="dashicons dashicons-update-alt"></span></div>');
        
        // Show modal
        $modal.addClass('active');
        
        // Load form content via AJAX
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_get_form',
                type: type,
                security: wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $content.html(response.data.html);
                    // Initialize forms after loading
                    initModalForms();
                } else {
                    $content.html('<div class="wp-alp-message wp-alp-message-error">' + response.data.message + '</div>');
                }
            },
            error: function() {
                $content.html('<div class="wp-alp-message wp-alp-message-error">Error loading form. Please try again.</div>');
            }
        });
    }

    /**
     * Initialize forms in the modal
     */
    function initModalForms() {
        // Initialize password strength meter
        initPasswordStrengthMeter();
        
        // Initialize password toggle
        initPasswordToggle();
        
        // Handle form submissions
        $('#wp-alp-login-form').on('submit', function(e) {
            e.preventDefault();
            handleModalFormSubmit($(this), 'login');
        });
        
        $('#wp-alp-register-user-form').on('submit', function(e) {
            e.preventDefault();
            handleModalFormSubmit($(this), 'register_user');
        });
    }

    /**
     * Handle modal form submission
     * 
     * @param {jQuery} $form The form element
     * @param {string} formType The type of form
     */
    function handleModalFormSubmit($form, formType) {
        var $messagesContainer = $('#wp-alp-modal-messages');
        var $submitButton = $form.find('button[type="submit"]');
        
        // Clear previous messages
        $messagesContainer.empty();
        
        // Disable submit button
        $submitButton.prop('disabled', true).addClass('wp-alp-button-loading');
        
        // Collect form data
        var formData = new FormData($form.get(0));
        
        // Add action type
        formData.append('is_modal', 'true');
        
        // Send AJAX request
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log("Response:", response);
                
                try {
                    // Ensure we have a proper JSON response
                    if (typeof response === 'string') {
                        // Check if it's HTML
                        if (response.trim().startsWith('<!DOCTYPE') || response.trim().startsWith('<html')) {
                            console.error("Received HTML instead of JSON:", response.substring(0, 200));
                            throw new Error('Server returned HTML instead of JSON');
                        }
                        // Try to parse JSON
                        response = JSON.parse(response);
                    }
                    
                    if (response && response.success) {
                        // Display success message
                        var message = 'Operation completed successfully';
                        if (response.data && response.data.message) {
                            message = response.data.message;
                        } else if (response.message) {
                            message = response.message;
                        }
                        
                        $messagesContainer.html(
                            '<div class="wp-alp-message wp-alp-message-success">' + 
                            message + 
                            '</div>'
                        );
                        
                        // If profile completion is needed, redirect
                        if (response.data && response.data.needs_profile_completion) {
                            setTimeout(function() {
                                window.location.href = response.data.redirect;
                            }, 1000);
                            return;
                        }
                        
                        // Close modal and refresh page after success
                        setTimeout(function() {
                            closeAuthModal();
                            if (response.data && response.data.redirect) {
                                window.location.href = response.data.redirect;
                            } else {
                                window.location.reload();
                            }
                        }, 1500);
                    } else {
                        // Display error message
                        var errorMessage = 'An error occurred';
                        if (response && response.data && response.data.message) {
                            errorMessage = response.data.message;
                        } else if (response && response.message) {
                            errorMessage = response.message;
                        }
                        
                        $messagesContainer.html(
                            '<div class="wp-alp-message wp-alp-message-error">' + 
                            errorMessage + 
                            '</div>'
                        );
                        
                        // Re-enable submit button
                        $submitButton.prop('disabled', false).removeClass('wp-alp-button-loading');
                    }
                } catch (e) {
                    console.error('Error processing response:', e);
                    
                    // Display error message
                    $messagesContainer.html(
                        '<div class="wp-alp-message wp-alp-message-error">' + 
                        'An unexpected error occurred. Try refreshing the page and attempting again.' + 
                        '</div>'
                    );
                    
                    // Re-enable submit button
                    $submitButton.prop('disabled', false).removeClass('wp-alp-button-loading');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.error('Status:', status);
                console.error('Response Text:', xhr.responseText);
                
                // Display error message
                $messagesContainer.html(
                    '<div class="wp-alp-message wp-alp-message-error">' + 
                    wp_alp_ajax.ajax_error + 
                    '</div>'
                );
                
                // Re-enable submit button
                $submitButton.prop('disabled', false).removeClass('wp-alp-button-loading');
            }
        });
    }

    /**
     * Close authentication modal
     */
    function closeAuthModal() {
        $('#wp-alp-modal-overlay').removeClass('active');
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
                state: state,
                is_modal: 'true'
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