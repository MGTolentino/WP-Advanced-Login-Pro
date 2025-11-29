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
    <div class="wpalp-auth-modal">
        <div class="wpalp-modal-header">
            <h3 class="wpalp-modal-title"><?php _e('Inicia sesión o regístrate', 'wp-alp'); ?></h3>
            <button type="button" class="wpalp-btn-close" aria-label="Cerrar">
                <span class="wpalp-icon-close"></span>
            </button>
        </div>
        
        <div class="wpalp-modal-body">
            <h2 class="wpalp-welcome-title"><?php _e('¡Te damos la bienvenida!', 'wp-alp'); ?></h2>
            
            <div class="wpalp-field-group">
                <label for="wpalp-identifier" class="wpalp-field-label"><?php _e('Correo electrónico o teléfono', 'wp-alp'); ?></label>
                <input type="text" id="wpalp-identifier" name="identifier" class="wpalp-field-input" placeholder="<?php _e('Correo electrónico o teléfono', 'wp-alp'); ?>" />
                <div class="wpalp-field-info"><?php _e('Puedes usar tu correo o número de teléfono para continuar', 'wp-alp'); ?></div>
            </div>
            
            <div class="wpalp-field-group">
                <button type="button" class="wpalp-btn-primary" id="wpalp-continue-btn">
                    <?php _e('Continuar', 'wp-alp'); ?>
                </button>
            </div>
            
            <div class="wpalp-auth-divider">
                <span class="wpalp-divider-text"><?php _e('o', 'wp-alp'); ?></span>
            </div>
            
            <div class="wpalp-social-buttons">
                <?php if (!empty(get_option('wp_alp_google_client_id', ''))) : ?>
                <button type="button" class="wpalp-btn-social" id="wpalp-google-btn">
                    <span class="wpalp-social-icon wpalp-icon-google"></span>
                    <span><?php _e('Continuar con Google', 'wp-alp'); ?></span>
                </button>
                <?php endif; ?>
                
                <?php if (!empty(get_option('wp_alp_facebook_app_id', ''))) : ?>
                <button type="button" class="wpalp-btn-social" id="wpalp-facebook-btn">
                    <span class="wpalp-social-icon wpalp-icon-facebook"></span>
                    <span><?php _e('Continuar con Facebook', 'wp-alp'); ?></span>
                </button>
                <?php endif; ?>
                
                <?php if (!empty(get_option('wp_alp_apple_client_id', ''))) : ?>
                <button type="button" class="wpalp-btn-social" id="wpalp-apple-btn">
                    <span class="wpalp-social-icon wpalp-icon-apple"></span>
                    <span><?php _e('Continuar con Apple', 'wp-alp'); ?></span>
                </button>
                <?php endif; ?>
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
        <div class="wpalp-auth-modal">
            <div class="wpalp-modal-header">
                <button type="button" class="wpalp-btn-back" id="wpalp-back-to-initial" aria-label="Volver">
                    <span class="wpalp-icon-back"></span>
                </button>
                <h3 class="wpalp-modal-title"><?php _e('Inicia sesión', 'wp-alp'); ?></h3>
            </div>
            
            <div class="wpalp-modal-body">
                <div class="wpalp-field-group wpalp-field-disabled">
                    <label for="wpalp-login-email" class="wpalp-field-label"><?php _e('Correo electrónico', 'wp-alp'); ?></label>
                    <input type="email" id="wpalp-login-email" name="email" class="wpalp-field-input" value="<?php echo esc_attr($email); ?>" readonly />
                </div>
                
                <div class="wpalp-field-group">
                    <label for="wpalp-login-password" class="wpalp-field-label"><?php _e('Contraseña', 'wp-alp'); ?></label>
                    <div class="wpalp-password-wrapper">
                        <input type="password" id="wpalp-login-password" name="password" class="wpalp-field-input" placeholder="<?php _e('Contraseña', 'wp-alp'); ?>" />
                        <button type="button" class="wpalp-password-toggle" data-target="wpalp-login-password">
                            <span class="wpalp-show-text"><?php _e('Mostrar', 'wp-alp'); ?></span>
                            <span class="wpalp-hide-text" style="display: none;"><?php _e('Ocultar', 'wp-alp'); ?></span>
                        </button>
                    </div>
                </div>
                
                <div class="wpalp-forgot-password">
                    <a href="#" class="wpalp-link" id="wpalp-forgot-password-link"><?php _e('¿Olvidaste tu contraseña?', 'wp-alp'); ?></a>
                </div>
                
                <div class="wpalp-field-group">
                    <button type="button" class="wpalp-btn-primary" id="wpalp-login-btn">
                        <?php _e('Iniciar sesión', 'wp-alp'); ?>
                    </button>
                </div>
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
        <div class="wpalp-auth-modal">
            <div class="wpalp-modal-header">
                <button type="button" class="wpalp-btn-back" id="wpalp-back-to-initial" aria-label="Volver">
                    <span class="wpalp-icon-back"></span>
                </button>
                <h3 class="wpalp-modal-title"><?php _e('Termina de registrarte', 'wp-alp'); ?></h3>
            </div>
            
            <div class="wpalp-modal-body">
                <div class="wpalp-form-section">
                    <h3 class="wpalp-section-title"><?php _e('Información personal', 'wp-alp'); ?></h3>
                    
                    <div class="wpalp-field-group">
                        <label for="wpalp-register-first-name" class="wpalp-field-label"><?php _e('Nombre', 'wp-alp'); ?></label>
                        <input type="text" id="wpalp-register-first-name" name="first_name" class="wpalp-field-input" placeholder="<?php _e('Nombre', 'wp-alp'); ?>" />
                    </div>
                    
                    <div class="wpalp-field-group">
                        <label for="wpalp-register-last-name" class="wpalp-field-label"><?php _e('Apellidos', 'wp-alp'); ?></label>
                        <input type="text" id="wpalp-register-last-name" name="last_name" class="wpalp-field-input" placeholder="<?php _e('Apellidos', 'wp-alp'); ?>" />
                    </div>
                    
                    <div class="wpalp-field-group">
                        <label for="wpalp-register-birthdate" class="wpalp-field-label"><?php _e('Fecha de nacimiento', 'wp-alp'); ?></label>
                        <input type="date" id="wpalp-register-birthdate" name="birthdate" class="wpalp-field-input" />
                        <div class="wpalp-help-text"><?php _e('Debes tener al menos 18 años para registrarte.', 'wp-alp'); ?></div>
                    </div>
                </div>
                
                <div class="wpalp-form-section">
                    <h3 class="wpalp-section-title"><?php _e('Información de contacto', 'wp-alp'); ?></h3>
                    
                    <div class="wpalp-field-group wpalp-field-disabled">
                        <label for="wpalp-register-email" class="wpalp-field-label"><?php _e('Correo electrónico', 'wp-alp'); ?></label>
                        <input type="email" id="wpalp-register-email" name="email" class="wpalp-field-input" value="<?php echo esc_attr($email); ?>" readonly />
                    </div>
                    
                    <div class="wpalp-field-group">
                        <label for="wpalp-register-phone" class="wpalp-field-label"><?php _e('Número de teléfono', 'wp-alp'); ?></label>
                        <input type="tel" id="wpalp-register-phone" name="phone" class="wpalp-field-input" placeholder="<?php _e('Número de teléfono', 'wp-alp'); ?>" />
                    </div>
                </div>
                
                <div class="wpalp-form-section">
                    <h3 class="wpalp-section-title"><?php _e('Contraseña', 'wp-alp'); ?></h3>
                    
                    <div class="wpalp-field-group">
                        <label for="wpalp-register-password" class="wpalp-field-label"><?php _e('Contraseña', 'wp-alp'); ?></label>
                        <div class="wpalp-password-wrapper">
                            <input type="password" id="wpalp-register-password" name="password" class="wpalp-field-input" placeholder="<?php _e('Contraseña', 'wp-alp'); ?>" />
                            <button type="button" class="wpalp-password-toggle" data-target="wpalp-register-password">
                                <span class="wpalp-show-text"><?php _e('Mostrar', 'wp-alp'); ?></span>
                                <span class="wpalp-hide-text" style="display: none;"><?php _e('Ocultar', 'wp-alp'); ?></span>
                            </button>
                        </div>
                        <div class="wpalp-help-text"><?php _e('Mínimo 6 caracteres.', 'wp-alp'); ?></div>
                    </div>
                </div>
                
                <div class="wpalp-form-section">
                    <h3 class="wpalp-section-title"><?php _e('Información del evento (opcional)', 'wp-alp'); ?></h3>
                    
                    <div class="wpalp-field-group">
                        <label for="wpalp-event-type" class="wpalp-field-label"><?php _e('Tipo de evento', 'wp-alp'); ?></label>
                        <select id="wpalp-event-type" name="event_type" class="wpalp-field-select">
                            <option value=""><?php _e('Selecciona un tipo de evento', 'wp-alp'); ?></option>
                            <option value="Bodas"><?php _e('Bodas', 'wp-alp'); ?></option>
                            <option value="Cumpleaños"><?php _e('Cumpleaños', 'wp-alp'); ?></option>
                            <option value="Corporativo"><?php _e('Corporativo', 'wp-alp'); ?></option>
                            <option value="Graduación"><?php _e('Graduación', 'wp-alp'); ?></option>
                            <option value="Otro"><?php _e('Otro', 'wp-alp'); ?></option>
                        </select>
                    </div>
                    
                    <div class="wpalp-field-group">
                        <label for="wpalp-event-date" class="wpalp-field-label"><?php _e('Fecha del evento', 'wp-alp'); ?></label>
                        <input type="date" id="wpalp-event-date" name="event_date" class="wpalp-field-input" />
                    </div>
                    
                    <div class="wpalp-field-group">
                        <label for="wpalp-event-address" class="wpalp-field-label"><?php _e('Dirección del evento', 'wp-alp'); ?></label>
                        <input type="text" id="wpalp-event-address" name="event_address" class="wpalp-field-input" placeholder="<?php _e('Dirección del evento', 'wp-alp'); ?>" />
                    </div>
                    
                    <div class="wpalp-field-group">
                        <label for="wpalp-event-guests" class="wpalp-field-label"><?php _e('Número de invitados', 'wp-alp'); ?></label>
                        <input type="number" id="wpalp-event-guests" name="guests" class="wpalp-field-input" min="1" />
                    </div>
                </div>
                
                <div class="wpalp-terms">
                    <?php _e('Al hacer clic en "Registrarse", aceptas nuestros', 'wp-alp'); ?> 
                    <a href="#" class="wpalp-link"><?php _e('Términos de servicio', 'wp-alp'); ?></a> 
                    <?php _e('y', 'wp-alp'); ?> 
                    <a href="#" class="wpalp-link"><?php _e('Política de privacidad', 'wp-alp'); ?></a>.
                </div>
                
                <div class="wpalp-field-group">
                    <button type="button" class="wpalp-btn-primary" id="wpalp-register-btn">
                        <?php _e('Registrarse', 'wp-alp'); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Genera el contenedor del modal para el footer
     *
     * @return string HTML del contenedor del modal.
     */
    public static function get_modal_container() {
        ob_start();
        ?>
        <div id="wpalp-modal-wrapper" class="wpalp-modal-wrapper" style="display: none;">
            <div id="wpalp-modal-content" class="wpalp-modal-content">
                <!-- El contenido se cargará aquí dinámicamente -->
            </div>
            
            <div id="wpalp-modal-loader" class="wpalp-loading-overlay" style="display: none;">
                <div class="wpalp-spinner"></div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}