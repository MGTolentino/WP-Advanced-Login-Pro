/**
 * AJAX login handler.
 *
 * @since    1.0.0
 */
public function ajax_login() {
    // Prevenir redirecciones durante solicitudes AJAX - no quitar este filtro
    add_filter('wp_redirect', '__return_false', 999);
    
    // Verificar si es una solicitud AJAX
    $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
    
    if (!$is_ajax) {
        // Si no es una solicitud AJAX, usar el flujo normal
        $this->process_login_form();
        return;
    }
    
    // Check AJAX referer 
    $nonce = isset($_POST['security']) ? $_POST['security'] : (isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '');
    if (!wp_verify_nonce($nonce, 'wp_alp_public_nonce')) {
        wp_send_json_error(array(
            'message' => __('Security token expired. Please refresh the page and try again.', 'wp-alp'),
        ));
        return;
    }
    
    $response = $this->forms->process_login($_POST);
    
    // Manejar la respuesta AJAX independientemente del tipo de redirección
    if (is_wp_error($response)) {
        wp_send_json_error(array(
            'message' => $response->get_error_message(),
        ));
    } else {
        // Verificar si el perfil necesita completarse
        $user_id = get_current_user_id();
        $profile_status = get_user_meta($user_id, 'wp_alp_profile_status', true);
        
        wp_send_json_success(array(
            'message' => __('Login successful. Redirecting...', 'wp-alp'),
            'redirect' => $response['redirect'],
            'needs_profile_completion' => ($profile_status === 'incomplete')
        ));
    }
    
    // Note: No eliminar el filtro de redirección para asegurar que no ocurran redirecciones
}

/**
 * AJAX user registration handler.
 *
 * @since    1.0.0
 */
public function ajax_register_user() {
    // Prevenir redirecciones durante solicitudes AJAX - no quitar este filtro
    add_filter('wp_redirect', '__return_false', 999);
    
    // Verificar si es una solicitud AJAX
    $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
    
    if (!$is_ajax) {
        // Si no es una solicitud AJAX, usar el flujo normal
        $this->process_register_user_form();
        return;
    }
    
    // Check AJAX referer
    $nonce = isset($_POST['security']) ? $_POST['security'] : (isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '');
    if (!wp_verify_nonce($nonce, 'wp_alp_public_nonce')) {
        wp_send_json_error(array(
            'message' => __('Security token expired. Please refresh the page and try again.', 'wp-alp'),
        ));
        return;
    }
    
    $response = $this->forms->process_user_registration($_POST, $this->user_manager);
    
    // Manejar la respuesta AJAX independientemente del tipo de redirección
    if (is_wp_error($response)) {
        wp_send_json_error(array(
            'message' => $response->get_error_message(),
        ));
    } else {
        wp_send_json_success(array(
            'message' => __('Registration successful. Redirecting...', 'wp-alp'),
            'redirect' => $response['redirect'],
            'user_id' => $response['user_id'],
            'needs_profile_completion' => true // Nuevos registros siempre necesitan completar el perfil
        ));
    }
    
    // Note: No eliminar el filtro de redirección para asegurar que no ocurran redirecciones
}

/**
 * AJAX vendor registration handler.
 *
 * @since    1.0.0
 */
public function ajax_register_vendor() {
    // Prevenir redirecciones durante solicitudes AJAX - no quitar este filtro
    add_filter('wp_redirect', '__return_false', 999);
    
    // Verificar si es una solicitud AJAX
    $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
    
    if (!$is_ajax) {
        // Si no es una solicitud AJAX, usar el flujo normal
        $this->process_register_vendor_form();
        return;
    }
    
    // Check AJAX referer
    $nonce = isset($_POST['security']) ? $_POST['security'] : (isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '');
    if (!wp_verify_nonce($nonce, 'wp_alp_public_nonce')) {
        wp_send_json_error(array(
            'message' => __('Security token expired. Please refresh the page and try again.', 'wp-alp'),
        ));
        return;
    }
    
    $response = $this->forms->process_vendor_registration($_POST, $this->user_manager);
    
    // Manejar la respuesta AJAX independientemente del tipo de redirección
    if (is_wp_error($response)) {
        wp_send_json_error(array(
            'message' => $response->get_error_message(),
        ));
    } else {
        wp_send_json_success(array(
            'message' => __('Vendor registration successful. Redirecting...', 'wp-alp'),
            'redirect' => $response['redirect'],
            'user_id' => $response['user_id'],
            'vendor_id' => $response['vendor_id'],
        ));
    }
    
    // Note: No eliminar el filtro de redirección para asegurar que no ocurran redirecciones
}

/**
 * AJAX profile completion handler.
 *
 * @since    1.0.0
 */
public function ajax_complete_profile() {
    // Prevenir redirecciones durante solicitudes AJAX - no quitar este filtro
    add_filter('wp_redirect', '__return_false', 999);
    
    // Verificar si es una solicitud AJAX
    $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
    
    if (!$is_ajax) {
        // Si no es una solicitud AJAX, usar el flujo normal
        $this->process_profile_completion_form();
        return;
    }
    
    // Check AJAX referer
    $nonce = isset($_POST['security']) ? $_POST['security'] : (isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '');
    if (!wp_verify_nonce($nonce, 'wp_alp_public_nonce')) {
        wp_send_json_error(array(
            'message' => __('Security token expired. Please refresh the page and try again.', 'wp-alp'),
        ));
        return;
    }
    
    $response = $this->forms->process_profile_completion($_POST, $this->user_manager, $this->jetengine);
    
    // Manejar la respuesta AJAX independientemente del tipo de redirección
    if (is_wp_error($response)) {
        wp_send_json_error(array(
            'message' => $response->get_error_message(),
        ));
    } else {
        wp_send_json_success(array(
            'message' => __('Profile completed successfully. Redirecting...', 'wp-alp'),
            'redirect' => $response['redirect'],
            'lead_id' => $response['lead_id'],
        ));
    }
    
    // Note: No eliminar el filtro de redirección para asegurar que no ocurran redirecciones
}

/**
 * Handle social login.
 *
 * @since    1.0.0
 */
public function handle_social_login() {
    // Prevenir redirecciones durante solicitudes AJAX - no quitar este filtro
    add_filter('wp_redirect', '__return_false', 999);
    
    // Verificar si es una solicitud AJAX
    $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] === 'true';
    
    if (!$is_ajax) {
        // Procesar sin AJAX, pero prevenir redirecciones no controladas
        add_filter('wp_redirect', array($this, 'capture_redirect_url'), 10, 1);
        // Continuamos con el proceso normal
    }
    
    $provider = sanitize_text_field($_POST['provider']);
    $code = sanitize_text_field($_POST['code']);
    $state = sanitize_text_field($_POST['state']);
    
    $response = $this->forms->process_social_login($provider, $code, $state, $this->social, $this->user_manager);
    
    // Determinar cómo responder basado en si es AJAX o no
    if ($is_ajax) {
        if (is_wp_error($response)) {
            wp_send_json_error(array(
                'message' => $response->get_error_message(),
            ));
        } else {
            // Verificar si el perfil necesita completarse
            $user_id = $response['user_id'];
            $profile_status = get_user_meta($user_id, 'wp_alp_profile_status', true);