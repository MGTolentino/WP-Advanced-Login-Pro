<?php

/**
 * Verifica y crea un vendor si es necesario
 */
function check_create_vendor_ajax() {
    // Verificar nonce por seguridad
    if (!check_ajax_referer('create_vendor_nonce', 'nonce', false)) {
        wp_send_json_error(array(
            'message' => __('Sesión expirada. Por favor, recarga la página.', 'wp-alp'),
            'code' => 'invalid_nonce'
        ));
        return;
    }
    
    // Verificar si el usuario está loggeado
    if (!is_user_logged_in()) {
        wp_send_json_error(array(
            'message' => __('Debes iniciar sesión para continuar.', 'wp-alp'),
            'code' => 'not_logged_in'
        ));
        return;
    }
    
    $user_id = get_current_user_id();
    $result = array('success' => false);
    
    // Verificar permisos del usuario
    // Solo usuarios con rol 'lead' o superior pueden crear vendors
    $user = get_user_by('ID', $user_id);
    if (!$user) {
        wp_send_json_error(array(
            'message' => __('Usuario no válido.', 'wp-alp'),
            'code' => 'invalid_user'
        ));
        return;
    }
    
    // Verificar roles permitidos
    $allowed_roles = array('lead', 'administrator', 'editor');
    $user_roles = (array) $user->roles;
    $has_permission = false;
    
    foreach ($allowed_roles as $role) {
        if (in_array($role, $user_roles)) {
            $has_permission = true;
            break;
        }
    }
    
    if (!$has_permission) {
        // Registrar intento no autorizado
        if (class_exists('WP_ALP_Security_Enhanced')) {
            WP_ALP_Security_Enhanced::log_security_event(
                'unauthorized_vendor_creation_attempt',
                array(
                    'user_id' => $user_id,
                    'user_roles' => $user_roles
                ),
                'warning'
            );
        }
        
        wp_send_json_error(array(
            'message' => __('No tienes permisos para crear un vendor.', 'wp-alp'),
            'code' => 'insufficient_permissions'
        ));
        return;
    }
    
    // Verificar rate limiting
    if (WP_ALP_Security::is_rate_limited($user_id, 'create_vendor', 3, 3600)) {
        wp_send_json_error(array(
            'message' => __('Has intentado crear un vendor demasiadas veces. Por favor, espera una hora.', 'wp-alp'),
            'code' => 'rate_limited'
        ));
        return;
    }
    
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
        try {
            // Sanitizar datos del usuario
            $first_name = sanitize_text_field(get_user_meta($user_id, 'first_name', true));
            $last_name = sanitize_text_field(get_user_meta($user_id, 'last_name', true));
            $vendor_name = trim($first_name . ' ' . $last_name);
            
            // Si no hay nombre, usar el display name del usuario
            if (empty($vendor_name)) {
                $vendor_name = sanitize_text_field($user->display_name);
            }
            
            // Si aún no hay nombre, usar el email
            if (empty($vendor_name)) {
                $vendor_name = sanitize_text_field($user->user_email);
            }
            
            $vendor = new \HivePress\Models\Vendor();
            $vendor->fill(array(
                'user'   => $user_id,
                'status' => 'draft', // Iniciar como draft para revisión
                'name'   => $vendor_name,
            ));
            
            // Guardar el vendor
            if ($vendor->save()) {
                $result['created'] = true;
                
                // Registrar evento de seguridad
                if (class_exists('WP_ALP_Security_Enhanced')) {
                    WP_ALP_Security_Enhanced::log_security_event(
                        'vendor_created',
                        array(
                            'user_id' => $user_id,
                            'vendor_id' => $vendor->get_id(),
                            'vendor_name' => $vendor_name
                        ),
                        'info'
                    );
                }
            } else {
                throw new Exception('Failed to save vendor');
            }
        } catch (Exception $e) {
            // Registrar error
            if (class_exists('WP_ALP_Security_Enhanced')) {
                WP_ALP_Security_Enhanced::log_security_event(
                    'vendor_creation_error',
                    array(
                        'user_id' => $user_id,
                        'error' => $e->getMessage()
                    ),
                    'error'
                );
            }
            
            wp_send_json_error(array(
                'message' => __('Error al crear el vendor. Por favor, inténtalo de nuevo.', 'wp-alp'),
                'code' => 'creation_error'
            ));
            return;
        }
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
 * Los estilos del botón de login se han movido al archivo custom-alp-styles.css
 * para mejorar el rendimiento y la mantenibilidad.
 */
function wp_alp_add_login_button_styles() {
    // Los estilos ahora están en el archivo CSS correspondiente
    // y se cargan mediante wp_enqueue_style
}
// La acción se mantiene por retrocompatibilidad pero no hace nada
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