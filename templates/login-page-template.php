<?php
/**
 * Template Name: Página de Login WP-ALP
 *
 * Template para mostrar la página de login/registro personalizada.
 */

// Si el usuario ya está logueado, redirigir a la página principal
if (is_user_logged_in() && !isset($_GET['redirect_to'])) {
    wp_redirect(home_url());
    exit;
}

get_header();
?>

<div class="wp-alp-login-page">
    <div class="wp-alp-login-container">
        <div class="wp-alp-login-form-container">
            <div class="wp-alp-login-logo">
                <?php 
                $logo_id = get_theme_mod('custom_logo');
                if ($logo_id) {
                    $logo_img = wp_get_attachment_image_src($logo_id, 'full');
                    if ($logo_img) {
                        echo '<img src="' . esc_url($logo_img[0]) . '" alt="' . get_bloginfo('name') . '">';
                    } else {
                        echo '<h1>' . get_bloginfo('name') . '</h1>';
                    }
                } else {
                    echo '<h1>' . get_bloginfo('name') . '</h1>';
                }
                ?>
            </div>
            
            <h1><?php _e('Inicia sesión o regístrate', 'wp-alp'); ?></h1>
            
            <div id="wp-alp-login-form-wrapper">
    <!-- El formulario se cargará aquí con AJAX -->
    <?php 
    // Usar la instancia de la clase en lugar de llamada estática
    $forms = new WP_ALP_Forms();
    echo $forms->get_initial_form();
    ?>
</div>
        </div>
        
        <div class="wp-alp-login-benefits">
            <h2><?php _e('Beneficios de registrarte', 'wp-alp'); ?></h2>
            
            <div class="wp-alp-benefits-list">
                <div class="wp-alp-benefit-item">
                    <div class="wp-alp-benefit-icon">
                        <span class="dashicons dashicons-yes"></span>
                    </div>
                    <div class="wp-alp-benefit-text">
                        <h3><?php _e('Guarda tus favoritos', 'wp-alp'); ?></h3>
                        <p><?php _e('Crea colecciones de servicios y productos que te gusten.', 'wp-alp'); ?></p>
                    </div>
                </div>
                
                <div class="wp-alp-benefit-item">
                    <div class="wp-alp-benefit-icon">
                        <span class="dashicons dashicons-calendar-alt"></span>
                    </div>
                    <div class="wp-alp-benefit-text">
                        <h3><?php _e('Organiza tu evento', 'wp-alp'); ?></h3>
                        <p><?php _e('Mantén un seguimiento de todos los detalles para tu evento especial.', 'wp-alp'); ?></p>
                    </div>
                </div>
                
                <div class="wp-alp-benefit-item">
                    <div class="wp-alp-benefit-icon">
                        <span class="dashicons dashicons-money-alt"></span>
                    </div>
                    <div class="wp-alp-benefit-text">
                        <h3><?php _e('Ofertas exclusivas', 'wp-alp'); ?></h3>
                        <p><?php _e('Accede a descuentos y promociones especiales solo para miembros.', 'wp-alp'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .wp-alp-login-page {
        background-color: #f7f7f7;
        padding: 60px 0;
        min-height: calc(100vh - 200px);
    }
    
    .wp-alp-login-container {
        display: flex;
        max-width: 1200px;
        margin: 0 auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        overflow: hidden;
    }
    
    .wp-alp-login-form-container {
        flex: 1;
        padding: 40px;
    }
    
    .wp-alp-login-logo {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .wp-alp-login-logo img {
        max-height: 80px;
        width: auto;
    }
    
    .wp-alp-login-form-container h1 {
        text-align: center;
        font-size: 26px;
        margin-bottom: 30px;
    }
    
    .wp-alp-login-benefits {
        flex: 1;
        background: #FF385C;
        color: white;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .wp-alp-login-benefits h2 {
        font-size: 24px;
        margin-bottom: 30px;
    }
    
    .wp-alp-benefits-list {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }
    
    .wp-alp-benefit-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }
    
    .wp-alp-benefit-icon {
        background: rgba(255,255,255,0.2);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .wp-alp-benefit-icon .dashicons {
        width: 24px;
        height: 24px;
        font-size: 24px;
    }
    
    .wp-alp-benefit-text h3 {
        margin: 0 0 5px;
        font-size: 18px;
    }
    
    .wp-alp-benefit-text p {
        margin: 0;
        font-size: 14px;
        opacity: 0.9;
    }
    
    @media (max-width: 992px) {
        .wp-alp-login-container {
            flex-direction: column;
            max-width: 600px;
        }
        
        .wp-alp-login-benefits {
            display: none;
        }
    }
    
    @media (max-width: 600px) {
        .wp-alp-login-page {
            padding: 0;
        }
        
        .wp-alp-login-container {
            border-radius: 0;
            box-shadow: none;
        }
        
        .wp-alp-login-form-container {
            padding: 20px;
        }
    }
</style>

<!-- Modal loader y contenedor (escondido por defecto) -->
<div id="wp-alp-modal-overlay" class="wp-alp-modal-overlay" style="display: none;">
    <div id="wp-alp-modal-container" class="wp-alp-modal-container">
        <button type="button" id="wp-alp-close-modal" class="wp-alp-close-modal">
            <span class="wp-alp-close-icon"></span>
        </button>
        
        <div id="wp-alp-modal-content" class="wp-alp-modal-content">
            <!-- Aquí se cargarán dinámicamente los formularios -->
        </div>
        
        <div id="wp-alp-modal-loader" class="wp-alp-modal-loader" style="display: none;">
            <div class="wp-alp-spinner"></div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        // Si hay un parámetro redirect_to, guardarlo
        var redirectUrl = '<?php echo isset($_GET['redirect_to']) ? esc_url($_GET['redirect_to']) : ''; ?>';
        
        // Asignar el redirectUrl al formulario
        if (redirectUrl) {
            $('<input>').attr({
                type: 'hidden',
                name: 'redirect_to',
                value: redirectUrl
            }).appendTo('#wp-alp-login-form-wrapper form');
        }
        
        // Agregar soporte para abrir el modal cuando se hace clic en el enlace de login
        $('[data-wp-alp-trigger="login"]').on('click', function(e) {
            e.preventDefault();
            $('#wp-alp-login-form-wrapper').show();
        });
    });
</script>

<?php
get_footer();