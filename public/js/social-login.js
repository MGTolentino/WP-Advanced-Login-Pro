/**
 * Manejo del login social para WP Advanced Login Pro.
 * 
 * Este archivo implementa la autenticación con Google Identity Services,
 * Facebook SDK y Apple ID de forma moderna.
 */

(function($) {
    'use strict';

    // Variables de estado
    var socialLoginState = {
        googleInitialized: false,
        facebookInitialized: false,
        appleInitialized: false,
        googleButtonRendered: false
    };

    // Inicialización del módulo de login social
    window.socialLoginInitialized = true;

    // Cargar las APIs cuando el documento esté listo
    $(document).ready(function() {
        initSocialLoginButtons();
    });

    /**
     * Inicializa los botones de login social
     */
    function initSocialLoginButtons() {
        // Facebook Login - Asignar evento de clic
        $(document).on('click', '#wp-alp-facebook-btn', function(e) {
            e.preventDefault();
            handleFacebookLogin();
        });

        // Apple Login - Asignar evento de clic
        $(document).on('click', '#wp-alp-apple-btn', function(e) {
            e.preventDefault();
            handleAppleLogin();
        });

        // Google Login - No necesita evento de clic con la nueva API
        // Se renderiza como un botón personaliado por Google
    }

    /**
     * Función llamada cuando se abre el modal
     * Ahora se activa mediante el evento 'wp_alp_modal_opened'
     */
    window.socialLoginModalOpened = function() {
        // Cargar las APIs necesarias
        loadGoogleAPI();
        loadFacebookAPI();
        loadAppleAPI();
    };
    
    // Variable para evitar múltiples inicializaciones
    var isProcessingModalOpen = false;
    
    // Escuchar el evento de apertura del modal - con protección contra múltiples disparos
    $(document).on('wp_alp_modal_opened', function() {
        if (isProcessingModalOpen) return;
        isProcessingModalOpen = true;
        
        // Reiniciar el estado del botón para evitar problemas
        socialLoginState.googleButtonRendered = false;
        
        // Inicializar con delay para evitar condiciones de carrera
        setTimeout(function() {
            window.socialLoginModalOpened();
            isProcessingModalOpen = false;
        }, 100);
    });
    
    // Variable para controlar tiempo entre actualizaciones
    var lastContentUpdate = 0;
    
    // Escuchar el evento de actualización de contenido para re-renderizar botones
    $(document).on('wp_alp_content_updated', function() {
        // Evitar actualizaciones demasiado frecuentes (debe haber al menos 300ms entre cada una)
        var now = Date.now();
        if (now - lastContentUpdate < 300) return;
        lastContentUpdate = now;
        
        // Renderizar botón de Google solo si es necesario
        if (document.getElementById('wp-alp-google-btn') && !document.getElementById('google-btn-container')) {
            // Reiniciar estado para permitir un nuevo renderizado
            socialLoginState.googleButtonRendered = false;
            
            // Retrasar ligeramente para asegurar que el DOM esté estable
            setTimeout(function() {
                if (socialLoginState.googleInitialized) {
                    renderGoogleButton();
                }
            }, 150);
        }
    });

    /**
     * Carga la API de Google Identity Services de manera optimizada
     * Versión mejorada que precarga la API inmediatamente
     */
    function loadGoogleAPI() {
        if (typeof wp_alp_ajax === 'undefined' || !wp_alp_ajax.google_client_id) {
            return;
        }

        // Evitar carga duplicada
        if (socialLoginState.googleInitialized) {
            // La API ya está cargada, solo renderizar el botón si es necesario
            if (!socialLoginState.googleButtonRendered) {
                renderGoogleButton();
            }
            return;
        }

        // Verificar si el script ya fue cargado
        if (document.getElementById('google-api-script')) {
            return;
        }
        
        // Cargar la API inmediatamente
        var googleScript = document.createElement('script');
        googleScript.id = 'google-api-script';
        googleScript.src = 'https://accounts.google.com/gsi/client';
        googleScript.async = false; // Cargar de forma síncrona para evitar retrasos
        
        // Cuando el script se carga, inicializar Google Identity
        googleScript.onload = function() {
            socialLoginState.googleInitialized = true;
            initializeGoogleIdentity();
        };
        
        document.head.appendChild(googleScript);
    }

    /**
     * Inicializa la API de Google Identity Services
     */
    function initializeGoogleIdentity() {
        // Inicializar autenticación con Google

        if (typeof google === 'undefined' || !google.accounts) {
            // API de Google no disponible
            return;
        }

        // Inicializar el cliente de Google
        google.accounts.id.initialize({
            client_id: wp_alp_ajax.google_client_id,
            callback: handleGoogleCredentialResponse,
            auto_select: false,
            cancel_on_tap_outside: true
        });
        
        // Renderizar el botón
        renderGoogleButton();
    }

    // Eliminamos la función placeholderGoogleButton para evitar parpadeos y botones duplicados
    
    /**
     * Renderiza el botón de Google en el formulario - versión que muestra el botón de la API (en inglés)
     */
    function renderGoogleButton() {
        // Solo proceder si la API está inicializada
        if (!socialLoginState.googleInitialized || typeof google === 'undefined' || !google.accounts) {
            return;
        }
        
        // Verificar si existe el botón original
        var originalBtn = document.getElementById('wp-alp-google-btn');
        if (!originalBtn) {
            return;
        }
        
        // Evitar renderizar múltiples veces
        if (socialLoginState.googleButtonRendered || document.getElementById('google-btn-container')) {
            return;
        }
        
        // Crear contenedor para el botón de la API
        var container = document.createElement('div');
        container.id = 'google-btn-container';
        container.style.width = '100%';
        container.style.height = '40px';
        container.style.marginBottom = '10px';
        
        // Reemplazar el botón original con el contenedor
        originalBtn.style.display = 'none'; // Ocultar el botón original
        originalBtn.parentNode.insertBefore(container, originalBtn);
        
        // Renderizar el botón de Google (en inglés)
        try {
            google.accounts.id.renderButton(
                document.getElementById('google-btn-container'),
                {
                    type: 'standard',
                    theme: 'outline',
                    size: 'large',
                    text: 'continue_with',  // Usar 'continue_with' para que aparezca en inglés
                    shape: 'rectangular',
                    logo_alignment: 'center',
                    width: '100%'
                }
            );
            
            // Marcar como renderizado
            socialLoginState.googleButtonRendered = true;
        } catch (e) {
            // En caso de error, mostrar el botón original
            originalBtn.style.display = 'block';
            if (container && container.parentNode) {
                container.parentNode.removeChild(container);
            }
        }
    }

    /**
     * Maneja la respuesta de credenciales de Google
     */
    function handleGoogleCredentialResponse(response) {
        console.log('Respuesta de Google recibida');
        
        if (typeof window.wpAlp === 'undefined') {
            console.error('Plugin principal no inicializado');
            return;
        }
        
        // Mostrar loader
        window.wpAlp.showLoader();
        
        // Refrescar el nonce antes de enviar la solicitud
        refreshNonce(function(newNonce) {
            // Enviar el token JWT a nuestro servidor
            $.ajax({
                url: wp_alp_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'wp_alp_social_login',
                    provider: 'google',
                    token: response.credential,
                    nonce: newNonce
                },
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    
                    if (response.success) {
                        // Actualizar nonce si viene uno nuevo
                        if (response.data.new_nonce) {
                            wp_alp_ajax.nonce = response.data.new_nonce;
                        }
                        
                        window.wpAlp.showSuccess(response.data.message || 'Inicio de sesión exitoso');
                        
                        // Si necesita completar perfil
                        if (response.data.needs_profile) {
                            // Verificar si tenemos el HTML directamente
                            if (response.data.html) {
                                window.wpAlp.updateModalContent(response.data.html);
                            } else if (response.data.user_id) {
                                // Cargar el formulario con AJAX
                                loadProfileForm(response.data.user_id, response.data.new_nonce || wp_alp_ajax.nonce);
                            } else {
                                // Redireccionar si no podemos cargar el formulario
                                setTimeout(function() {
                                    window.location.href = response.data.redirect || window.location.href;
                                }, 1000);
                            }
                        } else {
                            // Redireccionar según la respuesta
                            setTimeout(function() {
                                window.location.href = response.data.redirect || window.location.href;
                            }, 1000);
                        }
                    } else {
                        window.wpAlp.hideLoader();
                        window.wpAlp.showError(response.data.message || 'Error en la autenticación con Google');
                    }
                },
                error: function(xhr) {
                    console.error('Error AJAX:', xhr);
                    window.wpAlp.hideLoader();
                    window.wpAlp.showError('Error de conexión. Por favor, intenta nuevamente.');
                    
                    // Si el error es 403, probablemente un problema de nonce
                    if (xhr.status === 403) {
                        refreshNonce();
                    }
                }
            });
        });
    }

    /**
     * Carga el SDK de Facebook
     */
    function loadFacebookAPI() {
        if (typeof wp_alp_ajax === 'undefined' || !wp_alp_ajax.facebook_app_id) {
            // ID de aplicación de Facebook no configurado
            return;
        }

        // Evitar carga duplicada
        if (socialLoginState.facebookInitialized) {
            return;
        }

        // Verificar si el script ya fue cargado
        if (document.getElementById('facebook-api-script') || typeof FB !== 'undefined') {
            socialLoginState.facebookInitialized = true;
            return;
        }

        // Función para inicializar Facebook
        window.fbAsyncInit = function() {
            FB.init({
                appId: wp_alp_ajax.facebook_app_id,
                cookie: true,
                xfbml: true,
                version: 'v18.0'
            });
            
            socialLoginState.facebookInitialized = true;
            // Facebook SDK inicializado correctamente
        };

        // Cargar el script de Facebook
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); 
            js.id = 'facebook-api-script';
            js.src = "https://connect.facebook.net/es_LA/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        console.log('API de Facebook cargada');
    }

    /**
     * Maneja el login con Facebook
     */
    function handleFacebookLogin() {
        if (typeof window.wpAlp === 'undefined') {
            console.error('Plugin principal no inicializado correctamente');
            return;
        }
        
        try {
            // Verificar si la API de Facebook está disponible
            if (typeof FB === 'undefined') {
                window.wpAlp.showError('Cargando Facebook Login...');
                
                // Intentar cargar de nuevo el SDK de Facebook
                loadFacebookAPI();
                
                // Esperar un poco y reintentar
                setTimeout(function() {
                    if (typeof FB !== 'undefined') {
                        handleFacebookLogin();
                    } else {
                        window.wpAlp.showError('No se pudo cargar Facebook Login. Por favor, intenta más tarde.');
                    }
                }, 2000);
                
                return;
            }
            
            // Mostrar loader
            window.wpAlp.showLoader();
            
            // Refrescar nonce antes del login
            refreshNonce(function(newNonce) {
                // Iniciar login con Facebook
                FB.login(function(response) {
                    if (response.authResponse) {
                        // Procesar el login exitoso
                        $.ajax({
                            url: wp_alp_ajax.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'wp_alp_social_login',
                                provider: 'facebook',
                                token: response.authResponse.accessToken,
                                nonce: newNonce
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Actualizar nonce si viene uno nuevo
                                    if (response.data.new_nonce) {
                                        wp_alp_ajax.nonce = response.data.new_nonce;
                                    }
                                    
                                    window.wpAlp.showSuccess(response.data.message || 'Inicio de sesión exitoso');
                                    
                                    // Si necesita completar perfil
                                    if (response.data.needs_profile) {
                                        // Verificar si tenemos el HTML directamente
                                        if (response.data.html) {
                                            window.wpAlp.updateModalContent(response.data.html);
                                        } else if (response.data.user_id) {
                                            // Cargar el formulario con AJAX
                                            loadProfileForm(response.data.user_id, response.data.new_nonce || wp_alp_ajax.nonce);
                                        } else {
                                            // Redireccionar si no podemos cargar el formulario
                                            setTimeout(function() {
                                                window.location.href = response.data.redirect || window.location.href;
                                            }, 1000);
                                        }
                                    } else {
                                        // Redireccionar según la respuesta
                                        setTimeout(function() {
                                            window.location.href = response.data.redirect || window.location.href;
                                        }, 1000);
                                    }
                                } else {
                                    window.wpAlp.hideLoader();
                                    window.wpAlp.showError(response.data.message || 'Error en el inicio de sesión con Facebook');
                                }
                            },
                            error: function(xhr) {
                                console.error('Error AJAX:', xhr);
                                window.wpAlp.hideLoader();
                                window.wpAlp.showError('Error de conexión. Por favor, intenta nuevamente.');
                                
                                // Si el error es 403, probablemente un problema de nonce
                                if (xhr.status === 403) {
                                    refreshNonce();
                                }
                            }
                        });
                    } else {
                        window.wpAlp.hideLoader();
                        window.wpAlp.showError('Inicio de sesión con Facebook cancelado.');
                    }
                }, {scope: 'public_profile,email'});
            });
        } catch (e) {
            // Error en proceso de login con Facebook
            window.wpAlp.hideLoader();
            window.wpAlp.showError('Error en el inicio de sesión con Facebook: ' + e.message);
        }
    }

    /**
     * Carga la API de Apple
     */
    function loadAppleAPI() {
        if (typeof wp_alp_ajax === 'undefined' || !wp_alp_ajax.apple_client_id) {
            // Cliente de Apple no configurado
            return;
        }

        // Evitar carga duplicada
        if (socialLoginState.appleInitialized) {
            return;
        }

        // Verificar si el script ya fue cargado
        if (document.getElementById('apple-api-script') || typeof AppleID !== 'undefined') {
            socialLoginState.appleInitialized = true;
            return;
        }

        // Crear el script
        var appleScript = document.createElement('script');
        appleScript.id = 'apple-api-script';
        appleScript.src = 'https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js';
        appleScript.async = true;
        
        // Cuando el script se carga, marcar como inicializado
        appleScript.onload = function() {
            socialLoginState.appleInitialized = true;
        };
        
        document.head.appendChild(appleScript);
        console.log('API de Apple cargada');
    }

    /**
     * Maneja el login con Apple
     */
    function handleAppleLogin() {
        if (typeof window.wpAlp === 'undefined') {
            console.error('Plugin principal no inicializado correctamente');
            return;
        }
        
        try {
            if (typeof AppleID === 'undefined') {
                window.wpAlp.showError('Cargando Apple Sign-In...');
                
                // Intentar cargar de nuevo la API de Apple
                loadAppleAPI();
                
                // Esperar un poco y reintentar
                setTimeout(function() {
                    if (typeof AppleID !== 'undefined') {
                        handleAppleLogin();
                    } else {
                        window.wpAlp.showError('No se pudo cargar Apple Sign-In. Por favor, intenta más tarde.');
                    }
                }, 2000);
                
                return;
            }
            
            // Mostrar loader
            window.wpAlp.showLoader();
            
            // Refrescar nonce antes del login
            refreshNonce(function(newNonce) {
                // Iniciar login con Apple
                AppleID.auth.init({
                    clientId: wp_alp_ajax.apple_client_id,
                    scope: 'name email',
                    redirectURI: window.location.href,
                    usePopup: true
                });
                
                AppleID.auth.signIn().then(function(response) {
                    if (response.authorization && response.authorization.id_token) {
                        // Recopilar datos del usuario si están disponibles
                        var userData = {};
                        if (response.user) {
                            userData.first_name = response.user.name ? response.user.name.firstName : '';
                            userData.last_name = response.user.name ? response.user.name.lastName : '';
                        }
                        
                        // Enviar el token JWT a nuestro servidor
                        $.ajax({
                            url: wp_alp_ajax.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'wp_alp_social_login',
                                provider: 'apple',
                                token: response.authorization.id_token,
                                first_name: userData.first_name || '',
                                last_name: userData.last_name || '',
                                nonce: newNonce
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Actualizar nonce si viene uno nuevo
                                    if (response.data.new_nonce) {
                                        wp_alp_ajax.nonce = response.data.new_nonce;
                                    }
                                    
                                    window.wpAlp.showSuccess(response.data.message || 'Inicio de sesión exitoso');
                                    
                                    // Si necesita completar perfil
                                    if (response.data.needs_profile) {
                                        // Verificar si tenemos el HTML directamente
                                        if (response.data.html) {
                                            window.wpAlp.updateModalContent(response.data.html);
                                        } else if (response.data.user_id) {
                                            // Cargar el formulario con AJAX
                                            loadProfileForm(response.data.user_id, response.data.new_nonce || wp_alp_ajax.nonce);
                                        } else {
                                            // Redireccionar si no podemos cargar el formulario
                                            setTimeout(function() {
                                                window.location.href = response.data.redirect || window.location.href;
                                            }, 1000);
                                        }
                                    } else {
                                        // Redireccionar según la respuesta
                                        setTimeout(function() {
                                            window.location.href = response.data.redirect || window.location.href;
                                        }, 1000);
                                    }
                                } else {
                                    window.wpAlp.hideLoader();
                                    window.wpAlp.showError(response.data.message || 'Error en el inicio de sesión con Apple');
                                }
                            },
                            error: function(xhr) {
                                console.error('Error AJAX:', xhr);
                                window.wpAlp.hideLoader();
                                window.wpAlp.showError('Error de conexión. Por favor, intenta nuevamente.');
                                
                                // Si el error es 403, probablemente un problema de nonce
                                if (xhr.status === 403) {
                                    refreshNonce();
                                }
                            }
                        });
                    } else {
                        window.wpAlp.hideLoader();
                        window.wpAlp.showError('Error en el inicio de sesión con Apple. Por favor, intenta nuevamente.');
                    }
                }).catch(function(error) {
                    console.error('Error en Apple Login:', error);
                    window.wpAlp.hideLoader();
                    window.wpAlp.showError('Error en el inicio de sesión con Apple: ' + error.message);
                });
            });
        } catch (e) {
            console.error('Error en Apple Login:', e);
            window.wpAlp.hideLoader();
            window.wpAlp.showError('Error en el inicio de sesión con Apple: ' + e.message);
        }
    }

    /**
     * Carga el formulario de perfil
     */
    function loadProfileForm(userId, nonce) {
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_get_form',
                form: 'profile',
                user_id: userId,
                nonce: nonce || wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    window.wpAlp.updateModalContent(response.data.html);
                } else {
                    window.wpAlp.hideLoader();
                    window.wpAlp.showError(response.data.message || 'Error al cargar formulario de perfil');
                    
                    // Si hay un error, intentar refrescar el nonce
                    refreshNonce();
                }
            },
            error: function(xhr) {
                console.error('Error cargando formulario de perfil:', xhr);
                window.wpAlp.hideLoader();
                window.wpAlp.showError('Error de conexión. Por favor, intenta nuevamente.');
                
                // Si el error es 403, probablemente un problema de nonce
                if (xhr.status === 403) {
                    refreshNonce(function() {
                        // Reintentar carga con nuevo nonce
                        loadProfileForm(userId);
                    });
                }
            }
        });
    }

    /**
     * Refresca el nonce
     */
    function refreshNonce(callback) {
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_refresh_nonce'
            },
            success: function(response) {
                if (response.success) {
                    // Actualizar nonce global
                    wp_alp_ajax.nonce = response.data.nonce;
                    console.log('Nonce actualizado correctamente');
                    
                    if (typeof callback === 'function') {
                        callback(response.data.nonce);
                    }
                } else {
                    console.error('Error al refrescar nonce:', response);
                }
            },
            error: function(xhr) {
                console.error('Error AJAX al refrescar nonce:', xhr);
            }
        });
    }

    // Eliminamos el observador basado en DOMNodeInserted ya que ahora usamos eventos personalizados
    // Esto evita múltiples renderizaciones y mejora el rendimiento

    // Exponer funciones clave para uso global
    window.wpAlpSocial = {
        refreshNonce: refreshNonce,
        loadProfileForm: loadProfileForm,
        renderGoogleButton: renderGoogleButton
    };

})(jQuery);