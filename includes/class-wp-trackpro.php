<?php
/**
 * WP TrackPro setup
 *
 * @package WPTrackPro
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

class WP_TrackPro
{
    public function __construct()
    {
        add_action('init', array('WTP_Shortcodes', 'init'));
        add_action('wp_enqueue_scripts', array($this, 'wtp_enqueue_script'));
        $this->includes();
    }

    public function includes()
    {
        /**
         * Core classes.
         */
        include_once WP_TRACKPRO_PLUGIN_PATH . 'includes/class-wtp-post-types.php';
        include_once WP_TRACKPRO_PLUGIN_PATH . 'includes/class-wtp-shortcodes.php';
        include_once WP_TRACKPRO_PLUGIN_PATH . 'includes/wtp-custom-functions.php';
        /**
         * Admin classes.
         */
        include_once WP_TRACKPRO_PLUGIN_PATH . 'admin/includes/class-wp-trackpro-admin.php';

    }

    public function wtp_enqueue_script()
    {
        global $post, $post_type;

        if (has_shortcode($post->post_content, 'wtp-track-shipment') && (is_single() || is_page())) {
            wp_register_style('wtp_admin_styles', WP_TRACKPRO_PLUGIN_URL . 'assets/css/wtp-styles.css', array(), WP_TRACKPRO_VERSION);
            wp_enqueue_style('wtp_admin_styles');
        }

    }

}

return new WP_TrackPro;