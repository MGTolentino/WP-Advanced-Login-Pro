/**
 * Inicialización correcta de las APIs de login social.
 */
(function($) {
    'use strict';

    // Inicializar Google Sign-In
    function initGoogleSignIn() {
        if (typeof wp_alp_ajax !== 'undefined' && wp_alp_ajax.google_client_id) {
            const googleClientId = wp_alp_ajax.google_client_id;
            
            // Cargar la biblioteca de Google
            gapi.load('auth2', function() {
                // Inicializar la API de Google
                try {
                    gapi.auth2.init({
                        client_id: googleClientId,
                        scope: 'profile email'
                    }).then(function(auth2) {
                        console.log('Google API inicializada correctamente');
                        
                        // Añadir evento a los botones de Google
                        $('.wp-alp-google-button').on('click', function() {
                            auth2.signIn().then(function(googleUser) {
                                const idToken = googleUser.getAuthResponse().id_token;
                                processSocialLogin('google', idToken);
                            }).catch(function(error) {
                                console.error('Error en Google Sign-In:', error);
                                showLoginError('Error al iniciar sesión con Google. Por favor, intenta de nuevo.');
                            });
                        });
                    });
                } catch (e) {
                    console.error('Error al inicializar Google API:', e);
                }
            });
        }
    }

    // Inicializar Facebook Login
    function initFacebookLogin() {
        if (typeof wp_alp_ajax !== 'undefined' && wp_alp_ajax.facebook_app_id) {
            const fbAppId = wp_alp_ajax.facebook_app_id;
            
            // Configurar Facebook SDK
            window.fbAsyncInit = function() {
                FB.init({
                    appId: fbAppId,
                    cookie: true,
                    xfbml: true,
                    version: 'v12.0'
                });
                
                // Añadir evento a los botones de Facebook
                $('.wp-alp-facebook-button').on('click', function() {
                    FB.login(function(response) {
                        if (response.authResponse) {
                            const accessToken = response.authResponse.accessToken;
                            processSocialLogin('facebook', accessToken);
                        } else {
                            showLoginError('Error al iniciar sesión con Facebook. Por favor, intenta de nuevo.');
                        }
                    }, {scope: 'email'});
                });
            };
            
            // Cargar SDK de Facebook
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "https://connect.facebook.net/es_LA/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
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
        
        // Añadir datos
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
        // Dar tiempo para que se carguen las APIs
        setTimeout(function() {
            if (typeof gapi !== 'undefined') {
                initGoogleSignIn();
            }
            if (typeof FB === 'undefined') {
                initFacebookLogin();
            }
        }, 1000);
    });
});

})(jQuery);