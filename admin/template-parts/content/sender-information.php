<?php
/**
 * Sender Information Template
 *
 * @package WP TrackPro\Template Parts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;
global $post;
$wtp_settings = get_option('wp_trackpro_options');
?>

<div id="wtp-sender-information">

    <div class="wtp-row">
        <div class="wtp-fields wtp-field-name">
            <label>
                <?php _e('Name', 'wptrackpro'); ?>
            </label>
            <input type="text" name="wtp-sender-name"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-name', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-phone">
            <label>
                <?php _e('Phone', 'wptrackpro'); ?>
            </label>
            <input type="phone" name="wtp-sender-phone"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-phone', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-email">
            <label>
                <?php _e('Email', 'wptrackpro'); ?>
            </label>
            <input type="email" name="wtp-sender-email"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-email', true); ?>">
        </div>
    </div>

    <div class="wtp-row">
        <div class="wtp-fields wtp-field-street">
            <label>
                <?php _e('Street Address', 'wptrackpro'); ?>
            </label>
            <input type="text" name="wtp-sender-street"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-street', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-city">
            <label>
                <?php _e('City', 'wptrackpro'); ?>
            </label>
            <input type="text" name="wtp-sender-city"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-city', true); ?>">
        </div>
    </div>

    <div class="wtp-row">
        <div class="wtp-fields wtp-field-country">
            <label>
                <?php _e('Country', 'wptrackpro'); ?>
            </label>
            <select name="wtp-sender-country">
                <option value="" required>
                    <?php _e("Select Country", 'wptrackpro'); ?>
                </option>
                <?php
                if ($wtp_settings) {
                    $wtp_countries = explode(",", $wtp_settings['wtp_settings_countries']);
                    foreach ($wtp_countries as $country) {
                        if (get_post_meta($post->ID, 'wtp-sender-country', true) == trim($country)) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        echo '<option value="' . trim($country) . '" ' . $selected . '>' . trim($country) . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="wtp-fields wtp-field-state">
            <label>
                <?php _e('State', 'wptrackpro'); ?>
            </label>
            <input type="text" name="wtp-sender-state"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-state', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-zipcode">
            <label>
                <?php _e('Zip code', 'wptrackpro'); ?>
            </label>
            <input type="text" name="wtp-sender-zipcode"
                value="<?php echo get_post_meta($post->ID, 'wtp-sender-zipcode', true); ?>">
        </div>
    </div>

</div>