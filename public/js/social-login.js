/**
 * Manejo del login social para WP Advanced Login Pro.
 * 
 * Este archivo maneja la inicialización y procesamiento
 * del login con Google, Facebook y Apple.
 */

(function($) {

    
    'use strict';

    var googleSignInInProgress = false;


    // Marcar que esta implementación está activa
    window.socialLoginInitialized = true;

    // Cargar las APIs cuando el documento esté listo
    $(document).ready(function() {
        // Inicializar handlers
        initSocialLoginButtons();
    });

    /**
     * Inicializa los botones de login social
     */
    function initSocialLoginButtons() {
        // Google Login
        $(document).on('click', '#wp-alp-google-btn', function(e) {
            e.preventDefault();
            handleGoogleLogin();
        });

        // Facebook Login
        $(document).on('click', '#wp-alp-facebook-btn', function(e) {
            e.preventDefault();
            handleFacebookLogin();
        });

        // Apple Login
        $(document).on('click', '#wp-alp-apple-btn', function(e) {
            e.preventDefault();
            handleAppleLogin();
        });
    }

    /**
     * Función llamada cuando se abre el modal
     */
    window.socialLoginModalOpened = function() {
        // Cargar las APIs necesarias
        loadGoogleAPI();
        loadFacebookAPI();
        loadAppleAPI();
    };

    /**
     * Carga la API de Google
     */
    function loadGoogleAPI() {
        if (typeof wp_alp_ajax === 'undefined') {
            console.error('Plugin WP-ALP: Variable wp_alp_ajax no disponible');
            return;
        }
        
        if (!wp_alp_ajax.google_client_id) {
            console.error('Plugin WP-ALP: Cliente ID de Google no configurado');
            return;
        }

        // Verificar si el script ya fue cargado
        if (document.getElementById('google-api-script')) {
            return;
        }

        // Crear el script
        var googleScript = document.createElement('script');
        googleScript.id = 'google-api-script';
        googleScript.src = 'https://accounts.google.com/gsi/client';
        googleScript.async = true;
        googleScript.defer = true;
        document.head.appendChild(googleScript);

        console.log('API de Google cargada');
    }

    /**
     * Carga el SDK de Facebook
     */
    function loadFacebookAPI() {
        if (typeof wp_alp_ajax === 'undefined') {
            console.error('Plugin WP-ALP: Variable wp_alp_ajax no disponible');
            return;
        }
        
        if (!wp_alp_ajax.google_client_id) {
            console.error('Plugin WP-ALP: Cliente ID de Google no configurado');
            return;
        }

        // Verificar si el script ya fue cargado
        if (document.getElementById('facebook-api-script')) {
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
            
            console.log('Facebook SDK inicializado');
        };

        // Cargar el script de Facebook
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); 
            js.id = id;
            js.id = 'facebook-api-script';
            js.src = "https://connect.facebook.net/es_LA/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        console.log('API de Facebook cargada');
    }

    /**
     * Carga la API de Apple
     */
    function loadAppleAPI() {
        if (typeof wp_alp_ajax === 'undefined') {
            console.error('Plugin WP-ALP: Variable wp_alp_ajax no disponible');
            return;
        }
        
        if (!wp_alp_ajax.google_client_id) {
            console.error('Plugin WP-ALP: Cliente ID de Google no configurado');
            return;
        }

        // Verificar si el script ya fue cargado
        if (document.getElementById('apple-api-script')) {
            return;
        }

        // Crear el script
        var appleScript = document.createElement('script');
        appleScript.id = 'apple-api-script';
        appleScript.src = 'https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js';
        appleScript.async = true;
        document.head.appendChild(appleScript);

        console.log('API de Apple cargada');
    }

    /**
     * Maneja el login con Google
     */
    function handleGoogleLogin() {
        if (typeof window.wpAlp === 'undefined') {
            console.error('Plugin principal no inicializado correctamente');
            return;
        }
        
        // Evitar múltiples solicitudes
        if (googleSignInInProgress) {
            console.log('Ya hay un proceso de inicio de sesión con Google en curso');
            return;
        }
        
        try {
            // Marcar que hay un proceso en curso
            googleSignInInProgress = true;
            
            // Mostrar loader
            window.wpAlp.showLoader();
            
            // Construir URL de autorización manualmente
            var googleAuthUrl = 'https://accounts.google.com/o/oauth2/auth?' + 
                'client_id=' + encodeURIComponent(wp_alp_ajax.google_client_id) + 
                '&redirect_uri=' + encodeURIComponent(wp_alp_ajax.home_url + '/wp-json/wp-alp/v1/auth/google') +
                '&response_type=code' +
                '&scope=email%20profile' +
                '&access_type=online' +
                '&state=' + encodeURIComponent(wp_alp_ajax.nonce);
            
            // Redireccionar a Google
            console.log('Redireccionando a Google para autenticación');
            window.location.href = googleAuthUrl;
        } catch (e) {
            // Reiniciar la bandera
            googleSignInInProgress = false;
            console.error('Error en Google Login:', e);
            window.wpAlp.hideLoader();
            window.wpAlp.showError('Error en el inicio de sesión con Google: ' + e.message);
        }
    }

    /**
     * Procesa el token de Google
     */
    function processGoogleLogin(token) {
        console.log('Procesando token de Google');
        
        $.ajax({
            url: wp_alp_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wp_alp_social_login',
                provider: 'google',
                token: token,
                nonce: wp_alp_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    window.wpAlp.showSuccess(response.data.message || 'Inicio de sesión exitoso');
                    
                    if (response.data.needs_profile) {
                        window.wpAlp.updateModalContent(response.data.html);
                    } else if (response.data.redirect) {
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 1000);
                    } else {
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                } else {
                    window.wpAlp.hideLoader();
                    window.wpAlp.showError(response.data.message || 'Error en el inicio de sesión con Google');
                }
            },
            error: function() {
                window.wpAlp.hideLoader();
                window.wpAlp.showError('Error de conexión. Por favor, intenta nuevamente.');
                googleSignInInProgress = false;
            }
        });
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
            if (typeof FB === 'undefined') {
                window.wpAlp.showError('Cargando Facebook Login...');
                
                // Intentar cargar de nuevo el SDK de Facebook
                loadFacebookAPI();
                
                // Esperar un poco y reintentar
                setTimeout(function() {
                    if (typeof FB !== 'undefined') {
                        console.log('SDK de Facebook listo, reintentando...');
                        handleFacebookLogin();
                    } else {
                        window.wpAlp.showError('No se pudo cargar Facebook Login. Por favor, intenta más tarde.');
                   }
               }, 2000);
               
               return;
           }
           
           // Mostrar loader
           window.wpAlp.showLoader();
           
           // Iniciar login con Facebook
           FB.login(function(response) {
               console.log('Facebook login callback ejecutado');
               if (response.authResponse) {
                   processFacebookLogin(response.authResponse.accessToken);
               } else {
                   window.wpAlp.hideLoader();
                   window.wpAlp.showError('Error en el inicio de sesión con Facebook. Por favor, intenta nuevamente.');
               }
           }, {scope: 'email,public_profile'});
       } catch (e) {
           console.error('Error en Facebook Login:', e);
           window.wpAlp.hideLoader();
           window.wpAlp.showError('Error en el inicio de sesión con Facebook: ' + e.message);
       }
   }

   /**
    * Procesa el token de Facebook
    */
   function processFacebookLogin(token) {
       console.log('Procesando token de Facebook');
       
       $.ajax({
           url: wp_alp_ajax.ajax_url,
           type: 'POST',
           data: {
               action: 'wp_alp_social_login',
               provider: 'facebook',
               token: token,
               nonce: wp_alp_ajax.nonce
           },
           success: function(response) {
               if (response.success) {
                   window.wpAlp.showSuccess(response.data.message || 'Inicio de sesión exitoso');
                   
                   if (response.data.needs_profile) {
                       window.wpAlp.updateModalContent(response.data.html);
                   } else if (response.data.redirect) {
                       setTimeout(function() {
                           window.location.href = response.data.redirect;
                       }, 1000);
                   } else {
                       setTimeout(function() {
                           window.location.reload();
                       }, 1000);
                   }
               } else {
                   window.wpAlp.hideLoader();
                   window.wpAlp.showError(response.data.message || 'Error en el inicio de sesión con Facebook');
               }
           },
           error: function() {
               window.wpAlp.hideLoader();
               window.wpAlp.showError('Error de conexión. Por favor, intenta nuevamente.');
           }
       });
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
                       console.log('API de Apple lista, reintentando...');
                       handleAppleLogin();
                   } else {
                       window.wpAlp.showError('No se pudo cargar Apple Sign-In. Por favor, intenta más tarde.');
                   }
               }, 2000);
               
               return;
           }
           
           // Mostrar loader
           window.wpAlp.showLoader();
           
           // Iniciar login con Apple
           AppleID.auth.init({
               clientId: wp_alp_ajax.apple_client_id,
               scope: 'name email',
               redirectURI: window.location.href,
               usePopup: true
           });
           
           AppleID.auth.signIn().then(function(response) {
               console.log('Apple login callback ejecutado');
               if (response.authorization && response.authorization.id_token) {
                   // Recopilar datos del usuario si están disponibles
                   var userData = {};
                   if (response.user) {
                       userData.first_name = response.user.name ? response.user.name.firstName : '';
                       userData.last_name = response.user.name ? response.user.name.lastName : '';
                   }
                   
                   processAppleLogin(response.authorization.id_token, userData);
               } else {
                   window.wpAlp.hideLoader();
                   window.wpAlp.showError('Error en el inicio de sesión con Apple. Por favor, intenta nuevamente.');
               }
           }).catch(function(error) {
               console.error('Error en Apple Login:', error);
               window.wpAlp.hideLoader();
               window.wpAlp.showError('Error en el inicio de sesión con Apple: ' + error.message);
           });
       } catch (e) {
           console.error('Error en Apple Login:', e);
           window.wpAlp.hideLoader();
           window.wpAlp.showError('Error en el inicio de sesión con Apple: ' + e.message);
       }
   }

   /**
    * Procesa el token de Apple
    */
   function processAppleLogin(token, userData) {
       console.log('Procesando token de Apple');
       
       var data = {
           action: 'wp_alp_social_login',
           provider: 'apple',
           token: token,
           nonce: wp_alp_ajax.nonce
       };
       
       // Añadir datos del usuario si están disponibles
       if (userData) {
           if (userData.first_name) data.first_name = userData.first_name;
           if (userData.last_name) data.last_name = userData.last_name;
       }
       
       $.ajax({
           url: wp_alp_ajax.ajax_url,
           type: 'POST',
           data: data,
           success: function(response) {
               if (response.success) {
                   window.wpAlp.showSuccess(response.data.message || 'Inicio de sesión exitoso');
                   
                   if (response.data.needs_profile) {
                       window.wpAlp.updateModalContent(response.data.html);
                   } else if (response.data.redirect) {
                       setTimeout(function() {
                           window.location.href = response.data.redirect;
                       }, 1000);
                   } else {
                       setTimeout(function() {
                           window.location.reload();
                       }, 1000);
                   }
               } else {
                   window.wpAlp.hideLoader();
                   window.wpAlp.showError(response.data.message || 'Error en el inicio de sesión con Apple');
               }
           },
           error: function() {
               window.wpAlp.hideLoader();
               window.wpAlp.showError('Error de conexión. Por favor, intenta nuevamente.');
           }
       });
   }

})(jQuery);