<?php
/**
 * Funcionalidad para la integración de Google Maps
 * Este archivo gestiona todo lo relacionado con la carga de Google Maps API
 * y la inicialización del mapa en las páginas de vendedor
 */

// Evitar acceso directo
if (!defined('WPINC')) {
    die;
}

/**
 * Obtiene la API key de Google Maps de manera segura y flexible
 * Busca la clave en varias ubicaciones posibles para mayor compatibilidad
 */
function wp_alp_get_google_maps_api_key() {
    // Opción 1: Constante definida directamente
    if (defined('GOOGLE_MAPS_API_KEY')) {
        return GOOGLE_MAPS_API_KEY;
    }
    
    // Opción 2: Opción en la base de datos de WordPress
    $db_option = get_option('wp_alp_google_maps_api_key');
    if (!empty($db_option)) {
        return $db_option;
    }
    
    // Opción 3: Constante definida en wp-config.php
    if (defined('GMAPS_API_KEY')) {
        return GMAPS_API_KEY;
    }
    
    // Opción 4: Buscar en opciones del tema o plugins populares
    $hivepress_option = get_option('hp_gmaps_api_key');
    if (!empty($hivepress_option)) {
        return $hivepress_option;
    }
    
    $theme_mod = get_theme_mod('google_maps_api_key');
    if (!empty($theme_mod)) {
        return $theme_mod;
    }
    
    // Opción 5: Valor predeterminado (solo para desarrollo)
    return 'YOUR_API_KEY_HERE'; // Cambiar en producción
}

// Definir constante para la API key si no está definida
if (!defined('GOOGLE_MAPS_API_KEY')) {
    define('GOOGLE_MAPS_API_KEY', wp_alp_get_google_maps_api_key());
}

/**
 * Maneja la carga de scripts de Google Maps para evitar duplicados
 * y asegurar que se cargue correctamente
 */
function wp_alp_manage_google_maps_scripts() {
    // Solo desregistrar los scripts que podrían causar conflictos si NO estamos
    // en la página de vendedor - esto previene duplicación
    if (!is_page_template('templates/vendor-steps-template.php')) {
        // Desregistrar scripts de Google Maps que podrían haber sido encolados por otros plugins
        wp_deregister_script('google-maps');
        wp_deregister_script('google-maps-api');
        wp_deregister_script('googlemaps');
        wp_deregister_script('google-places');
        wp_deregister_script('maps-googleapis');
        wp_deregister_script('google-maps-places');
        
        // Scripts específicos de plugins conocidos
        wp_deregister_script('hivepress-geolocation');
        wp_deregister_script('geocomplete');
    }
    
    // Solo en la página de vendedor cargamos Google Maps API con Places library
    if (is_page_template('templates/vendor-steps-template.php')) {
        // Primero, registrar el script principal de Google Maps con la API key
        wp_register_script(
            'wp-alp-google-maps',
            'https://maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_API_KEY . '&libraries=places&callback=initMap',
            array('jquery'),
            null,
            true
        );
        
        // Luego, registrar y encolar nuestro script personalizado
        wp_register_script(
            'wp-alp-vendor-location',
            plugin_dir_url(dirname(__FILE__)) . 'public/js/vendor-location.js',
            array('jquery', 'wp-alp-google-maps'),
            WP_ALP_VERSION . '.' . time(), // Forzar recarga evitando caché
            true
        );
        
        // Script de debugging (solo en entorno de desarrollo)
        $debug_mode = true; // Cambiar a false en producción
        if ($debug_mode) {
            wp_register_script(
                'wp-alp-maps-debug',
                plugin_dir_url(dirname(__FILE__)) . 'public/js/maps-debug.js',
                array('jquery', 'wp-alp-vendor-location'),
                WP_ALP_VERSION . '.' . time(),
                true
            );
            wp_enqueue_script('wp-alp-maps-debug');
        }
        
        // Encolar ambos scripts principales
        wp_enqueue_script('wp-alp-google-maps');
        wp_enqueue_script('wp-alp-vendor-location');
        
        // Agregar variable para debugging
        wp_localize_script('wp-alp-vendor-location', 'wpAlpMaps', array(
            'debugMode' => $debug_mode,
            'apiLoaded' => false,
            'apiKey' => GOOGLE_MAPS_API_KEY,
            'scriptVersion' => WP_ALP_VERSION,
            'templateFile' => 'vendor-steps-template.php'
        ));
    }
}
// Prioridad 5 para ejecutar antes que otros plugins (que suelen usar 10 o más)
add_action('wp_enqueue_scripts', 'wp_alp_manage_google_maps_scripts', 5);

/**
 * Agrega HTML modal para la información adicional sobre ubicación
 */
function wp_alp_add_location_info_modal() {
    if (!is_page_template('templates/vendor-steps-template.php')) {
        return;
    }
    
    // El HTML del modal
    ?>
    <div id="location-info-modal" class="wp-alp-info-modal" style="display: none;">
        <div class="wp-alp-info-modal-content">
            <span class="wp-alp-info-modal-close">&times;</span>
            <h3><?php echo get_locale() == 'en_US' ? 'About Location Sharing' : 'Sobre compartir tu ubicación'; ?></h3>
            <div class="wp-alp-info-modal-body">
                <?php if (get_locale() == 'en_US'): ?>
                    <p>By sharing your location, you help potential clients find services near them. You can choose to share either:</p>
                    <ul>
                        <li><strong>Exact location:</strong> Your precise address will be shown on the map.</li>
                        <li><strong>Approximate location:</strong> Only the general area will be displayed, protecting your exact address.</li>
                    </ul>
                    <p>You can change this setting at any time from your profile settings.</p>
                <?php else: ?>
                    <p>Al compartir tu ubicación, ayudas a que los clientes potenciales encuentren servicios cerca de ellos. Puedes elegir compartir:</p>
                    <ul>
                        <li><strong>Ubicación exacta:</strong> Tu dirección precisa se mostrará en el mapa.</li>
                        <li><strong>Ubicación aproximada:</strong> Solo se mostrará el área general, protegiendo tu dirección exacta.</li>
                    </ul>
                    <p>Puedes cambiar esta configuración en cualquier momento desde los ajustes de tu perfil.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'wp_alp_add_location_info_modal');

/**
 * Agrega estilos específicos para el mapa y modal
 */
function wp_alp_add_map_styles() {
    if (!is_page_template('templates/vendor-steps-template.php')) {
        return;
    }
    
    ?>
    <style>
    /* Estilos para el modal de información */
    .wp-alp-info-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }
    
    .wp-alp-info-modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        width: 90%;
        max-width: 600px;
        position: relative;
    }
    
    .wp-alp-info-modal-close {
        position: absolute;
        right: 20px;
        top: 15px;
        font-size: 24px;
        font-weight: bold;
        color: #888;
        cursor: pointer;
    }
    
    .wp-alp-info-modal-close:hover {
        color: #000;
    }
    
    .wp-alp-info-modal h3 {
        margin-top: 0;
        margin-bottom: 16px;
        font-size: 1.4em;
        color: #333;
    }
    
    .wp-alp-info-modal-body p {
        margin-bottom: 16px;
        line-height: 1.5;
    }
    
    .wp-alp-info-modal-body ul {
        margin-left: 20px;
        margin-bottom: 16px;
    }
    
    .wp-alp-info-modal-body li {
        margin-bottom: 8px;
        line-height: 1.4;
    }
    </style>
    <?php
}
add_action('wp_head', 'wp_alp_add_map_styles');