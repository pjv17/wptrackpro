<?php

/**
 * WP TrackPro Admin
 *
 * @package WP TrackPro\Classes
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

/**
 * WP TrackPro Admin
 */

class WTP_Database
{
    public function __construct()
    {
        register_activation_hook(WP_TRACKPRO_PLUGIN_FILE, array($this, 'create_product_info_database'));
        register_activation_hook(WP_TRACKPRO_PLUGIN_FILE, array($this, 'create_shipment_history_database'));
    }

    public function create_product_info_database()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'wtp_product_information';
        $sql = "CREATE TABLE `$table_name` (
        `prod_info_id` bigint(20) NOT NULL auto_increment,
        `post_id` bigint(20) DEFAULT NULL,
        `prod_values` varchar(255) DEFAULT NULL,
        PRIMARY KEY(prod_info_id)
        ) $charset_collate;";

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public function create_shipment_history_database()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'wtp_shipment_history';
        $sql = "CREATE TABLE `$table_name` (
        `sh_id` bigint(20) NOT NULL auto_increment,
        `post_id` bigint(20) DEFAULT NULL,
        `prod_values` varchar(255) DEFAULT NULL,
        PRIMARY KEY(sh_id)
        ) $charset_collate;";

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
}

return new WTP_Database;