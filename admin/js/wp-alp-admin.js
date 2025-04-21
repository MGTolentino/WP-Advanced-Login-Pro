/**
 * JavaScript para la administración de WP Advanced Login Pro.
 * 
 * Maneja la interactividad en las páginas de administración del plugin.
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Mostrar/ocultar campos de reCAPTCHA según esté habilitado o no
        var recaptchaToggle = $('input[name="wp_alp_enable_captcha"]');
        var recaptchaFields = $('.form-table tr:has(input[name^="wp_alp_recaptcha_"])');
        
        function toggleRecaptchaFields() {
            if (recaptchaToggle.is(':checked')) {
                recaptchaFields.show();
            } else {
                recaptchaFields.hide();
            }
        }
        
        // Inicializar visibilidad
        toggleRecaptchaFields();
        
        // Actualizar al cambiar
        recaptchaToggle.on('change', toggleRecaptchaFields);
        
        // Mostrar/ocultar campos de social login según esté habilitado o no
        var socialLoginToggle = $('input[name="wp_alp_enable_social_login"]');
        
        function toggleSocialFields() {
            if (socialLoginToggle.is(':checked')) {
                $('.wp-alp-social-settings').show();
            } else {
                $('.wp-alp-social-settings').hide();
            }
        }
        
        // Si estamos en la página de redes sociales
        if ($('.wp-alp-social-settings').length) {
            // Inicializar visibilidad
            toggleSocialFields();
            
            // Actualizar al cambiar
            socialLoginToggle.on('change', toggleSocialFields);
        }
        
        // Copiar código de shortcode al hacer clic
        $('.wp-alp-copy-shortcode').on('click', function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var $shortcode = $this.prev('.wp-alp-shortcode');
            
            // Crear un elemento de input temporal
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val($shortcode.text()).select();
            
            // Copiar al portapapeles
            document.execCommand('copy');
            $temp.remove();
            
            // Mostrar mensaje de éxito
            $this.text('¡Copiado!');
            setTimeout(function() {
                $this.text('Copiar');
            }, 2000);
        });
    });

})(jQuery);