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
            <h2 class="wpalp-welcome-title"><?php _e('¡Te damos la bienvenida a Reservas Events!', 'wp-alp'); ?></h2>
            
            <div class="wpalp-field-group">
                <!-- Input principal - siempre visible -->
                <div class="wpalp-input-container">
                    <label for="wpalp-identifier" class="wpalp-field-label"><?php _e('Correo electrónico o teléfono', 'wp-alp'); ?></label>
                    <input type="text" id="wpalp-identifier" name="identifier" class="wpalp-field-input" placeholder="<?php _e('Correo electrónico o teléfono', 'wp-alp'); ?>" />
                    <div class="wpalp-field-info"><?php _e('Puedes usar tu correo o número de teléfono para continuar', 'wp-alp'); ?></div>
                </div>
                
                <!-- Selector de país - solo visible cuando se detecta teléfono -->
                <div class="wpalp-country-selector-container" id="wpalp-country-selector-container" style="display: none;">
                    <label class="wpalp-field-label wpalp-country-label"><?php _e('País/región', 'wp-alp'); ?></label>
                    <div class="wpalp-country-selector" id="wpalp-country-selector">
                        <div class="wpalp-country-display">
                            <span class="wpalp-country-flag">🇲🇽</span>
                            <span class="wpalp-country-name">México</span>
                            <span class="wpalp-country-dial">(+52)</span>
                            <span class="wpalp-country-arrow">▼</span>
                        </div>
                        <div class="wpalp-country-dropdown" id="wpalp-country-dropdown">
                            <div class="wpalp-country-search">
                                <input type="text" id="wpalp-country-search" placeholder="<?php _e('Buscar países...', 'wp-alp'); ?>" />
                            </div>
                            <div class="wpalp-country-list" id="wpalp-country-list">
                                <!-- Se llena dinámicamente con JavaScript -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="wpalp-phone-disclaimer">
                        <?php _e('Te vamos a confirmar el número por teléfono o mensaje de texto. Sujeto a tarifas estándar para mensajes y datos.', 'wp-alp'); ?> 
                        <a href="#" class="wpalp-link"><?php _e('Política de privacidad', 'wp-alp'); ?></a>
                    </div>
                </div>
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
                <button type="button" class="wpalp-btn-social" id="wpalp-google-btn">
                    <span class="wpalp-social-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                    </span>
                    <span><?php _e('Continuar con Google', 'wp-alp'); ?></span>
                </button>
                
                <button type="button" class="wpalp-btn-social" id="wpalp-facebook-btn">
                    <span class="wpalp-social-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877F2">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </span>
                    <span><?php _e('Continuar con Facebook', 'wp-alp'); ?></span>
                </button>
                
                <button type="button" class="wpalp-btn-social" id="wpalp-apple-btn">
                    <span class="wpalp-social-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                        </svg>
                    </span>
                    <span><?php _e('Continuar con Apple', 'wp-alp'); ?></span>
                </button>
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