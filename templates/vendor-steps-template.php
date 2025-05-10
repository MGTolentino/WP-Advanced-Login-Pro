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
                                    <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/vendor-step1.png'); ?>" alt="Step 1">
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
                                    <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/vendor-step2.png'); ?>" alt="Step 2">
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
                                    <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/vendor-step3.png'); ?>" alt="Step 3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Barra de progreso a ancho completo DESPUÉS de two-column-layout -->
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

            <!-- Paso 1: "Describe tu espacio" con dos columnas -->
            <div class="wp-alp-form-step" id="step-1" data-step="1" style="display: none;">
                <div class="wp-alp-dual-column-container">
                    <!-- Columna izquierda con texto -->
                    <div class="wp-alp-dual-column-left">
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
                    </div>
                    
                    <!-- Columna derecha con imagen -->
                    <div class="wp-alp-dual-column-right">
                        <div class="wp-alp-step-illustration">
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/vendor-form-step1.png'); ?>" alt="Describe your service">
                        </div>
                    </div>
                </div>
                
                <!-- Barra de progreso y navegación en la parte inferior -->
                <div class="wp-alp-airbnb-footer">
                    <!-- Barra de progreso sin avance (toda gris) -->
                    <div class="wp-alp-airbnb-progress-bar">
                        <!-- No progreso, solo barra gris -->
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
                        if (!empty($categories)) {
                            $sample_options = array();
                            foreach ($categories as $category) {
                                $icon_id = get_term_meta($category->term_id, 'icono_categoria', true);
                                
                                // Verificar si es un ID de imagen y obtener la URL o HTML de la imagen
                                if (!empty($icon_id) && is_numeric($icon_id)) {
                                    // Opción 1: Obtener la URL de la imagen y usarla en un tag img
                                    $icon_url = wp_get_attachment_url($icon_id);
                                    if ($icon_url) {
                                        $icon = '<img src="' . esc_url($icon_url) . '" alt="' . esc_attr($category->name) . '" style="height: 24px; width: 24px;">';
                                    } else {
                                        // Si no se encuentra la imagen, usar ícono predeterminado
                                        $icon = '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;"><path d="M28 2a2 2 0 0 1 2 2v24a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h24zm0 2H4v15.499l3.5-3.5 3.5 3.5 8.5-8.5 8.5 8.5V4zm0 24v-2.961l-10-10-8.5 8.5-3.5-3.5-2 2V28h24zM18 10a2 2 0 1 1 0 4 2 2 0 0 1 0-4z"></path></svg>';
                                    }
                                } else if (!empty($icon_id) && is_string($icon_id) && strpos($icon_id, '<svg') !== false) {
                                    // Si ya es un SVG, usarlo directamente
                                    $icon = $icon_id;
                                } else {
                                    // Ícono predeterminado
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
                            <div class="wp-alp-airbnb-category-item <?php echo esc_attr($selected); ?>" data-term-id="<?php echo isset($option['term_id']) ? esc_attr($option['term_id']) : ''; ?>" data-name="<?php echo esc_attr($option['name']); ?>">
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

            <!-- Paso 1.2: Tipo de servicio (subpaso de Paso 1) -->
            <div class="wp-alp-form-step" id="step-1-service-type" data-step="1.2" style="display: none;">
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
                        <?php echo esc_html(get_locale() == 'en_US' ? 'What type of service do you offer to guests?' : '¿Qué tipo de servicio ofreces a los clientes?'); ?>
                    </h1>
                    
                    <!-- Grid de tipos de servicio -->
                    <div class="wp-alp-airbnb-service-grid">
                        <!-- Opción 1: Servicio por Día -->
                        <div class="wp-alp-airbnb-service-option" data-value="day">
                            <div class="wp-alp-airbnb-service-info">
                                <h2 class="wp-alp-airbnb-service-title">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Service per Day' : 'Servicio por Día'); ?>
                                </h2>
                                <p class="wp-alp-airbnb-service-description">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Clients hire your service for one or more full days.' : 'Los clientes contratan tu servicio por uno o más días completos.'); ?>
                                </p>
                            </div>
                            <div class="wp-alp-airbnb-service-icon">
                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;">
                                    <path d="M12 0v2h8V0h2v2h6a2 2 0 0 1 1.995 1.85L30 4v24a2 2 0 0 1-1.85 1.995L28 30H4a2 2 0 0 1-1.995-1.85L2 28V4a2 2 0 0 1 1.85-1.995L4 2h6V0h2zm16 10H4v18h24V10zm-8 2v2h2v-2h-2zm-6 0v2h2v-2h-2zm-6 0v2h2v-2H8zm12 6v2h2v-2h-2zm-6 0v2h2v-2h-2zm-6 0v2h2v-2H8zm12 6v2h2v-2h-2zm-6 0v2h2v-2h-2zm-6 0v2h2v-2H8z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Opción 2: Servicio por Hora -->
                        <div class="wp-alp-airbnb-service-option" data-value="hour">
                            <div class="wp-alp-airbnb-service-info">
                                <h2 class="wp-alp-airbnb-service-title">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Service per Hour' : 'Servicio por Hora'); ?>
                                </h2>
                                <p class="wp-alp-airbnb-service-description">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Clients hire your service by the hour, ideal for shorter events.' : 'Los clientes contratan tu servicio por hora, ideal para eventos más cortos.'); ?>
                                </p>
                            </div>
                            <div class="wp-alp-airbnb-service-icon">
                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;">
                                    <path d="M16 0a16 16 0 1 1 0 32 16 16 0 0 1 0-32zm0 2C8.268 2 2 8.268 2 16s6.268 14 14 14 14-6.268 14-14S23.732 2 16 2zm0 6a2 2 0 0 1 1.985 1.75L18 10l-.001 5.584.25.25a2 2 0 0 1 .565 1.32l-.009.131-.016.116-.012.09a2 2 0 0 1-.244.578l-.067.109-.08.115-.09.1-.067.074-.115.109-.12.1-.094.07-.124.08-.098.054-.136.065-.11.044-.133.044-.11.03-.134.03-.136.022-.13.013-.134.008L17 19l-.127-.007-.134-.008-.131-.013-.135-.022-.134-.029-.138-.044-.113-.043-.134-.065-.1-.054-.122-.08-.095-.07-.12-.1-.115-.109-.066-.074-.09-.1-.082-.115-.068-.11a2 2 0 0 1-.242-.577l-.039-.2-.008-.122L15 17.165 15 10a2 2 0 0 1 1-1.732V8a1 1 0 0 0-1.993.117L14 8.225v.613a3.984 3.984 0 0 0-2.997 3.745L11 12.771V14H9v-1.23a6.002 6.002 0 0 1 4.088-5.69l.237-.078A3.001 3.001 0 0 1 16 8zm-4 14v2H8v-2h4zm8 0v2h-4v-2h4zm4 0v2h-1v-2h1z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Opción 3: Servicio Completo para Evento -->
                        <div class="wp-alp-airbnb-service-option" data-value="complete">
                            <div class="wp-alp-airbnb-service-info">
                                <h2 class="wp-alp-airbnb-service-title">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Complete Event Service' : 'Servicio Completo para Evento'); ?>
                                </h2>
                                <p class="wp-alp-airbnb-service-description">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Clients receive all necessary elements for their event, including setup and breakdown.' : 'Los clientes reciben todos los elementos necesarios para su evento, incluyendo montaje y desmontaje.'); ?>
                                </p>
                            </div>
                            <div class="wp-alp-airbnb-service-icon">
                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: currentcolor;">
                                    <path d="M22.999 18V13.5A12 12 0 0 0 15 2.292a15 15 0 0 0-4.000 1.333A12 12 0 0 0 2.998 1.811L2.999 2v15.5C2.999 25.5 11 29 11 29s8-3.5 8-11.5V13l5 4.997L25.503 15l-2.504-2 2.504-2-1.504-2-5 4zM11 26c-4.693-1.97-6-6.678-6-8.5v-12c0-.745 3-2 6-2s6 1.255 6 2v12c0 1.822-1.307 6.53-6 8.5zm.691-6.051a.929.929 0 0 0 1.312 0l6.969-6.97a.93.93 0 0 0-1.312-1.313L12 18.327l-2.659-2.66a.93.93 0 1 0-1.312 1.313l3.315 3.317a.91.91 0 0 0 .657.272.9.9 0 0 0 .657-.272z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mensaje de validación (inicialmente oculto) -->
                    <div class="wp-alp-airbnb-service-validation" style="display: none;">
                        <p>Por favor, selecciona un tipo de servicio para continuar.</p>
                    </div>
                </div>
                
                <!-- Barra de navegación fija -->
                <div class="wp-alp-airbnb-footer">
                    <!-- Barra de progreso con avance -->
                    <div class="wp-alp-airbnb-progress-bar">
                        <div class="wp-alp-airbnb-progress-completed" style="width: 40%;"></div>
                    </div>
                    
                    <!-- Botones de navegación -->
                    <div class="wp-alp-airbnb-nav">
                        <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-categories-btn">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
                        </a>
                        <a href="#" class="wp-alp-airbnb-next-btn" id="next-from-service-type-btn">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Paso 1.3: Ubicación del servicio (subpaso de Paso 1) -->
            <div class="wp-alp-form-step" id="step-1-location" data-step="1.3" style="display: none;">
                <!-- Header con opciones de ayuda -->
                <div class="wp-alp-airbnb-help-header">
                    <div class="wp-alp-airbnb-help-links">
                        <a href="#" class="wp-alp-airbnb-help-link">¿Tienes alguna duda?</a>
                        <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-airbnb-save-link">Guardar y salir</a>
                    </div>
                </div>
                
                <!-- Título y subtítulo de la página -->
                <div class="wp-alp-airbnb-category-content">
                    <h1 class="wp-alp-airbnb-category-title">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Where do you provide your service?' : '¿Dónde das el servicio?'); ?>
                    </h1>
                    <p class="wp-alp-airbnb-category-subtitle">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'This location will be shown in your listing so customers can find you easily.' : 'Esta ubicación se mostrará en tu anuncio para que los clientes puedan encontrarte fácilmente.'); ?>
                    </p>
                    
                    <!-- Opciones de ubicación -->
                    <div class="wp-alp-location-options">
                        <!-- Opción 1: Ubicación específica -->
                        <div class="wp-alp-location-option" data-option="specific">
                            <div class="wp-alp-location-option-header">
                                <h3 class="wp-alp-location-option-title">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Specific location' : 'Ubicación específica'); ?>
                                </h3>
                                <p class="wp-alp-location-option-subtitle">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Select the exact location where you offer your service.' : 'Selecciona la ubicación exacta donde ofreces tu servicio.'); ?>
                                </p>
                            </div>
                            <div class="wp-alp-location-option-radio">
                                <input type="radio" name="location-type" id="location-specific" value="specific">
                            </div>
                        </div>
                        
                        <!-- Opción 2: Múltiples ubicaciones -->
                        <div class="wp-alp-location-option" data-option="multiple">
                            <div class="wp-alp-location-option-header">
                                <h3 class="wp-alp-location-option-title">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Multiple locations or service areas' : 'Múltiples ubicaciones o áreas de servicio'); ?>
                                </h3>
                                <p class="wp-alp-location-option-subtitle">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Select all areas where you offer your services.' : 'Selecciona todas las áreas donde ofreces tus servicios.'); ?>
                                </p>
                            </div>
                            <div class="wp-alp-location-option-radio">
                                <input type="radio" name="location-type" id="location-multiple" value="multiple">
                            </div>
                        </div>
                    </div>
                    
                    <div class="wp-alp-location-specific-container" style="display: none;">
                        <!-- Toggle para mostrar ubicación exacta -->
                        <div class="wp-alp-location-toggle">
                            <div class="wp-alp-toggle-text">
                                <span><?php echo esc_html(get_locale() == 'en_US' ? 'Show your exact location' : 'Mostrar tu ubicación exacta'); ?></span>
                                <p class="wp-alp-toggle-description">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Clearly indicate to guests where your place is located. We will only provide your address when the reservation is confirmed.' : 'Indica claramente a los huéspedes dónde se encuentra tu alojamiento. Solo les facilitaremos tu dirección cuando su reservación esté confirmada.'); ?>
                                    <a href="#" class="wp-alp-more-info"><?php echo esc_html(get_locale() == 'en_US' ? 'More information' : 'Más información'); ?></a>
                                </p>
                            </div>
                            <div class="wp-alp-toggle-switch">
                                <label class="wp-alp-switch">
                                    <input type="checkbox" id="exact-location-toggle">
                                    <span class="wp-alp-slider round"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Contenedor del mapa -->
                        <div class="wp-alp-map-container">
                            <div id="wp-alp-location-map" class="wp-alp-map-wrapper"></div>
                            
                            <!-- Tooltip de ubicación aproximada (inicialmente visible) -->
                            <div class="wp-alp-approximate-tooltip" id="approximate-tooltip">
                                <p><?php echo esc_html(get_locale() == 'en_US' ? 'We will share your approximate location.' : 'Compartiremos tu ubicación aproximada.'); ?></p>
                            </div>
                            
                            <!-- Marcador de casa (se moverá con el mapa) -->
                            <div class="wp-alp-house-marker" id="house-marker" style="display: none;">
                                <div class="wp-alp-marker-icon">
                                    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 24px; width: 24px; fill: white;">
                                        <path d="M17.954 2.781l.175.164 13.072 12.842-1.402 1.426-1.8-1.768L28 29a2 2 0 0 1-1.85 1.994L26 31H6a2 2 0 0 1-1.995-1.85L4 29V15.446l-1.8 1.767-1.4-1.426L13.856 2.958a3 3 0 0 1 4.098-.177zM16 17a5 5 0 0 0-5 5v7h14v-7a5 5 0 0 0-4.783-4.995L20 17h-4z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Barra de búsqueda de dirección -->
                        <div class="wp-alp-map-search">
                            <div class="wp-alp-search-icon">
                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="height: 18px; width: 18px; fill: currentcolor;">
                                    <path d="M13 0c7.18 0 13 5.82 13 13 0 2.868-.929 5.519-2.502 7.669l7.916 7.917-2.122 2.121-7.916-7.916A12.942 12.942 0 0 1 13 26C5.82 26 0 20.18 0 13S5.82 0 13 0zm0 2a11 11 0 1 0 0 22 11 11 0 0 0 0-22z"></path>
                                </svg>
                            </div>
                            <input type="text" id="wp-alp-address-input" placeholder="<?php echo esc_attr(get_locale() == 'en_US' ? 'Enter your address' : 'Ingresa tu dirección'); ?>" class="wp-alp-address-input">
                        </div>
                        
                        <!-- Botón para confirmación de dirección detallada (inicialmente oculto) -->
                        <div class="wp-alp-confirm-address-btn" style="display: none;">
                            <button type="button" id="confirm-address-btn" class="wp-alp-btn wp-alp-btn-secondary">
                                <?php echo esc_html(get_locale() == 'en_US' ? 'Confirm address' : 'Confirmar dirección'); ?>
                            </button>
                        </div>
                    </div>

                    <!-- Formulario detallado de dirección (inicialmente oculto) -->
                    <div class="wp-alp-address-form-container" id="address-form-container" style="display: none;">
                        <h2 class="wp-alp-address-form-title">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Confirm your address' : 'Confirma tu dirección'); ?>
                        </h2>
                        <p class="wp-alp-address-form-subtitle">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'We will only share the address with guests after they have made the reservation.' : 'Solo compartiremos la dirección con los huéspedes después de que hayan hecho la reservación.'); ?>
                        </p>
                        
                        <form id="wp-alp-detailed-address" class="wp-alp-address-form">
                            <!-- País -->
                            <div class="wp-alp-form-group">
                                <label for="country" class="wp-alp-form-label">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Country/Region' : 'País o región'); ?>
                                </label>
                                <select id="country" name="country" class="wp-alp-form-select">
                                    <option value="MX" selected>México - MX</option>
                                    <option value="US">Estados Unidos - US</option>
                                    <option value="CA">Canadá - CA</option>
                                    <!-- Agrega más países según sea necesario -->
                                </select>
                            </div>
                            
                            <!-- Dirección principal -->
                            <div class="wp-alp-form-group">
                                <label for="street" class="wp-alp-form-label">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Address' : 'Dirección'); ?>
                                </label>
                                <input type="text" id="street" name="street" class="wp-alp-form-input">
                            </div>
                            
                            <!-- Apartamento, habitación, etc. -->
                            <div class="wp-alp-form-group">
                                <label for="apt" class="wp-alp-form-label">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Apt, suite, etc. (if applicable)' : 'Departamento, habitación, etc. (si corresponde)'); ?>
                                </label>
                                <input type="text" id="apt" name="apt" class="wp-alp-form-input">
                            </div>
                            
                            <!-- Zona o barrio -->
                            <div class="wp-alp-form-group">
                                <label for="neighborhood" class="wp-alp-form-label">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Area (if applicable)' : 'Zona (si corresponde)'); ?>
                                </label>
                                <input type="text" id="neighborhood" name="neighborhood" class="wp-alp-form-input">
                            </div>
                            
                            <!-- Código postal -->
                            <div class="wp-alp-form-group">
                                <label for="zipcode" class="wp-alp-form-label">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Postal code' : 'Código postal'); ?>
                                </label>
                                <input type="text" id="zipcode" name="zipcode" class="wp-alp-form-input">
                            </div>
                            
                            <!-- Ciudad -->
                            <div class="wp-alp-form-group">
                                <label for="city" class="wp-alp-form-label">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'City/town' : 'Ciudad / municipio'); ?>
                                </label>
                                <input type="text" id="city" name="city" class="wp-alp-form-input">
                            </div>
                            
                            <!-- Estado -->
                            <div class="wp-alp-form-group">
                                <label for="state" class="wp-alp-form-label">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'State' : 'Estado'); ?>
                                </label>
                                <select id="state" name="state" class="wp-alp-form-select">
                                    <option value="NL" selected>Nuevo León</option>
                                    <option value="CDMX">Ciudad de México</option>
                                    <option value="JAL">Jalisco</option>
                                    <!-- Agrega más estados según sea necesario -->
                                </select>
                            </div>
                            
                            <!-- Botones de navegación -->
                            <div class="wp-alp-address-form-buttons">
                                <button type="button" id="back-to-map-btn" class="wp-alp-btn wp-alp-btn-text">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
                                </button>
                                <button type="button" id="save-address-btn" class="wp-alp-btn wp-alp-btn-primary">
                                    <?php echo esc_html(get_locale() == 'en_US' ? 'Confirm' : 'Confirmar'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Contenedor para múltiples ubicaciones - inicialmente oculto -->
                    <div class="wp-alp-location-multiple-container" style="display: none;">
                        <div class="wp-alp-locations-list">
                            <?php
                            // Intentar obtener las ubicaciones de hp_listing_location o hp_listing_ubicacion
                            $taxonomy = taxonomy_exists('hp_listing_location') ? 'hp_listing_location' : 
                                      (taxonomy_exists('hp_listing_ubicacion') ? 'hp_listing_ubicacion' : '');
                            
                            if (!empty($taxonomy)) {
                                $locations = get_terms(array(
                                    'taxonomy' => $taxonomy,
                                    'hide_empty' => false,
                                ));
                                
                                if (!empty($locations) && !is_wp_error($locations)) {
                                    echo '<div class="wp-alp-locations-checkboxes">';
                                    foreach ($locations as $location) {
                                        ?>
                                        <div class="wp-alp-location-checkbox-item">
                                            <input type="checkbox" id="location-<?php echo esc_attr($location->term_id); ?>" 
                                                  name="locations[]" value="<?php echo esc_attr($location->term_id); ?>">
                                            <label for="location-<?php echo esc_attr($location->term_id); ?>">
                                                <?php echo esc_html($location->name); ?>
                                            </label>
                                        </div>
                                        <?php
                                    }
                                    echo '</div>';
                                }
                            } else {
                                // Ubicaciones de ejemplo si no hay taxonomías disponibles
                                ?>
                                <div class="wp-alp-locations-checkboxes">
                                    <div class="wp-alp-location-checkbox-item">
                                        <input type="checkbox" id="location-1" name="locations[]" value="1">
                                        <label for="location-1">Ciudad de México</label>
                                    </div>
                                    <div class="wp-alp-location-checkbox-item">
                                        <input type="checkbox" id="location-2" name="locations[]" value="2">
                                        <label for="location-2">Guadalajara</label>
                                    </div>
                                    <div class="wp-alp-location-checkbox-item">
                                        <input type="checkbox" id="location-3" name="locations[]" value="3">
                                        <label for="location-3">Monterrey</label>
                                    </div>
                                    <div class="wp-alp-location-checkbox-item">
                                        <input type="checkbox" id="location-4" name="locations[]" value="4">
                                        <label for="location-4">Cancún</label>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <!-- Opción "Otro" con campo de texto -->
                            <div class="wp-alp-location-other">
                                <div class="wp-alp-location-checkbox-item">
                                    <input type="checkbox" id="location-other" name="locations[]" value="other">
                                    <label for="location-other">
                                        <?php echo esc_html(get_locale() == 'en_US' ? 'Other location' : 'Otra ubicación'); ?>
                                    </label>
                                </div>
                                <div class="wp-alp-location-other-input" style="display: none;">
                                    <input type="text" id="wp-alp-other-location" placeholder="<?php echo esc_attr(get_locale() == 'en_US' ? 'Specify the location' : 'Especifica la ubicación'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mensaje de validación (inicialmente oculto) -->
                    <div class="wp-alp-location-validation" style="display: none;">
                        <p><?php echo esc_html(get_locale() == 'en_US' ? 'Please select a location to continue.' : 'Por favor, selecciona una ubicación para continuar.'); ?></p>
                    </div>
                </div>
                
                <!-- Barra de navegación fija -->
                <div class="wp-alp-airbnb-footer">
                    <!-- Barra de progreso con avance -->
                    <div class="wp-alp-airbnb-progress-bar">
                        <div class="wp-alp-airbnb-progress-completed" style="width: 60%;"></div>
                    </div>
                    
                    <!-- Botones de navegación -->
                    <div class="wp-alp-airbnb-nav">
                        <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-service-type-btn">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
                        </a>
                        <a href="#" class="wp-alp-airbnb-next-btn" id="next-from-location-btn">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
                        </a>
                    </div>
                </div>
            </div>

           <!-- Paso 1.4: Datos básicos (subpaso de Paso 1) -->
<div class="wp-alp-form-step" id="step-1-basic-info" data-step="1.4" style="display: none;">
    <!-- Header con opciones de ayuda -->
    <div class="wp-alp-airbnb-help-header">
        <div class="wp-alp-airbnb-help-links">
            <a href="#" class="wp-alp-airbnb-help-link">¿Tienes alguna duda?</a>
            <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-airbnb-save-link">Guardar y salir</a>
        </div>
    </div>
    
    <!-- Título y subtítulo de la página -->
    <div class="wp-alp-airbnb-category-content">
        <h1 class="wp-alp-airbnb-category-title">
            <?php echo esc_html(get_locale() == 'en_US' ? 'Add some basic data about your space' : 'Agrega algunos datos básicos de tu espacio'); ?>
        </h1>
        <p class="wp-alp-airbnb-category-subtitle">
            <?php echo esc_html(get_locale() == 'en_US' ? 'Later, you can add more details, like the types of amenities.' : 'Más adelante, podrás incluir otros detalles, como los tipos de servicios.'); ?>
        </p>
        
        <!-- Contenedor de tarjetas de campos -->
        <div class="wp-alp-basic-info-card-container">
            <!-- Primera tarjeta: Capacidad -->
            <div class="wp-alp-basic-info-card">
                <div class="wp-alp-card-header">
                    <div class="wp-alp-card-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M22.5 17.25a.75.75 0 0 1-.75.75H2.25a.75.75 0 0 1 0-1.5h19.5a.75.75 0 0 1 .75.75zm0 4.5a.75.75 0 0 1-.75.75H2.25a.75.75 0 0 1 0-1.5h19.5a.75.75 0 0 1 .75.75zm-18-10.5a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 .75.75zM4.5 6a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 4.5 6zM22.5 6a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1 0-1.5h15a.75.75 0 0 1 .75.75zm0 4.5a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1 0-1.5h15a.75.75 0 0 1 .75.75z"></path></svg>
                    </div>
                    <h3 class="wp-alp-card-title">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Capacity Information' : 'Información de capacidad'); ?>
                    </h3>
                </div>
                <div class="wp-alp-card-content">
                    <!-- Campo: Capacidad máxima -->
                    <div class="wp-alp-number-field-improved">
                        <label for="max_capacity" class="wp-alp-field-label">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Maximum Guest Capacity' : 'Capacidad Máxima de Invitados'); ?>
                        </label>
                        <div class="wp-alp-field-hint">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'How many guests can your venue accommodate?' : '¿Cuántos invitados puede acomodar tu espacio?'); ?>
                        </div>
                        <div class="wp-alp-number-control-improved">
                            <button type="button" class="wp-alp-number-decrease-improved" aria-label="Decrease">
                                <span>−</span>
                            </button>
                            <input type="number" id="max_capacity" name="max_capacity" min="1" value="100" class="wp-alp-number-input-improved">
                            <button type="button" class="wp-alp-number-increase-improved" aria-label="Increase">
                                <span>+</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Campo: Capacidad mínima -->
                    <div class="wp-alp-number-field-improved event-venue-field">
                        <label for="min_capacity" class="wp-alp-field-label">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Minimum Capacity' : 'Capacidad Mínima'); ?>
                        </label>
                        <div class="wp-alp-field-hint">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Minimum number of guests required to book your venue' : 'Número mínimo de invitados necesarios para reservar tu espacio'); ?>
                        </div>
                        <div class="wp-alp-number-control-improved">
                            <button type="button" class="wp-alp-number-decrease-improved" aria-label="Decrease">
                                <span>−</span>
                            </button>
                            <input type="number" id="min_capacity" name="min_capacity" min="1" value="10" class="wp-alp-number-input-improved">
                            <button type="button" class="wp-alp-number-increase-improved" aria-label="Increase">
                                <span>+</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Segunda tarjeta: Instalaciones -->
            <div class="wp-alp-basic-info-card">
                <div class="wp-alp-card-header">
                    <div class="wp-alp-card-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M22.31 14.76a.4.4 0 0 1-.4.4H2.09a.4.4 0 0 1-.4-.4v-5.48a.4.4 0 0 1 .4-.4h19.83a.4.4 0 0 1 .4.4zM6.47 11.93a1.2 1.2 0 1 0-2.4 0 1.2 1.2 0 0 0 2.4 0zm3.32 0a1.2 1.2 0 1 0-2.4 0 1.2 1.2 0 0 0 2.4 0z"></path></svg>
                    </div>
                    <h3 class="wp-alp-card-title">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Facilities Information' : 'Información de instalaciones'); ?>
                    </h3>
                </div>
                <div class="wp-alp-card-content">
                    <!-- Campo: Número de baños -->
                    <div class="wp-alp-number-field-improved event-venue-field">
                        <label for="restrooms" class="wp-alp-field-label">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Restrooms' : 'Baños'); ?>
                        </label>
                        <div class="wp-alp-field-hint">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Number of restrooms available at your venue' : 'Número de baños disponibles en tu espacio'); ?>
                        </div>
                        <div class="wp-alp-number-control-improved">
                            <button type="button" class="wp-alp-number-decrease-improved" aria-label="Decrease">
                                <span>−</span>
                            </button>
                            <input type="number" id="restrooms" name="restrooms" min="1" value="2" class="wp-alp-number-input-improved">
                            <button type="button" class="wp-alp-number-increase-improved" aria-label="Increase">
                                <span>+</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Campo: Horas de servicio (solo se muestra si el tipo de servicio es por hora) -->
                    <div class="wp-alp-number-field-improved hour-service-field" style="display: none;">
                        <label for="hours" class="wp-alp-field-label">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Service Hours' : 'Horas de Servicio'); ?>
                        </label>
                        <div class="wp-alp-field-hint">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Service hours included in your basic package' : 'Horas de servicio incluidas en tu paquete básico'); ?>
                        </div>
                        <div class="wp-alp-number-control-improved">
                            <button type="button" class="wp-alp-number-decrease-improved" aria-label="Decrease">
                                <span>−</span>
                            </button>
                            <input type="number" id="hours" name="hours" min="1" max="24" value="4" class="wp-alp-number-input-improved">
                            <button type="button" class="wp-alp-number-increase-improved" aria-label="Increase">
                                <span>+</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tercera tarjeta: Disponibilidad -->
            <div class="wp-alp-basic-info-card">
                <div class="wp-alp-card-header">
                    <div class="wp-alp-card-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M22.5 2.25h-5.47V1a.75.75 0 0 0-1.5 0v1.25H8.47V1a.75.75 0 0 0-1.5 0v1.25H1.5A1.5 1.5 0 0 0 0 3.75v18.75a1.5 1.5 0 0 0 1.5 1.5h21a1.5 1.5 0 0 0 1.5-1.5V3.75a1.5 1.5 0 0 0-1.5-1.5zM1.5 3.75h5.47V5a.75.75 0 0 0 1.5 0V3.75h7.06V5a.75.75 0 0 0 1.5 0V3.75H22.5v4.5h-21zM22.5 22.5h-21V9.75h21z"></path></svg>
                    </div>
                    <h3 class="wp-alp-card-title">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Availability Options' : 'Opciones de disponibilidad'); ?>
                    </h3>
                </div>
                <div class="wp-alp-card-content">
                    <!-- Campo: Eventos simultáneos (toggle) -->
                    <div class="wp-alp-toggle-field-improved">
                        <div class="wp-alp-toggle-text-improved">
                            <span class="wp-alp-toggle-label">
                                <?php echo esc_html(get_locale() == 'en_US' ? 'Multiple events per day' : 'Eventos múltiples por día'); ?>
                            </span>
                            <p class="wp-alp-toggle-description-improved">
                                <?php echo esc_html(get_locale() == 'en_US' ? 'Can your venue host multiple events on the same day?' : '¿Tu espacio puede albergar varios eventos en el mismo día?'); ?>
                            </p>
                        </div>
                        <div class="wp-alp-toggle-switch-improved">
                            <label class="wp-alp-switch-improved">
                                <input type="checkbox" id="host_more_than_one_ev" name="host_more_than_one_ev">
                                <span class="wp-alp-slider-improved round"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Barra de navegación fija -->
    <div class="wp-alp-airbnb-footer">
        <!-- Barra de progreso con avance -->
        <div class="wp-alp-airbnb-progress-bar">
            <div class="wp-alp-airbnb-progress-completed" style="width: 80%;"></div>
        </div>
        
        <!-- Botones de navegación -->
        <div class="wp-alp-airbnb-nav">
            <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-location-btn">
                <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
            </a>
            <a href="#" class="wp-alp-airbnb-next-btn" id="finish-step-1-btn">
            <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
           </a>
       </div>
   </div>
</div>

           <!-- Paso 2: Inicio de información del listing - HivePress Formulario Oculto -->
<div class="wp-alp-form-step" id="step-2-intro" data-step="2" style="display: none;">
   <div class="wp-alp-dual-column-container">
       <!-- Columna izquierda con texto -->
       <div class="wp-alp-dual-column-left">
           <!-- Etiqueta del paso -->
           <div class="wp-alp-step-label">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Step 2' : 'Paso 2'); ?>
           </div>
           
           <!-- Título del paso -->
           <h1 class="wp-alp-step-heading">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Make it stand out' : 'Haz que destaque'); ?>
           </h1>
           
           <!-- Descripción del paso -->
           <p class="wp-alp-step-description-large">
               <?php echo esc_html(get_locale() == 'en_US' ? 'In this step, we\'ll ask for photos, a title, and a description of your space. These details will help customers find and choose your listing.' : 'En este paso, te pediremos fotos, un título y una descripción de tu espacio. Estos detalles ayudarán a que los clientes encuentren y elijan tu anuncio.'); ?>
           </p>
       </div>
       
       <!-- Columna derecha con imagen -->
       <div class="wp-alp-dual-column-right">
           <div class="wp-alp-step-illustration">
               <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/vendor-form-step2.png'); ?>" alt="Make it stand out">
           </div>
       </div>
   </div>
   
   <!-- Barra de progreso y navegación en la parte inferior -->
   <div class="wp-alp-airbnb-footer">
       <!-- Barra de progreso -->
       <div class="wp-alp-airbnb-progress-bar">
           <div class="wp-alp-airbnb-progress-completed" style="width: 100%;"></div>
       </div>
       
       <!-- Navegación -->
       <div class="wp-alp-airbnb-nav">
           <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-step-1-last-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
           </a>
           <a href="#" class="wp-alp-airbnb-next-btn" id="go-to-step-2-listing-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
           </a>
       </div>
   </div>
</div>

<!-- Paso 2.1: Información Básica del Listing -->
<div class="wp-alp-form-step" id="step-2-basic" data-step="2.1" style="display: none;">
   <!-- Header con opciones de ayuda -->
   <div class="wp-alp-airbnb-help-header">
       <div class="wp-alp-airbnb-help-links">
           <a href="#" class="wp-alp-airbnb-help-link">¿Tienes alguna duda?</a>
           <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-airbnb-save-link">Guardar y salir</a>
       </div>
   </div>
   
   <!-- Contenedor de formulario personalizado -->
   <div class="wp-alp-airbnb-category-content">
       <h1 class="wp-alp-airbnb-category-title">
           <?php echo esc_html(get_locale() == 'en_US' ? 'Tell us about your listing' : 'Cuéntanos sobre tu anuncio'); ?>
       </h1>
       
       <!-- Contenedor para el formulario de HivePress oculto -->
       <div id="wp-alp-hidden-hivepress-form" style="display: none;">
           <?php
           // Verificar si HivePress está activo
           if (class_exists('HivePress\Core')) {
               // Verificar si el usuario está loggeado
               if (is_user_logged_in()) {
                   // Crear modelo de listing (nuevo o existente)
                   $listing_id = isset($_GET['listing_id']) ? absint($_GET['listing_id']) : null;
                   
                   if ($listing_id) {
                       // Cargar listing existente
                       $listing = \HivePress\Models\Listing::query()->get_by_id($listing_id);
                   } else {
                       // Crear nuevo listing
                       $listing = new \HivePress\Models\Listing([
                           'status' => 'draft',
                           'user' => get_current_user_id(),
                       ]);
                       
                       // Guardar para obtener ID
                       $listing->save();
                   }
                   
                   if ($listing) {
                       // Crear el formulario de HivePress
                       $form = \HivePress\Helpers\create_class_instance('\HivePress\Forms\Listing_Submit', [['model' => $listing, 'redirect' => false]]);
                       
                       // Renderizar el formulario
                       if ($form) {
                           echo $form->render();
                       }
                   }
               }
           }
           ?>
       </div>
       
       <!-- Formulario personalizado visible -->
       <div class="wp-alp-basic-info-card-container">
           <!-- Tarjeta 1: Información esencial -->
           <div class="wp-alp-basic-info-card">
               <div class="wp-alp-card-header">
                   <div class="wp-alp-card-icon">
                       <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19.5 3H4.5A1.5 1.5 0 0 0 3 4.5v15A1.5 1.5 0 0 0 4.5 21h15a1.5 1.5 0 0 0 1.5-1.5v-15A1.5 1.5 0 0 0 19.5 3zM4.5 4.5h15v3.75h-15zm0 15v-10.5h15v10.5z"/></svg>
                   </div>
                   <h3 class="wp-alp-card-title">
                       <?php echo esc_html(get_locale() == 'en_US' ? 'Essential Information' : 'Información Esencial'); ?>
                   </h3>
               </div>
               <div class="wp-alp-card-content">
                   <!-- Título del listing -->
                   <div class="wp-alp-form-group">
                       <label for="listing-title" class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Listing Title' : 'Título del Anuncio'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Create a catchy title that describes your service' : 'Crea un título atractivo que describa tu servicio'); ?>
                       </div>
                       <input type="text" id="listing-title" name="title" class="wp-alp-form-input" maxlength="256" required>
                   </div>
                   
                   <!-- Precio base -->
                   <div class="wp-alp-form-group">
                       <label for="listing-price" class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Base Price' : 'Precio Base'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Set your starting price' : 'Establece tu precio inicial'); ?>
                       </div>
                       <input type="number" id="listing-price" name="price" step="0.01" min="0" class="wp-alp-form-input" required>
                   </div>
               </div>
           </div>
           
           <!-- Tarjeta 2: Descripción -->
           <div class="wp-alp-basic-info-card">
               <div class="wp-alp-card-header">
                   <div class="wp-alp-card-icon">
                       <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6zm2-9h8v2H8zm0 4h8v2H8z"/></svg>
                   </div>
                   <h3 class="wp-alp-card-title">
                       <?php echo esc_html(get_locale() == 'en_US' ? 'Description' : 'Descripción'); ?>
                   </h3>
               </div>
               <div class="wp-alp-card-content">
                   <div class="wp-alp-form-group">
                       <label for="listing-description" class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Listing Description' : 'Descripción del Anuncio'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Describe your service in detail. Highlight what makes it unique.' : 'Describe tu servicio en detalle. Destaca lo que lo hace único.'); ?>
                       </div>
                       <textarea id="listing-description" name="description" rows="8" class="wp-alp-form-input" maxlength="10240" required></textarea>
                   </div>
               </div>
           </div>
       </div>
   </div>
   
   <!-- Barra de navegación fija -->
   <div class="wp-alp-airbnb-footer">
       <!-- Barra de progreso con avance -->
       <div class="wp-alp-airbnb-progress-bar">
           <div class="wp-alp-airbnb-progress-completed" style="width: 14%;"></div>
       </div>
       
       <!-- Botones de navegación -->
       <div class="wp-alp-airbnb-nav">
           <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-step-2-intro-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
           </a>
           <a href="#" class="wp-alp-airbnb-next-btn" id="next-to-photos-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
           </a>
       </div>
   </div>
