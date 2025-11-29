/**
 * WP Advanced Login Pro - JavaScript Airbnb Style
 * Maneja la funcionalidad del modal estilo Airbnb
 */

(function($) {
    'use strict';

    // Variables del modal
    var modal = {
        wrapper: null,
        content: null,
        loader: null,
        currentForm: 'initial'
    };

    // Inicialización cuando el DOM está listo
    $(document).ready(function() {
        initializeModal();
        attachEventHandlers();
    });

    /**
     * Inicializa las referencias al modal
     */
    function initializeModal() {
        modal.wrapper = $('#wpalp-modal-wrapper');
        modal.content = $('#wpalp-modal-content');
        modal.loader = $('#wpalp-modal-loader');

        // Si no existe el wrapper del modal, crearlo
        if (modal.wrapper.length === 0) {
            createModalStructure();
        }
    }

    /**
     * Crea la estructura del modal dinámicamente
     */
    function createModalStructure() {
        var modalHTML = `
            <div id="wpalp-modal-wrapper" class="wpalp-modal-wrapper" style="display: none;">
                <div id="wpalp-modal-content" class="wpalp-modal-content">
                    <!-- El contenido se cargará aquí -->
                </div>
                <div id="wpalp-modal-loader" class="wpalp-loading-overlay" style="display: none;">
                    <div class="wpalp-spinner"></div>
                </div>
            </div>
        `;
        $('body').append(modalHTML);
        
        // Reinicializar las referencias
        modal.wrapper = $('#wpalp-modal-wrapper');
        modal.content = $('#wpalp-modal-content');
        modal.loader = $('#wpalp-modal-loader');
    }

    /**
     * Adjunta los manejadores de eventos
     */
    function attachEventHandlers() {
        // Abrir modal con botones de login
        $(document).on('click', '[data-wp-alp-trigger="login"], .wpalp-login-trigger', function(e) {
            e.preventDefault();
            e.stopPropagation();
            openModal();
        });

        // Cerrar modal
        $(document).on('click', '.wpalp-btn-close', closeModal);
        
        // Cerrar modal al hacer clic fuera
        $(document).on('click', '#wpalp-modal-wrapper', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Cerrar modal con ESC
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && modal.wrapper.is(':visible')) {
                closeModal();
            }
        });

        // Botón continuar en formulario inicial
        $(document).on('click', '#wpalp-continue-btn', handleContinue);

        // Botón volver
        $(document).on('click', '#wpalp-back-to-initial', loadInitialForm);

        // Toggle de contraseña
        $(document).on('click', '.wpalp-password-toggle', function() {
            var targetId = $(this).data('target');
            var $input = $('#' + targetId);
            var $showText = $(this).find('.wpalp-show-text');
            var $hideText = $(this).find('.wpalp-hide-text');
            
            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $showText.hide();
                $hideText.show();
            } else {
                $input.attr('type', 'password');
                $showText.show();
                $hideText.hide();
            }
        });

        // Login con botón
        $(document).on('click', '#wpalp-login-btn', handleLogin);

        // Registro con botón
        $(document).on('click', '#wpalp-register-btn', handleRegister);

        // Login social
        $(document).on('click', '#wpalp-google-btn', function() {
            handleSocialLogin('google');
        });

        $(document).on('click', '#wpalp-facebook-btn', function() {
            handleSocialLogin('facebook');
        });

        $(document).on('click', '#wpalp-apple-btn', function() {
            handleSocialLogin('apple');
        });

        // Forgot password
        $(document).on('click', '#wpalp-forgot-password-link', function(e) {
            e.preventDefault();
            loadForgotPasswordForm();
        });
    }

    /**
     * Abre el modal
     */
    function openModal() {
        modal.wrapper.fadeIn(200);
        $('body').addClass('wpalp-modal-open').css('overflow', 'hidden');
        loadInitialForm();
    }

    /**
     * Cierra el modal
     */
    function closeModal() {
        modal.wrapper.fadeOut(200);
        $('body').removeClass('wpalp-modal-open').css('overflow', '');
        modal.currentForm = 'initial';
    }

    /**
     * Muestra el loader
     */
    function showLoader() {
        modal.loader.fadeIn(100);
    }

    /**
     * Oculta el loader
     */
    function hideLoader() {
        modal.loader.fadeOut(100);
    }

    /**
     * Carga el formulario inicial
     */
    function loadInitialForm() {
        showLoader();
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_get_initial_form',
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    modal.content.html(response.data.html);
                    modal.currentForm = 'initial';
                }
                hideLoader();
            },
            error: function() {
                showError('Error al cargar el formulario');
                hideLoader();
            }
        });
    }

    /**
     * Maneja el botón continuar
     */
    function handleContinue() {
        var identifier = $('#wpalp-identifier').val().trim();
        
        if (!identifier) {
            showError('Por favor, introduce tu correo o teléfono');
            return;
        }

        var isEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(identifier);
        var isPhone = /^[0-9+\-\s()]{8,20}$/.test(identifier);

        if (!isEmail && !isPhone) {
            showError('Por favor, introduce un correo o teléfono válido');
            return;
        }

        validateUser(identifier);
    }

    /**
     * Valida el usuario
     */
    function validateUser(identifier) {
        showLoader();
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_validate_user',
                identifier: identifier,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                hideLoader();
                if (response.success) {
                    if (response.data.user_exists) {
                        loadLoginForm(identifier);
                    } else {
                        loadRegisterForm(identifier);
                    }
                } else {
                    showError(response.data.message || 'Error al validar usuario');
                }
            },
            error: function() {
                hideLoader();
                showError('Error de conexión');
            }
        });
    }

    /**
     * Carga el formulario de login
     */
    function loadLoginForm(email) {
        showLoader();
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_get_login_form',
                email: email,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    modal.content.html(response.data.html);
                    modal.currentForm = 'login';
                }
                hideLoader();
            },
            error: function() {
                showError('Error al cargar el formulario de login');
                hideLoader();
            }
        });
    }

    /**
     * Carga el formulario de registro
     */
    function loadRegisterForm(email) {
        showLoader();
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_get_register_form',
                email: email,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    modal.content.html(response.data.html);
                    modal.currentForm = 'register';
                }
                hideLoader();
            },
            error: function() {
                showError('Error al cargar el formulario de registro');
                hideLoader();
            }
        });
    }

    /**
     * Maneja el login
     */
    function handleLogin() {
        var email = $('#wpalp-login-email').val();
        var password = $('#wpalp-login-password').val();

        if (!password) {
            showError('Por favor, introduce tu contraseña');
            return;
        }

        showLoader();

        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_login',
                email: email,
                password: password,
                remember: true,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                hideLoader();
                if (response.success) {
                    showSuccess('¡Bienvenido! Redirigiendo...');
                    setTimeout(function() {
                        window.location.href = response.data.redirect || wp_alp_ajax.home_url;
                    }, 1000);
                } else {
                    showError(response.data.message || 'Error al iniciar sesión');
                }
            },
            error: function() {
                hideLoader();
                showError('Error de conexión');
            }
        });
    }

    /**
     * Maneja el registro
     */
    function handleRegister() {
        var formData = {
            action: 'wp_alp_register',
            email: $('#wpalp-register-email').val(),
            first_name: $('#wpalp-register-first-name').val(),
            last_name: $('#wpalp-register-last-name').val(),
            birthdate: $('#wpalp-register-birthdate').val(),
            phone: $('#wpalp-register-phone').val(),
            password: $('#wpalp-register-password').val(),
            event_type: $('#wpalp-event-type').val(),
            event_date: $('#wpalp-event-date').val(),
            event_address: $('#wpalp-event-address').val(),
            guests: $('#wpalp-event-guests').val(),
            details: $('#wpalp-event-details').val(),
            nonce: wp_alp_ajax.nonce
        };

        // Validaciones básicas
        if (!formData.first_name || !formData.last_name) {
            showError('Por favor, completa tu nombre');
            return;
        }

        if (!formData.password || formData.password.length < 6) {
            showError('La contraseña debe tener al menos 6 caracteres');
            return;
        }

        showLoader();

        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                hideLoader();
                if (response.success) {
                    showSuccess('¡Registro exitoso! Redirigiendo...');
                    setTimeout(function() {
                        window.location.href = response.data.redirect || wp_alp_ajax.home_url;
                    }, 1500);
                } else {
                    showError(response.data.message || 'Error al registrarte');
                }
            },
            error: function() {
                hideLoader();
                showError('Error de conexión');
            }
        });
    }

    /**
     * Maneja el login social
     */
    function handleSocialLogin(provider) {
        showLoader();
        
        // Aquí iría la integración con los proveedores sociales
        // Por ahora solo mostramos un mensaje
        setTimeout(function() {
            hideLoader();
            showError('Login con ' + provider + ' en desarrollo');
        }, 1000);
    }

    /**
     * Carga el formulario de recuperar contraseña
     */
    function loadForgotPasswordForm() {
        // Por implementar
        showError('Función en desarrollo');
    }

    /**
     * Muestra un mensaje de error
     */
    function showError(message) {
        removeMessages();
        var errorHtml = '<div class="wpalp-message wpalp-message-error">' + message + '</div>';
        modal.content.find('.wpalp-modal-body').prepend(errorHtml);
        
        // Auto-remover después de 5 segundos
        setTimeout(removeMessages, 5000);
    }

    /**
     * Muestra un mensaje de éxito
     */
    function showSuccess(message) {
        removeMessages();
        var successHtml = '<div class="wpalp-message wpalp-message-success">' + message + '</div>';
        modal.content.find('.wpalp-modal-body').prepend(successHtml);
    }

    /**
     * Remueve todos los mensajes
     */
    function removeMessages() {
        $('.wpalp-message').fadeOut(200, function() {
            $(this).remove();
        });
    }

})(jQuery);