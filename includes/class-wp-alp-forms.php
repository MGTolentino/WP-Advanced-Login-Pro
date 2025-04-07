<?php
/**
 * Handles form rendering and processing.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/includes
 */

class WP_ALP_Forms {
    
    /**
     * Security manager instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      WP_ALP_Security    $security    The security manager instance.
     */
    private $security;
    
    /**
     * Social login manager instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      WP_ALP_Social    $social    The social login manager instance.
     */
    private $social;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    WP_ALP_Security    $security    The security manager instance.
     * @param    WP_ALP_Social      $social      The social login manager instance.
     */
    public function __construct($security, $social) {
        $this->security = $security;
        $this->social = $social;
    }
    
    /**
     * Render login form.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            The rendered form.
     */
    public function render_login_form($atts = array()) {
        if (is_user_logged_in()) {
            return $this->render_logout_form();
        }
        
        // Get options
        $options = get_option('wp_alp_general_options');
        
        // Process attributes
        $atts = shortcode_atts(array(
            'redirect' => '',
            'show_title' => true,
            'show_social' => true,
        ), $atts, 'wp_alp_login');
        
        // Generate nonce and CSRF token
        $nonce = $this->security->generate_nonce();
        $csrf_token = $this->security->generate_csrf_token();
        
        // Start output buffering
        ob_start();
        
        // Get template
        $template = WP_ALP_PLUGIN_DIR . 'public/templates/login.php';
        
        // Check if template exists in theme
        $theme_template = get_stylesheet_directory() . '/wp-alp/login.php';
        if (file_exists($theme_template)) {
            $template = $theme_template;
        }
        
        // Include template with variables
        include $template;
        
        // Return the buffered output
        return ob_get_clean();
    }
    
    /**
     * Render registration form for normal users.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            The rendered form.
     */
    public function render_register_user_form($atts = array()) {
        if (is_user_logged_in()) {
            return '<p>' . __('You are already registered and logged in.', 'wp-alp') . '</p>';
        }
        
        // Get options
        $options = get_option('wp_alp_general_options');
        
        // Process attributes
        $atts = shortcode_atts(array(
            'redirect' => '',
            'show_title' => true,
            'show_social' => true,
        ), $atts, 'wp_alp_register_user');
        
        // Generate nonce and CSRF token
        $nonce = $this->security->generate_nonce();
        $csrf_token = $this->security->generate_csrf_token();
        
        // Get recaptcha site key
        $security_options = get_option('wp_alp_security_options');
        $recaptcha_site_key = isset($security_options['recaptcha_site_key']) ? $security_options['recaptcha_site_key'] : '';
        
        // Start output buffering
        ob_start();
        
        // Get template
        $template = WP_ALP_PLUGIN_DIR . 'public/templates/register-user.php';
        
        // Check if template exists in theme
        $theme_template = get_stylesheet_directory() . '/wp-alp/register-user.php';
        if (file_exists($theme_template)) {
            $template = $theme_template;
        }
        
        // Include template with variables
        include $template;
        
        // Return the buffered output
        return ob_get_clean();
    }
    
    /**
     * Render registration form for vendors.
     *
     * @since    1.0.0
     * @param    array    $atts    Shortcode attributes.
     * @return   string            The rendered form.
     */
    public function render_register_vendor_form($atts = array()) {
        if (is_user_logged_in()) {
            return '<p>' . __('You are already registered and logged in.', 'wp-alp') . '</p>';
        }
        
        // Get options
        $options = get_option('wp_alp_general_options');
        
        // Process attributes
        $atts = shortcode_atts(array(
            'redirect' => '',
            'show_title' => true,
            'show_social' => true,
        ), $atts, 'wp_alp_register_vendor');
        
        // Generate nonce and CSRF token
        $nonce = $this->security->generate_nonce();
        $csrf_token = $this->security->generate_csrf_token();
        
        // Get recaptcha site key
        $security_options = get_option('wp_alp_security_options');
        $recaptcha_site_key = isset($security_options['recaptcha_site_key']) ? $security_options['recaptcha_site_key'] : '';
        
        // Start output buffering
        ob_start();
        
        // Get template
        $template = WP_ALP_PLUGIN_DIR . 'public/templates/register-vendor.php';
        
        // Check if template exists in theme
        $theme_template = get_stylesheet_directory() . '/wp-alp/register-vendor.php';
        if (file_exists($theme_template)) {
            $template = $theme_template;
        }
        
        // Include template with variables
        include $template;
        
        // Return the buffered output
        return ob_get_clean();
    }
    
