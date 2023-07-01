<?php
/**
 * Custom Functions
 *
 * @package WP TrackPro\Classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

function wtp_additional_where_clause($where, $wp_query)
{
    global $wpdb;
    if ($title = $wp_query->get('wtp_tracking_code')) {
        $where .= " AND " . $wpdb->posts . ".post_title = '" . esc_sql($wpdb->esc_like($title)) . "'";
    }
    return $where;
}