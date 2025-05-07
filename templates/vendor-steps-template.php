<?php
if (!defined('ABSPATH')) exit;
$vendor_steps_template = true;
$steps = array(
    'intro' => __('Introduction', 'wp-alp'),
    'category' => __('Category', 'wp-alp'),
    'service' => __('Service Type', 'wp-alp'),
    'location' => __('Location', 'wp-alp'),
    'details' => __('Details', 'wp-alp'),
    'facilities' => __('Facilities', 'wp-alp'),
    'pricing' => __('Pricing', 'wp-alp'),
    'legal' => __('Legal', 'wp-alp')
);

// Obtener la ruta de las imágenes
$plugin_url = WP_PLUGIN_URL . '/wp-advanced-login-pro/images/';

// Obtener opciones guardadas
$saved_vendor_data = get_user_meta(get_current_user_id(), 'vendor_data', true);
if (!is_array($saved_vendor_data)) {
    $saved_vendor_data = array();
}

// Define la información de los pasos (versión visual)
$visual_steps = array(
    array(
        'title' => __('Solicitud Básica', 'wp-alp'),
        'description' => __('Comparte información básica sobre ti y tu espacio', 'wp-alp'),
        'image' => $plugin_url . 'vendor-step1.png'
    ),
    array(
        'title' => __('Configura tu listado', 'wp-alp'),
        'description' => __('Agrega fotos y detalles que ayuden a los huéspedes a entender lo que ofreces', 'wp-alp'),
        'image' => $plugin_url . 'vendor-step2.png'
    ),
    array(
        'title' => __('Termina y publica', 'wp-alp'),
        'description' => __('Elije tus configuraciones de reserva, establece tu precio y publica tu listado', 'wp-alp'),
        'image' => $plugin_url . 'vendor-step3.png'
    )
);

// Definición del formulario
$form_definition = array(
    'facilities' => array(
        'type' => 'multiselect',
        'options' => array(
            'piscina' => __('Pool', 'wp-alp'),
            'gimnasio' => __('Gym', 'wp-alp'),
            'jardin' => __('Garden', 'wp-alp'),
            'parking' => __('Parking', 'wp-alp'),
            'wifi' => __('WiFi', 'wp-alp'),
            'kitchen' => __('Kitchen', 'wp-alp'),
            'laundry' => __('Laundry', 'wp-alp'),
            'bar' => __('Bar', 'wp-alp'),
            'aire_acondicionado' => __('Air conditioning', 'wp-alp'),
            'sauna' => __('Sauna', 'wp-alp'),
            'pets_allowed' => __('Pets allowed', 'wp-alp'),
            'bbq' => __('BBQ', 'wp-alp'),
            'other' => __('Other', 'wp-alp'),
        ),
        'required' => false
    ),
    'business-details' => array(
        'business_name' => array(
            'type' => 'text',
            'required' => true,
        ),
        'business_type' => array(
            'type' => 'select',
            'options' => array(
                'individual' => __('Individual', 'wp-alp'),
                'company' => __('Company', 'wp-alp'),
            ),
            'required' => true,
        ),
        'tax_id' => array(
            'type' => 'text',
            'required' => false,
        ),
        'contact_phone' => array(
            'type' => 'tel',
            'required' => true,
        ),
        'website' => array(
            'type' => 'url',
            'required' => false,
        ),
    ),
    'guests' => array(
        'guests' => array(
            'type' => 'number',
            'min' => 1,
            'max' => 16,
            'default' => 4,
            'required' => true,
        ),
        'bedrooms' => array(
            'type' => 'number',
            'min' => 1,
            'max' => 10,
            'default' => 1,
            'required' => true,
        ),
        'beds' => array(
            'type' => 'number',
            'min' => 1,
            'max' => 20,
            'default' => 1,
            'required' => true,
        ),
        'bathrooms' => array(
            'type' => 'number',
            'min' => 1,
            'max' => 10,
            'default' => 1,
            'required' => true,
        ),
    ),
    'capacity' => array(
        'capacity' => array(
            'type' => 'number',
            'min' => 1,
            'max' => 100,
            'default' => 50,
            'required' => true,
        ),
        'restrooms' => array(
            'type' => 'number',
            'min' => 1,
            'max' => 10,
            'default' => 2,
            'required' => true,
        ),
        'parking_spaces' => array(
            'type' => 'number',
            'min' => 0,
            'max' => 50,
            'default' => 5,
            'required' => true,
        ),
    ),
    'studio-details' => array(
        'studio_type' => array(
            'type' => 'select',
            'options' => array(
                'photo' => __('Photo Studio', 'wp-alp'),
                'music' => __('Music Studio', 'wp-alp'),
                'production' => __('Production Studio', 'wp-alp'),
            ),
            'required' => true,
        ),
        'size_sqft' => array(
            'type' => 'number',
            'min' => 100,
            'max' => 10000,
            'default' => 500,
            'required' => true,
        ),
        'equipment_included' => array(
            'type' => 'checkbox',
            'label' => __('Equipment Included', 'wp-alp'),
        ),
        'soundproof' => array(
            'type' => 'checkbox',
            'label' => __('Soundproof', 'wp-alp'),
        ),
    ),
    'pricing' => array(
        'pricing_model' => array(
            'type' => 'radio',
            'options' => array(
                'hourly' => __('Hourly', 'wp-alp'),
                'daily' => __('Daily', 'wp-alp'),
                'event' => __('Per Event', 'wp-alp'),
            ),
            'required' => true,
        ),
        'price' => array(
            'type' => 'number',
            'min' => 1,
            'step' => 1.00,
            'required' => true,
        ),
        'minimum_booking' => array(
            'type' => 'select',
            'options' => array(
                '1' => __('1 hour', 'wp-alp'),
                '2' => __('2 hours', 'wp-alp'),
                '3' => __('3 hours', 'wp-alp'),
                '4' => __('4 hours', 'wp-alp'),
            ),
            'required' => true,
        ),
    ),
);

