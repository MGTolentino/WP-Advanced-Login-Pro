<?php
/**
 * Template Name: Pasos para Vendedores WP-ALP
 * 
 * Template para la página de pasos del proceso de convertirse en vendedor
 * Implementado como SPA (Single Page Application)
 */

get_header(); ?>

<div class="wp-alp-vendor-form-page">
    <div class="wp-alp-form-header">
        <div class="wp-alp-container">
            <div class="wp-alp-form-header-content">
                <a href="#" class="wp-alp-help-link">
                    <?php echo esc_html(get_locale() == 'en_US' ? 'Have a question?' : '¿Tienes alguna duda?'); ?>
                </a>
                <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-save-exit">
                    <?php echo esc_html(get_locale() == 'en_US' ? 'Save and exit' : 'Guardar y salir'); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Contenedor principal para todos los pasos del formulario -->
    <div class="wp-alp-form-content">
        <div class="wp-alp-container">
            <!-- Paso Inicial: Información sobre los pasos -->
            <div class="wp-alp-form-step" id="step-0" data-step="0">
                <div class="wp-alp-steps-container">
                    <div class="wp-alp-steps-left">
                        <h1 class="wp-alp-steps-heading">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Starting to offer your services is very simple' : 'Empezar a ofrecer tus servicios es muy sencillo'); ?>
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
                        
                        <div class="wp-alp-steps-action">
                            <button type="button" class="wp-alp-steps-button" id="start-registration">
                                <?php echo esc_html(get_locale() == 'en_US' ? 'Get Started' : 'Empieza'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paso 1: Describe tu servicio -->
            <div class="wp-alp-form-step" id="step-1" data-step="1" style="display: none;">
                <div class="wp-alp-form-flex">
                    <div class="wp-alp-form-left">
                        <div class="wp-alp-step-indicator">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Step 1' : 'Paso 1'); ?>
                        </div>
                        
                        <h1 class="wp-alp-form-title">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Describe your service' : 'Describe tu servicio'); ?>
                        </h1>
                        
                        <p class="wp-alp-form-description">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'In this step, we\'ll ask you what type of service you offer and how many people you can accommodate. Next, tell us where it\'s located and how many clients can book.' : 'En este paso, te preguntaremos qué tipo de servicio ofreces y cuántas personas puedes atender. A continuación, indícanos la ubicación y cuántos clientes pueden reservar.'); ?>
                        </p>
                        
                        <div class="wp-alp-form-group">
                            <label for="service-type"><?php echo esc_html(get_locale() == 'en_US' ? 'What type of service do you offer?' : '¿Qué tipo de servicio ofreces?'); ?></label>
                            <select id="service-type" name="service_type" class="wp-alp-select" required>
                                <option value=""><?php echo esc_html(get_locale() == 'en_US' ? 'Select a service type' : 'Selecciona un tipo de servicio'); ?></option>
                                <option value="music"><?php echo esc_html(get_locale() == 'en_US' ? 'Music and Entertainment' : 'Música y Entretenimiento'); ?></option>
                                <option value="venue"><?php echo esc_html(get_locale() == 'en_US' ? 'Venue or Location' : 'Recinto o Ubicación'); ?></option>
                                <option value="catering"><?php echo esc_html(get_locale() == 'en_US' ? 'Catering and Food' : 'Catering y Alimentos'); ?></option>
                                <option value="decoration"><?php echo esc_html(get_locale() == 'en_US' ? 'Decoration' : 'Decoración'); ?></option>
                                <option value="photography"><?php echo esc_html(get_locale() == 'en_US' ? 'Photography and Video' : 'Fotografía y Video'); ?></option>
                                <option value="other"><?php echo esc_html(get_locale() == 'en_US' ? 'Other' : 'Otro'); ?></option>
                            </select>
                        </div>
                        
                        <div class="wp-alp-form-group">
                            <label for="service-name"><?php echo esc_html(get_locale() == 'en_US' ? 'What is the name of your service?' : '¿Cuál es el nombre de tu servicio?'); ?></label>
                            <input type="text" id="service-name" name="service_name" class="wp-alp-input" required>
                        </div>
                        
                        <div class="wp-alp-form-group">
                            <label for="service-capacity"><?php echo esc_html(get_locale() == 'en_US' ? 'How many people can you accommodate?' : '¿Cuántas personas puedes atender?'); ?></label>
                            <input type="number" id="service-capacity" name="service_capacity" class="wp-alp-input" min="1" required>
                        </div>
                        
                        <div class="wp-alp-form-group">
                            <label for="service-location"><?php echo esc_html(get_locale() == 'en_US' ? 'Where is your service located?' : '¿Dónde está ubicado tu servicio?'); ?></label>
                            <input type="text" id="service-location" name="service_location" class="wp-alp-input" placeholder="<?php echo esc_attr(get_locale() == 'en_US' ? 'City, State' : 'Ciudad, Estado'); ?>" required>
                        </div>
                    </div>
                    
                    <div class="wp-alp-form-right">
                        <div class="wp-alp-form-image">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-form-step1.png'); ?>" alt="Describe your service">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Paso 2: Añade fotos y detalles -->
            <div class="wp-alp-form-step" id="step-2" data-step="2" style="display: none;">
                <div class="wp-alp-form-flex">
                    <div class="wp-alp-form-left">
                        <div class="wp-alp-step-indicator">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Step 2' : 'Paso 2'); ?>
                        </div>
                        
                        <h1 class="wp-alp-form-title">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Make it stand out' : 'Haz que destaque'); ?>
                        </h1>
                        
                        <p class="wp-alp-form-description">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Add photos, a title, and a description to help customers find your service.' : 'Añade fotos, un título y una descripción para ayudar a los clientes a encontrar tu servicio.'); ?>
                        </p>
                        
                        <!-- Contenido del paso 2 -->
                        <div class="wp-alp-form-group">
                            <label for="service-title"><?php echo esc_html(get_locale() == 'en_US' ? 'Give your service a title' : 'Dale un título a tu servicio'); ?></label>
                            <input type="text" id="service-title" name="service_title" class="wp-alp-input" required>
                        </div>
                        
                        <div class="wp-alp-form-group">
                            <label for="service-description"><?php echo esc_html(get_locale() == 'en_US' ? 'Describe your service' : 'Describe tu servicio'); ?></label>
                            <textarea id="service-description" name="service_description" class="wp-alp-input" rows="5" required></textarea>
                        </div>
                        
                        <div class="wp-alp-form-group">
                            <label><?php echo esc_html(get_locale() == 'en_US' ? 'Add photos of your service (at least 5 photos)' : 'Añade fotos de tu servicio (al menos 5 fotos)'); ?></label>
                            <div class="wp-alp-photo-upload">
                                <div class="wp-alp-photo-placeholder">
                                    <span class="wp-alp-photo-icon">+</span>
                                </div>
                                <!-- Aquí irá la lógica de carga de fotos -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="wp-alp-form-right">
                        <div class="wp-alp-form-image">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-form-step2.png'); ?>" alt="Add photos and details">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Paso 3: Finaliza y publica -->
            <div class="wp-alp-form-step" id="step-3" data-step="3" style="display: none;">
                <div class="wp-alp-form-flex">
                    <div class="wp-alp-form-left">
                        <div class="wp-alp-step-indicator">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Step 3' : 'Paso 3'); ?>
                        </div>
                        
                        <h1 class="wp-alp-form-title">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Finish and publish' : 'Terminar y publicar'); ?>
                        </h1>
                        
                        <p class="wp-alp-form-description">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Set your prices and publish your service.' : 'Establece tus precios y publica tu servicio.'); ?>
                        </p>
                        
                        <!-- Contenido del paso 3 -->
                        <div class="wp-alp-form-group">
                            <label for="service-price"><?php echo esc_html(get_locale() == 'en_US' ? 'How much do you charge for your service?' : '¿Cuánto cobras por tu servicio?'); ?></label>
                            <div class="wp-alp-price-input">
                                <span class="wp-alp-currency">$</span>
                                <input type="number" id="service-price" name="service_price" class="wp-alp-input" min="1" required>
                            </div>
                        </div>
                        
                        <div class="wp-alp-form-group">
                            <label for="service-availability"><?php echo esc_html(get_locale() == 'en_US' ? 'When is your service available?' : '¿Cuándo está disponible tu servicio?'); ?></label>
                            <select id="service-availability" name="service_availability" class="wp-alp-select" required>
                                <option value="always"><?php echo esc_html(get_locale() == 'en_US' ? 'Always available' : 'Siempre disponible'); ?></option>
                                <option value="weekends"><?php echo esc_html(get_locale() == 'en_US' ? 'Only weekends' : 'Solo fines de semana'); ?></option>
                                <option value="custom"><?php echo esc_html(get_locale() == 'en_US' ? 'Custom schedule' : 'Horario personalizado'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="wp-alp-form-right">
                        <div class="wp-alp-form-image">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-form-step3.png'); ?>" alt="Finish and publish">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Espacio para 5 pasos más -->
            <div class="wp-alp-form-step" id="step-4" data-step="4" style="display: none;">
                <!-- Contenido para paso 4 -->
            </div>
            
            <div class="wp-alp-form-step" id="step-5" data-step="5" style="display: none;">
                <!-- Contenido para paso 5 -->
            </div>
            
            <div class="wp-alp-form-step" id="step-6" data-step="6" style="display: none;">
                <!-- Contenido para paso 6 -->
            </div>
            
            <div class="wp-alp-form-step" id="step-7" data-step="7" style="display: none;">
                <!-- Contenido para paso 7 -->
            </div>
            
            <div class="wp-alp-form-step" id="step-8" data-step="8" style="display: none;">
                <!-- Contenido para paso 8 -->
            </div>
        </div>
    </div>
    
    <!-- Barra de progreso con botones de navegación (inicialmente oculta) -->
    <div class="wp-alp-progress-bar" id="progress-bar" style="display: none;">
        <div class="wp-alp-container">
            <div class="wp-alp-progress-inner">
                <div class="wp-alp-progress-line">
                    <div class="wp-alp-progress-indicator" id="progress-indicator" style="width: 33%;"></div>
                </div>
                <div class="wp-alp-progress-buttons">
                    <a href="#" class="wp-alp-back-button" id="back-button">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
                    </a>
                    <a href="#" class="wp-alp-next-button" id="next-button">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
                    </a>
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

<!-- JavaScript para la navegación SPA -->
<script>
jQuery(document).ready(function($) {
    // Variables para la navegación
    var currentStep = 0;
    var totalSteps = 8; // Total de pasos que tenemos definidos
    var activeSteps = 3; // Pasos actualmente implementados
    
    // Elementos del DOM
    var $steps = $('.wp-alp-form-step');
    var $progressBar = $('#progress-bar');
    var $progressIndicator = $('#progress-indicator');
    var $backButton = $('#back-button');
    var $nextButton = $('#next-button');
    var $currentStepInput = $('#current-step');
    
    // Botón de inicio de registro
    $('#start-registration').on('click', function() {
        goToStep(1);
    });
    
    // Función para actualizar la URL sin recargar la página
    function updateUrl(step) {
        var newUrl = window.location.pathname;
        if (step > 0) {
            newUrl += '?step=' + step;
        }
        history.pushState({step: step}, '', newUrl);
    }
    
    // Función para mostrar un paso específico
    function goToStep(step) {
        // Validar límites
        if (step < 0) step = 0;
        if (step > activeSteps) step = activeSteps;
        
        // Guardar el paso actual
        currentStep = step;
        $currentStepInput.val(step);
        
        // Ocultar todos los pasos
        $steps.hide();
        
        // Mostrar el paso seleccionado
        $('#step-' + step).show();
        
        // Gestionar la barra de progreso
        if (step === 0) {
            // Estamos en la página inicial, ocultar la barra de progreso
            $progressBar.hide();
        } else {
            // Estamos en un paso del formulario, mostrar la barra de progreso
            $progressBar.show();
            
            // Actualizar el ancho del indicador
            var progressWidth = ((step / activeSteps) * 100) + '%';
            $progressIndicator.css('width', progressWidth);
            
            // Actualizar estado del botón Atrás
            if (step === 1) {
                $backButton.text('<?php echo esc_js(get_locale() == 'en_US' ? 'Back to overview' : 'Volver a la visión general'); ?>');
            } else {
                $backButton.text('<?php echo esc_js(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>');
            }
            
            // Actualizar texto del botón Siguiente en el último paso
            if (step === activeSteps) {
                $nextButton.text('<?php echo esc_js(get_locale() == 'en_US' ? 'Publish' : 'Publicar'); ?>');
            } else {
                $nextButton.text('<?php echo esc_js(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>');
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
        
        // Validar campos del paso actual antes de avanzar
        if (validateCurrentStep()) {
            // Guardar datos del paso actual
            saveCurrentStepData();
            
            // Avanzar al siguiente paso
            goToStep(currentStep + 1);
        }
    });
    
    $backButton.on('click', function(e) {
        e.preventDefault();
        goToStep(currentStep - 1);
    });
    
    // Función para validar el paso actual
    function validateCurrentStep() {
        // Implementación básica - verificar campos required
        var valid = true;
        var $currentStepElement = $('#step-' + currentStep);
        
        $currentStepElement.find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('error');
                valid = false;
            } else {
                $(this).removeClass('error');
            }
        });
        
        if (!valid) {
            alert('<?php echo esc_js(get_locale() == 'en_US' ? 'Please fill in all required fields.' : 'Por favor, completa todos los campos obligatorios.'); ?>');
        }
        
        return valid;
    }
    
    // Función para guardar los datos del paso actual
    function saveCurrentStepData() {
        // Aquí puedes implementar lógica para guardar los datos mediante AJAX
        // o almacenarlos temporalmente para enviarlos al final del proceso
        console.log('Guardando datos del paso ' + currentStep);
    }
    
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