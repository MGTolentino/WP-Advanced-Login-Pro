<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wp-alp-form-container wp-alp-initial-container">
    <h2 class="wp-alp-form-title"><?php esc_html_e('Log in or sign up', 'wp-alp'); ?></h2>
    
    <div class="wp-alp-form-wrapper">
        <form id="wp-alp-initial-form" class="wp-alp-form" data-form-type="initial">
            <div class="wp-alp-form-inner">
                <div class="wp-alp-form-field">
                    <label for="wp-alp-identifier"><?php esc_html_e('Email or phone number', 'wp-alp'); ?></label>
                    <input type="text" id="wp-alp-identifier" name="identifier" required>
                </div>
                
                <div class="wp-alp-form-submit">
                    <button type="submit" class="wp-alp-button wp-alp-continue-button">
                        <?php esc_html_e('Continue', 'wp-alp'); ?>
                    </button>
                </div>
            </div>
        </form>
        
        <div class="wp-alp-separator-text">
            <span><?php esc_html_e('or', 'wp-alp'); ?></span>
        </div>
        
        <?php if (method_exists($this->social, 'render_social_buttons')) : ?>
            <div class="wp-alp-social-login">
                <?php echo $this->social->render_social_buttons('login'); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="wp-alp-form-messages" id="wp-alp-initial-messages"></div>
</div>