</div>

<!-- Paso 2.2: Fotos y Media -->
<div class="wp-alp-form-step" id="step-2-photos" data-step="2.2" style="display: none;">
   <!-- Header con opciones de ayuda -->
   <div class="wp-alp-airbnb-help-header">
       <div class="wp-alp-airbnb-help-links">
           <a href="#" class="wp-alp-airbnb-help-link">¿Tienes alguna duda?</a>
           <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-airbnb-save-link">Guardar y salir</a>
       </div>
   </div>
   
   <!-- Contenedor de fotos -->
   <div class="wp-alp-airbnb-category-content">
       <h1 class="wp-alp-airbnb-category-title">
           <?php echo esc_html(get_locale() == 'en_US' ? 'Add photos of your space' : 'Agrega fotos de tu espacio'); ?>
       </h1>
       <p class="wp-alp-airbnb-category-subtitle">
           <?php echo esc_html(get_locale() == 'en_US' ? 'Upload at least 5 photos. You can add more later.' : 'Sube al menos 5 fotos. Puedes agregar más después.'); ?>
       </p>
       
       <!-- Zona de carga de fotos personalizada -->
       <div class="wp-alp-photo-upload-container">
           <div class="wp-alp-photo-upload-zone" id="photo-upload-zone">
               <svg viewBox="0 0 64 64" style="height: 64px; width: 64px; fill: currentcolor;">
                   <path d="M56 2H8a6 6 0 0 0-6 6v48a6 6 0 0 0 6 6h48a6 6 0 0 0 6-6V8a6 6 0 0 0-6-6zM8 6h48a2 2 0 0 1 2 2v29.894L41.117 21.01a2 2 0 0 0-2.824 0l-16 16-4.586-4.586a2 2 0 0 0-2.828 0L6 41.303V8a2 2 0 0 1 2-2zm0 52a2 2 0 0 1-2-2V46.697l10.293-10.293 4.586 4.586a2 2 0 0 0 2.828 0l16-16L58 41.284V56a2 2 0 0 1-2 2H8z"/>
                   <circle cx="18" cy="18" r="6"/>
               </svg>
               <h2><?php echo esc_html(get_locale() == 'en_US' ? 'Drag your photos here' : 'Arrastra tus fotos aquí'); ?></h2>
               <p><?php echo esc_html(get_locale() == 'en_US' ? 'Choose at least 5 photos' : 'Elige al menos 5 fotos'); ?></p>
               <button type="button" class="wp-alp-upload-button" id="select-photos-btn">
                   <?php echo esc_html(get_locale() == 'en_US' ? 'Upload from your device' : 'Subir desde tu dispositivo'); ?>
               </button>
               <input type="file" id="photo-input" multiple accept="image/*" style="display: none;">
           </div>
           
           <!-- Vista previa de fotos cargadas -->
           <div class="wp-alp-photos-preview" id="photos-preview">
               <!-- Las fotos cargadas se mostrarán aquí dinámicamente -->
           </div>
       </div>
       
       <!-- Video URL (opcional) -->
       <div class="wp-alp-video-section">
           <h3><?php echo esc_html(get_locale() == 'en_US' ? 'Add a video (optional)' : 'Agrega un video (opcional)'); ?></h3>
           <div class="wp-alp-form-group">
               <label for="video-url" class="wp-alp-field-label">
                   <?php echo esc_html(get_locale() == 'en_US' ? 'Video URL' : 'URL del Video'); ?>
               </label>
               <div class="wp-alp-field-hint">
                   <?php echo esc_html(get_locale() == 'en_US' ? 'Add a YouTube or Vimeo link' : 'Agrega un enlace de YouTube o Vimeo'); ?>
               </div>
               <input type="url" id="video-url" name="video" class="wp-alp-form-input" placeholder="https://www.youtube.com/watch?v=...">
           </div>
       </div>
   </div>
   
   <!-- Barra de navegación fija -->
   <div class="wp-alp-airbnb-footer">
       <!-- Barra de progreso con avance -->
       <div class="wp-alp-airbnb-progress-bar">
           <div class="wp-alp-airbnb-progress-completed" style="width: 28%;"></div>
       </div>
       
       <!-- Botones de navegación -->
       <div class="wp-alp-airbnb-nav">
           <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-basic-info-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
           </a>
           <a href="#" class="wp-alp-airbnb-next-btn" id="next-to-pricing-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
           </a>
       </div>
   </div>
