<?php
/**
 * Shortcodes
 *
 * @package WP TrackPro\Classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * WP TrackPro Shortcodes class.
 */

class WTP_Shortcodes
{

    /**
     * Init shortcodes.
     */
    public static function init()
    {
        $shortcodes = array(
            'product' => __CLASS__ . '::product',
        );

        foreach ($shortcodes as $shortcode => $function) {
            add_shortcode($shortcode, $function);
        }

    }
}