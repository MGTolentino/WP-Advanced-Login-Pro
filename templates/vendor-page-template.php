<?php
/**
 * Template Name: Página para Vendedores WP-ALP
 * 
 * Template para la página "Conviértete en vendedor"
 */

get_header(); ?>

<div class="wp-alp-vendor-page">
    <!-- Hero Section -->
    <section class="wp-alp-vendor-hero">
        <div class="wp-alp-container">
            <div class="wp-alp-vendor-hero-content">
                <h1>Llega a miles de personas buscando servicios para sus eventos especiales</h1>
                <p class="wp-alp-hero-subtitle">Ofrece tus servicios, aumenta tu visibilidad y haz crecer tu negocio</p>
                <a href="#start" class="wp-alp-cta-button">Comienza ahora</a>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="wp-alp-vendor-process">
        <div class="wp-alp-container">
            <h2>Comienza a vender en solo unos pasos</h2>
            <div class="wp-alp-process-steps">
                <div class="wp-alp-process-step">
                    <div class="wp-alp-step-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3>Regístrate</h3>
                    <p>Crea tu cuenta de vendedor en nuestra plataforma</p>
                </div>
                <div class="wp-alp-process-step">
                    <div class="wp-alp-step-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3>Configura tu perfil</h3>
                    <p>Personaliza tu perfil con fotos e información</p>
                </div>
                <div class="wp-alp-process-step">
                    <div class="wp-alp-step-icon">
                        <i class="fas fa-upload"></i>
                    </div>
                    <h3>Publica tus servicios</h3>
                    <p>Añade tus servicios y establece tus precios</p>
                </div>
                <div class="wp-alp-process-step">
                    <div class="wp-alp-step-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3>Recibe ingresos</h3>
                    <p>Obtén pagos seguros por tus servicios</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mobile App Section -->
    <section class="wp-alp-vendor-mobile">
        <div class="wp-alp-container">
            <div class="wp-alp-mobile-flex">
                <div class="wp-alp-mobile-text">
                    <h2>Cuéntales a los clientes todo lo que ofrece tu servicio</h2>
                    <!-- Espacio para contenido dinámico de los pasos -->
                    <div class="wp-alp-mobile-content">
                        <!-- Aquí irá el contenido dinámico generado por JS -->
                        <div id="mobile-step-content">
                            <h3>Paso 1</h3>
                            <h4>Describe tu servicio</h4>
                            <p>Agrega todos los detalles que los clientes necesitan saber sobre lo que ofreces.</p>
                        </div>
                    </div>
                </div>
                <div class="wp-alp-mobile-image">
    <div class="wp-alp-mobile-placeholder">
    <img src="<?php echo esc_url(plugins_url('images/mobile-app.png', dirname(__FILE__))); ?>" alt="...">
    </div>
</div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="wp-alp-vendor-features">
        <div class="wp-alp-container">
            <div class="wp-alp-features-grid">
                <div class="wp-alp-feature">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Crea un anuncio para tu servicio en pocos pasos</h3>
                    <p>Nuestra plataforma te guía paso a paso para crear anuncios atractivos que destaquen tus servicios.</p>
                </div>
                <div class="wp-alp-feature">
                    <i class="fas fa-clock"></i>
                    <h3>Ve a tu ritmo y haz cambios cuando tú quieras</h3>
                    <p>Actualiza tus anuncios, precios y disponibilidad en cualquier momento según tus necesidades.</p>
                </div>
                <div class="wp-alp-feature">
                    <i class="fas fa-headset"></i>
                    <h3>Recibe ayuda a la medida de vendedores con experiencia en todo momento</h3>
                    <p>Nuestro equipo de soporte especializado está disponible para ayudarte a tener éxito en la plataforma.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Simplicity Section -->
    <section class="wp-alp-vendor-simplicity">
        <div class="wp-alp-container">
            <div class="wp-alp-simplicity-content">
                <h2>Publicar tu servicio es muy sencillo</h2>
                <p>Nuestro sistema intuitivo te permite crear anuncios profesionales en minutos, sin necesidad de conocimientos técnicos.</p>
                <!-- Espacio para más contenido dinámico si lo necesitas -->
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="wp-alp-vendor-gallery">
        <div class="wp-alp-container">
            <h2>Servicios destacados en nuestra plataforma</h2>
            <div class="wp-alp-gallery-grid">
    <div class="wp-alp-gallery-item">
        <div class="wp-alp-gallery-image">
            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/gallery-music.png'); ?>" alt="Grupos musicales">
        </div>
        <div class="wp-alp-gallery-caption">
            <h4>Grupos musicales</h4>
            <p>Servicios de entretenimiento para todo tipo de eventos</p>
        </div>
    </div>
    <div class="wp-alp-gallery-item">
        <div class="wp-alp-gallery-image">
            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/gallery-venue.png'); ?>" alt="Recintos para eventos">
        </div>
        <div class="wp-alp-gallery-caption">
            <h4>Recintos para eventos</h4>
            <p>Espacios exclusivos para celebraciones inolvidables</p>
        </div>
    </div>
    <div class="wp-alp-gallery-item">
        <div class="wp-alp-gallery-image">
            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/gallery-catering.png'); ?>" alt="Servicios de catering">
        </div>
        <div class="wp-alp-gallery-caption">
            <h4>Servicios de catering</h4>
            <p>Experiencias gastronómicas únicas para tus invitados</p>
        </div>
    </div>