function render_field($field_id, $field, $field_value = null) {
    $output = '';
    
    switch($field['type']) {
        case 'text':
        case 'email':
        case 'tel':
        case 'url':
            $output .= '<input type="' . esc_attr($field['type']) . '" 
                         name="' . esc_attr($field_id) . '" 
                         value="' . esc_attr($field_value) . '"
                         class="wp-alp-form-input"
                         ' . (isset($field['required']) && $field['required'] ? 'required' : '') . '>';
            break;
            
        case 'number':
            $output .= '<div class="wp-alp-number-control">';
            $output .= '<button type="button" class="wp-alp-number-decrease" aria-label="Decrease">
                          <span>−</span>
                        </button>';
            $output .= '<input type="number" 
                         id="' . esc_attr($field_id) . '"
                         name="' . esc_attr($field_id) . '"
                         min="' . esc_attr($field['min'] ?? 0) . '"
                         max="' . esc_attr($field['max'] ?? 100) . '"
                         value="' . esc_attr($field_value ?? $field['default'] ?? $field['min'] ?? 0) . '"
                         class="wp-alp-number-input">';
            $output .= '<button type="button" class="wp-alp-number-increase" aria-label="Increase">
                          <span>+</span>
                        </button>';
            $output .= '</div>';
            break;
            
        case 'select':
            $output .= '<select name="' . esc_attr($field_id) . '" class="wp-alp-form-select">';
            foreach($field['options'] as $key => $label) {
                $selected = ($field_value == $key) ? 'selected' : '';
                $output .= '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($label) . '</option>';
            }
            $output .= '</select>';
            break;
            
        case 'multiselect':
            $output .= '<div class="vendor-multiselect-container">';
            $output .= '<div class="vendor-multiselect-toggle" data-field="' . $field_id . '">
                <span class="vendor-multiselect-text">' . __('Selecciona opciones', 'wp-alp') . '</span>
                <span class="vendor-multiselect-arrow">▼</span>
            </div>';
            $output .= '<div class="vendor-multiselect-options" style="display: none;">';
            
            if (!is_array($field_value)) {
                $field_value = array();
            }
            
            foreach($field['options'] as $key => $label) {
                $checked = in_array($key, $field_value) ? 'checked' : '';
                $output .= '<label class="vendor-multiselect-option">';
                $output .= '<input type="checkbox" name="vendor_data[' . $field_id . '][]" 
                              value="' . esc_attr($key) . '" ' . $checked . '>';
                $output .= esc_html($label);
                $output .= '</label>';
            }
            
            $output .= '</div>';
            $output .= '</div>';
            $output .= '<input type="hidden" name="vendor_data[' . $field_id . '][]" value="">';
            break;
            
        case 'radio':
            foreach($field['options'] as $key => $label) {
                $checked = ($field_value == $key) ? 'checked' : '';
                $output .= '<label class="wp-alp-radio-label">';
                $output .= '<input type="radio" name="' . esc_attr($field_id) . '" 
                              value="' . esc_attr($key) . '" ' . $checked . '>';
                $output .= esc_html($label);
                $output .= '</label>';
            }
            break;
            
        case 'checkbox':
            $checked = $field_value ? 'checked' : '';
            $output .= '<label class="wp-alp-checkbox-label">';
            $output .= '<input type="checkbox" name="' . esc_attr($field_id) . '" value="1" ' . $checked . '>';
            $output .= esc_html($field['label'] ?? '');
            $output .= '</label>';
            break;
    }
    
    return $output;
}
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <link href="<?php echo esc_url(plugins_url('css/wp-alp-public.css', dirname(__FILE__, 2))); ?>" rel="stylesheet">
    <link href="<?php echo esc_url(plugins_url('css/custom-alp-styles.css', dirname(__FILE__, 2))); ?>" rel="stylesheet">
</head>
<body <?php body_class(); ?>>

<!-- Paso 1: Visión general de los pasos -->
<div id="step-intro" class="wp-alp-form-step wp-alp-vendor-form-page">
    <div class="wp-alp-form-content">
        <div class="wp-alp-container">
            <header class="wp-alp-airbnb-help-header">
                <div class="wp-alp-airbnb-help-links">
                    <a href="#" class="wp-alp-airbnb-help-link"><?php _e('Preguntas', 'wp-alp'); ?></a>
                    <a href="#" class="wp-alp-airbnb-save-link"><?php _e('Guardar y salir', 'wp-alp'); ?></a>
                </div>
            </header>

            <div class="wp-alp-two-column-layout">
                <div class="wp-alp-column-left">
                    <h1 class="wp-alp-steps-heading">Es fácil empezar a ser anfitrión</h1>
                    <div class="wp-alp-steps-list">
                        <?php foreach($visual_steps as $i => $step): ?>
                        <div class="wp-alp-step-item">
                            <div class="wp-alp-step-content">
                                <div class="wp-alp-step-number"><?php echo $i + 1; ?></div>
                                <div>
                                    <h3 class="wp-alp-step-title"><?php echo esc_html($step['title']); ?></h3>
                                    <p class="wp-alp-step-description"><?php echo esc_html($step['description']); ?></p>
                                </div>
                            </div>
                            <div class="wp-alp-step-image">
                                <img src="<?php echo esc_url($step['image']); ?>" alt="<?php echo esc_attr($step['title']); ?>">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="wp-alp-column-right">
                    <img src="<?php echo esc_url($plugin_url . 'vendor-form-step1.png'); ?>" alt="Vendor Form Visual" style="width: 100%;">
                </div>
            </div>

            <div class="wp-alp-full-width-progress">
                <div class="wp-alp-progress-line" style="width: 0%;"></div>
            </div>

            <div class="wp-alp-steps-action">
    <button class="wp-alp-steps-button" id="start-button"><?php _e('Empezar', 'wp-alp'); ?></button>
</div>
        </div>
    </div>
</div>

<!-- Paso 2: Selección de categoría -->
<div id="step-category" class="wp-alp-form-step" style="display: none;">
    <header class="wp-alp-airbnb-help-header">
        <div class="wp-alp-airbnb-help-links">
            <a href="#" class="wp-alp-airbnb-help-link"><?php _e('Preguntas', 'wp-alp'); ?></a>
            <a href="#" class="wp-alp-airbnb-save-link"><?php _e('Guardar y salir', 'wp-alp'); ?></a>
        </div>
    </header>

    <div class="wp-alp-airbnb-category-content">
        <h1 class="wp-alp-airbnb-category-title">¿Cuál de estas opciones describe mejor tu espacio?</h1>
        <form id="category-form">
            <input type="hidden" name="action" value="wp_alp_save_vendor_data">
            <input type="hidden" name="step" value="category">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wp_alp_vendor_nonce'); ?>">
            
            <div class="wp-alp-airbnb-category-grid">
                <div class="wp-alp-airbnb-category-item" data-value="apartment">
                    <div class="wp-alp-airbnb-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                            <line x1="9" y1="22" x2="15" y2="22"></line>
                            <line x1="8" y1="6" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                        </svg>
                    </div>
                    <span class="wp-alp-airbnb-category-name">Departamento</span>
                </div>
                <div class="wp-alp-airbnb-category-item" data-value="house">
                    <div class="wp-alp-airbnb-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <span class="wp-alp-airbnb-category-name">Casa</span>
                </div>
                <div class="wp-alp-airbnb-category-item" data-value="secondary-house">
                    <div class="wp-alp-airbnb-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"></path>
                            <path d="M7 13h10v8H7z"></path>
                        </svg>
                    </div>
                    <span class="wp-alp-airbnb-category-name">Casa Secundaria</span>
                </div>
                <div class="wp-alp-airbnb-category-item" data-value="room">
                    <div class="wp-alp-airbnb-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                            <rect x="2" y="10" width="20" height="10" rx="2" ry="2"></rect>
                            <line x1="12" y1="2" x2="12" y2="22"></line>
                        </svg>
                    </div>
                    <span class="wp-alp-airbnb-category-name">Habitación</span>
                </div>
                <div class="wp-alp-airbnb-category-item" data-value="event-venue">
                    <div class="wp-alp-airbnb-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <span class="wp-alp-airbnb-category-name">Evento</span>
                </div>
                <div class="wp-alp-airbnb-category-item" data-value="studio">
                    <div class="wp-alp-airbnb-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                    </div>
                    <span class="wp-alp-airbnb-category-name">Estudio</span>
                </div>
            </div>
            <input type="hidden" name="vendor_data[category]" value="">
        </form>
    </div>
