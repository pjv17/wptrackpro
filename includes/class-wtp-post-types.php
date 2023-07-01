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
                'name' => _x('All Shipment', 'Post Type General Name', 'wptrackpro'),
                'singular_name' => _x('WP TrackPro', 'Post Type Singular Name', 'wptrackpro'),
                'menu_name' => __('WP TrackPro', 'wptrackpro'),
                'name_admin_bar' => __('WP TrackPro', 'wptrackpro'),
                'archives' => __('Item Archives', 'wptrackpro'),
                'attributes' => __('Item Attributes', 'wptrackpro'),
                'parent_item_colon' => __('Parent Item:', 'wptrackpro'),
                'all_items' => __('All Shipment', 'wptrackpro'),
                'add_new_item' => __('Add New Shipment', 'wptrackpro'),
                'add_new' => __('Add New Shipment', 'wptrackpro'),
                'new_item' => __('New Shipment', 'wptrackpro'),
                'edit_item' => __('Edit Shipment', 'wptrackpro'),
                'update_item' => __('Update Shipment', 'wptrackpro'),
                'view_item' => __('View Shipment', 'wptrackpro'),
                'view_items' => __('View Shipments', 'wptrackpro'),
                'search_items' => __('Search Shipment', 'wptrackpro'),
                'not_found' => __('Not found', 'wptrackpro'),
                'not_found_in_trash' => __('Not found in Trash', 'wptrackpro'),
                'featured_image' => __('Featured Image', 'wptrackpro'),
                'set_featured_image' => __('Set featured image', 'wptrackpro'),
                'remove_featured_image' => __('Remove featured image', 'wptrackpro'),
                'use_featured_image' => __('Use as featured image', 'wptrackpro'),
                'insert_into_item' => __('Insert into item', 'wptrackpro'),
                'uploaded_to_this_item' => __('Uploaded to this item', 'wptrackpro'),
                'items_list' => __('Items list', 'wptrackpro'),
                'items_list_navigation' => __('Items list navigation', 'wptrackpro'),
                'filter_items_list' => __('Filter items list', 'wptrackpro'),
            );
            $args = array(
                'label' => __('WP TrackPro', 'wptrackpro'),
                'description' => __('WP TrackPro is the ultimate Parcel Delivery Plugin for WordPress.', 'wptrackpro'),
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