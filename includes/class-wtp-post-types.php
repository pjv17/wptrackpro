<?php
/**
 * Post Types
 *
 * @package WP TrackPro\Classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * WP TrackPro Post Types class.
 */

if (!class_exists('WTP_Custom_Post_Types')):
    class WTP_Custom_Post_Types
    {

        public function __construct()
        {
            add_action('init', array($this, 'init_hooks'), 0);
        }

        function init_hooks()
        {
            $this->wtp_trackpro_post_type();
        }

        // Company Post Type
        public function wtp_trackpro_post_type()
        {
            $labels = array(
                'name' => _x('All Shipment', 'Post Type General Name', 'wp-trackpro'),
                'singular_name' => _x('WP TrackPro', 'Post Type Singular Name', 'wp-trackpro'),
                'menu_name' => __('WP TrackPro', 'wp-trackpro'),
                'name_admin_bar' => __('WP TrackPro', 'wp-trackpro'),
                'archives' => __('Item Archives', 'wp-trackpro'),
                'attributes' => __('Item Attributes', 'wp-trackpro'),
                'parent_item_colon' => __('Parent Item:', 'wp-trackpro'),
                'all_items' => __('All Shipment', 'wp-trackpro'),
                'add_new_item' => __('Add New Shipment', 'wp-trackpro'),
                'add_new' => __('Add New Shipment', 'wp-trackpro'),
                'new_item' => __('New Shipment', 'wp-trackpro'),
                'edit_item' => __('Edit Shipment', 'wp-trackpro'),
                'update_item' => __('Update Shipment', 'wp-trackpro'),
                'view_item' => __('View Shipment', 'wp-trackpro'),
                'view_items' => __('View Shipments', 'wp-trackpro'),
                'search_items' => __('Search Shipment', 'wp-trackpro'),
                'not_found' => __('Not found', 'wp-trackpro'),
                'not_found_in_trash' => __('Not found in Trash', 'wp-trackpro'),
                'featured_image' => __('Featured Image', 'wp-trackpro'),
                'set_featured_image' => __('Set featured image', 'wp-trackpro'),
                'remove_featured_image' => __('Remove featured image', 'wp-trackpro'),
                'use_featured_image' => __('Use as featured image', 'wp-trackpro'),
                'insert_into_item' => __('Insert into item', 'wp-trackpro'),
                'uploaded_to_this_item' => __('Uploaded to this item', 'wp-trackpro'),
                'items_list' => __('Items list', 'wp-trackpro'),
                'items_list_navigation' => __('Items list navigation', 'wp-trackpro'),
                'filter_items_list' => __('Filter items list', 'wp-trackpro'),
            );
            $args = array(
                'label' => __('WP TrackPro', 'wp-trackpro'),
                'description' => __('WP TrackPro is the ultimate Parcel Delivery Plugin for WordPress.', 'wp-trackpro'),
                'labels' => $labels,
                'supports' => array('title'),
                'taxonomies' => array(),
                'hierarchical' => false,
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_position' => 5,
                'menu_icon' => WP_TRACKPRO_PLUGIN_URL . 'assets/images/parcel.png',
                'show_in_admin_bar' => true,
                'show_in_nav_menus' => true,
                'can_export' => true,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'capability_type' => 'post',
                'show_in_rest' => true,
            );
            register_post_type('wp_trackpro', $args);
        }
    }
    return new WTP_Custom_Post_Types();
endif;