<?php
/**
 * Handles user management functionality.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/includes
 */

class WP_ALP_User_Manager {

    /**
     * Security manager instance.
     *
     * @since    1.0.0
     * @access   private
     * @var      WP_ALP_Security    $security    The security manager instance.
     */
    private $security;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    WP_ALP_Security    $security    The security manager instance.
     */
    public function __construct($security = null) {
        if ($security) {
            $this->security = $security;
        } else {
            $this->security = new WP_ALP_Security();
        }
    }

    /**
     * Register a new user.
     *
     * @since    1.0.0
     * @param    array    $user_data    The user data for registration.
     * @param    string   $user_type    The type of user (normal or vendor).
     * @return   int|WP_Error           User ID on success, WP_Error on failure.
     */
    public function register_user($user_data, $user_type = 'normal') {
        // Sanitize input data
        $sanitized_data = $this->sanitize_user_data($user_data);

        // Validate user data
        $validation = $this->validate_user_data($sanitized_data, $user_type);
        if (is_wp_error($validation)) {
            return $validation;
        }

        // Create user
        $userdata = array(
            'user_login'    => $sanitized_data['username'] ?? $sanitized_data['email'],
            'user_email'    => $sanitized_data['email'],
            'user_pass'     => $sanitized_data['password'],
            'first_name'    => $sanitized_data['first_name'] ?? '',
            'last_name'     => $sanitized_data['last_name'] ?? '',
            'display_name'  => $sanitized_data['first_name'] ?? '',
            'role'          => 'subscriber', // Default role for all users
        );

        $user_id = wp_insert_user($userdata);

        if (is_wp_error($user_id)) {
            return $user_id;
        }

        // Store user type in user meta
        update_user_meta($user_id, 'wp_alp_user_type', $user_type);

        // Store additional meta for vendors if applicable
        if ($user_type === 'vendor') {
            update_user_meta($user_id, 'wp_alp_vendor_status', 'pending');
            // Other vendor-specific meta can be added here
        }

        // Additional custom meta fields
        $meta_fields = array('phone', 'company', 'address', 'bio');
        foreach ($meta_fields as $field) {
            if (isset($sanitized_data[$field])) {
                update_user_meta($user_id, 'wp_alp_' . $field, $sanitized_data[$field]);
            }
        }

        return $user_id;
    }

    /**
     * Create a lead user from provided data.
     *
     * @since    1.0.0
     * @param    int      $user_id    The WordPress user ID.
     * @param    array    $lead_data  The lead-specific data.
     * @return   int|WP_Error         Lead ID on success, WP_Error on failure.
     */
    public function create_lead($user_id, $lead_data) {
        // Ensure the user exists
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            return new WP_Error('invalid_user', __('Invalid user ID.', 'wp-alp'));
        }

        // Sanitize lead data
        $sanitized_data = $this->sanitize_lead_data($lead_data);

        // Check if the user is already a lead
        $existing_lead = $this->get_lead_by_user_id($user_id);
        if ($existing_lead) {
            // Update existing lead instead of creating a new one
            return $this->update_lead($existing_lead, $sanitized_data);
        }

