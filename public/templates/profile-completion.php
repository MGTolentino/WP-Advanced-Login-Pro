<?php
/**
 * Template for the profile completion form.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/public/templates
 *
 * Variables available:
 * @var array  $atts           Shortcode attributes.
 * @var string $nonce          Security nonce.
 * @var string $csrf_token     CSRF token.
 * @var array  $options        Plugin options.
 * @var object $user           Current user object.
 * @var int    $user_id        Current user ID.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$redirect_to = !empty($atts['redirect']) ? $atts['redirect'] : '';
$show_title = filter_var($atts['show_title'], FILTER_VALIDATE_BOOLEAN);

// Get event types and categories
$event_types = array(
    'Bodas' => __('Weddings', 'wp-alp'),
    'Cumpleaños' => __('Birthdays', 'wp-alp'),
    'Corporativo' => __('Corporate', 'wp-alp'),
    'Social' => __('Social', 'wp-alp'),
    'Otro' => __('Other', 'wp-alp'),
);

$event_categories = array(
    'Catering' => __('Catering', 'wp-alp'),
    'Decoración' => __('Decoration', 'wp-alp'),
    'Fotografía' => __('Photography', 'wp-alp'),
    'Música' => __('Music', 'wp-alp'),
    'Lugar' => __('Venue', 'wp-alp'),
);

$services = array(
    'Catering' => __('Catering', 'wp-alp'),
    'Decoración' => __('Decoration', 'wp-alp'),
    'Fotografía' => __('Photography', 'wp-alp'),
    'Música' => __('Music', 'wp-alp'),
    'Lugar' => __('Venue', 'wp-alp'),
    'Coordinación' => __('Coordination', 'wp-alp'),
    'Otro' => __('Other', 'wp-alp'),
);
?>

<div class="wp-alp-form-container wp-alp-profile-completion-container">
    <?php if ($show_title) : ?>
        <h2 class="wp-alp-form-title"><?php esc_html_e('Complete Your Profile', 'wp-alp'); ?></h2>
        <p class="wp-alp-form-description">
            <?php esc_html_e('Please provide additional information about your event to help us serve you better.', 'wp-alp'); ?>
        </p>
    <?php endif; ?>
    
    <div class="wp-alp-form-wrapper">
        <form id="wp-alp-profile-completion-form" class="wp-alp-form" method="post">
            <div class="wp-alp-form-inner">
                <div class="wp-alp-form-section">
                    <h3 class="wp-alp-section-title"><?php esc_html_e('Contact Information', 'wp-alp'); ?></h3>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-phone"><?php esc_html_e('Phone Number', 'wp-alp'); ?> <span class="required">*</span></label>
                        <input type="tel" id="wp-alp-phone" name="phone" required>
                    </div>
                </div>
                
                <div class="wp-alp-form-section">
                    <h3 class="wp-alp-section-title"><?php esc_html_e('Event Details', 'wp-alp'); ?></h3>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-event-type"><?php esc_html_e('Event Type', 'wp-alp'); ?> <span class="required">*</span></label>
                        <select id="wp-alp-event-type" name="event_type" required>
                            <option value=""><?php esc_html_e('Select Event Type', 'wp-alp'); ?></option>
                            <?php foreach ($event_types as $value => $label) : ?>
                                <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
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
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-event-category"><?php esc_html_e('Event Category', 'wp-alp'); ?></label>
                        <select id="wp-alp-event-category" name="event_category">
                            <option value=""><?php esc_html_e('Select Category', 'wp-alp'); ?></option>
                            <?php foreach ($event_categories as $value => $label) : ?>
                                <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-service-interest"><?php esc_html_e('Service of Interest', 'wp-alp'); ?></label>
                        <select id="wp-alp-service-interest" name="service_interest">
                            <option value=""><?php esc_html_e('Select Service', 'wp-alp'); ?></option>
                            <?php foreach ($services as $value => $label) : ?>
                                <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="wp-alp-form-field">
                        <label for="wp-alp-event-details"><?php esc_html_e('Additional Details', 'wp-alp'); ?></label>
                        <textarea id="wp-alp-event-details" name="event_details" rows="5"></textarea>
                    </div>
                </div>
                
                <?php
                // Add anti-bot fields
                echo $this->security->add_anti_bot_fields();
                ?>
                
                <input type="hidden" name="action" value="wp_alp_complete_profile">
                <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>">
                <input type="hidden" name="_csrf_token" value="<?php echo esc_attr($csrf_token); ?>">
                <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect_to); ?>">
                
                <div class="wp-alp-form-submit">
                    <a href="#" id="wp-alp-skip-profile" class="wp-alp-button wp-alp-button-secondary">
                        <?php esc_html_e('Skip for Now', 'wp-alp'); ?>
                    </a>
                    <button type="submit" class="wp-alp-button wp-alp-submit-button">
                        <?php esc_html_e('Complete Profile', 'wp-alp'); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="wp-alp-form-messages" id="wp-alp-profile-completion-messages"></div>
</div>

<script>
    (function() {
        // Skip profile completion
        document.getElementById('wp-alp-skip-profile').addEventListener('click', function(e) {
            e.preventDefault();
            
            var redirectTo = '<?php echo esc_url(empty($redirect_to) ? home_url() : $redirect_to); ?>';
            
            // Check if we're in a modal
            if (document.querySelector('.wp-alp-modal')) {
                window.location.href = redirectTo;
            } else {
                window.location.href = redirectTo;
            }
        });
        
        // Form submission - use the modal handler if in modal
        var form = document.getElementById('wp-alp-profile-completion-form');
        if (form) {
            // If we're in a modal, the event is handled by jQuery in wp-alp-public.js
            if (!document.querySelector('.wp-alp-modal')) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    var messagesContainer = document.getElementById('wp-alp-profile-completion-messages');
                    var submitButton = form.querySelector('button[type="submit"]');
                    
                    // Clear previous messages
                    messagesContainer.innerHTML = '';
                    
                    // Disable submit button
                    submitButton.disabled = true;
                    submitButton.classList.add('wp-alp-button-loading');
                    
                    // Collect form data
                    var formData = new FormData(form);
                    
                    // Send AJAX request
                    fetch(wp_alp_ajax.ajax_url, {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        if (data.success) {
                            // Display success message
                            messagesContainer.innerHTML = '<div class="wp-alp-message wp-alp-message-success">' + 
                                data.data.message + '</div>';
                            
                            // Redirect if needed
                            if (data.data.redirect) {
                                setTimeout(function() {
                                    window.location.href = data.data.redirect;
                                }, 1000);
                            }
                        } else {
                            // Display error message
                            messagesContainer.innerHTML = '<div class="wp-alp-message wp-alp-message-error">' + 
                                data.data.message + '</div>';
                            
                            // Re-enable submit button
                            submitButton.disabled = false;
                            submitButton.classList.remove('wp-alp-button-loading');
                        }
                    })
                    .catch(function(error) {
                        // Display error message
                        messagesContainer.innerHTML = '<div class="wp-alp-message wp-alp-message-error">' + 
                            '<?php esc_html_e('An error occurred. Please try again.', 'wp-alp'); ?>' + '</div>';
                        
                        // Re-enable submit button
                        submitButton.disabled = false;
                        submitButton.classList.remove('wp-alp-button-loading');
                        
                        console.error('Error:', error);
                    });
                });
            }
        }
    })();
</script>