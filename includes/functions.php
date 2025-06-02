<?php

/**
 * Verifica y crea un vendor si es necesario
 */
function check_create_vendor_ajax() {
    // Verificar nonce por seguridad
    check_ajax_referer('create_vendor_nonce', 'nonce');
    
    // Verificar si el usuario está loggeado
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
        return;
    }
    
    $user_id = get_current_user_id();
    $result = array('success' => false);
    
    // Verificar si el usuario ya es vendor
    $vendor = null;
    if (class_exists('HivePress\Models\Vendor')) {
        $vendor = \HivePress\Models\Vendor::query()->filter(
            array(
                'status' => array('auto-draft', 'draft', 'publish'),
                'user'   => $user_id,
            )
        )->get_first();
    }
    
    // Si no hay vendor, crearlo
    if (!$vendor && class_exists('HivePress\Models\Vendor')) {
        $vendor = new \HivePress\Models\Vendor();
        $vendor->fill(array(
            'user'   => $user_id,
            'status' => 'publish',
            'name'   => get_user_meta($user_id, 'first_name', true) . ' ' . get_user_meta($user_id, 'last_name', true),
        ));
        
        // Guardar el vendor
        $vendor->save();
        $result['created'] = true;
    }
    
    $result['success'] = true;
    $result['vendor_id'] = $vendor ? $vendor->get_id() : null;
    
    wp_send_json($result);
}
add_action('wp_ajax_check_create_vendor', 'check_create_vendor_ajax');

/**
 * Modifica los elementos del menú para mostrar u ocultar el botón de login
 * según el estado de autenticación del usuario.
 * 
 * @param string $items HTML de los elementos del menú.
 * @param object $args Argumentos del menú.
 * @return string HTML modificado de los elementos del menú.
 */
function wp_alp_modify_menu_items($items, $args) {
    // Si el usuario está autenticado, quitar el botón de login
    if (is_user_logged_in()) {
        // Buscar y eliminar el ítem de menú con clase wp-alp-login-trigger o data-wp-alp-trigger="login"
        $items = preg_replace(
            '/<li[^>]*class="[^"]*wp-alp-login-trigger[^"]*".*?<\/li>/i', 
            '', 
            $items
        );
        
        $items = preg_replace(
            '/<li[^>]*><a[^>]*data-wp-alp-trigger="login"[^>]*>.*?<\/a><\/li>/i', 
            '', 
            $items
        );
    }
    
    return $items;
}
add_filter('wp_nav_menu_items', 'wp_alp_modify_menu_items', 10, 2);

/**
 * Agrega estilos personalizados para el botón de login en el menú.
 * Utiliza !important para sobrescribir los estilos de Elementor.
 */
function wp_alp_add_login_button_styles() {
    ?>
    <style>
        /* Estilos de alta especificidad para sobrescribir Elementor */
        body .elementor-nav-menu .menu-item a[data-wp-alp-trigger="login"],
        body .elementor-widget-container .menu-item.wp-alp-login-trigger a,
        body #menu-main-menu .menu-item a[data-wp-alp-trigger="login"],
        body #menu-main-menu .menu-item.wp-alp-login-trigger a,
        body .menu-item a[href="#"][data-wp-alp-trigger="login"],
        body .menu-item.wp-alp-login-trigger a[href="#"] {
            background-color: #FF385C !important;
            color: white !important;
            border-radius: 8px !important;
            padding: 10px 16px !important;
            font-weight: 500 !important;
            transition: background-color 0.2s !important;
            text-decoration: none !important;
            display: inline-block !important;
        }
        
        /* Hover state */
        body .elementor-nav-menu .menu-item a[data-wp-alp-trigger="login"]:hover,
        body .elementor-widget-container .menu-item.wp-alp-login-trigger a:hover,
        body #menu-main-menu .menu-item a[data-wp-alp-trigger="login"]:hover,
        body #menu-main-menu .menu-item.wp-alp-login-trigger a:hover,
        body .menu-item a[href="#"][data-wp-alp-trigger="login"]:hover,
        body .menu-item.wp-alp-login-trigger a[href="#"]:hover {
            background-color: #E31C5F !important;
            color: white !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'wp_alp_add_login_button_styles');

/**
 * Modifica la clase del cuerpo cuando se está en una página de vendor.
 * Agrega una clase específica para mejorar los estilos CSS.
 * 
 * @param array $classes Clases existentes.
 * @return array Clases modificadas.
 */
function wp_alp_add_body_classes($classes) {
    // Comprobar si estamos en una página de vendor
    if (is_page_template('templates/vendor-steps-template.php')) {
        $classes[] = 'wp-alp-vendor-page';
        $classes[] = 'wp-alp-template-page';
    }
    
    return $classes;
}
add_filter('body_class', 'wp_alp_add_body_classes');

/**
 * Enqueue estilos adicionales para las páginas de vendedor.
 * Estos estilos mejoran la apariencia de las secciones de listing.
 */
function wp_alp_enqueue_vendor_styles() {
    if (is_page_template('templates/vendor-steps-template.php') || 
        is_page_template('templates/vendor-page-template.php')) {
        
        wp_enqueue_style(
            'wp-alp-vendor-styles',
            plugin_dir_url(dirname(__FILE__)) . 'public/css/custom-alp-styles.css',
            array(),
            WP_ALP_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'wp_alp_enqueue_vendor_styles', 999); // Prioridad alta para sobrescribir otros estilos