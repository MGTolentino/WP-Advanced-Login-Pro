<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post" action="options.php">
        <?php
        settings_fields('wp_alp_social');
        do_settings_sections('wp_alp_social');
        ?>
        
        <h2><?php _e('Google Login', 'wp-alp'); ?></h2>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Client ID', 'wp-alp'); ?></th>
                <td>
                    <input type="text" name="wp_alp_google_client_id" class="regular-text" value="<?php echo esc_attr(get_option('wp_alp_google_client_id', '')); ?>" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php _e('Client Secret', 'wp-alp'); ?></th>
                <td>
                    <input type="password" name="wp_alp_google_client_secret" class="regular-text" value="<?php echo esc_attr(get_option('wp_alp_google_client_secret', '')); ?>" />
                </td>
            </tr>
        </table>
        
        <h2><?php _e('Facebook Login', 'wp-alp'); ?></h2>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('App ID', 'wp-alp'); ?></th>
                <td>
                    <input type="text" name="wp_alp_facebook_app_id" class="regular-text" value="<?php echo esc_attr(get_option('wp_alp_facebook_app_id', '')); ?>" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php _e('App Secret', 'wp-alp'); ?></th>
                <td>
                    <input type="password" name="wp_alp_facebook_app_secret" class="regular-text" value="<?php echo esc_attr(get_option('wp_alp_facebook_app_secret', '')); ?>" />
                </td>
            </tr>
        </table>
        
        <h2><?php _e('Apple Login', 'wp-alp'); ?></h2>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Client ID', 'wp-alp'); ?></th>
                <td>
                    <input type="text" name="wp_alp_apple_client_id" class="regular-text" value="<?php echo esc_attr(get_option('wp_alp_apple_client_id', '')); ?>" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php _e('Team ID', 'wp-alp'); ?></th>
                <td>
                    <input type="text" name="wp_alp_apple_team_id" class="regular-text" value="<?php echo esc_attr(get_option('wp_alp_apple_team_id', '')); ?>" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php _e('Key ID', 'wp-alp'); ?></th>
                <td>
                    <input type="text" name="wp_alp_apple_key_id" class="regular-text" value="<?php echo esc_attr(get_option('wp_alp_apple_key_id', '')); ?>" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php _e('Private Key', 'wp-alp'); ?></th>
                <td>
                    <textarea name="wp_alp_apple_private_key" class="large-text code" rows="10"><?php echo esc_textarea(get_option('wp_alp_apple_private_key', '')); ?></textarea>
                    <p class="description"><?php _e('Pega el contenido de tu archivo de clave privada .p8 aquí.', 'wp-alp'); ?></p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
    
    <hr>
    
    <h2><?php _e('Instrucciones de Configuración', 'wp-alp'); ?></h2>
    
    <h3><?php _e('Google Login', 'wp-alp'); ?></h3>
    <ol>
        <li><?php _e('Ve a la <a href="https://console.developers.google.com/" target="_blank">Google Developer Console</a>', 'wp-alp'); ?></li>
        <li><?php _e('Crea un nuevo proyecto o selecciona uno existente', 'wp-alp'); ?></li>
        <li><?php _e('Ve a "Credenciales" y crea unas credenciales OAuth 2.0', 'wp-alp'); ?></li>
        <li><?php _e('Configura las URIs de redirección autorizadas. Añade: ', 'wp-alp'); ?> <code><?php echo home_url('/wp-json/wp-alp/v1/auth/google'); ?></code></li>
        <li><?php _e('Copia el ID de cliente y el Secreto de cliente en los campos de arriba', 'wp-alp'); ?></li>
    </ol>
    
    <h3><?php _e('Facebook Login', 'wp-alp'); ?></h3>
    <ol>
        <li><?php _e('Ve a <a href="https://developers.facebook.com/apps/" target="_blank">Facebook for Developers</a>', 'wp-alp'); ?></li>
        <li><?php _e('Crea una nueva aplicación', 'wp-alp'); ?></li>
        <li><?php _e('Configura el producto "Inicio de sesión con Facebook"', 'wp-alp'); ?></li>
        <li><?php _e('En Configuración > Básica, encontrarás el ID de la aplicación y el secreto de la aplicación', 'wp-alp'); ?></li>
        <li><?php _e('Añade como URI de redirección OAuth válida: ', 'wp-alp'); ?> <code><?php echo home_url('/wp-json/wp-alp/v1/auth/facebook'); ?></code></li>
    </ol>
    
    <h3><?php _e('Apple Login', 'wp-alp'); ?></h3>
    <ol>
        <li><?php _e('Ve a <a href="https://developer.apple.com/account/resources/identifiers/list" target="_blank">Apple Developer Portal</a>', 'wp-alp'); ?></li>
        <li><?php _e('Registra un nuevo identificador de aplicación y habilita "Sign In with Apple"', 'wp-alp'); ?></li>
        <li><?php _e('Crea un identificador de servicio para web', 'wp-alp'); ?></li>
        <li><?php _e('Configura los dominios y la URL de retorno: ', 'wp-alp'); ?> <code><?php echo home_url('/wp-json/wp-alp/v1/auth/apple'); ?></code></li>
        <li><?php _e('Crea una clave privada para "Sign In with Apple"', 'wp-alp'); ?></li>
        <li><?php _e('Completa los campos de arriba con la información correspondiente', 'wp-alp'); ?></li>
    </ol>
</div>