<?php
/**
 * Handles JetEngine integration.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/includes
 */

class WP_ALP_JetEngine {

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
        // Initialize JetEngine integration
    }

    /**
     * Check if JetEngine is active and available.
     *
     * @since    1.0.0
     * @return   bool    True if JetEngine is active, false otherwise.
     */
    public function is_jetengine_active() {
        return function_exists('jet_engine');
    }

    /**
     * Create a lead entry in JetEngine custom content type.
     *
     * @since    1.0.0
     * @param    array    $lead_data    The lead data to insert.
     * @return   int|WP_Error           Lead ID on success, WP_Error on failure.
     */
    public function create_lead($lead_data) {
        if (!$this->is_jetengine_active()) {
            return new WP_Error('jetengine_inactive', __('JetEngine is required for lead management.', 'wp-alp'));
        }

        // Sanitize and validate lead data
        $lead_data = $this->sanitize_lead_data($lead_data);

        // Ensure required fields are present
        $required_fields = array('Nombre', 'Apellido', 'EMAIL');
        foreach ($required_fields as $field) {
            if (empty($lead_data[$field])) {
                return new WP_Error(
                    'missing_required_field',
                    sprintf(__('Required field %s is missing.', 'wp-alp'), $field)
                );
            }
        }

        // Set default status if not provided
        if (empty($lead_data['status'])) {
            $lead_data['status'] = 'nuevo';
        }

        // Insert lead into JetEngine CCT
        $lead_id = jet_engine()->db->insert_item($lead_data, 'leads');

        if (!$lead_id) {
            return new WP_Error('lead_creation_failed', __('Failed to create lead in JetEngine.', 'wp-alp'));
        }

        return $lead_id;
    }

    /**
     * Create an event entry in JetEngine custom content type.
     *
     * @since    1.0.0
     * @param    array    $event_data    The event data to insert.
     * @return   int|WP_Error            Event ID on success, WP_Error on failure.
     */
    public function create_event($event_data) {
        if (!$this->is_jetengine_active()) {
            return new WP_Error('jetengine_inactive', __('JetEngine is required for event management.', 'wp-alp'));
        }

        // Sanitize and validate event data
        $event_data = $this->sanitize_event_data($event_data);

        // Ensure required fields are present
        $required_fields = array('Tipo de Evento', 'inserted_cct_leads');
        foreach ($required_fields as $field) {
            if (empty($event_data[$field])) {
                return new WP_Error(
                    'missing_required_field',
                    sprintf(__('Required field %s is missing.', 'wp-alp'), $field)
                );
            }
        }

        // Set default status if not provided
        if (empty($event_data['status'])) {
            $event_data['status'] = 'nuevo';
        }

        // Insert event into JetEngine CCT
        $event_id = jet_engine()->db->insert_item($event_data, 'eventos');

        if (!$event_id) {
            return new WP_Error('event_creation_failed', __('Failed to create event in JetEngine.', 'wp-alp'));
        }

        return $event_id;
    }

    /**
     * Get lead by ID from JetEngine CCT.
     *
     * @since    1.0.0
     * @param    int      $lead_id    The lead ID.
     * @return   array|false          Lead data if found, false otherwise.
     */
    public function get_lead($lead_id) {
        if (!$this->is_jetengine_active()) {
            return false;
        }

        return jet_engine()->db->get_item($lead_id, 'leads');
    }

    /**
     * Get lead by user ID from JetEngine CCT.
     *
     * @since    1.0.0
     * @param    int      $user_id    The WordPress user ID.
     * @return   array|false          Lead data if found, false otherwise.
     */
    public function get_lead_by_user_id($user_id) {
        if (!$this->is_jetengine_active()) {
            return false;
        }

        $args = array(
            'meta_query' => array(
                array(
                    'key'   => '_user_id',
                    'value' => $user_id,
                ),
            ),
        );

        $leads = jet_engine()->db->get_items('leads', $args);

        if (empty($leads)) {
            return false;
        }

        return $leads[0];
    }

    /**
     * Get lead by email from JetEngine CCT.
     *
     * @since    1.0.0
     * @param    string   $email    The email address.
     * @return   array|false        Lead data if found, false otherwise.
     */
    public function get_lead_by_email($email) {
        if (!$this->is_jetengine_active()) {
            return false;
        }

        $args = array(
            'meta_query' => array(
                array(
                    'key'   => 'EMAIL',
                    'value' => $email,
                ),
            ),
        );

        $leads = jet_engine()->db->get_items('leads', $args);

        if (empty($leads)) {
            return false;
        }

        return $leads[0];
    }

    /**
     * Update lead in JetEngine CCT.
     *
     * @since    1.0.0
     * @param    int      $lead_id      The lead ID.
     * @param    array    $lead_data    The lead data to update.
     * @return   bool|WP_Error          True on success, WP_Error on failure.
     */
    public function update_lead($lead_id, $lead_data) {
        if (!$this->is_jetengine_active()) {
            return new WP_Error('jetengine_inactive', __('JetEngine is required for lead management.', 'wp-alp'));
        }

        // Sanitize lead data
        $lead_data = $this->sanitize_lead_data($lead_data);

        // Update lead in JetEngine CCT
        $result = jet_engine()->db->update_item($lead_id, $lead_data, 'leads');

        if (!$result) {
            return new WP_Error('lead_update_failed', __('Failed to update lead in JetEngine.', 'wp-alp'));
        }

        return true;
    }

    /**
     * Get events by lead ID from JetEngine CCT.
     *
     * @since    1.0.0
     * @param    int      $lead_id    The lead ID.
     * @return   array                Array of events.
     */
    public function get_events_by_lead($lead_id) {
        if (!$this->is_jetengine_active()) {
            return array();
        }

        $args = array(
            'meta_query' => array(
                array(
                    'key'   => 'inserted_cct_leads',
                    'value' => $lead_id,
                ),
            ),
        );

        return jet_engine()->db->get_items('eventos', $args);
    }

    /**
     * Sanitize lead data for JetEngine CCT.
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
            'Nombre'      => 'sanitize_text_field',
            'Apellido'    => 'sanitize_text_field',
            'EMAIL'       => 'sanitize_email',
            'CELULAR'     => 'sanitize_text_field',
            'status'      => 'sanitize_text_field',
            '_user_id'    => 'intval',
        );

        foreach ($fields as $field => $sanitizer) {
            if (isset($lead_data[$field])) {
                $sanitized[$field] = $sanitizer($lead_data[$field]);
            }
        }

        // Preserve any additional fields not in the map
        foreach ($lead_data as $key => $value) {
            if (!isset($sanitized[$key])) {
                $sanitized[$key] = sanitize_text_field($value);
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize event data for JetEngine CCT.
     *
     * @since    1.0.0
     * @access   private
     * @param    array    $event_data    The raw event data.
     * @return   array                   Sanitized event data.
     */
    private function sanitize_event_data($event_data) {
        $sanitized = array();

        // Map fields to sanitization methods
        $fields = array(
            'Tipo de Evento'           => 'sanitize_text_field',
            'Fecha de evento'          => 'sanitize_text_field',
            'Direccion de evento'      => 'sanitize_textarea_field',
            'Cantidad de asistentes'   => 'intval',
            'Detalles adicionales'     => 'sanitize_textarea_field',
            'Categoria_padre'          => 'sanitize_text_field',
            'Lead-Servicio-de-Interes' => 'sanitize_text_field',
            'inserted_cct_leads'       => 'intval',
            'status'                   => 'sanitize_text_field',
        );

        foreach ($fields as $field => $sanitizer) {
            if (isset($event_data[$field])) {
                $sanitized[$field] = $sanitizer($event_data[$field]);
            }
        }

        // Preserve any additional fields not in the map
        foreach ($event_data as $key => $value) {
            if (!isset($sanitized[$key])) {
                $sanitized[$key] = sanitize_text_field($value);
            }
        }

        return $sanitized;
    }

    /**
     * Link WordPress user to JetEngine lead.
     *
     * @since    1.0.0
     * @param    int      $user_id    The WordPress user ID.
     * @param    int      $lead_id    The JetEngine lead ID.
     * @return   bool                 True on success, false on failure.
     */
    public function link_user_to_lead($user_id, $lead_id) {
        if (!$this->is_jetengine_active()) {
            return false;
        }

        // Update lead with user ID
        $result = jet_engine()->db->update_item($lead_id, array('_user_id' => $user_id), 'leads');

        if ($result) {
            // Also store the lead ID in user meta for quick reference
            update_user_meta($user_id, 'wp_alp_lead_id', $lead_id);
            return true;
        }

        return false;
    }

    /**
     * Register REST API endpoints for JetEngine integration.
     *
     * @since    1.0.0
     */
    public function register_rest_endpoints() {
        if (!$this->is_jetengine_active()) {
            return;
        }

        register_rest_route('wp-alp/v1', '/leads', array(
            'methods'  => 'GET',
            'callback' => array($this, 'api_get_leads'),
            'permission_callback' => function() {
                return current_user_can('edit_posts');
            }
        ));

        register_rest_route('wp-alp/v1', '/lead/(?P<id>\d+)', array(
            'methods'  => 'GET',
            'callback' => array($this, 'api_get_lead'),
            'permission_callback' => function() {
                return current_user_can('edit_posts');
            }
        ));

        register_rest_route('wp-alp/v1', '/lead', array(
            'methods'  => 'POST',
            'callback' => array($this, 'api_create_lead'),
            'permission_callback' => function() {
                return current_user_can('edit_posts');
            }
        ));

        register_rest_route('wp-alp/v1', '/lead/(?P<id>\d+)', array(
            'methods'  => 'PUT',
            'callback' => array($this, 'api_update_lead'),
            'permission_callback' => function() {
                return current_user_can('edit_posts');
            }
        ));

        register_rest_route('wp-alp/v1', '/events/lead/(?P<lead_id>\d+)', array(
            'methods'  => 'GET',
            'callback' => array($this, 'api_get_events_by_lead'),
            'permission_callback' => function() {
                return current_user_can('edit_posts');
            }
        ));
    }

    /**
     * API callback for getting all leads.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    The request object.
     * @return   WP_REST_Response               The response object.
     */
    public function api_get_leads($request) {
        $args = array();

        // Handle pagination
        $per_page = $request->get_param('per_page') ? intval($request->get_param('per_page')) : 10;
        $page = $request->get_param('page') ? intval($request->get_param('page')) : 1;

        $args['limit'] = $per_page;
        $args['offset'] = ($page - 1) * $per_page;

        // Handle search
        if ($request->get_param('search')) {
            $args['search'] = sanitize_text_field($request->get_param('search'));
        }

        // Handle status filter
        if ($request->get_param('status')) {
            $args['meta_query'] = array(
                array(
                    'key'   => 'status',
                    'value' => sanitize_text_field($request->get_param('status')),
                ),
            );
        }

        $leads = jet_engine()->db->get_items('leads', $args);
        $total = jet_engine()->db->count_items('leads', $args);

        return new WP_REST_Response(array(
            'leads' => $leads,
            'total' => $total,
            'pages' => ceil($total / $per_page),
        ), 200);
    }

    /**
     * API callback for getting a specific lead.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    The request object.
     * @return   WP_REST_Response               The response object.
     */
    public function api_get_lead($request) {
        $lead_id = intval($request->get_param('id'));
        $lead = jet_engine()->db->get_item($lead_id, 'leads');

        if (!$lead) {
            return new WP_REST_Response(array('error' => 'Lead not found'), 404);
        }

        return new WP_REST_Response($lead, 200);
    }

    /**
     * API callback for creating a lead.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    The request object.
     * @return   WP_REST_Response               The response object.
     */
    public function api_create_lead($request) {
        $lead_data = $this->sanitize_lead_data($request->get_params());
        $lead_id = $this->create_lead($lead_data);

        if (is_wp_error($lead_id)) {
            return new WP_REST_Response(array('error' => $lead_id->get_error_message()), 400);
        }

        return new WP_REST_Response(array(
            'id' => $lead_id,
            'message' => __('Lead created successfully', 'wp-alp'),
        ), 201);
    }

    /**
     * API callback for updating a lead.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    The request object.
     * @return   WP_REST_Response               The response object.
     */
    public function api_update_lead($request) {
        $lead_id = intval($request->get_param('id'));
        $lead_data = $this->sanitize_lead_data($request->get_params());
        $result = $this->update_lead($lead_id, $lead_data);

        if (is_wp_error($result)) {
            return new WP_REST_Response(array('error' => $result->get_error_message()), 400);
        }

        return new WP_REST_Response(array(
            'id' => $lead_id,
            'message' => __('Lead updated successfully', 'wp-alp'),
        ), 200);
    }

    /**
     * API callback for getting events by lead.
     *
     * @since    1.0.0
     * @param    WP_REST_Request    $request    The request object.
     * @return   WP_REST_Response               The response object.
     */
    public function api_get_events_by_lead($request) {
        $lead_id = intval($request->get_param('lead_id'));
        $events = $this->get_events_by_lead($lead_id);

        return new WP_REST_Response(array(
            'events' => $events,
            'total' => count($events),
        ), 200);
    }
}