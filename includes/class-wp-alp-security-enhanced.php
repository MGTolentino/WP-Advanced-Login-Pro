<?php
/**
 * Clase de seguridad mejorada para WP Advanced Login Pro
 * 
 * Implementa medidas de seguridad críticas para prevenir:
 * - Escalación de privilegios
 * - Creación no autorizada de cuentas admin
 * - Inyección SQL
 * - Ataques CSRF
 * - XSS
 */
class WP_ALP_Security_Enhanced {
    
    /**
     * Verifica si un usuario tiene permiso para asignar un rol específico
     * 
     * @param int $user_id ID del usuario que intenta hacer la asignación
     * @param string $role_to_assign Rol que se intenta asignar
     * @return bool
     */
    public static function can_assign_role($user_id, $role_to_assign) {
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            return false;
        }
        
        // Solo administradores pueden asignar roles
        if (!user_can($user, 'manage_options')) {
            error_log('WP_ALP Security: Usuario ' . $user_id . ' intentó asignar rol sin permisos');
            return false;
        }
        
        // Lista de roles permitidos para asignación automática
        $allowed_auto_roles = array('subscriber', 'lead');
        
        // Si el rol no está en la lista permitida, rechazar
        if (!in_array($role_to_assign, $allowed_auto_roles)) {
            error_log('WP_ALP Security: Intento de asignar rol no permitido: ' . $role_to_assign);
            return false;
        }
        
