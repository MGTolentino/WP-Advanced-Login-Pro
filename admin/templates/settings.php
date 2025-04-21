<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post" action="options.php">
        <?php
        settings_fields('wp_alp_general');
        do_settings_sections('wp_alp_general');
        ?>
        
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Habilitar Login Social', 'wp-alp'); ?></th>
                <td>
                    <input type="checkbox" name="wp_alp_enable_social_login" value="1" <?php checked(get_option('wp_alp_enable_social_login', true), true); ?> />
                    <p class="description"><?php _e('Permitir a los usuarios iniciar sesión con sus cuentas de redes sociales.', 'wp-alp'); ?></p>
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php _e('Habilitar Login por Teléfono', 'wp-alp'); ?></th>
                <td>
                    <input type="checkbox" name="wp_alp_enable_phone_login" value="1" <?php checked(get_option('wp_alp_enable_phone_login', true), true); ?> />
                    <p class="description"><?php _e('Permitir a los usuarios iniciar sesión con su número de teléfono.', 'wp-alp'); ?></p>
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php _e('URL de Redirección después del Login', 'wp-alp'); ?></th>
                <td>
                    <input type="text" name="wp_alp_redirect_after_login" class="regular-text" value="<?php echo esc_attr(get_option('wp_alp_redirect_after_login', home_url())); ?>" />
                    <p class="description"><?php _e('URL a la que se redirigirá al usuario después de iniciar sesión.', 'wp-alp'); ?></p>
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php _e('Habilitar Verificación por Email', 'wp-alp'); ?></th>
                <td>
                    <input type="checkbox" name="wp_alp_enable_email_verification" value="1" <?php checked(get_option('wp_alp_enable_email_verification', true), true); ?> />
                    <p class="description"><?php _e('Requerir que los usuarios verifiquen su dirección de correo electrónico al registrarse.', 'wp-alp'); ?></p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
    
    <hr>
    
    <h2><?php _e('Instrucciones de Uso', 'wp-alp'); ?></h2>
    
    <h3><?php _e('Shortcode para Botón de Login', 'wp-alp'); ?></h3>
    <p><?php _e('Puedes usar el siguiente shortcode para mostrar un botón de inicio de sesión en cualquier página o post:', 'wp-alp'); ?></p>
    <code>[wp_alp_login_button text="Iniciar sesión" class="mi-clase-personalizada"]</code>
    
    <h3><?php _e('Integración con JetEngine', 'wp-alp'); ?></h3>
    <p><?php _e('El plugin almacena la información de usuarios y eventos en las siguientes colecciones de JetEngine:', 'wp-alp'); ?></p>
    
    <ul>
        <li><strong><?php _e('Colección "leads"', 'wp-alp'); ?></strong> - <?php _e('Almacena los datos del usuario como lead.', 'wp-alp'); ?></li>
        <li><strong><?php _e('Colección "eventos"', 'wp-alp'); ?></strong> - <?php _e('Almacena los datos del evento asociado al lead.', 'wp-alp'); ?></li>
    </ul>
    
    <p><?php _e('Asegúrate de que estas colecciones existan en JetEngine con los campos mencionados en la documentación.', 'wp-alp'); ?></p>
</div>