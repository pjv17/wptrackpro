<?php
/**
 * Custom Admin Table Columns
 *
 * @package WP TrackPro\Classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * WP TrackPro Custom Admin Table Columns class.
 */

if (!class_exists('WTP_Custom_Column_Tables')):

    class WTP_Custom_Column_Tables
    {
        public function __construct()
        {
            add_filter('manage_wp_trackpro_posts_columns', array($this, 'wtp_custom_columns'));
            add_action('manage_wp_trackpro_posts_custom_column', array($this, 'wtp_fill_custom_columns'));
            add_filter('post_row_actions', array($this, 'wtp_remove_row_actions'), 10, 1);
        }

        private function wtp_shipment_history($post_id)
        {
            global $wpdb;
            $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wtp_shipment_history WHERE post_id = {$post_id} ORDER BY date_time DESC LIMIT 1", OBJECT);
            return $results;
        }

        public function wtp_custom_columns($columns = null)
        {
            $columns['title'] = 'Tracking Code';
            $columns['wtp_sender_name'] = 'Sender';
            $columns['wtp_sender_email'] = 'Email';
            $columns['wtp_sender_phone'] = 'Phone';
            $columns['wtp_shipment_status'] = 'Status';
            $columns['wtp_publish_date'] = 'Date';
            unset($columns['date']);
            return $columns;
        }

        function wtp_fill_custom_columns($column_name = null)
        {
            global $post_id;
            $wtp_shipment_history = $this->wtp_shipment_history(get_the_ID());
            $get_latest_status = 'N/A';
            $get_first_val = 0;
            if ($wtp_shipment_history) {
                foreach ($wtp_shipment_history as $shipment_history) {
                    $decode_sh = json_decode($shipment_history->prod_values);
                    foreach ($decode_sh as $key => $value) {
                        if ($key == 'wtp-shipment-status') {
                            $get_latest_status = $value;
                        }
                    }
                }
            }

            if ($column_name == 'title') {
                echo get_the_title(get_the_ID());
            }
            if ($column_name == 'wtp_sender_name') {
                echo get_post_meta(get_the_ID(), 'wtp-sender-name', true);
            }
            if ($column_name == 'wtp_sender_email') {
                echo get_post_meta(get_the_ID(), 'wtp-sender-email', true);
            }
            if ($column_name == 'wtp_sender_phone') {
                echo get_post_meta(get_the_ID(), 'wtp-sender-phone', true);
            }
            if ($column_name == 'wtp_shipment_status') {
                echo $get_latest_status;
            }
            if ($column_name == 'wtp_publish_date') {
                echo get_the_date("F j, Y", get_the_ID());
            }
        }

        function wtp_remove_row_actions($actions)
        {
            if (get_post_type() === 'wp_trackpro')
                unset($actions['view']);
            unset($actions['inline hide-if-no-js']);
            return $actions;
        }
    }
    return new WTP_Custom_Column_Tables;


endif;