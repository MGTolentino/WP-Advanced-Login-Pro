<?php
/**
 * Template Name: Pasos para Vendedores WP-ALP
 * 
 * Template para la página de pasos del proceso de convertirse en vendedor
 * Adaptado al estilo de Airbnb
 */

get_header(); ?>

<div class="wp-alp-vendor-form-page">
    <!-- Contenedor principal para la página inicial y los pasos -->
    <div class="wp-alp-form-content">
        <div class="wp-alp-container">
            <!-- Paso Inicial: Información sobre los pasos -->
            <div class="wp-alp-form-step" id="step-0" data-step="0">
                <!-- Estructura de dos columnas -->
                <div class="wp-alp-two-column-layout">
                    <!-- Columna izquierda: título principal -->
                    <div class="wp-alp-column-left">
                        <h1 class="wp-alp-steps-heading">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Starting to offer your services is very simple' : 'Empezar a ofrecer tus servicios es muy sencillo'); ?>
                        </h1>
                    </div>
                    
                    <!-- Columna derecha: lista de pasos -->
                    <div class="wp-alp-column-right">
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
                        
                        <!-- Barra de progreso -->
                        <div class="wp-alp-full-width-progress">
                            <div class="wp-alp-progress-line"></div>
                        </div>
                        
                        <!-- Botón de acción -->
                        <div class="wp-alp-steps-action">
                            <button type="button" class="wp-alp-steps-button" id="start-registration">
                                <?php echo esc_html(get_locale() == 'en_US' ? 'GET STARTED' : 'EMPEZAR'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paso 1: "Describe tu espacio" - Mantenemos este paso como estaba originalmente -->
            <div class="wp-alp-form-step" id="step-1" data-step="1" style="display: none;">
                <div class="wp-alp-step-wrapper">
                    <!-- Etiqueta del paso -->
                    <div class="wp-alp-step-label">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Step 1' : 'Paso 1'); ?>
                    </div>
                    
                    <!-- Título del paso -->
                    <h1 class="wp-alp-step-heading">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Describe your space' : 'Describe tu espacio'); ?>
                    </h1>
                    
                    <!-- Descripción del paso -->
                    <p class="wp-alp-step-description-large">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'In this step, we\'ll ask you what type of service you offer and how many people you can accommodate.' : 'En este paso, te preguntaremos qué tipo de servicio ofreces y cuántas personas puedes atender.'); ?>
                    </p>
                    
                    <!-- Imagen ilustrativa -->
                    <div class="wp-alp-step-illustration">
                        <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-form-step1.png'); ?>" alt="Describe your service">
                    </div>
                </div>
                
                <!-- Barra de progreso y navegación en la parte inferior -->
                <div class="wp-alp-airbnb-footer">
                    <!-- Barra de progreso con avance -->
                    <div class="wp-alp-airbnb-progress-bar">
                        <div class="wp-alp-airbnb-progress-completed" style="width: 20%;"></div>
                    </div>
                    
                    <!-- Navegación -->
                    <div class="wp-alp-airbnb-nav">
                        <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-overview-btn">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Back to overview' : 'Volver a la visión general'); ?>
                        </a>
                        <a href="#" class="wp-alp-airbnb-next-btn" id="go-to-categories-btn">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Paso 1.1: Vista de categorías de alojamiento (subpaso de Paso 1) -->
            <div class="wp-alp-form-step" id="step-1-categories" data-step="1.1" style="display: none;">
                <!-- Header con opciones de ayuda -->
                <div class="wp-alp-airbnb-help-header">
                    <div class="wp-alp-airbnb-help-links">
                        <a href="#" class="wp-alp-airbnb-help-link">¿Tienes alguna duda?</a>
                        <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-airbnb-save-link">Guardar y salir</a>
                    </div>
                </div>
                
                <!-- Título de la página -->
                <div class="wp-alp-airbnb-category-content">
                    <h1 class="wp-alp-airbnb-category-title">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Which of these best describes your place?' : '¿Cuál de estas opciones describe mejor tu alojamiento?'); ?>
                    </h1>
                    
                    <!-- Grid de categorías -->
                    <div class="wp-alp-airbnb-category-grid">
                        <?php
                        // Obtener categorías de HivePress si está disponible
                        $categories = array();
                        if (taxonomy_exists('hp_listing_category')) {
                            $categories = get_terms(array(
                                'taxonomy' => 'hp_listing_category',
                                'hide_empty' => false,
                            ));
                        }
                        
                        // Si no hay categorías o HivePress no está activo, usar opciones de ejemplo
                        if (empty($categories)) {
                            $sample_options = array(
                                array('name' => 'Casa', 'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;"><path d="M17.954 2.781l.175.164 13.072 12.842-1.402 1.426-1.8-1.768L28 29a2 2 0 0 1-1.85 1.994L26 31H6a2 2 0 0 1-1.995-1.85L4 29V15.446l-1.8 1.767-1.4-1.426L13.856 2.958a3 3 0 0 1 4.098-.177zM16 17a5 5 0 0 0-5 5v7h14v-7a5 5 0 0 0-4.783-4.995L20 17h-4zM6 15.446l.001 13.554h4v-7a7 7 0 0 1 6.76-6.993L17 15h4a7 7 0 0 1 6.991 6.76L28 22v7h3.999L32 15.445 16 0 0 15.446z"></path></svg>'),
                                array('name' => 'Departamento', 'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;"><path d="M14 1v30h4V1h-4zm7 0v30h4V1h-4zM7 1v30h4V1H7zM0 1v30h4V1H0zM28 1a3 3 0 0 1 3 3v24a3 3 0 0 1-3 3h-1V1h1z"></path></svg>'),
                                array('name' => 'Granero', 'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;"><path d="M16 0c5.9 0 11.525 4.347 15.942 11.783l.058.217L16 19.5 0 12l.058-.217A22.03 22.03 0 0 1 4.74 4.822l.26-.303A16.198 16.198 0 0 1 10.042.752L10.458.53A10.516 10.516 0 0 1 14.5.035L15.067.007 15.125.003 16 0zm0 2c-2.825 0-5.8 1.552-8.56 4.394l-.28.324A15.929 15.929 0 0 0 4.2 10.473l-.258.518L16 17l12.057-6.01-.258-.517A15.928 15.928 0 0 0 24.84 6.718l-.28-.325C21.8 3.552 18.825 2 16 2zm4.5 8.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3zM29 13v17h-6v-6h-3v6H6v-6H3v6H0V13h3v10.56l3-1.5V13h3v10.56l3-1.5V13h6v10.56l3 1.5V13h3v10.56l3 1.5V13h2zm-7 2a3 3 0 0 0-3 3v4a3 3 0 0 0 3 3h4a3 3 0 0 0 3-3v-4a3 3 0 0 0-3-3h-4zm0 2h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1z"></path></svg>'),
                                array('name' => 'Bed and breakfast', 'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;"><path d="M24 1a5 5 0 0 1 4.995 4.783L29 6v20a5 5 0 0 1-4.783 4.995L24 31H8a5 5 0 0 1-4.995-4.783L3 26V6a5 5 0 0 1 4.783-4.995L8 1h16zm0 2H8a3 3 0 0 0-2.995 2.824L5 6v20a3 3 0 0 0 2.824 2.995L8 29h16a3 3 0 0 0 2.995-2.824L27 26V6a3 3 0 0 0-2.824-2.995L24 3zm-8 10a3 3 0 0 1 2.995 2.824L19 16v1h2v2h-2v6h-2v-6h-4v6H11v-6H9v-2h2v-1a3 3 0 0 1 2.824-2.995L14 13h2zm-1 2h-2a1 1 0 0 0-.993.883L12 16v1h4v-1a1 1 0 0 0-.883-.993L15 15h-1zM24 7a2 2 0 0 1 1.995 1.85L26 9v6a2 2 0 0 1-1.85 1.995L24 17h-6a2 2 0 0 1-1.995-1.85L16 15V9a2 2 0 0 1 1.85-1.995L18 7h6zm-6 2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1h-5z"></path></svg>'),
                                array('name' => 'Barco', 'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;"><path d="M32 6v2h-2V6H2v2H0V6h4V4c0-1.1.9-2 2-2h20a2 2 0 0 1 2 2v2h4zM30 13v18h-2v-2h-4a5 5 0 0 1-10 0h-4a5 5 0 0 1-10 0v2H0V13h30zm-15 6a3 3 0 0 0-3 3v6.08a3 3 0 0 1-2.34 2.92l-.16.01a3 3 0 0 0 5.5-1.01v-1h4v1a3 3 0 0 0 6 0v-6a3 3 0 0 0-3-3h-7zm2-11h-4V6h4v2zm10 0h-4V6h4v2z"></path></svg>'),
                                array('name' => 'Cabaña', 'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;"><path d="M26 4a3 3 0 0 1 3 2.824V26a4.002 4.002 0 0 1-3.172 3.913l-.203.035-.244.034-.28.017-.286.008H7a4.002 4.002 0 0 1-3.965-3.456L3 26.177V26V7a3 3 0 0 1 2.824-3H26zm1 14H5v8a2 2 0 0 0 1.85 1.995L7 28h18a2 2 0 0 0 1.995-1.85L27 26v-8zm-10 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zM26 6H6a1 1 0 0 0-1 .88L5 7v9h22V7a1 1 0 0 0-.88-1H26z"></path></svg>'),
                                array('name' => 'Casa rodante', 'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;"><path d="M7 28v-3a1 1 0 0 1 .883-.993L8 24h16a1 1 0 0 1 .993.883L25 25v3h2a1 1 0 0 1 .993.883L28 29v1h-2a1 1 0 0 1-.993-.883L25 29h-2v1h-4v-1h-6v1H9v-1H7v1a1 1 0 0 1-.883.993L6 31H4v-1a1 1 0 0 1 .883-.993L5 29h2zm2-3v2h14v-2H9zm12-2v-8h-8v8h8zm0-10a2 2 0 0 1 1.995 1.85L23 15v8a2 2 0 0 1-1.85 1.995L21 25h-8a2 2 0 0 1-1.995-1.85L11 23v-8a2 2 0 0 1 1.85-1.995L13 13h8zm8.5-3a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 7a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zM3 13.5a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0zm7 0a2.5 2.5 0 1 0-5 0 2.5 2.5 0 0 0 5 0zm-1-10a2 2 0 0 1 1.995 1.85L11 5.5V10H5V5.5a2 2 0 0 1 1.85-1.995L7 3.5h2zm-2 2v3h4v-3H7z"></path></svg>'),
                                array('name' => 'Casa particular', 'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;"><path d="M12 0V3H8v3h4v3H0v23h32V9h-4V6h4V3h-8V0zm16 27h-6v-4h-4v4H4V11h24zm-6-17v5h4v-5zm-10 0v5h4v-5zm-6 0v5h4v-5z"></path></svg>'),
                                array('name' => 'Castillo', 'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;"><path d="M26 4a2 2 0 0 1 1.995 1.85L28 6v22a2 2 0 0 1-1.85 1.995L26 30H24v-2h2V6H16v2h-2V6H6v22h2v2H6a2 2 0 0 1-1.995-1.85L4 28V6a2 2 0 0 1 1.85-1.995L6 4h20zM12 2a2 2 0 0 1 2 2 2 2 0 1 1-4 0 2 2 0 0 1 2-2zm10 0a2 2 0 0 1 2 2 2 2 0 1 1-4 0 2 2 0 0 1 2-2zM9 14a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 0 1 1 1 1 1 0 1 1-2 0 1 1 0 0 1 1-1zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zM9 18a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zM9 22a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path></svg>'),
                            );
                        } else {
                            $sample_options = array();
                            foreach ($categories as $category) {
                                $icon = get_term_meta($category->term_id, 'icono_categoria', true);
                                if (empty($icon)) {
                                    $icon = '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;"><path d="M28 2a2 2 0 0 1 2 2v24a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h24zm0 2H4v15.499l3.5-3.5 3.5 3.5 8.5-8.5 8.5 8.5V4zm0 24v-2.961l-10-10-8.5 8.5-3.5-3.5-2 2V28h24zM18 10a2 2 0 1 1 0 4 2 2 0 0 1 0-4z"></path></svg>';
                                }
                                $sample_options[] = array(
                                    'name' => $category->name,
                                    'icon' => $icon,
                                    'term_id' => $category->term_id
                                );
                            }
                        }
                        
                        // Mostrar las opciones
                        foreach ($sample_options as $index => $option) {
                            $selected = ($index === 8) ? 'selected' : ''; // Supongamos que Castillo (índice 8) está seleccionado
                            ?>
                            <div class="wp-alp-airbnb-category-item <?php echo esc_attr($selected); ?>" data-term-id="<?php echo isset($option['term_id']) ? esc_attr($option['term_id']) : ''; ?>">
                                <div class="wp-alp-airbnb-category-icon">
                                    <?php echo $option['icon']; ?>
                                </div>
                                <div class="wp-alp-airbnb-category-name">
                                    <?php echo esc_html($option['name']); ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Barra de navegación fija -->
                <div class="wp-alp-airbnb-footer">
                    <!-- Barra de progreso con avance -->
                    <div class="wp-alp-airbnb-progress-bar">
                        <div class="wp-alp-airbnb-progress-completed" style="width: 20%;"></div>
                    </div>
                    
                    <!-- Botones de navegación -->
                    <div class="wp-alp-airbnb-nav">
                        <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-step1-btn">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
                        </a>
                        <a href="#" class="wp-alp-airbnb-next-btn" id="next-from-categories-btn">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Aquí podrían ir más pasos... -->
        </div>
    </div>
</div>

<!-- JavaScript para la navegación -->
<script>
jQuery(document).ready(function($) {
    // Variables para la navegación
    var currentStep = 0;
    var totalSteps = 3; // Total de pasos implementados
    
    // Elementos del DOM
    var $steps = $('.wp-alp-form-step');
    
    // Botón de inicio de registro (página principal -> paso 1)
    $('#start-registration').on('click', function() {
        goToStep(1); // Va a "Describe tu espacio"
    });
    
   // Botón de siguiente en paso 1 (lleva a categorías)
   $('#go-to-categories-btn').on('click', function(e) {
        e.preventDefault();
        $('#step-1').hide();
        $('#step-1-categories').show();
        
        // Actualizar URL sin cambiar el número de paso principal
        var currentUrl = window.location.pathname;
        var newUrl = currentUrl + '?step=1&substep=categories';
        history.pushState({step: 1, substep: 'categories'}, '', newUrl);
        
        // Desplazarse al inicio de la página
        $('html, body').scrollTop(0);
    });
    
    // Botón de volver desde categorías a paso 1
    $('#back-to-step1-btn').on('click', function(e) {
        e.preventDefault();
        $('#step-1-categories').hide();
        $('#step-1').show();
        
        // Actualizar URL
        var currentUrl = window.location.pathname;
        var newUrl = currentUrl + '?step=1';
        history.pushState({step: 1}, '', newUrl);
        
        // Desplazarse al inicio de la página
        $('html, body').scrollTop(0);
    });
    
    // Botón de volver desde paso 1 a visión general
    $('#back-to-overview-btn').on('click', function(e) {
        e.preventDefault();
        goToStep(0); // Volver a la visión general
    });
    
    // Botón de siguiente desde categorías
    $('#next-from-categories-btn').on('click', function(e) {
        e.preventDefault();
        // Verificar si hay una categoría seleccionada
        if ($('.wp-alp-airbnb-category-item.selected').length > 0) {
            var selectedTermId = $('.wp-alp-airbnb-category-item.selected').data('term-id');
            console.log('Categoría seleccionada: ' + selectedTermId);
            
            // Aquí iríamos al siguiente paso (paso 2)
            // Por ahora solo mostramos un mensaje
            alert('Categoría seleccionada: ' + selectedTermId + '\nIríamos al paso 2');
        } else {
            alert('Por favor, selecciona una opción antes de continuar.');
        }
    });
    
    // Selección de categorías
    $('.wp-alp-airbnb-category-item').on('click', function() {
        $('.wp-alp-airbnb-category-item').removeClass('selected');
        $(this).addClass('selected');
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
        if (step > totalSteps) step = totalSteps;
        
        // Guardar el paso actual
        currentStep = step;
        
        // Ocultar todos los pasos
        $steps.hide();
        
        // Mostrar el paso seleccionado
        $('#step-' + step).show();
        
        // Actualizar la URL
        updateUrl(step);
        
        // Desplazarse al inicio de la página
        $('html, body').scrollTop(0);
    }
    
    // Manejo de navegación del historial del navegador
    window.onpopstate = function(event) {
        if (event.state) {
            if (event.state.substep === 'categories') {
                // Mostrar el subpaso de categorías
                $steps.hide();
                $('#step-1-categories').show();
            } else if (typeof event.state.step !== 'undefined') {
                goToStep(event.state.step);
            }
        } else {
            goToStep(0);
        }
    };
    
    // Inicialización: verificar si hay un paso en la URL
    var urlParams = new URLSearchParams(window.location.search);
    var stepParam = urlParams.get('step');
    var substepParam = urlParams.get('substep');
    
    // Si hay un paso en la URL y es válido, ir a ese paso
    if (stepParam !== null && !isNaN(parseInt(stepParam))) {
        var stepNum = parseInt(stepParam);
        
        // Verificar si hay un subpaso especificado
        if (substepParam === 'categories' && stepNum === 1) {
            // Mostrar el subpaso de categorías
            $steps.hide();
            $('#step-1-categories').show();
        } else {
            goToStep(stepNum);
        }
    } else {
        // Si no hay parámetro de paso, iniciar en el paso 0 (visión general)
        goToStep(0);
    }
});
</script>

<?php get_footer(); ?> // Botón de siguiente en paso 1 (lleva a categorías)
    $('#go-to-categories-btn').on