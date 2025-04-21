<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post" action="options.php">
        <?php
        settings_fields('wp_alp_security');
        do_settings_sections('wp_alp_security');
        ?>
        
        <h2><?php _e('Protección contra Fuerza Bruta', 'wp-alp'); ?></h2>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Intentos máximos de inicio de sesión', 'wp-alp'); ?></th>
                <td>
                    <input type="number" name="wp_alp_max_login_attempts" min="1" max="20" value="<?php echo esc_attr(get_option('wp_alp_max_login_attempts', 5)); ?>" />
                    <p class="description"><?php _e('Número de intentos fallidos de inicio de sesión antes de bloquear al usuario.', 'wp-alp'); ?></p>
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php _e('Tiempo de bloqueo (segundos)', 'wp-alp'); ?></th>
                <td>
                    <input type="number" name="wp_alp_lockout_time" min="60" value="<?php echo esc_attr(get_option('wp_alp_lockout_time', 300)); ?>" />
                    <p class="description"><?php _e('Tiempo en segundos que el usuario estará bloqueado después de demasiados intentos fallidos.', 'wp-alp'); ?></p>
                </td>
            </tr>
        </table>
        
        <h2><?php _e('reCAPTCHA', 'wp-alp'); ?></h2>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Habilitar reCAPTCHA', 'wp-alp'); ?></th>
                <td>
                    <input type="checkbox" name="wp_alp_enable_captcha" value="1" <?php checked(get_option('wp_alp_enable_captcha', false), true); ?> />
                    <p class="description"><?php _e('Añadir Google reCAPTCHA v2 a los formularios de registro e inicio de sesión.', 'wp-alp'); ?></p>
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php _e('Clave del sitio', 'wp-alp'); ?></th>
                <td>
                    <input type="text" name="wp_alp_recaptcha_site_key" class="regular-text" value="<?php echo esc_attr(get_option('wp_alp_recaptcha_site_key', '')); ?>" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php _e('Clave secreta', 'wp-alp'); ?></th>
                <td>
                    <input type="password" name="wp_alp_recaptcha_secret_key" class="regular-text" value="<?php echo esc_attr(get_option('wp_alp_recaptcha_secret_key', '')); ?>" />
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
    
    <hr>
    
    <h2><?php _e('Configuración de reCAPTCHA', 'wp-alp'); ?></h2>
    <p><?php _e('Para obtener las claves de reCAPTCHA:', 'wp-alp'); ?></p>
    <ol>
        <li><?php _e('Ve a <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA Admin</a>', 'wp-alp'); ?></li>
        <li><?php _e('Registra un nuevo sitio', 'wp-alp'); ?></li>
        <li><?php _e('Selecciona reCAPTCHA v2 (Casilla "No soy un robot")', 'wp-alp'); ?></li>
        <li><?php _e('Añade el dominio de tu sitio', 'wp-alp'); ?></li>
        <li><?php _e('Acepta los términos de servicio y haz clic en "Registrar"', 'wp-alp'); ?></li>
        <li><?php _e('Copia la clave del sitio y la clave secreta en los campos de arriba', 'wp-alp'); ?></li>
    </ol>
    
    <h2><?php _e('Consejos de Seguridad', 'wp-alp'); ?></h2>
    <ul>
        <li><?php _e('Usa siempre HTTPS para tu sitio web.', 'wp-alp'); ?></li>
        <li><?php _e('Mantén WordPress y todos los plugins actualizados.', 'wp-alp'); ?></li>
        <li><?php _e('Usa contraseñas fuertes para todas las cuentas.', 'wp-alp'); ?></li>
        <li><?php _e('Considera usar un plugin de seguridad adicional como Wordfence o Sucuri.', 'wp-alp'); ?></li>
    </ul>
</div>