</div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="wp-alp-vendor-testimonials">
        <div class="wp-alp-container">
            <h2>Lo que dicen nuestros vendedores</h2>
            <div class="wp-alp-testimonials-slider">
                <div class="wp-alp-testimonial">
                    <div class="wp-alp-testimonial-content">
                        <p>"Desde que me uní a la plataforma, hemos incrementado nuestras presentaciones en un 40%. La visibilidad que nos da nos permite llegar a clientes que nunca hubiéramos alcanzado por nuestra cuenta."</p>
                    </div>
                    <div class="wp-alp-testimonial-author">
                        <p><strong>Grupo musical con show de bailarines</strong></p>
                    </div>
                </div>
                <div class="wp-alp-testimonial">
                    <div class="wp-alp-testimonial-content">
                        <p>"Administrar las reservas siempre fue complicado. Ahora, el sistema de calendario nos permite gestionar múltiples eventos sin traslapes y con todos los detalles organizados."</p>
                    </div>
                    <div class="wp-alp-testimonial-author">
                        <p><strong>Propietario de recinto para eventos</strong></p>
                    </div>
                </div>
                <div class="wp-alp-testimonial">
                    <div class="wp-alp-testimonial-content">
                        <p>"En nuestro primer año en la plataforma, aumentamos nuestras reservas en un 65%. La posibilidad de mostrar un recorrido virtual de nuestras instalaciones ha sido clave para atraer a nuevos clientes."</p>
                    </div>
                    <div class="wp-alp-testimonial-author">
                        <p><strong>Quinta para eventos con más de 10 años de experiencia</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
<section class="wp-alp-vendor-cta" id="start">
    <div class="wp-alp-container">
        <div class="wp-alp-cta-content">
            <h2>¿Estás listo para empezar?</h2>
            <p>Únete a nuestra comunidad de vendedores y lleva tu negocio al siguiente nivel</p>
            
            <a href="<?php 
                // Intenta encontrar la página por su ruta
                $steps_page = get_page_by_path('pasos-para-convertirte-en-vendedor');
                
                // Si no encuentra la página por ese slug, intenta con variaciones
                if (!$steps_page) {
                    $steps_page = get_page_by_path('steps-to-become-a-seller');
                }
                
                // Si aún no lo encuentra, busca por título
                if (!$steps_page) {
                    $steps_pages = get_posts(array(
                        'post_type' => 'page',
                        'post_status' => 'publish',
                        'posts_per_page' => 1,
                        'title' => get_locale() == 'en_US' ? 'Steps to become a seller' : 'Pasos para convertirte en vendedor'
                    ));
                    
                    if (!empty($steps_pages)) {
                        $steps_page = $steps_pages[0];
                    }
                }
                
                // Si se encontró la página, usa su URL, de lo contrario usa una URL codificada
                if ($steps_page) {
                    echo esc_url(get_permalink($steps_page->ID));
                } else {
                    // URL de respaldo directa
                    echo esc_url(home_url(get_locale() == 'en_US' ? '/steps-to-become-a-seller/' : '/pasos-para-convertirte-en-vendedor/'));
                }
            ?>" class="wp-alp-cta-button">
                <?php echo esc_html(get_locale() == 'en_US' ? 'Become a seller now' : 'Conviértete en vendedor ahora'); ?>
            </a>
        </div>
    </div>
</section>
</div>

<?php get_footer(); ?>
