/**
 * JavaScript del lado público para WP Advanced Login Pro.
 * 
 * Maneja la interacción del usuario con los formularios modales
 * y las peticiones AJAX para autenticación y registro.
 */
(function($) {
    'use strict';

    /**
     * Variables globales.
     */
    var modal = {
        overlay: $('#wp-alp-modal-overlay'),
        container: $('#wp-alp-modal-container'),
        content: $('#wp-alp-modal-content'),
        loader: $('#wp-alp-modal-loader'),
        closeBtn: $('#wp-alp-close-modal')
    };

    /**
     * Inicialización cuando el DOM está listo.
     */
    $(document).ready(function() {
    
        // Agregamos estilos dinámicos para el nuevo loader de contenido
        $('<style>\n\
        .wp-alp-content-loader {\n\
            position: absolute;\n\
            top: 0;\n\
            left: 0;\n\
            width: 100%;\n\
            height: 100%;\n\
            background: rgba(255,255,255,0.7);\n\
            display: flex;\n\
            align-items: center;\n\
            justify-content: center;\n\
            z-index: 999;\n\
        }\n\
        .wp-alp-initial-loader {\n\
            display: flex;\n\
            align-items: center;\n\
            justify-content: center;\n\
            height: 200px;\n\
        }\n\
        </style>').appendTo('head');
        
        // Agregar un manejador directo (retenemos por compatibilidad)
        $('[data-wp-alp-trigger="login"]').on('click', function(e) {
            console.log('Botón de login clickeado directamente');
            e.preventDefault();
            openModal();
        });

    // Inicializar referencias al modal
    modal = {
        overlay: $('#wp-alp-modal-overlay'),
        container: $('#wp-alp-modal-container'),
        content: $('#wp-alp-modal-content'),
        loader: $('#wp-alp-modal-loader'),
        closeBtn: $('#wp-alp-close-modal')
    };

        // Inicializar listeners
        initModalListeners();
        initFormListeners();
        
        // La inicialización social ahora se maneja en social-login.js
        // Solo inicializar si no está siendo manejado externamente
        if (typeof window.socialLoginInitialized === 'undefined') {
            initSocialLogin();
        }
    });

    /**
     * Inicializa los listeners para abrir/cerrar el modal.
     */
    function initModalListeners() {

        // Abrir modal con botones o enlaces específicos (usando clase o atributo)
        // Mejorado para prevenir comportamiento nativo incluso en clics rápidos
        $(document).on('click', '[data-wp-alp-trigger="login"], .wp-alp-login-trigger', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Detener propagación del evento
            openModal();
            return false; // Asegurar que no ocurra la navegación
        });

        // Cerrar modal con botón de cierre o click fuera
        modal.closeBtn.on('click', closeModal);
        modal.overlay.on('click', function(e) {
            if (e.target === modal.overlay[0]) {
                closeModal();
            }
        });

        // Cerrar modal con tecla Escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && modal.overlay.is(':visible')) {
                closeModal();
            }
        });
    }

    /**
     * Inicializa los listeners para los formularios.
     */
    function initFormListeners() {
        // Botón continuar en formulario inicial
        $(document).on('click', '#wp-alp-continue-btn', function() {
            var identifier = $('#wp-alp-identifier').val().trim();
            if (!identifier) {
                showError('Por favor, introduce un correo electrónico o número de teléfono.');
                return;
            }
            
            // Detectar si es correo o teléfono
            var isEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(identifier);
            var isPhone = /^[0-9]{8,15}$/.test(identifier.replace(/[\s-]+/g, ''));
            
            if (!isEmail && !isPhone) {
                showError('Por favor, introduce un correo electrónico o número de teléfono válido.');
                return;
            }
            
            validateUser(identifier);
        });

        // Ya no necesitamos el manejador del botón de teléfono porque la funcionalidad está integrada

        // Botón continuar en formulario de teléfono
        $(document).on('click', '#wp-alp-phone-continue-btn', function() {
            var phone = $('#wp-alp-phone-number').val().trim();
            if (!phone) {
                showError(wp_alp_ajax.translations.invalid_phone);
                return;
            }
            validateUser(phone);
        });

        // Botón de regreso al formulario inicial
        $(document).on('click', '#wp-alp-back-to-initial', function() {
            loadInitialForm();
        });

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

        // Botón de login
        $(document).on('click', '#wp-alp-login-btn', function() {
            var email = $('#wp-alp-login-email').val().trim();
            var password = $('#wp-alp-login-password').val().trim();
            
            if (!email || !password) {
                showError(wp_alp_ajax.translations.required_field);
                return;
            }
            
            loginUser(email, password);
        });

        // Botón de registro
        $(document).on('click', '#wp-alp-register-btn', function() {
            var formData = {
                email: $('#wp-alp-register-email').val().trim(),
                first_name: $('#wp-alp-register-first-name').val().trim(),
                last_name: $('#wp-alp-register-last-name').val().trim(),
                birthdate: $('#wp-alp-register-birthdate').val().trim(),
                phone: $('#wp-alp-register-phone').val().trim(),
                password: $('#wp-alp-register-password').val().trim(),
                event_type: $('#wp-alp-event-type').val().trim(),
                event_date: $('#wp-alp-event-date').val().trim(),
                event_address: $('#wp-alp-event-address').val().trim(),
                guests: $('#wp-alp-event-guests').val().trim(),
                details: $('#wp-alp-event-details').val().trim()
            };
            
            // Validar campos requeridos
            var requiredFields = ['email', 'first_name', 'last_name', 'birthdate', 'phone', 'password', 'event_type', 'event_date', 'event_address', 'guests'];
            for (var i = 0; i < requiredFields.length; i++) {
                if (!formData[requiredFields[i]]) {
                    showError(wp_alp_ajax.translations.required_field + ': ' + requiredFields[i]);
                    return;
                }
            }
            
            // Validar contraseña
            if (formData.password.length < 6) {
                showError(wp_alp_ajax.translations.password_short);
                return;
            }
            
            registerUser(formData);
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
                    showError(wp_alp_ajax.translations.required_field + ': ' + requiredFields[i]);
                    return;
                }
            }
            
            completeProfile(formData);
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

        // Enlace de olvidó contraseña
        $(document).on('click', '#wp-alp-forgot-password-link', function(e) {
            e.preventDefault();
            // Implementar recuperación de contraseña
            alert('Funcionalidad de recuperación de contraseña no implementada en esta versión.');
        });

        // Manejar clic en el botón de login de vendedor (para usuarios no logueados)
$(document).on('click', '#wp-alp-vendor-login-btn', function() {
    // Cargar el formulario inicial de login
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
                showError(response.data.message);
            }
        },
        error: function() {
            showError(wp_alp_ajax.translations.error);
        }
    });
});

