<?php
/**
 * Receiver Information Template
 *
 * @package WP TrackPro\Template Parts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;
global $post;
?>

<div id="wtp-receiver-information">

    <div class="wtp-row">
        <div class="wtp-fields wtp-field-name">
            <label>
                <?php _e('Name', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-receiver-name"
                value="<?php echo get_post_meta($post->ID, 'wtp-receiver-name', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-phone">
            <label>
                <?php _e('Phone', 'wp-trackpro'); ?>
            </label>
            <input type="phone" name="wtp-receiver-phone"
                value="<?php echo get_post_meta($post->ID, 'wtp-receiver-phone', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-email">
            <label>
                <?php _e('Email', 'wp-trackpro'); ?>
            </label>
            <input type="email" name="wtp-receiver-email"
                value="<?php echo get_post_meta($post->ID, 'wtp-receiver-email', true); ?>">
        </div>
    </div>

    <div class="wtp-row">
        <div class="wtp-fields wtp-field-street">
            <label>
                <?php _e('Street Address', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-receiver-street"
                value="<?php echo get_post_meta($post->ID, 'wtp-receiver-street', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-city">
            <label>
                <?php _e('City', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-receiver-city"
                value="<?php echo get_post_meta($post->ID, 'wtp-receiver-city', true); ?>">
        </div>
    </div>

    <div class="wtp-row">
        <div class="wtp-fields wtp-field-country">
            <label>
                <?php _e('Country', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-receiver-country"
                value="<?php echo get_post_meta($post->ID, 'wtp-receiver-country', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-state">
            <label>
                <?php _e('State', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-receiver-state"
                value="<?php echo get_post_meta($post->ID, 'wtp-receiver-state', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-zipcode">
            <label>
                <?php _e('Zip code', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-receiver-zipcode"
                value="<?php echo get_post_meta($post->ID, 'wtp-receiver-zipcode', true); ?>">
        </div>
    </div>

</div>