</div>

<!-- Paso 3: Tipo de servicio -->
<div id="step-service" class="wp-alp-form-step" style="display: none;">
    <header class="wp-alp-airbnb-help-header">
        <div class="wp-alp-airbnb-help-links">
            <a href="#" class="wp-alp-airbnb-help-link"><?php _e('Preguntas', 'wp-alp'); ?></a>
            <a href="#" class="wp-alp-airbnb-save-link"><?php _e('Guardar y salir', 'wp-alp'); ?></a>
        </div>
    </header>

    <div class="wp-alp-airbnb-category-content">
        <h1 class="wp-alp-airbnb-category-title">¿Cómo prefieren hospedarse los clientes?</h1>
        <form id="service-form">
            <input type="hidden" name="action" value="wp_alp_save_vendor_data">
            <input type="hidden" name="step" value="service">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wp_alp_vendor_nonce'); ?>">
            
            <div class="wp-alp-airbnb-service-grid">
                <div class="wp-alp-airbnb-service-option" data-value="entire-place">
                    <div class="wp-alp-airbnb-service-info">
                        <h3 class="wp-alp-airbnb-service-title">Espacio completo</h3>
                        <p class="wp-alp-airbnb-service-description">Los clientes tendrán todo el espacio solo para ellos.</p>
                    </div>
                    <div class="wp-alp-airbnb-service-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                </div>
                <div class="wp-alp-airbnb-service-option" data-value="private-room">
                    <div class="wp-alp-airbnb-service-info">
                        <h3 class="wp-alp-airbnb-service-title">Habitación privada</h3>
                        <p class="wp-alp-airbnb-service-description">Los clientes tendrán su propia habitación y pueden compartir algunas áreas comunes.</p>
                    </div>
                    <div class="wp-alp-airbnb-service-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="2" ry="2"></rect>
                            <line x1="12" y1="2" x2="12" y2="22"></line>
                        </svg>
                    </div>
                </div>
                <div class="wp-alp-airbnb-service-option" data-value="shared-room">
                    <div class="wp-alp-airbnb-service-info">
                        <h3 class="wp-alp-airbnb-service-title">Habitación compartida</h3>
                        <p class="wp-alp-airbnb-service-description">Los clientes dormirán en una habitación con otros.</p>
                    </div>
                    <div class="wp-alp-airbnb-service-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M17 21V7"></path>
                            <path d="M7 21V7"></path>
                            <path d="M22 10c0-1.1-.9-2-2-2H4a2 2 0 0 0-2 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="wp-alp-airbnb-service-validation"></div>
            <input type="hidden" name="vendor_data[service]" value="">
        </form>
    </div>
</div>

<!-- Paso 4: Ubicación -->
<div id="step-location" class="wp-alp-form-step" style="display: none;">
    <header class="wp-alp-airbnb-help-header">
        <div class="wp-alp-airbnb-help-links">
            <a href="#" class="wp-alp-airbnb-help-link"><?php _e('Preguntas', 'wp-alp'); ?></a>
            <a href="#" class="wp-alp-airbnb-save-link"><?php _e('Guardar y salir', 'wp-alp'); ?></a>
        </div>
    </header>

    <div class="wp-alp-airbnb-category-content">
        <h1 class="wp-alp-airbnb-category-title">¿Dónde está ubicado tu lugar?</h1>
        <p class="wp-alp-airbnb-category-subtitle">Tu dirección se comparte solo con los clientes después de que reserven un viaje.</p>
        
        <form id="location-form">
            <input type="hidden" name="action" value="wp_alp_save_vendor_data">
            <input type="hidden" name="step" value="location">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wp_alp_vendor_nonce'); ?>">
            
            <div class="wp-alp-location-options">
                <div class="wp-alp-location-option" data-value="single-location">
                    <div>
                        <h3 class="wp-alp-location-option-title">Un solo lugar</h3>
                        <p class="wp-alp-location-option-subtitle">Los clientes estarán en un solo lugar durante su estancia</p>
                    </div>
                    <div class="wp-alp-location-option-radio"></div>
                </div>
                <div class="wp-alp-location-option" data-value="multiple-locations">
                    <div>
                        <h3 class="wp-alp-location-option-title">Múltiples lugares</h3>
                        <p class="wp-alp-location-option-subtitle">Los clientes se alojarán en diferentes lugares durante su estancia</p>
                    </div>
                    <div class="wp-alp-location-option-radio"></div>
                </div>
            </div>
            <input type="hidden" name="vendor_data[location_type]" value="">
        </form>
    </div>
</div>

