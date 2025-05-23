<?php
/**
 * Maneja la protección de contenido y redirecciones.
 */

// Prevenir acceso directo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Clase para manejar la protección de contenido.
 */
class WP_ALP_Redirect_Protection {
    
    /**
     * Inicializar la protección.
     */
    public static function init() {
        add_action('template_redirect', array(__CLASS__, 'check_access'));
    }
    
    /**
     * Verifica si el usuario tiene acceso a la página actual.
     */
    public static function check_access() {
        // No hacer nada en admin o feed
        if (is_admin() || is_feed()) {
            return;
        }
        
        // No proteger la página principal
        if (is_front_page() || is_home()) {
            return;
        }
        
        // No proteger la página de login
        global $post;
        if ($post && has_shortcode($post->post_content, 'wp_alp_login_page')) {
            return;
        }
        
        // Verificar si el usuario está logueado
        if (!is_user_logged_in()) {
            // Obtener la página de login
            $login_page_id = get_option('wp_alp_login_page_id', 0);
            
            // Si no hay página de login configurada, usar la página principal
            if (empty($login_page_id)) {
                $redirect_url = home_url();
            } else {
                $redirect_url = get_permalink($login_page_id);
            }
            
            // Añadir parámetro de redirección
            $current_url = esc_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            $redirect_url = add_query_arg('redirect_to', urlencode($current_url), $redirect_url);
            
            // Redirigir
            wp_redirect($redirect_url);
            exit;
        }
        
        // Verificar si el usuario necesita completar perfil
        $user_id = get_current_user_id();
        $user_type = get_user_meta($user_id, 'wp_alp_user_type', true);
        $profile_status = get_user_meta($user_id, 'wp_alp_profile_status', true);
        
        // Si es subscriber con perfil incompleto, redirigir a página de login
        if (current_user_can('subscriber') && ($user_type === '' || $profile_status === 'incomplete')) {
            // Obtener la página de login
            $login_page_id = get_option('wp_alp_login_page_id', 0);
            
            // Si no hay página de login configurada, usar la página principal
            if (empty($login_page_id)) {
                $redirect_url = home_url();
            } else {
                $redirect_url = get_permalink($login_page_id);
            }
            
            // Añadir parámetros para completar perfil
            $redirect_url = add_query_arg(array(
                'wp_alp_action' => 'complete_profile',
                'wp_alp_user_id' => $user_id,
                'wp_alp_token' => wp_create_nonce('wp_alp_complete_profile_' . $user_id),
            ), $redirect_url);
            
            // Redirigir
            wp_redirect($redirect_url);
            exit;
        }
    }
}

// Inicializar la protección
WP_ALP_Redirect_Protection::init();