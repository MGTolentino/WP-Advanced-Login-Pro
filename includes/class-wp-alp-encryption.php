<?php
/**
 * Clase para manejo de encriptación de datos sensibles
 * 
 * Encripta y desencripta datos sensibles como:
 * - Claves API de proveedores sociales
 * - Tokens de autenticación
 * - Códigos de verificación
 */
class WP_ALP_Encryption {
    
    /**
     * Algoritmo de encriptación
     */
    private static $cipher = 'aes-256-cbc';
    
    /**
     * Genera o obtiene la clave de encriptación
     * 
     * @return string
     */
    private static function get_encryption_key() {
        $key = get_option('wp_alp_encryption_key');
        
        if (!$key) {
            // Generar nueva clave si no existe
            $key = self::generate_key();
            update_option('wp_alp_encryption_key', $key);
            
            // Registrar evento de seguridad
            if (class_exists('WP_ALP_Security_Enhanced')) {
                WP_ALP_Security_Enhanced::log_security_event(
                    'encryption_key_generated',
                    array(),
                    'info'
                );
            }
        }
        
        return $key;
    }
    
    /**
     * Genera una clave de encriptación segura
     * 
     * @return string
     */
    private static function generate_key() {
        // Usar sal de WordPress para mayor entropía
        $salt = wp_salt('auth') . wp_salt('secure_auth') . wp_salt('logged_in') . wp_salt('nonce');
        
        // Combinar con datos únicos del sitio
        $site_data = get_option('siteurl') . get_option('admin_email') . get_option('db_version');
        
        // Generar clave usando hash seguro
        return hash('sha256', $salt . $site_data . random_bytes(32));
    }
    
