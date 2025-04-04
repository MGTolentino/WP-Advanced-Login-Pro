<?php
/**
 * Handles security features for the plugin.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/includes
 */

class WP_ALP_Security {

    /**
     * Max login attempts before temporary lockout.
     *
     * @since    1.0.0
     * @access   private
     * @var      int     $max_login_attempts    Maximum number of login attempts allowed.
     */
    private $max_login_attempts = 5;

    /**
     * Lockout duration in seconds.
     *
     * @since    1.0.0
     * @access   private
     * @var      int     $lockout_duration    Duration of lockout in seconds.
     */
    private $lockout_duration = 1800; // 30 minutes

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
        // Get security settings from options
        $options = get_option('wp_alp_security_options');
        if ($options) {
            if (isset($options['max_login_attempts'])) {
                $this->max_login_attempts = intval($options['max_login_attempts']);
            }
            if (isset($options['lockout_duration'])) {
                $this->lockout_duration = intval($options['lockout_duration']);
            }
        }
    }

    /**
     * Generate a secure nonce for forms.
     *
     * @since    1.0.0
     * @return   string    The generated nonce.
     */
    public function generate_nonce() {
        return wp_create_nonce('wp_alp_security_nonce');
    }

    /**
     * Verify a nonce from submitted form.
     *
     * @since    1.0.0
     * @param    string    $nonce    The nonce to verify.
     * @return   bool                Whether the nonce is valid.
     */
    public function verify_nonce($nonce) {
        return wp_verify_nonce($nonce, 'wp_alp_security_nonce');
    }

   /**
 * Generate CSRF token for forms.
 *
 * @since    1.0.0
 * @return   string    The generated CSRF token.
 */
public function generate_csrf_token() {
    // En lugar de usar sesiones, generamos un token basado en el nonce de WordPress
    return wp_create_nonce('wp_alp_csrf_token');
}

/**
 * Verify CSRF token from submitted form.
 *
 * @since    1.0.0
 * @param    string    $token    The token to verify.
 * @return   bool                Whether the token is valid.
 */
