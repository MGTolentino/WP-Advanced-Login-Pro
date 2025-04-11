<?php
/**
 * Template Name: Pasos para Vendedores WP-ALP
 * 
 * Template para la página de pasos del proceso de convertirse en vendedor
 */

get_header(); ?>

<div class="wp-alp-vendor-steps-page">
    <div class="wp-alp-steps-container">
        <div class="wp-alp-steps-left">
            <h1 class="wp-alp-steps-heading">
                <?php echo esc_html(get_locale() == 'en_US' ? 'Starting to offer your services is very simple' : 'Empezar a ofrecer tus servicios es muy sencillo'); ?>
            </h1>
        </div>
        
        <div class="wp-alp-steps-right">
            <div class="wp-alp-steps-list">
                <!-- Step 1 -->
                <div class="wp-alp-step-item">
                    <div class="wp-alp-step-content">
                        <span class="wp-alp-step-number">1</span>
                        <h2 class="wp-alp-step-title">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Describe your service' : 'Describe tu servicio'); ?>
                        </h2>
                        <p class="wp-alp-step-description">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Add some basic information, like what kind of service you offer and your capacity.' : 'Agrega información básica, como qué tipo de servicio ofreces y tu capacidad.'); ?>
                        </p>
                    </div>
                    <div class="wp-alp-step-image">
                        <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-step1.png'); ?>" alt="Step 1">
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="wp-alp-step-item">
                    <div class="wp-alp-step-content">
                        <span class="wp-alp-step-number">2</span>
                        <h2 class="wp-alp-step-title">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Make it stand out' : 'Haz que destaque'); ?>
                        </h2>
                        <p class="wp-alp-step-description">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Add at least five photos, a title, and a description. Well help you.' : 'Agrega al menos cinco fotos, un título y una descripción. Nosotros te ayudamos.'); ?>
                        </p>
                    </div>
                    <div class="wp-alp-step-image">
                        <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-step2.png'); ?>" alt="Step 2">
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="wp-alp-step-item">
                    <div class="wp-alp-step-content">
                        <span class="wp-alp-step-number">3</span>
                        <h2 class="wp-alp-step-title">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Finish and publish' : 'Terminar y publicar'); ?>
                        </h2>
                        <p class="wp-alp-step-description">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Set an initial price, check some details and publish your listing.' : 'Establece un precio inicial, verifica algunos detalles y publica tu anuncio.'); ?>
                        </p>
                    </div>
                    <div class="wp-alp-step-image">
                        <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-step3.png'); ?>" alt="Step 3">
                    </div>
                </div>
            </div>
            
            <div class="wp-alp-steps-action">
    <a href="<?php echo esc_url(get_permalink(get_page_by_path(get_locale() == 'en_US' ? 'vendor-form-step-1' : 'formulario-vendedor-paso-1'))); ?>" class="wp-alp-steps-button">
        <?php echo esc_html(get_locale() == 'en_US' ? 'Get Started' : 'Empieza'); ?>
    </a>
</div>
        </div>
    </div>
</div>

<?php get_footer(); ?>