   /**
 * Render profile completion form.
 *
 * @since    1.0.0
 * @param    array    $atts    Shortcode attributes.
 * @return   string            The rendered form.
 */
public function render_profile_completion_form($atts = array()) {
    if (!is_user_logged_in()) {
        return '<p>' . __('You must be logged in to complete your profile.', 'wp-alp') . '</p>';
    }
    
    $user = wp_get_current_user();
    $user_id = $user->ID;
    
    // Check if profile is already complete
    $profile_status = get_user_meta($user_id, 'wp_alp_profile_status', true);
    if ($profile_status === 'complete') {
        return '<p>' . __('Your profile is already complete.', 'wp-alp') . '</p>';
    }
    
    // Process attributes
    $atts = shortcode_atts(array(
        'redirect' => '',
        'show_title' => true,
    ), $atts, 'wp_alp_profile_completion');
    
    // Convert string "false" to boolean false
    if (is_string($atts['show_title']) && strtolower($atts['show_title']) === 'false') {
        $atts['show_title'] = false;
    }
    
    // Generate nonce and CSRF token
    $nonce = $this->security->generate_nonce();
    $csrf_token = $this->security->generate_csrf_token();
    
    // Start output buffering
    ob_start();
    
    // Get template
    $template = WP_ALP_PLUGIN_DIR . 'public/templates/profile-completion.php';
    
    // Check if template exists in theme
    $theme_template = get_stylesheet_directory() . '/wp-alp/profile-completion.php';
    if (file_exists($theme_template)) {
        $template = $theme_template;
    }
    
    // Include template with variables
    include $template;
    
    // Return the buffered output
    return ob_get_clean();
}
    
