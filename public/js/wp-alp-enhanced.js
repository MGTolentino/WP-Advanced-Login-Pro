(function($) {
    'use strict';

    // Variables globales
    var modal = {
        overlay: null,
        container: null,
        content: null,
        loader: null,
        closeBtn: null
    };

    /**
     * Inicialización cuando el DOM está listo.
     */
    $(document).ready(function() {
        // Obtener referencias a los elementos del modal
        modal.overlay = $('#wp-alp-modal-overlay');
        modal.container = $('#wp-alp-modal-container');
        modal.content = $('#wp-alp-modal-content');
        modal.loader = $('#wp-alp-modal-loader');
        modal.closeBtn = $('#wp-alp-close-modal');

        // Inicializar listeners
        initModalListeners();
    });

    /**
     * Inicializa los listeners para el modal.
     */
    function initModalListeners() {
        // Abrir modal con cualquier elemento que tenga data-wp-alp-trigger="login"
        $(document).on('click', '[data-wp-alp-trigger="login"]', function(e) {
            e.preventDefault();
            openModal();
        });

        // Cerrar modal con botón de cierre
        $(document).on('click', '#wp-alp-close-modal', function() {
            closeModal();
        });

        // Cerrar modal con clic fuera
        $(document).on('click', '#wp-alp-modal-overlay', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Cerrar modal con tecla Escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && modal.overlay.is(':visible')) {
                closeModal();
            }
        });

        // Botón continuar en formulario inicial
        $(document).on('click', '#wp-alp-continue-btn', function() {
            var identifier = $('#wp-alp-identifier').val().trim();
            if (!identifier) {
                showError('Por favor, introduce un correo electrónico o número de teléfono.');
                return;
            }
            validateUser(identifier);
        });

        // Botón para regresar al formulario inicial
        $(document).on('click', '#wp-alp-back-to-initial', function(e) {
            e.preventDefault();
            loadInitialForm();
        });

        // Botón de iniciar sesión
        $(document).on('click', '#wp-alp-login-btn', function() {
            var email = $('#wp-alp-login-email').val().trim();
            var password = $('#wp-alp-login-password').val().trim();
            
            if (!email || !password) {
                showError('Por favor, completa todos los campos.');
                return;
            }
            
            loginUser(email, password);
        });

        // Botón de completar perfil
        $(document).on('click', '#wp-alp-complete-profile-btn', function() {
            var formData = {
                user_id: $('input[name="user_id"]').val().trim(),
                email: $('input[name="email"]').val().trim(),
                first_name: $('input[name="first_name"]').val().trim(),
                last_name: $('input[name="last_name"]').val().trim(),
                phone: $('input[name="phone"]').val().trim(),
                event_type: $('#wp-alp-event-type').val().trim(),
                event_date: $('#wp-alp-event-date').val().trim(),
                event_address: $('#wp-alp-event-address').val().trim(),
                guests: $('#wp-alp-event-guests').val().trim(),
                details: $('#wp-alp-event-details').val().trim()
            };
            
            // Validar campos requeridos
            var requiredFields = ['user_id', 'event_type', 'event_date', 'event_address', 'guests'];
            for (var i = 0; i < requiredFields.length; i++) {
                if (!formData[requiredFields[i]]) {
                    showError('Por favor, completa el campo: ' + requiredFields[i]);
                    return;
                }
            }
            
            completeProfile(formData);
        });

        // Otras interacciones de formulario
        initFormInteractions();
    }

    /**
     * Inicializa interacciones adicionales del formulario
     */
    function initFormInteractions() {
        // Botón de mostrar/ocultar contraseña
        $(document).on('click', '.wp-alp-toggle-password', function() {
            var targetId = $(this).data('target');
            var target = $('#' + targetId);
            var showText = $(this).find('.wp-alp-show-text');
            var hideText = $(this).find('.wp-alp-hide-text');
            
            if (target.attr('type') === 'password') {
                target.attr('type', 'text');
                showText.hide();
                hideText.show();
            } else {
                target.attr('type', 'password');
                showText.show();
                hideText.hide();
            }
        });

        // Enlace de olvidó contraseña
        $(document).on('click', '#wp-alp-forgot-password-link', function(e) {
            e.preventDefault();
            alert('Funcionalidad de recuperación de contraseña no implementada en esta versión.');
        });

        // Inputs de código de verificación
        $(document).on('input', '.wp-alp-verification-digit', function() {
            var index = parseInt($(this).data('index'));
            var code = $(this).val().trim();
            
            if (code.length > 0) {
                // Autoavanzar al siguiente input
                if (index < 5) {
                    $('.wp-alp-verification-digit[data-index="' + (index + 1) + '"]').focus();
                } else {
                    // Último dígito, verificar código
                    var fullCode = '';
                    $('.wp-alp-verification-digit').each(function() {
                        fullCode += $(this).val().trim();
                    });
                    
                    if (fullCode.length === 6) {
                        var userId = $('#wp-alp-verification-user-id').val().trim();
                        verifyCode(fullCode, userId);
                    }
                }
            }
        });

        // Enlace de reenviar código
        $(document).on('click', '#wp-alp-resend-code-link', function(e) {
            e.preventDefault();
            var userId = $('#wp-alp-verification-user-id').val().trim();
            resendCode(userId);
        });
    }

    /**
     * Abre el modal y carga el formulario inicial.
     */
    function openModal() {
        // Mostrar el modal con animación
        modal.overlay.fadeIn(300, function() {
            // Una vez visible, cargar el formulario inicial
            loadInitialForm();
        });
    }

    /**
     * Cierra el modal con animación.
     */
    function closeModal() {
        modal.overlay.fadeOut(300);
    }

    /**
     * Carga el formulario inicial vía AJAX.
     */
    function loadInitialForm() {
        showLoaderOverlay();
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_get_form',
                form: 'initial',
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateModalContent(response.data.html);
                } else {
                    showError(response.data.message || 'Error al cargar el formulario.');
                }
                hideLoaderOverlay();
            },
            error: function() {
                showError('Error de conexión. Por favor, intenta nuevamente.');
                hideLoaderOverlay();
            }
        });
    }

    /**
     * Muestra un loader encima del contenido actual.
     */
    function showLoaderOverlay() {
        // No ocultar el contenido actual, solo mostrar el loader encima
        modal.loader.fadeIn(150);
    }

    /**
     * Oculta el loader overlay.
     */
    function hideLoaderOverlay() {
        modal.loader.fadeOut(150);
    }

    /**
     * Actualiza el contenido del modal con una animación suave.
     */
    function updateModalContent(html) {
        // Animación de fade out/in para el contenido
        modal.content.fadeOut(150, function() {
            modal.content.html(html);
            modal.content.fadeIn(150);
        });
    }

    /**
     * Muestra un mensaje de error en el modal.
     */
    function showError(message) {
        var errorHtml = '<div class="wp-alp-error-message">' + message + '</div>';
        
        // Remover mensajes de error anteriores
        $('.wp-alp-error-message').remove();
        
        // Añadir nuevo mensaje de error al inicio del contenido
        modal.content.prepend(errorHtml);
        
        // Auto-ocultar después de 5 segundos
        setTimeout(function() {
            $('.wp-alp-error-message').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    }

    /**
     * Muestra un mensaje de éxito en el modal.
     */
    function showSuccess(message) {
        var successHtml = '<div class="wp-alp-success-message">' + message + '</div>';
        
        // Remover mensajes anteriores
        $('.wp-alp-success-message, .wp-alp-error-message').remove();
        
        // Añadir nuevo mensaje
        modal.content.prepend(successHtml);
        
        // Auto-ocultar después de 3 segundos
        setTimeout(function() {
            $('.wp-alp-success-message').fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }

    /**
     * Valida si un usuario existe.
     */
    function validateUser(identifier) {
        showLoaderOverlay();
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_validate_user',
                identifier: identifier,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateModalContent(response.data.html);
                } else {
                    showError(response.data.message || 'Error al validar usuario.');
                }
                hideLoaderOverlay();
            },
            error: function() {
                showError('Error de conexión. Por favor, intenta nuevamente.');
                hideLoaderOverlay();
            }
        });
    }

    /**
     * Procesa el login de un usuario.
     */
    function loginUser(email, password) {
        showLoaderOverlay();
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_login_user',
                email: email,
                password: password,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                hideLoaderOverlay();
                
                if (response.success) {
                    if (response.data.needs_profile) {
                        // Si necesita completar perfil, mostrar ese formulario
                        updateModalContent(response.data.html);
                        showSuccess('Inicio de sesión exitoso. Por favor, completa tu perfil.');
                    } else {
                        // Login completo, mostrar mensaje y redireccionar
                        showSuccess(response.data.message || 'Inicio de sesión exitoso.');
                        setTimeout(function() {
                            window.location.href = response.data.redirect || wp_alp_ajax.home_url;
                        }, 1000);
                    }
                } else {
                    // Error en login
                    showError(response.data.message || 'Error al iniciar sesión.');
                }
            },
            error: function() {
                hideLoaderOverlay();
                
                // Incluso si hubo error en la respuesta AJAX, el login podría haber funcionado
                // Intentar recargar para verificar
                window.location.reload();
            }
        });
    }

    /**
     * Completa el perfil de un usuario.
     */
    function completeProfile(formData) {
        showLoaderOverlay();
        
        // Añadir nonce y acción
        formData.action = 'wp_alp_complete_profile';
        formData.nonce = wp_alp_ajax.nonce;
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                hideLoaderOverlay();
                
                if (response.success) {
                    showSuccess(response.data.message || 'Perfil completado exitosamente.');
                    setTimeout(function() {
                        window.location.href = response.data.redirect || wp_alp_ajax.home_url;
                    }, 1000);
                } else {
                    showError(response.data.message || 'Error al completar el perfil.');
                }
            },
            error: function() {
                hideLoaderOverlay();
                showError('Error de conexión. Por favor, intenta nuevamente.');
            }
        });
    }

    /**
     * Verifica un código de verificación.
     */
    function verifyCode(code, userId) {
        showLoaderOverlay();
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_verify_code',
                code: code,
                user_id: userId,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                hideLoaderOverlay();
                
                if (response.success) {
                    if (response.data.needs_profile) {
                        updateModalContent(response.data.html);
                        showSuccess('Código verificado correctamente. Por favor, completa tu perfil.');
                    } else {
                        showSuccess(response.data.message || 'Código verificado correctamente.');
                        setTimeout(function() {
                            window.location.href = response.data.redirect || wp_alp_ajax.home_url;
                        }, 1000);
                    }
                } else {
                    showError(response.data.message || 'Código inválido.');
                    
                    // Limpiar inputs de código
                    $('.wp-alp-verification-digit').val('');
                    $('.wp-alp-verification-digit[data-index="0"]').focus();
                }
            },
            error: function() {
                hideLoaderOverlay();
                showError('Error de conexión. Por favor, intenta nuevamente.');
            }
        });
    }

    /**
     * Reenvía un código de verificación.
     */
    function resendCode(userId) {
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_resend_code',
                user_id: userId,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    showSuccess(response.data.message || 'Código reenviado correctamente.');
                } else {
                    showError(response.data.message || 'Error al reenviar el código.');
                }
            },
            error: function() {
                showError('Error de conexión. Por favor, intenta nuevamente.');
            }
        });
    }

})(jQuery);