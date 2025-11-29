/**
 * WP Advanced Login Pro - JavaScript Airbnb Style OPTIMIZADO
 * Sin recargas innecesarias, transiciones instantáneas como Airbnb
 */

(function($) {
    'use strict';

    // Variables del modal
    var modal = {
        wrapper: null,
        content: null,
        loader: null,
        currentForm: 'initial',
        cachedForms: {}, // Cache de formularios
        userData: {} // Datos del usuario
    };

    // Templates pre-cargados (se cargan una sola vez)
    var formTemplates = {
        initial: '',
        login: '',
        register: ''
    };

    // Inicialización cuando el DOM está listo
    $(document).ready(function() {
        initializeModal();
        attachEventHandlers();
        preloadFormTemplates(); // Pre-cargar todos los formularios
    });

    /**
     * Pre-carga todos los templates de formularios para transiciones instantáneas
     */
    function preloadFormTemplates() {
        // Cargar formulario inicial
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_get_initial_form',
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    formTemplates.initial = response.data.html;
                }
            }
        });
    }

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
        // Abrir modal con botones de login - soporta ambas clases para compatibilidad
        $(document).on('click', '[data-wp-alp-trigger="login"], .wp-alp-login-trigger, .wpalp-login-trigger', function(e) {
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

        // Botón volver - AHORA ES INSTANTÁNEO
        $(document).on('click', '#wpalp-back-to-initial', function(e) {
            e.preventDefault();
            showFormInstant('initial');
        });

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
        modal.wrapper.addClass('wpalp-modal-active').fadeIn(200);
        $('body').addClass('wpalp-modal-open').css('overflow', 'hidden');
        showFormInstant('initial');
    }

    /**
     * Cierra el modal
     */
    function closeModal() {
        modal.wrapper.removeClass('wpalp-modal-active').fadeOut(200);
        $('body').removeClass('wpalp-modal-open').css('overflow', '');
        modal.currentForm = 'initial';
    }

    /**
     * Muestra un formulario instantáneamente (sin AJAX)
     */
    function showFormInstant(formType) {
        // Animación de salida suave
        modal.content.addClass('wpalp-form-exit');
        
        setTimeout(function() {
            var htmlContent = '';
            
            switch(formType) {
                case 'initial':
                    htmlContent = formTemplates.initial;
                    break;
                case 'login':
                    htmlContent = generateLoginForm(modal.userData.identifier);
                    break;
                case 'register':
                    htmlContent = generateRegisterForm(modal.userData.identifier);
                    break;
            }
            
            if (htmlContent) {
                modal.content.html(htmlContent);
                modal.currentForm = formType;
                
                // Animación de entrada suave
                modal.content.removeClass('wpalp-form-exit').addClass('wpalp-form-enter');
                
                setTimeout(function() {
                    modal.content.removeClass('wpalp-form-enter');
                }, 300);
            }
        }, 150);
    }

    /**
     * Genera formulario de login dinámicamente
     */
    function generateLoginForm(email) {
        return `
            <div class="wpalp-auth-modal">
                <div class="wpalp-modal-header">
                    <button type="button" class="wpalp-btn-back" id="wpalp-back-to-initial" aria-label="Volver">
                        <span class="wpalp-icon-back"></span>
                    </button>
                    <h3 class="wpalp-modal-title">Inicia sesión</h3>
                </div>
                
                <div class="wpalp-modal-body">
                    <div class="wpalp-field-group wpalp-field-disabled">
                        <label for="wpalp-login-email" class="wpalp-field-label">Correo electrónico</label>
                        <input type="email" id="wpalp-login-email" name="email" class="wpalp-field-input" value="${email}" readonly />
                    </div>
                    
                    <div class="wpalp-field-group">
                        <label for="wpalp-login-password" class="wpalp-field-label">Contraseña</label>
                        <div class="wpalp-password-wrapper">
                            <input type="password" id="wpalp-login-password" name="password" class="wpalp-field-input" placeholder="Contraseña" />
                            <button type="button" class="wpalp-password-toggle" data-target="wpalp-login-password">
                                <span class="wpalp-show-text">Mostrar</span>
                                <span class="wpalp-hide-text" style="display: none;">Ocultar</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="wpalp-forgot-password">
                        <a href="#" class="wpalp-link" id="wpalp-forgot-password-link">¿Olvidaste tu contraseña?</a>
                    </div>
                    
                    <div class="wpalp-field-group">
                        <button type="button" class="wpalp-btn-primary" id="wpalp-login-btn">
                            Iniciar sesión
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Genera formulario de registro dinámicamente
     */
    function generateRegisterForm(email) {
        return `
            <div class="wpalp-auth-modal">
                <div class="wpalp-modal-header">
                    <button type="button" class="wpalp-btn-back" id="wpalp-back-to-initial" aria-label="Volver">
                        <span class="wpalp-icon-back"></span>
                    </button>
                    <h3 class="wpalp-modal-title">Termina de registrarte</h3>
                </div>
                
                <div class="wpalp-modal-body">
                    <div class="wpalp-form-section">
                        <h3 class="wpalp-section-title">Información personal</h3>
                        
                        <div class="wpalp-field-group">
                            <label for="wpalp-register-first-name" class="wpalp-field-label">Nombre</label>
                            <input type="text" id="wpalp-register-first-name" name="first_name" class="wpalp-field-input" placeholder="Nombre" />
                        </div>
                        
                        <div class="wpalp-field-group">
                            <label for="wpalp-register-last-name" class="wpalp-field-label">Apellidos</label>
                            <input type="text" id="wpalp-register-last-name" name="last_name" class="wpalp-field-input" placeholder="Apellidos" />
                        </div>
                        
                        <div class="wpalp-field-group">
                            <label for="wpalp-register-birthdate" class="wpalp-field-label">Fecha de nacimiento</label>
                            <input type="date" id="wpalp-register-birthdate" name="birthdate" class="wpalp-field-input" />
                            <div class="wpalp-help-text">Debes tener al menos 18 años para registrarte.</div>
                        </div>
                    </div>
                    
                    <div class="wpalp-form-section">
                        <h3 class="wpalp-section-title">Información de contacto</h3>
                        
                        <div class="wpalp-field-group wpalp-field-disabled">
                            <label for="wpalp-register-email" class="wpalp-field-label">Correo electrónico</label>
                            <input type="email" id="wpalp-register-email" name="email" class="wpalp-field-input" value="${email}" readonly />
                        </div>
                        
                        <div class="wpalp-field-group">
                            <label for="wpalp-register-phone" class="wpalp-field-label">Número de teléfono</label>
                            <input type="tel" id="wpalp-register-phone" name="phone" class="wpalp-field-input" placeholder="Número de teléfono" />
                        </div>
                    </div>
                    
                    <div class="wpalp-form-section">
                        <h3 class="wpalp-section-title">Contraseña</h3>
                        
                        <div class="wpalp-field-group">
                            <label for="wpalp-register-password" class="wpalp-field-label">Contraseña</label>
                            <div class="wpalp-password-wrapper">
                                <input type="password" id="wpalp-register-password" name="password" class="wpalp-field-input" placeholder="Contraseña" />
                                <button type="button" class="wpalp-password-toggle" data-target="wpalp-register-password">
                                    <span class="wpalp-show-text">Mostrar</span>
                                    <span class="wpalp-hide-text" style="display: none;">Ocultar</span>
                                </button>
                            </div>
                            <div class="wpalp-help-text">Mínimo 6 caracteres.</div>
                        </div>
                    </div>
                    
                    <div class="wpalp-terms">
                        Al hacer clic en "Registrarse", aceptas nuestros 
                        <a href="#" class="wpalp-link">Términos de servicio</a> 
                        y 
                        <a href="#" class="wpalp-link">Política de privacidad</a>.
                    </div>
                    
                    <div class="wpalp-field-group">
                        <button type="button" class="wpalp-btn-primary" id="wpalp-register-btn">
                            Registrarse
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Muestra el loader
     */
    function showLoader() {
        modal.loader.addClass('wpalp-loader-active').fadeIn(100);
    }

    /**
     * Oculta el loader
     */
    function hideLoader() {
        modal.loader.removeClass('wpalp-loader-active').fadeOut(100);
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

        // Guardar datos del usuario para uso posterior
        modal.userData.identifier = identifier;
        modal.userData.isEmail = isEmail;

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
                        // Transición instantánea a login
                        showFormInstant('login');
                    } else {
                        // Transición instantánea a registro
                        showFormInstant('register');
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
                    showSuccess('¡Cuenta creada exitosamente! Redirigiendo...');
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
        
        // Buscar .wpalp-modal-body, si no existe, usar modal.content directamente
        var targetContainer = modal.content.find('.wpalp-modal-body');
        if (targetContainer.length === 0) {
            targetContainer = modal.content;
        }
        targetContainer.prepend(errorHtml);
        
        // Auto-remover después de 5 segundos
        setTimeout(removeMessages, 5000);
    }

    /**
     * Muestra un mensaje de éxito
     */
    function showSuccess(message) {
        removeMessages();
        var successHtml = '<div class="wpalp-message wpalp-message-success">' + message + '</div>';
        
        // Buscar .wpalp-modal-body, si no existe, usar modal.content directamente
        var targetContainer = modal.content.find('.wpalp-modal-body');
        if (targetContainer.length === 0) {
            targetContainer = modal.content;
        }
        targetContainer.prepend(successHtml);
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