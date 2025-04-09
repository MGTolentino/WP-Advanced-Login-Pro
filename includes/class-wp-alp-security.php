<?php
/**
 * Maneja la seguridad del plugin.
 *
 * Esta clase contiene métodos para proteger el plugin contra
 * ataques comunes y vulnerabilidades.
 */
class WP_ALP_Security {

    /**
     * Genera un token CSRF.
     *
     * @return string El token CSRF.
     */
    public static function generate_csrf_token() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['wp_alp_csrf_token'])) {
            $_SESSION['wp_alp_csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['wp_alp_csrf_token'];
    }

    /**
     * Verifica un token CSRF.
     *
     * @param string $token El token a verificar.
     * @return bool True si el token es válido, false en caso contrario.
     */
    public static function verify_csrf_token($token) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['wp_alp_csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['wp_alp_csrf_token'], $token);
    }

    /**
     * Genera un código de verificación para email o SMS.
     *
     * @param int $length Longitud del código. Predeterminado: 6.
     * @return string El código generado.
     */
    public static function generate_verification_code($length = 6) {
        $characters = '0123456789';
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $code;
    }

    /**
     * Sanitiza un número de teléfono.
     *
     * @param string $phone_number El número a sanitizar.
     * @return string El número sanitizado.
     */
    public static function sanitize_phone_number($phone_number) {
        // Eliminar todos los caracteres no numéricos
        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
        
        return $phone_number;
    }

    /**
     * Verifica si una dirección IP está haciendo demasiadas solicitudes.
     *
     * @param string $ip La dirección IP a verificar.
     * @param string $action La acción que se está realizando.
     * @param int $max_attempts Máximo número de intentos permitidos.
     * @param int $timeframe Período de tiempo en segundos.
     * @return bool True si está limitado, false si no.
     */
    public static function is_rate_limited($ip, $action, $max_attempts = 5, $timeframe = 300) {
        $transient_name = 'wp_alp_rate_limit_' . md5($ip . '_' . $action);
        $attempts = get_transient($transient_name);
        
        if (false === $attempts) {
            set_transient($transient_name, 1, $timeframe);
            return false;
        }
        
        if ($attempts >= $max_attempts) {
            return true;
        }
        
        set_transient($transient_name, $attempts + 1, $timeframe);
        return false;
    }

    /**
     * Sanitiza un array de datos de entrada.
     *
     * @param array $data Los datos a sanitizar.
     * @return array Los datos sanitizados.
     */
    public static function sanitize_input_data($data) {
        $sanitized = array();
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitize_input_data($value);
            } else {
                switch ($key) {
                    case 'email':
                        $sanitized[$key] = sanitize_email($value);
                        break;
                    case 'phone':
                    case 'telefono':
                    case 'celular':
                        $sanitized[$key] = self::sanitize_phone_number($value);
                        break;
                    case 'url':
                        $sanitized[$key] = esc_url_raw($value);
                        break;
                    default:
                        $sanitized[$key] = sanitize_text_field($value);
                        break;
                }
            }
        }
        
        return $sanitized;
    }

    /**
     * Genera un token seguro para autenticación.
     *
     * @return string El token generado.
     */
    public static function generate_auth_token() {
        return bin2hex(random_bytes(32));
    }

    /**
     * Verifica el nonce de WordPress.
     *
     * @param string $nonce El nonce a verificar.
     * @param string $action La acción asociada con el nonce.
     * @return bool True si es válido, false en caso contrario.
     */
    public static function verify_nonce($nonce, $action) {
        return wp_verify_nonce($nonce, $action);
    }
}