public function verify_csrf_token($token) {
    // Verificamos el token usando el sistema de nonces de WordPress
    return wp_verify_nonce($token, 'wp_alp_csrf_token');
}

    /**
     * Validate recaptcha response.
     *
     * @since    1.0.0
     * @param    string    $recaptcha_response    The recaptcha response from the form.
     * @return   bool                             Whether the recaptcha is valid.
     */
    public function verify_recaptcha($recaptcha_response) {
        $options = get_option('wp_alp_security_options');
        
        if (!isset($options['recaptcha_secret_key']) || empty($options['recaptcha_secret_key'])) {
            // If no recaptcha configured, skip this check
            return true;
        }

        $secret_key = $options['recaptcha_secret_key'];
        
        // Make a POST request to the Google reCAPTCHA Server
        $response = wp_remote_post(
            'https://www.google.com/recaptcha/api/siteverify',
            array(
                'body' => array(
                    'secret' => $secret_key,
                    'response' => $recaptcha_response,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                )
            )
        );
        
        // Parse the response
        $response_body = wp_remote_retrieve_body($response);
        $result = json_decode($response_body, true);
        
        // Return true if successful
        return (isset($result['success']) && $result['success'] === true);
    }

    /**
     * Generate a strong random password.
     *
     * @since    1.0.0
     * @param    int       $length    The length of the password. Default is 12.
     * @return   string               The generated password.
     */
    public function generate_strong_password($length = 12) {
        return wp_generate_password($length, true, true);
    }

    /**
     * Check if an email is valid and not from a disposable email provider.
     *
     * @since    1.0.0
     * @param    string    $email    The email to validate.
     * @return   bool                Whether the email is valid.
     */
    public function validate_email($email) {
        // Basic email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Optional: Check against disposable email providers
        $domain = explode('@', $email)[1];
        $disposable_domains = $this->get_disposable_email_domains();
        
        if (in_array($domain, $disposable_domains)) {
            return false;
        }

        return true;
    }

    /**
     * Get a list of common disposable email domains.
     *
     * @since    1.0.0
     * @return   array    List of disposable email domains.
     */
    private function get_disposable_email_domains() {
        // This is a small sample. In a real plugin, this would be a much larger list
        // or even use an API to check against disposable email providers
        return array(
            'mailinator.com',
            'guerrillamail.com',
            'tempmail.com',
            'throwawaymail.com',
            'yopmail.com',
            '10minutemail.com',
            'trashmail.com',
            'sharklasers.com'
        );
    }

    /**
     * Handle failed login attempts.
     *
     * @since    1.0.0
     * @param    string    $username    The username that was used in the failed login attempt.
     */
    public function handle_failed_login($username) {
        $ip = $this->get_client_ip();
        $failed_attempts = get_transient('wp_alp_failed_login_' . $ip);
        
        if ($failed_attempts === false) {
            // First failed attempt
            set_transient('wp_alp_failed_login_' . $ip, 1, $this->lockout_duration);
        } else {
            // Increment failed attempts
            set_transient('wp_alp_failed_login_' . $ip, $failed_attempts + 1, $this->lockout_duration);
        }
    }

    /**
     * Check login limiter before authentication.
     *
     * @since    1.0.0
     * @param    WP_User|WP_Error    $user        WP_User or WP_Error object from previous hook.
     * @param    string              $username    Username.
     * @param    string              $password    Password.
     * @return   WP_User|WP_Error                 WP_User or WP_Error object.
     */
    public function check_login_limiter($user, $username, $password) {
        $ip = $this->get_client_ip();
        $failed_attempts = get_transient('wp_alp_failed_login_' . $ip);
        
        if ($failed_attempts !== false && $failed_attempts >= $this->max_login_attempts) {
            return new WP_Error(
                'too_many_attempts',
                sprintf(
                    __('Too many failed login attempts. Please try again after %d minutes.', 'wp-alp'),
                    ceil($this->lockout_duration / 60)
                )
            );
        }
        
        return $user;
    }

    /**
     * Get client IP address.
     *
     * @since    1.0.0
     * @return   string    The client IP address.
     */
    public function get_client_ip() {
        $ip_keys = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );
        
        foreach ($ip_keys as $key) {
            if (isset($_SERVER[$key]) && filter_var($_SERVER[$key], FILTER_VALIDATE_IP)) {
                return $_SERVER[$key];
            }
        }
        
        // Fallback to server IP
        return $_SERVER['REMOTE_ADDR'];
    }
    
    /**
     * Sanitize and validate user input.
     *
     * @since    1.0.0
     * @param    string    $input       The input to sanitize.
     * @param    string    $type        The type of input (text, email, username, etc).
     * @return   string|bool            Sanitized input or false if invalid.
     */
    public function sanitize_input($input, $type = 'text') {
        switch ($type) {
            case 'email':
                $sanitized = sanitize_email($input);
                return $this->validate_email($sanitized) ? $sanitized : false;
                
            case 'username':
                $sanitized = sanitize_user($input);
                return ($sanitized === $input) ? $sanitized : false;
                
            case 'text':
                return sanitize_text_field($input);
                
            case 'textarea':
                return sanitize_textarea_field($input);
                
            case 'url':
                return esc_url_raw($input);
                
            case 'int':
                return intval($input);
                
            case 'float':
                return floatval($input);
                
            case 'bool':
                return (bool) $input;
                
            default:
                return sanitize_text_field($input);
        }
    }
    
    /**
     * Check for common bot patterns in user input.
     *
     * @since    1.0.0
     * @param    array    $form_data    The form data to check.
     * @return   bool                   True if bot is detected, false otherwise.
     */
    public function detect_bot($form_data) {
        // Check for honeypot fields (hidden fields that should be empty)
        if (isset($form_data['wp_alp_honeypot']) && !empty($form_data['wp_alp_honeypot'])) {
            return true;
        }
        
        // Check for unusually fast form submission (bots typically submit forms faster than humans)
        if (isset($form_data['wp_alp_time_check'])) {
            $time_check = intval($form_data['wp_alp_time_check']);
            $current_time = time();
            
            // If form was submitted in less than 3 seconds, it's likely a bot
            if (($current_time - $time_check) < 3) {
                return true;
            }
        }
        
        // Check for common bot user agents
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $bot_agents = array('bot', 'spider', 'crawl', 'slurp', 'mediapartners');
        
        foreach ($bot_agents as $bot_agent) {
            if (stripos($user_agent, $bot_agent) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Add hidden anti-bot fields to a form.
     *
     * @since    1.0.0
     * @return   string    HTML for anti-bot fields.
     */
    public function add_anti_bot_fields() {
        $time_check = time();
        $output = '<input type="hidden" name="wp_alp_time_check" value="' . esc_attr($time_check) . '">';
        $output .= '<div style="display: none;"><input type="text" name="wp_alp_honeypot" value=""></div>';
        
        return $output;
    }
    
    /**
     * Encrypt sensitive data.
     *
     * @since    1.0.0
     * @param    string    $data    The data to encrypt.
     * @return   string             The encrypted data.
     */
    public function encrypt_data($data) {
        // This is a simplified example. In a production environment, 
        // you would use a more robust encryption method.
        $encryption_key = get_option('wp_alp_encryption_key');
        
        if (!$encryption_key) {
            $encryption_key = bin2hex(random_bytes(32));
            update_option('wp_alp_encryption_key', $encryption_key);
        }
        
        $ivlen = openssl_cipher_iv_length($cipher = 'AES-256-CBC');
        $iv = openssl_random_pseudo_bytes($ivlen);
        $encrypted = openssl_encrypt($data, $cipher, $encryption_key, 0, $iv);
        
        // Return the IV and encrypted data together
        return base64_encode($iv . $encrypted);
    }
    
    /**
     * Decrypt sensitive data.
     *
     * @since    1.0.0
     * @param    string    $data    The encrypted data to decrypt.
     * @return   string             The decrypted data or empty string on failure.
     */
    public function decrypt_data($data) {
        $encryption_key = get_option('wp_alp_encryption_key');
        
        if (!$encryption_key) {
            return '';
        }
        
        $data = base64_decode($data);
        $ivlen = openssl_cipher_iv_length($cipher = 'AES-256-CBC');
        $iv = substr($data, 0, $ivlen);
        $encrypted = substr($data, $ivlen);
        
        return openssl_decrypt($encrypted, $cipher, $encryption_key, 0, $iv);
    }
}