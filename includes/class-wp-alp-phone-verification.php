<?php

/**
 * Maneja la verificación de números de teléfono
 * Soporta SMS, WhatsApp y llamadas estilo Airbnb
 */

class WP_ALP_Phone_Verification {

    /**
     * Tabla de verificaciones
     */
    const TABLE_NAME = 'wp_alp_phone_verifications';

    /**
     * Nombre completo de la tabla con prefijo
     */
    private $table_name;

    /**
     * Configuraciones por defecto
     */
    private $settings;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . self::TABLE_NAME;
        
        $this->settings = array(
            'code_length' => 6,
            'expiry_minutes' => 10,
            'max_attempts' => 3,
            'resend_delay' => 60, // segundos
            'providers' => array(
                'sms_enabled' => get_option('wp_alp_sms_enabled', true),
                'whatsapp_enabled' => get_option('wp_alp_whatsapp_enabled', false),
                'call_enabled' => get_option('wp_alp_call_enabled', false),
            )
        );
    }

    /**
     * Crea la tabla en la base de datos
     */
    public static function create_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            phone_full varchar(20) NOT NULL,
            country_code varchar(5) NOT NULL,
            phone_number varchar(15) NOT NULL,
            verification_code varchar(6) NOT NULL,
            method enum('sms','whatsapp','call') NOT NULL DEFAULT 'sms',
            status enum('pending','verified','expired','failed') NOT NULL DEFAULT 'pending',
            attempts int(11) NOT NULL DEFAULT 0,
            created_at datetime NOT NULL,
            expires_at datetime NOT NULL,
            verified_at datetime DEFAULT NULL,
            user_agent text,
            ip_address varchar(45),
            PRIMARY KEY (id),
            KEY phone_full (phone_full),
            KEY status (status),
            KEY expires_at (expires_at)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Envía código de verificación
     */
    public function send_verification_code($phone_full, $country_code, $phone_number, $method = 'sms') {
        // Validar entradas
        if (!$this->validate_phone($phone_full)) {
            return array(
                'success' => false,
                'message' => 'Número de teléfono inválido'
            );
        }

        // Verificar si hay intentos recientes
        if (!$this->can_send_code($phone_full)) {
            return array(
                'success' => false,
                'message' => 'Debes esperar antes de solicitar otro código'
            );
        }

        // Generar código
        $code = $this->generate_code();
        
        // Guardar en base de datos
        $verification_id = $this->save_verification($phone_full, $country_code, $phone_number, $code, $method);
        
        if (!$verification_id) {
            return array(
                'success' => false,
                'message' => 'Error interno al guardar verificación'
            );
        }

        // Enviar código según el método
        $send_result = $this->send_code_by_method($phone_full, $code, $method);
        
        if ($send_result['success']) {
            return array(
                'success' => true,
                'message' => $this->get_send_message($method, $phone_full),
                'verification_id' => $verification_id,
                'expires_in' => $this->settings['expiry_minutes'] * 60
            );
        } else {
            // Marcar como fallido
            $this->mark_failed($verification_id);
            return $send_result;
        }
    }

    /**
     * Verifica el código ingresado
     */
    public function verify_code($phone_full, $code) {
        global $wpdb;

        // Buscar verificación activa
        $verification = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_name} 
             WHERE phone_full = %s 
             AND status = 'pending' 
             AND expires_at > NOW() 
             ORDER BY created_at DESC 
             LIMIT 1",
            $phone_full
        ));

        if (!$verification) {
            return array(
                'success' => false,
                'message' => 'Código expirado o no encontrado'
            );
        }

        // Incrementar intentos
        $this->increment_attempts($verification->id);

        // Verificar intentos máximos
        if ($verification->attempts >= $this->settings['max_attempts']) {
            $this->mark_expired($verification->id);
            return array(
                'success' => false,
                'message' => 'Demasiados intentos fallidos'
            );
        }

        // Verificar código
        if ($verification->verification_code === $code) {
            $this->mark_verified($verification->id);
            return array(
                'success' => true,
                'message' => 'Teléfono verificado correctamente',
                'verification_id' => $verification->id
            );
        } else {
            return array(
                'success' => false,
                'message' => 'Código incorrecto',
                'attempts_left' => $this->settings['max_attempts'] - ($verification->attempts + 1)
            );
        }
    }

    /**
     * Valida formato de teléfono
     */
    private function validate_phone($phone_full) {
        // Formato internacional: +[1-4 dígitos][8-12 dígitos]
        return preg_match('/^\+[1-9]\d{8,14}$/', $phone_full);
    }

    /**
     * Verifica si se puede enviar otro código
     */
    private function can_send_code($phone_full) {
        global $wpdb;

        $last_send = $wpdb->get_var($wpdb->prepare(
            "SELECT created_at FROM {$this->table_name} 
             WHERE phone_full = %s 
             ORDER BY created_at DESC 
             LIMIT 1",
            $phone_full
        ));

        if (!$last_send) return true;

        $time_diff = time() - strtotime($last_send);
        return $time_diff >= $this->settings['resend_delay'];
    }

    /**
     * Genera código de verificación
     */
    private function generate_code() {
        return str_pad(rand(0, 999999), $this->settings['code_length'], '0', STR_PAD_LEFT);
    }

    /**
     * Guarda verificación en base de datos
     */
    private function save_verification($phone_full, $country_code, $phone_number, $code, $method) {
        global $wpdb;

        $expires_at = date('Y-m-d H:i:s', time() + ($this->settings['expiry_minutes'] * 60));

        $result = $wpdb->insert(
            $this->table_name,
            array(
                'phone_full' => $phone_full,
                'country_code' => $country_code,
                'phone_number' => $phone_number,
                'verification_code' => $code,
                'method' => $method,
                'status' => 'pending',
                'attempts' => 0,
                'created_at' => current_time('mysql'),
                'expires_at' => $expires_at,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'ip_address' => $this->get_client_ip()
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s')
        );

        return $result ? $wpdb->insert_id : false;
    }

    /**
     * Envía código según el método seleccionado
     */
    private function send_code_by_method($phone_full, $code, $method) {
        switch ($method) {
            case 'sms':
                return $this->send_sms($phone_full, $code);
            
            case 'whatsapp':
                return $this->send_whatsapp($phone_full, $code);
            
            case 'call':
                return $this->send_call($phone_full, $code);
            
            default:
                return array(
                    'success' => false,
                    'message' => 'Método de envío no soportado'
                );
        }
    }

    /**
     * Envía SMS usando Twilio
     */
    private function send_sms($phone_full, $code) {
        $twilio_sid = get_option('wp_alp_twilio_sid', '');
        $twilio_token = get_option('wp_alp_twilio_token', '');
        $twilio_from = get_option('wp_alp_twilio_from', '');
        
        if (empty($twilio_sid) || empty($twilio_token) || empty($twilio_from)) {
            return array(
                'success' => false,
                'message' => 'Credenciales de Twilio no configuradas'
            );
        }

        $message = "Tu código de verificación es: $code";
        
        $url = "https://api.twilio.com/2010-04-01/Accounts/$twilio_sid/Messages.json";
        
        $data = array(
            'From' => $twilio_from,
            'To' => $phone_full,
            'Body' => $message
        );
        
        $response = wp_remote_post($url, array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode("$twilio_sid:$twilio_token"),
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
            'body' => $data,
            'timeout' => 30
        ));
        
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => 'Error de conexión con Twilio: ' . $response->get_error_message()
            );
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($response_code >= 200 && $response_code < 300) {
            return array(
                'success' => true,
                'message' => 'SMS enviado correctamente'
            );
        } else {
            $error_message = isset($response_body['message']) ? $response_body['message'] : 'Error desconocido de Twilio';
            return array(
                'success' => false,
                'message' => 'Error de Twilio: ' . $error_message
            );
        }
    }

    /**
     * Envía mensaje por WhatsApp
     */
    private function send_whatsapp($phone_full, $code) {
        // TODO: Implementar WhatsApp Business API
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("WhatsApp a $phone_full: Tu código de verificación es: $code");
        }

        // Simulación por ahora
        return array(
            'success' => true,
            'message' => 'Mensaje de WhatsApp enviado'
        );
    }

    /**
     * Realiza llamada con código
     */
    private function send_call($phone_full, $code) {
        // TODO: Implementar Twilio Voice
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("Llamada a $phone_full: Tu código de verificación es: $code");
        }

        // Simulación por ahora
        return array(
            'success' => true,
            'message' => 'Llamada iniciada'
        );
    }

    /**
     * Obtiene mensaje personalizado según método
     */
    private function get_send_message($method, $phone_full) {
        $masked_phone = $this->mask_phone_number($phone_full);
        
        switch ($method) {
            case 'sms':
                return "Te hemos enviado un código por SMS al $masked_phone";
            
            case 'whatsapp':
                return "Te hemos enviado un código por WhatsApp al $masked_phone";
            
            case 'call':
                return "Te estamos llamando al $masked_phone para darte el código";
            
            default:
                return "Código enviado al $masked_phone";
        }
    }

    /**
     * Enmascara número de teléfono para privacidad
     */
    private function mask_phone_number($phone_full) {
        if (strlen($phone_full) < 8) return $phone_full;
        
        $start = substr($phone_full, 0, 4);
        $end = substr($phone_full, -2);
        $middle = str_repeat('*', strlen($phone_full) - 6);
        
        return $start . $middle . $end;
    }

    /**
     * Marca verificación como verificada
     */
    private function mark_verified($verification_id) {
        global $wpdb;
        
        $wpdb->update(
            $this->table_name,
            array(
                'status' => 'verified',
                'verified_at' => current_time('mysql')
            ),
            array('id' => $verification_id),
            array('%s', '%s'),
            array('%d')
        );
    }

    /**
     * Marca verificación como fallida
     */
    private function mark_failed($verification_id) {
        global $wpdb;
        
        $wpdb->update(
            $this->table_name,
            array('status' => 'failed'),
            array('id' => $verification_id),
            array('%s'),
            array('%d')
        );
    }

    /**
     * Marca verificación como expirada
     */
    private function mark_expired($verification_id) {
        global $wpdb;
        
        $wpdb->update(
            $this->table_name,
            array('status' => 'expired'),
            array('id' => $verification_id),
            array('%s'),
            array('%d')
        );
    }

    /**
     * Incrementa intentos de verificación
     */
    private function increment_attempts($verification_id) {
        global $wpdb;
        
        $wpdb->query($wpdb->prepare(
            "UPDATE {$this->table_name} SET attempts = attempts + 1 WHERE id = %d",
            $verification_id
        ));
    }

    /**
     * Obtiene IP del cliente
     */
    private function get_client_ip() {
        $ip_keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ip_keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = trim(explode(',', $_SERVER[$key])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    /**
     * Limpia verificaciones expiradas
     */
    public function cleanup_expired() {
        global $wpdb;
        
        return $wpdb->query(
            "DELETE FROM {$this->table_name} 
             WHERE status IN ('pending', 'expired') 
             AND expires_at < NOW()"
        );
    }

    /**
     * Verifica si un teléfono ya fue verificado recientemente
     */
    public function is_phone_recently_verified($phone_full, $hours = 24) {
        global $wpdb;
        
        $since = date('Y-m-d H:i:s', time() - ($hours * 3600));
        
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->table_name} 
             WHERE phone_full = %s 
             AND status = 'verified' 
             AND verified_at > %s",
            $phone_full,
            $since
        ));
        
        return $count > 0;
    }
}