        return true;
    }
    
    /**
     * Valida que una contraseña cumpla con requisitos mínimos de seguridad
     * 
     * @param string $password
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validate_password_strength($password) {
        $errors = array();
        
        // Longitud mínima
        if (strlen($password) < 8) {
            $errors[] = __('La contraseña debe tener al menos 8 caracteres.', 'wp-alp');
        }
        
        // Debe contener al menos una mayúscula
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = __('La contraseña debe contener al menos una letra mayúscula.', 'wp-alp');
        }
        
        // Debe contener al menos una minúscula
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = __('La contraseña debe contener al menos una letra minúscula.', 'wp-alp');
        }
        
        // Debe contener al menos un número
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = __('La contraseña debe contener al menos un número.', 'wp-alp');
        }
        
        // Debe contener al menos un carácter especial
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = __('La contraseña debe contener al menos un carácter especial.', 'wp-alp');
        }
        
        return array(
            'valid' => empty($errors),
            'message' => implode(' ', $errors)
        );
    }
    
    /**
     * Valida entrada de email con verificaciones adicionales
     * 
     * @param string $email
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validate_email_input($email) {
        // Validación básica de WordPress
        if (!is_email($email)) {
            return array(
                'valid' => false,
                'message' => __('El formato del correo electrónico no es válido.', 'wp-alp')
            );
        }
        
        // Verificar longitud
        if (strlen($email) > 100) {
            return array(
                'valid' => false,
                'message' => __('El correo electrónico es demasiado largo.', 'wp-alp')
            );
        }
        
        // Verificar dominios temporales conocidos (opcional)
        $temp_domains = array('tempmail.com', '10minutemail.com', 'guerrillamail.com');
        $domain = substr(strrchr($email, "@"), 1);
        
        if (in_array($domain, $temp_domains)) {
            return array(
                'valid' => false,
                'message' => __('No se permiten correos temporales.', 'wp-alp')
            );
        }
        
        return array('valid' => true, 'message' => '');
    }
    
    /**
     * Rate limiting mejorado con protección por cuenta y IP
     * 
     * @param string $identifier Email, teléfono o ID de usuario
     * @param string $action Acción que se está limitando
     * @param int $max_attempts Máximo número de intentos
     * @param int $timeframe Ventana de tiempo en segundos
     * @return bool True si está limitado
     */
    public static function is_rate_limited_enhanced($identifier, $action, $max_attempts = 5, $timeframe = 300) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        
        // Limitar por identificador (cuenta)
        $account_key = 'wp_alp_rl_acc_' . md5($identifier . '_' . $action);
        $account_attempts = get_transient($account_key);
        
        if ($account_attempts >= $max_attempts) {
            error_log('WP_ALP Security: Rate limit alcanzado para cuenta ' . $identifier);
            return true;
        }
        
        // Limitar por IP
        $ip_key = 'wp_alp_rl_ip_' . md5($ip . '_' . $action);
        $ip_attempts = get_transient($ip_key);
        
        if ($ip_attempts >= ($max_attempts * 2)) { // Doble límite para IP
            error_log('WP_ALP Security: Rate limit alcanzado para IP ' . $ip);
            return true;
        }
        
        // Incrementar contadores
        set_transient($account_key, ($account_attempts ?: 0) + 1, $timeframe);
        set_transient($ip_key, ($ip_attempts ?: 0) + 1, $timeframe);
        
        return false;
    }
    
    /**
     * Genera un token JWT seguro para autenticación social
     * 
     * @param array $payload Datos a incluir en el token
     * @param int $expiry Tiempo de expiración en segundos
     * @return string Token JWT
     */
    public static function generate_jwt_token($payload, $expiry = 3600) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        
        $payload['iat'] = time();
        $payload['exp'] = time() + $expiry;
        $payload['jti'] = wp_generate_uuid4(); // ID único del token
        
        $payload_json = json_encode($payload);
        
        $base64_header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64_payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload_json));
        
        $secret = wp_salt('auth');
        $signature = hash_hmac('sha256', $base64_header . '.' . $base64_payload, $secret, true);
        $base64_signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64_header . '.' . $base64_payload . '.' . $base64_signature;
    }
    
    /**
     * Verifica un token JWT
     * 
     * @param string $token
     * @return array|false Payload si es válido, false si no
     */
    public static function verify_jwt_token($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        
        list($header_b64, $payload_b64, $signature_b64) = $parts;
        
        // Verificar firma
        $secret = wp_salt('auth');
        $signature = base64_decode(str_replace(['-', '_'], ['+', '/'], $signature_b64));
        $expected_signature = hash_hmac('sha256', $header_b64 . '.' . $payload_b64, $secret, true);
        
        if (!hash_equals($signature, $expected_signature)) {
            error_log('WP_ALP Security: Token JWT con firma inválida');
            return false;
        }
        
        // Decodificar payload
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payload_b64)), true);
        
        // Verificar expiración
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            error_log('WP_ALP Security: Token JWT expirado');
            return false;
        }
        
        return $payload;
    }
    
    /**
     * Sanitiza datos para prevenir XSS con validación estricta
     * 
     * @param mixed $data
     * @param string $context Contexto de uso (html, attribute, js, url)
     * @return mixed
     */
    public static function sanitize_output($data, $context = 'html') {
        if (is_array($data)) {
            return array_map(function($item) use ($context) {
                return self::sanitize_output($item, $context);
            }, $data);
        }
        
        switch ($context) {
            case 'html':
                return wp_kses_post($data);
            case 'attribute':
                return esc_attr($data);
            case 'js':
                return esc_js($data);
            case 'url':
                return esc_url($data);
            case 'sql':
                global $wpdb;
                return esc_sql($data);
            default:
                return sanitize_text_field($data);
        }
    }
    
    /**
     * Verifica permisos antes de operaciones críticas
     * 
     * @param string $capability Capacidad requerida
     * @param int $user_id ID del usuario (opcional, usa el actual si no se proporciona)
     * @return bool
     */
    public static function check_permission($capability, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            return false;
        }
        
        // Registrar intentos de acceso a capacidades críticas
        $critical_caps = array('manage_options', 'create_users', 'delete_users', 'edit_users');
        if (in_array($capability, $critical_caps)) {
            error_log('WP_ALP Security: Usuario ' . $user_id . ' intentando acceder a capacidad crítica: ' . $capability);
        }
        
        return user_can($user_id, $capability);
    }
    
    /**
     * Genera un nonce seguro con contexto adicional
     * 
     * @param string $action
     * @param array $context Datos adicionales para el contexto
     * @return string
     */
    public static function create_secure_nonce($action, $context = array()) {
        $context_string = '';
        if (!empty($context)) {
            ksort($context); // Ordenar para consistencia
            $context_string = serialize($context);
        }
        
        $enhanced_action = $action . '_' . md5($context_string);
        return wp_create_nonce($enhanced_action);
    }
    
    /**
     * Verifica un nonce seguro con contexto
     * 
     * @param string $nonce
     * @param string $action
     * @param array $context
     * @return bool|int
     */
    public static function verify_secure_nonce($nonce, $action, $context = array()) {
        $context_string = '';
        if (!empty($context)) {
            ksort($context);
            $context_string = serialize($context);
        }
        
        $enhanced_action = $action . '_' . md5($context_string);
        return wp_verify_nonce($nonce, $enhanced_action);
    }
    
    /**
     * Registra eventos de seguridad
     * 
     * @param string $event_type Tipo de evento
     * @param array $data Datos del evento
     * @param string $severity 'info', 'warning', 'error', 'critical'
     */
    public static function log_security_event($event_type, $data = array(), $severity = 'info') {
        $log_entry = array(
            'timestamp' => current_time('mysql'),
            'event_type' => $event_type,
            'severity' => $severity,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'user_id' => get_current_user_id(),
            'data' => $data
        );
        
        // Guardar en transient para análisis (mantener por 7 días)
        $logs = get_transient('wp_alp_security_logs') ?: array();
        array_unshift($logs, $log_entry);
        
        // Mantener solo los últimos 1000 eventos
        $logs = array_slice($logs, 0, 1000);
        set_transient('wp_alp_security_logs', $logs, 7 * DAY_IN_SECONDS);
        
        // Para eventos críticos, notificar al admin
        if ($severity === 'critical') {
            $admin_email = get_option('admin_email');
            $subject = sprintf(__('[%s] Evento de seguridad crítico', 'wp-alp'), get_bloginfo('name'));
            $message = sprintf(
                __("Se ha detectado un evento de seguridad crítico:\n\nTipo: %s\nIP: %s\nUsuario ID: %s\nDetalles: %s", 'wp-alp'),
                $event_type,
                $log_entry['ip'],
                $log_entry['user_id'],
                json_encode($data)
            );
            
            wp_mail($admin_email, $subject, $message);
        }
        
        // Log en archivo de error para eventos warning y superiores
        if (in_array($severity, array('warning', 'error', 'critical'))) {
            error_log('WP_ALP Security [' . $severity . ']: ' . $event_type . ' - ' . json_encode($log_entry));
        }
    }
}