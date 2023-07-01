<?php
/**
 * Product Information Template
 *
 * @package WP TrackPro\Template Parts
 * @version 1.0.0
 */

defined('ABSPATH') || exit;
ob_start();
?>
<div id="wtp-product-information">
    <div class="wtp-row button-add-wrap">
        <div class="wtp-button wtp-add-product swal2-styled"><span class="dashicons dashicons-plus-alt2"></span> Add
            Item</div>
    </div>
    <div class="wtp-product-fields-json d-none"
        json-fields='<?php echo apply_filters('wtp-product-fields-json', $wtp_fields_json); ?>'>
    </div>

    <?php
    $decode_wtp_fields = json_decode($wtp_fields_json);
    if ($decode_wtp_fields) {
        ?>
        <div class="wtp-row wtp-header-row">
            <?php
            foreach ($decode_wtp_fields as $wtp_field) {
                if ($wtp_field->display_metabox == 1) {
                    ?>
                    <div class="wtp-fields <?php echo $wtp_field->name; ?>">
                        <label>
                            <?php _e($wtp_field->label, 'wptrackpro'); ?>
                        </label>
                    </div>
                    <?php
                }
            }
            ?>
            <div class="wtp-fields wtp-field-weight">
                <label>
                    <?php _e('Action', 'wptrackpro'); ?>
                </label>
            </div>
        </div>
        <?php
    }

    if ($get_product_info) {

        foreach ($get_product_info as $key => $product_info) {
            $decode_prod_val = json_decode($product_info->prod_values);
            ?>
            <div class="wtp-row" id="wtp-row-<?php echo $product_info->prod_info_id ?>">
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
                    <div type="button" class="wtp-button wtp-view swal2-styled" name="wtp-product-edit" btn-action="view"
                        prod-info-id="<?php echo $product_info->prod_info_id; ?>" placeholder="View"><span
                            class="dashicons dashicons-visibility"></span> View</div>
                    <div type="button" class="wtp-button wtp-edit swal2-styled" name="wtp-product-edit"
                        prod-info-id="<?php echo $product_info->prod_info_id; ?>" btn-action="edit"><span
                            class="dashicons dashicons-edit-page"></span> Edit
                    </div>
                    <div type="button" class="wtp-button wtp-delete swal2-styled" name="wtp-product-delete" btn-action="delete"
                        prod-info-id="<?php echo $product_info->prod_info_id; ?>">
                        <span class="dashicons dashicons-trash"></span> Delete
                    </div>
                </div>
            </div>
            <?php
        }

    }

    ?>
</div>