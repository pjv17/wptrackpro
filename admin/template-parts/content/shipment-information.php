<?php
/**
 * Shipment Information Template
 *
 * @package WP TrackPro\Template Parts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;
global $post;
$wtp_settings = get_option('wp_trackpro_options');
?>

<div id="wtp-shipment-information">

    <div class="wtp-row">
        <div class="wtp-fields wtp-field-total-weight">
            <label>
                <?php _e('Total Weight(KG)', 'wptrackpro'); ?>
            </label>
            <input type="number" name="wtp-total-weight"
                value="<?php echo get_post_meta($post->ID, 'wtp-total-weight', true); ?>">
        </div>
        <div class="wtp-fields wtp-field-shipping-mode">
            <label>
                <?php _e('Shipping Mode', 'wptrackpro'); ?>
            </label>
            <select name="wtp-shipping-mode">
                <?php
                if ($wtp_settings) {
                    $wtp_shipping_mode = explode(",", $wtp_settings['wtp_settings_shipping_mode']);
                    foreach ($wtp_shipping_mode as $shipping_mode) {
                        if (get_post_meta($post->ID, 'wtp-shipping-mode', true) == trim($shipping_mode)) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        echo '<option value="' . trim($shipping_mode) . '" ' . $selected . '>' . trim($shipping_mode) . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="wtp-fields wtp-field-reference-number">
            <label>
                <?php _e('Reference Number', 'wptrackpro'); ?>
            </label>
            <input type="text" name="wtp-reference-number"
                value="<?php echo get_post_meta($post->ID, 'wtp-reference-number', true); ?>">
        </div>
    </div>

    <div class="wtp-row">
        <div class="wtp-fields wtp-field-instructions">
            <label>
                <?php _e('Instructions', 'wptrackpro'); ?>
            </label>
            <textarea name="wtp-field-instructions"
                rows="10"><?php echo get_post_meta($post->ID, 'wtp-field-instructions', true); ?></textarea>
        </div>
    </div>

    <div class="wtp-row">

        <div class="wtp-fields wtp-field-pickup-time">
            <label>
                <?php _e('Pickup Time', 'wptrackpro'); ?>
            </label>
            <input type="time" name="wtp-pickup-time"
                value="<?php echo get_post_meta($post->ID, 'wtp-pickup-time', true); ?>">
        </div>

        <div class="wtp-fields wtp-field-pickup-date">
            <label>
                <?php _e('Pickup Date', 'wptrackpro'); ?>
            </label>
            <input type="date" name="wtp-pickup-date"
                value="<?php echo get_post_meta($post->ID, 'wtp-pickup-date', true); ?>">
        </div>

    </div>

</div>