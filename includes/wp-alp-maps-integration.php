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

// ===== DEBUG - Verificar que este archivo se carga correctamente =====
error_log('WP-ALP Maps Integration file loaded - ' . date('Y-m-d H:i:s'));

// Función para debug visual en el admin footer
function wp_alp_maps_admin_debug() {
    echo '<div style="background-color:#ff5500; color:white; padding:5px; margin-top:10px;">
    WP-ALP Maps Integration cargado correctamente - ' . date('Y-m-d H:i:s') . '
    </div>';
}
add_action('admin_footer', 'wp_alp_maps_admin_debug');

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
    // Desregistrar TODOS los scripts de Google Maps para evitar duplicados
    // independientemente de la página en que estemos
    $scripts_to_deregister = array(
        'google-maps', 'google-maps-api', 'googlemaps', 'google-places', 
        'maps-googleapis', 'google-maps-places', 'googleapis',
        'google-api', 'googlemap', 'google-map',
        'maps-api', 'maps-google', 'gmaps', 'gmaps-api',
        'google-maps-js', 'google-places-js', 'google-maps-places-js',
        'hivepress-geolocation', 'geocomplete', 'google-maps-script',
        'maps-js', 'places-js', 'places-library'
    );
    
    foreach ($scripts_to_deregister as $script) {
        wp_deregister_script($script);
    }
    
    // Desregistrar globalmente los scripts específicos de HivePress
    global $wp_scripts;
    if ($wp_scripts) {
        foreach ($wp_scripts->registered as $handle => $script) {
            if ($script->src && (
                strpos($script->src, 'maps.googleapis.com') !== false || 
                strpos($script->src, 'maps.google.com') !== false ||
                strpos($handle, 'map') !== false || 
                strpos($handle, 'place') !== false)
            ) {
                wp_deregister_script($handle);
            }
        }
    }
    
    // Solo en la página de vendedor cargamos Google Maps API con Places library
    if (is_page_template('templates/vendor-steps-template.php')) {
        // Primero, registrar el script principal de Google Maps con la API key
        // Usamos async y defer para mejorar carga, y sin callback para evitar problemas
        wp_register_script(
            'wp-alp-google-maps',
            'https://maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_API_KEY . '&libraries=places',
            array('jquery'),
            null,
            true
        );
        
        // Añadir atributos async y defer al script
        add_filter('script_loader_tag', function($tag, $handle) {
            if ('wp-alp-google-maps' === $handle) {
                return str_replace('<script', '<script async defer', $tag);
            }
            return $tag;
        }, 10, 2);
        
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