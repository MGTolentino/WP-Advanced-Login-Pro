<?php
/**
 * Maneja la seguridad del plugin.
 *
 * Esta clase contiene métodos para proteger el plugin contra
 * ataques comunes y vulnerabilidades.
 */

// Incluir clase de seguridad mejorada
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wp-alp-security-enhanced.php';

class WP_ALP_Security extends WP_ALP_Security_Enhanced {

    /**
     * Genera un token CSRF usando WordPress nonces.
     *
     * @return string El token CSRF.
     */
    public static function generate_csrf_token() {
        // Usar WordPress nonces en lugar de sesiones PHP
        return wp_create_nonce('wp_alp_csrf_token');
    }

    /**
     * Verifica un token CSRF.
     *
     * @param string $token El token a verificar.
     * @return bool True si el token es válido, false en caso contrario.
     */
    public static function verify_csrf_token($token) {
        // Usar WordPress nonces para verificación
        return wp_verify_nonce($token, 'wp_alp_csrf_token') !== false;
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
     * Usa el método mejorado de la clase padre.
     *
     * @param string $ip La dirección IP a verificar.
     * @param string $action La acción que se está realizando.
     * @param int $max_attempts Máximo número de intentos permitidos.
     * @param int $timeframe Período de tiempo en segundos.
     * @return bool True si está limitado, false si no.
     */
    public static function is_rate_limited($ip, $action, $max_attempts = 5, $timeframe = 300) {
        // Usar el método mejorado que incluye protección por cuenta
        return parent::is_rate_limited_enhanced($ip, $action, $max_attempts, $timeframe);
    }

    /**
     * Sanitiza un array de datos de entrada con validación adicional.
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
                        $email = sanitize_email($value);
                        // Validación adicional de email
                        $validation = parent::validate_email_input($email);
                        if ($validation['valid']) {
                            $sanitized[$key] = $email;
                        } else {
                            $sanitized[$key] = ''; // Email inválido
                        }
                        break;
                    case 'phone':
                    case 'telefono':
                    case 'celular':
                        $sanitized[$key] = self::sanitize_phone_number($value);
                        break;
                    case 'url':
                        $sanitized[$key] = esc_url_raw($value);
                        break;
                    case 'password':
                        // No sanitizar contraseñas, pero validar fortaleza
                        $sanitized[$key] = $value;
                        break;
                    default:
                        // Usar sanitización mejorada con contexto
                        $sanitized[$key] = parent::sanitize_output($value, 'html');
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