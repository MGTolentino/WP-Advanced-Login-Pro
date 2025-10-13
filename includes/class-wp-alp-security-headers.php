<?php
/**
 * Clase para implementar headers de seguridad HTTP
 * 
 * Agrega headers de seguridad importantes para proteger contra:
 * - Ataques XSS
 * - Clickjacking
 * - Ataques CSRF
 * - Information disclosure
 */
class WP_ALP_Security_Headers {
    
    /**
     * Inicializa los headers de seguridad
     */
    public static function init() {
        // Agregar headers en las páginas del plugin
        add_action('wp_head', array(__CLASS__, 'add_meta_headers'), 1);
        add_action('send_headers', array(__CLASS__, 'add_http_headers'), 1);
        
        // Headers específicos para admin
        add_action('admin_head', array(__CLASS__, 'add_admin_meta_headers'), 1);
        
        // Headers para AJAX
        add_action('wp_ajax_validate_user', array(__CLASS__, 'add_ajax_headers'), 1);
        add_action('wp_ajax_nopriv_validate_user', array(__CLASS__, 'add_ajax_headers'), 1);
        add_action('wp_ajax_register_user', array(__CLASS__, 'add_ajax_headers'), 1);
        add_action('wp_ajax_nopriv_register_user', array(__CLASS__, 'add_ajax_headers'), 1);
        add_action('wp_ajax_login_user', array(__CLASS__, 'add_ajax_headers'), 1);
        add_action('wp_ajax_nopriv_login_user', array(__CLASS__, 'add_ajax_headers'), 1);
        add_action('wp_ajax_social_login', array(__CLASS__, 'add_ajax_headers'), 1);
        add_action('wp_ajax_nopriv_social_login', array(__CLASS__, 'add_ajax_headers'), 1);
    }
    
    /**
     * Agrega headers HTTP de seguridad
     */
    public static function add_http_headers() {
        // Solo aplicar CSP en páginas del plugin para evitar conflictos
        if (!headers_sent() && self::should_apply_headers()) {
            header('X-Frame-Options: SAMEORIGIN');
            
            // Content Security Policy básico - SOLO en páginas del plugin
            $csp = self::get_content_security_policy();
            if ($csp) {
                header('Content-Security-Policy: ' . $csp);
            }
            
            // Otros headers de seguridad
            header('X-Content-Type-Options: nosniff');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Habilitar HSTS si es HTTPS
            if (is_ssl()) {
                header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
            }
            
            // Feature Policy para restringir APIs
            header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        }
    }
    
    /**
     * Agrega meta headers en el HTML
     */
    public static function add_meta_headers() {
        // Solo en páginas que usan nuestro plugin
        if (self::should_apply_headers()) {
            echo '<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">' . "\n";
            echo '<meta http-equiv="X-Content-Type-Options" content="nosniff">' . "\n";
            echo '<meta http-equiv="X-XSS-Protection" content="1; mode=block">' . "\n";
            echo '<meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">' . "\n";
            
            // CSP como meta tag también
            $csp = self::get_content_security_policy();
            if ($csp) {
                echo '<meta http-equiv="Content-Security-Policy" content="' . esc_attr($csp) . '">' . "\n";
            }
        }
    }
    
    /**
     * Agrega headers específicos para admin
     */
    public static function add_admin_meta_headers() {
        // Headers más estrictos para el admin
        echo '<meta http-equiv="X-Frame-Options" content="DENY">' . "\n";
        echo '<meta http-equiv="X-Content-Type-Options" content="nosniff">' . "\n";
        echo '<meta http-equiv="X-XSS-Protection" content="1; mode=block">' . "\n";
    }
    
