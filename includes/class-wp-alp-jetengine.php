<?php
/**
 * Maneja la integración con JetEngine.
 *
 * Esta clase contiene métodos para interactuar con las
 * colecciones personalizadas de JetEngine.
 */
class WP_ALP_JetEngine {

    /**
     * Nombre de la colección de leads.
     */
    private $leads_collection = 'leads';

    /**
     * Nombre de la colección de eventos.
     */
    private $events_collection = 'eventos';

    /**
     * Constructor de la clase.
     */
    public function __construct() {
        // Verificar que JetEngine esté activo
        if (!class_exists('Jet_Engine')) {
            add_action('admin_notices', array($this, 'display_jetengine_required_notice'));
            return;
        }
    }

    /**
     * Muestra un aviso si JetEngine no está instalado.
     */
    public function display_jetengine_required_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('El plugin WP Advanced Login Pro requiere que JetEngine esté instalado y activado.', 'wp-alp'); ?></p>
        </div>
        <?php
    }

    /**
     * Crea un nuevo lead en la colección.
     *
     * @param array $lead_data Datos del lead.
     * @return int|false ID del lead creado o false en caso de error.
     */
    public function create_lead($lead_data) {
        if (!class_exists('Jet_Engine_CPT_Manager')) {
            return false;
        }
        
        // Obtener instancia de CCT
        $cct_instance = jet_engine()->modules->get_module('custom-content-types')->instance;
        
        if (!$cct_instance) {
            return false;
        }
        
        // Obtener el model manager
        $model_manager = $cct_instance->manager;
        
        // Buscar la colección de leads
        $lead_cct = false;
        foreach ($model_manager->get_content_types() as $content_type) {
            if ($content_type->get_arg('slug') === $this->leads_collection) {
                $lead_cct = $content_type;
                break;
            }
        }
        
        if (!$lead_cct) {
            return false;
        }
        
        // Añadir timestamp de creación
        $lead_data['cct_created'] = current_time('timestamp');
        
        // Insertar el lead
        try {
            $inserted = $lead_cct->db->insert($lead_data);
            
            if ($inserted) {
                return $lead_cct->db->get_last_id();
            }
        } catch (Exception $e) {
            error_log('Error al crear lead: ' . $e->getMessage());
        }
        
        return false;
    }

    /**
     * Crea un nuevo evento en la colección.
     *
     * @param array $event_data Datos del evento.
     * @return int|false ID del evento creado o false en caso de error.
     */
    public function create_event($event_data) {
        if (!class_exists('Jet_Engine_CPT_Manager')) {
            return false;
        }
        
        // Obtener instancia de CCT
        $cct_instance = jet_engine()->modules->get_module('custom-content-types')->instance;
        
        if (!$cct_instance) {
            return false;
        }
        
        // Obtener el model manager
        $model_manager = $cct_instance->manager;
        
        // Buscar la colección de eventos
        $event_cct = false;
        foreach ($model_manager->get_content_types() as $content_type) {
            if ($content_type->get_arg('slug') === $this->events_collection) {
                $event_cct = $content_type;
                break;
            }
        }
        
        if (!$event_cct) {
            return false;
        }
        
        // Añadir timestamp de creación
        $event_data['cct_created'] = current_time('timestamp');
        
        // Insertar el evento
        try {
            $inserted = $event_cct->db->insert($event_data);
            
            if ($inserted) {
                return $event_cct->db->get_last_id();
            }
        } catch (Exception $e) {
            error_log('Error al crear evento: ' . $e->getMessage());
        }
        
        return false;
    }

    /**
     * Busca un lead por número de teléfono.
     *
     * @param string $phone Número de teléfono.
     * @return array|false Datos del lead o false si no se encuentra.
     */
    public function find_lead_by_phone($phone) {
        if (!class_exists('Jet_Engine_CPT_Manager')) {
            return false;
        }
        
        // Obtener instancia de CCT
        $cct_instance = jet_engine()->modules->get_module('custom-content-types')->instance;
        
        if (!$cct_instance) {
            return false;
        }
        
        // Obtener el model manager
        $model_manager = $cct_instance->manager;
        
        // Buscar la colección de leads
        $lead_cct = false;
        foreach ($model_manager->get_content_types() as $content_type) {
            if ($content_type->get_arg('slug') === $this->leads_collection) {
                $lead_cct = $content_type;
                break;
            }
        }
        
        if (!$lead_cct) {
            return false;
        }
        
        // Buscar por teléfono
        try {
            $leads = $lead_cct->db->query(array(
                'CELULAR' => $phone,
            ));
            
            if (!empty($leads)) {
                return $leads[0];
            }
        } catch (Exception $e) {
            error_log('Error al buscar lead por teléfono: ' . $e->getMessage());
        }
        
        return false;
    }

    /**
     * Busca un lead por ID de usuario de WordPress.
     *
     * @param int $user_id ID del usuario.
     * @return array|false Datos del lead o false si no se encuentra.
     */
    public function find_lead_by_user_id($user_id) {
        if (!class_exists('Jet_Engine_CPT_Manager')) {
            return false;
        }
        
        // Obtener instancia de CCT
        $cct_instance = jet_engine()->modules->get_module('custom-content-types')->instance;
        
        if (!$cct_instance) {
            return false;
        }
        
        // Obtener el model manager
        $model_manager = $cct_instance->manager;
        
        // Buscar la colección de leads
        $lead_cct = false;
        foreach ($model_manager->get_content_types() as $content_type) {
            if ($content_type->get_arg('slug') === $this->leads_collection) {
                $lead_cct = $content_type;
                break;
            }
        }
        
        if (!$lead_cct) {
            return false;
        }
        
        // Buscar por ID de usuario
        try {
            $leads = $lead_cct->db->query(array(
                '_user_id' => $user_id,
            ));
            
            if (!empty($leads)) {
                return $leads[0];
            }
        } catch (Exception $e) {
            error_log('Error al buscar lead por ID de usuario: ' . $e->getMessage());
        }
        
        return false;
    }

    /**
     * Busca un lead por correo electrónico.
     *
     * @param string $email Correo electrónico.
     * @return array|false Datos del lead o false si no se encuentra.
     */
    public function find_lead_by_email($email) {
        if (!class_exists('Jet_Engine_CPT_Manager')) {
            return false;
        }
        
        // Obtener instancia de CCT
        $cct_instance = jet_engine()->modules->get_module('custom-content-types')->instance;
        
        if (!$cct_instance) {
            return false;
        }
        
        // Obtener el model manager
        $model_manager = $cct_instance->manager;
        
        // Buscar la colección de leads
        $lead_cct = false;
        foreach ($model_manager->get_content_types() as $content_type) {
            if ($content_type->get_arg('slug') === $this->leads_collection) {
                $lead_cct = $content_type;
                break;
            }
        }
        
        if (!$lead_cct) {
            return false;
        }
        
        // Buscar por email
        try {
            $leads = $lead_cct->db->query(array(
                'EMAIL' => $email,
            ));
            
            if (!empty($leads)) {
                return $leads[0];
            }
        } catch (Exception $e) {
            error_log('Error al buscar lead por email: ' . $e->getMessage());
        }
        
        return false;
    }

    /**
     * Actualiza un lead existente.
     *
     * @param int $lead_id ID del lead.
     * @param array $lead_data Datos a actualizar.
     * @return bool Resultado de la actualización.
     */
    public function update_lead($lead_id, $lead_data) {
        if (!class_exists('Jet_Engine_CPT_Manager')) {
            return false;
        }
        
        // Obtener instancia de CCT
        $cct_instance = jet_engine()->modules->get_module('custom-content-types')->instance;
        
        if (!$cct_instance) {
            return false;
        }
        
        // Obtener el model manager
        $model_manager = $cct_instance->manager;
        
        // Buscar la colección de leads
        $lead_cct = false;
        foreach ($model_manager->get_content_types() as $content_type) {
            if ($content_type->get_arg('slug') === $this->leads_collection) {
                $lead_cct = $content_type;
                break;
            }
        }
        
        if (!$lead_cct) {
            return false;
        }
        
        // Actualizar el lead
        try {
            return $lead_cct->db->update($lead_data, array('_ID' => $lead_id));
        } catch (Exception $e) {
            error_log('Error al actualizar lead: ' . $e->getMessage());
        }
        
        return false;
    }

    /**
     * Actualiza un evento existente.
     *
     * @param int $event_id ID del evento.
     * @param array $event_data Datos a actualizar.
     * @return bool Resultado de la actualización.
     */
    public function update_event($event_id, $event_data) {
        if (!class_exists('Jet_Engine_CPT_Manager')) {
            return false;
        }
        
        // Obtener instancia de CCT
        $cct_instance = jet_engine()->modules->get_module('custom-content-types')->instance;
        
        if (!$cct_instance) {
            return false;
        }
        
        // Obtener el model manager
        $model_manager = $cct_instance->manager;
        
        // Buscar la colección de eventos
        $event_cct = false;
        foreach ($model_manager->get_content_types() as $content_type) {
            if ($content_type->get_arg('slug') === $this->events_collection) {
                $event_cct = $content_type;
                break;
            }
        }
        
        if (!$event_cct) {
            return false;
        }
        
        // Actualizar el evento
        try {
            return $event_cct->db->update($event_data, array('_ID' => $event_id));
        } catch (Exception $e) {
            error_log('Error al actualizar evento: ' . $e->getMessage());
        }
        
        return false;
    }
}