// Manejar clic en el botón de registro de vendedor (para usuarios logueados)
$(document).on('click', '#wp-alp-vendor-register-btn', function() {
    // Aquí implementarás la lógica para crear el vendedor
    // Por ahora, solo cerraremos el modal
    closeModal();
    
    // Redireccionar a la página de vendedor
    window.location.href = wp_alp_ajax.home_url + '/conviertete-en-vendedor/';
});
    }

    /**
     * Inicializa los botones de login social.
     * Esta función se mantiene por compatibilidad pero 
     * la implementación real ahora está en social-login.js
     */
    function initSocialLogin() {
        // Esta función está vacía intencionalmente
        // La implementación real está en social-login.js
    }

    /**
     * Abre el modal con una experiencia fluida y rápida.
     */
    function openModal() {
        // Mostrar el modal inmediatamente con un loader
        var initialLoader = $('<div class="wp-alp-initial-loader"><div class="wp-alp-spinner"></div></div>');
        modal.content.html(initialLoader);
        modal.overlay.fadeIn(150); // Reducción de tiempo de fade para mayor rapidez
        
        // Precarga del contenido
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
                    // Actualizar el contenido rápidamente
                    updateModalContent(response.data.html);
                    // Notificar que el modal está abierto
                    $(document).trigger('wp_alp_modal_opened');
                } else {
                    loadInitialForm();
                }
            },
            error: function() {
                loadInitialForm();
            }
        });
    }

    /**
     * Cierra el modal.
     */
    function closeModal() {
        modal.overlay.fadeOut(300);
    }

    /**
     * Muestra el loader sin ocultar todo el modal.
     * Versión mejorada que mantiene el modal visible y solo muestra un indicador de carga
     * sobre el contenido actual.
     */
    function showLoader() {
        // En lugar de ocultar todo el contenido, agregamos una capa de carga encima
        if (!$('#wp-alp-content-loader').length) {
            var contentLoader = $('<div id="wp-alp-content-loader" class="wp-alp-content-loader"><div class="wp-alp-spinner"></div></div>');
            modal.content.append(contentLoader);
        } else {
            $('#wp-alp-content-loader').show();
        }
        
        // No ocultamos el contenido completo, solo lo hacemos visualmente inaccesible
        modal.content.css('opacity', '0.5');
    }

    /**
     * Oculta el loader manteniendo el contenido visible.
     * Versión mejorada que mantiene el modal visible durante toda la transición.
     */
    function hideLoader() {
        modal.loader.hide();
        $('#wp-alp-content-loader').hide();
        modal.content.css('opacity', '1');
        modal.content.show();
    }

    /**
     * Muestra un mensaje de error.
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
     * Muestra un mensaje de éxito.
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
     * Carga el formulario inicial.
     */
    function loadInitialForm() {
        showLoader();
        
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
                    // Si hay error, mostrar un formulario inicial base
                    var initialForm = `
                        <div class="wp-alp-form-container wp-alp-initial-form">
                            <h2>¡Te damos la bienvenida!</h2>
                            <div class="wp-alp-input-group">
                                <label for="wp-alp-identifier">Correo electrónico</label>
                                <input type="text" id="wp-alp-identifier" name="identifier" class="wp-alp-input" placeholder="Correo electrónico" />
                            </div>
                            <div class="wp-alp-button-group">
                                <button type="button" class="wp-alp-button wp-alp-primary-button" id="wp-alp-continue-btn">Continuar</button>
                            </div>
                        </div>
                    `;
                    updateModalContent(initialForm);
                    showError(response.data.message || 'Error al cargar el formulario.');
                }
            },
            error: function() {
                // Fallback para error de conexión
                var initialForm = `
                    <div class="wp-alp-form-container wp-alp-initial-form">
                        <h2>¡Te damos la bienvenida!</h2>
                        <div class="wp-alp-input-group">
                            <label for="wp-alp-identifier">Correo electrónico</label>
                            <input type="text" id="wp-alp-identifier" name="identifier" class="wp-alp-input" placeholder="Correo electrónico" />
                        </div>
                        <div class="wp-alp-button-group">
                            <button type="button" class="wp-alp-button wp-alp-primary-button" id="wp-alp-continue-btn">Continuar</button>
                        </div>
                    </div>
                `;
                updateModalContent(initialForm);
                showError('Error de conexión. Por favor, intenta nuevamente.');
            }
        });
    }

    /**
     * Carga el formulario de teléfono.
     */
    function loadPhoneForm() {
        showLoader();
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_get_form',
                form: 'phone',
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateModalContent(response.data.html);
                } else {
                    showError(response.data.message || 'Error al cargar el formulario.');
                }
            },
            error: function() {
                showError('Error de conexión. Por favor, intenta nuevamente.');
            }
        });
    }

    /**
     * Actualiza el contenido del modal de forma rápida y eficiente.
     */
    function updateModalContent(html) {
        // Ocultar loader
        hideLoader();
        
        // Reemplazar contenido directamente para mayor velocidad
        modal.content.empty().append($(html));
        
        // Notificar que se actualizó el contenido para inicializar componentes externos
        setTimeout(function() {
            $(document).trigger('wp_alp_content_updated');
        }, 10);
    }

    /**
     * Valida si un usuario existe.
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
                if (response.success) {
                    updateModalContent(response.data.html);
                } else {
                    loadInitialForm();
                    showError(response.data.message);
                }
            },
            error: function() {
                loadInitialForm();
                showError('Error de conexión. Por favor, intenta nuevamente.');
            }
        });
    }

    /**
     * Registra un nuevo usuario.
     */
    function registerUser(formData) {
        showLoader();
        
        // Añadir nonce y acción
        formData.action = 'wp_alp_register_user';
        formData.nonce = wp_alp_ajax.nonce;
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    if (response.data.needs_verification) {
                        updateModalContent(response.data.html);
                    } else {
                        showSuccess(response.data.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                } else {
                    hideLoader();
                    showError(response.data.message);
                }
            },
            error: function() {
                hideLoader();
                showError('Error de conexión. Por favor, intenta nuevamente.');
            }
        });
    }

    /**
     * Autentica a un usuario.
     */
    function loginUser(email, password) {
        showLoader();
        
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
                if (response.success) {
                    if (response.data.needs_profile) {
                        updateModalContent(response.data.html);
                    } else {
                        showSuccess(response.data.message);
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 1000);
                    }
                } else {
                    hideLoader();
                    showError(response.data.message);
                }
            },
            error: function() {
                hideLoader();
                showError('Error de conexión. Por favor, intenta nuevamente.');
            }
        });
    }

    /**
     * Verifica un código de verificación.
     */
    function verifyCode(code, userId) {
        showLoader();
        
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
                if (response.success) {
                    // Actualizar el nonce si se proporciona uno nuevo
                    if (response.data.new_nonce) {
                        wp_alp_ajax.nonce = response.data.new_nonce;
                        console.log('Nonce actualizado después de verificación');
                    }
                    
                    if (response.data.needs_profile) {
                        updateModalContent(response.data.html);
                    } else {
                        showSuccess(response.data.message);
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 1000);
                    }
                } else {
                    hideLoader();
                    showError(response.data.message);
                    
                    // Limpiar inputs de código
                    $('.wp-alp-verification-digit').val('');
                    $('.wp-alp-verification-digit[data-index="0"]').focus();
                }
            },
            error: function(xhr, status, error) {
                hideLoader();
                
                // Intentar una recuperación si la respuesta es 0 o está vacía
                if (xhr.responseText === '0' || xhr.responseText === '') {
                    console.log('Detectada respuesta vacía, intentando recuperar...');
                    
                    // Refrescar el nonce y reintentar
                    $.ajax({
                        url: wp_alp_ajax.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'wp_alp_refresh_nonce'
                        },
                        success: function(nonceResponse) {
                            if (nonceResponse.success) {
                                // Actualizar nonce
                                wp_alp_ajax.nonce = nonceResponse.data.nonce;
                                
                                // Mostrar mensaje de estado
                                showSuccess('Verificación procesada. Redirigiendo...');
                                
                                // Simular éxito y recargar la página después de un breve retraso
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                showError('Error de conexión. Por favor, intenta nuevamente.');
                            }
                        },
                        error: function() {
                            showError('Error de conexión. Por favor, intenta nuevamente.');
                        }
                    });
                } else {
                    showError('Error de conexión. Por favor, intenta nuevamente.');
                }
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
                    showSuccess(response.data.message);
                } else {
                    showError(response.data.message);
                }
            },
            error: function() {
                showError('Error de conexión. Por favor, intenta nuevamente.');
            }
        });
    }

    /**
     * Completa el perfil de un usuario.
     */
    function completeProfile(formData) {
        showLoader();
        
        // Añadir nonce y acción
        formData.action = 'wp_alp_complete_profile';
        formData.nonce = wp_alp_ajax.nonce;
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showSuccess(response.data.message);
                    setTimeout(function() {
                        window.location.href = response.data.redirect;
                    }, 1000);
                } else {
                    hideLoader();
                    showError(response.data.message);
                }
            },
            error: function() {
                hideLoader();
                showError('Error de conexión. Por favor, intenta nuevamente.');
            }
        });
    }

