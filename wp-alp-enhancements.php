<?php
/**
 * Mejoras al plugin WP-Advanced-Login-Pro
 * 
 * Este archivo contiene arreglos y mejoras para proporcionar
 * una experiencia de usuario fluida al estilo Airbnb.
 */

// Evitar acceso directo
if (!defined('WPINC')) {
    die;
}

class WP_ALP_Enhancements {

    /**
     * Inicializar las mejoras
     */
    public static function init() {
        // Cargar el JavaScript mejorado
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_enhanced_scripts'), 99);
        
        // Modificar la funcionalidad del perfil
        add_action('init', array(__CLASS__, 'fix_user_profile_flow'));
        
        // Modificar la funcionalidad de login
        add_action('init', array(__CLASS__, 'fix_login_ajax'));
        
        // Fix para el menú (que ya tenías)
        add_filter('nav_menu_link_attributes', array(__CLASS__, 'add_login_trigger_attribute'), 10, 3);
    }

    /**
     * Cargar scripts y estilos mejorados
     */
    public static function enqueue_enhanced_scripts() {
        // Desregistrar el script original
        wp_deregister_script('wp-advanced-login-pro');
        
        // Registrar script inline para mayor compatibilidad
        wp_enqueue_script('wp-alp-enhanced-inline', '', array('jquery'), WP_ALP_VERSION, true);
        
        // Pasar variables al script
        wp_localize_script('wp-alp-enhanced-inline', 'wp_alp_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_alp_nonce'),
            'home_url' => home_url(),
            'translations' => array(
                'error' => __('Error', 'wp-alp'),
                'success' => __('Éxito', 'wp-alp'),
                'loading' => __('Cargando...', 'wp-alp'),
                'invalid_email' => __('Por favor, introduce un email válido.', 'wp-alp'),
                'invalid_phone' => __('Por favor, introduce un número de teléfono válido.', 'wp-alp'),
                'required_field' => __('Este campo es obligatorio.', 'wp-alp'),
                'password_short' => __('La contraseña debe tener al menos 6 caracteres.', 'wp-alp'),
                'verify_code' => __('Por favor, introduce el código de verificación completo.', 'wp-alp'),
            ),
        ));
        
        // Añadir el código JavaScript directamente
        wp_add_inline_script('wp-alp-enhanced-inline', self::get_enhanced_js());
        
        // Añadir estilos CSS adicionales
        wp_add_inline_style('wp-advanced-login-pro', '
            /* Mejorar estilos del modal para transiciones fluidas */
            .wp-alp-modal-loader {
                background-color: rgba(255, 255, 255, 0.7);
                z-index: 10;
            }
            
            .wp-alp-modal-content {
                position: relative;
                z-index: 5;
                min-height: 300px; /* Evitar saltos de altura */
            }
            
            /* Estilos para las animaciones de fade */
            .wp-alp-fade {
                transition: opacity 0.3s ease;
            }
            
            /* Asegurar que el modal permanezca centrado */
            .wp-alp-modal-container {
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        ');
    }
    
    /**
     * Obtener el código JavaScript mejorado
     */
    private static function get_enhanced_js() {
        return <<<'JAVASCRIPT'
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

})(jQuery);
JAVASCRIPT;
    }

    /**
     * Añade el atributo data-wp-alp-trigger="login" a los elementos del menú
     */
    public static function add_login_trigger_attribute($atts, $item, $args) {
        // Verifica si el elemento tiene la clase wp-alp-login-trigger
        if (in_array('wp-alp-login-trigger', $item->classes)) {
            $atts['data-wp-alp-trigger'] = 'login';
        }
        return $atts;
    }

    /**
     * Modifica la función validate_user_ajax para cambiar la detección de perfil
     */
    public static function fix_user_profile_flow() {
        // Modificar la clase para cambiar la comprobación del perfil
        add_filter('wp_alp_validate_user_result', array(__CLASS__, 'modify_validate_result'), 10, 2);
    }

    /**
     * Modifica el resultado de la validación de usuario
     */
    public static function modify_validate_result($data, $result) {
        if ($result['exists']) {
            // Obtener el usuario de WordPress
            $user = get_user_by('ID', $result['user_id']);
            
            // Comprobar si es un subscriber con perfil incompleto
            if ($result['found_by'] === 'email' && 
                is_object($user) &&
                (in_array('subscriber', (array)$user->roles) || $result['user_type'] === 'subscriber') && 
                $result['profile_status'] === 'incomplete') {
                // Cambiar flujo: mostrar formulario de login primero
                $data['needs_profile'] = false;
            }
        }
        
        return $data;
    }

    /**
     * Modifica la función login_user_ajax
     */
    public static function fix_login_ajax() {
        // Añadir un filtro para modificar el resultado del login
        add_filter('wp_alp_login_user_result', array(__CLASS__, 'modify_login_result'), 10, 2);
    }

    /**
     * Modifica el resultado del login
     */
    public static function modify_login_result($response, $user_id) {
        // Verificar si el usuario necesita completar su perfil
        $user = get_user_by('ID', $user_id);
        $profile_status = get_user_meta($user_id, 'wp_alp_profile_status', true);
        
        if (is_object($user) && 
            in_array('subscriber', (array)$user->roles) && 
            $profile_status === 'incomplete') {
            
            // Usuario necesita completar su perfil
            $response['needs_profile'] = true;
            
            // Usar la clase original del plugin para obtener el HTML del formulario
            if (class_exists('WP_ALP_Forms')) {
                $response['html'] = WP_ALP_Forms::get_profile_completion_form($user_id);
            }
        }
        
        return $response;
    }
}

// Inicializar las mejoras
WP_ALP_Enhancements::init();

// Asegurarse de que la clase principal del plugin está cargada
add_action('plugins_loaded', function() {
    // Verificar si el plugin está activo correctamente
    if (class_exists('WP_ALP_Core')) {
        // La clase principal existe, agregar las acciones AJAX necesarias
        add_action('wp_ajax_wp_alp_get_form', function() {
            $plugin_public = new WP_ALP_Public('wp-advanced-login-pro', WP_ALP_VERSION);
            $plugin_public->get_form_ajax();
        });
        add_action('wp_ajax_nopriv_wp_alp_get_form', function() {
            $plugin_public = new WP_ALP_Public('wp-advanced-login-pro', WP_ALP_VERSION);
            $plugin_public->get_form_ajax();
        });
    }
});