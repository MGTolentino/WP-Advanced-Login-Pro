<?php
// Solo permitir acceso desde WordPress
if (!defined('ABSPATH')) {
    exit;
}

// Verificar si el formulario fue enviado
if (isset($_POST['wp_alp_save_social_options']) && current_user_can('manage_options')) {
    // Verificar nonce
    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'wp_alp_social_nonce')) {
        
        // Recuperar opciones existentes o crear array vacío
        $existing_options = get_option('wp_alp_social_options', array());
        
        // Preparar array para nuevas opciones
        $new_options = array();
        
        // Procesar Google
        $new_options['google'] = array(
            'enabled' => isset($_POST['google_enabled']) ? true : false,
            'client_id' => isset($_POST['google_client_id']) ? sanitize_text_field($_POST['google_client_id']) : '',
            'client_secret' => isset($_POST['google_client_secret']) ? sanitize_text_field($_POST['google_client_secret']) : '',
        );
        
        // Procesar Facebook
        $new_options['facebook'] = array(
            'enabled' => isset($_POST['facebook_enabled']) ? true : false,
            'client_id' => isset($_POST['facebook_client_id']) ? sanitize_text_field($_POST['facebook_client_id']) : '',
            'client_secret' => isset($_POST['facebook_client_secret']) ? sanitize_text_field($_POST['facebook_client_secret']) : '',
        );
        
        // Procesar LinkedIn
        $new_options['linkedin'] = array(
            'enabled' => isset($_POST['linkedin_enabled']) ? true : false,
            'client_id' => isset($_POST['linkedin_client_id']) ? sanitize_text_field($_POST['linkedin_client_id']) : '',
            'client_secret' => isset($_POST['linkedin_client_secret']) ? sanitize_text_field($_POST['linkedin_client_secret']) : '',
        );
        
        // Procesar Apple
        $new_options['apple'] = array(
            'enabled' => isset($_POST['apple_enabled']) ? true : false,
            'client_id' => isset($_POST['apple_client_id']) ? sanitize_text_field($_POST['apple_client_id']) : '',
            'client_secret' => isset($_POST['apple_client_secret']) ? sanitize_textarea_field($_POST['apple_client_secret']) : '',
        );
        
        // Guardar opciones
        update_option('wp_alp_social_options', $new_options);
        
        // Redirigir con mensaje de éxito
        wp_redirect(add_query_arg('settings-updated', 'true', admin_url('admin.php?page=wp-advanced-login-pro-social')));
        exit;
    }
}