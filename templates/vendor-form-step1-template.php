<?php
/**
 * Template Name: Formulario Vendedor Paso 1 WP-ALP
 * 
 * Template para el primer paso del formulario de registro de vendedor
 */

get_header(); ?>

<div class="wp-alp-vendor-form-page">
    <div class="wp-alp-form-header">
        <div class="wp-alp-container">
            <div class="wp-alp-form-header-content">
                <a href="#" class="wp-alp-help-link">
                    <?php echo esc_html(get_locale() == 'en_US' ? 'Have a question?' : '¿Tienes alguna duda?'); ?>
                </a>
                <a href="<?php echo esc_url(home_url()); ?>" class="wp-alp-save-exit">
                    <?php echo esc_html(get_locale() == 'en_US' ? 'Save and exit' : 'Guardar y salir'); ?>
                </a>
            </div>
        </div>
    </div>

    <div class="wp-alp-form-content">
        <div class="wp-alp-container">
            <div class="wp-alp-form-flex">
                <div class="wp-alp-form-left">
                    <div class="wp-alp-step-indicator">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Step 1' : 'Paso 1'); ?>
                    </div>
                    
                    <h1 class="wp-alp-form-title">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Describe your service' : 'Describe tu servicio'); ?>
                    </h1>
                    
                    <p class="wp-alp-form-description">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'In this step, we\'ll ask you what type of service you offer and how many people you can accommodate. Next, tell us where it\'s located and how many clients can book.' : 'En este paso, te preguntaremos qué tipo de servicio ofreces y cuántas personas puedes atender. A continuación, indícanos la ubicación y cuántos clientes pueden reservar.'); ?>
                    </p>
                    
                    <form id="vendor-step1-form" class="wp-alp-vendor-form" method="post">
                        <div class="wp-alp-form-group">
                            <label for="service-type"><?php echo esc_html(get_locale() == 'en_US' ? 'What type of service do you offer?' : '¿Qué tipo de servicio ofreces?'); ?></label>
                            <select id="service-type" name="service_type" class="wp-alp-select" required>
                                <option value=""><?php echo esc_html(get_locale() == 'en_US' ? 'Select a service type' : 'Selecciona un tipo de servicio'); ?></option>
                                <option value="music"><?php echo esc_html(get_locale() == 'en_US' ? 'Music and Entertainment' : 'Música y Entretenimiento'); ?></option>
                                <option value="venue"><?php echo esc_html(get_locale() == 'en_US' ? 'Venue or Location' : 'Recinto o Ubicación'); ?></option>
                                <option value="catering"><?php echo esc_html(get_locale() == 'en_US' ? 'Catering and Food' : 'Catering y Alimentos'); ?></option>
                                <option value="decoration"><?php echo esc_html(get_locale() == 'en_US' ? 'Decoration' : 'Decoración'); ?></option>
                                <option value="photography"><?php echo esc_html(get_locale() == 'en_US' ? 'Photography and Video' : 'Fotografía y Video'); ?></option>
                                <option value="other"><?php echo esc_html(get_locale() == 'en_US' ? 'Other' : 'Otro'); ?></option>
                            </select>
                        </div>
                        
                        <div class="wp-alp-form-group">
                            <label for="service-name"><?php echo esc_html(get_locale() == 'en_US' ? 'What is the name of your service?' : '¿Cuál es el nombre de tu servicio?'); ?></label>
                            <input type="text" id="service-name" name="service_name" class="wp-alp-input" required>
                        </div>
                        
                        <div class="wp-alp-form-group">
                            <label for="service-capacity"><?php echo esc_html(get_locale() == 'en_US' ? 'How many people can you accommodate?' : '¿Cuántas personas puedes atender?'); ?></label>
                            <input type="number" id="service-capacity" name="service_capacity" class="wp-alp-input" min="1" required>
                        </div>
                        
                        <div class="wp-alp-form-group">
                            <label for="service-location"><?php echo esc_html(get_locale() == 'en_US' ? 'Where is your service located?' : '¿Dónde está ubicado tu servicio?'); ?></label>
                            <input type="text" id="service-location" name="service_location" class="wp-alp-input" placeholder="<?php echo esc_attr(get_locale() == 'en_US' ? 'City, State' : 'Ciudad, Estado'); ?>" required>
                        </div>
                        
                        <input type="hidden" name="form_step" value="1">
                        <input type="hidden" name="action" value="wp_alp_save_vendor_form">
                        <?php wp_nonce_field('wp_alp_vendor_form_nonce', 'vendor_form_nonce'); ?>
                    </form>
                </div>
                
                <div class="wp-alp-form-right">
                    <div class="wp-alp-form-image">
                        <img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__)) . 'public/img/vendor-form-step1.png'); ?>" alt="Describe your service">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Progress Bar Section -->
    <div class="wp-alp-progress-bar">
        <div class="wp-alp-container">
            <div class="wp-alp-progress-inner">
                <div class="wp-alp-progress-line">
                    <div class="wp-alp-progress-indicator" style="width: 33%;"></div>
                </div>
                <div class="wp-alp-progress-buttons">
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path(get_locale() == 'en_US' ? 'steps-to-become-a-seller' : 'pasos-para-convertirte-en-vendedor'))); ?>" class="wp-alp-back-button">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Back' : 'Atrás'); ?>
                    </a>
                    <button type="submit" form="vendor-step1-form" class="wp-alp-next-button">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Next' : 'Siguiente'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>