</div>

<!-- Paso 2.3: Precios y Disponibilidad -->
<div class="wp-alp-form-step" id="step-2-pricing" data-step="2.3" style="display: none;">
   <!-- Header con opciones de ayuda -->
   <div class="wp-alp-airbnb-help-header">
       <div class="wp-alp-airbnb-help-links">
           <a href="#" class="wp-alp-airbnb-help-link">¿Tienes alguna duda?</a>
           <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-airbnb-save-link">Guardar y salir</a>
       </div>
   </div>
   
   <!-- Contenedor de precios -->
   <div class="wp-alp-airbnb-category-content">
       <h1 class="wp-alp-airbnb-category-title">
           <?php echo esc_html(get_locale() == 'en_US' ? 'Set up your pricing' : 'Configura tus precios'); ?>
       </h1>
       
       <div class="wp-alp-basic-info-card-container">
           <!-- Tarjeta 1: Precios variables -->
           <div class="wp-alp-basic-info-card">
               <div class="wp-alp-card-header">
                   <div class="wp-alp-card-icon">
                       <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13v4H8v2h3v2h2v-2h2a2 2 0 0 0 0-4h-2V7h4V5h-3a2 2 0 0 0-3 2zm2 4h2v2h-2v-2z"/></svg>
                   </div>
                   <h3 class="wp-alp-card-title">
                       <?php echo esc_html(get_locale() == 'en_US' ? 'Variable Pricing' : 'Precios Variables'); ?>
                   </h3>
               </div>
               <div class="wp-alp-card-content">
                   <!-- Precios por día de la semana -->
                   <div class="wp-alp-form-group">
                       <label class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Daily Prices' : 'Precios Diarios'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Set different prices for specific days' : 'Establece precios diferentes para días específicos'); ?>
                       </div>
                       <div id="daily-prices-container" class="wp-alp-repeater-container">
                           <!-- Botón para agregar precio diario -->
                           <button type="button" class="wp-alp-add-item-btn" id="add-daily-price">
                               <?php echo esc_html(get_locale() == 'en_US' ? '+ Add Daily Price' : '+ Agregar Precio Diario'); ?>
                           </button>
                       </div>
                   </div>
                   
                   <!-- Niveles de precio -->
                   <div class="wp-alp-form-group">
                       <label class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Price Tiers' : 'Niveles de Precio'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Create different pricing tiers' : 'Crea diferentes niveles de precio'); ?>
                       </div>
                       <div id="price-tiers-container" class="wp-alp-repeater-container">
                           <button type="button" class="wp-alp-add-item-btn" id="add-price-tier">
                               <?php echo esc_html(get_locale() == 'en_US' ? '+ Add Price Tier' : '+ Agregar Nivel de Precio'); ?>
                           </button>
                       </div>
                   </div>
               </div>
           </div>
           
           <!-- Tarjeta 2: Extras y Descuentos -->
           <div class="wp-alp-basic-info-card">
               <div class="wp-alp-card-header">
                   <div class="wp-alp-card-icon">
                       <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/></svg>
                   </div>
                   <h3 class="wp-alp-card-title">
                       <?php echo esc_html(get_locale() == 'en_US' ? 'Extras & Discounts' : 'Extras y Descuentos'); ?>
                   </h3>
               </div>
               <div class="wp-alp-card-content">
                   <!-- Extras -->
                   <div class="wp-alp-form-group">
                       <label class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Extra Services' : 'Servicios Adicionales'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Add optional services with additional costs' : 'Agrega servicios opcionales con costos adicionales'); ?>
                       </div>
                       <div id="extras-container" class="wp-alp-repeater-container">
                           <button type="button" class="wp-alp-add-item-btn" id="add-extra">
                               <?php echo esc_html(get_locale() == 'en_US' ? '+ Add Extra' : '+ Agregar Extra'); ?>
                           </button>
                       </div>
                   </div>
                   
                   <!-- Descuentos -->
                   <div class="wp-alp-form-group">
                       <label class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Discounts' : 'Descuentos'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Offer discounts for bulk bookings' : 'Ofrece descuentos por reservas múltiples'); ?>
                       </div>
                       <div id="discounts-container" class="wp-alp-repeater-container">
                           <button type="button" class="wp-alp-add-item-btn" id="add-discount">
                               <?php echo esc_html(get_locale() == 'en_US' ? '+ Add Discount' : '+ Agregar Descuento'); ?>
                           </button>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
   
   <!-- Barra de navegación fija -->
   <div class="wp-alp-airbnb-footer">
       <!-- Barra de progreso con avance -->
       <div class="wp-alp-airbnb-progress-bar">
           <div class="wp-alp-airbnb-progress-completed" style="width: 42%;"></div>
       </div>
       
       <!-- Botones de navegación -->
       <div class="wp-alp-airbnb-nav">
           <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-photos-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
           </a>
           <a href="#" class="wp-alp-airbnb-next-btn" id="next-to-availability-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
           </a>
       </div>
   </div>
