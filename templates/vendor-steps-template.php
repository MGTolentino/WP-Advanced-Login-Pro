<?php
/**
 * Template Name: Pasos para Vendedores WP-ALP
 * 
 * Template para la página de pasos del proceso de convertirse en vendedor
 * Simplificado para mostrar solo la página inicial y un primer paso básico
 */

get_header(); ?>

<div class="wp-alp-vendor-form-page">
    <!-- Contenedor principal para la página inicial -->
    <div class="wp-alp-form-content">
        <div class="wp-alp-container">
            <!-- Paso Inicial: Información sobre los pasos -->
            <div class="wp-alp-form-step" id="step-0" data-step="0">
                <div class="wp-alp-steps-container">
                    <div class="wp-alp-steps-left">
                        <h1 class="wp-alp-steps-heading">
                            Empezar a ofrecer tus servicios es muy sencillo
                        </h1>
                    </div>
                    
                    <div class="wp-alp-steps-right">
                        <div class="wp-alp-steps-list">
                            <!-- Step 1 -->
                            <div class="wp-alp-step-item">
                                <div class="wp-alp-step-content">
                                    <span class="wp-alp-step-number">1</span>
                                    <h2 class="wp-alp-step-title">
                                        <?php echo esc_html(get_locale() == 'en_US' ? 'Describe your service' : 'Describe tu servicio'); ?>
                                    </h2>
                                    <p class="wp-alp-step-description">
                                        <?php echo esc_html(get_locale() == 'en_US' ? 'Add some basic information, like what kind of service you offer and your capacity.' : 'Agrega información básica, como qué tipo de servicio ofreces y tu capacidad.'); ?>
                                    </p>
                                </div>
                                <div class="wp-alp-step-image">
                                    <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-step1.png'); ?>" alt="Step 1">
                                </div>
                            </div>
                            
                            <!-- Step 2 -->
                            <div class="wp-alp-step-item">
                                <div class="wp-alp-step-content">
                                    <span class="wp-alp-step-number">2</span>
                                    <h2 class="wp-alp-step-title">
                                        <?php echo esc_html(get_locale() == 'en_US' ? 'Make it stand out' : 'Haz que destaque'); ?>
                                    </h2>
                                    <p class="wp-alp-step-description">
                                        <?php echo esc_html(get_locale() == 'en_US' ? 'Add at least five photos, a title, and a description. We\'ll help you.' : 'Agrega al menos cinco fotos, un título y una descripción. Nosotros te ayudamos.'); ?>
                                    </p>
                                </div>
                                <div class="wp-alp-step-image">
                                    <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-step2.png'); ?>" alt="Step 2">
                                </div>
                            </div>
                            
                            <!-- Step 3 -->
                            <div class="wp-alp-step-item">
                                <div class="wp-alp-step-content">
                                    <span class="wp-alp-step-number">3</span>
                                    <h2 class="wp-alp-step-title">
                                        <?php echo esc_html(get_locale() == 'en_US' ? 'Finish and publish' : 'Terminar y publicar'); ?>
                                    </h2>
                                    <p class="wp-alp-step-description">
                                        <?php echo esc_html(get_locale() == 'en_US' ? 'Set an initial price, check some details and publish your listing.' : 'Establece un precio inicial, verifica algunos detalles y publica tu anuncio.'); ?>
                                    </p>
                                </div>
                                <div class="wp-alp-step-image">
                                    <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-step3.png'); ?>" alt="Step 3">
                                </div>
                            </div>
                        </div>
                        
                         <!-- Botón de inicio al estilo Airbnb -->
                         <div class="wp-alp-airbnb-start-button-container">
                            <button type="button" class="wp-alp-airbnb-start-button" id="start-registration">
                                Get Started
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paso 1: Simplificado a solo un texto HTML -->
            <div class="wp-alp-form-step" id="step-1" data-step="1" style="display: none;">
                <div class="wp-alp-step-wrapper">
                    <h1>Step 1</h1>
                    <p>Este es el primer paso simplificado.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="wp-alp-progress-bar" id="progress-bar">
    <!-- Barra de progreso visual -->
    <div class="wp-alp-progress-track">
        <div class="wp-alp-progress-section" id="progress-section-1"></div>
        <div class="wp-alp-progress-section" id="progress-section-2"></div>
        <div class="wp-alp-progress-section" id="progress-section-3"></div>
    </div>
    
    <!-- Botones de navegación -->
    <div class="wp-alp-container">
        <div class="wp-alp-progress-inner">
            <div class="wp-alp-progress-buttons">
                <a href="#" class="wp-alp-back-button" id="back-button">Atrás</a>
                <a href="#" class="wp-alp-next-button" id="next-button">Siguiente</a>
            </div>
        </div>
    </div>
