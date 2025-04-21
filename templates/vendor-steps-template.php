<?php
/**
 * Template Name: Vendor Steps Template
 * 
 * A clean implementation of the Airbnb-style vendor steps process
 */

get_header(); ?>

<div class="wp-alp-vendor-steps-page">
    <!-- Main Content Container -->
    <div class="wp-alp-main-container">
        <!-- Step Overview Page (Initial Page) -->
        <div class="wp-alp-step-overview" id="step-overview">
            <!-- Left Column with Main Title -->
            <div class="wp-alp-overview-left">
                <h1 class="wp-alp-overview-title">
                    <?php echo esc_html(get_locale() == 'en_US' ? 'Starting to offer your services is very simple' : 'Empezar a ofrecer tus servicios es muy sencillo'); ?>
                </h1>
            </div>
            
            <!-- Right Column with Steps List -->
            <div class="wp-alp-overview-right">
                <!-- Step 1 -->
                <div class="wp-alp-overview-step">
                    <div class="wp-alp-overview-step-header">
                        <span class="wp-alp-step-number">1</span>
                        <h2 class="wp-alp-step-title">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Describe your service' : 'Describe tu servicio'); ?>
                        </h2>
                    </div>
                    <p class="wp-alp-step-description">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Add some basic information, like what kind of service you offer and your capacity.' : 'Agrega información básica, como qué tipo de servicio ofreces y tu capacidad.'); ?>
                    </p>
                    <div class="wp-alp-step-divider"></div>
                </div>
                
                <!-- Step 2 -->
                <div class="wp-alp-overview-step">
                    <div class="wp-alp-overview-step-header">
                        <span class="wp-alp-step-number">2</span>
                        <h2 class="wp-alp-step-title">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Make it stand out' : 'Haz que destaque'); ?>
                        </h2>
                    </div>
                    <p class="wp-alp-step-description">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Add at least five photos, a title, and a description. We\'ll help you.' : 'Agrega al menos cinco fotos, un título y una descripción. Nosotros te ayudamos.'); ?>
                    </p>
                    <div class="wp-alp-step-divider"></div>
                </div>
                
                <!-- Step 3 -->
                <div class="wp-alp-overview-step">
                    <div class="wp-alp-overview-step-header">
                        <span class="wp-alp-step-number">3</span>
                        <h2 class="wp-alp-step-title">
                            <?php echo esc_html(get_locale() == 'en_US' ? 'Finish and publish' : 'Terminar y publicar'); ?>
                        </h2>
                    </div>
                    <p class="wp-alp-step-description">
                        <?php echo esc_html(get_locale() == 'en_US' ? 'Set an initial price, check some details and publish your listing.' : 'Establece un precio inicial, verifica algunos detalles y publica tu anuncio.'); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Progress Bar (Simple line initially) -->
    <div class="wp-alp-progress-container">
        <div class="wp-alp-progress-bar">
            <div class="wp-alp-progress-section" data-section="1"></div>
            <div class="wp-alp-progress-section" data-section="2"></div>
            <div class="wp-alp-progress-section" data-section="3"></div>
        </div>
    </div>
        
        <!-- "Get Started" Button -->
        <div class="wp-alp-get-started-container">
            <button type="button" class="wp-alp-get-started-button" id="get-started-button">
                <?php echo esc_html(get_locale() == 'en_US' ? 'Get Started' : 'Empieza'); ?>
            </button>
        </div>
    </div>
    
    
</div>

<!-- Add custom styles inline to ensure they take precedence -->
<style>
/* Reset styles to prevent theme conflicts */
.wp-alp-vendor-steps-page * {
    box-sizing: border-box !important;
}

/* Main container */
.wp-alp-vendor-steps-page {
    font-family: 'Circular', -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, sans-serif !important;
    color: #222222 !important;
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 48px 24px 100px !important;
    position: relative !important;
}

/* Main layout */
.wp-alp-main-container {
    display: flex !important;
    flex-direction: column !important;
    min-height: 70vh !important;
}

/* Overview page layout */
.wp-alp-step-overview {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 48px !important;
    margin-bottom: 40px !important;
}

/* Left column */
.wp-alp-overview-left {
    flex: 1 !important;
    min-width: 280px !important;
}

.wp-alp-overview-title {
    font-size: 36px !important;
    font-weight: 600 !important;
    color: #222222 !important;
    line-height: 1.2 !important;
    margin: 0 0 24px !important;
}

/* Right column */
.wp-alp-overview-right {
    flex: 2 !important;
    min-width: 320px !important;
}

/* Individual step in overview */
.wp-alp-overview-step {
    margin-bottom: 32px !important;
}

.wp-alp-overview-step-header {
    display: flex !important;
    align-items: baseline !important;
    gap: 16px !important;
    margin-bottom: 8px !important;
}

.wp-alp-step-number {
    font-size: 18px !important;
    font-weight: 600 !important;
    color: #222222 !important;
}

.wp-alp-step-title {
    font-size: 22px !important;
    font-weight: 600 !important;
    color: #222222 !important;
    margin: 0 !important;
}

.wp-alp-step-description {
    font-size: 16px !important;
    color: #717171 !important;
    line-height: 1.5 !important;
    margin: 0 0 24px !important;
}

.wp-alp-step-divider {
    height: 1px !important;
    background-color: #EBEBEB !important;
    margin-bottom: 32px !important;
}

/* Get Started button container */
.wp-alp-get-started-container {
    display: flex !important;
    justify-content: flex-end !important;
    margin-top: auto !important;
}

.wp-alp-get-started-button {
    background-color: #FF385C !important;
    color: white !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 14px 32px !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: background-color 0.2s ease !important;
}

.wp-alp-get-started-button:hover {
    background-color: #E31C5F !important;
}

/* Progress bar container */
.wp-alp-progress-container {
    width: 100% !important;
    margin: 20px 0 !important;
    padding: 0 !important;
    display: block !important;
}

/* Progress bar - inicialmente solo una línea sólida */
.wp-alp-progress-bar {
    width: 100% !important;
    height: 6px !important;
    background-color: #DDDDDD !important;
    display: flex !important;
}

/* Progress sections (invisible initially) */
.wp-alp-progress-section {
    flex: 1 !important;
    height: 100% !important;
}

/* Mobile styles */
@media (max-width: 768px) {
    .wp-alp-vendor-steps-page {
        padding: 24px 16px 100px !important;
    }
    
    .wp-alp-overview-title {
        font-size: 28px !important;
    }
    
    .wp-alp-step-number {
        font-size: 16px !important;
    }
    
    .wp-alp-step-title {
        font-size: 20px !important;
    }
}

/* Barra de emergencia - usada para depuración */
body:after {
    content: "";
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: #DDDDDD;
    z-index: 999999;
}
</style>

<!-- Custom JavaScript to handle interactions -->
<script>
jQuery(document).ready(function($) {
    // Get Started button click event
    $('#get-started-button').on('click', function() {
        // For now, just log that the button was clicked
        console.log('Get Started button clicked');
        // You can add the actual navigation logic here later
    });
});
</script>

<?php get_footer(); ?>