    /**
     * Agrega headers para solicitudes AJAX
     */
    public static function add_ajax_headers() {
        if (!headers_sent()) {
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: DENY');
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
    }
    
    /**
     * Determina si deben aplicarse los headers
     * 
     * @return bool
     */
    private static function should_apply_headers() {
        global $post;
        
        // En páginas que usan nuestras templates
        if ($post && is_page()) {
            $template_name = get_post_meta($post->ID, '_wp_page_template', true);
            
            $our_templates = array(
                'login-page-template.php',
                'vendor-page-template.php',
                'vendor-steps-template.php'
            );
            
            foreach ($our_templates as $template) {
                if (strpos($template_name, $template) !== false) {
                    return true;
                }
            }
        }
        
        // En admin del plugin
        if (is_admin()) {
            $screen = get_current_screen();
            if ($screen && strpos($screen->id, 'wp-advanced-login-pro') !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Genera la política de seguridad de contenido (CSP)
     * 
     * @return string
     */
    private static function get_content_security_policy() {
        $directives = array();
        
        // Script sources - permitir el dominio actual y CDNs necesarios
        $script_src = array(
            "'self'",
            "'unsafe-inline'", // Necesario para algunos scripts de WordPress
            "'unsafe-eval'",   // Necesario para algunos plugins
            "https://accounts.google.com",
            "https://connect.facebook.net",
            "https://appleid.cdn-apple.com",
            "https://www.google.com",
            "https://www.google-analytics.com",
            "https://www.googletagmanager.com",
            "https://apis.google.com",
            "https://www.gstatic.com" // Necesario para reCAPTCHA
        );
        
        // Style sources
        $style_src = array(
            "'self'",
            "'unsafe-inline'", // Necesario para estilos dinámicos
            "https://fonts.googleapis.com",
            "https://accounts.google.com",
            "https://www.gstatic.com" // Estilos de reCAPTCHA
        );
        
        // Font sources
        $font_src = array(
            "'self'",
            "https://fonts.gstatic.com"
        );
        
        // Image sources
        $img_src = array(
            "'self'",
            "data:",
            "https:",
            "*.googleusercontent.com",
            "*.facebook.com",
            "*.fbcdn.net"
        );
        
        // Frame sources para OAuth y reCAPTCHA
        $frame_src = array(
            "'self'",
            "https://accounts.google.com",
            "https://www.facebook.com",
            "https://appleid.apple.com",
            "https://www.google.com",
            "https://recaptcha.google.com"
        );
        
        // Connect sources para AJAX y API calls
        $connect_src = array(
            "'self'",
            "https://accounts.google.com",
            "https://www.googleapis.com",
            "https://graph.facebook.com",
            "https://appleid.apple.com",
            "https://www.google.com",
            "https://www.gstatic.com",
            "https://recaptcha.google.com"
        );
        
        // Construir CSP
        $directives['default-src'] = "'self'";
        $directives['script-src'] = implode(' ', $script_src);
        $directives['style-src'] = implode(' ', $style_src);
        $directives['font-src'] = implode(' ', $font_src);
        $directives['img-src'] = implode(' ', $img_src);
        $directives['frame-src'] = implode(' ', $frame_src);
        $directives['connect-src'] = implode(' ', $connect_src);
        $directives['object-src'] = "'none'";
        $directives['base-uri'] = "'self'";
        $directives['form-action'] = "'self'";
        
        // Permitir personalización a través de filtro
        $directives = apply_filters('wp_alp_csp_directives', $directives);
        
        // Convertir a string
        $csp_parts = array();
        foreach ($directives as $directive => $sources) {
            $csp_parts[] = $directive . ' ' . $sources;
        }
        
        return implode('; ', $csp_parts);
    }
    
    /**
     * Configura headers seguros para uploads
     */
    public static function secure_upload_headers() {
        add_filter('wp_handle_upload_prefilter', array(__CLASS__, 'validate_upload_security'));
    }
    
    /**
     * Valida la seguridad de archivos subidos
     * 
     * @param array $file
     * @return array
     */
    public static function validate_upload_security($file) {
        // Lista de extensiones peligrosas
        $dangerous_extensions = array(
            'php', 'php3', 'php4', 'php5', 'phtml', 'pht',
            'js', 'exe', 'scr', 'bat', 'cmd', 'com', 'pif',
            'vbs', 'vbe', 'ws', 'wsf', 'wsc', 'wsh'
        );
        
        $file_info = pathinfo($file['name']);
        $extension = strtolower($file_info['extension'] ?? '');
        
        if (in_array($extension, $dangerous_extensions)) {
            $file['error'] = __('Tipo de archivo no permitido por razones de seguridad.', 'wp-alp');
            
            // Registrar intento de subida peligrosa
            if (class_exists('WP_ALP_Security_Enhanced')) {
                WP_ALP_Security_Enhanced::log_security_event(
                    'dangerous_file_upload_blocked',
                    array(
                        'filename' => $file['name'],
                        'extension' => $extension,
                        'user_id' => get_current_user_id()
                    ),
                    'warning'
                );
            }
        }
        
        return $file;
    }
    
    /**
     * Configura cookies seguras
     */
    public static function secure_cookies() {
        // Solo en HTTPS
        if (!is_ssl()) {
            return;
        }
        
        // Configurar cookies seguras para el plugin
        add_action('wp_login', array(__CLASS__, 'set_secure_cookie_flags'), 10, 2);
        
        // Configurar headers de cookie
        if (!headers_sent()) {
            header('Set-Cookie: SameSite=Strict; Secure; HttpOnly', false);
        }
    }
    
    /**
     * Establece flags seguros en cookies
     * 
     * @param string $user_login
     * @param WP_User $user
     */
    public static function set_secure_cookie_flags($user_login, $user) {
        // WordPress ya maneja esto, pero podemos agregar validación adicional
        if (class_exists('WP_ALP_Security_Enhanced')) {
            WP_ALP_Security_Enhanced::log_security_event(
                'secure_login_completed',
                array('user_id' => $user->ID),
                'info'
            );
        }
    }
    
    /**
     * Elimina headers que revelan información
     */
    public static function remove_sensitive_headers() {
        // Eliminar header de versión de WordPress
        remove_action('wp_head', 'wp_generator');
        
        // Filtro para eliminar versiones de scripts y estilos
        add_filter('style_loader_src', array(__CLASS__, 'remove_version_strings'), 9999);
        add_filter('script_loader_src', array(__CLASS__, 'remove_version_strings'), 9999);
        
        // Eliminar header Server si es posible
        add_action('send_headers', function() {
            if (!headers_sent()) {
                header_remove('Server');
                header_remove('X-Powered-By');
            }
        });
    }
    
    /**
     * Elimina strings de versión de URLs de recursos
     * 
     * @param string $src
     * @return string
     */
    public static function remove_version_strings($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
}