</div>

<!-- Paso 2.4: Disponibilidad y Configuración de Reservas -->
<div class="wp-alp-form-step" id="step-2-availability" data-step="2.4" style="display: none;">
   <!-- Header con opciones de ayuda -->
   <div class="wp-alp-airbnb-help-header">
       <div class="wp-alp-airbnb-help-links">
           <a href="#" class="wp-alp-airbnb-help-link">¿Tienes alguna duda?</a>
           <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-airbnb-save-link">Guardar y salir</a>
       </div>
   </div>
   
   <!-- Contenedor de disponibilidad -->
   <div class="wp-alp-airbnb-category-content">
       <h1 class="wp-alp-airbnb-category-title">
           <?php echo esc_html(get_locale() == 'en_US' ? 'Set your availability' : 'Configura tu disponibilidad'); ?>
       </h1>
       
       <div class="wp-alp-basic-info-card-container">
           <!-- Tarjeta 1: Configuración de tiempos -->
           <div class="wp-alp-basic-info-card">
               <div class="wp-alp-card-header">
                   <div class="wp-alp-card-icon">
                       <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-.22-13h-.06c-.4 0-.72.32-.72.72v4.72c0 .35.18.68.49.86l4.15 2.49c.34.2.78.1.98-.24.21-.34.1-.79-.25-.99l-3.87-2.3V7.72c0-.4-.32-.72-.72-.72z"/></svg>
                   </div>
                   <h3 class="wp-alp-card-title">
                       <?php echo esc_html(get_locale() == 'en_US' ? 'Booking Schedule' : 'Horario de Reservas'); ?>
                   </h3>
               </div>
               <div class="wp-alp-card-content">
                   <!-- Horario de disponibilidad -->
                   <div class="wp-alp-form-group">
                       <label for="booking-min-time" class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Available From' : 'Disponible Desde'); ?>
                       </label>
                       <input type="time" id="booking-min-time" name="booking_min_time" class="wp-alp-form-input" required>
                   </div>
                   
                   <div class="wp-alp-form-group">
                       <label for="booking-max-time" class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Available To' : 'Disponible Hasta'); ?>
                       </label>
                       <input type="time" id="booking-max-time" name="booking_max_time" class="wp-alp-form-input" required>
                   </div>
                   
                   <!-- Duración del slot -->
                   <div class="wp-alp-number-field-improved">
                       <label for="booking-slot-duration" class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Booking Slot Duration' : 'Duración del Slot de Reserva'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Set the time slot duration in minutes' : 'Establece la duración del slot en minutos'); ?>
                       </div>
                       <div class="wp-alp-number-control-improved">
                           <button type="button" class="wp-alp-number-decrease-improved" aria-label="Decrease">
                               <span>−</span>
                           </button>
                           <input type="number" id="booking-slot-duration" name="booking_slot_duration" min="5" max="720" value="60" class="wp-alp-number-input-improved" required>
                           <button type="button" class="wp-alp-number-increase-improved" aria-label="Increase">
                               <span>+</span>
                           </button>
                       </div>
                   </div>
               </div>
           </div>
           
           <!-- Tarjeta 2: Políticas de reserva -->
           <div class="wp-alp-basic-info-card">
               <div class="wp-alp-card-header">
                   <div class="wp-alp-card-icon">
                       <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M14 6V4h-4v2h4zM4 8v11h16V8H4zm16-2c1.11 0 2 .89 2 2v11c0 1.11-.89 2-2 2H4c-1.11 0-2-.89-2-2l.01-11c0-1.11.88-2 1.99-2h4V4c0-1.11.89-2 2-2h4c1.11 0 2 .89 2 2v2h4z"/></svg>
                   </div>
                   <h3 class="wp-alp-card-title">
                       <?php echo esc_html(get_locale() == 'en_US' ? 'Booking Policies' : 'Políticas de Reserva'); ?>
                   </h3>
               </div>
               <div class="wp-alp-card-content">
                   <!-- Offset de reserva -->
                   <div class="wp-alp-number-field-improved">
                       <label for="booking-offset" class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Advance Notice' : 'Aviso Previo'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Days required before booking' : 'Días requeridos antes de la reserva'); ?>
                       </div>
                       <div class="wp-alp-number-control-improved">
                           <button type="button" class="wp-alp-number-decrease-improved" aria-label="Decrease">
                               <span>−</span>
                           </button>
                           <input type="number" id="booking-offset" name="booking_offset" min="0" value="1" class="wp-alp-number-input-improved">
                           <button type="button" class="wp-alp-number-increase-improved" aria-label="Increase">
                               <span>+</span>
                           </button>
                       </div>
                   </div>
                   
                   <!-- Ventana de reserva -->
                   <div class="wp-alp-number-field-improved">
                       <label for="booking-window" class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Booking Window' : 'Ventana de Reserva'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'How far in advance can bookings be made' : 'Con cuánta anticipación se pueden hacer reservas'); ?>
                       </div>
                       <div class="wp-alp-number-control-improved">
                           <button type="button" class="wp-alp-number-decrease-improved" aria-label="Decrease">
                               <span>−</span>
                           </button>
                           <input type="number" id="booking-window" name="booking_window" min="1" value="90" class="wp-alp-number-input-improved">
                           <button type="button" class="wp-alp-number-increase-improved" aria-label="Increase">
                               <span>+</span>
                           </button>
                       </div>
                   </div>
                   
                   <!-- Aceptación manual -->
                   <div class="wp-alp-toggle-field-improved">
                       <div class="wp-alp-toggle-text-improved">
                           <span class="wp-alp-toggle-label">
                               <?php echo esc_html(get_locale() == 'en_US' ? 'Manual Acceptance' : 'Aceptación Manual'); ?>
                           </span>
                           <p class="wp-alp-toggle-description-improved">
                               <?php echo esc_html(get_locale() == 'en_US' ? 'Manually accept new bookings' : 'Acepta manualmente las nuevas reservas'); ?>
                           </p>
                       </div>
                       <div class="wp-alp-toggle-switch-improved">
                           <label class="wp-alp-switch-improved">
                               <input type="checkbox" id="booking-moderated" name="booking_moderated">
                               <span class="wp-alp-slider-improved round"></span>
                           </label>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
   
   <!-- Barra de navegación fija -->
   <div class="wp-alp-airbnb-footer">
       <!-- Barra de progreso con avance -->
       <div class="wp-alp-airbnb-progress-bar">
           <div class="wp-alp-airbnb-progress-completed" style="width: 56%;"></div>
       </div>
       
       <!-- Botones de navegación -->
       <div class="wp-alp-airbnb-nav">
           <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-pricing-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
           </a>
           <a href="#" class="wp-alp-airbnb-next-btn" id="next-to-features-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
           </a>
       </div>
   </div>