<!-- Paso 5: Dirección -->
<div id="step-address" class="wp-alp-form-step" style="display: none;">
    <header class="wp-alp-airbnb-help-header">
        <div class="wp-alp-airbnb-help-links">
            <a href="#" class="wp-alp-airbnb-help-link"><?php _e('Preguntas', 'wp-alp'); ?></a>
            <a href="#" class="wp-alp-airbnb-save-link"><?php _e('Guardar y salir', 'wp-alp'); ?></a>
        </div>
    </header>

    <div class="wp-alp-airbnb-category-content">
        <h1 class="wp-alp-airbnb-category-title">Confirma tu dirección</h1>
        <p class="wp-alp-airbnb-category-subtitle">Tu dirección se comparte solo con los clientes después de que reserven un viaje.</p>
        
        <form id="address-form">
            <input type="hidden" name="action" value="wp_alp_save_vendor_data">
            <input type="hidden" name="step" value="address">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wp_alp_vendor_nonce'); ?>">
            
            <div class="wp-alp-map-search">
                <div class="wp-alp-search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="M21 21l-4.35-4.35"></path>
                    </svg>
                </div>
                <input type="text" class="wp-alp-address-input" 
                       name="address_search" 
                       placeholder="Busca tu ubicación"
                       autocomplete="address-line1">
            </div>
            
            <div class="wp-alp-map-container">
                <div class="wp-alp-map-wrapper">
                    <!-- El mapa se inicializará aquí -->
                </div>
                <div class="wp-alp-house-marker">
                    <div class="wp-alp-marker-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="white" stroke="none">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                </div>
                <div class="wp-alp-approximate-tooltip">
                    Ubicación aproximada
                </div>
            </div>
            
            <button type="button" class="wp-alp-btn wp-alp-btn-secondary wp-alp-confirm-address-btn">
                Confirmar ubicación
            </button>
            
            <input type="hidden" name="vendor_data[address]" value="">
            <input type="hidden" name="vendor_data[latitude]" value="">
            <input type="hidden" name="vendor_data[longitude]" value="">
        </form>
    </div>
</div>

<!-- Paso 6: Dirección detallada -->
<div id="step-address-details" class="wp-alp-form-step" style="display: none;">
    <header class="wp-alp-airbnb-help-header">
        <div class="wp-alp-airbnb-help-links">
            <a href="#" class="wp-alp-airbnb-help-link"><?php _e('Preguntas', 'wp-alp'); ?></a>
            <a href="#" class="wp-alp-airbnb-save-link"><?php _e('Guardar y salir', 'wp-alp'); ?></a>
        </div>
    </header>

    <div class="wp-alp-airbnb-category-content">
        <div class="wp-alp-address-form-container">
            <h1 class="wp-alp-address-form-title">¿Cuál es la dirección exacta?</h1>
            <p class="wp-alp-address-form-subtitle">Solo los clientes confirmados obtienen tu dirección exacta. Así sabrán dónde se alojan y cómo llegar.</p>
            
            <form id="address-details-form">
                <input type="hidden" name="action" value="wp_alp_save_vendor_data">
                <input type="hidden" name="step" value="address-details">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wp_alp_vendor_nonce'); ?>">
                
                <div class="wp-alp-form-group">
                    <label class="wp-alp-form-label">País/región</label>
                    <select name="vendor_data[country]" class="wp-alp-form-select">
                        <option value="MX">México</option>
                        <option value="US">Estados Unidos</option>
                    </select>
                </div>
                
                <div class="wp-alp-form-group">
                    <label class="wp-alp-form-label">Dirección</label>
                    <input type="text" name="vendor_data[street_address]" class="wp-alp-form-input" placeholder="Dirección de la calle">
                </div>
                
                <div class="wp-alp-form-group">
                    <label class="wp-alp-form-label">Apartamento, suite, etc. (opcional)</label>
                    <input type="text" name="vendor_data[street_address_2]" class="wp-alp-form-input" placeholder="Apartamento 2A">
                </div>
                
                <div class="wp-alp-form-group">
                    <label class="wp-alp-form-label">Ciudad/pueblo</label>
                    <input type="text" name="vendor_data[city]" class="wp-alp-form-input" placeholder="Ciudad">
                </div>
                
                <div class="wp-alp-form-group">
                    <label class="wp-alp-form-label">Estado/provincia</label>
                    <input type="text" name="vendor_data[state]" class="wp-alp-form-input" placeholder="Estado">
                </div>
                
                <div class="wp-alp-form-group">
                    <label class="wp-alp-form-label">Código postal</label>
                    <input type="text" name="vendor_data[postal_code]" class="wp-alp-form-input" placeholder="Código postal">
                </div>
                
                <div class="wp-alp-form-group">
                    <label class="wp-alp-form-label">Zona horaria</label>
                    <select name="vendor_data[timezone]" class="wp-alp-form-select">
                        <option value="America/Tijuana">America/Tijuana (PT)</option>
                        <option value="America/Hermosillo">America/Hermosillo (MT)</option>
                        <option value="America/Mexico_City">America/Mexico_City (CT)</option>
                    </select>
                </div>
                
                <div class="wp-alp-address-form-buttons">
                    <button type="button" class="wp-alp-btn wp-alp-btn-secondary" onclick="showStep('address')">Atrás</button>
                    <button type="submit" class="wp-alp-btn wp-alp-btn-primary">Continuar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Paso 7: Ubicaciones adicionales -->
