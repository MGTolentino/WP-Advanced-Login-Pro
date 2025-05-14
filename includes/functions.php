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