</div>

<!-- Paso 2.5: Características y Servicios -->
<div class="wp-alp-form-step" id="step-2-features" data-step="2.5" style="display: none;">
   <!-- Header con opciones de ayuda -->
   <div class="wp-alp-airbnb-help-header">
       <div class="wp-alp-airbnb-help-links">
           <a href="#" class="wp-alp-airbnb-help-link">¿Tienes alguna duda?</a>
           <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-airbnb-save-link">Guardar y salir</a>
       </div>
   </div>
   
   <!-- Contenedor de características -->
   <div class="wp-alp-airbnb-category-content">
       <h1 class="wp-alp-airbnb-category-title">
           <?php echo esc_html(get_locale() == 'en_US' ? 'Tell us what you offer' : 'Cuéntanos qué ofreces'); ?>
       </h1>
       
       <div class="wp-alp-basic-info-card-container">
           <!-- Tarjeta 1: Servicios incluidos -->
           <div class="wp-alp-basic-info-card">
               <div class="wp-alp-card-header">
                   <div class="wp-alp-card-icon">
                       <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                   </div>
                   <h3 class="wp-alp-card-title">
                       <?php echo esc_html(get_locale() == 'en_US' ? 'Included Services' : 'Servicios Incluidos'); ?>
                   </h3>
               </div>
               <div class="wp-alp-card-content">
                   <div class="wp-alp-checkbox-grid">
                       <label class="wp-alp-checkbox-item">
                           <input type="checkbox" name="service_features_even[]" value="70">
                           <span><?php echo esc_html(get_locale() == 'en_US' ? 'All-Inclusive' : 'Todo Incluido'); ?></span>
                       </label>
                       <label class="wp-alp-checkbox-item">
                           <input type="checkbox" name="service_features_even[]" value="69">
                           <span><?php echo esc_html(get_locale() == 'en_US' ? 'Venue Rental Only' : 'Solo Renta del Lugar'); ?></span>
                       </label>
                       <label class="wp-alp-checkbox-item">
                           <input type="checkbox" name="service_features_even[]" value="65">
                           <span><?php echo esc_html(get_locale() == 'en_US' ? 'With Banquet Service' : 'Con Servicio de Banquete'); ?></span>
                       </label>
                       <label class="wp-alp-checkbox-item">
                           <input type="checkbox" name="service_features_even[]" value="66">
                           <span><?php echo esc_html(get_locale() == 'en_US' ? 'With Decoration' : 'Con Decoración'); ?></span>
                       </label>
                       <label class="wp-alp-checkbox-item">
                           <input type="checkbox" name="service_features_even[]" value="67">
                           <span><?php echo esc_html(get_locale() == 'en_US' ? 'With Furniture' : 'Con Mobiliario'); ?></span>
                       </label>
                       <label class="wp-alp-checkbox-item">
                           <input type="checkbox" name="service_features_even[]" value="68">
                           <span><?php echo esc_html(get_locale() == 'en_US' ? 'With Music' : 'Con Música'); ?></span>
                       </label>
                   </div>
               </div>
           </div>
           
           <!-- Tarjeta 2: Etiquetas y Tags -->
           <div class="wp-alp-basic-info-card">
               <div class="wp-alp-card-header">
                   <div class="wp-alp-card-icon">
                       <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/></svg>
                   </div>
                   <h3 class="wp-alp-card-title">
                       <?php echo esc_html(get_locale() == 'en_US' ? 'Tags' : 'Etiquetas'); ?>
                   </h3>
               </div>
               <div class="wp-alp-card-content">
                   <div class="wp-alp-form-group">
                       <label class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Event Types' : 'Tipos de Eventos'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Select all that apply' : 'Selecciona todos los que apliquen'); ?>
                       </div>
                       <div class="wp-alp-tags-select">
                           <select id="tags-select" name="tags[]" multiple class="wp-alp-form-select">
                               <option value="86"><?php echo esc_html(get_locale() == 'en_US' ? 'Weddings' : 'Bodas'); ?></option>
                               <option value="87"><?php echo esc_html(get_locale() == 'en_US' ? 'Social Events' : 'Eventos Sociales'); ?></option>
                               <option value="88"><?php echo esc_html(get_locale() == 'en_US' ? 'Parties' : 'Fiestas'); ?></option>
                               <option value="89"><?php echo esc_html(get_locale() == 'en_US' ? 'Anniversaries' : 'Aniversarios'); ?></option>
                               <option value="90"><?php echo esc_html(get_locale() == 'en_US' ? 'Birthdays' : 'Cumpleaños'); ?></option>
                               <option value="95"><?php echo esc_html(get_locale() == 'en_US' ? 'Quinceañera' : 'Quinceañera'); ?></option>
                               <option value="109"><?php echo esc_html(get_locale() == 'en_US' ? 'Conferences' : 'Conferencias'); ?></option>
                               <option value="110"><?php echo esc_html(get_locale() == 'en_US' ? 'Networking Events' : 'Eventos de Networking'); ?></option>
                           </select>
                       </div>
                   </div>
               </div>
           </div>
           
           <!-- Tarjeta 3: Información Adicional -->
           <div class="wp-alp-basic-info-card">
               <div class="wp-alp-card-header">
                   <div class="wp-alp-card-icon">
                       <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11 17h2v-6h-2v6zm1-15C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zM11 9h2V7h-2v2z"/></svg>
                   </div>
                   <h3 class="wp-alp-card-title">
                       <?php echo esc_html(get_locale() == 'en_US' ? 'Additional Information' : 'Información Adicional'); ?>
                   </h3>
               </div>
               <div class="wp-alp-card-content">
                   <!-- Nota de reserva -->
                   <div class="wp-alp-form-group">
                       <label for="purchase-note" class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Booking Note' : 'Nota de Reserva'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Add a note that will be revealed to the customer upon booking' : 'Agrega una nota que se revelará al cliente al reservar'); ?>
                       </div>
                       <textarea id="purchase-note" name="purchase_note" rows="4" class="wp-alp-form-input" maxlength="10240"></textarea>
                   </div>
               </div>
           </div>
       </div>
   </div>
   
   <!-- Barra de navegación fija -->
   <div class="wp-alp-airbnb-footer">
       <!-- Barra de progreso con avance -->
       <div class="wp-alp-airbnb-progress-bar">
           <div class="wp-alp-airbnb-progress-completed" style="width: 70%;"></div>
       </div>
       
       <!-- Botones de navegación -->
       <div class="wp-alp-airbnb-nav">
           <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-availability-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
           </a>
           <a href="#" class="wp-alp-airbnb-next-btn" id="next-to-contact-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
           </a>
       </div>
   </div>
