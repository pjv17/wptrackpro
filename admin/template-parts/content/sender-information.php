<?php
/**
 * Sender Information Template
 *
 * @package WP TrackPro\Template Parts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;
global $post;
?>

<div id="wtp-sender-information">

    <div class="wtp-row">
        <div class="wtp-fields wtp-field-name">
            <label>
                <?php _e('Name', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-sender-name"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-name', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-phone">
            <label>
                <?php _e('Phone', 'wp-trackpro'); ?>
            </label>
            <input type="phone" name="wtp-sender-phone"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-phone', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-email">
            <label>
                <?php _e('Email', 'wp-trackpro'); ?>
            </label>
            <input type="email" name="wtp-sender-email"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-email', true); ?>">
        </div>
    </div>

    <div class="wtp-row">
        <div class="wtp-fields wtp-field-street">
            <label>
                <?php _e('Street Address', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-sender-street"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-street', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-city">
            <label>
                <?php _e('City', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-sender-city"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-city', true); ?>">
        </div>
    </div>

    <div class="wtp-row">
        <div class="wtp-fields wtp-field-country">
            <label>
                <?php _e('Country', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-sender-country"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-country', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-state">
            <label>
                <?php _e('State', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-sender-state"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-state', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-zipcode">
            <label>
                <?php _e('Zip code', 'wp-trackpro'); ?>
            </label>
            <input type="text" name="wtp-sender-zipcode"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-zipcode', true); ?>">
        </div>
    </div>

</div>