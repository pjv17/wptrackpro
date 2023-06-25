<?php
/**
 * Shipment History Template
 *
 * @package WP TrackPro\Template Parts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;
ob_start();

?>
<div id="wtp-shipment-history-information">
    <div class="wtp-row button-add-wrap">
        <div class="wtp-button wtp-add-shipment-history swal2-styled"><span
                class="dashicons dashicons-plus-alt2"></span> Add
            Shipment History</div>
    </div>
    <div class="wtp-shipment-history-fields-json d-none"
        json-fields='<?php echo apply_filters('wtp-shipment-history-fields-json', $wtp_sh_fields_json); ?>'>
    </div>

    <?php
    $decode_wtp_fields = json_decode($wtp_sh_fields_json);
    if ($decode_wtp_fields) {
        ?>
        <div class="wtp-row wtp-header-row">
            <?php
            foreach ($decode_wtp_fields as $wtp_field) {
                if ($wtp_field->display_metabox == 1) {
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
            <div class="wtp-fields wtp-field-weight">
                <label>
                    <?php _e('Action', 'wp-trackpro'); ?>
                </label>
            </div>
        </div>
        <?php
    }

    if ($get_shipment_history) {

        foreach ($get_shipment_history as $key => $shipment_history) {
            $decode_prod_val = json_decode($shipment_history->prod_values);
            ?>
            <div class="wtp-row" id="wtp-row-<?php echo $shipment_history->sh_id ?>">
                <?php
                foreach ($decode_prod_val as $key => $value) {
                    if (in_array($key, $get_display_field_name)) {
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

                <div class="wtp-fields wtp-field-action">
                    <div type="button" class="wtp-button wtp-view swal2-styled" name="wtp-shipment-history-edit"
                        btn-action="view" prod-info-id="<?php echo $shipment_history->prod_info_id; ?>" placeholder="View">
                        <span class="dashicons dashicons-visibility"></span> View
                    </div>
                    <div type="button" class="wtp-button wtp-edit swal2-styled" name="wtp-shipment-history-edit"
                        prod-info-id="<?php echo $shipment_history->prod_info_id; ?>" btn-action="edit"><span
                            class="dashicons dashicons-edit-page"></span> Edit
                    </div>
                    <div type="button" class="wtp-button wtp-delete swal2-styled" name="wtp-shipment-history-delete"
                        btn-action="delete" prod-info-id="<?php echo $shipment_history->prod_info_id; ?>">
                        <span class="dashicons dashicons-trash"></span> Delete
                    </div>
                </div>
            </div>
            <?php
        }

    }

    ?>
</div>