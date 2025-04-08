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
        
        // Form submissions
        initFormSubmissions();
        
        // Social login handlers
        initSocialLogin();
        
        // Modal login/register
        initModalForms();
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
 * Función corregida para inicializar el toggle de visibilidad de contraseña
 */
function initPasswordToggle() {
    // Usar delegación de eventos para capturar clics en el botón de toggle
    $(document).on('click', '.wp-alp-toggle-password', function(e) {
        e.preventDefault();
        console.log('Toggle password clicked');
        
        var $button = $(this);
        var $icon = $button.find('.dashicons');
        
        // Si no hay un icono dentro del botón, buscar en sus elementos hermanos
        if (!$icon.length) {
            $icon = $button.siblings('.dashicons');
        }
        
        // Buscar el campo de contraseña relacionado
        var $input = $button.closest('.wp-alp-password-wrapper').find('input[type="password"], input[type="text"]');
        
        // Si no se encuentra, intentar buscar en el padre
        if (!$input.length) {
            $input = $button.parent().find('input[type="password"], input[type="text"]');
        }
        
        // Si todavía no se encuentra, buscar en los hermanos
        if (!$input.length) {
            $input = $button.siblings('input[type="password"], input[type="text"]');
        }
        
        console.log('Input field found:', $input.length > 0);
        
        if ($input.length) {
            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                if ($icon.length) {
                    $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
                }
            } else {
                $input.attr('type', 'password');
                if ($icon.length) {
                    $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
                }
            }
        }
    });
}

    /**
 * Initialize modal forms
 */
function initModalForms() {
    // Login trigger
    $('.wp-alp-login-trigger').on('click', function(e) {
        e.preventDefault();
        loadModalForm('login');
    });
    
    // Register trigger
    $('.wp-alp-register-trigger').on('click', function(e) {
        e.preventDefault();
        loadModalForm('register');
    });
    
    // Close modal
    $(document).on('click', '.wp-alp-modal-close, .wp-alp-modal-overlay', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Tab switching
    $(document).on('click', '.wp-alp-modal-tab', function(e) {
        e.preventDefault();
        var tab = $(this).data('tab');
        loadModalForm(tab);
    });
    
    // Handle ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('.wp-alp-modal').length) {
            closeModal();
        }
    });
    
    // Handle form submission in modal
    $(document).on('submit', '.wp-alp-modal .wp-alp-form', function(e) {
        e.preventDefault();
        handleModalFormSubmit($(this));
    });
    
    // Handle Skip Profile button in modal
    $(document).on('click', '.wp-alp-modal #wp-alp-skip-profile', function(e) {
        e.preventDefault();
        var redirectTo = $(this).data('redirect') || wp_alp_ajax.home_url;
        window.location.href = redirectTo;
    });
}

    /**
     * Load modal form via AJAX
     */
    function loadModalForm(type) {
        // Create modal if it doesn't exist
        if (!$('.wp-alp-modal').length) {
            var modalHtml = '<div class="wp-alp-modal-overlay">' +
                '<div class="wp-alp-modal">' +
                '<a href="#" class="wp-alp-modal-close">&times;</a>' +
                '<div class="wp-alp-modal-content"></div>' +
                '</div>' +
                '</div>';
            $('body').append(modalHtml);
        }
        
        var $modalContent = $('.wp-alp-modal-content');
        
        // Show loading spinner
        $modalContent.html('<div class="wp-alp-loading-spinner"></div>');
        
        // Load form via AJAX
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
                    $modalContent.html(response.data.html);
                    
                    // Update active tab
                    $('.wp-alp-modal-tab').removeClass('active');
                    $('.wp-alp-modal-tab[data-tab="' + type + '"]').addClass('active');
                } else {
                    $modalContent.html('<div class="wp-alp-message wp-alp-message-error">' + 
                        wp_alp_ajax.ajax_error + '</div>');
                }
            },
            error: function() {
                $modalContent.html('<div class="wp-alp-message wp-alp-message-error">' + 
                    wp_alp_ajax.ajax_error + '</div>');
            }
        });
        
        // Add active class to modal
        $('.wp-alp-modal-overlay').addClass('active');
    }

/**
 * Load profile completion form via AJAX
 */
function loadProfileCompletionForm() {
    var $modalContent = $('.wp-alp-modal-content');
    
    // Show loading spinner
    $modalContent.html('<div class="wp-alp-loading-spinner"></div>');
    
    // Load form via AJAX
    $.ajax({
        url: wp_alp_ajax.ajax_url,
        type: 'POST',
        data: {
            action: 'wp_alp_get_profile_form',
            security: wp_alp_ajax.nonce
        },
        success: function(response) {
            if (response.success) {
                // Replace tabs with title
                var tabsHtml = '<div class="wp-alp-modal-tabs"><h3>' + 
                    'Complete Your Profile' + '</h3></div>';
                
                // Remove existing tabs
                $('.wp-alp-modal-tabs').remove();
                
                // Add the title at the top of modal
                $('.wp-alp-modal').prepend(tabsHtml);
                
                // Set form content
                $modalContent.html(response.data.html);
            } else {
                $modalContent.html('<div class="wp-alp-message wp-alp-message-error">' + 
                    wp_alp_ajax.ajax_error + '</div>');
            }
        },
        error: function() {
            $modalContent.html('<div class="wp-alp-message wp-alp-message-error">' + 
                wp_alp_ajax.ajax_error + '</div>');
        }
    });
}

    /**
     * Close modal
     */
    function closeModal() {
        $('.wp-alp-modal-overlay').removeClass('active');
        
        // Optional: Remove modal from DOM after animation completes
        setTimeout(function() {
            if (!$('.wp-alp-modal-overlay').hasClass('active')) {
                $('.wp-alp-modal-overlay').remove();
            }
        }, 300);
    }