    /**
     * Render logout form/button.
     *
     * @since    1.0.0
     * @return   string    The rendered form.
     */
    private function render_logout_form() {
        $user = wp_get_current_user();
        
        ob_start();
        ?>
        <div class="wp-alp-logged-in">
            <p><?php printf(__('Logged in as %s', 'wp-alp'), '<strong>' . esc_html($user->display_name) . '</strong>'); ?></p>
            <a href="<?php echo wp_logout_url(home_url()); ?>" class="wp-alp-button wp-alp-logout-button">
                <?php _e('Log Out', 'wp-alp'); ?>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render social login buttons.
     *
     * @since    1.0.0
     * @param    string    $context    The context (login, register_user, register_vendor).
     * @return   string                The rendered buttons.
     */
    public function render_social_buttons($context = 'login') {
        return $this->social->render_social_buttons($context);
    }
    
   /**
     * Process login form submission.
     *
     * @since    1.0.0
     * @param    array    $form_data    The submitted form data.
     * @return   array|WP_Error         Response data on success, WP_Error on failure.
     */
    public function process_login($form_data) {
        // Verify security tokens - Accept both _wpnonce and security
        $nonce = isset($form_data['_wpnonce']) ? $form_data['_wpnonce'] : (isset($form_data['security']) ? $form_data['security'] : '');
        if (!$this->security->verify_nonce($nonce)) {
            return new WP_Error('invalid_nonce', __('Security token expired. Please refresh the page and try again.', 'wp-alp'));
        }
        
        // Verify csrf token if provided
        if (isset($form_data['_csrf_token']) && !$this->security->verify_csrf_token($form_data['_csrf_token'])) {
            return new WP_Error('invalid_csrf', __('Security check failed. Please refresh the page and try again.', 'wp-alp'));
        }
        
        // Check for bot
        if ($this->security->detect_bot($form_data)) {
            return new WP_Error('bot_detected', __('Bot activity detected.', 'wp-alp'));
        }
        
        // Sanitize input
        $username_email = $this->security->sanitize_input($form_data['username_email']);
        $password = $form_data['password']; // Don't sanitize password
        $remember = isset($form_data['remember']) ? (bool) $form_data['remember'] : false;
        
        // Determine if username or email was provided
        $user_data = array();
        if (is_email($username_email)) {
            $user = get_user_by('email', $username_email);
            if ($user) {
                $user_data['user_login'] = $user->user_login;
            }
        } else {
            $user_data['user_login'] = $username_email;
        }
        
        if (empty($user_data['user_login'])) {
            return new WP_Error('invalid_username', __('Unknown username or email address. Please check again or try with your email address.', 'wp-alp'));
        }
        
        $user_data['user_password'] = $password;
        $user_data['remember'] = $remember;
        
        // Attempt to log in the user
        $user = wp_signon($user_data, is_ssl());
        
        if (is_wp_error($user)) {
            return $user;
        }
        
        // Determine redirect URL
        $redirect_url = home_url();
        if (!empty($form_data['redirect_to'])) {
            $redirect_url = esc_url_raw($form_data['redirect_to']);
        } else {
            // Get default redirect based on user type
            $user_type = get_user_meta($user->ID, 'wp_alp_user_type', true);
            $options = get_option('wp_alp_general_options');
            
            switch ($user_type) {
                case 'lead':
                    if (!empty($options['lead_redirect'])) {
                        $redirect_url = $options['lead_redirect'];
                    }
                    break;
                case 'vendor':
                    if (!empty($options['vendor_redirect'])) {
                        $redirect_url = $options['vendor_redirect'];
                    }
                    break;
                default:
                    if (!empty($options['user_redirect'])) {
                        $redirect_url = $options['user_redirect'];
                    }
            }
        }
        
        // Check if profile completion is needed
        $profile_status = get_user_meta($user->ID, 'wp_alp_profile_status', true);
        if ($profile_status === 'incomplete') {
            $options = get_option('wp_alp_general_options');
            if (!empty($options['profile_completion_page'])) {
                $redirect_url = get_permalink($options['profile_completion_page']);
            }
        }
        
        return array(
            'success' => true,
            'redirect' => $redirect_url,
        );
    }
    
    /**
     * Process user registration form submission.
     *
     * @since    1.0.0
     * @param    array                $form_data    The submitted form data.
     * @param    WP_ALP_User_Manager  $user_manager The user manager instance.
     * @return   array|WP_Error                     Response data on success, WP_Error on failure.
     */
    public function process_user_registration($form_data, $user_manager) {
        // Verify security tokens
        if (!$this->security->verify_nonce($form_data['_wpnonce'])) {
            return new WP_Error('invalid_nonce', __('Security token expired. Please refresh the page and try again.', 'wp-alp'));
        }
        
        if (!$this->security->verify_csrf_token($form_data['_csrf_token'])) {
            return new WP_Error('invalid_csrf', __('Security check failed. Please refresh the page and try again.', 'wp-alp'));
        }
        
        // Check for bot
        if ($this->security->detect_bot($form_data)) {
            return new WP_Error('bot_detected', __('Bot activity detected.', 'wp-alp'));
        }
        
        // Verify recaptcha if enabled
        $security_options = get_option('wp_alp_security_options');
        if (!empty($security_options['recaptcha_site_key']) && !empty($security_options['recaptcha_secret_key'])) {
            if (empty($form_data['g-recaptcha-response'])) {
                return new WP_Error('recaptcha_required', __('Please complete the reCAPTCHA verification.', 'wp-alp'));
            }
            
            $recaptcha_valid = $this->security->verify_recaptcha($form_data['g-recaptcha-response']);
            if (!$recaptcha_valid) {
                return new WP_Error('recaptcha_failed', __('reCAPTCHA verification failed. Please try again.', 'wp-alp'));
            }
        }
        
        // Prepare user data
        $user_data = array(
            'email' => $form_data['email'],
            'password' => $form_data['password'],
            'password_confirm' => $form_data['password_confirm'],
            'first_name' => isset($form_data['first_name']) ? $form_data['first_name'] : '',
            'last_name' => isset($form_data['last_name']) ? $form_data['last_name'] : '',
        );
        
        // Additional validation
        if ($user_data['password'] !== $user_data['password_confirm']) {
            return new WP_Error('password_mismatch', __('Passwords do not match.', 'wp-alp'));
        }
        
        // Register user
        $user_id = $user_manager->register_user($user_data, 'normal');
        
        if (is_wp_error($user_id)) {
            return $user_id;
        }
        
        // Set profile status as incomplete to trigger profile completion form
        update_user_meta($user_id, 'wp_alp_profile_status', 'incomplete');
        
        // Check if auto-login is enabled
        $options = get_option('wp_alp_general_options');
        $auto_login = isset($options['auto_login']) ? (bool) $options['auto_login'] : true;
        
        if ($auto_login) {
            // Log the user in
            wp_set_auth_cookie($user_id, false);
            $user = get_user_by('ID', $user_id);
            do_action('wp_login', $user->user_login, $user);
        }
        
        // Determine redirect URL
        $redirect_url = home_url();
        if (!empty($form_data['redirect_to'])) {
            $redirect_url = esc_url_raw($form_data['redirect_to']);
        } else {
            // Show profile completion form if available
            if (!empty($options['profile_completion_page'])) {
                $redirect_url = get_permalink($options['profile_completion_page']);
            } elseif (!empty($options['user_redirect'])) {
                $redirect_url = $options['user_redirect'];
            }
        }
        
        return array(
            'success' => true,
            'redirect' => $redirect_url,
            'user_id' => $user_id,
        );
    }
    
    /**
     * Process vendor registration form submission.
     *
     * @since    1.0.0
     * @param    array                $form_data    The submitted form data.
     * @param    WP_ALP_User_Manager  $user_manager The user manager instance.
     * @return   array|WP_Error                     Response data on success, WP_Error on failure.
     */
    public function process_vendor_registration($form_data, $user_manager) {
        // Verify security tokens
        if (!$this->security->verify_nonce($form_data['_wpnonce'])) {
            return new WP_Error('invalid_nonce', __('Security token expired. Please refresh the page and try again.', 'wp-alp'));
        }
        
        if (!$this->security->verify_csrf_token($form_data['_csrf_token'])) {
            return new WP_Error('invalid_csrf', __('Security check failed. Please refresh the page and try again.', 'wp-alp'));
        }
        
        // Check for bot
        if ($this->security->detect_bot($form_data)) {
            return new WP_Error('bot_detected', __('Bot activity detected.', 'wp-alp'));
        }
        
        // Verify recaptcha if enabled
        $security_options = get_option('wp_alp_security_options');
        if (!empty($security_options['recaptcha_site_key']) && !empty($security_options['recaptcha_secret_key'])) {
            if (empty($form_data['g-recaptcha-response'])) {
                return new WP_Error('recaptcha_required', __('Please complete the reCAPTCHA verification.', 'wp-alp'));
            }
            
            $recaptcha_valid = $this->security->verify_recaptcha($form_data['g-recaptcha-response']);
            if (!$recaptcha_valid) {
                return new WP_Error('recaptcha_failed', __('reCAPTCHA verification failed. Please try again.', 'wp-alp'));
            }
        }
        
        // Prepare user data
        $user_data = array(
            'email' => $form_data['email'],
            'password' => $form_data['password'],
            'password_confirm' => $form_data['password_confirm'],
            'first_name' => $form_data['first_name'],
            'last_name' => $form_data['last_name'],
            'phone' => $form_data['phone'],
            'company' => isset($form_data['company']) ? $form_data['company'] : '',
            'address' => isset($form_data['address']) ? $form_data['address'] : '',
        );
        
        // Additional validation
        if ($user_data['password'] !== $user_data['password_confirm']) {
            return new WP_Error('password_mismatch', __('Passwords do not match.', 'wp-alp'));
        }
        
        // Register user as vendor
        $user_id = $user_manager->register_user($user_data, 'vendor');
        
        if (is_wp_error($user_id)) {
            return $user_id;
        }
        
        // Create vendor profile
        $vendor_data = array(
            'company' => $user_data['company'],
            'address' => $user_data['address'],
            'phone' => $user_data['phone'],
        );
        
        $vendor_id = $user_manager->create_vendor($user_id, $vendor_data);
        
        if (is_wp_error($vendor_id)) {
            // If there's an error creating the vendor, log it but don't prevent user registration
            error_log('Error creating vendor: ' . $vendor_id->get_error_message());
        }
        
        // Check if auto-login is enabled
        $options = get_option('wp_alp_general_options');
        $auto_login = isset($options['auto_login']) ? (bool) $options['auto_login'] : true;
        
        if ($auto_login) {
            // Log the user in
            wp_set_auth_cookie($user_id, false);
            $user = get_user_by('ID', $user_id);
            do_action('wp_login', $user->user_login, $user);
        }
        
        // Determine redirect URL
        $redirect_url = home_url();
        if (!empty($form_data['redirect_to'])) {
            $redirect_url = esc_url_raw($form_data['redirect_to']);
        } else if (!empty($options['vendor_redirect'])) {
            $redirect_url = $options['vendor_redirect'];
        }
        
        return array(
            'success' => true,
            'redirect' => $redirect_url,
            'user_id' => $user_id,
            'vendor_id' => $vendor_id,
        );
    }
    
    /**
     * Process profile completion form submission.
     *
     * @since    1.0.0
     * @param    array                $form_data     The submitted form data.
     * @param    WP_ALP_User_Manager  $user_manager  The user manager instance.
     * @param    WP_ALP_JetEngine     $jetengine     The JetEngine manager instance.
     * @return   array|WP_Error                      Response data on success, WP_Error on failure.
     */
    public function process_profile_completion($form_data, $user_manager, $jetengine) {
        // Verify security tokens
        if (!$this->security->verify_nonce($form_data['_wpnonce'])) {
            return new WP_Error('invalid_nonce', __('Security token expired. Please refresh the page and try again.', 'wp-alp'));
        }
        
        if (!$this->security->verify_csrf_token($form_data['_csrf_token'])) {
            return new WP_Error('invalid_csrf', __('Security check failed. Please refresh the page and try again.', 'wp-alp'));
        }
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            return new WP_Error('not_logged_in', __('You must be logged in to complete your profile.', 'wp-alp'));
        }
        
        $user_id = get_current_user_id();
        
        // Prepare lead data from form submission
        $lead_data = array(
            'phone' => isset($form_data['phone']) ? $form_data['phone'] : '',
            'event_type' => isset($form_data['event_type']) ? $form_data['event_type'] : '',
            'event_date' => isset($form_data['event_date']) ? $form_data['event_date'] : '',
            'event_address' => isset($form_data['event_address']) ? $form_data['event_address'] : '',
            'event_attendees' => isset($form_data['event_attendees']) ? $form_data['event_attendees'] : '',
            'event_details' => isset($form_data['event_details']) ? $form_data['event_details'] : '',
            'event_category' => isset($form_data['event_category']) ? $form_data['event_category'] : '',
            'service_interest' => isset($form_data['service_interest']) ? $form_data['service_interest'] : '',
        );
        
        // Create lead
        $lead_id = $user_manager->create_lead($user_id, $lead_data);
        
        if (is_wp_error($lead_id)) {
            return $lead_id;
        }
        
        // Mark profile as complete
        update_user_meta($user_id, 'wp_alp_profile_status', 'complete');
        
        // Determine redirect URL
        $redirect_url = home_url();
        if (!empty($form_data['redirect_to'])) {
            $redirect_url = esc_url_raw($form_data['redirect_to']);
        } else {
            // Get default redirect based on user type
            $options = get_option('wp_alp_general_options');
            
            if (!empty($options['lead_redirect'])) {
                $redirect_url = $options['lead_redirect'];
            } elseif (!empty($options['user_redirect'])) {
                $redirect_url = $options['user_redirect'];
            }
        }
        
        return array(
            'success' => true,
            'redirect' => $redirect_url,
            'lead_id' => $lead_id,
        );
    }
    