<div id="step-additional-locations" class="wp-alp-form-step" style="display: none;">
    <header class="wp-alp-airbnb-help-header">
        <div class="wp-alp-airbnb-help-links">
            <a href="#" class="wp-alp-airbnb-help-link"><?php _e('Preguntas', 'wp-alp'); ?></a>
            <a href="#" class="wp-alp-airbnb-save-link"><?php _e('Guardar y salir', 'wp-alp'); ?></a>
        </div>
    </header>

    <div class="wp-alp-airbnb-category-content">
        <h1 class="wp-alp-airbnb-category-title">¿A qué otras ubicaciones pueden acceder los clientes?</h1>
        <p class="wp-alp-airbnb-category-subtitle">Selecciona todas las opciones que correspondan</p>
        
        <form id="additional-locations-form">
            <input type="hidden" name="action" value="wp_alp_save_vendor_data">
            <input type="hidden" name="step" value="additional-locations">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wp_alp_vendor_nonce'); ?>">
            
            <div class="wp-alp-locations-checkboxes">
                <div class="wp-alp-location-checkbox-item">
                    <input type="checkbox" name="vendor_data[additional_locations][]" value="garage" id="garage">
                    <label for="garage">Garaje</label>
                </div>
                <div class="wp-alp-location-checkbox-item">
                    <input type="checkbox" name="vendor_data[additional_locations][]" value="backyard" id="backyard">
                    <label for="backyard">Jardín trasero</label>
                </div>
                <div class="wp-alp-location-checkbox-item">
                    <input type="checkbox" name="vendor_data[additional_locations][]" value="front-yard" id="front-yard">
                    <label for="front-yard">Jardín delantero</label>
                </div>
                <div class="wp-alp-location-checkbox-item">
                    <input type="checkbox" name="vendor_data[additional_locations][]" value="basement" id="basement">
                    <label for="basement">Sótano</label>
                </div>
                <div class="wp-alp-location-checkbox-item">
                    <input type="checkbox" name="vendor_data[additional_locations][]" value="attic" id="attic">
                    <label for="attic">Ático</label>
                </div>
                <div class="wp-alp-location-checkbox-item">
                    <input type="checkbox" name="vendor_data[additional_locations][]" value="other" id="other-location">
                    <label for="other-location">Otro</label>
                </div>
            </div>
            
            <div class="wp-alp-location-other-input" style="display: none;">
                <input type="text" name="vendor_data[other_location_description]" class="wp-alp-form-input" placeholder="Describe la otra ubicación">
            </div>
            
            <div class="wp-alp-location-toggle">
                <div>
                    <h3>¿Hay lugares que solo el anfitrión pueda usar?</h3>
                    <p class="wp-alp-toggle-description">Indica si hay habitaciones o espacios que solo tú puedas usar mientras haya clientes alojados</p>
                </div>
                <div class="wp-alp-toggle-switch" style="position: relative;">
                    <label class="wp-alp-switch">
                        <input type="hidden" name="vendor_data[host_only_areas]" value="0">
                        <input type="checkbox" name="vendor_data[host_only_areas]" value="1">
                        <span class="wp-alp-slider"></span>
                        <div class="wp-alp-toggle-labels">
                            <span class="wp-alp-toggle-off">No</span>
                            <span class="wp-alp-toggle-on">Sí</span>
                        </div>
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Paso 8: Información básica -->
<div id="step-basic-info" class="wp-alp-form-step" style="display: none;">
    <header class="wp-alp-airbnb-help-header">
        <div class="wp-alp-airbnb-help-links">
            <a href="#" class="wp-alp-airbnb-help-link"><?php _e('Preguntas', 'wp-alp'); ?></a>
            <a href="#" class="wp-alp-airbnb-save-link"><?php _e('Guardar y salir', 'wp-alp'); ?></a>
        </div>
    </header>

    <div class="wp-alp-airbnb-category-content">
        <h1 class="wp-alp-airbnb-category-title">Comparte algunos conceptos básicos sobre tu espacio</h1>
        <p class="wp-alp-airbnb-category-subtitle">Luego podrás agregar más detalles, como la zona del dueño.</p>
        
        <form id="basic-info-form">
            <input type="hidden" name="action" value="wp_alp_save_vendor_data">
            <input type="hidden" name="step" value="basic-info">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wp_alp_vendor_nonce'); ?>">
            
            <div class="wp-alp-basic-info-container">
                <!-- Tarjeta de Huéspedes -->
                <div class="wp-alp-info-card">
                    <div class="wp-alp-card-header">
                        <i class="wp-alp-icon fas fa-users"></i>
                        <h3>Guests</h3>
                    </div>
                    <div class="wp-alp-number-field">
                        <label for="guests" class="wp-alp-field-label">
                            Number of guests                            <span class="wp-alp-tooltip" data-tooltip="Maximum number of guests who can stay">ⓘ</span>
                        </label>
                        <?php echo render_field('guests', $form_definition['guests']['guests'], $saved_vendor_data['guests'] ?? null); ?>
                    </div>
                </div>

                <!-- Tarjeta de Espacios -->
                <div class="wp-alp-info-card">
                    <div class="wp-alp-card-header">
                        <i class="wp-alp-icon fas fa-bed"></i>
                        <h3>Spaces</h3>
                    </div>
                    <div class="wp-alp-number-field">
                        <label for="bedrooms" class="wp-alp-field-label">
                            Number of bedrooms                            <span class="wp-alp-tooltip" data-tooltip="Private rooms for sleeping">ⓘ</span>
                        </label>
                        <?php echo render_field('bedrooms', $form_definition['guests']['bedrooms'], $saved_vendor_data['bedrooms'] ?? null); ?>
                    </div>
                    <div class="wp-alp-number-field">
                        <label for="beds" class="wp-alp-field-label">
                            Number of beds                            <span class="wp-alp-tooltip" data-tooltip="Total beds available">ⓘ</span>
                        </label>
                        <?php echo render_field('beds', $form_definition['guests']['beds'], $saved_vendor_data['beds'] ?? null); ?>
                    </div>
                    <div class="wp-alp-number-field">
                        <label for="bathrooms" class="wp-alp-field-label">
                            Number of bathrooms                            <span class="wp-alp-tooltip" data-tooltip="Bathrooms with full facilities">ⓘ</span>
                        </label>
                        <?php echo render_field('bathrooms', $form_definition['guests']['bathrooms'], $saved_vendor_data['bathrooms'] ?? null); ?>
                    </div>
                </div>

                <!-- Tarjeta para campos específicos de Event Venue -->
                <div class="wp-alp-info-card">
                    <div class="wp-alp-card-header">
                        <i class="wp-alp-icon fas fa-warehouse"></i>
                        <h3>Capacity</h3>
                    </div>
                    
                    <div class="wp-alp-number-field event-venue-field" style="display: none;">
                        <label for="capacity" class="wp-alp-field-label">
                            Total capacity                            <span class="wp-alp-tooltip" data-tooltip="Maximum number of people the space can accommodate">ⓘ</span>
                        </label>
                        <?php echo render_field('capacity', $form_definition['capacity']['capacity'], $saved_vendor_data['capacity'] ?? null); ?>
                    </div>
                    
                    <div class="wp-alp-number-field event-venue-field" style="display: none;">
                        <label for="restrooms" class="wp-alp-field-label">
                            Number of restrooms                            <span class="wp-alp-tooltip" data-tooltip="Number of restrooms available for guests">ⓘ</span>
                        </label>
                        <?php echo render_field('restrooms', $form_definition['capacity']['restrooms'], $saved_vendor_data['restrooms'] ?? null); ?>
                    </div>
                    
                    <div class="wp-alp-number-field event-venue-field" style="display: none;">
                        <label for="parking_spaces" class="wp-alp-field-label">
                            Parking spaces                            <span class="wp-alp-tooltip" data-tooltip="Number of parking spaces available">ⓘ</span>
                        </label>
                        <?php echo render_field('parking_spaces', $form_definition['capacity']['parking_spaces'], $saved_vendor_data['parking_spaces'] ?? null); ?>
                    </div>
                </div>

                <!-- Tarjeta para campos específicos de Studio -->
                <div class="wp-alp-info-card" style="display: none;">
                    <div class="wp-alp-card-header">
                        <i class="wp-alp-icon fas fa-building"></i>
                        <h3>Studio Details</h3>
                    </div>
                    <div class="wp-alp-number-field studio-field">
                        <label for="size_sqft" class="wp-alp-field-label">
                            Size (sq ft)                            <span class="wp-alp-tooltip" data-tooltip="Total area of the studio">ⓘ</span>
                        </label>
                        <?php echo render_field('size_sqft', $form_definition['studio-details']['size_sqft'], $saved_vendor_data['size_sqft'] ?? null); ?>
                    </div>
                    <div class="wp-alp-toggle-field studio-field">
                        <span class="wp-alp-toggle-text">Equipment Included</span>
                        <label class="wp-alp-switch">
                            <input type="hidden" name="vendor_data[equipment_included]" value="0">
                            <input type="checkbox" name="vendor_data[equipment_included]" value="1" <?php checked(isset($saved_vendor_data['equipment_included']) && $saved_vendor_data['equipment_included']); ?>>
                            <span class="wp-alp-slider"></span>
                        </label>
                    </div>
                    <div class="wp-alp-toggle-field studio-field">
                        <span class="wp-alp-toggle-text">Soundproof</span>
                        <label class="wp-alp-switch">
                            <input type="hidden" name="vendor_data[soundproof]" value="0">
                            <input type="checkbox" name="vendor_data[soundproof]" value="1" <?php checked(isset($saved_vendor_data['soundproof']) && $saved_vendor_data['soundproof']); ?>>
                            <span class="wp-alp-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Tarjeta de Facilities -->
                <div class="wp-alp-info-card">
                    <div class="wp-alp-card-header">
                        <i class="wp-alp-icon fas fa-restroom"></i>
                        <h3>Facilities</h3>
                    </div>
                    
                    <!-- Renderizar el campo facilities correctamente -->
                    <?php echo render_field('facilities', $form_definition['facilities'], $saved_vendor_data['facilities'] ?? array()); ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Paso final: Éxito -->
