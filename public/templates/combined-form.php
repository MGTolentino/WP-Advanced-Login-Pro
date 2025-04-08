<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$redirect_to = !empty($atts['redirect']) ? $atts['redirect'] : '';
$show_title = filter_var($atts['show_title'], FILTER_VALIDATE_BOOLEAN);
$email = isset($email) ? $email : '';
$phone = isset($phone) ? $phone : '';
$is_phone = isset($is_phone) ? $is_phone : false;
$is_new_user = isset($is_new_user) ? $is_new_user : true;
?>

<div class="wp-alp-form-container wp-alp-combined-container">
    <?php if ($show_title) : ?>
        <h2 class="wp-alp-form-title"><?php echo $is_new_user ? esc_html__('Finish signing up', 'wp-alp') : esc_html__('Complete your profile', 'wp-alp'); ?></h2>
    <?php endif; ?>
    
    <div class="wp-alp-form-wrapper">
        <form id="wp-alp-combined-form" class="wp-alp-form" data-form-type="combined">
            <div class="wp-alp-form-inner">
                <?php if ($is_new_user) : ?>
                <!-- Registration Fields -->
                <div class="wp-alp-form-section">
                    <h3 class="wp-alp-section-title"><?php esc_html_e('Account Information', 'wp-alp'); ?></h3>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-email"><?php esc_html_e('Email', 'wp-alp'); ?></label>
                        <input type="email" id="wp-alp-email" name="email" value="<?php echo esc_attr($email); ?>" <?php echo !empty($email) ? 'readonly' : 'required'; ?>>
                    </div>
                    
                    <?php if ($is_phone) : ?>
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-phone"><?php esc_html_e('Phone Number', 'wp-alp'); ?></label>
                        <input type="tel" id="wp-alp-phone" name="phone" value="<?php echo esc_attr($phone); ?>" <?php echo !empty($phone) ? 'readonly' : 'required'; ?>>
                    </div>
                    <?php endif; ?>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-first-name"><?php esc_html_e('First Name', 'wp-alp'); ?></label>
                        <input type="text" id="wp-alp-first-name" name="first_name" required>
                    </div>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-last-name"><?php esc_html_e('Last Name', 'wp-alp'); ?></label>
                        <input type="text" id="wp-alp-last-name" name="last_name" required>
                    </div>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-password"><?php esc_html_e('Password', 'wp-alp'); ?></label>
                        <div class="wp-alp-password-wrapper">
                            <input type="password" id="wp-alp-password" name="password" required>
                            <button type="button" class="wp-alp-toggle-password" aria-label="<?php esc_attr_e('Toggle password visibility', 'wp-alp'); ?>">
                                <span class="dashicons dashicons-visibility"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Profile Completion Fields -->
                <div class="wp-alp-form-section">
                    <h3 class="wp-alp-section-title"><?php esc_html_e('Event Details', 'wp-alp'); ?></h3>
                    
                    <?php if (!$is_phone && !$is_new_user) : ?>
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-phone"><?php esc_html_e('Phone Number', 'wp-alp'); ?> <span class="required">*</span></label>
                        <input type="tel" id="wp-alp-phone" name="phone" required>
                    </div>
                    <?php endif; ?>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-event-type"><?php esc_html_e('Event Type', 'wp-alp'); ?> <span class="required">*</span></label>
                        <select id="wp-alp-event-type" name="event_type" required>
                            <option value=""><?php esc_html_e('Select Event Type', 'wp-alp'); ?></option>
                            <option value="Bodas"><?php esc_html_e('Weddings', 'wp-alp'); ?></option>
                            <option value="Cumpleaños"><?php esc_html_e('Birthdays', 'wp-alp'); ?></option>
                            <option value="Corporativo"><?php esc_html_e('Corporate', 'wp-alp'); ?></option>
                            <option value="Social"><?php esc_html_e('Social', 'wp-alp'); ?></option>
                            <option value="Otro"><?php esc_html_e('Other', 'wp-alp'); ?></option>
                        </select>
                    </div>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-event-date"><?php esc_html_e('Event Date', 'wp-alp'); ?></label>
                        <input type="date" id="wp-alp-event-date" name="event_date">
                    </div>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-event-address"><?php esc_html_e('Event Address/Location', 'wp-alp'); ?></label>
                        <textarea id="wp-alp-event-address" name="event_address" rows="3"></textarea>
                    </div>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-event-attendees"><?php esc_html_e('Number of Attendees', 'wp-alp'); ?></label>
                        <input type="number" id="wp-alp-event-attendees" name="event_attendees" min="1">
                    </div>
                </div>
                
                <input type="hidden" name="action" value="wp_alp_process_combined_form">
                <input type="hidden" name="is_new_user" value="<?php echo $is_new_user ? '1' : '0'; ?>">
                <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>">
                <input type="hidden" name="_csrf_token" value="<?php echo esc_attr($csrf_token); ?>">
                <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
                <input type="hidden" name="identifier" value="<?php echo esc_attr(!empty($email) ? $email : $phone); ?>">
                <input type="hidden" name="is_phone" value="<?php echo $is_phone ? '1' : '0'; ?>">
                
                <div class="wp-alp-form-submit">
                    <button type="submit" class="wp-alp-button wp-alp-submit-button">
                        <?php esc_html_e('Complete Registration', 'wp-alp'); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="wp-alp-form-messages" id="wp-alp-combined-messages"></div>
</div>