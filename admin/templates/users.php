<?php
/**
 * Template for the users and leads page.
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/admin/templates
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if JetEngine is active
$jetengine_active = function_exists('jet_engine');
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="wp-alp-admin-wrapper">
        <div class="wp-alp-admin-header">
            <h2 class="nav-tab-wrapper">
                <a href="?page=wp-advanced-login-pro" class="nav-tab"><?php esc_html_e('General', 'wp-alp'); ?></a>
                <a href="?page=wp-advanced-login-pro-security" class="nav-tab"><?php esc_html_e('Security', 'wp-alp'); ?></a>
                <a href="?page=wp-advanced-login-pro-social" class="nav-tab"><?php esc_html_e('Social Login', 'wp-alp'); ?></a>
                <a href="?page=wp-advanced-login-pro-users" class="nav-tab nav-tab-active"><?php esc_html_e('Users & Leads', 'wp-alp'); ?></a>
            </h2>
        </div>
        
        <div class="wp-alp-admin-content">
            <div class="wp-alp-admin-section">
                <h2><?php esc_html_e('User Types Overview', 'wp-alp'); ?></h2>
                
                <div class="wp-alp-user-types-grid">
                    <div class="wp-alp-user-type-card">
                        <h3><?php esc_html_e('Regular Users', 'wp-alp'); ?></h3>
                        <p><?php esc_html_e('Basic users with subscriber role.', 'wp-alp'); ?></p>
                        <p><strong><?php esc_html_e('Registration Form:', 'wp-alp'); ?></strong> [wp_alp_register_user]</p>
                        <?php
                        // Count regular users
                        $regular_users_count = count_users();
                        $subscriber_count = isset($regular_users_count['avail_roles']['subscriber']) ? $regular_users_count['avail_roles']['subscriber'] : 0;
                        ?>
                        <p><strong><?php esc_html_e('Count:', 'wp-alp'); ?></strong> <?php echo esc_html($subscriber_count); ?></p>
                    </div>
                    
                    <div class="wp-alp-user-type-card">
                        <h3><?php esc_html_e('Leads', 'wp-alp'); ?></h3>
                        <p><?php esc_html_e('Users who have completed their profile with event information.', 'wp-alp'); ?></p>
                        <p><strong><?php esc_html_e('Profile Form:', 'wp-alp'); ?></strong> [wp_alp_profile_completion]</p>
                        <?php
                        // Count leads if JetEngine is active
                        $leads_count = 0;
                        if ($jetengine_active) {
                            global $wpdb;
                            $cct_table = $wpdb->prefix . 'jet_cct_leads';
                            if ($wpdb->get_var("SHOW TABLES LIKE '$cct_table'") === $cct_table) {
                                $leads_count = $wpdb->get_var("SELECT COUNT(*) FROM $cct_table");
                            }
                        }
                        ?>
                        <p><strong><?php esc_html_e('Count:', 'wp-alp'); ?></strong> <?php echo esc_html($leads_count); ?></p>
                        <?php if ($jetengine_active) : ?>
                            <a href="<?php echo admin_url('admin.php?page=jet-engine-cct-leads'); ?>" class="button button-secondary">
                                <?php esc_html_e('View Leads', 'wp-alp'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="wp-alp-user-type-card">
                        <h3><?php esc_html_e('Vendors', 'wp-alp'); ?></h3>
                        <p><?php esc_html_e('Users who can create listings and provide services.', 'wp-alp'); ?></p>
                        <p><strong><?php esc_html_e('Registration Form:', 'wp-alp'); ?></strong> [wp_alp_register_vendor]</p>
                        <?php
                        // Count vendors
                        $vendors_count = 0;
                        if (post_type_exists('hp_vendor')) {
                            $vendors_count = wp_count_posts('hp_vendor')->publish;
                        }
                        ?>
                        <p><strong><?php esc_html_e('Count:', 'wp-alp'); ?></strong> <?php echo esc_html($vendors_count); ?></p>
                        <?php if (post_type_exists('hp_vendor')) : ?>
                            <a href="<?php echo admin_url('edit.php?post_type=hp_vendor'); ?>" class="button button-secondary">
                                <?php esc_html_e('View Vendors', 'wp-alp'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="wp-alp-admin-section">
                <h2><?php esc_html_e('User Management', 'wp-alp'); ?></h2>
                
                <div class="wp-alp-admin-card">
                    <h3><?php esc_html_e('Social Connections', 'wp-alp'); ?></h3>
                    <p><?php esc_html_e('Users can connect their accounts with social media platforms for easier login.', 'wp-alp'); ?></p>
                    
                    <?php
                    // Count social connections
                    global $wpdb;
                    $social_table = $wpdb->prefix . 'wp_alp_social_profiles';
                    $social_count = 0;
                    
                    if ($wpdb->get_var("SHOW TABLES LIKE '$social_table'") === $social_table) {
                        $social_count = $wpdb->get_var("SELECT COUNT(*) FROM $social_table");
                    }
                    ?>
                    
                    <p><strong><?php esc_html_e('Social Connections:', 'wp-alp'); ?></strong> <?php echo esc_html($social_count); ?></p>
                </div>
                
                <div class="wp-alp-admin-card">
                    <h3><?php esc_html_e('User to Lead Conversion', 'wp-alp'); ?></h3>
                    <p><?php esc_html_e('Normal users can be converted to leads when they complete their profile.', 'wp-alp'); ?></p>
                    
                    <form method="post" action="" class="wp-alp-admin-form">
                        <?php wp_nonce_field('wp_alp_convert_users_nonce', 'wp_alp_nonce'); ?>
                        <p>
                            <label for="wp-alp-user-to-lead">
                                <?php esc_html_e('Send profile completion reminder to users without leads:', 'wp-alp'); ?>
                            </label>
                        </p>
                        <button type="submit" name="wp_alp_send_reminder" class="button button-primary">
                            <?php esc_html_e('Send Reminder Email', 'wp-alp'); ?>
                        </button>
                    </form>
                </div>
            </div>
            
            <?php if ($jetengine_active) : ?>
            <div class="wp-alp-admin-section">
                <h2><?php esc_html_e('Event Statistics', 'wp-alp'); ?></h2>
                
                <div class="wp-alp-stats-grid">
                    <?php
                    // Get event statistics
                    global $wpdb;
                    $eventos_table = $wpdb->prefix . 'jet_cct_eventos';
                    
                    if ($wpdb->get_var("SHOW TABLES LIKE '$eventos_table'") === $eventos_table) {
                        // Event types count
                        $event_types = $wpdb->get_results("
                            SELECT `Tipo de Evento` as type, COUNT(*) as count 
                            FROM $eventos_table 
                            GROUP BY `Tipo de Evento` 
                            ORDER BY count DESC
                            LIMIT 5
                        ");
                        
                        if (!empty($event_types)) {
                            ?>
                            <div class="wp-alp-stats-card">
                                <h3><?php esc_html_e('Popular Event Types', 'wp-alp'); ?></h3>
                                <ul>
                                    <?php foreach ($event_types as $event_type) : ?>
                                        <li>
                                            <strong><?php echo esc_html($event_type->type); ?>:</strong>
                                            <?php echo esc_html($event_type->count); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php
                        }
                        
                        // Status count
                        $status_counts = $wpdb->get_results("
                            SELECT status, COUNT(*) as count 
                            FROM $eventos_table 
                            GROUP BY status
                        ");
                        
                        if (!empty($status_counts)) {
                            ?>
                            <div class="wp-alp-stats-card">
                                <h3><?php esc_html_e('Event Status', 'wp-alp'); ?></h3>
                                <ul>
                                    <?php foreach ($status_counts as $status) : ?>
                                        <li>
                                            <strong><?php echo esc_html($status->status); ?>:</strong>
                                            <?php echo esc_html($status->count); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="wp-alp-admin-sidebar">
            <div class="wp-alp-admin-box">
                <h3><?php esc_html_e('User Flow', 'wp-alp'); ?></h3>
                <p><?php esc_html_e('WP Advanced Login Pro manages three main user types:', 'wp-alp'); ?></p>
                <ol>
                    <li>
                        <strong><?php esc_html_e('Regular Users', 'wp-alp'); ?></strong><br>
                        <?php esc_html_e('Basic WordPress users with the subscriber role.', 'wp-alp'); ?>
                    </li>
                    <li>
                        <strong><?php esc_html_e('Leads', 'wp-alp'); ?></strong><br>
                        <?php esc_html_e('Users who have completed their profile with event information. Stored in JetEngine CCT.', 'wp-alp'); ?>
                    </li>
                    <li>
                        <strong><?php esc_html_e('Vendors', 'wp-alp'); ?></strong><br>
                        <?php esc_html_e('Service providers who can create listings. Uses the hp_vendor post type.', 'wp-alp'); ?>
                    </li>
                </ol>
                
                <h3><?php esc_html_e('Integration with JetEngine', 'wp-alp'); ?></h3>
                <p><?php esc_html_e('This plugin connects WordPress users with JetEngine CCTs:', 'wp-alp'); ?></p>
                <ul>
                    <li><?php esc_html_e('WordPress User → Lead CCT', 'wp-alp'); ?></li>
                    <li><?php esc_html_e('Lead CCT → Event CCT', 'wp-alp'); ?></li>
                </ul>
                
                <?php if (!$jetengine_active) : ?>
                    <div class="wp-alp-admin-notice wp-alp-admin-notice-warning">
                        <p>
                            <strong><?php esc_html_e('JetEngine Not Detected', 'wp-alp'); ?></strong><br>
                            <?php esc_html_e('JetEngine is required for lead management functionality.', 'wp-alp'); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>