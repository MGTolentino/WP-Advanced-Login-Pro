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
    // Login/Register trigger
    $('.wp-alp-login-trigger, .wp-alp-register-trigger').on('click', function(e) {
        e.preventDefault();
        loadInitialForm();
    });
    
    // Close modal
    $(document).on('click', '.wp-alp-modal-close, .wp-alp-modal-overlay', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // Handle ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('.wp-alp-modal').length) {
            closeModal();
        }
    });
    
    // Handle tab navigation if still needed
    $(document).on('click', '.wp-alp-modal-tab', function(e) {
        e.preventDefault();
        var tab = $(this).data('tab');
        if (tab === 'login' || tab === 'register') {
            loadModalForm(tab);
        }
    });
    
    // Handle initial form submission (email/phone check)
    $(document).on('submit', '#wp-alp-initial-form', function(e) {
        e.preventDefault();
        handleInitialFormSubmit($(this));
    });
    
    // Handle combined form submission
    $(document).on('submit', '#wp-alp-combined-form', function(e) {
        e.preventDefault();
        handleCombinedFormSubmit($(this));
    });
    
    // Handle form submission in modal
    $(document).on('submit', '.wp-alp-modal .wp-alp-form', function(e) {
        e.preventDefault();
        var formId = $(this).attr('id');
        
        // Skip already handled forms
        if (formId === 'wp-alp-initial-form' || formId === 'wp-alp-combined-form') {
            return;
        }
        
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
 * Load initial form (email/phone input + social buttons)
 */
function loadInitialForm() {
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
    
    // Load initial form via AJAX
    $.ajax({
        url: wp_alp_ajax.ajax_url,
        type: 'POST',
        data: {
            action: 'wp_alp_get_initial_form',
            security: wp_alp_ajax.nonce
        },
        success: function(response) {
            if (response.success) {
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
    
    // Add active class to modal
    $('.wp-alp-modal-overlay').addClass('active');
}

/**
 * Handle initial form submission (email/phone check)
 */
function handleInitialFormSubmit($form) {
    var $messagesContainer = $('#wp-alp-initial-messages');
    var $submitButton = $form.find('button[type="submit"]');
    var identifier = $form.find('input[name="identifier"]').val();
    
    // Clear previous messages
    $messagesContainer.empty();
    
    // Disable submit button
    $submitButton.prop('disabled', true).addClass('wp-alp-button-loading');
    
    // Check identifier via AJAX
    $.ajax({
        url: wp_alp_ajax.ajax_url,
        type: 'POST',
        data: {
            action: 'wp_alp_check_identifier',
            identifier: identifier,
            security: wp_alp_ajax.nonce
        },
        success: function(response) {
            if (response.success) {
                var data = response.data;
                
                if (data.exists) {
                    if (data.is_subscriber && data.profile_incomplete) {
                        // Existing subscriber with incomplete profile
                        // Show password entry screen first
                        loadPasswordForm(identifier, data.is_phone);
                    } else {
                        // Regular user, show login form
                        loadModalForm('login');
                    }
                } else {
                    // New user, show combined registration form
                    loadCombinedForm(identifier, data.is_phone, true);
                }
            } else {
                $messagesContainer.html('<div class="wp-alp-message wp-alp-message-error">' + 
                    response.data.message + '</div>');
                    
                // Re-enable submit button
                $submitButton.prop('disabled', false).removeClass('wp-alp-button-loading');
            }
        },
        error: function() {
            $messagesContainer.html('<div class="wp-alp-message wp-alp-message-error">' + 
                wp_alp_ajax.ajax_error + '</div>');
                
            // Re-enable submit button
            $submitButton.prop('disabled', false).removeClass('wp-alp-button-loading');
        }
    });
}

/**
 * Load password entry form
 */
function loadPasswordForm(identifier, isPhone) {
    var $modalContent = $('.wp-alp-modal-content');
    
    // Show loading spinner
    $modalContent.html('<div class="wp-alp-loading-spinner"></div>');
    
    var html = '<div id="wp-alp-modal-messages" class="wp-alp-form-messages"></div>' +
        '<div class="wp-alp-form-container wp-alp-password-container">' +
        '<h2 class="wp-alp-form-title">' + (isPhone ? 'Enter your password' : 'Welcome back') + '</h2>' +
        '<div class="wp-alp-form-wrapper">' +
        '<form id="wp-alp-password-form" class="wp-alp-form" data-form-type="password">' +
        '<div class="wp-alp-form-inner">' +
        '<div class="wp-alp-identifier-display">' + identifier + '</div>' +
        '<div class="wp-alp-form-field">' +
        '<label for="wp-alp-login-password">Password</label>' +
        '<div class="wp-alp-password-wrapper">' +
        '<input type="password" id="wp-alp-login-password" name="password" required>' +
        '<button type="button" class="wp-alp-toggle-password" aria-label="Toggle password visibility">' +
        '<span class="dashicons dashicons-visibility"></span>' +
        '</button>' +
        '</div>' +
        '</div>' +
        '<input type="hidden" name="action" value="wp_alp_login">' +
        '<input type="hidden" name="username_email" value="' + identifier + '">' +
        '<input type="hidden" name="security" value="' + wp_alp_ajax.nonce + '">' +
        '<input type="hidden" name="is_ajax" value="true">' +
        '<input type="hidden" name="is_modal" value="true">' +
        '<div class="wp-alp-form-submit">' +
        '<button type="submit" class="wp-alp-button wp-alp-login-button">Log in</button>' +
        '</div>' +
        '<div class="wp-alp-forgot-password">' +
        '<a href="' + wp_alp_ajax.lostpassword_url + '">Forgot password?</a>' + // Cambiar esta línea
        '</div>' +
        '</div>' +
        '</form>' +
        '</div>' +
        '</div>';

    $modalContent.html(html);

    // Add handler for the password form
    $('#wp-alp-password-form').on('submit', function(e) {
        e.preventDefault();
        handlePasswordFormSubmit($(this), identifier, isPhone);
    });
}

/**
* Handle password form submission
*/
function handlePasswordFormSubmit($form, identifier, isPhone) {
   var $messagesContainer = $('#wp-alp-modal-messages');
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
           try {
               // If response is a string (HTML), we have an issue
               if (typeof response === 'string' && (response.includes('<!DOCTYPE') || response.includes('<html'))) {
                   console.error('Received HTML instead of JSON');
                   throw new Error('Server returned HTML instead of JSON');
               }
               
               // Handle empty response as successful login
               if (typeof response === 'string' && response.trim() === '') {
                   setTimeout(function() {
                       // Load profile completion form for subscribers with incomplete profile
                       loadCombinedForm(identifier, isPhone, false);
                   }, 1000);
                   return;
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
                   
                   if (response.data.needs_profile_completion) {
                       // Load profile completion form
                       setTimeout(function() {
                           loadCombinedForm(identifier, isPhone, false);
                       }, 1000);
                   } else {
                       // Regular login redirect
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
           } catch (e) {
               console.error('Error processing response:', e);
               
               // Handle AJAX parsing error
               if (e.message === 'Unexpected end of JSON input' || e.message.includes('JSON')) {
                   // Assume login succeeded but response is malformed
                   $messagesContainer.html(
                       '<div class="wp-alp-message wp-alp-message-success">' + 
                       'Login successful. Proceeding...' + 
                       '</div>'
                   );
                   
                   setTimeout(function() {
                       // Load profile completion form
                       loadCombinedForm(identifier, isPhone, false);
                   }, 1000);
               } else {
                   // Display error message
                   $messagesContainer.html(
                       '<div class="wp-alp-message wp-alp-message-error">' + 
                       'An unexpected error occurred. Try refreshing the page and attempting again.' + 
                       '</div>'
                   );
                   
                   // Re-enable submit button
                   $submitButton.prop('disabled', false).removeClass('wp-alp-button-loading');
               }
           }
       },
       error: function(xhr, status, error) {
           console.error('AJAX Error:', status, error);
           console.log('Response Text:', xhr.responseText);
           
           // Handle empty response with parse error as successful login
           if (status === "parsererror" && xhr.responseText.trim() === "") {
               console.log('Empty response with parse error, assuming successful login');
               $messagesContainer.html(
                   '<div class="wp-alp-message wp-alp-message-success">' + 
                   'Login successful. Proceeding...' + 
                   '</div>'
               );
               setTimeout(function() {
                   // Load profile completion form
                   loadCombinedForm(identifier, isPhone, false);
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
* Load combined registration and profile completion form
*/
function loadCombinedForm(identifier, isPhone, isNewUser) {
   var $modalContent = $('.wp-alp-modal-content');
   
   // Show loading spinner
   $modalContent.html('<div class="wp-alp-loading-spinner"></div>');
   
   // Format data based on identifier type
   var email = isPhone ? '' : identifier;
   var phone = isPhone ? identifier : '';
   
   // Load combined form via AJAX
   $.ajax({
       url: wp_alp_ajax.ajax_url,
       type: 'POST',
       data: {
           action: 'wp_alp_get_combined_form',
           email: email,
           phone: phone,
           is_phone: isPhone,
           is_new_user: isNewUser,
           security: wp_alp_ajax.nonce
       },
       success: function(response) {
           if (response.success) {
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
* Handle combined form submission
*/
function handleCombinedFormSubmit($form) {
   var $messagesContainer = $('#wp-alp-combined-messages');
   var $submitButton = $form.find('button[type="submit"]');
   
   // Clear previous messages
   $messagesContainer.empty();
   
   // Disable submit button
   $submitButton.prop('disabled', true).addClass('wp-alp-button-loading');
   
   // Collect form data
   var formData = new FormData($form.get(0));
   
   // Add AJAX flags
   formData.append('is_ajax', 'true');
   formData.append('is_modal', 'true');
   
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
               
               // Handle empty response as successful submission
               if (typeof response === 'string' && response.trim() === '') {
                   $messagesContainer.html(
                       '<div class="wp-alp-message wp-alp-message-success">' + 
                       'Registration successful. Redirecting...' + 
                       '</div>'
                   );
                   setTimeout(function() {
                       window.location.reload();
                   }, 1500);
                   return;
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
                   
                   // Redirect to specified URL
                   setTimeout(function() {
                       window.location.href = response.data.redirect;
                   }, 1500);
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
               
               // Handle JSON parsing errors
               if (e.message === 'Unexpected end of JSON input' || e.message.includes('JSON')) {
                   // Assume submission succeeded but response is malformed
                   $messagesContainer.html(
                       '<div class="wp-alp-message wp-alp-message-success">' + 
                       'Registration successful. Redirecting...' + 
                       '</div>'
                   );
                   setTimeout(function() {
                       window.location.reload();
                   }, 1500);
               } else {
                   // Display error message
                   $messagesContainer.html(
                       '<div class="wp-alp-message wp-alp-message-error">' + 
                       'An unexpected error occurred. Try refreshing the page and attempting again.' + 
                       '</div>'
                   );
                   
                   // Re-enable submit button
                   $submitButton.prop('disabled', false).removeClass('wp-alp-button-loading');
               }
           }
       },
       error: function(xhr, status, error) {
           console.error('AJAX Error:', status, error);
           console.log('Response Text:', xhr.responseText);
           
           // Handle empty response with parse error as successful submission
           if (status === "parsererror" && xhr.responseText.trim() === "") {
               console.log('Empty response with parse error, assuming successful submission');
               $messagesContainer.html(
                   '<div class="wp-alp-message wp-alp-message-success">' + 
                   'Registration successful. Redirecting...' + 
                   '</div>'
               );
               setTimeout(function() {
                   window.location.reload();
               }, 1500);
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

    function handleModalFormSubmit($form) {
        var formType = $form.attr('id') || '';
        if (formType.includes('login')) {
            formType = 'login';
        } else if (formType.includes('register')) {
            formType = 'register';
        } else if (formType.includes('profile-completion')) {
            formType = 'profile_completion';
        }
        
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
                        if (response.trim() === '') {
                            // Handle empty response as successful login
                            console.log('Empty response received, assuming successful login');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                            return;
                        }
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
                
                // Handle empty response with parse error as successful login
                if (status === "parsererror" && xhr.responseText.trim() === "") {
                    console.log('Empty response with parse error, assuming successful login');
                    $messagesContainer.html(
                        '<div class="wp-alp-message wp-alp-message-success">' + 
                        'Login successful. Redirecting...' + 
                        '</div>'
                    );
                    setTimeout(function() {
                        window.location.reload();
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