<div id="step-success" class="wp-alp-form-step" style="display: none;">
    <div class="wp-alp-form-content">
        <div class="wp-alp-container">
            <h1>¡Solicitud enviada con éxito!</h1>
            <p>Tu solicitud ha sido enviada y la revisaremos pronto.</p>
            <a href="<?php echo home_url(); ?>" class="wp-alp-btn wp-alp-btn-primary">Volver al inicio</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

// Manejar el botón de inicio
const startButton = document.getElementById('start-button');
if (startButton) {
    startButton.addEventListener('click', function() {
        showStep('category');
    });
}

    // Estado del formulario
    const formState = {
        currentStep: 'intro',
        data: {},
        steps: ['intro', 'category', 'service', 'location', 'address', 'address-details', 'additional-locations', 'basic-info', 'amenities', 'photos', 'description', 'pricing', 'availability', 'rules', 'legal', 'success'],
        requiresRegister: <?php echo !is_user_logged_in() ? 'true' : 'false'; ?>,
        currentUser: <?php echo json_encode(wp_get_current_user()->to_array()); ?>,
        savedData: <?php echo json_encode($saved_vendor_data); ?>
    };

    // Configuraciones de pasos
    const stepConfigs = {
        'intro': { 
            progressPercent: 0, 
            showBackButton: false,
            showProgressBar: false,
            hideNavButtons: true,
            useCustomTemplate: true
        },
        'category': { 
            progressPercent: 5, 
            showBackButton: true,
            customNextChecker: checkCategorySelection,
            nextStepLogic: getNextStepAfterCategory
        },
        'service': { 
            progressPercent: 10, 
            showBackButton: true,
            customNextChecker: checkServiceSelection
        },
        'location': { 
            progressPercent: 15, 
            showBackButton: true,
            customNextChecker: checkLocationSelection
        },
        'address': { 
            progressPercent: 20, 
            showBackButton: true,
            showMapButton: true,
            customNextChecker: checkAddressForm
        },
        'address-details': { 
            progressPercent: 25, 
            showBackButton: true,
            showNavButtons: false
        },
        'additional-locations': { 
            progressPercent: 30, 
            showBackButton: true
        },
        'basic-info': { 
            progressPercent: 35, 
            showBackButton: true,
            customNextChecker: checkBasicInfoForm
        },
        'amenities': { 
            progressPercent: 40, 
            showBackButton: true
        },
        'facilities': { 
            progressPercent: 45, 
            showBackButton: true,
            customNextChecker: function() {
                // No es requerido, siempre permite continuar
                return { valid: true };
            }
        },
        'photos': { 
            progressPercent: 50, 
            showBackButton: true
        },
        'description': { 
            progressPercent: 60, 
            showBackButton: true
        },
        'pricing': { 
            progressPercent: 70, 
            showBackButton: true,
            customNextChecker: checkPricingForm,
            useCustomTemplate: true
        },
        'availability': { 
            progressPercent: 75, 
            showBackButton: true
        },
        'rules': { 
            progressPercent: 80, 
            showBackButton: true
        },
        'legal': { 
            progressPercent: 90, 
            showBackButton: true,
            customNextChecker: checkLegalForm,
            useCustomTemplate: true,
            hideNavButtons: true
        }
    };

    // Navegación básica
    const navigation = document.createElement('div');
    navigation.className = 'wp-alp-airbnb-footer';
    navigation.innerHTML = `
        <div class="wp-alp-airbnb-progress-bar">
            <div class="wp-alp-airbnb-progress-completed" style="width: 0%;"></div>
        </div>
        <div class="wp-alp-airbnb-nav">
            <button class="wp-alp-airbnb-back-btn"><?php _e('Atrás', 'wp-alp'); ?></button>
            <button class="wp-alp-airbnb-next-btn"><?php _e('Siguiente', 'wp-alp'); ?></button>
        </div>
    `;
    document.body.appendChild(navigation);

    // Función para mostrar un paso
    function showStep(stepName) {
        document.querySelectorAll('.wp-alp-form-step').forEach(step => {
            step.style.display = 'none';
        });
        
        const stepElement = document.getElementById(`step-${stepName}`);
        if (stepElement) {
            stepElement.style.display = 'block';
            formState.currentStep = stepName;
            updateProgressBar();
            updateNavigationButtons();
            
            // Disparar eventos personalizados
            const event = new CustomEvent('stepShown', { detail: { step: stepName } });
            document.dispatchEvent(event);
        }
    }

    // Función para actualizar la barra de progreso
    function updateProgressBar() {
        const config = stepConfigs[formState.currentStep];
        const progressBar = document.querySelector('.wp-alp-airbnb-progress-completed');
        if (progressBar && config && config.progressPercent !== undefined) {
            progressBar.style.width = `${config.progressPercent}%`;
        }
    }

    // Función para actualizar los botones de navegación
    function updateNavigationButtons() {
        const config = stepConfigs[formState.currentStep];
        const backBtn = document.querySelector('.wp-alp-airbnb-back-btn');
        const nextBtn = document.querySelector('.wp-alp-airbnb-next-btn');
        const navigation = document.querySelector('.wp-alp-airbnb-footer');

        if (backBtn) {
            backBtn.style.display = config.showBackButton ? 'block' : 'none';
        }

        if (navigation) {
            if (config.hideNavButtons || !config.showProgressBar) {
                navigation.style.display = 'none';
            } else {
                navigation.style.display = 'block';
            }
        }
    }

    // Función para navegar hacia atrás
    function navigateBack(e) {
        e.preventDefault();
        const currentIndex = formState.steps.indexOf(formState.currentStep);
        if (currentIndex > 0) {
            showStep(formState.steps[currentIndex - 1]);
        }
    }

    // Función para navegar hacia adelante
    function navigateNext(e) {
        e.preventDefault();
        const config = stepConfigs[formState.currentStep];
        
        if (config.customNextChecker) {
            const result = config.customNextChecker();
            if (!result.valid) {
                return;
            }
        }

        saveFormData().then(() => {
            let nextStep;
            if (config.nextStepLogic) {
                nextStep = config.nextStepLogic();
            } else {
                const currentIndex = formState.steps.indexOf(formState.currentStep);
                nextStep = formState.steps[currentIndex + 1];
            }
            
            if (nextStep) {
                showStep(nextStep);
            }
        }).catch(error => {
            console.error('Error saving form data:', error);
        });
    }

    // Función para obtener datos del formulario
    function getFormData() {
        const currentStepElement = document.getElementById(`step-${formState.currentStep}`);
        const form = currentStepElement.querySelector('form');
        const formData = new FormData(form);
        
        const data = {
            action: formData.get('action'),
            nonce: formData.get('nonce'),
            step: formData.get('step'),
            user_data: {},
            vendor_data: {},
            address_search: formData.get('address_search'),
            exact_address: formData.get('exact_address') === '1'
        };
        
        // Recopilar todos los campos vendor_data
        for (let pair of formData.entries()) {
            if (pair[0].startsWith('vendor_data[')) {
                const key = pair[0].replace('vendor_data[', '').replace(']', '');
                if (data.vendor_data[key]) {
                    if (!Array.isArray(data.vendor_data[key])) {
                        data.vendor_data[key] = [data.vendor_data[key]];
                    }
                    data.vendor_data[key].push(pair[1]);
                } else {
                    data.vendor_data[key] = pair[1];
                }
            }
        }
        
        // Filtrar arrays vacíos
        Object.keys(data.vendor_data).forEach(key => {
            if (Array.isArray(data.vendor_data[key])) {
                data.vendor_data[key] = data.vendor_data[key].filter(item => item !== '');
            }
        });
        
        console.log('Form data collected:', data);
        return data;
    }

    // Función para guardar datos del formulario
    function saveFormData() {
        return new Promise((resolve, reject) => {
            const data = getFormData();
            
            fetch(wp_alp_ajax.ajax_url, {
                method: 'POST',
                credentials: 'same-origin',
                body: new URLSearchParams(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log('Data saved successfully');
                    formState.data = {...formState.data, ...data.vendor_data};
                    resolve();
                } else {
                    console.error('Error saving data:', result.data);
                    reject(new Error('Failed to save data'));
                }
            })
            .catch(error => {
                console.error('AJAX error:', error);
                reject(error);
            });
        });
    }

    // Validaciones específicas
    function checkCategorySelection() {
        const selected = document.querySelector('.wp-alp-airbnb-category-item.selected');
        if (!selected) {
            return { valid: false, message: 'Por favor selecciona una categoría' };
        }
        return { valid: true };
    }

    function checkServiceSelection() {
        const selected = document.querySelector('.wp-alp-airbnb-service-option.selected');
        if (!selected) {
            return { valid: false, message: 'Por favor selecciona un tipo de servicio' };
        }
        return { valid: true };
    }

    function checkLocationSelection() {
        const selected = document.querySelector('.wp-alp-location-option.selected');
        if (!selected) {
            return { valid: false, message: 'Por favor selecciona un tipo de ubicación' };
        }
        return { valid: true };
    }

    function checkAddressForm() {
        const address = document.querySelector('input[name="address_search"]').value;
        if (!address) {
            return { valid: false, message: 'Por favor ingresa una dirección' };
        }
        return { valid: true };
    }

    function checkBasicInfoForm() {
        // Todas las validaciones necesarias para el formulario básico
        return { valid: true };
    }

    function checkPricingForm() {
        const price = document.querySelector('input[name="vendor_data[price]"]');
        if (!price || !price.value) {
            return { valid: false, message: 'Por favor ingresa un precio' };
        }
        return { valid: true };
    }

    function checkLegalForm() {
        const accepted = document.querySelector('input[name="vendor_data[accept_terms]"]');
        if (!accepted || !accepted.checked) {
            return { valid: false, message: 'Debes aceptar los términos y condiciones' };
        }
        return { valid: true };
    }

    // Lógica para determinar el siguiente paso
    function getNextStepAfterCategory() {
        const selectedCategory = document.querySelector('.wp-alp-airbnb-category-item.selected');
        const value = selectedCategory ? selectedCategory.dataset.value : null;
        
        // Lógica para saltar ciertos pasos según la categoría
        if (value === 'event-venue') {
            // Para eventos, podríamos querer saltar ciertos pasos...
        }
        
        return 'service'; // Por defecto, ir al siguiente paso
    }

    // Eventos de formulario
    function initializeMultiselect() {
        document.querySelectorAll('.vendor-multiselect-container').forEach(container => {
            const toggle = container.querySelector('.vendor-multiselect-toggle');
            const options = container.querySelector('.vendor-multiselect-options');
            const field = toggle.getAttribute('data-field');
            
            // Función para actualizar el texto del toggle
            function updateToggleText() {
                const checkboxes = options.querySelectorAll('input[type="checkbox"]');
                const checked = Array.from(checkboxes).filter(cb => cb.checked);
                const textElement = toggle.querySelector('.vendor-multiselect-text');
                
                if (checked.length === 0) {
                    textElement.textContent = '<?php _e('Selecciona opciones', 'wp-alp'); ?>';
                    textElement.classList.add('placeholder');
                } else {
                    textElement.textContent = checked.length + ' ' + (checked.length === 1 ? '<?php _e('opción seleccionada', 'wp-alp'); ?>' : '<?php _e('opciones seleccionadas', 'wp-alp'); ?>');
                    textElement.classList.remove('placeholder');
                }
            }
            
            // Evento click en el toggle
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isVisible = options.style.display !== 'none';
                
                // Cerrar todos los dropdowns
                document.querySelectorAll('.vendor-multiselect-options').forEach(opt => {
                    opt.style.display = 'none';
                });
                document.querySelectorAll('.vendor-multiselect-toggle').forEach(t => {
                    t.classList.remove('active');
                });
                
                // Abrir este dropdown si estaba cerrado
                if (!isVisible) {
                    options.style.display = 'block';
                    toggle.classList.add('active');
                }
            });
            
            // Cerrar dropdown al hacer click fuera
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    options.style.display = 'none';
                    toggle.classList.remove('active');
                }
            });
            
            // Actualizar el texto del toggle cuando cambian los checkboxes
            options.addEventListener('change', function(e) {
                if (e.target.type === 'checkbox') {
                    updateToggleText();
                }
            });
            
            // Inicializar el texto del toggle
            updateToggleText();
        });
    }

    // Event listeners para la navegación
    document.querySelector('.wp-alp-airbnb-back-btn').addEventListener('click', navigateBack);
    document.querySelector('.wp-alp-airbnb-next-btn').addEventListener('click', navigateNext);

    // Event listeners para los campos del formulario
    document.addEventListener('click', function(e) {
        // Categorías
        if (e.target.closest('.wp-alp-airbnb-category-item')) {
            const item = e.target.closest('.wp-alp-airbnb-category-item');
            document.querySelectorAll('.wp-alp-airbnb-category-item').forEach(el => {
                el.classList.remove('selected');
            });
            item.classList.add('selected');
            const input = document.querySelector('input[name="vendor_data[category]"]');
            if (input) {
                input.value = item.dataset.value;
            }
            updateFieldsBasedOnCategory();
        }

        // Servicios
        if (e.target.closest('.wp-alp-airbnb-service-option')) {
            const item = e.target.closest('.wp-alp-airbnb-service-option');
            document.querySelectorAll('.wp-alp-airbnb-service-option').forEach(el => {
                el.classList.remove('selected');
            });
            item.classList.add('selected');
            const input = document.querySelector('input[name="vendor_data[service]"]');
            if (input) {
                input.value = item.dataset.value;
            }
        }

        // Ubicaciones
        if (e.target.closest('.wp-alp-location-option')) {
            const item = e.target.closest('.wp-alp-location-option');
            document.querySelectorAll('.wp-alp-location-option').forEach(el => {
                el.classList.remove('selected');
            });
            item.classList.add('selected');
            const input = document.querySelector('input[name="vendor_data[location_type]"]');
            if (input) {
                input.value = item.dataset.value;
            }
        }
    });

    // Event listeners para número
    document.addEventListener('click', function(e) {
        if (e.target.closest('.wp-alp-number-decrease')) {
            const button = e.target.closest('.wp-alp-number-decrease');
            const control = button.closest('.wp-alp-number-control');
            const input = control.querySelector('.wp-alp-number-input');
            const min = parseInt(input.min) || 0;
            let value = parseInt(input.value) || min;
            
            if (value > min) {
                input.value = value - 1;
                updateButtonStates(input);
            }
        }
        
        if (e.target.closest('.wp-alp-number-increase')) {
            const button = e.target.closest('.wp-alp-number-increase');
            const control = button.closest('.wp-alp-number-control');
            const input = control.querySelector('.wp-alp-number-input');
            const max = parseInt(input.max) || Infinity;
            let value = parseInt(input.value) || 0;
            
            if (value < max) {
                input.value = value + 1;
                updateButtonStates(input);
            }
        }
    });

    function updateButtonStates(input) {
        const control = input.closest('.wp-alp-number-control');
        const decreaseBtn = control.querySelector('.wp-alp-number-decrease');
        const increaseBtn = control.querySelector('.wp-alp-number-increase');
        const value = parseInt(input.value);
        const min = parseInt(input.min);
        const max = parseInt(input.max);
        
        decreaseBtn.disabled = value <= min;
        increaseBtn.disabled = value >= max;
    }

    // Event listener para formularios
    document.addEventListener('submit', function(e) {
        if (e.target.closest('form')) {
            e.preventDefault();
            navigateNext(e);
        }
    });

    // Event listener para "Otro" en ubicaciones adicionales
    document.addEventListener('change', function(e) {
        if (e.target.id === 'other-location') {
            const otherInput = document.querySelector('.wp-alp-location-other-input');
            otherInput.style.display = e.target.checked ? 'block' : 'none';
        }
    });

    // Función para actualizar campos basados en la categoría
    function updateFieldsBasedOnCategory() {
        const selectedField = document.querySelector('.wp-alp-airbnb-category-item.selected');
        if (!selectedField) return;
        
        const selectedValue = selectedField.dataset.value;
        
        // Ocultar todos los campos específicos
        document.querySelectorAll('.event-venue-field, .studio-field').forEach(field => {
            field.style.display = 'none';
        });
        
        // Mostrar campos basados en la categoría
        if (selectedValue === 'event-venue') {
            document.querySelectorAll('.event-venue-field').forEach(field => {
                field.style.display = 'block';
            });
        } else if (selectedValue === 'studio') {
            document.querySelectorAll('.studio-field').forEach(field => {
                field.style.display = 'block';
            });
        }
        
        // Por defecto, asegurar que los campos de huéspedes estén visibles
        document.querySelectorAll('.wp-alp-number-field:not(.event-venue-field, .studio-field)').forEach(field => {
            field.style.display = 'block';
        });
    }

    // Inicializar los campos si hay datos guardados
    Object.keys(formState.savedData).forEach(key => {
        const input = document.querySelector(`[name="vendor_data[${key}]"]`);
        const categoryItem = document.querySelector(`.wp-alp-airbnb-category-item[data-value="${key}"]`);
        const serviceOption = document.querySelector(`.wp-alp-airbnb-service-option[data-value="${key}"]`);
        const locationOption = document.querySelector(`.wp-alp-location-option[data-value="${key}"]`);
        
        if (input) {
            input.value = formState.savedData[key];
        } else if (categoryItem) {
            categoryItem.classList.add('selected');
        } else if (serviceOption) {
            serviceOption.classList.add('selected');
        } else if (locationOption) {
            locationOption.classList.add('selected');
        }
    });

    // Evento cuando se muestra un paso
    document.addEventListener('stepShown', function(e) {
        if (e.detail.step === 'basic-info') {
            updateFieldsBasedOnCategory();
        }
    });

    // Inicializar el formulario
    showStep(formState.currentStep);
});
</script>

<?php wp_footer(); ?>
</body>
</html>