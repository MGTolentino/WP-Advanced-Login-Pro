/**
 * Inicialización correcta de las APIs de login social.
 */
(function($) {
    'use strict';

    // Variables globales
    let googleInitialized = false;
    let facebookInitialized = false;

    // Inicializar Google Sign-In (nueva API)
    function initGoogleSignIn() {
        if (typeof wp_alp_ajax !== 'undefined' && wp_alp_ajax.google_client_id && !googleInitialized) {
            try {
                // Cargar el script de Google de manera dinámica
                const script = document.createElement('script');
                script.src = 'https://accounts.google.com/gsi/client';
                script.async = true;
                script.defer = true;
                document.head.appendChild(script);
                
                script.onload = function() {
                    if (typeof google !== 'undefined') {
                        googleInitialized = true;
                        console.log('Google Identity Services cargado correctamente');
                        
                        // Añadir evento a los botones de Google
                        $(document).on('click', '#wp-alp-google-btn', function() {
                            // Si google.accounts.id está disponible, usarlo
                            if (google.accounts && google.accounts.id) {
                                google.accounts.id.initialize({
                                    client_id: wp_alp_ajax.google_client_id,
                                    callback: handleGoogleResponse
                                });
                                
                                google.accounts.id.prompt((notification) => {
                                    if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
                                        console.log('Google Sign-In no pudo mostrarse:', notification);
                                        showLoginError('Error al iniciar Google Sign-In. Por favor, intenta de nuevo.');
                                    }
                                });
                            } else {
                                showLoginError('Google Sign-In API no está disponible. Por favor, intenta más tarde.');
                            }
                        });
                    }
                };
                
                script.onerror = function() {
                    console.error('Error al cargar Google Identity Services');
                    showLoginError('Error al cargar Google Sign-In. Por favor, intenta más tarde.');
                };
            } catch (e) {
                console.error('Error al inicializar Google API:', e);
                showLoginError('Error al inicializar Google Sign-In. Por favor, intenta más tarde.');
            }
        }
    }
    
    // Callback para la respuesta de Google
    function handleGoogleResponse(response) {
        if (response.credential) {
            processSocialLogin('google', response.credential);
        } else {
            showLoginError('Error al iniciar sesión con Google. Por favor, intenta de nuevo.');
        }
    }

    // Inicializar Facebook Login
    function initFacebookLogin() {
        if (typeof wp_alp_ajax !== 'undefined' && wp_alp_ajax.facebook_app_id && !facebookInitialized) {
            try {
                // Cargar el SDK de Facebook de manera dinámica
                const script = document.createElement('script');
                script.src = 'https://connect.facebook.net/es_LA/sdk.js';
                script.async = true;
                script.defer = true;
                document.head.appendChild(script);
                
                script.onload = function() {
                    if (typeof FB !== 'undefined') {
                        facebookInitialized = true;
                        // Inicializar Facebook SDK
                        FB.init({
                            appId: wp_alp_ajax.facebook_app_id,
                            cookie: true,
                            xfbml: true,
                            version: 'v18.0' // Versión más reciente
                        });
                        
                        console.log('Facebook SDK cargado correctamente');
                        
                        // Añadir evento a los botones de Facebook
                        $(document).on('click', '#wp-alp-facebook-btn', function() {
                            FB.login(function(response) {
                                if (response.authResponse) {
                                    processSocialLogin('facebook', response.authResponse.accessToken);
                                } else {
                                    showLoginError('Error al iniciar sesión con Facebook. Por favor, intenta de nuevo.');
                                }
                            }, {scope: 'email,public_profile'});
                        });
                    }
                };
                
                script.onerror = function() {
                    console.error('Error al cargar Facebook SDK');
                    showLoginError('Error al cargar Facebook Login. Por favor, intenta más tarde.');
                };
            } catch (e) {
                console.error('Error al inicializar Facebook API:', e);
            }
        }
    }

    // Procesar login social
    function processSocialLogin(provider, token, userData) {
        // Mostrar loader
        $('#wp-alp-modal-loader').addClass('wp-alp-loading-overlay').show();
        
        const data = {
            action: 'wp_alp_social_login',
            provider: provider,
            token: token,
            nonce: wp_alp_ajax.nonce
        };
        
        // Añadir datos adicionales si existen
        if (userData) {
            if (userData.first_name) data.first_name = userData.first_name;
            if (userData.last_name) data.last_name = userData.last_name;
        }
        
        // Enviar solicitud AJAX
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    showLoginSuccess(response.data.message || 'Inicio de sesión exitoso');
                    
                    if (response.data.redirect) {
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 1000);
                    }
                } else {
                    $('#wp-alp-modal-loader').hide();
                    showLoginError(response.data.message || 'Error al iniciar sesión');
                }
            },
            error: function() {
                $('#wp-alp-modal-loader').hide();
                showLoginError('Error de conexión. Por favor, intenta nuevamente.');
            }
        });
    }

    // Mostrar mensaje de error
    function showLoginError(message) {
        var errorHtml = '<div class="wp-alp-error-message">' + message + '</div>';
        
        // Remover mensajes anteriores
        $('.wp-alp-error-message').remove();
        
        // Añadir nuevo mensaje
        $('#wp-alp-modal-content').prepend(errorHtml);
        
        // Auto-ocultar después de 5 segundos
        setTimeout(function() {
            $('.wp-alp-error-message').fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Mostrar mensaje de éxito
    function showLoginSuccess(message) {
        var successHtml = '<div class="wp-alp-success-message">' + message + '</div>';
        
        // Remover mensajes anteriores
        $('.wp-alp-success-message, .wp-alp-error-message').remove();
        
        // Añadir nuevo mensaje
        $('#wp-alp-modal-content').prepend(successHtml);
    }

    // Inicializar cuando el DOM esté listo
    $(document).ready(function() {
        // Inicializar login social cuando se abra el modal
        $(document).on('click', '[data-wp-alp-trigger="login"]', function() {
            setTimeout(function() {
                initGoogleSignIn();
                initFacebookLogin();
            }, 500);
        });
    });

})(jQuery);