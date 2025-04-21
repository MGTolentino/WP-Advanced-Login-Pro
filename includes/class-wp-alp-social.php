<?php
/**
 * Maneja la autenticación con proveedores sociales.
 */
class WP_ALP_Social {

    /**
     * Configuración de los proveedores sociales.
     */
    private $providers;

    /**
     * Constructor de la clase.
     */
    public function __construct() {
        $this->providers = array(
            'google' => array(
                'client_id' => get_option('wp_alp_google_client_id', ''),
                'client_secret' => get_option('wp_alp_google_client_secret', ''),
                'redirect_uri' => home_url('/wp-json/wp-alp/v1/auth/google'),
            ),
            'facebook' => array(
                'app_id' => get_option('wp_alp_facebook_app_id', ''),
                'app_secret' => get_option('wp_alp_facebook_app_secret', ''),
                'redirect_uri' => home_url('/wp-json/wp-alp/v1/auth/facebook'),
            ),
            'apple' => array(
                'client_id' => get_option('wp_alp_apple_client_id', ''),
                'team_id' => get_option('wp_alp_apple_team_id', ''),
                'key_id' => get_option('wp_alp_apple_key_id', ''),
                'private_key' => get_option('wp_alp_apple_private_key', ''),
                'redirect_uri' => home_url('/wp-json/wp-alp/v1/auth/apple'),
            ),
        );

        // Registrar endpoints REST API
        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }

