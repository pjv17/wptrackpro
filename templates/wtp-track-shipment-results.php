<?php
/**
 * Shipment Tracking Results
 *
 * @package WP TrackPro\Template Parts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

if (isset($_REQUEST['wtp-tracking-code'])) {

    ?>
    <div class="wtp-shipment-results">
        <?php
        if ($shipment_results->have_posts()) {
            while ($shipment_results->have_posts()):
                $shipment_results->the_post();
                $get_shipment_history = $wtp_queries->wtp_shipment_history(get_the_ID());
                $get_latest_status = '';
                $get_first_val = 0;
                if ($get_shipment_history) {
                    foreach ($get_shipment_history as $shipment_history) {
                        $decode_sh = json_decode($shipment_history->prod_values);
                        foreach ($decode_sh as $key => $value) {
                            if ($get_first_val == 0) {
                                if ($key == 'wtp-shipment-status') {
                                    $get_latest_status = $value;
                                }
                            }
                        }
                    }
                }
                ?>
                <div class="wtp-shipment-details-wrap">

                    <div class="wtp-shipment-detail wtp-tracking-code">
                        <p><strong>
                                <?php _e('Tracking Code: ', 'wp-trackpro'); ?>
                            </strong>
                            <?php echo get_the_title(); ?>
                        </p>
                    </div>

                    <div class="wtp-shipment-detail wtp-latest-status">
                        <p><strong>
                                <?php _e('Status: ', 'wp-trackpro'); ?>
                            </strong>
                            <?php echo $get_latest_status; ?>
                        </p>
                    </div>

                    <div class="wtp-shipment-detail wtp-sender">
                        <p><strong>
                                <?php _e('Sender: ', 'wp-trackpro'); ?>
                            </strong>
                            <?php echo get_post_meta(get_the_ID(), 'wtp-sender-name', true); ?>
                        </p>
                    </div>

                </div>

                <div class="wtp-shipment-history-wrap" id="wtp-shipment-history-information">

                    <?php
                    if ($get_shipment_history) {
                        $decode_wtp_fields = json_decode($wtp_sh_fields_json);
                        if ($decode_wtp_fields) {
                            ?>
                            <div class="wtp-row wtp-header-row">
                                <?php
                                foreach ($decode_wtp_fields as $wtp_field) {
                                    if ($wtp_field->display_frontend == 1) {
                                        ?>
                                        <div class="wtp-fields <?php echo $wtp_field->name; ?>">
                                            <label>
                                                <?php _e($wtp_field->label, 'wp-trackpro'); ?>
                                            </label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <?php
                        }
                        foreach ($get_shipment_history as $shipment_history) {
                            $decode_sh = json_decode($shipment_history->prod_values);
                            ?>
                            <div class="wtp-row" id="wtp-row-<?php echo $shipment_history->sh_id ?>">
                                <?php
                                foreach ($decode_sh as $key => $value) {
                                    if (in_array($key, $display_fields_fe)) {
                                        ?>
                                        <div class="wtp-fields <?php echo $key; ?>">
                                            <p>
                                                <?php echo $value; ?>
                                            </p>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    <?php
                    }
                    ?>
                </div>
                <?php
            endwhile;
        } else {
            ?>
        <h3>
            <?php _e('No Results', 'wp-trackpro') ?>
        </h3>
        <?php
        }
        wp_reset_postdata();
        ?>
    </div>
    <?php
}