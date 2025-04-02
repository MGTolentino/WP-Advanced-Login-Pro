/**
 * Admin JavaScript for WP Advanced Login Pro
 *
 * @since      1.0.0
 * @package    WP_Advanced_Login_Pro
 * @subpackage WP_Advanced_Login_Pro/admin/js
 */

(function($) {
    'use strict';

    /**
     * Initialize the admin functionality
     */
    function init() {
        // Initialize tabs
        initTabs();
        
        // Initialize copy to clipboard
        initCopyToClipboard();
        
        // Initialize toggles
        initToggles();
    }

    /**
     * Initialize tabs functionality
     */
    function initTabs() {
        $('.wp-alp-tabs-nav a').on('click', function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var target = $this.data('target');
            
            // Update active tab
            $('.wp-alp-tabs-nav a').removeClass('active');
            $this.addClass('active');
            
            // Show target tab
            $('.wp-alp-tab-content').hide();
            $('#' + target).show();
            
            // Update URL hash
            if (history.pushState) {
                history.pushState(null, null, '#' + target);
            } else {
                location.hash = '#' + target;
            }
        });
        
        // Check if URL has hash
        var hash = window.location.hash;
        if (hash) {
            hash = hash.substring(1);
            $('.wp-alp-tabs-nav a[data-target="' + hash + '"]').trigger('click');
        } else {
            // Show first tab by default
            $('.wp-alp-tabs-nav a:first').trigger('click');
        }
    }

    /**
     * Initialize copy to clipboard functionality
     */
    function initCopyToClipboard() {
        $('.wp-alp-copy-to-clipboard').on('click', function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var text = $this.data('copy');
            var $temp = $('<input>');
            
            $('body').append($temp);
            $temp.val(text).select();
            document.execCommand('copy');
            $temp.remove();
            
            // Show copied message
            var originalText = $this.text();
            $this.text(wp_alp_admin.copied_text);
            
            setTimeout(function() {
                $this.text(originalText);
            }, 2000);
        });
    }

    /**
     * Initialize toggle fields
     */
    function initToggles() {
        // Google toggle
        $('#wp-alp-google\\[enabled\\]').on('change', function() {
            var isChecked = $(this).is(':checked');
            toggleFieldsVisibility('google', isChecked);
        });
        toggleFieldsVisibility('google', $('#wp-alp-google\\[enabled\\]').is(':checked'));
        
        // Facebook toggle
        $('#wp-alp-facebook\\[enabled\\]').on('change', function() {
            var isChecked = $(this).is(':checked');
            toggleFieldsVisibility('facebook', isChecked);
        });
        toggleFieldsVisibility('facebook', $('#wp-alp-facebook\\[enabled\\]').is(':checked'));
        
        // Apple toggle
        $('#wp-alp-apple\\[enabled\\]').on('change', function() {
            var isChecked = $(this).is(':checked');
            toggleFieldsVisibility('apple', isChecked);
        });
        toggleFieldsVisibility('apple', $('#wp-alp-apple\\[enabled\\]').is(':checked'));
        
        // LinkedIn toggle
        $('#wp-alp-linkedin\\[enabled\\]').on('change', function() {
            var isChecked = $(this).is(':checked');
            toggleFieldsVisibility('linkedin', isChecked);
        });
        toggleFieldsVisibility('linkedin', $('#wp-alp-linkedin\\[enabled\\]').is(':checked'));
    }

    /**
     * Toggle fields visibility based on provider state
     * 
     * @param {string} provider The provider name
     * @param {boolean} show Whether to show or hide fields
     */
    function toggleFieldsVisibility(provider, show) {
        var $fields = $('tr.wp-alp-' + provider + '-field');
        
        if (show) {
            $fields.show();
        } else {
            $fields.hide();
        }
    }

    // Initialize when the DOM is ready
    $(document).ready(init);

})(jQuery);