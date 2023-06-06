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
        $this->includes();
    }

    public function includes()
    {
        /**
         * Core classes.
         */
        include_once WP_TRACKPRO_PLUGIN_PATH . 'includes/class-wtp-post-types.php';
        include_once WP_TRACKPRO_PLUGIN_PATH . 'includes/class-wtp-shortcodes.php';

        /**
         * Admin classes.
         */
        include_once WP_TRACKPRO_PLUGIN_PATH . 'admin/includes/class-wp-trackpro-admin.php';

    }

}

return new WP_TrackPro;