</div>

<!-- Paso 2.6: Información de Contacto -->
<div class="wp-alp-form-step" id="step-2-contact" data-step="2.6" style="display: none;">
   <!-- Header con opciones de ayuda -->
   <div class="wp-alp-airbnb-help-header">
       <div class="wp-alp-airbnb-help-links">
           <a href="#" class="wp-alp-airbnb-help-link">¿Tienes alguna duda?</a>
           <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-airbnb-save-link">Guardar y salir</a>
       </div>
   </div>
   
   <!-- Contenedor de información de contacto -->
   <div class="wp-alp-airbnb-category-content">
       <h1 class="wp-alp-airbnb-category-title">
           <?php echo esc_html(get_locale() == 'en_US' ? 'Contact Information' : 'Información de Contacto'); ?>
       </h1>
       
       <div class="wp-alp-basic-info-card-container">
           <!-- Tarjeta única para información de contacto -->
           <div class="wp-alp-basic-info-card">
               <div class="wp-alp-card-header">
                   <div class="wp-alp-card-icon">
                       <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                   </div>
                   <h3 class="wp-alp-card-title">
                       <?php echo esc_html(get_locale() == 'en_US' ? 'Provider Details' : 'Detalles del Proveedor'); ?>
                   </h3>
               </div>
               <div class="wp-alp-card-content">
                   <!-- Información de contacto del proveedor -->
                   <div class="wp-alp-form-group">
                       <label for="contact-information" class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Service Provider Contact Information' : 'Información de Contacto del Proveedor'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'Enter your contact details' : 'Ingresa tus datos de contacto'); ?>
                       </div>
                       <textarea id="contact-information" name="contact_information_provider" rows="6" class="wp-alp-form-input" 
                                 placeholder="<?php echo esc_attr(get_locale() == 'en_US' ? 
                                     "Company Name:\nContact Name:\nPhone:\nEmail:\nAddress:" : 
                                     "Nombre de la Empresa:\nNombre de Contacto:\nTeléfono:\nCorreo:\nDirección:"); ?>"></textarea>
                   </div>
                   
                   <!-- WhatsApp URL -->
                   <div class="wp-alp-form-group">
                       <label for="whatsapp-url" class="wp-alp-field-label">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'WhatsApp URL' : 'URL de WhatsApp'); ?>
                       </label>
                       <div class="wp-alp-field-hint">
                           <?php echo esc_html(get_locale() == 'en_US' ? 'https://wa.me/52(10-digit cell phone number without spaces)' : 'https://wa.me/52(número de celular de 10 dígitos sin espacios)'); ?>
                       </div>
                       <input type="url" id="whatsapp-url" name="whatsapp_url_provider" 
                              class="wp-alp-form-input" 
                              placeholder="https://wa.me/521111111111"
                              maxlength="2048">
                   </div>
               </div>
           </div>
       </div>
   </div>
   
   <!-- Barra de navegación fija -->
   <div class="wp-alp-airbnb-footer">
       <!-- Barra de progreso con avance -->
       <div class="wp-alp-airbnb-progress-bar">
           <div class="wp-alp-airbnb-progress-completed" style="width: 85%;"></div>
       </div>
       
       <!-- Botones de navegación -->
       <div class="wp-alp-airbnb-nav">
           <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-features-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
           </a>
           <a href="#" class="wp-alp-airbnb-next-btn" id="go-to-step-3-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
           </a>
       </div>
   </div>
</div>

<!-- Paso 3: Finalizar y Publicar -->
<div class="wp-alp-form-step" id="step-3" data-step="3" style="display: none;">
   <div class="wp-alp-dual-column-container">
       <!-- Columna izquierda con texto -->
       <div class="wp-alp-dual-column-left">
           <!-- Etiqueta del paso -->
           <div class="wp-alp-step-label">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Step 3' : 'Paso 3'); ?>
           </div>
           
           <!-- Título del paso -->
           <h1 class="wp-alp-step-heading">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Finish and publish' : 'Terminar y publicar'); ?>
           </h1>
           
           <!-- Descripción del paso -->
           <p class="wp-alp-step-description-large">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Review your listing and accept the terms to publish it.' : 'Revisa tu anuncio y acepta los términos para publicarlo.'); ?>
           </p>
       </div>
       
       <!-- Columna derecha con imagen -->
       <div class="wp-alp-dual-column-right">
           <div class="wp-alp-step-illustration">
               <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/vendor-form-step3.png'); ?>" alt="Finish and publish">
           </div>
       </div>
   </div>
   
   <!-- Barra de progreso y navegación en la parte inferior -->
   <div class="wp-alp-airbnb-footer">
       <!-- Barra de progreso completa -->
       <div class="wp-alp-airbnb-progress-bar">
           <div class="wp-alp-airbnb-progress-completed" style="width: 0%;"></div>
       </div>
       
       <!-- Navegación -->
       <div class="wp-alp-airbnb-nav">
           <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-step-2-last-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
           </a>
           <a href="#" class="wp-alp-airbnb-next-btn" id="go-to-review-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
           </a>
       </div>
   </div>
</div>

<!-- Paso 3.1: Revisión Final -->
<div class="wp-alp-form-step" id="step-3-review" data-step="3.1" style="display: none;">
   <!-- Header con opciones de ayuda -->
   <div class="wp-alp-airbnb-help-header">
       <div class="wp-alp-airbnb-help-links">
           <a href="#" class="wp-alp-airbnb-help-link">¿Tienes alguna duda?</a>
           <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-airbnb-save-link">Guardar y salir</a>
       </div>
   </div>
   
   <!-- Contenedor de revisión final -->
   <div class="wp-alp-airbnb-category-content">
       <h1 class="wp-alp-airbnb-category-title">
           <?php echo esc_html(get_locale() == 'en_US' ? 'Review your listing' : 'Revisa tu anuncio'); ?>
       </h1>
       
       <!-- Resumen de la información -->
       <div class="wp-alp-review-container">
           <!-- Aquí se mostrará el resumen de toda la información ingresada -->
           <div id="listing-review-summary" class="wp-alp-review-summary">
               <!-- El contenido se generará dinámicamente con JavaScript -->
           </div>
           
           <!-- Términos y condiciones -->
           <div class="wp-alp-terms-section">
               <label class="wp-alp-checkbox-item">
                   <input type="checkbox" id="terms-checkbox" name="_terms" value="1" required>
                   <span>
                       <?php echo sprintf(
                           esc_html(get_locale() == 'en_US' ? 
                               'I agree to the %sterms and conditions%s' : 
                               'Acepto los %stérminos y condiciones%s'),
                           '<a href="' . esc_url(home_url('/terms-and-conditions/')) . '" target="_blank">',
                           '</a>'
                       ); ?>
                   </span>
               </label>
           </div>
       </div>
   </div>
   
   <!-- Barra de navegación fija -->
   <div class="wp-alp-airbnb-footer">
       <!-- Barra de progreso completa -->
       <div class="wp-alp-airbnb-progress-bar">
           <div class="wp-alp-airbnb-progress-completed" style="width: 100%;"></div>
       </div>
       
       <!-- Botones de navegación -->
       <div class="wp-alp-airbnb-nav">
           <a href="#" class="wp-alp-airbnb-back-btn" id="back-to-step-3-intro-btn">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
           </a>
           <button type="button" class="wp-alp-airbnb-next-btn" id="submit-listing-btn" disabled>
               <?php echo esc_html(get_locale() == 'en_US' ? 'Publish Listing' : 'Publicar Anuncio'); ?>
           </button>
       </div>
   </div>