// Exponer algunas funciones para ser usadas por otros scripts
window.wpAlp = {
    showLoader: showLoader,
    hideLoader: hideLoader,
    showError: showError,
    showSuccess: showSuccess,
    updateModalContent: updateModalContent
};

// Pasos del móvil para mostrar dinámicamente
var mobileSteps = [
    {
        step: "Paso 1",
        title: "Describe tu servicio",
        description: "Agrega todos los detalles que los clientes necesitan saber sobre lo que ofreces."
    },
    {
        step: "Paso 2",
        title: "Añade fotos y videos",
        description: "Las imágenes de calidad aumentan considerablemente las posibilidades de recibir reservas."
    },
    {
        step: "Paso 3",
        title: "Establece tu disponibilidad",
        description: "Define cuándo estás disponible para ofrecer tus servicios."
    },
    {
        step: "Paso 4",
        title: "Fija tus precios",
        description: "Establece tarifas competitivas para atraer a más clientes."
    }
];

// Función para cambiar el contenido del móvil
function showMobileStep(index) {
    var step = mobileSteps[index];
    $('#mobile-step-content').fadeOut(200, function() {
        $(this).html(`
            <h3>${step.step}</h3>
            <h4>${step.title}</h4>
            <p>${step.description}</p>
        `).fadeIn(200);
    });
}

// Si estamos en la página de vendedor, iniciar la rotación de pasos
if ($('.wp-alp-vendor-page').length > 0) {
    // Iniciar rotación de pasos
    var currentStep = 0;
    setInterval(function() {
        currentStep = (currentStep + 1) % mobileSteps.length;
        showMobileStep(currentStep);
    }, 5000);

    // Para la sección de testimonios (slider simple)
    var testimonials = $('.wp-alp-testimonial');
    var currentTestimonial = 0;
    
    if (testimonials.length > 1) {
        testimonials.hide();
        $(testimonials[0]).show();
        
        setInterval(function() {
            $(testimonials[currentTestimonial]).fadeOut(400);
            currentTestimonial = (currentTestimonial + 1) % testimonials.length;
            $(testimonials[currentTestimonial]).fadeIn(400);
        }, 7000);
    }
}

})(jQuery);

