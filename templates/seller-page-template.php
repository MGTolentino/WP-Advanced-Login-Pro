<?php
/**
 * Template Name: Página para Vendedores WP-ALP
 *
 * Template para mostrar la página de registro como vendedor.
 */

get_header();
?>

<div class="wp-alp-seller-page">
    <div class="wp-alp-seller-hero">
        <div class="wp-alp-seller-hero-content">
            <h1><?php _e('Conviértete en vendedor', 'wp-alp'); ?></h1>
            <p class="wp-alp-seller-subtitle"><?php _e('Comparte tus servicios y productos con miles de clientes potenciales', 'wp-alp'); ?></p>
            
            <button class="wp-alp-seller-cta" data-wp-alp-trigger="login">
                <?php _e('Comenzar ahora', 'wp-alp'); ?>
            </button>
        </div>
    </div>
    
    <div class="wp-alp-seller-benefits">
        <div class="wp-alp-container">
            <h2><?php _e('Por qué unirte como vendedor', 'wp-alp'); ?></h2>
            
            <div class="wp-alp-seller-benefits-grid">
                <div class="wp-alp-seller-benefit-card">
                    <div class="wp-alp-seller-benefit-icon">
                        <span class="dashicons dashicons-groups"></span>
                    </div>
                    <h3><?php _e('Alcanza a más clientes', 'wp-alp'); ?></h3>
                    <p><?php _e('Accede a miles de usuarios que buscan servicios para sus eventos.', 'wp-alp'); ?></p>
                </div>
                
                <div class="wp-alp-seller-benefit-card">
                    <div class="wp-alp-seller-benefit-icon">
                        <span class="dashicons dashicons-calendar"></span>
                    </div>
                    <h3><?php _e('Gestiona tu calendario', 'wp-alp'); ?></h3>
                    <p><?php _e('Organiza tus eventos y disponibilidad de manera eficiente.', 'wp-alp'); ?></p>
                </div>
                
                <div class="wp-alp-seller-benefit-card">
                    <div class="wp-alp-seller-benefit-icon">
                        <span class="dashicons dashicons-chart-line"></span>
                    </div>
                    <h3><?php _e('Aumenta tus ventas', 'wp-alp'); ?></h3>
                    <p><?php _e('Incrementa tus ingresos con nuestra plataforma de alta visibilidad.', 'wp-alp'); ?></p>
                </div>
                
                <div class="wp-alp-seller-benefit-card">
                    <div class="wp-alp-seller-benefit-icon">
                        <span class="dashicons dashicons-admin-appearance"></span>
                    </div>
                    <h3><?php _e('Perfil profesional', 'wp-alp'); ?></h3>
                    <p><?php _e('Crea un perfil atractivo para mostrar tus servicios y productos.', 'wp-alp'); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="wp-alp-seller-how-it-works">
        <div class="wp-alp-container">
            <h2><?php _e('Cómo funciona', 'wp-alp'); ?></h2>
            
            <div class="wp-alp-seller-steps">
                <div class="wp-alp-seller-step">
                    <div class="wp-alp-seller-step-number">1</div>
                    <h3><?php _e('Regístrate', 'wp-alp'); ?></h3>
                    <p><?php _e('Crea una cuenta como vendedor en nuestra plataforma.', 'wp-alp'); ?></p>
                </div>
                
                <div class="wp-alp-seller-step">
                    <div class="wp-alp-seller-step-number">2</div>
                    <h3><?php _e('Crea tu perfil', 'wp-alp'); ?></h3>
                    <p><?php _e('Configura tu perfil con información de tu negocio y servicios.', 'wp-alp'); ?></p>
                </div>
                
                <div class="wp-alp-seller-step">
                    <div class="wp-alp-seller-step-number">3</div>
                    <h3><?php _e('Publica tus servicios', 'wp-alp'); ?></h3>
                    <p><?php _e('Añade tus productos y servicios con precios y disponibilidad.', 'wp-alp'); ?></p>
                </div>
                
                <div class="wp-alp-seller-step">
                    <div class="wp-alp-seller-step-number">4</div>
                    <h3><?php _e('Recibe reservas', 'wp-alp'); ?></h3>
                    <p><?php _e('Empieza a recibir solicitudes de clientes interesados.', 'wp-alp'); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="wp-alp-seller-testimonials">
        <div class="wp-alp-container">
            <h2><?php _e('Lo que dicen nuestros vendedores', 'wp-alp'); ?></h2>
            
            <div class="wp-alp-seller-testimonials-grid">
                <div class="wp-alp-seller-testimonial">
                    <div class="wp-alp-seller-testimonial-content">
                        <p>"Desde que me uní como vendedor, mis ventas han aumentado un 40%. La plataforma es muy fácil de usar y el soporte es excelente."</p>
                    </div>
                    <div class="wp-alp-seller-testimonial-author">
                        <div class="wp-alp-seller-testimonial-avatar">
                            <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'public/img/avatar1.jpg'; ?>" alt="Testimonio">
                        </div>
                        <div class="wp-alp-seller-testimonial-info">
                            <h4>Carlos Rodríguez</h4>
                            <p>Fotógrafo de eventos</p>
                        </div>
                    </div>
                </div>
                
                <div class="wp-alp-seller-testimonial">
                    <div class="wp-alp-seller-testimonial-content">
                        <p>"La visibilidad que me ha dado esta plataforma es increíble. He podido expandir mi negocio de catering y ahora tengo clientes de toda la ciudad."</p>
                    </div>
                    <div class="wp-alp-seller-testimonial-author">
                        <div class="wp-alp-seller-testimonial-avatar">
                            <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'public/img/avatar2.jpg'; ?>" alt="Testimonio">
                        </div>
                        <div class="wp-alp-seller-testimonial-info">
                            <h4>Laura Martínez</h4>
                            <p>Servicio de catering</p>
                        </div>
                    </div>
                </div>
                
                <div class="wp-alp-seller-testimonial">
                    <div class="wp-alp-seller-testimonial-content">
                        <p>"Como DJ, encontrar clientes solía ser un desafío. Ahora recibo solicitudes cada semana y puedo organizar mejor mi calendario de eventos."</p>
                    </div>
                    <div class="wp-alp-seller-testimonial-author">
                        <div class="wp-alp-seller-testimonial-avatar">
                            <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'public/img/avatar3.jpg'; ?>" alt="Testimonio">
                        </div>
                        <div class="wp-alp-seller-testimonial-info">
                            <h4>Miguel Ángel López</h4>
                            <p>DJ profesional</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="wp-alp-seller-cta-section">
        <div class="wp-alp-container">
            <h2><?php _e('¿Listo para empezar?', 'wp-alp'); ?></h2>
            <p><?php _e('Únete a nuestra comunidad de vendedores y lleva tu negocio al siguiente nivel.', 'wp-alp'); ?></p>
            
            <button class="wp-alp-seller-cta" data-wp-alp-trigger="login">
                <?php _e('Registrarme ahora', 'wp-alp'); ?>
            </button>
        </div>
    </div>
    
    <div class="wp-alp-seller-faq">
        <div class="wp-alp-container">
            <h2><?php _e('Preguntas frecuentes', 'wp-alp'); ?></h2>
            
            <div class="wp-alp-seller-faq-list">
                <div class="wp-alp-seller-faq-item">
                    <h3><?php _e('¿Cuánto cuesta registrarse como vendedor?', 'wp-alp'); ?></h3>
                    <div class="wp-alp-seller-faq-answer">
                        <p><?php _e('El registro básico es gratuito. Ofrecemos planes premium con características adicionales a partir de $X al mes.', 'wp-alp'); ?></p>
                    </div>
                </div>
                
                <div class="wp-alp-seller-faq-item">
                    <h3><?php _e('¿Qué tipo de servicios puedo ofrecer?', 'wp-alp'); ?></h3>
                    <div class="wp-alp-seller-faq-answer">
                        <p><?php _e('Puedes ofrecer cualquier servicio relacionado con eventos: catering, fotografía, música, decoración, alquiler de espacios, etc.', 'wp-alp'); ?></p>
                    </div>
                </div>
                
                <div class="wp-alp-seller-faq-item">
                    <h3><?php _e('¿Cómo recibo los pagos?', 'wp-alp'); ?></h3>
                    <div class="wp-alp-seller-faq-answer">
                    <p><?php _e('Los pagos se procesan a través de nuestra plataforma segura. Recibirás el dinero directamente en tu cuenta bancaria dentro de los 5-7 días hábiles.', 'wp-alp'); ?></p>
                   </div>
               </div>
               
               <div class="wp-alp-seller-faq-item">
                   <h3><?php _e('¿Puedo usar la plataforma desde mi teléfono móvil?', 'wp-alp'); ?></h3>
                   <div class="wp-alp-seller-faq-answer">
                       <p><?php _e('Sí, nuestra plataforma es 100% responsive y funciona perfectamente en dispositivos móviles.', 'wp-alp'); ?></p>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>