    /**
     * Encripta un string
     * 
     * @param string $data Datos a encriptar
     * @return string|false Datos encriptados en base64 o false en error
     */
    public static function encrypt($data) {
        if (empty($data)) {
            return $data;
        }
        
        try {
            $key = self::get_encryption_key();
            
            // Generar IV aleatorio
            $iv_length = openssl_cipher_iv_length(self::$cipher);
            $iv = openssl_random_pseudo_bytes($iv_length);
            
            // Encriptar datos
            $encrypted = openssl_encrypt($data, self::$cipher, $key, 0, $iv);
            
            if ($encrypted === false) {
                error_log('WP_ALP Encryption: Error encrypting data');
                return false;
            }
            
            // Combinar IV y datos encriptados
            $result = base64_encode($iv . $encrypted);
            
            return $result;
            
        } catch (Exception $e) {
            error_log('WP_ALP Encryption: Exception during encryption - ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Desencripta un string
     * 
     * @param string $encrypted_data Datos encriptados en base64
     * @return string|false Datos desencriptados o false en error
     */
    public static function decrypt($encrypted_data) {
        if (empty($encrypted_data)) {
            return $encrypted_data;
        }
        
        try {
            $key = self::get_encryption_key();
            
            // Decodificar de base64
            $data = base64_decode($encrypted_data);
            
            if ($data === false) {
                return false;
            }
            
            // Extraer IV
            $iv_length = openssl_cipher_iv_length(self::$cipher);
            $iv = substr($data, 0, $iv_length);
            $encrypted = substr($data, $iv_length);
            
            // Desencriptar
            $decrypted = openssl_decrypt($encrypted, self::$cipher, $key, 0, $iv);
            
            if ($decrypted === false) {
                error_log('WP_ALP Encryption: Error decrypting data');
                return false;
            }
            
            return $decrypted;
            
        } catch (Exception $e) {
            error_log('WP_ALP Encryption: Exception during decryption - ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Encripta y guarda una opción de WordPress
     * 
     * @param string $option_name Nombre de la opción
     * @param string $value Valor a encriptar y guardar
     * @return bool
     */
    public static function set_encrypted_option($option_name, $value) {
        if (empty($value)) {
            return delete_option($option_name);
        }
        
        $encrypted = self::encrypt($value);
        if ($encrypted === false) {
            return false;
        }
        
        return update_option($option_name, $encrypted);
    }
    
    /**
     * Obtiene y desencripta una opción de WordPress
     * 
     * @param string $option_name Nombre de la opción
     * @param mixed $default Valor por defecto si no existe
     * @return string|mixed
     */
    public static function get_encrypted_option($option_name, $default = '') {
        $encrypted = get_option($option_name, null);
        
        if ($encrypted === null) {
            return $default;
        }
        
        if (empty($encrypted)) {
            return $default;
        }
        
        $decrypted = self::decrypt($encrypted);
        if ($decrypted === false) {
            return $default;
        }
        
        return $decrypted;
    }
    
    /**
     * Verifica si una opción está encriptada
     * 
     * @param string $value Valor a verificar
     * @return bool
     */
    public static function is_encrypted($value) {
        if (empty($value)) {
            return false;
        }
        
        // Verificar si parece ser base64 válido con longitud apropiada
        if (!preg_match('/^[A-Za-z0-9+\/]+=*$/', $value)) {
            return false;
        }
        
        $decoded = base64_decode($value, true);
        if ($decoded === false) {
            return false;
        }
        
        // Verificar longitud mínima (IV + datos mínimos)
        $iv_length = openssl_cipher_iv_length(self::$cipher);
        return strlen($decoded) > $iv_length;
    }
    
    /**
     * Migra opciones existentes a formato encriptado
     * 
     * @param array $option_names Lista de nombres de opciones a encriptar
     * @return array Resultado de la migración
     */
    public static function migrate_to_encrypted($option_names) {
        $results = array();
        
        foreach ($option_names as $option_name) {
            $current_value = get_option($option_name, '');
            
            if (empty($current_value)) {
                $results[$option_name] = 'empty';
                continue;
            }
            
            // Si ya está encriptado, no hacer nada
            if (self::is_encrypted($current_value)) {
                $results[$option_name] = 'already_encrypted';
                continue;
            }
            
            // Encriptar y guardar
            if (self::set_encrypted_option($option_name, $current_value)) {
                $results[$option_name] = 'migrated';
                
                // Registrar evento de seguridad
                if (class_exists('WP_ALP_Security_Enhanced')) {
                    WP_ALP_Security_Enhanced::log_security_event(
                        'option_encrypted',
                        array('option_name' => $option_name),
                        'info'
                    );
                }
            } else {
                $results[$option_name] = 'failed';
                
                // Registrar error
                if (class_exists('WP_ALP_Security_Enhanced')) {
                    WP_ALP_Security_Enhanced::log_security_event(
                        'option_encryption_failed',
                        array('option_name' => $option_name),
                        'error'
                    );
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Rota la clave de encriptación (para seguridad avanzada)
     * 
     * @return bool
     */
    public static function rotate_encryption_key() {
        // Obtener todas las opciones encriptadas
        $encrypted_options = array(
            'wp_alp_google_client_secret',
            'wp_alp_facebook_app_secret',
            'wp_alp_apple_private_key',
            'wp_alp_recaptcha_secret_key'
        );
        
        // Desencriptar todos los valores con la clave actual
        $values = array();
        foreach ($encrypted_options as $option) {
            $values[$option] = self::get_encrypted_option($option);
        }
        
        // Generar nueva clave
        $new_key = self::generate_key();
        
        // Respaldar clave anterior
        update_option('wp_alp_encryption_key_backup', self::get_encryption_key());
        
        // Establecer nueva clave
        update_option('wp_alp_encryption_key', $new_key);
        
        // Re-encriptar todos los valores
        $success = true;
        foreach ($values as $option => $value) {
            if (!empty($value)) {
                if (!self::set_encrypted_option($option, $value)) {
                    $success = false;
                }
            }
        }
        
        if ($success) {
            // Eliminar respaldo si todo salió bien
            delete_option('wp_alp_encryption_key_backup');
            
            // Registrar evento
            if (class_exists('WP_ALP_Security_Enhanced')) {
                WP_ALP_Security_Enhanced::log_security_event(
                    'encryption_key_rotated',
                    array(),
                    'info'
                );
            }
        } else {
            // Restaurar clave anterior si algo falló
            $backup_key = get_option('wp_alp_encryption_key_backup');
            if ($backup_key) {
                update_option('wp_alp_encryption_key', $backup_key);
                delete_option('wp_alp_encryption_key_backup');
            }
            
            error_log('WP_ALP Encryption: Key rotation failed, restored previous key');
        }
        
        return $success;
    }
}