        // Create lead in JetEngine CCT
        if (function_exists('jet_engine')) {
            // Prepare lead data for JetEngine CCT
            $lead_cct_data = array(
                'Nombre'    => $user->first_name,
                'Apellido'  => $user->last_name,
                'EMAIL'     => $user->user_email,
                'CELULAR'   => $sanitized_data['phone'] ?? '',
                'status'    => 'nuevo',
                '_user_id'  => $user_id, // Store reference to WordPress user
            );

            // Insert lead into CCT
            $lead_id = jet_engine()->db->insert_item($lead_cct_data, 'leads');

            if (!$lead_id) {
                return new WP_Error('lead_creation_failed', __('Failed to create lead.', 'wp-alp'));
            }

            // Store lead ID in user meta
            update_user_meta($user_id, 'wp_alp_lead_id', $lead_id);
            update_user_meta($user_id, 'wp_alp_user_type', 'lead');

                            // Create event if event data is provided
            if (!empty($sanitized_data['event_type'])) {
                $event_cct_data = array(
                    'Tipo de Evento'            => $sanitized_data['event_type'],
                    'Fecha de evento'           => $sanitized_data['event_date'] ?? '',
                    'Direccion de evento'       => $sanitized_data['event_address'] ?? '',
                    'Cantidad de asistentes'    => $sanitized_data['event_attendees'] ?? '',
                    'Detalles adicionales'      => $sanitized_data['event_details'] ?? '',
                    'Categoria_padre'           => $sanitized_data['event_category'] ?? '',
                    'Lead-Servicio-de-Interes'  => $sanitized_data['service_interest'] ?? '',
                    'inserted_cct_leads'        => $lead_id,
                    'status'                    => 'nuevo',
                );
                
                // Insert event into CCT
                $event_id = jet_engine()->db->insert_item($event_cct_data, 'eventos');
                
                if ($event_id) {
                    // Store event ID in user meta
                    update_user_meta($user_id, 'wp_alp_event_id', $event_id);
                }
            }
            
            return $lead_id;
        }
        
