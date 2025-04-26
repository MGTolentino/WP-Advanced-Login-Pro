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
                            <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-form-step1.png'); ?>" alt="Describe your service">
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
            <div class="wp-alp-airbnb-progress-completed" style="width: 60%;"></div>
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
            <div class="wp-alp-airbnb-progress-completed" style="width: 80%;"></div>
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

            <!-- Aquí podrían ir más pasos... -->
        </div>
    </div>
</div>

<!-- JavaScript para la navegación -->
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
            var selectedCategory = $('.wp-alp-airbnb-category-item.selected');
            var selectedName = selectedCategory.data('name');
            console.log('Categoría seleccionada: ' + selectedName);
            
            // En lugar de mostrar alert, ocultamos paso actual y mostramos el siguiente
            $('#step-1-categories').hide();
            $('#step-1-service-type').show();
            
            // Actualizar URL
            var currentUrl = window.location.pathname;
            var newUrl = currentUrl + '?step=1&substep=service-type';
            history.pushState({step: 1, substep: 'service-type'}, '', newUrl);
            
            // Desplazarse al inicio de la página
            $('html, body').scrollTop(0);
        } else {
            // Si no hay categoría seleccionada, mostrar mensaje pero no alert
            $('.wp-alp-airbnb-category-validation').fadeIn();
        }
    });
    
    // Selección de categorías
    $('.wp-alp-airbnb-category-item').on('click', function() {
        $('.wp-alp-airbnb-category-item').removeClass('selected');
        $(this).addClass('selected');
    });
    
    // Selección de tipo de servicio
    $('.wp-alp-airbnb-service-option').on('click', function() {
        $('.wp-alp-airbnb-service-option').removeClass('selected');
        $(this).addClass('selected');
        // Ocultar mensaje de validación si estaba visible
        $('.wp-alp-airbnb-service-validation').hide();
    });
    
    // Botón de volver desde tipo de servicio a categorías
    $('#back-to-categories-btn').on('click', function(e) {
        e.preventDefault();
        $('#step-1-service-type').hide();
        $('#step-1-categories').show();
        
        // Actualizar URL
        var currentUrl = window.location.pathname;
        var newUrl = currentUrl + '?step=1&substep=categories';
        history.pushState({step: 1, substep: 'categories'}, '', newUrl);
        
        // Desplazarse al inicio de la página
        $('html, body').scrollTop(0);
    });
    
    // Botón para ir a la ubicación desde el tipo de servicio
    $('#next-from-service-type-btn').on('click', function(e) {
        e.preventDefault();
        // Verificar si hay un tipo de servicio seleccionado
        if ($('.wp-alp-airbnb-service-option.selected').length > 0) {
            var selectedService = $('.wp-alp-airbnb-service-option.selected');
            var selectedValue = selectedService.data('value');
            console.log('Tipo de servicio seleccionado: ' + selectedValue);
            
            // Ocultar paso actual y mostrar paso de ubicación
            $('#step-1-service-type').hide();
            $('#step-1-location').show();
            
            // Actualizar URL
            var currentUrl = window.location.pathname;
            var newUrl = currentUrl + '?step=1&substep=location';
            history.pushState({step: 1, substep: 'location'}, '', newUrl);
            
            // Desplazarse al inicio de la página
            $('html, body').scrollTop(0);
        } else {
            // Mostrar mensaje de validación
            $('.wp-alp-airbnb-service-validation').fadeIn();
        }
    });
    
    // Botón para volver al tipo de servicio desde la ubicación
    $('#back-to-service-type-btn').on('click', function(e) {
        e.preventDefault();
        $('#step-1-location').hide();
        $('#step-1-service-type').show();
        
        // Actualizar URL
        var currentUrl = window.location.pathname;
        var newUrl = currentUrl + '?step=1&substep=service-type';
        history.pushState({step: 1, substep: 'service-type'}, '', newUrl);
        
        // Desplazarse al inicio de la página
        $('html, body').scrollTop(0);
    });
    
    // Selección de tipo de ubicación
    $('.wp-alp-location-option').on('click', function() {
        var $this = $(this);
        var option = $this.data('option');
        
        // Actualizar selección visual
        $('.wp-alp-location-option').removeClass('selected');
        $this.addClass('selected');
        
        // Marcar el radio button
        $('#location-' + option).prop('checked', true);
        
        // Mostrar el contenedor correspondiente
        if (option === 'specific') {
            $('.wp-alp-location-specific-container').show();
            $('.wp-alp-location-multiple-container').hide();
            
            // Inicializar el mapa si existe la función
            if (typeof initMap === 'function') {
                setTimeout(function() {
                    initMap();
                    
                    // Crear el elemento de círculo rojo si no existe
                    if ($('.wp-alp-location-circle').length === 0) {
                        $('.wp-alp-map-container').append('<div class="wp-alp-location-circle"></div>');
                    }
                    
                    // Mostrar elementos correctos
                    $('.wp-alp-house-marker, .wp-alp-approximate-tooltip').show();
                }, 100);
            }
        } else if (option === 'multiple') {
            $('.wp-alp-location-specific-container').hide();
            $('.wp-alp-location-multiple-container').show();
        }
        
        // Ocultar mensaje de validación si estaba visible
        $('.wp-alp-location-validation').hide();
    });
    
    // Manejar la opción "Otro"
    $('#location-other').on('change', function() {
        if ($(this).is(':checked')) {
            $('.wp-alp-location-other-input').show();
        } else {
            $('.wp-alp-location-other-input').hide();
        }
    });
    
    // Manejar el toggle de ubicación exacta
    $('#exact-location-toggle').on('change', function() {
        isExactLocation = $(this).is(':checked');
        
        if (isExactLocation) {
            // Cambiar a ubicación exacta
            $('#approximate-tooltip').fadeOut(200);
            if (selectedLocation && selectedLocation.geometry) {
                updateLocationDisplay(selectedLocation.geometry.location);
            } else if (map) {
                updateLocationDisplay(map.getCenter());
            }
        } else {
            // Cambiar a ubicación aproximada
            $('#approximate-tooltip').fadeIn(200);
            if (selectedLocation && selectedLocation.geometry) {
                updateLocationDisplay(selectedLocation.geometry.location);
            } else if (map) {
                updateLocationDisplay(map.getCenter());
            }
        }
    });
    
    // Botón para confirmar dirección y mostrar el formulario detallado
    $('#confirm-address-btn').on('click', function() {
        // Ocultar la vista del mapa
        $('.wp-alp-location-specific-container').hide();
        
        // Mostrar el formulario de dirección detallada
        $('#address-form-container').show();
        
        // Desplazarse al inicio del contenedor
        $('html, body').animate({
            scrollTop: $('#address-form-container').offset().top - 100
        }, 300);
    });
    
    // Botón para volver al mapa desde el formulario detallado
    $('#back-to-map-btn').on('click', function() {
        // Ocultar el formulario
        $('#address-form-container').hide();
        
        // Mostrar la vista del mapa
        $('.wp-alp-location-specific-container').show();
        
        // Desplazarse al inicio del contenedor
        $('html, body').animate({
            scrollTop: $('.wp-alp-location-specific-container').offset().top - 100
        }, 300);
    });
    
    // Botón para guardar la dirección y continuar
    $('#save-address-btn').on('click', function() {
        // Validar que los campos requeridos estén completos
        var street = $('#street').val().trim();
        var city = $('#city').val().trim();
        var zipcode = $('#zipcode').val().trim();
        
        if (!street || !city || !zipcode) {
            alert('Por favor completa los campos obligatorios: Dirección, Ciudad y Código Postal.');
            return;
        }
        
        // Recopilar todos los datos del formulario
        var addressData = {
            country: $('#country').val(),
            street: street,
            apt: $('#apt').val().trim(),
            neighborhood: $('#neighborhood').val().trim(),
            zipcode: zipcode,
            city: city,
            state: $('#state').val(),
            isExactLocation: isExactLocation
        };
        
        // Guardar los datos (puedes usar localStorage o enviarlos mediante AJAX)
        console.log('Datos de dirección guardados:', addressData);
        
        // Continuar al siguiente paso
        goToNextStep();
    });
    
    // Botón de siguiente desde la ubicación
    $('#next-from-location-btn').on('click', function(e) {
        e.preventDefault();
        
        // Verificar qué tipo de ubicación se ha seleccionado
        var isSpecificLocation = $('#location-specific').is(':checked');
        var isMultipleLocation = $('#location-multiple').is(':checked');
        
        if (!isSpecificLocation && !isMultipleLocation) {
            // No se ha seleccionado un tipo de ubicación
            $('.wp-alp-location-validation').fadeIn();
            return;
        }
        
        // Si se seleccionó ubicación específica y hay datos de dirección guardados
        if (isSpecificLocation) {
            // Verificar si se completó el formulario detallado
            if ($('#address-form-container').is(':visible')) {
                // Validar campos requeridos
                var street = $('#street').val().trim();
                var city = $('#city').val().trim();
                var zipcode = $('#zipcode').val().trim();
                
                if (!street || !city || !zipcode) {
                    alert('Por favor completa los campos obligatorios: Dirección, Ciudad y Código Postal.');
                    return;
                }
                
                // Recopilar todos los datos del formulario
                var addressData = {
                    country: $('#country').val(),
                    street: street,
                    apt: $('#apt').val().trim(),
                    neighborhood: $('#neighborhood').val().trim(),
                    zipcode: zipcode,
                    city: city,
                    state: $('#state').val(),
                    isExactLocation: isExactLocation
                };
                
                // Guardar los datos (puedes usar localStorage o enviarlos mediante AJAX)
                console.log('Datos de dirección guardados:', addressData);
                
                // Continuar al siguiente paso
                goToNextStep();
            } else {
                // Verificar si se ha ingresado una dirección
                var address = $('#wp-alp-address-input').val().trim();
                if (!address || !selectedLocation) {
                    $('.wp-alp-location-validation').fadeIn();
                    return;
                }
                
                // Mostrar formulario detallado de dirección
                $('.wp-alp-location-specific-container').hide();
                $('#address-form-container').show();
                
                // Desplazarse al inicio del contenedor
                $('html, body').animate({
                    scrollTop: $('#address-form-container').offset().top - 100
                }, 300);
            }
        } else if (isMultipleLocation) {
            // Verificar selección para ubicaciones múltiples
            var hasChecked = $('.wp-alp-locations-checkboxes input:checked').length > 0 || $('#location-other').is(':checked');
            if (!hasChecked) {
                $('.wp-alp-location-validation').fadeIn();
                return;
            }
            
            // Si "Otro" está marcado, verificar que se haya ingresado texto
            if ($('#location-other').is(':checked')) {
                var otherLocation = $('#wp-alp-other-location').val().trim();
                if (!otherLocation) {
                    $('.wp-alp-location-validation').fadeIn();
                    return;
                }
            }
            
            // Recopilar datos de ubicaciones múltiples
            var selectedLocations = [];
            $('.wp-alp-locations-checkboxes input:checked').each(function() {
                selectedLocations.push($(this).val());
            });
            
            if ($('#location-other').is(':checked')) {
                selectedLocations.push('other: ' + $('#wp-alp-other-location').val().trim());
            }
            
            // Guardar los datos
            console.log('Ubicaciones múltiples seleccionadas:', selectedLocations);
            
            // Continuar al siguiente paso
            goToNextStep();
        }
    });
    
    // Función para ir al siguiente paso
    function goToNextStep() {
        // Ocultar paso actual
        $('#step-1-location').hide();
        
        // Aquí deberías mostrar el siguiente paso
        // $('#step-1-next-page').show();
        
        // Por ahora, solo mostramos un mensaje
        alert('Ubicación guardada correctamente. Continuaríamos al siguiente paso.');
        
        // Actualizar URL
        var currentUrl = window.location.pathname;
        var newUrl = currentUrl + '?step=1&substep=next-page'; // Cambiar a tu siguiente subpaso
        history.pushState({step: 1, substep: 'next-page'}, '', newUrl);
        
        // Desplazarse al inicio de la página
        $('html, body').scrollTop(0);
    }
    
    // Función para actualizar la visualización de ubicación (exacta o aproximada)
    function updateLocationDisplay(location) {
        if (isExactLocation) {
            // Mostrar el marcador exacto
            marker.setPosition(location);
            marker.setVisible(true);
            circle.setVisible(false);
        } else {
            // Mostrar el círculo de ubicación aproximada
            circle.setCenter(location);
            circle.setVisible(true);
            marker.setVisible(false);
        }
    }
    
    // Función para extraer componentes de dirección para el formulario
    function extractAddressComponents(place) {
        // Reiniciar todos los campos
        $('#street, #apt, #neighborhood, #zipcode, #city').val('');
        
        // Si no hay componentes de dirección, salir
        if (!place.address_components) return;
        
        // Mapeo de tipos de componentes a campos del formulario
        var componentMapping = {
            street_number: 'street_number',
            route: 'street_name',
            sublocality_level_1: 'neighborhood',
            locality: 'city',
            administrative_area_level_1: 'state',
            country: 'country',
            postal_code: 'zipcode'
        };
        
        // Datos extraídos
        var extractedData = {};
        
        // Recorrer componentes y extraer información
        place.address_components.forEach(function(component) {
            component.types.forEach(function(type) {
                if (componentMapping[type]) {
                    extractedData[componentMapping[type]] = component.long_name;
                }
            });
        });
        
        // Combinar número de calle y nombre de calle para la dirección
        if (extractedData.street_number && extractedData.street_name) {
            $('#street').val(extractedData.street_number + ' ' + extractedData.street_name);
        } else if (extractedData.street_name) {
            $('#street').val(extractedData.street_name);
        }
        
        // Completar el resto de campos
        if (extractedData.neighborhood) $('#neighborhood').val(extractedData.neighborhood);
        if (extractedData.zipcode) $('#zipcode').val(extractedData.zipcode);
        if (extractedData.city) $('#city').val(extractedData.city);
        
        // Seleccionar país y estado en los selectores si existen
        if (extractedData.country) {
            $('#country option').each(function() {
                if ($(this).text().indexOf(extractedData.country) !== -1) {
                    $(this).prop('selected', true);
                }
            });
        }
        
        if (extractedData.state) {
            $('#state option').each(function() {
                if ($(this).text().indexOf(extractedData.state) !== -1) {
                    $(this).prop('selected', true);
                }
            });
        }
    }
    
    // Inicializar el mapa
    function initMap() {
        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
            try {

            // Coordenadas por defecto (puedes cambiarlas)
            var defaultLocation = {lat: 25.6866, lng: -100.3161};
            
            // Inicializar el mapa
            map = new google.maps.Map(document.getElementById('wp-alp-location-map'), {
                center: defaultLocation,
                zoom: 13,
                mapTypeControl: false,
                fullscreenControl: false,
                streetViewControl: false,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_TOP
                }
            });

             // Verificar Places API explícitamente
             if (!google.maps.places) {
                    console.error("Error: Google Maps Places API no está disponible");
                    return;
                }
            
            // Inicializar geocoder y servicios de lugares
            geocoder = new google.maps.Geocoder();
            placesService = new google.maps.places.PlacesService(map);
            
            // Crear marcador (inicialmente oculto)
            marker = new google.maps.Marker({
                map: map,
                position: defaultLocation,
                visible: false
            });
            
            // Crear círculo para ubicación aproximada
            circle = new google.maps.Circle({
                map: map,
                center: defaultLocation,
                radius: 500, // Radio en metros
                fillColor: '#E41D57',
                fillOpacity: 0.2,
                strokeColor: '#E41D57',
                strokeOpacity: 0.5,
                strokeWeight: 1
            });
            
            // Agregar el autocompletado para la dirección
            var input = document.getElementById('wp-alp-address-input');
            var autocomplete = new google.maps.places.Autocomplete(input);
            
            // Limitar las sugerencias a direcciones (no negocios)
            autocomplete.setTypes(['address']);
            
            // Bias hacia el área del mapa visible
            autocomplete.bindTo('bounds', map);
            
            // Manejar la selección de lugar
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                
                if (!place.geometry) {
                    window.alert("No hay detalles disponibles para: '" + place.name + "'");
                    return;
                }
                
                // Actualizar la ubicación seleccionada
                selectedLocation = place;
                
                // Centrar el mapa en la ubicación seleccionada
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                
                // Actualizar la posición del círculo y marcador
                updateLocationDisplay(place.geometry.location);
                
                // Mostrar el botón de confirmación
                $('.wp-alp-confirm-address-btn').show();
                
                // Extraer la información de la dirección para el formulario
                extractAddressComponents(place);
            });
            
            // Escuchar cambios en el mapa para actualizar la ubicación
            map.addListener('center_changed', function() {
                // Ocultar temporalmente el tooltip de ubicación aproximada
                $('#approximate-tooltip').fadeOut(200);
                
                // Actualizar la posición del círculo y marcador después de un retraso
                clearTimeout(map.centerChangedTimeout);
                map.centerChangedTimeout = setTimeout(function() {
                    var center = map.getCenter();
                    updateLocationDisplay(center);
                    
                    // Mostrar nuevamente el tooltip si corresponde
                    if (!isExactLocation) {
                        $('#approximate-tooltip').fadeIn(200);
                    }
                    
                    // Hacer geocoding inverso para obtener la dirección
                    geocoder.geocode({'location': center}, function(results, status) {
                        if (status === 'OK' && results[0]) {
                            selectedLocation = results[0];
                            extractAddressComponents(results[0]);
                            
                            // Actualizar el campo de dirección
                            $('#wp-alp-address-input').val(results[0].formatted_address);
                            
                            // Mostrar el botón de confirmación
                            $('.wp-alp-confirm-address-btn').show();
                        }
                    });
                }, 300);
            });
            
            // Mostrar el marcador inicial de casa
            $('#house-marker').show();
            
            // Mostrar el círculo de ubicación aproximada (inicialmente)
            circle.setVisible(true);  
        } catch (error) {
                console.error("Error al inicializar el mapa:", error);
            }
        } else {
            console.error("Google Maps API no está disponible");
        }
        }
    }
    
    // Cuando se selecciona la opción de ubicación específica
    $('#location-specific').on('change', function() {
        if ($(this).is(':checked')) {
            setTimeout(function() {
                initMap();
                
                // Crear el elemento de círculo rojo si no existe
                if ($('.wp-alp-location-circle').length === 0) {
                    $('.wp-alp-map-container').append('<div class="wp-alp-location-circle"></div>');
                }
                
                // Mostrar elementos correctos
                $('.wp-alp-house-marker, .wp-alp-approximate-tooltip').show();
            }, 100);
        }
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
            } else if (event.state.substep === 'service-type') {
                // Mostrar el subpaso de tipo de servicio
                $steps.hide();
                $('#step-1-service-type').show();
            } else if (event.state.substep === 'location') {
                // Mostrar el subpaso de ubicación
                $steps.hide();
                $('#step-1-location').show();
                
                // Reiniciar estado del formulario de dirección
                $('.wp-alp-location-specific-container').show();
                $('#address-form-container').hide();
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
        } else if (substepParam === 'service-type' && stepNum === 1) {
            // Mostrar el subpaso de tipo de servicio
            $steps.hide();
            $('#step-1-service-type').show();
        } else if (substepParam === 'location' && stepNum === 1) {
            // Mostrar el subpaso de ubicación
            $steps.hide();
            $('#step-1-location').show();
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