</div>

<!-- Paso Final: Confirmación -->
<div class="wp-alp-form-step" id="step-success" data-step="success" style="display: none;">
   <div class="wp-alp-success-container">
       <div class="wp-alp-success-icon">
           <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 80px; height: 80px; fill: #00a699;">
               <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
           </svg>
       </div>
       <h1 class="wp-alp-success-title">
           <?php echo esc_html(get_locale() == 'en_US' ? 'Congratulations!' : '¡Felicidades!'); ?>
       </h1>
       <p class="wp-alp-success-message">
           <?php echo esc_html(get_locale() == 'en_US' ? 'Your listing has been successfully created and published.' : 'Tu anuncio ha sido creado y publicado exitosamente.'); ?>
       </p>
       <div class="wp-alp-success-actions">
           <a href="#" id="view-listing-btn" class="wp-alp-btn wp-alp-btn-primary">
               <?php echo esc_html(get_locale() == 'en_US' ? 'View Listing' : 'Ver Anuncio'); ?>
           </a>
           <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-btn wp-alp-btn-secondary">
               <?php echo esc_html(get_locale() == 'en_US' ? 'Go to Homepage' : 'Ir al Inicio'); ?>
           </a>
       </div>
   </div>
</div>

           <!-- Aquí podrían ir más pasos... -->
       </div>
   </div>
</div>

<!-- Estilos adicionales para los nuevos elementos -->
<style>
.wp-alp-photo-upload-container {
   margin: 40px auto;
   max-width: 800px;
}

.wp-alp-photo-upload-zone {
   border: 2px dashed #ddd;
   border-radius: 12px;
   padding: 60px 40px;
   text-align: center;
   transition: all 0.3s ease;
   cursor: pointer;
}

.wp-alp-photo-upload-zone:hover {
   border-color: #222;
   background-color: #f7f7f7;
}

.wp-alp-photo-upload-zone h2 {
   font-size: 24px;
   font-weight: 600;
   color: #222;
   margin: 16px 0 8px;
}

.wp-alp-photo-upload-zone p {
   color: #717171;
   margin-bottom: 24px;
}

.wp-alp-upload-button {
   display: inline-block;
   background-color: #fff;
   color: #222;
   border: 1px solid #222;
   padding: 14px 24px;
   border-radius: 8px;
   font-size: 16px;
   font-weight: 600;
   cursor: pointer;
   transition: all 0.2s ease;
}

.wp-alp-upload-button:hover {
   background-color: #222;
   color: #fff;
}

.wp-alp-photos-preview {
   display: grid;
   grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
   gap: 16px;
   margin-top: 40px;
}

.wp-alp-photo-preview-item {
   position: relative;
   border-radius: 8px;
   overflow: hidden;
}

.wp-alp-photo-preview-item img {
   width: 100%;
   height: 200px;
   object-fit: cover;
}

