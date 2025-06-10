<?php
/**
 * Maneja los formularios del plugin.
 *
 * Esta clase contiene métodos para generar y procesar
 * los distintos formularios utilizados en el plugin.
 */
class WP_ALP_Forms {

/**
 * Genera el HTML para el formulario inicial (entrada de email/teléfono).
 *
 * @return string HTML del formulario.
 */
public static function get_initial_form() {
    ob_start();
    ?>
    <div class="wp-alp-form-container wp-alp-initial-form">
        <h3 class="wp-alp-modal-title"><?php _e('Inicia sesión o regístrate', 'wp-alp'); ?></h3>
        
        <div class="wp-alp-modal-content">
            <h2><?php _e('¡Te damos la bienvenida!', 'wp-alp'); ?></h2>
            
            <div class="wp-alp-input-group">
                <label for="wp-alp-identifier"><?php _e('Correo electrónico o teléfono', 'wp-alp'); ?></label>
                <input type="text" id="wp-alp-identifier" name="identifier" class="wp-alp-input" placeholder="<?php _e('Correo electrónico o teléfono', 'wp-alp'); ?>" />
                <div class="wp-alp-input-info"><?php _e('Puedes usar tu correo o número de teléfono para continuar', 'wp-alp'); ?></div>
            </div>
            
            <div class="wp-alp-button-group">
                <button type="button" class="wp-alp-button wp-alp-primary-button" id="wp-alp-continue-btn">
                    <?php _e('Continuar', 'wp-alp'); ?>
                </button>
            </div>
            
            <div class="wp-alp-divider">
                <span><?php _e('o', 'wp-alp'); ?></span>
            </div>
            
            <div class="wp-alp-social-login">
                <?php if (!empty(get_option('wp_alp_google_client_id', ''))) : ?>
                <button type="button" class="wp-alp-social-button" id="wp-alp-google-btn">
                    <span class="wp-alp-social-icon google-icon"></span>
                    <span><?php _e('Continuar con Google', 'wp-alp'); ?></span>
                </button>
                <?php endif; ?>
                
                <?php if (!empty(get_option('wp_alp_facebook_app_id', ''))) : ?>
                <button type="button" class="wp-alp-social-button" id="wp-alp-facebook-btn">
                    <span class="wp-alp-social-icon facebook-icon"></span>
                    <span><?php _e('Continuar con Facebook', 'wp-alp'); ?></span>
                </button>
                <?php endif; ?>
                
                <?php if (!empty(get_option('wp_alp_apple_client_id', ''))) : ?>
                <button type="button" class="wp-alp-social-button" id="wp-alp-apple-btn">
                    <span class="wp-alp-social-icon apple-icon"></span>
                    <span><?php _e('Continuar con Apple', 'wp-alp'); ?></span>
                </button>
                <?php endif; ?>
                
                <!-- Botón de teléfono eliminado ya que la funcionalidad está integrada en el campo principal -->
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

    /**
     * Genera el HTML para el formulario de login.
     *
     * @param string $email Email del usuario.
     * @return string HTML del formulario.
     */
    public static function get_login_form($email) {
        ob_start();
        ?>
        <div class="wp-alp-form-container wp-alp-login-form">
            <button type="button" class="wp-alp-back-button" id="wp-alp-back-to-initial">
                <span class="wp-alp-back-icon"></span>
            </button>
            
            <h2><?php _e('Inicia sesión', 'wp-alp'); ?></h2>
            
            <div class="wp-alp-input-group wp-alp-disabled">
                <label for="wp-alp-login-email"><?php _e('Correo electrónico', 'wp-alp'); ?></label>
                <input type="email" id="wp-alp-login-email" name="email" class="wp-alp-input" value="<?php echo esc_attr($email); ?>" readonly />
            </div>
            
            <div class="wp-alp-input-group">
                <label for="wp-alp-login-password"><?php _e('Contraseña', 'wp-alp'); ?></label>
                <input type="password" id="wp-alp-login-password" name="password" class="wp-alp-input" placeholder="<?php _e('Contraseña', 'wp-alp'); ?>" />
                <button type="button" class="wp-alp-toggle-password" data-target="wp-alp-login-password">
                    <span class="wp-alp-show-text"><?php _e('Mostrar', 'wp-alp'); ?></span>
                    <span class="wp-alp-hide-text" style="display: none;"><?php _e('Ocultar', 'wp-alp'); ?></span>
                </button>
            </div>
            
            <div class="wp-alp-forgot-password">
                <a href="#" id="wp-alp-forgot-password-link"><?php _e('¿Olvidaste tu contraseña?', 'wp-alp'); ?></a>
            </div>
            
            <div class="wp-alp-button-group">
                <button type="button" class="wp-alp-button wp-alp-primary-button" id="wp-alp-login-btn">
                    <?php _e('Iniciar sesión', 'wp-alp'); ?>
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Genera el HTML para el formulario de registro.
     *
     * @param string $email Email del usuario.
     * @return string HTML del formulario.
     */
    public static function get_register_form($email) {
        ob_start();
        ?>
        <div class="wp-alp-form-container wp-alp-register-form">
            <button type="button" class="wp-alp-back-button" id="wp-alp-back-to-initial">
                <span class="wp-alp-back-icon"></span>
            </button>
            
            <h2><?php _e('Termina de registrarte', 'wp-alp'); ?></h2>
            
            <div class="wp-alp-user-info-section">
                <h3><?php _e('Nombre legal', 'wp-alp'); ?></h3>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-register-first-name"><?php _e('Nombre que aparece en tu identificación', 'wp-alp'); ?></label>
                    <input type="text" id="wp-alp-register-first-name" name="first_name" class="wp-alp-input" placeholder="<?php _e('Nombre', 'wp-alp'); ?>" />
                </div>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-register-last-name"><?php _e('Apellidos que aparecen en tu identificación', 'wp-alp'); ?></label>
                    <input type="text" id="wp-alp-register-last-name" name="last_name" class="wp-alp-input" placeholder="<?php _e('Apellidos', 'wp-alp'); ?>" />
                </div>
                
                <p class="wp-alp-help-text">
                    <?php _e('Asegúrate de que coincida con el nombre que aparece en tu identificación oficial. Si usas otro distinto, puedes agregar el nombre que prefieras.', 'wp-alp'); ?>
                </p>
            </div>
            
            <div class="wp-alp-user-info-section">
                <h3><?php _e('Fecha de nacimiento', 'wp-alp'); ?></h3>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-register-birthdate"><?php _e('Fecha de nacimiento', 'wp-alp'); ?></label>
                    <input type="date" id="wp-alp-register-birthdate" name="birthdate" class="wp-alp-input" />
                </div>
                
                <p class="wp-alp-help-text">
                    <?php _e('Debes tener al menos 18 años para registrarte. No compartiremos tu fecha de nacimiento con ningún otro usuario.', 'wp-alp'); ?>
                </p>
            </div>
            
            <div class="wp-alp-user-info-section">
                <h3><?php _e('Información de contacto', 'wp-alp'); ?></h3>
                
                <div class="wp-alp-input-group wp-alp-disabled">
                    <label for="wp-alp-register-email"><?php _e('Correo electrónico', 'wp-alp'); ?></label>
                    <input type="email" id="wp-alp-register-email" name="email" class="wp-alp-input" value="<?php echo esc_attr($email); ?>" readonly />
                </div>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-register-phone"><?php _e('Número de teléfono', 'wp-alp'); ?></label>
                    <input type="tel" id="wp-alp-register-phone" name="phone" class="wp-alp-input" placeholder="<?php _e('Número de teléfono', 'wp-alp'); ?>" />
                </div>
                
                <p class="wp-alp-help-text">
                    <?php _e('Te enviaremos las confirmaciones y los recibos por correo electrónico.', 'wp-alp'); ?>
                </p>
            </div>
            
            <div class="wp-alp-user-info-section">
                <h3><?php _e('Contraseña', 'wp-alp'); ?></h3>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-register-password"><?php _e('Contraseña', 'wp-alp'); ?></label>
                    <input type="password" id="wp-alp-register-password" name="password" class="wp-alp-input" placeholder="<?php _e('Contraseña', 'wp-alp'); ?>" />
                    <button type="button" class="wp-alp-toggle-password" data-target="wp-alp-register-password">
                        <span class="wp-alp-show-text"><?php _e('Mostrar', 'wp-alp'); ?></span>
                        <span class="wp-alp-hide-text" style="display: none;"><?php _e('Ocultar', 'wp-alp'); ?></span>
                    </button>
                </div>
            </div>
            
            <div class="wp-alp-event-info-section">
                <h3><?php _e('Información del evento', 'wp-alp'); ?></h3>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-event-type"><?php _e('Tipo de evento', 'wp-alp'); ?></label>
                    <select id="wp-alp-event-type" name="event_type" class="wp-alp-input">
                        <option value=""><?php _e('Selecciona un tipo de evento', 'wp-alp'); ?></option>
                        <option value="Bodas"><?php _e('Bodas', 'wp-alp'); ?></option>
                        <option value="Cumpleaños"><?php _e('Cumpleaños', 'wp-alp'); ?></option>
                        <option value="Corporativo"><?php _e('Corporativo', 'wp-alp'); ?></option>
                        <option value="Graduación"><?php _e('Graduación', 'wp-alp'); ?></option>
                        <option value="Otro"><?php _e('Otro', 'wp-alp'); ?></option>
                    </select>
                </div>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-event-date"><?php _e('Fecha del evento', 'wp-alp'); ?></label>
                    <input type="date" id="wp-alp-event-date" name="event_date" class="wp-alp-input" />
                </div>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-event-address"><?php _e('Dirección del evento', 'wp-alp'); ?></label>
                    <input type="text" id="wp-alp-event-address" name="event_address" class="wp-alp-input" placeholder="<?php _e('Dirección del evento', 'wp-alp'); ?>" />
                </div>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-event-guests"><?php _e('Número de invitados', 'wp-alp'); ?></label>
                    <input type="number" id="wp-alp-event-guests" name="guests" class="wp-alp-input" min="1" />
                </div>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-event-details"><?php _e('Detalles adicionales', 'wp-alp'); ?></label>
                    <textarea id="wp-alp-event-details" name="details" class="wp-alp-input" placeholder="<?php _e('¿Tienes alguna solicitud especial o requisito adicional para el evento?', 'wp-alp'); ?>"></textarea>
                </div>
            </div>
            
            <div class="wp-alp-terms-section">
                <p class="wp-alp-terms-text">
                    <?php _e('Al seleccionar Aceptar y continuar, acepto los', 'wp-alp'); ?>
                    <a href="#" target="_blank"><?php _e('Términos del servicio', 'wp-alp'); ?></a>,
                    <a href="#" target="_blank"><?php _e('Términos de Pago del Servicio', 'wp-alp'); ?></a>
                    <?php _e('y la', 'wp-alp'); ?>
                    <a href="#" target="_blank"><?php _e('Política contra la discriminación', 'wp-alp'); ?></a>
                    <?php _e('de nuestra plataforma, así como su', 'wp-alp'); ?>
                    <a href="#" target="_blank"><?php _e('Política de privacidad', 'wp-alp'); ?></a>.
                </p>
            </div>
            
            <div class="wp-alp-button-group">
                <button type="button" class="wp-alp-button wp-alp-primary-button" id="wp-alp-register-btn">
                    <?php _e('Aceptar y continuar', 'wp-alp'); ?>
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Genera el HTML para el formulario de completar perfil.
     *
     * @param int $user_id ID del usuario.
     * @return string HTML del formulario.
     */
    public static function get_profile_completion_form($user_id) {
        $user = get_user_by('ID', $user_id);
        
        if (!$user) {
            return '';
        }
        
        ob_start();
        ?>
        <div class="wp-alp-form-container wp-alp-profile-form">
            <h2><?php _e('Completa tu perfil', 'wp-alp'); ?></h2>
            
            <div class="wp-alp-event-info-section">
                <h3><?php _e('Información del evento', 'wp-alp'); ?></h3>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-event-type"><?php _e('Tipo de evento', 'wp-alp'); ?></label>
                    <select id="wp-alp-event-type" name="event_type" class="wp-alp-input">
                        <option value=""><?php _e('Selecciona un tipo de evento', 'wp-alp'); ?></option>
                        <option value="Bodas"><?php _e('Bodas', 'wp-alp'); ?></option>
                        <option value="Cumpleaños"><?php _e('Cumpleaños', 'wp-alp'); ?></option>
                        <option value="Corporativo"><?php _e('Corporativo', 'wp-alp'); ?></option>
                        <option value="Graduación"><?php _e('Graduación', 'wp-alp'); ?></option>
                        <option value="Otro"><?php _e('Otro', 'wp-alp'); ?></option>
                    </select>
                </div>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-event-date"><?php _e('Fecha del evento', 'wp-alp'); ?></label>
                    <input type="date" id="wp-alp-event-date" name="event_date" class="wp-alp-input" />
                </div>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-event-address"><?php _e('Dirección del evento', 'wp-alp'); ?></label>
                    <input type="text" id="wp-alp-event-address" name="event_address" class="wp-alp-input" placeholder="<?php _e('Dirección del evento', 'wp-alp'); ?>" />
                </div>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-event-guests"><?php _e('Número de invitados', 'wp-alp'); ?></label>
                    <input type="number" id="wp-alp-event-guests" name="guests" class="wp-alp-input" min="1" />
                </div>
                
                <div class="wp-alp-input-group">
                    <label for="wp-alp-event-details"><?php _e('Detalles adicionales', 'wp-alp'); ?></label>
                    <textarea id="wp-alp-event-details" name="details" class="wp-alp-input" placeholder="<?php _e('¿Tienes alguna solicitud especial o requisito adicional para el evento?', 'wp-alp'); ?>"></textarea>
                </div>
                
                <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>" />
                <input type="hidden" name="email" value="<?php echo esc_attr($user->user_email); ?>" />
                <input type="hidden" name="first_name" value="<?php echo esc_attr($user->first_name); ?>" />
                <input type="hidden" name="last_name" value="<?php echo esc_attr($user->last_name); ?>" />
                <input type="hidden" name="phone" value="<?php echo esc_attr(get_user_meta($user_id, 'wp_alp_phone', true)); ?>" />
            </div>
            
            <div class="wp-alp-button-group">
                <button type="button" class="wp-alp-button wp-alp-primary-button" id="wp-alp-complete-profile-btn">
                    <?php _e('Completar perfil', 'wp-alp'); ?>
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Genera el HTML para el formulario de verificación de código.
     *
     * @param string $email Email del usuario.
     * @param int $user_id ID del usuario.
     * @return string HTML del formulario.
     */
    public static function get_verification_form($email, $user_id) {
        ob_start();
        ?>
        <div class="wp-alp-form-container wp-alp-verification-form">
            <button type="button" class="wp-alp-back-button" id="wp-alp-back-to-initial">
                <span class="wp-alp-back-icon"></span>
            </button>
            
            <h2><?php _e('Confirmar cuenta', 'wp-alp'); ?></h2>
            
            <h3><?php _e('Ingresa el código de verificación', 'wp-alp'); ?></h3>
            
            <p class="wp-alp-verification-text">
                <?php printf(__('Ingresa el código que te enviamos por correo electrónico a %s.', 'wp-alp'), '<strong>' . esc_html($email) . '</strong>'); ?>
            </p>
            
            <div class="wp-alp-verification-code-container">
                <input type="text" class="wp-alp-verification-digit" maxlength="1" data-index="0" autocomplete="off" />
                <input type="text" class="wp-alp-verification-digit" maxlength="1" data-index="1" autocomplete="off" />
                <input type="text" class="wp-alp-verification-digit" maxlength="1" data-index="2" autocomplete="off" />
                <input type="text" class="wp-alp-verification-digit" maxlength="1" data-index="3" autocomplete="off" />
                <input type="text" class="wp-alp-verification-digit" maxlength="1" data-index="4" autocomplete="off" />
                <input type="text" class="wp-alp-verification-digit" maxlength="1" data-index="5" autocomplete="off" />
                <input type="hidden" id="wp-alp-verification-code" name="verification_code" />
                <input type="hidden" id="wp-alp-verification-user-id" name="user_id" value="<?php echo esc_attr($user_id); ?>" />
            </div>
            
            <div class="wp-alp-resend-code">
                <p><?php _e('¿No recibiste ningún correo electrónico?', 'wp-alp'); ?> <a href="#" id="wp-alp-resend-code-link"><?php _e('Vuelve a intentarlo', 'wp-alp'); ?></a></p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Genera el HTML para el formulario de teléfono.
     *
     * @return string HTML del formulario.
     */
    public static function get_phone_form() {
        ob_start();
        ?>
        <div class="wp-alp-form-container wp-alp-phone-form">
            <button type="button" class="wp-alp-back-button" id="wp-alp-back-to-initial">
                <span class="wp-alp-back-icon"></span>
            </button>
            
            <h2><?php _e('Ingresa tu número de teléfono', 'wp-alp'); ?></h2>
            
            <div class="wp-alp-input-group">
                <label for="wp-alp-phone-number"><?php _e('Número de teléfono', 'wp-alp'); ?></label>
                <input type="tel" id="wp-alp-phone-number" name="phone" class="wp-alp-input" placeholder="<?php _e('Número de teléfono', 'wp-alp'); ?>" />
            </div>
            
            <div class="wp-alp-button-group">
                <button type="button" class="wp-alp-button wp-alp-primary-button" id="wp-alp-phone-continue-btn">
                    <?php _e('Continuar', 'wp-alp'); ?>
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Genera el HTML para el modal completo que contendrá todos los formularios.
     *
     * @return string HTML del modal.
     */
    public static function get_modal_container() {
        ob_start();
        ?>
        <div id="wp-alp-modal-overlay" class="wp-alp-modal-overlay" style="display: none;">
            <div id="wp-alp-modal-container" class="wp-alp-modal-container">
                <button type="button" id="wp-alp-close-modal" class="wp-alp-close-modal">
                    <span class="wp-alp-close-icon"></span>
                </button>
                
                <div id="wp-alp-modal-content" class="wp-alp-modal-content">
                    <!-- Aquí se cargarán dinámicamente los formularios -->
                    <?php echo self::get_initial_form(); ?>
                </div>
                
                <div id="wp-alp-modal-loader" class="wp-alp-modal-loader" style="display: none;">
                    <div class="wp-alp-spinner"></div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Procesa los datos del formulario de registro.
     *
     * @param array $data Datos del formulario.
     * @return array Resultado del procesamiento.
     */
    public static function process_register_form($data) {
        $user_manager = new WP_ALP_User_Manager();
        return $user_manager->register_user($data);
    }
    
    /**
     * Procesa los datos del formulario de login.
     *
     * @param string $email Email del usuario.
     * @param string $password Contraseña.
     * @return array Resultado del procesamiento.
     */
    public static function process_login_form($email, $password) {
        $user_manager = new WP_ALP_User_Manager();
        return $user_manager->login_user($email, $password);
    }
    
    /**
     * Procesa los datos del formulario de completar perfil.
     *
     * @param array $data Datos del formulario.
     * @return array Resultado del procesamiento.
     */
    public static function process_profile_form($data) {
        $user_manager = new WP_ALP_User_Manager();
        return $user_manager->complete_user_profile($data['user_id'], $data);
    }
    
    /**
     * Procesa el código de verificación.
     *
     * @param int $user_id ID del usuario.
     * @param string $code Código de verificación.
     * @return array Resultado del procesamiento.
     */
    public static function process_verification_code($user_id, $code) {
        $user_manager = new WP_ALP_User_Manager();
        return $user_manager->verify_email_code($user_id, $code);
    }
    
    /**
     * Solicita el reenvío del código de verificación.
     *
     * @param int $user_id ID del usuario.
     * @return array Resultado del procesamiento.
     */
    public static function process_resend_code($user_id) {
        $user_manager = new WP_ALP_User_Manager();
        return $user_manager->resend_verification_code($user_id);
    }
    
    /**
     * Verifica si un usuario existe.
     *
     * @param string $identifier Email o teléfono.
     * @return array Resultado de la verificación.
     */
    public static function process_check_user($identifier) {
        $user_manager = new WP_ALP_User_Manager();
        return $user_manager->check_user_exists($identifier);
    }
}