/**
 * Handle modal form submission
 */
function handleModalFormSubmit($form) {
    var formType = $form.data('form-type');
    var $messagesContainer = $('#wp-alp-modal-messages');
    var $submitButton = $form.find('button[type="submit"]');
    
    // Clear previous messages
    $messagesContainer.empty();
    
    // Disable submit button
    $submitButton.prop('disabled', true).addClass('wp-alp-button-loading');
    
    // Get form action URL
    var actionUrl = wp_alp_ajax.ajax_url;
    
    // Collect form data
    var formData = new FormData($form.get(0));
    
    // Add AJAX flag
    formData.append('is_ajax', 'true');
    formData.append('is_modal', 'true');
    
    // Add security token if not already present
    if (!formData.has('security')) {
        formData.append('security', wp_alp_ajax.nonce);
    }
    
    // Send AJAX request
    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            try {
                // If response is a string (HTML), we have an issue
                if (typeof response === 'string' && (response.includes('<!DOCTYPE') || response.includes('<html'))) {
                    console.error('Received HTML instead of JSON');
                    throw new Error('Server returned HTML instead of JSON');
                }
                
                // Ensure response is an object
                if (typeof response === 'string') {
                    response = JSON.parse(response);
                }
                
                if (response.success) {
                    // Display success message
                    $messagesContainer.html(
                        '<div class="wp-alp-message wp-alp-message-success">' + 
                        response.data.message + 
                        '</div>'
                    );
                    
                    // Handle profile completion if needed
                    if (formType === 'profile_completion' || (formType !== 'profile_completion' && !response.data.needs_profile_completion)) {
                        // Profile is complete or was just completed - redirect
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 1000);
                    } else if (response.data.needs_profile_completion) {
                        // User needs to complete profile - load profile form
                        loadProfileCompletionForm();
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
            console.error('AJAX Error:', status, error);
            console.log('Response Text:', xhr.responseText);
            
            // Si el login fue exitoso pero la respuesta es vacía, redirigir al home
            if (status === "parsererror" && xhr.responseText === "") {
                setTimeout(function() {
                    window.location.href = wp_alp_ajax.home_url;
                }, 1000);
                return;
            }
            
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
        
        // Add flag to indicate this is an AJAX request
        formData.append('is_ajax', 'true');
        
        // Ensure we have the security nonce
        if (!formData.has('security')) {
            formData.append('security', wp_alp_ajax.nonce);
        }
        
        // Send AJAX request
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    // If response is a string (HTML), we have an issue
                    if (typeof response === 'string' && (response.includes('<!DOCTYPE') || response.includes('<html'))) {
                        console.error('Received HTML instead of JSON');
                        throw new Error('Server returned HTML instead of JSON');
                    }
                    
                    // Ensure response is an object
                    if (typeof response === 'string') {
                        response = JSON.parse(response);
                    }
                    
                    if (response.success) {
                        // Display success message
                        $messagesContainer.html(
                            '<div class="wp-alp-message wp-alp-message-success">' + 
                            response.data.message + 
                            '</div>'
                        );
                        
                        // Redirect if needed
                        if (response.data && response.data.redirect) {
                            setTimeout(function() {
                                window.location.href = response.data.redirect;
                            }, 1500);
                        } else {
                            // Refresh page if no redirect specified
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        }
                    } else {
                        // Display error message
                        var errorMessage = 'An error occurred';
                        if (response.data && response.data.message) {
                            errorMessage = response.data.message;
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
                console.error('AJAX Error:', status, error);
                
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
     * Initialize social login
     */
    function initSocialLogin() {
        // Handle social login button clicks
        $('.wp-alp-social-button').on('click', function(e) {
            // The actual redirection is handled by the href attribute
            
            // Add analytics tracking if available
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
                is_ajax: 'true'
            },
            success: function(response) {
                try {
                    // If response is a string (HTML), we have an issue
                    if (typeof response === 'string' && (response.includes('<!DOCTYPE') || response.includes('<html'))) {
                        console.error('Received HTML instead of JSON');
                        throw new Error('Server returned HTML instead of JSON');
                    }
                    
                    // Parse JSON if needed
                    if (typeof response === 'string') {
                        response = JSON.parse(response);
                    }
                    
                    if (response.success && response.data && response.data.redirect) {
                        // Redirect to the specified URL
                        window.location.href = response.data.redirect;
                    } else {
                        // Display error message
                        var errorMessage = wp_alp_ajax.social_login_error;
                        if (response.data && response.data.message) {
                            errorMessage = response.data.message;
                        }
                        alert(errorMessage);
                        
                        // Redirect to login page
                        window.location.href = wp_alp_ajax.login_url;
                    }
                } catch (e) {
                    console.error('Error processing response:', e);
                    alert(wp_alp_ajax.social_login_error);
                    
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

    // Initialize when the DOM is ready
    $(document).ready(init);

})(jQuery);