</div>
    
    <!-- Formulario oculto para almacenar todos los datos -->
    <form id="vendor-form-data" style="display: none;">
        <?php wp_nonce_field('wp_alp_vendor_form_nonce', 'vendor_form_nonce'); ?>
        <input type="hidden" name="action" value="wp_alp_save_vendor_form">
        <input type="hidden" name="current_step" id="current-step" value="0">
    </form>
</div>

<!-- JavaScript simplificado para la navegación básica -->
<script>
jQuery(document).ready(function($) {
    // Variables para la navegación
    var currentStep = 0;
    var totalSteps = 3; // Total de pasos (ahora son 3)
    
    // Elementos del DOM
    var $steps = $('.wp-alp-form-step');
    var $progressBar = $('#progress-bar');
    var $progressSections = $('.wp-alp-progress-section');
    var $backButton = $('#back-button');
    var $nextButton = $('#next-button');
    var $currentStepInput = $('#current-step');
    
    // Inicialmente configurar para el paso 0
    $progressBar.addClass('step-0');
    
    // Botón de inicio de registro
    $('#start-registration').on('click', function() {
        goToStep(1);
    });
    
    // Función para actualizar la barra de progreso visual
    function updateProgressBar(step) {
        // Resetear clases
        $progressBar.removeClass('step-0 step-1 step-2 step-3');
        
        // Añadir clase para el paso actual
        $progressBar.addClass('step-' + step);
        
        // Actualizar secciones activas
        $progressSections.removeClass('active');
        for (var i = 0; i < step; i++) {
            $($progressSections[i]).addClass('active');
        }
    }
    
    // Función para mostrar un paso específico
    function goToStep(step) {
        // Validar límites
        if (step < 0) step = 0;
        if (step > totalSteps) step = totalSteps;
        
        // Guardar el paso actual
        currentStep = step;
        $currentStepInput.val(step);
        
        // Ocultar todos los pasos
        $steps.hide();
        
        // Mostrar el paso seleccionado
        $('#step-' + step).show();
        
        // Actualizar la barra de progreso visual
        updateProgressBar(step);
        
        // Gestionar la visibilidad de los botones en la barra de progreso
        if (step === 0) {
            // En paso 0, la barra se muestra pero sin botones
            $progressBar.addClass('step-0');
        } else {
            // En otros pasos, mostrar con botones
            $progressBar.removeClass('step-0');
            
            // Actualizar estado del botón Atrás
            if (step === 1) {
                $backButton.text('Volver a la visión general');
            } else {
                $backButton.text('Atrás');
            }
            
            // Actualizar texto del botón Siguiente en el último paso
            if (step === totalSteps) {
                $nextButton.text('Publicar');
            } else {
                $nextButton.text('Siguiente');
            }
        }
        
        // Actualizar la URL
        updateUrl(step);
        
        // Desplazarse al inicio de la página
        $('html, body').scrollTop(0);
    }
    
    // Manejar clics en botones de navegación
    $nextButton.on('click', function(e) {
        e.preventDefault();
        goToStep(currentStep + 1);
    });
    
    $backButton.on('click', function(e) {
        e.preventDefault();
        goToStep(currentStep - 1);
    });
    
    // Manejo de navegación del historial del navegador
    window.onpopstate = function(event) {
        if (event.state && typeof event.state.step !== 'undefined') {
            goToStep(event.state.step);
        } else {
            goToStep(0);
        }
    };
    
    // Inicialización: verificar si hay un paso en la URL
    var urlParams = new URLSearchParams(window.location.search);
    var stepParam = urlParams.get('step');
    
    // Si hay un paso en la URL y es válido, ir a ese paso
    if (stepParam !== null && !isNaN(parseInt(stepParam))) {
        goToStep(parseInt(stepParam));
    } else {
        // Si no hay parámetro de paso, iniciar en el paso 0 (visión general)
        goToStep(0);
    }
});
</script>

<?php get_footer(); ?>