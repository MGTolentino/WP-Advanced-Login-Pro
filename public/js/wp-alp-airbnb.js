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
        userData: {}, // Datos del usuario
        selectedCountry: null, // País seleccionado
        phoneMode: false // Si está en modo teléfono
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
        initializeCountrySystem(); // Inicializar sistema de países
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
     * Inicializa el sistema de países
     */
    function initializeCountrySystem() {
        // Detectar país por defecto
        if (typeof WP_ALP_detectCountry === 'function') {
            WP_ALP_detectCountry().then(function(countryCode) {
                var country = WP_ALP_getCountryByCode(countryCode);
                if (country) {
                    modal.selectedCountry = country;
                }
            });
        }
        
        // Event handlers específicos del selector de países comentados
        // attachCountryEventHandlers();
    }

    /*
     * Event handlers para países comentados
     *
    function attachCountryEventHandlers() {
        // Detectar cuando se está escribiendo un teléfono
        $(document).on('input', '#wpalp-identifier', function() {
            var value = $(this).val().trim();
            var isPhone = /^[+0-9]/.test(value) && value.length > 0;
            
            togglePhoneMode(isPhone);
        });

        // Click en selector de país
        $(document).on('click', '#wpalp-country-selector', function(e) {
            e.stopPropagation();
            toggleCountryDropdown();
        });

        // Buscar países
        $(document).on('input', '#wpalp-country-search', function() {
            var query = $(this).val();
            renderCountryList(query);
        });

        // Seleccionar país
        $(document).on('click', '.wpalp-country-option', function() {
            var countryCode = $(this).data('country-code');
            var country = WP_ALP_getCountryByCode(countryCode);
            if (country) {
                selectCountry(country);
            }
        });

        // Cerrar dropdown al hacer clic fuera
        $(document).on('click', function() {
            closeCountryDropdown();
        });
    }
    */

    /*
     * FUNCIONES DE PAÍSES COMENTADAS (para implementar después)
     *
    function togglePhoneMode(enable) {
        var $container = $('#wpalp-country-selector-container');
        var $identifier = $('#wpalp-identifier');
        
        if (enable && !modal.phoneMode) {
            modal.phoneMode = true;
            $container.slideDown(200);
            $identifier.attr('placeholder', 'Número de teléfono');
            
            // Inicializar selector si no está inicializado
            if (!modal.selectedCountry) {
                modal.selectedCountry = WP_ALP_getCountryByCode('MX'); // Default México
            }
            updateCountryDisplay();
            renderCountryList('');
            
        } else if (!enable && modal.phoneMode) {
            modal.phoneMode = false;
            $container.slideUp(200);
            $identifier.attr('placeholder', 'Correo electrónico o teléfono');
        }
    }
    */

    /**
     * Abre/cierra dropdown de países
     */
    function toggleCountryDropdown() {
        var $selector = $('#wpalp-country-selector');
        $selector.toggleClass('wpalp-open');
        
        if ($selector.hasClass('wpalp-open')) {
            $('#wpalp-country-search').focus();
        }
    }

    /**
     * Cierra dropdown de países
     */
    function closeCountryDropdown() {
        $('#wpalp-country-selector').removeClass('wpalp-open');
    }

    /**
     * Selecciona un país
     */
    function selectCountry(country) {
        modal.selectedCountry = country;
        updateCountryDisplay();
        closeCountryDropdown();
    }

    /**
     * Actualiza la visualización del país seleccionado
     */
    function updateCountryDisplay() {
        if (!modal.selectedCountry) return;
        
        var country = modal.selectedCountry;
        $('.wpalp-country-flag').text(country.flag);
        $('.wpalp-country-name').text(country.name);
        $('.wpalp-country-dial').text('(' + country.dial + ')');
    }

    /**
     * Renderiza la lista de países
     */
    function renderCountryList(query) {
        if (typeof WP_ALP_searchCountries !== 'function') return;
        
        var countries = WP_ALP_searchCountries(query);
        var $list = $('#wpalp-country-list');
        
        $list.empty();
        
        countries.slice(0, 10).forEach(function(country) {
            var $option = $('<div class="wpalp-country-option" data-country-code="' + country.code + '">' +
                '<span class="wpalp-country-option-flag">' + country.flag + '</span>' +
                '<span class="wpalp-country-option-name">' + country.name + '</span>' +
                '<span class="wpalp-country-option-dial">' + country.dial + '</span>' +
            '</div>');
            $list.append($option);
        });
    }

    /*
     * Función de número completo comentada (no se usa selector países)
     *
    function getFullPhoneNumber() {
        if (!modal.phoneMode || !modal.selectedCountry) return null;
        
        var phoneNumberValue = $('#wpalp-identifier').val();
        if (!phoneNumberValue) return null;
        
        var phoneNumber = phoneNumberValue.trim();
        if (!phoneNumber) return null;
        
        // Limpiar número (quitar espacios, guiones, etc)
        var cleanNumber = phoneNumber.replace(/[\s\-\(\)]/g, '');
        
        // Combinar código de país + número
        return modal.selectedCountry.dial + cleanNumber;
    }
    */

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

        // OTP Events
        $(document).on('input', '.wpalp-otp-digit', handleOTPInput);
        $(document).on('keydown', '.wpalp-otp-digit', handleOTPKeydown);
        $(document).on('click', '#wpalp-verify-otp-btn', handleVerifyOTP);
        $(document).on('click', '#wpalp-choose-different', function(e) {
            e.preventDefault();
            showFormInstant('otp-methods');
        });
        $(document).on('click', '#wpalp-back-to-otp', function(e) {
            e.preventDefault();
            showFormInstant('otp');
        });
        $(document).on('click', '#wpalp-resend-code-btn', handleResendCode);
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
                case 'otp':
                    htmlContent = generateOTPForm();
                    break;
                case 'otp-methods':
                    htmlContent = generateMethodsForm();
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
     * Genera formulario de verificación OTP
     */
    function generateOTPForm() {
        var maskedPhone = maskPhoneNumber(modal.userData.identifier);
        
        return `
            <div class="wpalp-auth-modal">
                <div class="wpalp-modal-header">
                    <button type="button" class="wpalp-btn-back" id="wpalp-back-to-initial" aria-label="Volver">
                        <span class="wpalp-icon-back"></span>
                    </button>
                    <h3 class="wpalp-modal-title">Confirma tu número de teléfono</h3>
                </div>
                
                <div class="wpalp-modal-body">
                    <p class="wpalp-otp-message">
                        ${modal.userData.verification_message || 'Te hemos enviado un código por SMS al ' + maskedPhone}
                    </p>
                    
                    <div class="wpalp-otp-input-container">
                        <div class="wpalp-otp-inputs">
                            <input type="text" class="wpalp-otp-digit" maxlength="1" data-index="0" />
                            <input type="text" class="wpalp-otp-digit" maxlength="1" data-index="1" />
                            <input type="text" class="wpalp-otp-digit" maxlength="1" data-index="2" />
                            <input type="text" class="wpalp-otp-digit" maxlength="1" data-index="3" />
                            <input type="text" class="wpalp-otp-digit" maxlength="1" data-index="4" />
                            <input type="text" class="wpalp-otp-digit" maxlength="1" data-index="5" />
                        </div>
                    </div>
                    
                    <div class="wpalp-otp-actions">
                        <a href="#" id="wpalp-choose-different" class="wpalp-link">
                            Elige una opción diferente
                        </a>
                        
                        <button type="button" class="wpalp-btn-primary" id="wpalp-verify-otp-btn">
                            Continuar
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Genera formulario de métodos alternativos
     */
    function generateMethodsForm() {
        var maskedPhone = maskPhoneNumber(modal.userData.identifier);
        
        return `
            <div class="wpalp-auth-modal">
                <div class="wpalp-modal-header">
                    <button type="button" class="wpalp-btn-back" id="wpalp-back-to-otp" aria-label="Volver">
                        <span class="wpalp-icon-back"></span>
                    </button>
                    <h3 class="wpalp-modal-title">Más opciones</h3>
                </div>
                
                <div class="wpalp-modal-body">
                    <p class="wpalp-methods-description">
                        Elige otra forma de recibir el código de verificación en el número ${maskedPhone}
                    </p>
                    
                    <p class="wpalp-methods-help">
                        Asegúrate de que tienes las notificaciones activadas.
                    </p>
                    
                    <div class="wpalp-method-options">
                        <label class="wpalp-method-option">
                            <input type="radio" name="verification_method" value="sms" checked>
                            <div class="wpalp-method-content">
                                <span class="wpalp-method-icon">💬</span>
                                <div class="wpalp-method-info">
                                    <strong>Mensaje de texto (SMS)</strong>
                                    <p>Te enviaremos un código por SMS</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="wpalp-method-option">
                            <input type="radio" name="verification_method" value="whatsapp">
                            <div class="wpalp-method-content">
                                <span class="wpalp-method-icon">📱</span>
                                <div class="wpalp-method-info">
                                    <strong>WhatsApp</strong>
                                    <p>Te enviaremos un código mediante WhatsApp</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="wpalp-method-option">
                            <input type="radio" name="verification_method" value="call">
                            <div class="wpalp-method-content">
                                <span class="wpalp-method-icon">☎️</span>
                                <div class="wpalp-method-info">
                                    <strong>Llamada</strong>
                                    <p>Te llamaremos para darte un código</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="wpalp-method-actions">
                        <button type="button" class="wpalp-btn-primary" id="wpalp-resend-code-btn">
                            Vuelve a enviar el código
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Enmascara número de teléfono
     */
    function maskPhoneNumber(phoneNumber) {
        if (!phoneNumber || phoneNumber.length < 8) return phoneNumber;
        
        var start = phoneNumber.substring(0, 4);
        var end = phoneNumber.substring(phoneNumber.length - 2);
        var middle = '*'.repeat(phoneNumber.length - 6);
        
        return start + middle + end;
    }

    /**
     * Muestra loader en botón específico
     */
    function showButtonLoader(buttonId) {
        var $button = $('#' + buttonId);
        if ($button.length) {
            $button.data('original-text', $button.text());
            $button.prop('disabled', true);
            $button.html('<div class="wpalp-button-spinner"></div>');
        }
    }

    /**
     * Oculta loader del botón y restaura texto
     */
    function hideButtonLoader(buttonId) {
        var $button = $('#' + buttonId);
        if ($button.length && $button.data('original-text')) {
            $button.prop('disabled', false);
            $button.html($button.data('original-text'));
        }
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
        var isPhone = /^[+0-9]/.test(identifier) && identifier.length >= 8;

        if (!isEmail && !isPhone) {
            showError('Por favor, introduce un correo o teléfono válido');
            return;
        }

        // Guardar datos del usuario para uso posterior
        modal.userData.identifier = identifier;
        modal.userData.isEmail = isEmail;
        modal.userData.isPhone = isPhone;

        validateUser(identifier);
    }

    /**
     * Valida el usuario
     */
    function validateUser(identifier) {
        showButtonLoader('wpalp-continue-btn');
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_validate_user',
                identifier: identifier,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                hideButtonLoader('wpalp-continue-btn');
                if (response.success) {
                    // Flujo simplificado sin verificación SMS
                    if (response.data.user_exists) {
                        // Usuario existente - ir a login
                        window.wpAlp.showFormInstant('login', identifier);
                    } else {
                        // Usuario nuevo - ir a registro  
                        window.wpAlp.showFormInstant('register', identifier);
                    }
                } else {
                    showError(response.data.message || 'Error al validar usuario');
                }
            },
            error: function() {
                hideButtonLoader('wpalp-continue-btn');
                showError('Error de conexión');
            }
        });
    }

    /**
     * Inicia el proceso de verificación telefónica
     */
    function startPhoneVerification() {
        // Enviar código automáticamente por SMS
        sendVerificationCode('sms', function(success) {
            if (success) {
                showFormInstant('otp');
            }
        });
    }

    /**
     * Envía código de verificación
     */
    function sendVerificationCode(method, callback) {
        if (!modal.userData.isPhone) {
            showError('Error: no es un número de teléfono válido');
            return;
        }

        // Obtener número completo con código de país
        var fullPhoneNumber = getFullPhoneNumber();
        if (!fullPhoneNumber) {
            showError('Error: número de teléfono incompleto');
            return;
        }

        // Parsear para obtener componentes
        var phoneData = parsePhoneNumber(fullPhoneNumber);

        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_send_phone_verification',
                phone_full: fullPhoneNumber,
                country_code: phoneData.countryCode,
                phone_number: phoneData.phoneNumber,
                method: method,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Actualizar identifier con número completo para futuras referencias
                    modal.userData.identifier = fullPhoneNumber;
                    modal.userData.verification_message = response.data.message;
                    modal.userData.verification_expires = Date.now() + (response.data.expires_in * 1000);
                    if (callback) callback(true);
                } else {
                    showError(response.data.message || 'Error al enviar código');
                    if (callback) callback(false);
                }
            },
            error: function() {
                showError('Error de conexión');
                if (callback) callback(false);
            }
        });
    }

    /**
     * Parsea número de teléfono completo
     */
    function parsePhoneNumber(fullPhone) {
        if (!fullPhone || !fullPhone.startsWith('+')) {
            return { countryCode: '+52', phoneNumber: '' };
        }

        // Buscar el país en la lista
        if (typeof WP_ALP_Countries !== 'undefined') {
            for (var i = 0; i < WP_ALP_Countries.length; i++) {
                var country = WP_ALP_Countries[i];
                if (fullPhone.startsWith(country.dial)) {
                    return {
                        countryCode: country.dial,
                        phoneNumber: fullPhone.substring(country.dial.length)
                    };
                }
            }
        }

        // Fallback
        return {
            countryCode: fullPhone.substring(0, 3),
            phoneNumber: fullPhone.substring(3)
        };
    }

    /**
     * Verifica código OTP
     */
    function verifyOTPCode(code) {
        showButtonLoader('wpalp-verify-otp-btn');

        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_verify_phone_code',
                phone_full: modal.userData.identifier,
                code: code,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                hideButtonLoader('wpalp-verify-otp-btn');
                if (response.success) {
                    // Teléfono verificado - ir a registro
                    modal.userData.phone_verified = true;
                    showSuccess('Teléfono verificado correctamente');
                    setTimeout(function() {
                        showFormInstant('register');
                    }, 1000);
                } else {
                    showError(response.data.message || 'Código incorrecto');
                }
            },
            error: function() {
                hideButtonLoader('wpalp-verify-otp-btn');
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

        showButtonLoader('wpalp-login-btn');

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
                hideButtonLoader('wpalp-login-btn');
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
                hideButtonLoader('wpalp-login-btn');
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

        showButtonLoader('wpalp-register-btn');

        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                hideButtonLoader('wpalp-register-btn');
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
                hideButtonLoader('wpalp-register-btn');
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

    /**
     * Maneja la entrada en los campos OTP
     */
    function handleOTPInput(event) {
        var $input = $(event.target);
        var value = $input.val();
        
        // Solo números
        if (!/^\d$/.test(value)) {
            $input.val('');
            return;
        }
        
        // Auto-avanzar al siguiente campo
        var nextIndex = parseInt($input.data('index')) + 1;
        if (nextIndex <= 6) {
            $('.wpalp-otp-digit[data-index="' + nextIndex + '"]').focus();
        }
        
        // Si todos los campos están llenos, habilitar botón
        updateOTPButton();
    }

    /**
     * Maneja las teclas en los campos OTP
     */
    function handleOTPKeydown(event) {
        var $input = $(event.target);
        var key = event.key;
        
        // Backspace: limpiar y ir al anterior
        if (key === 'Backspace') {
            if ($input.val() === '') {
                var prevIndex = parseInt($input.data('index')) - 1;
                if (prevIndex >= 1) {
                    var $prevInput = $('.wpalp-otp-digit[data-index="' + prevIndex + '"]');
                    $prevInput.val('').focus();
                }
            } else {
                $input.val('');
            }
            updateOTPButton();
        }
        
        // Arrow keys para navegación
        else if (key === 'ArrowLeft') {
            var prevIndex = parseInt($input.data('index')) - 1;
            if (prevIndex >= 1) {
                $('.wpalp-otp-digit[data-index="' + prevIndex + '"]').focus();
            }
        }
        else if (key === 'ArrowRight') {
            var nextIndex = parseInt($input.data('index')) + 1;
            if (nextIndex <= 6) {
                $('.wpalp-otp-digit[data-index="' + nextIndex + '"]').focus();
            }
        }
    }

    /**
     * Obtiene el código OTP completo
     */
    function getOTPCode() {
        var code = '';
        for (var i = 1; i <= 6; i++) {
            var value = $('.wpalp-otp-digit[data-index="' + i + '"]').val();
            if (!value) return null; // Incompleto
            code += value;
        }
        return code;
    }

    /**
     * Actualiza el estado del botón de verificar OTP
     */
    function updateOTPButton() {
        var code = getOTPCode();
        var $button = $('#wpalp-verify-otp-btn');
        
        if (code && code.length === 6) {
            $button.prop('disabled', false).removeClass('wpalp-btn-disabled');
        } else {
            $button.prop('disabled', true).addClass('wpalp-btn-disabled');
        }
    }

    /**
     * Maneja la verificación del código OTP
     */
    function handleVerifyOTP() {
        var code = getOTPCode();
        if (!code) {
            showError('Por favor, ingresa el código completo');
            return;
        }
        
        verifyOTPCode(code);
    }

    /**
     * Maneja el reenvío del código
     */
    function handleResendCode() {
        if (!modal.userData.identifier) {
            showError('Error: no hay número de teléfono');
            return;
        }
        
        showButtonLoader('wpalp-resend-code-btn');
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_send_phone_verification',
                phone_full: modal.userData.identifier,
                method: modal.userData.verification_method || 'sms',
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                hideButtonLoader('wpalp-resend-code-btn');
                if (response.success) {
                    showSuccess('Código reenviado correctamente');
                    // Limpiar campos OTP
                    $('.wpalp-otp-digit').val('');
                    $('.wpalp-otp-digit[data-index="1"]').focus();
                    updateOTPButton();
                } else {
                    showError(response.data.message || 'Error al reenviar código');
                }
            },
            error: function() {
                hideButtonLoader('wpalp-resend-code-btn');
                showError('Error de conexión');
            }
        });
    }

})(jQuery);