        // Fallback if JetEngine is not available
        return new WP_Error('jetengine_missing', __('JetEngine is required for lead management.', 'wp-alp'));
    }
    
    /**
     * Get lead by user ID.
     *
     * @since    1.0.0
     * @param    int      $user_id    The WordPress user ID.
     * @return   int|false            Lead ID if found, false otherwise.
     */
    private function get_lead_by_user_id($user_id) {
        // First check user meta
        $lead_id = get_user_meta($user_id, 'wp_alp_lead_id', true);
        if ($lead_id) {
            return $lead_id;
        }
        
        // If not found in meta, try to find in JetEngine CCT
        if (function_exists('jet_engine')) {
            $args = array(
                'meta_query' => array(
                    array(
                        'key'   => '_user_id',
                        'value' => $user_id,
                    ),
                ),
            );
            
            $leads = jet_engine()->db->get_items('leads', $args);
            
            if (!empty($leads)) {
                $lead_id = $leads[0]['_ID'];
                
                // Store in user meta for future queries
                update_user_meta($user_id, 'wp_alp_lead_id', $lead_id);
                
                return $lead_id;
            }
        }
        
        return false;
    }
    
    /**
     * Update lead data.
     *
     * @since    1.0.0
     * @param    int      $lead_id     The lead ID.
     * @param    array    $lead_data   The lead data to update.
     * @return   bool|WP_Error         True on success, WP_Error on failure.
     */
    private function update_lead($lead_id, $lead_data) {
        if (!function_exists('jet_engine')) {
            return new WP_Error('jetengine_missing', __('JetEngine is required for lead management.', 'wp-alp'));
        }
        
        // Get existing lead data
        $existing_lead = jet_engine()->db->get_item($lead_id, 'leads');
        
        if (empty($existing_lead)) {
            return new WP_Error('lead_not_found', __('Lead not found.', 'wp-alp'));
        }
        
        // Prepare update data
        $update_data = array();
        
        // Only update fields that are provided
        if (isset($lead_data['phone'])) {
            $update_data['CELULAR'] = $lead_data['phone'];
        }
        
        // Add other fields as needed
        
        // Only perform update if there are changes
        if (!empty($update_data)) {
            $result = jet_engine()->db->update_item($lead_id, $update_data, 'leads');
            
            if (!$result) {
                return new WP_Error('lead_update_failed', __('Failed to update lead.', 'wp-alp'));
            }
        }
        
        // Create event if event data is provided
        if (!empty($lead_data['event_type'])) {
            $event_cct_data = array(
                'Tipo de Evento'            => $lead_data['event_type'],
                'Fecha de evento'           => $lead_data['event_date'] ?? '',
                'Direccion de evento'       => $lead_data['event_address'] ?? '',
                'Cantidad de asistentes'    => $lead_data['event_attendees'] ?? '',
                'Detalles adicionales'      => $lead_data['event_details'] ?? '',
                'Categoria_padre'           => $lead_data['event_category'] ?? '',
                'Lead-Servicio-de-Interes'  => $lead_data['service_interest'] ?? '',
                'inserted_cct_leads'        => $lead_id,
                'status'                    => 'nuevo',
            );
            
            // Insert event into CCT
            $event_id = jet_engine()->db->insert_item($event_cct_data, 'eventos');
            
            if ($event_id) {
                // Get user ID from lead
                $user_id = $existing_lead['_user_id'];
                if ($user_id) {
                    update_user_meta($user_id, 'wp_alp_event_id', $event_id);
                }
            }
        }
        
        return true;
    }
    
    /**
     * Create or update a vendor for a user.
     *
     * @since    1.0.0
     * @param    int      $user_id       The WordPress user ID.
     * @param    array    $vendor_data   The vendor data.
     * @return   int|WP_Error            Vendor ID on success, WP_Error on failure.
     */
    public function create_vendor($user_id, $vendor_data = array()) {
        // Ensure the user exists
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            return new WP_Error('invalid_user', __('Invalid user ID.', 'wp-alp'));
        }
        
        // Check if vendor post type exists (HivePress compatibility)
        if (!post_type_exists('hp_vendor')) {
            return new WP_Error('vendor_type_missing', __('Vendor post type not found.', 'wp-alp'));
        }
        
        // Check if the user already has a vendor
        $existing_vendor_id = $this->get_vendor_by_user_id($user_id);
        
        if ($existing_vendor_id) {
            // Update existing vendor
            return $this->update_vendor($existing_vendor_id, $vendor_data);
        }
        
        // Create new vendor post
        $vendor_args = array(
            'post_type'    => 'hp_vendor',
            'post_status'  => 'publish',
            'post_title'   => $user->display_name,
            'post_author'  => $user_id,
        );
        
        $vendor_id = wp_insert_post($vendor_args);
        
        if (is_wp_error($vendor_id)) {
            return $vendor_id;
        }
        
        // Link vendor to user
        update_post_meta($vendor_id, 'hp_user_id', $user_id);
        update_user_meta($user_id, 'hp_vendor_id', $vendor_id);
        update_user_meta($user_id, 'wp_alp_user_type', 'vendor');
        
        // Add vendor data as meta
        foreach ($vendor_data as $key => $value) {
            update_post_meta($vendor_id, 'hp_' . $key, $value);
        }
        
        return $vendor_id;
    }
    
    /**
     * Get vendor by user ID.
     *
     * @since    1.0.0
     * @param    int      $user_id    The WordPress user ID.
     * @return   int|false            Vendor ID if found, false otherwise.
     */
    private function get_vendor_by_user_id($user_id) {
        // First check user meta
        $vendor_id = get_user_meta($user_id, 'hp_vendor_id', true);
        if ($vendor_id) {
            return $vendor_id;
        }
        
        // If not found in meta, query posts
        $args = array(
            'post_type'      => 'hp_vendor',
            'posts_per_page' => 1,
            'meta_query'     => array(
                array(
                    'key'   => 'hp_user_id',
                    'value' => $user_id,
                ),
            ),
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            $vendor_id = $query->posts[0]->ID;
            
            // Store in user meta for future queries
            update_user_meta($user_id, 'hp_vendor_id', $vendor_id);
            
            return $vendor_id;
        }
        
        return false;
    }
    
    /**
     * Update vendor data.
     *
     * @since    1.0.0
     * @param    int      $vendor_id     The vendor ID.
     * @param    array    $vendor_data   The vendor data to update.
     * @return   int|WP_Error            Vendor ID on success, WP_Error on failure.
     */
    private function update_vendor($vendor_id, $vendor_data) {
        // Verify vendor exists
        $vendor = get_post($vendor_id);
        
        if (!$vendor || $vendor->post_type !== 'hp_vendor') {
            return new WP_Error('vendor_not_found', __('Vendor not found.', 'wp-alp'));
        }
        
        // Update vendor data as meta
        foreach ($vendor_data as $key => $value) {
            update_post_meta($vendor_id, 'hp_' . $key, $value);
        }
        
        return $vendor_id;
    }
    
    /**
     * Register a user from social login data.
     *
     * @since    1.0.0
     * @param    array    $social_data    The normalized user data from social provider.
     * @param    string   $user_type      The type of user (normal or vendor).
     * @return   int|WP_Error             User ID on success, WP_Error on failure.
     */
    public function register_social_user($social_data, $user_type = 'normal') {
        // Check if email already exists
        $existing_user = get_user_by('email', $social_data['email']);
        
        if ($existing_user) {
            // Link social profile to existing user and return user ID
            $this->link_social_profile($existing_user->ID, $social_data);
            return $existing_user->ID;
        }
        
        // Generate username from email or name
        $username = $this->generate_username_from_email($social_data['email']);
        
        // Generate random password
        $password = wp_generate_password(16, true, true);
        
        // Prepare user data
        $userdata = array(
            'user_login'    => $username,
            'user_email'    => $social_data['email'],
            'user_pass'     => $password,
            'first_name'    => $social_data['first_name'] ?? '',
            'last_name'     => $social_data['last_name'] ?? '',
            'display_name'  => $social_data['name'] ?? $username,
            'role'          => 'subscriber',
        );
        
        // Create user
        $user_id = wp_insert_user($userdata);
        
        if (is_wp_error($user_id)) {
            return $user_id;
        }
        
        // Store user type in user meta
        update_user_meta($user_id, 'wp_alp_user_type', $user_type);
        
        // Store social profile data
        $this->link_social_profile($user_id, $social_data);
        
        // Set flag for profile completion
        update_user_meta($user_id, 'wp_alp_profile_status', 'incomplete');
        
        return $user_id;
    }
    
    /**
     * Link a social profile to a WordPress user.
     *
     * @since    1.0.0
     * @access   private
     * @param    int      $user_id       The WordPress user ID.
     * @param    array    $social_data   The social profile data.
     */
    private function link_social_profile($user_id, $social_data) {
        $provider = $social_data['provider'];
        $provider_id = $social_data['id'];
        
        // Store provider ID in user meta
        update_user_meta($user_id, 'wp_alp_' . $provider . '_id', $provider_id);
        
        // Store timestamp of last login with this provider
        update_user_meta($user_id, 'wp_alp_' . $provider . '_last_login', time());
        
        // Store profile picture URL if available
        if (!empty($social_data['picture'])) {
            update_user_meta($user_id, 'wp_alp_' . $provider . '_picture', $social_data['picture']);
            
            // If user doesn't have a profile picture yet, set this as the default
            if (!get_user_meta($user_id, 'wp_alp_profile_picture', true)) {
                update_user_meta($user_id, 'wp_alp_profile_picture', $social_data['picture']);
            }
        }
        
        // Store whether user is verified on this platform (if available)
        if (isset($social_data['verified']) && $social_data['verified']) {
            update_user_meta($user_id, 'wp_alp_' . $provider . '_verified', true);
        }
    }
    
    /**
     * Generate a unique username from an email address.
     *
     * @since    1.0.0
     * @access   private
     * @param    string    $email    The email address.
     * @return   string              A unique username.
     */
    private function generate_username_from_email($email) {
        // Extract the part before @ as base username
        $base_username = strtolower(explode('@', $email)[0]);
        
        // Remove special characters
        $base_username = preg_replace('/[^a-z0-9]/', '', $base_username);
        
        // Ensure minimum length
        if (strlen($base_username) < 4) {
            $base_username .= rand(1000, 9999);
        }
        
        // Check if username exists
        $username = $base_username;
        $i = 1;
        
        while (username_exists($username)) {
            $username = $base_username . $i;
            $i++;
        }
        
        return $username;
    }
    
    /**
     * Sanitize user data for registration.
     *
     * @since    1.0.0
     * @access   private
     * @param    array    $user_data    The raw user data.
     * @return   array                  Sanitized user data.
     */
    private function sanitize_user_data($user_data) {
        $sanitized = array();
        
        // Map fields to sanitization methods
        $fields = array(
            'username'    => 'username',
            'email'       => 'email',
            'first_name'  => 'text',
            'last_name'   => 'text',
            'password'    => 'raw', // Passwords need special handling
            'phone'       => 'text',
            'company'     => 'text',
            'address'     => 'textarea',
            'bio'         => 'textarea',
        );
        
        foreach ($fields as $field => $type) {
            if (isset($user_data[$field])) {
                if ($type === 'raw') {
                    $sanitized[$field] = $user_data[$field];
                } else {
                    $sanitized[$field] = $this->security->sanitize_input($user_data[$field], $type);
                }
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Sanitize lead data for creating/updating leads.
     *
     * @since    1.0.0
     * @access   private
     * @param    array    $lead_data    The raw lead data.
     * @return   array                  Sanitized lead data.
     */
    private function sanitize_lead_data($lead_data) {
        $sanitized = array();
        
        // Map fields to sanitization methods
        $fields = array(
            'phone'             => 'text',
            'event_type'        => 'text',
            'event_date'        => 'text',
            'event_address'     => 'textarea',
            'event_attendees'   => 'int',
            'event_details'     => 'textarea',
            'event_category'    => 'text',
            'service_interest'  => 'text',
        );
        
        foreach ($fields as $field => $type) {
            if (isset($lead_data[$field])) {
                $sanitized[$field] = $this->security->sanitize_input($lead_data[$field], $type);
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Validate user data for registration.
     *
     * @since    1.0.0
     * @access   private
     * @param    array     $user_data    The sanitized user data.
     * @param    string    $user_type    The type of user (normal or vendor).
     * @return   true|WP_Error           True if valid, WP_Error otherwise.
     */
    private function validate_user_data($user_data, $user_type = 'normal') {
        // Check for required fields
        $required_fields = array('email', 'password');
        
        if ($user_type === 'vendor') {
            // Add vendor-specific required fields
            $required_fields = array_merge($required_fields, array('first_name', 'last_name', 'phone'));
        }
        
        foreach ($required_fields as $field) {
            if (empty($user_data[$field])) {
                return new WP_Error(
                    'missing_required_field',
                    sprintf(__('Required field %s is missing.', 'wp-alp'), $field)
                );
            }
        }
        
        // Validate email
        if (!$this->security->validate_email($user_data['email'])) {
            return new WP_Error('invalid_email', __('Invalid email address.', 'wp-alp'));
        }
        
        // Check if email already exists
        if (email_exists($user_data['email'])) {
            return new WP_Error('email_exists', __('This email is already registered.', 'wp-alp'));
        }
        
        // Check if username already exists (if provided)
        if (!empty($user_data['username']) && username_exists($user_data['username'])) {
            return new WP_Error('username_exists', __('This username is already taken.', 'wp-alp'));
        }
        
        // Validate password strength
        if (strlen($user_data['password']) < 8) {
            return new WP_Error('weak_password', __('Password must be at least 8 characters long.', 'wp-alp'));
        }
        
        // Additional validation for vendors
        if ($user_type === 'vendor') {
            // Validate phone (simple check for now)
            if (!preg_match('/^\+?[0-9]{10,15}$/', $user_data['phone'])) {
                return new WP_Error('invalid_phone', __('Invalid phone number.', 'wp-alp'));
            }
        }
        
        return true;
    }
}