<?php
/**
 * Shipment Results
 *
 * @package WP TrackPro\Template Parts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

if (!isset($_REQUEST['wtp-tracking-code'])) {
    ?>
    <div class="wtp-form">
        <form method="GET">
            <label for="wtp-tracking-input-field">
                <?php echo _e(apply_filters('wpt-tracking-form-label', 'Track'), 'wp-trackpro'); ?>
            </label>
            <input id="wtp-tracking-input-field" name="wtp-tracking-code" type="text" placeholder="Input Tracking Code">
            <input type="submit" value="Track">
        </form>
    </div>
    <?php
}