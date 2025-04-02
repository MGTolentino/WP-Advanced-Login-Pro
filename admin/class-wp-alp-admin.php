<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/admin
 */

class WP_ALP_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name       The name of this plugin.
     * @param    string    $version           The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/wp-alp-admin.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/wp-alp-admin.js',
            array('jquery'),
            $this->version,
            false
        );

        wp_localize_script(
            $this->plugin_name,
            'wp_alp_admin',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wp_alp_admin_nonce'),
                'copied_text' => __('Copied!', 'wp-alp'),
            )
        );
    }

    /**
     * Add settings page to the admin menu.
     *
     * @since    1.0.0
     */
    public function add_settings_page() {
        add_menu_page(
            __('Advanced Login Pro', 'wp-alp'),
            __('Advanced Login', 'wp-alp'),
            'manage_options',
            'wp-advanced-login-pro',
            array($this, 'display_settings_page'),
            'dashicons-lock',
            81
        );

        // Add submenus
        add_submenu_page(
            'wp-advanced-login-pro',
            __('Settings', 'wp-alp'),
            __('Settings', 'wp-alp'),
            'manage_options',
            'wp-advanced-login-pro',
            array($this, 'display_settings_page')
        );

        add_submenu_page(
            'wp-advanced-login-pro',
            __('Security', 'wp-alp'),
            __('Security', 'wp-alp'),
            'manage_options',
            'wp-advanced-login-pro-security',
            array($this, 'display_security_page')
        );

        add_submenu_page(
            'wp-advanced-login-pro',
            __('Social Login', 'wp-alp'),
            __('Social Login', 'wp-alp'),
            'manage_options',
            'wp-advanced-login-pro-social',
            array($this, 'display_social_page')
        );

        add_submenu_page(
            'wp-advanced-login-pro',
            __('Users & Leads', 'wp-alp'),
            __('Users & Leads', 'wp-alp'),
            'manage_options',
            'wp-advanced-login-pro-users',
            array($this, 'display_users_page')
        );
    }

    /**
     * Register plugin settings.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        // General settings
        register_setting(
            'wp_alp_general_settings',
            'wp_alp_general_options',
            array($this, 'sanitize_general_options')
        );

        add_settings_section(
            'wp_alp_general_section',
            __('General Settings', 'wp-alp'),
            array($this, 'render_general_section'),
            'wp_alp_general_settings'
        );

        // Pages settings
        add_settings_field(
            'login_page',
            __('Login Page', 'wp-alp'),
            array($this, 'render_page_select'),
            'wp_alp_general_settings',
            'wp_alp_general_section',
            array('field' => 'login_page')
        );

        add_settings_field(
            'register_user_page',
            __('Register User Page', 'wp-alp'),
            array($this, 'render_page_select'),
            'wp_alp_general_settings',
            'wp_alp_general_section',
            array('field' => 'register_user_page')
        );

        add_settings_field(
            'register_vendor_page',
            __('Register Vendor Page', 'wp-alp'),
            array($this, 'render_page_select'),
            'wp_alp_general_settings',
            'wp_alp_general_section',
            array('field' => 'register_vendor_page')
        );

        add_settings_field(
            'profile_completion_page',
            __('Profile Completion Page', 'wp-alp'),
            array($this, 'render_page_select'),
            'wp_alp_general_settings',
            'wp_alp_general_section',
            array('field' => 'profile_completion_page')
        );

        // Redirect settings
        add_settings_field(
            'user_redirect',
            __('User Redirect URL', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_general_settings',
            'wp_alp_general_section',
            array('field' => 'user_redirect', 'placeholder' => home_url())
        );

        add_settings_field(
            'lead_redirect',
            __('Lead Redirect URL', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_general_settings',
            'wp_alp_general_section',
            array('field' => 'lead_redirect', 'placeholder' => home_url())
        );

        add_settings_field(
            'vendor_redirect',
            __('Vendor Redirect URL', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_general_settings',
            'wp_alp_general_section',
            array('field' => 'vendor_redirect', 'placeholder' => home_url())
        );

        // General options
        add_settings_field(
            'auto_login',
            __('Auto Login After Registration', 'wp-alp'),
            array($this, 'render_checkbox_field'),
            'wp_alp_general_settings',
            'wp_alp_general_section',
            array('field' => 'auto_login')
        );

        add_settings_field(
            'override_wp_login',
            __('Override Default WordPress Login', 'wp-alp'),
            array($this, 'render_checkbox_field'),
            'wp_alp_general_settings',
            'wp_alp_general_section',
            array('field' => 'override_wp_login')
        );

        // Security settings
        register_setting(
            'wp_alp_security_settings',
            'wp_alp_security_options',
            array($this, 'sanitize_security_options')
        );

        add_settings_section(
            'wp_alp_security_section',
            __('Security Settings', 'wp-alp'),
            array($this, 'render_security_section'),
            'wp_alp_security_settings'
        );

        add_settings_field(
            'max_login_attempts',
            __('Max Login Attempts', 'wp-alp'),
            array($this, 'render_number_field'),
            'wp_alp_security_settings',
            'wp_alp_security_section',
            array('field' => 'max_login_attempts', 'min' => 1, 'max' => 20, 'step' => 1)
        );

        add_settings_field(
            'lockout_duration',
            __('Lockout Duration (seconds)', 'wp-alp'),
            array($this, 'render_number_field'),
            'wp_alp_security_settings',
            'wp_alp_security_section',
            array('field' => 'lockout_duration', 'min' => 60, 'max' => 86400, 'step' => 60)
        );

        add_settings_field(
            'recaptcha_site_key',
            __('reCAPTCHA Site Key', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_security_settings',
            'wp_alp_security_section',
            array('field' => 'recaptcha_site_key', 'placeholder' => __('Enter your reCAPTCHA site key', 'wp-alp'))
        );

        add_settings_field(
            'recaptcha_secret_key',
            __('reCAPTCHA Secret Key', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_security_settings',
            'wp_alp_security_section',
            array('field' => 'recaptcha_secret_key', 'placeholder' => __('Enter your reCAPTCHA secret key', 'wp-alp'))
        );

        // Social login settings
        register_setting(
            'wp_alp_social_settings',
            'wp_alp_social_options',
            array($this, 'sanitize_social_options')
        );

        add_settings_section(
            'wp_alp_social_section',
            __('Social Login Settings', 'wp-alp'),
            array($this, 'render_social_section'),
            'wp_alp_social_settings'
        );

        // Google settings
        add_settings_section(
            'wp_alp_google_section',
            __('Google', 'wp-alp'),
            array($this, 'render_google_section'),
            'wp_alp_social_settings'
        );

        add_settings_field(
            'google_enabled',
            __('Enable Google Login', 'wp-alp'),
            array($this, 'render_checkbox_field'),
            'wp_alp_social_settings',
            'wp_alp_google_section',
            array('field' => 'google[enabled]')
        );

        add_settings_field(
            'google_client_id',
            __('Client ID', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_social_settings',
            'wp_alp_google_section',
            array('field' => 'google[client_id]', 'placeholder' => __('Enter your Google client ID', 'wp-alp'))
        );

        add_settings_field(
            'google_client_secret',
            __('Client Secret', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_social_settings',
            'wp_alp_google_section',
            array('field' => 'google[client_secret]', 'placeholder' => __('Enter your Google client secret', 'wp-alp'))
        );

        // Facebook settings
        add_settings_section(
            'wp_alp_facebook_section',
            __('Facebook', 'wp-alp'),
            array($this, 'render_facebook_section'),
            'wp_alp_social_settings'
        );

        add_settings_field(
            'facebook_enabled',
            __('Enable Facebook Login', 'wp-alp'),
            array($this, 'render_checkbox_field'),
            'wp_alp_social_settings',
            'wp_alp_facebook_section',
            array('field' => 'facebook[enabled]')
        );

        add_settings_field(
            'facebook_client_id',
            __('App ID', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_social_settings',
            'wp_alp_facebook_section',
            array('field' => 'facebook[client_id]', 'placeholder' => __('Enter your Facebook app ID', 'wp-alp'))
        );

        add_settings_field(
            'facebook_client_secret',
            __('App Secret', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_social_settings',
            'wp_alp_facebook_section',
            array('field' => 'facebook[client_secret]', 'placeholder' => __('Enter your Facebook app secret', 'wp-alp'))
        );

        // Apple settings
        add_settings_section(
            'wp_alp_apple_section',
            __('Apple', 'wp-alp'),
            array($this, 'render_apple_section'),
            'wp_alp_social_settings'
        );

        add_settings_field(
            'apple_enabled',
            __('Enable Apple Login', 'wp-alp'),
            array($this, 'render_checkbox_field'),
            'wp_alp_social_settings',
            'wp_alp_apple_section',
            array('field' => 'apple[enabled]')
        );

        add_settings_field(
            'apple_client_id',
            __('Service ID', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_social_settings',
            'wp_alp_apple_section',
            array('field' => 'apple[client_id]', 'placeholder' => __('Enter your Apple service ID', 'wp-alp'))
        );

        add_settings_field(
            'apple_client_secret',
            __('Private Key', 'wp-alp'),
            array($this, 'render_textarea_field'),
            'wp_alp_social_settings',
            'wp_alp_apple_section',
            array('field' => 'apple[client_secret]', 'placeholder' => __('Enter your Apple private key', 'wp-alp'))
        );

        // LinkedIn settings
        add_settings_section(
            'wp_alp_linkedin_section',
            __('LinkedIn', 'wp-alp'),
            array($this, 'render_linkedin_section'),
            'wp_alp_social_settings'
        );

        add_settings_field(
            'linkedin_enabled',
            __('Enable LinkedIn Login', 'wp-alp'),
            array($this, 'render_checkbox_field'),
            'wp_alp_social_settings',
            'wp_alp_linkedin_section',
            array('field' => 'linkedin[enabled]')
        );

        add_settings_field(
            'linkedin_client_id',
            __('Client ID', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_social_settings',
            'wp_alp_linkedin_section',
            array('field' => 'linkedin[client_id]', 'placeholder' => __('Enter your LinkedIn client ID', 'wp-alp'))
        );

        add_settings_field(
            'linkedin_client_secret',
            __('Client Secret', 'wp-alp'),
            array($this, 'render_text_field'),
            'wp_alp_social_settings',
            'wp_alp_linkedin_section',
            array('field' => 'linkedin[client_secret]', 'placeholder' => __('Enter your LinkedIn client secret', 'wp-alp'))
        );
    }

    /**
     * Sanitize general options.
     *
     * @since    1.0.0
     * @param    array    $input    The input options.
     * @return   array              The sanitized options.
     */
    public function sanitize_general_options($input) {
        $sanitized = array();

        // Page IDs
        $page_fields = array('login_page', 'register_user_page', 'register_vendor_page', 'profile_completion_page');
        foreach ($page_fields as $field) {
            $sanitized[$field] = isset($input[$field]) ? absint($input[$field]) : 0;
        }

        // URLs
        $url_fields = array('user_redirect', 'lead_redirect', 'vendor_redirect');
        foreach ($url_fields as $field) {
            $sanitized[$field] = isset($input[$field]) ? esc_url_raw($input[$field]) : '';
        }

        // Checkboxes
        $checkbox_fields = array('auto_login', 'override_wp_login');
        foreach ($checkbox_fields as $field) {
            $sanitized[$field] = isset($input[$field]) ? (bool) $input[$field] : false;
        }

        return $sanitized;
    }

    /**
     * Sanitize security options.
     *
     * @since    1.0.0
     * @param    array    $input    The input options.
     * @return   array              The sanitized options.
     */
    public function sanitize_security_options($input) {
        $sanitized = array();

        // Numbers
        $sanitized['max_login_attempts'] = isset($input['max_login_attempts']) ? absint($input['max_login_attempts']) : 5;
        $sanitized['lockout_duration'] = isset($input['lockout_duration']) ? absint($input['lockout_duration']) : 1800;

        // Ensure valid ranges
        $sanitized['max_login_attempts'] = max(1, min(20, $sanitized['max_login_attempts']));
        $sanitized['lockout_duration'] = max(60, min(86400, $sanitized['lockout_duration']));

        // reCAPTCHA keys
        $sanitized['recaptcha_site_key'] = isset($input['recaptcha_site_key']) ? sanitize_text_field($input['recaptcha_site_key']) : '';
        $sanitized['recaptcha_secret_key'] = isset($input['recaptcha_secret_key']) ? sanitize_text_field($input['recaptcha_secret_key']) : '';

        return $sanitized;
    }

    /**
     * Sanitize social options.
     *
     * @since    1.0.0
     * @param    array    $input    The input options.
     * @return   array              The sanitized options.
     */
    public function sanitize_social_options($input) {
        $sanitized = array();

        $providers = array('google', 'facebook', 'apple', 'linkedin');

        foreach ($providers as $provider) {
            $sanitized[$provider] = array();

            // Enabled status
            $sanitized[$provider]['enabled'] = isset($input[$provider]['enabled']) ? (bool) $input[$provider]['enabled'] : false;

            // Client ID
            $sanitized[$provider]['client_id'] = isset($input[$provider]['client_id']) ? sanitize_text_field($input[$provider]['client_id']) : '';

            // Client Secret
            if ($provider === 'apple') {
                // For Apple, the secret is a private key
                $sanitized[$provider]['client_secret'] = isset($input[$provider]['client_secret']) ? sanitize_textarea_field($input[$provider]['client_secret']) : '';
            } else {
                $sanitized[$provider]['client_secret'] = isset($input[$provider]['client_secret']) ? sanitize_text_field($input[$provider]['client_secret']) : '';
            }
        }

        return $sanitized;
    }

    /**
     * Render the settings page.
     *
     * @since    1.0.0
     */
    public function display_settings_page() {
        include plugin_dir_path(__FILE__) . 'templates/settings.php';
    }

    /**
     * Render the security page.
     *
     * @since    1.0.0
     */
    public function display_security_page() {
        include plugin_dir_path(__FILE__) . 'templates/security.php';
    }

    /**
     * Render the social login page.
     *
     * @since    1.0.0
     */
    public function display_social_page() {
        include plugin_dir_path(__FILE__) . 'templates/social.php';
    }

    /**
     * Render the users page.
     *
     * @since    1.0.0
     */
    public function display_users_page() {
        include plugin_dir_path(__FILE__) . 'templates/users.php';
    }

    /**
     * Render the general section.
     *
     * @since    1.0.0
     */
    public function render_general_section() {
        echo '<p>' . esc_html__('Configure the general settings for the advanced login and registration system.', 'wp-alp') . '</p>';
    }

    /**
     * Render the security section.
     *
     * @since    1.0.0
     */
    public function render_security_section() {
        echo '<p>' . esc_html__('Configure security settings to protect your login and registration forms.', 'wp-alp') . '</p>';
    }

    /**
     * Render the social login section.
     *
     * @since    1.0.0
     */
    public function render_social_section() {
        echo '<p>' . esc_html__('Configure social login providers to allow users to sign in with their social accounts.', 'wp-alp') . '</p>';
    }

    /**
     * Render the Google section.
     *
     * @since    1.0.0
     */
    public function render_google_section() {
        ?>
        <p><?php esc_html_e('Configure Google login integration. You need to create a project in the Google Developer Console and configure OAuth credentials.', 'wp-alp'); ?></p>
        <p><strong><?php esc_html_e('Redirect URI:', 'wp-alp'); ?></strong> <code><?php echo esc_url(home_url('wp-json/wp-alp/v1/social/google/callback')); ?></code></p>
        <?php
    }

    /**
     * Render the Facebook section.
     *
     * @since    1.0.0
     */
    public function render_facebook_section() {
        ?>
        <p><?php esc_html_e('Configure Facebook login integration. You need to create an app in the Facebook Developer portal and configure OAuth settings.', 'wp-alp'); ?></p>
        <p><strong><?php esc_html_e('Redirect URI:', 'wp-alp'); ?></strong> <code><?php echo esc_url(home_url('wp-json/wp-alp/v1/social/facebook/callback')); ?></code></p>
        <?php
    }

    /**
     * Render the Apple section.
     *
     * @since    1.0.0
     */
    public function render_apple_section() {
        ?>
        <p><?php esc_html_e('Configure Apple login integration. You need to register your app with Apple and configure Sign in with Apple.', 'wp-alp'); ?></p>
        <p><strong><?php esc_html_e('Redirect URI:', 'wp-alp'); ?></strong> <code><?php echo esc_url(home_url('wp-json/wp-alp/v1/social/apple/callback')); ?></code></p>
        <?php
    }

    /**
     * Render the LinkedIn section.
     *
     * @since    1.0.0
     */
    public function render_linkedin_section() {
        ?>
        <p><?php esc_html_e('Configure LinkedIn login integration. You need to create an app in the LinkedIn Developer portal and configure OAuth settings.', 'wp-alp'); ?></p>
        <p><strong><?php esc_html_e('Redirect URI:', 'wp-alp'); ?></strong> <code><?php echo esc_url(home_url('wp-json/wp-alp/v1/social/linkedin/callback')); ?></code></p>
        <?php
    }

    /**
     * Render a text field.
     *
     * @since    1.0.0
     * @param    array    $args    The field arguments.
     */
    public function render_text_field($args) {
        $field = $args['field'];
        $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
        
        // Handle nested options (e.g., google[client_id])
        $parts = explode('[', $field);
        if (count($parts) > 1) {
            $option_name = 'wp_alp_' . $parts[0] . '_options';
            $options = get_option($option_name);
            $key = str_replace(']', '', $parts[1]);
            $value = isset($options[$parts[0]][$key]) ? $options[$parts[0]][$key] : '';
            $name = $option_name . '[' . $parts[0] . '][' . $key . ']';
        } else {
            $option_name = 'wp_alp_general_options';
            if (strpos($field, 'recaptcha_') === 0) {
                $option_name = 'wp_alp_security_options';
            }
            $options = get_option($option_name);
            $value = isset($options[$field]) ? $options[$field] : '';
            $name = $option_name . '[' . $field . ']';
        }

        ?>
        <input type="text" id="wp-alp-<?php echo esc_attr($field); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" class="regular-text">
        <?php
    }

    /**
     * Render a textarea field.
     *
     * @since    1.0.0
     * @param    array    $args    The field arguments.
     */
    public function render_textarea_field($args) {
        $field = $args['field'];
        $placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
        
        // Handle nested options (e.g., apple[client_secret])
        $parts = explode('[', $field);
        if (count($parts) > 1) {
            $option_name = 'wp_alp_' . $parts[0] . '_options';
            $options = get_option($option_name);
            $key = str_replace(']', '', $parts[1]);
            $value = isset($options[$parts[0]][$key]) ? $options[$parts[0]][$key] : '';
            $name = $option_name . '[' . $parts[0] . '][' . $key . ']';
        } else {
            $option_name = 'wp_alp_general_options';
            $options = get_option($option_name);
            $value = isset($options[$field]) ? $options[$field] : '';
            $name = $option_name . '[' . $field . ']';
        }

        ?>
        <textarea id="wp-alp-<?php echo esc_attr($field); ?>" name="<?php echo esc_attr($name); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" class="large-text" rows="5"><?php echo esc_textarea($value); ?></textarea>
        <?php
    }

    /**
     * Render a number field.
     *
     * @since    1.0.0
     * @param    array    $args    The field arguments.
     */
    public function render_number_field($args) {
        $field = $args['field'];
        $min = isset($args['min']) ? $args['min'] : 0;
        $max = isset($args['max']) ? $args['max'] : 999999;
        $step = isset($args['step']) ? $args['step'] : 1;
        
        $option_name = 'wp_alp_security_options';
        $options = get_option($option_name);
        $value = isset($options[$field]) ? $options[$field] : '';
        
        ?>
        <input type="number" id="wp-alp-<?php echo esc_attr($field); ?>" name="<?php echo esc_attr($option_name . '[' . $field . ']'); ?>" value="<?php echo esc_attr($value); ?>" min="<?php echo esc_attr($min); ?>" max="<?php echo esc_attr($max); ?>" step="<?php echo esc_attr($step); ?>" class="small-text">
        <?php
    }

    /**
     * Render a checkbox field.
     *
     * @since    1.0.0
     * @param    array    $args    The field arguments.
     */
    public function render_checkbox_field($args) {
        $field = $args['field'];
        
        // Handle nested options (e.g., google[enabled])
        $parts = explode('[', $field);
        if (count($parts) > 1) {
            $option_name = 'wp_alp_' . $parts[0] . '_options';
            $options = get_option($option_name);
            $key = str_replace(']', '', $parts[1]);
            $checked = isset($options[$parts[0]][$key]) ? $options[$parts[0]][$key] : false;
            $name = $option_name . '[' . $parts[0] . '][' . $key . ']';
        } else {
            $option_name = 'wp_alp_general_options';
            if (strpos($field, 'security_') === 0) {
                $option_name = 'wp_alp_security_options';
            }
            $options = get_option($option_name);
            $checked = isset($options[$field]) ? $options[$field] : false;
            $name = $option_name . '[' . $field . ']';
        }
        
        ?>
        <label for="wp-alp-<?php echo esc_attr($field); ?>">
            <input type="checkbox" id="wp-alp-<?php echo esc_attr($field); ?>" name="<?php echo esc_attr($name); ?>" value="1" <?php checked($checked); ?>>
            <?php esc_html_e('Enabled', 'wp-alp'); ?>
        </label>
        <?php
    }

    /**
     * Render a page select field.
     *
     * @since    1.0.0
     * @param    array    $args    The field arguments.
     */
    public function render_page_select($args) {
        $field = $args['field'];
        $option_name = 'wp_alp_general_options';
        $options = get_option($option_name);
        $selected = isset($options[$field]) ? $options[$field] : 0;
        
        $pages = get_pages(array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'hierarchical' => 0,
            'parent' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        ));
        
        ?>
        <select id="wp-alp-<?php echo esc_attr($field); ?>" name="<?php echo esc_attr($option_name . '[' . $field . ']'); ?>">
            <option value="0"><?php esc_html_e('Select a page', 'wp-alp'); ?></option>
            <?php foreach ($pages as $page) : ?>
                <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($selected, $page->ID); ?>><?php echo esc_html($page->post_title); ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description">
            <?php
            // Show shortcode for each page type
            $shortcode = '';
            switch ($field) {
                case 'login_page':
                    $shortcode = '[wp_alp_login]';
                    break;
                case 'register_user_page':
                    $shortcode = '[wp_alp_register_user]';
                    break;
                case 'register_vendor_page':
                    $shortcode = '[wp_alp_register_vendor]';
                    break;
                case 'profile_completion_page':
                    $shortcode = '[wp_alp_profile_completion]';
                    break;
            }
            
            if ($shortcode) {
                echo sprintf(
                    esc_html__('Add the %s shortcode to this page.', 'wp-alp'),
                    '<code>' . esc_html($shortcode) . '</code>'
                );
            }
            ?>
        </p>
        <?php
    }

    /**
     * Add plugin action links.
     *
     * @since    1.0.0
     * @param    array    $links    The existing action links.
     * @return   array              The modified action links.
     */
    public function add_action_links($links) {
        $plugin_links = array(
            '<a href="' . admin_url('admin.php?page=wp-advanced-login-pro') . '">' . __('Settings', 'wp-alp') . '</a>',
        );
        
        return array_merge($plugin_links, $links);
    }

    /**
     * Display admin notices.
     *
     * @since    1.0.0
     */
    public function display_admin_notices() {
        // Check if required pages are set up
        $options = get_option('wp_alp_general_options');
        $required_pages = array(
            'login_page' => __('Login Page', 'wp-alp'),
            'register_user_page' => __('Register User Page', 'wp-alp'),
            'register_vendor_page' => __('Register Vendor Page', 'wp-alp'),
            'profile_completion_page' => __('Profile Completion Page', 'wp-alp'),
        );
        
        $missing_pages = array();
        foreach ($required_pages as $page_key => $page_name) {
            if (empty($options[$page_key])) {
                $missing_pages[] = $page_name;
            }
        }
        
        if (!empty($missing_pages) && isset($_GET['page']) && strpos($_GET['page'], 'wp-advanced-login-pro') === 0) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <?php
                    echo sprintf(
                        esc_html__('Please configure the following pages: %s', 'wp-alp'),
                        '<strong>' . implode(', ', $missing_pages) . '</strong>'
                    );
                    ?>
                </p>
                <p>
                    <a href="<?php echo admin_url('admin.php?page=wp-advanced-login-pro'); ?>" class="button button-primary">
                        <?php esc_html_e('Configure Pages', 'wp-alp'); ?>
                    </a>
                </p>
            </div>
            <?php
        }
        
        // Check if social login providers are configured
        $social_options = get_option('wp_alp_social_options');
        $enabled_providers = array();
        
        $providers = array(
            'google' => __('Google', 'wp-alp'),
            'facebook' => __('Facebook', 'wp-alp'),
            'apple' => __('Apple', 'wp-alp'),
            'linkedin' => __('LinkedIn', 'wp-alp'),
        );
        
        foreach ($providers as $provider_key => $provider_name) {
            if (isset($social_options[$provider_key]['enabled']) && $social_options[$provider_key]['enabled']) {
                if (empty($social_options[$provider_key]['client_id']) || empty($social_options[$provider_key]['client_secret'])) {
                    $enabled_providers[] = $provider_name;
                }
            }
        }
        
        if (!empty($enabled_providers) && isset($_GET['page']) && $_GET['page'] === 'wp-advanced-login-pro-social') {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <?php
                    echo sprintf(
                        esc_html__('The following social providers are enabled but not fully configured: %s', 'wp-alp'),
                        '<strong>' . implode(', ', $enabled_providers) . '</strong>'
                    );
                    ?>
                </p>
            </div>
            <?php
        }
    }
    
    /**
     * Process sending reminders to users without leads.
     *
     * @since    1.0.0
     */
    public function process_send_reminder() {
        // Check if the form was submitted and the nonce is valid
        if (
            isset($_POST['wp_alp_send_reminder']) &&
            isset($_POST['wp_alp_nonce']) &&
            wp_verify_nonce($_POST['wp_alp_nonce'], 'wp_alp_convert_users_nonce')
        ) {
            // Get users without leads
            $users_without_leads = $this->get_users_without_leads();
            $count = 0;
            
            // Send reminder emails
            foreach ($users_without_leads as $user) {
                if ($this->send_profile_completion_email($user)) {
                    $count++;
                }
            }
            
            // Show success message
            add_action('admin_notices', function() use ($count) {
                ?>
                <div class="notice notice-success is-dismissible">
                    <p>
                        <?php
                        echo sprintf(
                            esc_html(_n('Reminder email sent to %d user.', 'Reminder emails sent to %d users.', $count, 'wp-alp')),
                            $count
                        );
                        ?>
                    </p>
                </div>
                <?php
            });
        }
    }
    
    /**
     * Get users without leads.
     *
     * @since    1.0.0
     * @return   array    List of users without leads.
     */
    private function get_users_without_leads() {
        $users = get_users(array(
            'role' => 'subscriber',
            'fields' => array('ID', 'user_email', 'display_name'),
        ));
        
        $users_without_leads = array();
        
        foreach ($users as $user) {
            $lead_id = get_user_meta($user->ID, 'wp_alp_lead_id', true);
            
            if (empty($lead_id)) {
                $users_without_leads[] = $user;
            }
        }
        
        return $users_without_leads;
    }
    
    /**
     * Send profile completion email to a user.
     *
     * @since    1.0.0
     * @param    WP_User    $user    The user to send the email to.
     * @return   bool                Whether the email was sent successfully.
     */
    private function send_profile_completion_email($user) {
        $options = get_option('wp_alp_general_options');
        $profile_completion_page = !empty($options['profile_completion_page']) ? get_permalink($options['profile_completion_page']) : home_url();
        
        $subject = sprintf(__('Complete your profile on %s', 'wp-alp'), get_bloginfo('name'));
        
        $message = sprintf(
            __('Hello %s,', 'wp-alp'),
            $user->display_name
        ) . "\n\n";
        
        $message .= __('We noticed that you haven\'t completed your profile yet. Complete it now to access all features:', 'wp-alp') . "\n\n";
        $message .= $profile_completion_page . "\n\n";
        $message .= __('Thank you!', 'wp-alp') . "\n";
        $message .= get_bloginfo('name');
        
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        return wp_mail($user->user_email, $subject, $message, $headers);
    }
}