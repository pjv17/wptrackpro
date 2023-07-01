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
            'wtp-track-shipment' => __CLASS__ . '::track_shipment',
        );

        foreach ($shortcodes as $shortcode => $function) {
            add_shortcode($shortcode, $function);
        }
    }

    private function wtp_search_shipment_query($wtp_tracking_code)
    {
        global $post, $wpdb;

        $args = array(
            'post_type' => 'wp_trackpro',
            'wtp_tracking_code' => $wtp_tracking_code,
            'post_status' => 'publish',
        );
        $wtp_shortcode = new WTP_Shortcodes;
        add_filter('posts_where', 'wtp_additional_where_clause', 10, 2);
        $shipment_results = new WP_Query($args);
        remove_filter('posts_where', 'wtp_additional_where_clause');
        return $shipment_results;
    }

    private function wtp_shipment_history($post_id)
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wtp_shipment_history WHERE post_id = {$post_id} ORDER BY date_time DESC", OBJECT);
        return $results;
    }

    public function wtp_sh_display_fields_frontend()
    {
        $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-shipment-history.json');
        $decode_wtp_fields = json_decode($wtp_fields_json);
        $get_display_field_name = [];
        if ($decode_wtp_fields) {
            foreach ($decode_wtp_fields as $wtp_field) {
                if ($wtp_field->display_frontend == 1) {
                    $get_display_field_name[] = $wtp_field->name;
                }
            }
        }
        return $get_display_field_name;
    }

    public static function track_shipment()
    {
        global $post, $wpdb;
        ob_start();

        $wtp_queries = new WTP_Shortcodes;
        $wtp_tracking_code = '';
        $shipment_results = '';
        if (isset($_GET['wtp-tracking-code'])) {
            $wtp_tracking_code = $_GET['wtp-tracking-code'];
            $shipment_results = $wtp_queries->wtp_search_shipment_query($wtp_tracking_code);
            $wtp_sh_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-shipment-history.json');
            $display_fields_fe = $wtp_queries->wtp_sh_display_fields_frontend();
        }

        $get_wptrackpro_theme_directory = get_stylesheet_directory() . '/wptrackpro';
        $check_theme_template_form = false;
        $check_theme_template_results = false;
        if (is_dir($get_wptrackpro_theme_directory)) {
            $get_wptrackpro_theme_directory_form = $get_wptrackpro_theme_directory . '/wtp-track-shipment-form.php';
            if (file_exists($get_wptrackpro_theme_directory_form)) {
                $check_theme_template_form = true;
                include_once($get_wptrackpro_theme_directory_form);
            }
            $get_wptrackpro_theme_directory_results = $get_wptrackpro_theme_directory . '/wtp-track-shipment-results.php';
            if (file_exists($get_wptrackpro_theme_directory_results)) {
                $check_theme_template_results = true;
                include_once($get_wptrackpro_theme_directory_results);
            }
        }

        if ($check_theme_template_form == false) {
            include_once(WP_TRACKPRO_PLUGIN_PATH . 'templates/wtp-track-shipment-form.php');
        }

        if ($check_theme_template_results == false) {
            include_once(WP_TRACKPRO_PLUGIN_PATH . 'templates/wtp-track-shipment-results.php');
        }

        return ob_get_clean();
    }

}