    /**
     * Registra rutas REST API para autenticación social.
     */
    public function register_rest_routes() {
        register_rest_route('wp-alp/v1', '/auth/google', array(
            'methods' => 'GET',
            'callback' => array($this, 'handle_google_callback'),
            'permission_callback' => '__return_true', // Asegura acceso público
        ));
    
        register_rest_route('wp-alp/v1', '/auth/facebook', array(
            'methods' => 'GET',
            'callback' => array($this, 'handle_facebook_callback'),
            'permission_callback' => '__return_true',
        ));
    
        register_rest_route('wp-alp/v1', '/auth/apple', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_apple_callback'),
            'permission_callback' => '__return_true',
        ));
    }

    /**
     * Obtiene la URL de autorización para Google.
     *
     * @return string La URL de autorización.
     */
    public function get_google_auth_url() {
        $client_id = $this->providers['google']['client_id'];
        $redirect_uri = $this->providers['google']['redirect_uri'];
        
        $params = array(
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'state' => wp_create_nonce('google-auth'),
        );
        
        return 'https://accounts.google.com/o/oauth2/auth?' . http_build_query($params);
    }

    /**
     * Obtiene la URL de autorización para Facebook.
     *
     * @return string La URL de autorización.
     */
    public function get_facebook_auth_url() {
        $app_id = $this->providers['facebook']['app_id'];
        $redirect_uri = $this->providers['facebook']['redirect_uri'];
        
        $params = array(
            'client_id' => $app_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => 'email',
            'state' => wp_create_nonce('facebook-auth'),
        );
        
        return 'https://www.facebook.com/v13.0/dialog/oauth?' . http_build_query($params);
    }

    /**
     * Maneja la respuesta de autenticación de Google.
     *
     * @param WP_REST_Request $request La solicitud REST.
     * @return WP_REST_Response La respuesta REST.
     */
    public function handle_google_callback($request) {
        // Agregar log para diagnosticar
        error_log('Google Auth Callback recibido: ' . json_encode($_GET));
        
        $code = $request->get_param('code');
        $state = $request->get_param('state');
        
        if (empty($code)) {
            error_log('Google Auth: Código faltante');
            return new WP_REST_Response(array('error' => 'Falta código de autorización'), 400);
        }
        
        // Intercambiar código por token
        $token_data = $this->get_google_token($code);
        if (isset($token_data['error'])) {
            error_log('Google Auth: Error al obtener token: ' . json_encode($token_data));
            return new WP_REST_Response($token_data, 400);
        }
        
        // Obtener datos del usuario
        $user_data = $this->get_google_user_data($token_data['access_token']);
        if (isset($user_data['error'])) {
            error_log('Google Auth: Error al obtener datos de usuario: ' . json_encode($user_data));
            return new WP_REST_Response($user_data, 400);
        }
        
        // Iniciar sesión o crear usuario
        $result = $this->login_or_create_user($user_data, 'google');
        
        // Redirigir con un script que cierre la ventana y notifique a la ventana principal
        $redirect_url = $result['redirect_url'];
        $needs_profile = $result['needs_profile'] ? 'true' : 'false';
        $user_id = $result['user_id'];
        
        // Devolver HTML que se comunicará con la ventana principal
        echo '<html><head><script>
        window.opener.postMessage({
            provider: "google",
            success: true,
            needs_profile: ' . $needs_profile . ',
            user_id: ' . $user_id . ',
            redirect_url: "' . esc_js($redirect_url) . '"
        }, window.location.origin);
        window.close();
        </script></head><body>Autenticación exitosa. Cerrando ventana...</body></html>';
        exit;
    }

    /**
     * Obtiene el token de acceso de Google.
     *
     * @param string $code El código de autorización.
     * @return array Los datos del token.
     */
    private function get_google_token($code) {
        $client_id = $this->providers['google']['client_id'];
        $client_secret = $this->providers['google']['client_secret'];
        $redirect_uri = $this->providers['google']['redirect_uri'];
        
        $params = array(
            'code' => $code,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $redirect_uri,
            'grant_type' => 'authorization_code',
        );
        
        error_log('Google Auth: Solicitando token con parámetros: ' . json_encode($params));
        
        $response = wp_remote_post('https://oauth2.googleapis.com/token', array(
            'body' => $params,
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            error_log('Google Auth: Error en solicitud: ' . $response->get_error_message());
            return array('error' => $response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        error_log('Google Auth: Respuesta de token: ' . json_encode($body));
        
        if (isset($body['error'])) {
            return array('error' => $body['error_description'] ?? $body['error']);
        }
        
        return $body;
    }

    /**
     * Obtiene los datos del usuario de Google.
     *
     * @param string $access_token El token de acceso.
     * @return array Los datos del usuario.
     */
    private function get_google_user_data($access_token) {
        $response = wp_remote_get('https://www.googleapis.com/oauth2/v3/userinfo', array(
            'headers' => array('Authorization' => 'Bearer ' . $access_token),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return array('error' => $response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['error'])) {
            return array('error' => $body['error_description']);
        }
        
        return array(
            'email' => $body['email'],
            'first_name' => $body['given_name'],
            'last_name' => $body['family_name'],
            'id' => $body['sub'],
            'picture' => $body['picture'],
            'verified' => $body['email_verified'],
        );
    }

    /**
     * Maneja la respuesta de autenticación de Facebook.
     *
     * @param WP_REST_Request $request La solicitud REST.
     * @return WP_REST_Response La respuesta REST.
     */
    public function handle_facebook_callback($request) {
        $code = $request->get_param('code');
        $state = $request->get_param('state');
        
        // Verificar nonce
        if (!wp_verify_nonce($state, 'facebook-auth')) {
            return new WP_REST_Response(array('error' => 'Invalid state'), 400);
        }
        
        // Intercambiar código por token
        $token_data = $this->get_facebook_token($code);
        if (isset($token_data['error'])) {
            return new WP_REST_Response($token_data, 400);
        }
        
        // Obtener datos del usuario
        $user_data = $this->get_facebook_user_data($token_data['access_token']);
        if (isset($user_data['error'])) {
            return new WP_REST_Response($user_data, 400);
        }
        
        // Iniciar sesión o crear usuario
        $result = $this->login_or_create_user($user_data, 'facebook');
        
        // Redirigir al usuario
        wp_redirect($result['redirect_url']);
        exit;
    }

    /**
     * Obtiene el token de acceso de Facebook.
     *
     * @param string $code El código de autorización.
     * @return array Los datos del token.
     */
    private function get_facebook_token($code) {
        $app_id = $this->providers['facebook']['app_id'];
        $app_secret = $this->providers['facebook']['app_secret'];
        $redirect_uri = $this->providers['facebook']['redirect_uri'];
        
        $params = array(
            'client_id' => $app_id,
            'client_secret' => $app_secret,
            'redirect_uri' => $redirect_uri,
            'code' => $code,
        );
        
        $response = wp_remote_get('https://graph.facebook.com/v18.0/oauth/access_token?' . http_build_query($params), array(            
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return array('error' => $response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['error'])) {
            return array('error' => $body['error']['message']);
        }
        
        return $body;
    }

    /**
     * Obtiene los datos del usuario de Facebook.
     *
     * @param string $access_token El token de acceso.
     * @return array Los datos del usuario.
     */
    private function get_facebook_user_data($access_token) {
        $fields = 'id,email,first_name,last_name,picture';
        $response = wp_remote_get('https://graph.facebook.com/v18.0/me?fields=' . $fields . '&access_token=' . $access_token, array(            
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return array('error' => $response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['error'])) {
            return array('error' => $body['error']['message']);
        }
        
        return array(
            'email' => isset($body['email']) ? $body['email'] : '',
            'first_name' => $body['first_name'],
            'last_name' => $body['last_name'],
            'id' => $body['id'],
            'picture' => $body['picture']['data']['url'],
            'verified' => true, // Facebook ya verifica los emails
        );
    }

    /**
     * Maneja la respuesta de autenticación de Apple.
     *
     * @param WP_REST_Request $request La solicitud REST.
     * @return WP_REST_Response La respuesta REST.
     */
    public function handle_apple_callback($request) {
        $code = $request->get_param('code');
        $id_token = $request->get_param('id_token');
        $user_data = $request->get_param('user');
        
        if (empty($id_token)) {
            return new WP_REST_Response(array('error' => 'ID token is required'), 400);
        }
        
        // Decodificar el token JWT (sin verificar la firma para simplificar)
        $token_parts = explode('.', $id_token);
        $payload = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', $token_parts[1]))), true);
        
        // Obtener datos del usuario
        $user_info = array(
            'email' => $payload['email'],
            'id' => $payload['sub'],
            'verified' => $payload['email_verified'] === 'true',
        );
        
        // Si tenemos datos adicionales del usuario
        if (!empty($user_data) && is_string($user_data)) {
            $user_data = json_decode($user_data, true);
            if (isset($user_data['name'])) {
                $user_info['first_name'] = $user_data['name']['firstName'] ?? '';
                $user_info['last_name'] = $user_data['name']['lastName'] ?? '';
            }
        }
        
        // Iniciar sesión o crear usuario
        $result = $this->login_or_create_user($user_info, 'apple');
        
        // Redirigir al usuario
        wp_redirect($result['redirect_url']);
        exit;
    }

    /**
     * Inicia sesión o crea un usuario basado en los datos del proveedor social.
     *
     * @param array $user_data Datos del usuario del proveedor social.
     * @param string $provider El proveedor social utilizado.
     * @return array Resultado de la operación.
     */
    public function login_or_create_user($user_data, $provider) {
        // Verificar si el email existe
        $user = get_user_by('email', $user_data['email']);
        
        if ($user) {
            // El usuario existe, iniciar sesión
            wp_set_auth_cookie($user->ID, true);
            
            // Verificar si el usuario es subscriber y necesita completar perfil
            $user_type = get_user_meta($user->ID, 'wp_alp_user_type', true);
            $profile_status = get_user_meta($user->ID, 'wp_alp_profile_status', true);
            
            if ($user->roles[0] === 'subscriber' && ($user_type === '' || $profile_status === 'incomplete')) {
                // El usuario necesita completar su perfil
                return array(
                    'success' => true,
                    'user_id' => $user->ID,
                    'needs_profile' => true,
                    'redirect_url' => add_query_arg(array(
                        'wp_alp_action' => 'complete_profile',
                        'wp_alp_user_id' => $user->ID,
                        'wp_alp_token' => wp_create_nonce('wp_alp_complete_profile_' . $user->ID),
                    ), home_url()),
                );
            } else {
                // Usuario completo, redirigir a la página principal
                return array(
                    'success' => true,
                    'user_id' => $user->ID,
                    'needs_profile' => false,
                    'redirect_url' => home_url(),
                );
            }
        } else {
            // El usuario no existe, crear uno nuevo
            $username = sanitize_user($user_data['email'], true);
            
            // Asegurarse de que el username sea único
            $count = 1;
            $original_username = $username;
            while (username_exists($username)) {
                $username = $original_username . $count;
                $count++;
            }
            
            // Crear el usuario
            $user_id = wp_insert_user(array(
                'user_login' => $username,
                'user_email' => $user_data['email'],
                'user_pass' => wp_generate_password(),
                'first_name' => $user_data['first_name'] ?? '',
                'last_name' => $user_data['last_name'] ?? '',
                'display_name' => $user_data['first_name'] ?? '',
                'role' => 'subscriber',
            ));
            
            if (is_wp_error($user_id)) {
                return array(
                    'success' => false,
                    'error' => $user_id->get_error_message(),
                    'redirect_url' => home_url(),
                );
            }
            
            // Guardar metadatos adicionales
            update_user_meta($user_id, 'wp_alp_provider', $provider);
            update_user_meta($user_id, 'wp_alp_social_id', $user_data['id']);
            update_user_meta($user_id, 'wp_alp_profile_status', 'incomplete');
            update_user_meta($user_id, 'wp_alp_email_verified', $user_data['verified'] ? 'yes' : 'no');
            
            // Iniciar sesión
            wp_set_auth_cookie($user_id, true);
            
            // Redirigir para completar perfil
            return array(
                'success' => true,
                'user_id' => $user_id,
                'needs_profile' => true,
                'redirect_url' => add_query_arg(array(
                    'wp_alp_action' => 'complete_profile',
                    'wp_alp_user_id' => $user_id,
                    'wp_alp_token' => wp_create_nonce('wp_alp_complete_profile_' . $user_id),
                ), home_url()),
            );
        }
    }

   /**
 * Procesa los datos sociales recibidos via AJAX.
 *
 * @param array $data Los datos de autenticación social.
 * @return array Resultado del proceso.
 */
public function process_social_data_ajax($data) {
    $provider = sanitize_text_field($data['provider']);
    $token = sanitize_text_field($data['token']);
    
    // Registrar datos para debugging
    error_log('WP_ALP: Procesando datos sociales para proveedor: ' . $provider);
    
    switch ($provider) {
        case 'google':
            // Para Google, estamos recibiendo un JWT (token ID) en lugar de un token de acceso
            if (strpos($token, '.') !== false) {
                // Es un token JWT, decodificar la parte del payload
                $parts = explode('.', $token);
                if (count($parts) >= 2) {
                    $payload = json_decode($this->base64url_decode($parts[1]), true);
                    
                    if ($payload && isset($payload['email']) && isset($payload['sub'])) {
                        $user_data = array(
                            'email' => $payload['email'],
                            'id' => $payload['sub'],
                            'verified' => isset($payload['email_verified']) ? $payload['email_verified'] : true,
                            'first_name' => isset($payload['given_name']) ? $payload['given_name'] : '',
                            'last_name' => isset($payload['family_name']) ? $payload['family_name'] : '',
                            'picture' => isset($payload['picture']) ? $payload['picture'] : '',
                        );
                        
                        error_log('WP_ALP: Datos de usuario de Google JWT: ' . json_encode($user_data));
                    } else {
                        return array(
                            'success' => false,
                            'message' => __('Token JWT de Google inválido o incompleto', 'wp-alp'),
                        );
                    }
                } else {
                    return array(
                        'success' => false,
                        'message' => __('Formato de token JWT inválido', 'wp-alp'),
                    );
                }
            } else {
                // Usar el método tradicional para tokens de acceso
                $user_data = $this->get_google_user_data($token);
            }
            break;
        case 'facebook':
            $user_data = $this->get_facebook_user_data($token);
            break;
        case 'apple':
            // Decodificar token JWT
            $token_parts = explode('.', $token);
            if (count($token_parts) >= 2) {
                $payload = json_decode($this->base64url_decode($token_parts[1]), true);
                
                $user_data = array(
                    'email' => $payload['email'],
                    'id' => $payload['sub'],
                    'verified' => $payload['email_verified'] === 'true',
                    'first_name' => $data['first_name'] ?? '',
                    'last_name' => $data['last_name'] ?? '',
                );
            } else {
                return array(
                    'success' => false,
                    'message' => __('Token de Apple inválido', 'wp-alp'),
                );
            }
            break;
        default:
            return array(
                'success' => false,
                'message' => __('Proveedor no soportado', 'wp-alp'),
            );
    }
    
    if (isset($user_data['error'])) {
        error_log('WP_ALP: Error obteniendo datos de usuario: ' . json_encode($user_data));
        return array(
            'success' => false,
            'message' => $user_data['error'],
        );
    }
    
    $result = $this->login_or_create_user($user_data, $provider);
    
    // Asegurarnos de que el resultado incluya el user_id
    if ($result['success'] && !isset($result['user_id'])) {
        error_log('WP_ALP: Login exitoso pero falta user_id en el resultado');
    }
    
    return array(
        'success' => $result['success'],
        'message' => $result['success'] ? __('Autenticación exitosa', 'wp-alp') : $result['error'],
        'needs_profile' => $result['needs_profile'] ?? false,
        'redirect_url' => $result['redirect_url'],
        'user_id' => $result['user_id'] ?? 0,
    );
}

/**
 * Decodifica una cadena base64url (compatible con JWT)
 *
 * @param string $data La cadena codificada
 * @return string La cadena decodificada
 */
private function base64url_decode($data) {
    return base64_decode(str_replace(array('-', '_'), array('+', '/'), $data));
}
}