.wp-alp-photo-remove-btn {
   position: absolute;
   top: 8px;
   right: 8px;
   background: white;
   border: none;
   border-radius: 50%;
   width: 28px;
   height: 28px;
   cursor: pointer;
   display: flex;
   align-items: center;
   justify-content: center;
   box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.wp-alp-repeater-container {
   margin-top: 16px;
}

.wp-alp-repeater-item {
   background: #f7f7f7;
   border-radius: 8px;
   padding: 16px;
   margin-bottom: 12px;
   position: relative;
}

.wp-alp-repeater-item .wp-alp-remove-item {
   position: absolute;
   top: 8px;
   right: 8px;
   background: none;
   border: none;
   color: #222;
   cursor: pointer;
   font-size: 18px;
}

.wp-alp-add-item-btn {
   background: none;
   border: 1px dashed #222;
   color: #222;
   padding: 12px 24px;
   border-radius: 8px;
   cursor: pointer;
   font-weight: 600;
   transition: all 0.2s ease;
}

.wp-alp-add-item-btn:hover {
   background: #f7f7f7;
}

.wp-alp-checkbox-grid {
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
   gap: 16px;
}

.wp-alp-checkbox-item {
   display: flex;
   align-items: center;
   cursor: pointer;
}

.wp-alp-checkbox-item input[type="checkbox"] {
   margin-right: 12px;
   width: 20px;
   height: 20px;
}

.wp-alp-tags-select {
   margin-top: 12px;
}

.wp-alp-success-container {
   text-align: center;
   padding: 60px 20px;
   max-width: 600px;
   margin: 0 auto;
}

.wp-alp-success-icon {
   margin-bottom: 24px;
}

.wp-alp-success-title {
   font-size: 36px;
   font-weight: 600;
   color: #222;
   margin-bottom: 16px;
}

.wp-alp-success-message {
   font-size: 18px;
   color: #717171;
   margin-bottom: 40px;
}

.wp-alp-success-actions {
   display: flex;
   gap: 16px;
   justify-content: center;
}

.wp-alp-review-container {
   max-width: 800px;
   margin: 0 auto;
}

.wp-alp-review-summary {
   background: #f7f7f7;
   border-radius: 12px;
   padding: 24px;
   margin-bottom: 32px;
}

.wp-alp-terms-section {
   margin-top: 24px;
   padding-top: 24px;
   border-top: 1px solid #ddd;
}

.wp-alp-video-section {
   margin-top: 60px;
   padding-top: 40px;
   border-top: 1px solid #ddd;
}

.wp-alp-video-section h3 {
   font-size: 24px;
   font-weight: 600;
   color: #222;
   margin-bottom: 24px;
}
</style>

<!-- JavaScript para la navegación mejorada -->
<script>
// Definir una función global que será llamada cuando Google Maps esté cargado
function googleMapsCallback() {
   // Indicar que Google Maps está listo
   window.googleMapsLoaded = true;
   console.log("Google Maps API cargada correctamente");
}

jQuery(document).ready(function($) {
   // Variables para la navegación
   var currentStep = 0;
   var totalSteps = 3; // Total de pasos implementados
   var selectedLocation = null;
   var isExactLocation = false;
   var map, marker, circle, geocoder, placesService;
   var selectedCategory = null;
   var selectedServiceType = null;
   var listingId = null;
   var uploadedImages = [];
   
   // Elementos del DOM
   var $steps = $('.wp-alp-form-step');
   var $hiddenForm = $('#wp-alp-hidden-hivepress-form');

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
            } else if (event.state.substep === 'service-type') {
                // Mostrar el subpaso de tipo de servicio
                $steps.hide();
                $('#step-1-service-type').show();
            } else if (event.state.substep === 'location') {
                // Mostrar el subpaso de ubicación
                $steps.hide();
                $('#step-1-location').show();
            } else if (event.state.substep === 'basic-info') {
                // Mostrar el subpaso de datos básicos
                $steps.hide();
                $('#step-1-basic-info').show();
                
                // Actualizar campos según la categoría y tipo de servicio
                updateBasicInfoFields();
            } else if (typeof event.state.step !== 'undefined') {
                goToStep(event.state.step);
            }
        } else {
            goToStep(0);
        }
    };
   
   // Función para actualizar campos del formulario oculto
   function updateHiddenFormField(fieldName, value) {
       var $field = $hiddenForm.find('[name="' + fieldName + '"]');
       if ($field.length) {
           if ($field.is(':checkbox')) {
               $field.prop('checked', value);
           } else if ($field.is('select')) {
               $field.val(value).trigger('change');
           } else {
               $field.val(value);
           }
       }
   }
   
   // Función para sincronizar campos múltiples (checkboxes)
   function updateMultipleCheckboxes(fieldName, values) {
       $hiddenForm.find('[name="' + fieldName + '"]').each(function() {
           var $checkbox = $(this);
           var checkboxValue = $checkbox.val();
           $checkbox.prop('checked', values.includes(checkboxValue));
       });
   }
   
   // Manejadores de cambio para sincronizar con el formulario oculto
   
   // Paso 2.1: Información básica
   $('#listing-title').on('change blur', function() {
       updateHiddenFormField('title', $(this).val());
   });
   
   $('#listing-price').on('change blur', function() {
       updateHiddenFormField('price', $(this).val());
   });
   
   $('#listing-description').on('change blur', function() {
       updateHiddenFormField('description', $(this).val());
   });
   
   // Paso 2.2: Fotos
   $('#photo-input').on('change', function(e) {
       var files = e.target.files;
       if (files.length > 0) {
           // Aquí manejarías la carga de archivos
           // Por ahora, solo mostramos una vista previa
           Array.from(files).forEach(function(file) {
               if (file.type.startsWith('image/')) {
                   var reader = new FileReader();
                   reader.onload = function(e) {
                       var preview = $('<div class="wp-alp-photo-preview-item">' +
                           '<img src="' + e.target.result + '" alt="Preview">' +
                           '<button type="button" class="wp-alp-photo-remove-btn">×</button>' +
                           '</div>');
                       $('#photos-preview').append(preview);
                   };
                   reader.readAsDataURL(file);
               }
           });
           
           // Actualizar el campo de imágenes del formulario oculto
           // Esto requeriría implementación adicional para manejar archivos
       }
   });
   
   $('#video-url').on('change blur', function() {
       updateHiddenFormField('video', $(this).val());
   });
   
   // Paso 2.3: Precios
   $('#add-daily-price').on('click', function() {
       var template = '<div class="wp-alp-repeater-item">' +
           '<button type="button" class="wp-alp-remove-item">×</button>' +
           '<select name="daily_days[]" class="wp-alp-form-select" style="margin-bottom: 12px;">' +
               '<option value="0">' + (get_locale() == 'en_US' ? 'Sunday' : 'Domingo') + '</option>' +
               '<option value="1">' + (get_locale() == 'en_US' ? 'Monday' : 'Lunes') + '</option>' +
               '<option value="2">' + (get_locale() == 'en_US' ? 'Tuesday' : 'Martes') + '</option>' +
               '<option value="3">' + (get_locale() == 'en_US' ? 'Wednesday' : 'Miércoles') + '</option>' +
               '<option value="4">' + (get_locale() == 'en_US' ? 'Thursday' : 'Jueves') + '</option>' +
               '<option value="5">' + (get_locale() == 'en_US' ? 'Friday' : 'Viernes') + '</option>' +
               '<option value="6">' + (get_locale() == 'en_US' ? 'Saturday' : 'Sábado') + '</option>' +
           '</select>' +
           '<input type="number" name="daily_price[]" placeholder="' + (get_locale() == 'en_US' ? 'Price' : 'Precio') + '" class="wp-alp-form-input" step="0.01" min="0">' +
           '</div>';
       $(this).before(template);
   });
   
   // Delegación de eventos para remover items dinámicos
   $(document).on('click', '.wp-alp-remove-item', function() {
       $(this).closest('.wp-alp-repeater-item').remove();
   });
   
   // Paso 2.4: Disponibilidad
   $('#booking-min-time').on('change blur', function() {
       updateHiddenFormField('booking_min_time', $(this).val());
   });
   
   $('#booking-max-time').on('change blur', function() {
       updateHiddenFormField('booking_max_time', $(this).val());
   });
   
   $('#booking-slot-duration').on('change blur', function() {
       updateHiddenFormField('booking_slot_duration', $(this).val());
   });
   
   $('#booking-offset').on('change blur', function() {
       updateHiddenFormField('booking_offset', $(this).val());
   });
   
   $('#booking-window').on('change blur', function() {
       updateHiddenFormField('booking_window', $(this).val());
   });
   
   $('#booking-moderated').on('change', function() {
       updateHiddenFormField('booking_moderated', $(this).is(':checked') ? 1 : 0);
   });
   
   // Paso 2.5: Características
   $('input[name="service_features_even[]"]').on('change', function() {
       var checkedValues = [];
       $('input[name="service_features_even[]"]:checked').each(function() {
           checkedValues.push($(this).val());
       });
       updateMultipleCheckboxes('service_features_even[]', checkedValues);
   });
   
   $('#tags-select').on('change', function() {
       updateHiddenFormField('tags[]', $(this).val());
   });
   
   $('#purchase-note').on('change blur', function() {
       updateHiddenFormField('purchase_note', $(this).val());
   });
   
   // Paso 2.6: Contacto
   $('#contact-information').on('change blur', function() {
       updateHiddenFormField('contact_information_provider', $(this).val());
   });
   
   $('#whatsapp-url').on('change blur', function() {
       updateHiddenFormField('whatsapp_url_provider', $(this).val());
   });
   
   // Navegación del paso 1 (código existente)
   // ... [mantener todo el código de navegación existente del paso 1] ...
   
   // Navegación del paso 2
   $('#go-to-step-2-listing-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-2-intro').hide();
       $('#step-2-basic').show();
       $('html, body').scrollTop(0);
   });
   
   $('#back-to-step-1-last-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-2-intro').hide();
       $('#step-1-basic-info').show();
       $('html, body').scrollTop(0);
   });
   
   $('#next-to-photos-btn').on('click', function(e) {
       e.preventDefault();
       
       // Validar campos requeridos
       var title = $('#listing-title').val().trim();
       var price = $('#listing-price').val();
       var description = $('#listing-description').val().trim();
       
       if (!title || !price || !description) {
           alert(get_locale() == 'en_US' ? 'Please fill in all required fields.' : 'Por favor, completa todos los campos requeridos.');
           return;
       }
       
       $('#step-2-basic').hide();
       $('#step-2-photos').show();
       $('html, body').scrollTop(0);
   });
   
   $('#back-to-step-2-intro-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-2-basic').hide();
       $('#step-2-intro').show();
       $('html, body').scrollTop(0);
   });
   
   $('#next-to-pricing-btn').on('click', function(e) {
       e.preventDefault();
       
       // Validar que haya al menos 5 fotos
       var photoCount = $('#photos-preview .wp-alp-photo-preview-item').length;
       if (photoCount < 5) {
           alert(get_locale() == 'en_US' ? 'Please upload at least 5 photos.' : 'Por favor, sube al menos 5 fotos.');
           return;
       }
       
       $('#step-2-photos').hide();
       $('#step-2-pricing').show();
       $('html, body').scrollTop(0);
   });
   
   $('#back-to-basic-info-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-2-photos').hide();
       $('#step-2-basic').show();
       $('html, body').scrollTop(0);
   });
   
   $('#next-to-availability-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-2-pricing').hide();
       $('#step-2-availability').show();
       $('html, body').scrollTop(0);
   });
   
   $('#back-to-photos-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-2-pricing').hide();
       $('#step-2-photos').show();
       $('html, body').scrollTop(0);
   });
   
   $('#next-to-features-btn').on('click', function(e) {
       e.preventDefault();
       
       // Validar campos requeridos
       var minTime = $('#booking-min-time').val();
       var maxTime = $('#booking-max-time').val();
       var slotDuration = $('#booking-slot-duration').val();
       
       if (!minTime || !maxTime || !slotDuration) {
           alert(get_locale() == 'en_US' ? 'Please fill in all required fields.' : 'Por favor, completa todos los campos requeridos.');
           return;
       }
       
       $('#step-2-availability').hide();
       $('#step-2-features').show();
       $('html, body').scrollTop(0);
   });
   
   $('#back-to-pricing-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-2-availability').hide();
       $('#step-2-pricing').show();
       $('html, body').scrollTop(0);
   });
   
   $('#next-to-contact-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-2-features').hide();
       $('#step-2-contact').show();
       $('html, body').scrollTop(0);
   });
   
   $('#back-to-availability-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-2-features').hide();
       $('#step-2-availability').show();
       $('html, body').scrollTop(0);
   });
   
   $('#go-to-step-3-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-2-contact').hide();
       $('#step-3').show();
       $('html, body').scrollTop(0);
   });
   
   $('#back-to-features-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-2-contact').hide();
       $('#step-2-features').show();
       $('html, body').scrollTop(0);
   });
   
   // Navegación del paso 3
   $('#go-to-review-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-3').hide();
       $('#step-3-review').show();
       generateReviewSummary();
       $('html, body').scrollTop(0);
   });
   
   $('#back-to-step-2-last-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-3').hide();
       $('#step-2-contact').show();
       $('html, body').scrollTop(0);
   });
   
   $('#back-to-step-3-intro-btn').on('click', function(e) {
       e.preventDefault();
       $('#step-3-review').hide();
       $('#step-3').show();
       $('html, body').scrollTop(0);
   });
   
   // Habilitar botón de publicar cuando se aceptan términos
   $('#terms-checkbox').on('change', function() {
       $('#submit-listing-btn').prop('disabled', !$(this).is(':checked'));
   });
   
   // Enviar el formulario
   $('#submit-listing-btn').on('click', function(e) {
       e.preventDefault();
       
       // Enviar el formulario oculto de HivePress
       var $form = $hiddenForm.find('form');
       if ($form.length) {
           // Simular el envío del formulario
           $.ajax({
               url: $form.attr('action'),
               type: 'POST',
               data: $form.serialize(),
               success: function(response) {
                   // Mostrar pantalla de éxito
                   $('#step-3-review').hide();
                   $('#step-success').show();
                   $('html, body').scrollTop(0);
               },
               error: function(xhr, status, error) {
                   alert(get_locale() == 'en_US' ? 'An error occurred while publishing your listing.' : 'Ocurrió un error al publicar tu anuncio.');
               }
           });
       }
   });
   
   // Función para generar el resumen de revisión
   function generateReviewSummary() {
       var summary = '<div class="wp-alp-review-sections">';
       
       // Información básica
       summary += '<div class="wp-alp-review-section">';
       summary += '<h3>' + (get_locale() == 'en_US' ? 'Basic Information' : 'Información Básica') + '</h3>';
       summary += '<p><strong>' + (get_locale() == 'en_US' ? 'Title:' : 'Título:') + '</strong> ' + $('#listing-title').val() + '</p>';
       summary += '<p><strong>' + (get_locale() == 'en_US' ? 'Price:' : 'Precio:') + '</strong> $' + $('#listing-price').val() + '</p>';
       summary += '</div>';
       
       // Más secciones del resumen...
       
       summary += '</div>';
       
       $('#listing-review-summary').html(summary);
   }

   // Función para actualizar la función updateBasicInfoFields
   function updateBasicInfoFields() {
        // Si es servicio por hora, mostrar campo de horas
        if (selectedServiceType === 'hour') {
            $('.hour-service-field').show();
        } else {
            $('.hour-service-field').hide();
        }
    }
   
   // Zona de arrastre para fotos
   $('#photo-upload-zone').on('click', function() {
       $('#photo-input').click();
   });
   
   $('#photo-upload-zone').on('dragover', function(e) {
       e.preventDefault();
       e.stopPropagation();
       $(this).css('border-color', '#222');
   });
   
   $('#photo-upload-zone').on('dragleave', function(e) {
       e.preventDefault();
       e.stopPropagation();
       $(this).css('border-color', '#ddd');
   });
   
   $('#photo-upload-zone').on('drop', function(e) {
       e.preventDefault();
       e.stopPropagation();
       $(this).css('border-color', '#ddd');
       
       var files = e.originalEvent.dataTransfer.files;
       $('#photo-input')[0].files = files;
       $('#photo-input').trigger('change');
   });
   
   // Remover fotos
   $(document).on('click', '.wp-alp-photo-remove-btn', function() {
       $(this).closest('.wp-alp-photo-preview-item').remove();
   });
   
   // Controles numéricos mejorados (ya existentes)
   $('.wp-alp-number-increase-improved').on('click', function() {
       var $input = $(this).siblings('input');
       var max = parseInt($input.attr('max')) || 9999;
       var currentVal = parseInt($input.val()) || 0;
       
       if (currentVal < max) {
           $input.val(currentVal + 1);
           $input.trigger('change');
       }
   });
   
   $('.wp-alp-number-decrease-improved').on('click', function() {
       var $input = $(this).siblings('input');
       var min = parseInt($input.attr('min')) || 0;
       var currentVal = parseInt($input.val()) || 0;
       
       if (currentVal > min) {
           $input.val(currentVal - 1);
           $input.trigger('change');
       }
   });
   
   // Función auxiliar para detectar el idioma
   function get_locale() {
       return $('html').attr('lang') || 'es_ES';
   }
   
   // Inicialización
   init();
   
   function init() {
       // Configurar el formulario oculto
       if ($hiddenForm.find('form').length) {
           // Prevenir el envío normal del formulario
           $hiddenForm.find('form').on('submit', function(e) {
               e.preventDefault();
           });
       }
       
       // Otras inicializaciones...
   }

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
        } else if (substepParam === 'service-type' && stepNum === 1) {
            // Mostrar el subpaso de tipo de servicio
            $steps.hide();
            $('#step-1-service-type').show();
        } else if (substepParam === 'location' && stepNum === 1) {
            // Mostrar el subpaso de ubicación
            $steps.hide();
            $('#step-1-location').show();
        } else if (substepParam === 'basic-info' && stepNum === 1) {
            // Mostrar el subpaso de datos básicos
            $steps.hide();
            $('#step-1-basic-info').show();
            // Actualizar campos según la categoría y tipo de servicio
            updateBasicInfoFields();
        } else {
            goToStep(stepNum);
        }
    } else {
        // Si no hay parámetro de paso, iniciar en el paso 0 (visión general)
        goToStep(0);
    }
});
</script>

<?php get_footer(); ?>