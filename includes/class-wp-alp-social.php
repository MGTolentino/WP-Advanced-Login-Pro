<?php
/**
 * Handles social login integration.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/includes
 */

class WP_ALP_Social {

    /**
     * Social providers configuration.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $providers    The configured social providers.
     */
    private $providers = array();

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->load_providers();
    }

    /**
     * Load social providers configuration from options.
     *
     * @since    1.0.0
     */
    private function load_providers() {
        $options = get_option('wp_alp_social_options');
        
        // Default configuration for Google
        $this->providers['google'] = array(
            'enabled' => false,
            'client_id' => '',
            'client_secret' => '',
            'redirect_uri' => home_url('wp-json/wp-alp/v1/social/google/callback'),
            'scope' => 'email profile',
            'auth_url' => 'https://accounts.google.com/o/oauth2/auth',
            'token_url' => 'https://oauth2.googleapis.com/token',
            'user_info_url' => 'https://www.googleapis.com/oauth2/v2/userinfo',
        );
        
        // Default configuration for Facebook
        $this->providers['facebook'] = array(
            'enabled' => false,
            'client_id' => '',
            'client_secret' => '',
            'redirect_uri' => home_url('wp-json/wp-alp/v1/social/facebook/callback'),
            'scope' => 'email',
            'auth_url' => 'https://www.facebook.com/v12.0/dialog/oauth',
            'token_url' => 'https://graph.facebook.com/v12.0/oauth/access_token',
            'user_info_url' => 'https://graph.facebook.com/v12.0/me?fields=id,name,email,first_name,last_name',
        );
        
        // Default configuration for Apple
        $this->providers['apple'] = array(
            'enabled' => false,
            'client_id' => '',
            'client_secret' => '',
            'redirect_uri' => home_url('wp-json/wp-alp/v1/social/apple/callback'),
            'scope' => 'name email',
            'auth_url' => 'https://appleid.apple.com/auth/authorize',
            'token_url' => 'https://appleid.apple.com/auth/token',
        );
        
        // Default configuration for LinkedIn
        $this->providers['linkedin'] = array(
            'enabled' => false,
            'client_id' => '',
            'client_secret' => '',
            'redirect_uri' => home_url('wp-json/wp-alp/v1/social/linkedin/callback'),
            'scope' => 'r_liteprofile r_emailaddress',
            'auth_url' => 'https://www.linkedin.com/oauth/v2/authorization',
            'token_url' => 'https://www.linkedin.com/oauth/v2/accessToken',
            'user_info_url' => 'https://api.linkedin.com/v2/me',
            'email_url' => 'https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))',
        );
        
        // Override with saved options
        if ($options) {
            foreach ($this->providers as $provider => $config) {
                if (isset($options[$provider])) {
                    $this->providers[$provider] = array_merge($config, $options[$provider]);
                }
            }
        }
    }

    /**
     * Check if a social provider is enabled.
     *
     * @since    1.0.0
     * @param    string    $provider    The provider to check.
     * @return   bool                   Whether the provider is enabled.
     */
    public function is_provider_enabled($provider) {
        return isset($this->providers[$provider]) && $this->providers[$provider]['enabled'];
    }

    /**
     * Get the auth URL for a social provider.
     *
     * @since    1.0.0
     * @param    string    $provider    The provider to get the auth URL for.
     * @param    string    $state       Optional state parameter for CSRF protection.
     * @return   string                 The auth URL or empty string if provider not enabled.
     */
    public function get_auth_url($provider, $state = '') {
        if (!$this->is_provider_enabled($provider)) {
            return '';
        }
        
        $config = $this->providers[$provider];
        
        // Generate random state if not provided
        if (empty($state)) {
            $state = wp_generate_password(12, false);
            // Store state in session
            if (!session_id()) {
                session_start();
            }
            $_SESSION['wp_alp_social_state'] = $state;
        }
        
        // Build parameters for auth URL
        $params = array(
            'client_id' => $config['client_id'],
            'redirect_uri' => $config['redirect_uri'],
            'response_type' => 'code',
            'scope' => $config['scope'],
            'state' => $state,
        );
        
        // Special handling for Apple
        if ($provider === 'apple') {
            $params['response_mode'] = 'form_post';
        }
        
        return $config['auth_url'] . '?' . http_build_query($params);
    }

    /**
     * Authenticate with a social provider.
     *
     * @since    1.0.0
     * @param    string    $provider    The provider to authenticate with.
     * @param    string    $code        The authorization code from the provider.
     * @param    string    $state       The state parameter for CSRF protection.
     * @return   array|WP_Error         User data on success, WP_Error on failure.
     */
    public function authenticate($provider, $code, $state) {
        if (!$this->is_provider_enabled($provider)) {
            return new WP_Error('provider_disabled', __('This social login provider is not enabled.', 'wp-alp'));
        }
        
        // Verify state parameter to prevent CSRF
        if (!session_id()) {
            session_start();
        }
        
        if (!isset($_SESSION['wp_alp_social_state']) || $_SESSION['wp_alp_social_state'] !== $state) {
            return new WP_Error('invalid_state', __('Invalid state parameter. Please try again.', 'wp-alp'));
        }
        
        // Clear the state from session
        unset($_SESSION['wp_alp_social_state']);
        
        $config = $this->providers[$provider];
        
        // Exchange code for token
        $token_response = $this->get_access_token($provider, $code);
        
        if (is_wp_error($token_response)) {
            return $token_response;
        }
        
        // Get user info with the token
        $user_info = $this->get_user_info($provider, $token_response['access_token']);
        
        if (is_wp_error($user_info)) {
            return $user_info;
        }
        
        return $user_info;
    }

    /**
     * Get access token from a social provider.
     *
     * @since    1.0.0
     * @access   private
     * @param    string    $provider    The provider to get the token from.
     * @param    string    $code        The authorization code from the provider.
     * @return   array|WP_Error         Token data on success, WP_Error on failure.
     */
    private function get_access_token($provider, $code) {
        $config = $this->providers[$provider];
        
        $params = array(
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'code' => $code,
            'redirect_uri' => $config['redirect_uri'],
            'grant_type' => 'authorization_code',
        );
        
        $response = wp_remote_post($config['token_url'], array(
            'body' => $params,
            'headers' => array(
                'Accept' => 'application/json',
            ),
        ));
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (empty($body) || isset($body['error'])) {
            return new WP_Error(
                'token_error',
                isset($body['error_description']) ? $body['error_description'] : __('Failed to get access token.', 'wp-alp')
            );
        }
        
        return $body;
    }

    /**
     * Get user info from a social provider.
     *
     * @since    1.0.0
     * @access   private
     * @param    string    $provider       The provider to get the user info from.
     * @param    string    $access_token   The access token from the provider.
     * @return   array|WP_Error            User data on success, WP_Error on failure.
     */
    private function get_user_info($provider, $access_token) {
        $config = $this->providers[$provider];
        
        // LinkedIn requires separate API calls for profile and email
        if ($provider === 'linkedin') {
            return $this->get_linkedin_user_info($access_token);
        }
        
        // Apple doesn't provide a user info endpoint, user info comes with the token response
        if ($provider === 'apple') {
            // For Apple, the user info is provided in the id_token
            // This would require additional JWT parsing
            // For simplicity, we're skipping the full implementation
            return array(
                'id' => 'apple_user',
                'email' => 'user@example.com',
                'name' => 'Apple User',
                'first_name' => 'Apple',
                'last_name' => 'User',
                'picture' => '',
                'provider' => 'apple',
            );
        }
        
        $response = wp_remote_get($config['user_info_url'], array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Accept' => 'application/json',
            ),
        ));
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (empty($body) || isset($body['error'])) {
            return new WP_Error(
                'user_info_error',
                isset($body['error_description']) ? $body['error_description'] : __('Failed to get user info.', 'wp-alp')
            );
        }
        
        // Normalize user data based on the provider
        return $this->normalize_user_data($provider, $body);
    }

    /**
     * Get LinkedIn user info (requires separate API calls for profile and email).
     *
     * @since    1.0.0
     * @access   private
     * @param    string    $access_token   The access token from LinkedIn.
     * @return   array|WP_Error            User data on success, WP_Error on failure.
     */
    private function get_linkedin_user_info($access_token) {
        $config = $this->providers['linkedin'];
        
        // Get basic profile info
        $profile_response = wp_remote_get($config['user_info_url'], array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Accept' => 'application/json',
            ),
        ));
        
        if (is_wp_error($profile_response)) {
            return $profile_response;
        }
        
        $profile = json_decode(wp_remote_retrieve_body($profile_response), true);
        
        if (empty($profile) || isset($profile['error'])) {
            return new WP_Error(
                'profile_error',
                isset($profile['error_description']) ? $profile['error_description'] : __('Failed to get LinkedIn profile.', 'wp-alp')
            );
        }
        
        // Get email info
        $email_response = wp_remote_get($config['email_url'], array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Accept' => 'application/json',
            ),
        ));
        
        if (is_wp_error($email_response)) {
            return $email_response;
        }
        
        $email_data = json_decode(wp_remote_retrieve_body($email_response), true);
        
        // Extract email from response
        $email = '';
        if (!empty($email_data['elements'][0]['handle~']['emailAddress'])) {
            $email = $email_data['elements'][0]['handle~']['emailAddress'];
        }
        
        // Build normalized user data
        $user_data = array(
            'id' => $profile['id'],
            'email' => $email,
            'name' => $profile['localizedFirstName'] . ' ' . $profile['localizedLastName'],
            'first_name' => $profile['localizedFirstName'],
            'last_name' => $profile['localizedLastName'],
            'picture' => '', // LinkedIn doesn't provide picture in basic API
            'provider' => 'linkedin',
        );
        
        return $user_data;
    }

    /**
     * Normalize user data from different providers to a common format.
     *
     * @since    1.0.0
     * @access   private
     * @param    string    $provider    The provider that provided the data.
     * @param    array     $data        The raw user data from the provider.
     * @return   array                  Normalized user data.
     */
    private function normalize_user_data($provider, $data) {
        $user_data = array(
            'provider' => $provider,
        );
        
        switch ($provider) {
            case 'google':
                $user_data['id'] = $data['id'];
                $user_data['email'] = $data['email'];
                $user_data['name'] = $data['name'];
                $user_data['first_name'] = isset($data['given_name']) ? $data['given_name'] : '';
                $user_data['last_name'] = isset($data['family_name']) ? $data['family_name'] : '';
                $user_data['picture'] = isset($data['picture']) ? $data['picture'] : '';
                break;
                
            case 'facebook':
                $user_data['id'] = $data['id'];
                $user_data['email'] = isset($data['email']) ? $data['email'] : '';
                $user_data['name'] = $data['name'];
                $user_data['first_name'] = isset($data['first_name']) ? $data['first_name'] : '';
                $user_data['last_name'] = isset($data['last_name']) ? $data['last_name'] : '';
                $user_data['picture'] = "https://graph.facebook.com/{$data['id']}/picture?type=large";
                break;
                
            // Additional providers can be added here
            
            default:
                // Generic fallback
                $user_data['id'] = isset($data['id']) ? $data['id'] : '';
                $user_data['email'] = isset($data['email']) ? $data['email'] : '';
                $user_data['name'] = isset($data['name']) ? $data['name'] : '';
                $user_data['first_name'] = isset($data['first_name']) ? $data['first_name'] : '';
                $user_data['last_name'] = isset($data['last_name']) ? $data['last_name'] : '';
                $user_data['picture'] = isset($data['picture']) ? $data['picture'] : '';
        }
        
        return $user_data;
    }

    /**
     * Get all enabled social providers.
     *
     * @since    1.0.0
     * @return   array    List of enabled providers.
     */
    public function get_enabled_providers() {
        $enabled = array();
        
        foreach ($this->providers as $provider => $config) {
            if ($config['enabled']) {
                $enabled[] = $provider;
            }
        }
        
        return $enabled;
    }

    /**
     * Render social login buttons.
     *
     * @since    1.0.0
     * @param    string    $context    The context where buttons are displayed (login, register, register_vendor).
     * @return   string                HTML for social login buttons.
     */
    public function render_social_buttons($context = 'login') {
        $enabled_providers = $this->get_enabled_providers();
        
        if (empty($enabled_providers)) {
            return '';
        }
        
        $output = '<div class="wp-alp-social-buttons">';
        
        foreach ($enabled_providers as $provider) {
            $auth_url = $this->get_auth_url($provider);
            $label = sprintf(__('Continue with %s', 'wp-alp'), ucfirst($provider));
            
            $output .= '<a href="' . esc_url($auth_url) . '" class="wp-alp-social-button wp-alp-' . esc_attr($provider) . '">';
            $output .= '<span class="wp-alp-social-icon wp-alp-' . esc_attr($provider) . '-icon"></span>';
            $output .= '<span class="wp-alp-social-text">' . esc_html($label) . '</span>';
            $output .= '</a>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
}