    /**
     * Process social login.
     *
     * @since    1.0.0
     * @param    string               $provider      The social provider.
     * @param    string               $code          The authorization code.
     * @param    string               $state         The state parameter.
     * @param    WP_ALP_Social        $social        The social login manager instance.
     * @param    WP_ALP_User_Manager  $user_manager  The user manager instance.
     * @return   array|WP_Error                      Response data on success, WP_Error on failure.
     */
    public function process_social_login($provider, $code, $state, $social, $user_manager) {
        // Authenticate with social provider
        $social_data = $social->authenticate($provider, $code, $state);
        
        if (is_wp_error($social_data)) {
            return $social_data;
        }
        
        // Check if the user exists by email
        $user = get_user_by('email', $social_data['email']);
        
        if ($user) {
            // User exists, log them in
            wp_set_auth_cookie($user->ID, true);
            do_action('wp_login', $user->user_login, $user);
        } else {
            // User doesn't exist, register them
            $user_id = $user_manager->register_social_user($social_data);
            
            if (is_wp_error($user_id)) {
                return $user_id;
            }
            
            // Log the user in
            wp_set_auth_cookie($user_id, true);
            $user = get_user_by('ID', $user_id);
            do_action('wp_login', $user->user_login, $user);
        }
        
        // Determine redirect URL
        $options = get_option('wp_alp_general_options');
        $redirect_url = home_url();
        
        // Check if profile completion is needed for new users
        $user_id = $user->ID;
        $profile_status = get_user_meta($user_id, 'wp_alp_profile_status', true);
        
        if ($profile_status === 'incomplete') {
            if (!empty($options['profile_completion_page'])) {
                $redirect_url = get_permalink($options['profile_completion_page']);
            }
        } else {
            // Get default redirect based on user type
            $user_type = get_user_meta($user_id, 'wp_alp_user_type', true);
            
            switch ($user_type) {
                case 'lead':
                    if (!empty($options['lead_redirect'])) {
                        $redirect_url = $options['lead_redirect'];
                    }
                    break;
                case 'vendor':
                    if (!empty($options['vendor_redirect'])) {
                        $redirect_url = $options['vendor_redirect'];
                    }
                    break;
                default:
                    if (!empty($options['user_redirect'])) {
                        $redirect_url = $options['user_redirect'];
                    }
            }
        }
        
        return array(
            'success' => true,
            'redirect' => $redirect_url,
            'user_id' => $user->ID,
        );
    }
}