<style>
   .wp-alp-seller-page {
       font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
       color: #222;
   }
   
   .wp-alp-container {
       max-width: 1200px;
       margin: 0 auto;
       padding: 0 20px;
   }
   
   .wp-alp-seller-hero {
       background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?php echo plugin_dir_url(dirname(__FILE__)) . 'public/img/seller-hero.jpg'; ?>');
       background-size: cover;
       background-position: center;
       color: white;
       padding: 100px 0;
       text-align: center;
   }
   
   .wp-alp-seller-hero-content {
       max-width: 800px;
       margin: 0 auto;
       padding: 0 20px;
   }
   
   .wp-alp-seller-hero h1 {
       font-size: 48px;
       margin-bottom: 20px;
   }
   
   .wp-alp-seller-subtitle {
       font-size: 24px;
       margin-bottom: 40px;
   }
   
   .wp-alp-seller-cta {
       background-color: #FF385C;
       color: white;
       font-size: 18px;
       padding: 15px 30px;
       border-radius: 8px;
       border: none;
       cursor: pointer;
       font-weight: bold;
       transition: background-color 0.3s;
   }
   
   .wp-alp-seller-cta:hover {
       background-color: #E31C5F;
   }
   
   .wp-alp-seller-benefits,
   .wp-alp-seller-how-it-works,
   .wp-alp-seller-testimonials,
   .wp-alp-seller-faq {
       padding: 80px 0;
   }
   
   .wp-alp-seller-benefits {
       background-color: #f7f7f7;
   }
   
   .wp-alp-seller-benefits h2,
   .wp-alp-seller-how-it-works h2,
   .wp-alp-seller-testimonials h2,
   .wp-alp-seller-faq h2 {
       text-align: center;
       font-size: 36px;
       margin-bottom: 50px;
   }
   
   .wp-alp-seller-benefits-grid {
       display: grid;
       grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
       gap: 30px;
   }
   
   .wp-alp-seller-benefit-card {
       background: white;
       padding: 30px;
       border-radius: 12px;
       box-shadow: 0 4px 12px rgba(0,0,0,0.08);
       text-align: center;
   }
   
   .wp-alp-seller-benefit-icon {
       background: #FF385C;
       color: white;
       width: 60px;
       height: 60px;
       border-radius: 50%;
       display: flex;
       align-items: center;
       justify-content: center;
       margin: 0 auto 20px;
   }
   
   .wp-alp-seller-benefit-icon .dashicons {
       width: 30px;
       height: 30px;
       font-size: 30px;
   }
   
   .wp-alp-seller-benefit-card h3 {
       font-size: 20px;
       margin-bottom: 10px;
   }
   
   .wp-alp-seller-steps {
       display: grid;
       grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
       gap: 30px;
   }
   
   .wp-alp-seller-step {
       text-align: center;
       padding: 20px;
   }
   
   .wp-alp-seller-step-number {
       background: #FF385C;
       color: white;
       width: 40px;
       height: 40px;
       border-radius: 50%;
       display: flex;
       align-items: center;
       justify-content: center;
       margin: 0 auto 15px;
       font-size: 18px;
       font-weight: bold;
   }
   
   .wp-alp-seller-testimonials {
       background-color: #f7f7f7;
   }
   
   .wp-alp-seller-testimonials-grid {
       display: grid;
       grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
       gap: 30px;
   }
   
   .wp-alp-seller-testimonial {
       background: white;
       padding: 25px;
       border-radius: 12px;
       box-shadow: 0 4px 12px rgba(0,0,0,0.08);
   }
   
   .wp-alp-seller-testimonial-content {
       margin-bottom: 20px;
   }
   
   .wp-alp-seller-testimonial-content p {
       font-style: italic;
       font-size: 16px;
       line-height: 1.6;
   }
   
   .wp-alp-seller-testimonial-author {
       display: flex;
       align-items: center;
   }
   
   .wp-alp-seller-testimonial-avatar {
       width: 50px;
       height: 50px;
       border-radius: 50%;
       overflow: hidden;
       margin-right: 15px;
   }
   
   .wp-alp-seller-testimonial-avatar img {
       width: 100%;
       height: 100%;
       object-fit: cover;
   }
   
   .wp-alp-seller-testimonial-info h4 {
       margin: 0 0 5px;
       font-size: 18px;
   }
   
   .wp-alp-seller-testimonial-info p {
       margin: 0;
       font-size: 14px;
       color: #717171;
   }
   
   .wp-alp-seller-cta-section {
       background-color: #FF385C;
       color: white;
       text-align: center;
       padding: 60px 0;
   }
   
   .wp-alp-seller-cta-section h2 {
       font-size: 36px;
       margin-bottom: 20px;
   }
   
   .wp-alp-seller-cta-section p {
       font-size: 18px;
       margin-bottom: 30px;
       max-width: 700px;
       margin-left: auto;
       margin-right: auto;
   }
   
   .wp-alp-seller-cta-section .wp-alp-seller-cta {
       background-color: white;
       color: #FF385C;
   }
   
   .wp-alp-seller-cta-section .wp-alp-seller-cta:hover {
       background-color: #f7f7f7;
   }
   
   .wp-alp-seller-faq-list {
       max-width: 800px;
       margin: 0 auto;
   }
   
   .wp-alp-seller-faq-item {
       margin-bottom: 20px;
       border-bottom: 1px solid #e4e4e4;
       padding-bottom: 20px;
   }
   
   .wp-alp-seller-faq-item h3 {
       font-size: 20px;
       margin-bottom: 10px;
       cursor: pointer;
   }
   
   .wp-alp-seller-faq-item h3:hover {
       color: #FF385C;
   }
   
   .wp-alp-seller-faq-answer {
       font-size: 16px;
       line-height: 1.6;
   }
   
   @media (max-width: 768px) {
       .wp-alp-seller-hero h1 {
           font-size: 36px;
       }
       
       .wp-alp-seller-subtitle {
           font-size: 18px;
       }
       
       .wp-alp-seller-benefits,
       .wp-alp-seller-how-it-works,
       .wp-alp-seller-testimonials,
       .wp-alp-seller-faq {
           padding: 50px 0;
       }
       
       .wp-alp-seller-benefits h2,
       .wp-alp-seller-how-it-works h2,
       .wp-alp-seller-testimonials h2,
       .wp-alp-seller-faq h2 {
           font-size: 28px;
       }
   }
</style>

<!-- Modal para iniciar sesión -->
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
   // Script para el toggle de las FAQs
   $('.wp-alp-seller-faq-item h3').click(function() {
       $(this).next('.wp-alp-seller-faq-answer').slideToggle();
   });
});
</script>

<?php
get_footer();