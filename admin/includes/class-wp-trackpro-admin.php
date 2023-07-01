<?php
/**
 * WP TrackPro Admin
 *
 * @package WP TrackPro\Classes
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

/**
 * WP TrackPro Admin
 */

class WP_TrackPro_Admin
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'wtp_enqueue_admin_script'));
        add_filter('script_loader_tag', array($this, 'wtp_script_loader_tag'), 10, 3);
        add_action("wp_ajax_wtp_product_information_save", array($this, "wtp_product_information_save"));
        add_action("wp_ajax_nopriv_wtp_product_information_save", array($this, "wtp_product_information_save"));
        add_action("wp_ajax_wtp_product_information_view", array($this, "wtp_product_information_view"));
        add_action("wp_ajax_nopriv_wtp_product_information_view", array($this, "wtp_product_information_view"));
        add_action("wp_ajax_wtp_product_information_edit", array($this, "wtp_product_information_edit"));
        add_action("wp_ajax_nopriv_wtp_product_information_edit", array($this, "wtp_product_information_edit"));
        add_action("wp_ajax_wtp_product_information_update", array($this, "wtp_product_information_update"));
        add_action("wp_ajax_nopriv_wtp_product_information_update", array($this, "wtp_product_information_update"));
        add_action("wp_ajax_wtp_product_information_delete", array($this, "wtp_product_information_delete"));
        add_action("wp_ajax_nopriv_wtp_product_information_delete", array($this, "wtp_product_information_delete"));
        add_action("wp_ajax_wtp_shipment_history_save", array($this, "wtp_shipment_history_save"));
        add_action("wp_ajax_nopriv_wtp_shipment_history_save", array($this, "wtp_shipment_history_save"));
        add_action("wp_ajax_wtp_shipment_history_view", array($this, "wtp_shipment_history_view"));
        add_action("wp_ajax_nopriv_wtp_shipment_history_view", array($this, "wtp_shipment_history_view"));
        add_action("wp_ajax_wtp_shipment_history_edit", array($this, "wtp_shipment_history_edit"));
        add_action("wp_ajax_nopriv_wtp_shipment_history_edit", array($this, "wtp_shipment_history_edit"));
        add_action("wp_ajax_wtp_shipment_history_update", array($this, "wtp_shipment_history_update"));
        add_action("wp_ajax_nopriv_wtp_shipment_history_update", array($this, "wtp_shipment_history_update"));
        add_action("wp_ajax_wtp_shipment_history_delete", array($this, "wtp_shipment_history_delete"));
        add_action("wp_ajax_nopriv_wtp_shipment_history_delete", array($this, "wtp_shipment_history_delete"));
        add_action("wp_ajax_wtp_shipment_history_sort", array($this, "wtp_shipment_history_sort"));
        add_action("wp_ajax_nopriv_wtp_shipment_history_sort", array($this, "wtp_shipment_history_sort"));
        $this->includes();
    }

    public function includes()
    {
        /**
         * Admin classes.
         */
        include_once WP_TRACKPRO_PLUGIN_PATH . 'admin/includes/class-wtp-metabox.php';
        include_once WP_TRACKPRO_PLUGIN_PATH . 'admin/includes/class-wtp-settings.php';
        include_once WP_TRACKPRO_PLUGIN_PATH . 'admin/includes/class-wtp-database.php';
        include_once WP_TRACKPRO_PLUGIN_PATH . 'admin/includes/class-wtp-admin-tables.php';
    }

    public function wtp_enqueue_admin_script()
    {
        global $post_type;
        if ('wp_trackpro' != $post_type)
            return;
        wp_enqueue_script('sweetalert-js', WP_TRACKPRO_PLUGIN_URL . 'admin/assets/js/sweetalert-min.js');
        wp_enqueue_script('wtp-admin-js', WP_TRACKPRO_PLUGIN_URL . 'admin/assets/js/admin-scripts.js');
        wp_localize_script(
            'wtp-admin-js',
            'wtp_params',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'wtp_admin_url' => admin_url()
            )
        );
        wp_register_style('wtp_admin_styles', WP_TRACKPRO_PLUGIN_URL . 'admin/assets/css/wtp-admin-styles.css', array(), WP_TRACKPRO_VERSION);
        wp_enqueue_style('wtp_admin_styles');
    }

    public function wtp_script_loader_tag($tag, $handle, $src)
    {
        if ($handle === 'wtp-admin-js') {
            if (false === stripos($tag, 'async')) {
                $tag = str_replace(' src', ' async="async" src', $tag);
            }

            if (false === stripos($tag, 'defer')) {
                $tag = str_replace('<script ', '<script defer ', $tag);
            }

        }
        return $tag;
    }

    public function wtp_product_information_save()
    {
        global $wpdb;

        $get_all_product_information = $_REQUEST;
        $get_wtp_id = $_REQUEST['postID'];
        $wtp_fields = [];
        $wtp_results['status'] = false;
        $wtp_results['fields'] = [];
        $wtp_results['checker'] = [];
        if ($get_all_product_information) {
            foreach ($get_all_product_information as $product_info_key => $product_info_val) {
                if ($product_info_key != "action" && $product_info_key != "postID") {
                    if (preg_match('/(wtp-*)/', $product_info_key, $output_array) || preg_match('/(postID)/', $product_info_key, $output_array)) {
                        $wtp_fields[$product_info_key] = $product_info_val;
                    }

                }
            }
        }
        $json_enc_wtp_fields = json_encode($wtp_fields);
        $insert_product_info = $wpdb->insert("{$wpdb->prefix}wtp_product_information", array('post_id' => $get_wtp_id, 'prod_values' => $json_enc_wtp_fields));
        if ($insert_product_info) {
            $wtp_results['status'] = true;
            $wtp_last_id = $wpdb->insert_id;

            $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-fields.json');
            $decode_wtp_fields = json_decode($wtp_fields_json);
            $get_field_name = [];
            if ($decode_wtp_fields) {
                foreach ($decode_wtp_fields as $wtp_field) {
                    if ($wtp_field->display_metabox == 1) {
                        $get_field_name[] = $wtp_field->name;
                    }
                }
            }

            $get_display_field_value = [];
            if ($json_enc_wtp_fields) {
                foreach (json_decode($json_enc_wtp_fields, true) as $key => $value) {
                    if (in_array($key, $get_field_name)) {
                        $get_display_field_value[$key] = $value;
                    }
                }
            }
            $wtp_results['fields'] = $get_display_field_value['wtp-field-product-info-id'] = $wtp_last_id;
            $wtp_results['fields'] = $get_display_field_value;
        }
        echo json_encode($wtp_results);
        wp_die();
    }

    public function wtp_product_information_view()
    {
        global $wpdb;

        $wtp_get_prod_info_id = $_REQUEST['productID'];
        $wtp_id = $_REQUEST['postID'];

        $get_product_information = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wtp_product_information WHERE post_id = '$wtp_id' AND prod_info_id = '$wtp_get_prod_info_id'");

        $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-fields.json');
        $decode_wtp_fields = json_decode($wtp_fields_json);
        $get_display_field_label = [];
        $get_field_name = [];
        if ($decode_wtp_fields) {
            foreach ($decode_wtp_fields as $wtp_field) {
                if ($wtp_field->hide == 0) {
                    $get_display_field_label[$wtp_field->name] = $wtp_field->label;
                    $get_field_name[] = $wtp_field->name;
                }
            }
        }

        $get_display_field_value = [];
        if ($get_product_information) {
            $product_information = $get_product_information[0];
            foreach (json_decode($product_information->prod_values, true) as $key => $value) {
                if (in_array($key, $get_field_name)) {
                    $get_display_field_value[$key] = $value;
                }
            }
        }
        $merge_product_information = [];
        if (!empty($get_display_field_label) && !empty($get_display_field_value)) {
            $merge_product_information = array_merge_recursive($get_display_field_label, $get_display_field_value);
        }

        if ($merge_product_information) {
            foreach ($merge_product_information as $product_info) {
                ?>
                <div class="wtp-product-info">
                    <p>
                        <strong class="wtp-product-info-label">
                            <?php echo $product_info[0]; ?>
                        </strong>:
                        <span class="wtp-product-info-value">
                            <?php echo $product_info[1]; ?>
                        </span>
                    </p>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="wtp-product-info">
                <h2 class="wtp-error-message">
                    <strong class="wtp-product-info-label">
            <?php _e("No Results", "wp-trackpro"); ?>
            </strong>

        </h2>
    </div>
<?php
        }
        wp_die();
    }

    public function wtp_product_information_edit()
    {
        global $wpdb;

        $wtp_get_prod_info_id = $_REQUEST['productID'];
        $wtp_id = $_REQUEST['postID'];

        $get_product_information = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wtp_product_information WHERE post_id = '$wtp_id' AND prod_info_id = '$wtp_get_prod_info_id'");

        $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-fields.json');
        $decode_wtp_fields = json_decode($wtp_fields_json);
        $get_field_name = [];
        if ($decode_wtp_fields) {
            foreach ($decode_wtp_fields as $wtp_field) {
                if ($wtp_field->hide == 0) {
                    $get_field_name[] = $wtp_field->name;
                }
            }
        }
        $get_display_field_value = [];
        if ($get_product_information) {
            $get_display_field_value['wtp-field-product-info-id'] .= $wtp_get_prod_info_id;
            $product_information = $get_product_information[0];
            foreach (json_decode($product_information->prod_values, true) as $key => $value) {
                if (in_array($key, $get_field_name)) {
                    $get_display_field_value[$key] = $value;
                }
            }
        }

        echo json_encode($get_display_field_value);
        wp_die();
    }

    public function wtp_product_information_update()
    {
        global $wpdb;

        $get_all_product_information = $_REQUEST;
        $get_wtp_prod_info_id = $_REQUEST['productID'];
        $wtp_fields = [];
        $wtp_results['status'] = false;
        $wtp_results['fields'] = [];
        $wtp_results['checker'] = [];
        if ($get_all_product_information) {
            foreach ($get_all_product_information as $product_info_key => $product_info_val) {
                if ($product_info_key != "action" && $product_info_key != "postID") {
                    if (preg_match('/(wtp-*)/', $product_info_key, $output_array) || preg_match('/(postID)/', $product_info_key, $output_array)) {
                        $wtp_fields[$product_info_key] = $product_info_val;
                    }

                }
            }
        }
        $json_enc_wtp_fields = json_encode($wtp_fields);

        $update_product_info = $wpdb->update("{$wpdb->prefix}wtp_product_information", array('prod_values' => $json_enc_wtp_fields), array('prod_info_id' => $get_wtp_prod_info_id));

        if ($update_product_info) {
            $wtp_results['status'] = true;
            $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-fields.json');
            $decode_wtp_fields = json_decode($wtp_fields_json);
            $get_field_name = [];
            if ($decode_wtp_fields) {
                foreach ($decode_wtp_fields as $wtp_field) {
                    if ($wtp_field->display_metabox == 1) {
                        $get_field_name[] = $wtp_field->name;
                    }
                }
            }

            $get_display_field_value = [];
            if ($json_enc_wtp_fields) {
                foreach (json_decode($json_enc_wtp_fields, true) as $key => $value) {
                    if (in_array($key, $get_field_name)) {
                        $get_display_field_value[$key] = $value;
                    }
                }
            }
            $wtp_results['fields'] = $get_display_field_value['wtp-field-product-info-id'] = $get_wtp_prod_info_id;
            $wtp_results['fields'] = $get_display_field_value;

        }

        if ($update_product_info === 0) {
            $wtp_results['status'] = 'no-rows-updated';
        }

        echo json_encode($wtp_results);
        wp_die();

    }

    public function wtp_product_information_delete()
    {
        global $wpdb;
        $get_wtp_prod_info_id = $_REQUEST['productID'];
        $wtp_results['status'] = false;

        $table = $wpdb->prefix . 'wtp_product_information';
        $wtp_delete_product_info = $wpdb->delete($table, array('prod_info_id' => $get_wtp_prod_info_id));
        if ($wtp_delete_product_info) {
            $wtp_results['productID'] = $get_wtp_prod_info_id;
            $wtp_results['status'] = true;
        }

        echo json_encode($wtp_results);
        wp_die();
    }

    public function wtp_shipment_history_save()
    {
        global $wpdb;

        $get_all_product_information = $_REQUEST;
        $get_wtp_id = $_REQUEST['postID'];
        $wtp_fields = [];
        $wtp_results['status'] = false;
        $wtp_results['fields'] = [];
        $wtp_results['checker'] = [];
        if ($get_all_product_information) {
            foreach ($get_all_product_information as $product_info_key => $product_info_val) {
                if ($product_info_key != "action" && $product_info_key != "postID") {
                    if (preg_match('/(wtp-*)/', $product_info_key, $output_array) || preg_match('/(postID)/', $product_info_key, $output_array)) {
                        $wtp_fields[$product_info_key] = $product_info_val;
                    }

                }
            }
        }
        $date_time = null;
        if (isset($wtp_fields['wtp-shipment-date']) && isset($wtp_fields['wtp-shipment-time'])) {
            $date_time = date("Y-m-d H:i:s", strtotime($wtp_fields['wtp-shipment-date'] . ' ' . $wtp_fields['wtp-shipment-time']));
        }
        $json_enc_wtp_fields = json_encode($wtp_fields);
        $insert_product_info = $wpdb->insert("{$wpdb->prefix}wtp_shipment_history", array('post_id' => $get_wtp_id, 'prod_values' => $json_enc_wtp_fields, 'date_time' => $date_time));
        if ($insert_product_info) {
            $wtp_results['status'] = true;
            $wtp_last_id = $wpdb->insert_id;

            $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-shipment-history.json');
            $decode_wtp_fields = json_decode($wtp_fields_json);
            $get_field_name = [];
            if ($decode_wtp_fields) {
                foreach ($decode_wtp_fields as $wtp_field) {
                    if ($wtp_field->display_metabox == 1) {
                        $get_field_name[] = $wtp_field->name;
                    }
                }
            }

            $get_display_field_value = [];
            if ($json_enc_wtp_fields) {
                foreach (json_decode($json_enc_wtp_fields, true) as $key => $value) {
                    if (in_array($key, $get_field_name)) {
                        $get_display_field_value[$key] = $value;
                    }
                }
            }
            $wtp_results['fields'] = $get_display_field_value['wtp-field-shipment-history-id'] = $wtp_last_id;
            $wtp_results['fields'] = $get_display_field_value;
        }
        echo json_encode($wtp_results);
        wp_die();
    }

    public function wtp_shipment_history_view()
    {
        global $wpdb;

        $wtp_get_sh_id = $_REQUEST['shipmentHistoryID'];
        $wtp_id = $_REQUEST['postID'];

        $get_product_information = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wtp_shipment_history WHERE post_id = '$wtp_id' AND sh_id = '$wtp_get_sh_id'");

        $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-shipment-history.json');
        $decode_wtp_fields = json_decode($wtp_fields_json);
        $get_display_field_label = [];
        $get_field_name = [];
        if ($decode_wtp_fields) {
            foreach ($decode_wtp_fields as $wtp_field) {
                if ($wtp_field->hide == 0) {
                    $get_display_field_label[$wtp_field->name] = $wtp_field->label;
                    $get_field_name[] = $wtp_field->name;
                }
            }
        }

        $get_display_field_value = [];
        if ($get_product_information) {
            $product_information = $get_product_information[0];
            foreach (json_decode($product_information->prod_values, true) as $key => $value) {
                if (in_array($key, $get_field_name)) {
                    $get_display_field_value[$key] = $value;
                }
            }
        }
        $merge_product_information = [];
        if (!empty($get_display_field_label) && !empty($get_display_field_value)) {
            $merge_product_information = array_merge_recursive($get_display_field_label, $get_display_field_value);
        }

        if ($merge_product_information) {
            foreach ($merge_product_information as $product_info) {
                ?>
        <div class="wtp-shipment-history">
            <p>
                <strong class="wtp-shipment-history-label">
                    <?php echo $product_info[0]; ?>
                </strong>:
                <span class="wtp-shipment-history-value">
                    <?php echo $product_info[1]; ?>
                </span>
            </p>
        </div>
        <?php
            }
        } else {
            ?>
            <div class="wtp-shipment-history">
                <h2 class="wtp-error-message">
                    <strong class="wtp-shipment-history-label">
            <?php _e("No Results", "wp-trackpro"); ?>
            </strong>

        </h2>
    </div>
<?php
        }
        wp_die();
    }

    public function wtp_shipment_history_edit()
    {
        global $wpdb;

        $wtp_get_sh_id = $_REQUEST['shipmentHistoryID'];
        $wtp_id = $_REQUEST['postID'];

        $get_shipment_history = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wtp_shipment_history WHERE post_id = '$wtp_id' AND sh_id = '$wtp_get_sh_id'");

        $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-shipment-history.json');
        $decode_wtp_fields = json_decode($wtp_fields_json);
        $get_field_name = [];
        if ($decode_wtp_fields) {
            foreach ($decode_wtp_fields as $wtp_field) {
                if ($wtp_field->hide == 0) {
                    $get_field_name[] = $wtp_field->name;
                }
            }
        }
        $get_display_field_value = [];
        if ($get_shipment_history) {
            $get_display_field_value['wtp-field-shipment-history-id'] .= $wtp_get_sh_id;
            $shipment_history = $get_shipment_history[0];
            foreach (json_decode($shipment_history->prod_values, true) as $key => $value) {
                if (in_array($key, $get_field_name)) {
                    $get_display_field_value[$key] = $value;
                }
            }
        }

        echo json_encode($get_display_field_value);
        wp_die();
    }

    public function wtp_shipment_history_update()
    {
        global $wpdb;

        $get_all_sh = $_REQUEST;
        $get_wtp_sh_id = $_REQUEST['shipmentHistoryID'];
        $wtp_fields = [];
        $wtp_results['status'] = false;
        $wtp_results['fields'] = [];
        $wtp_results['checker'] = [];
        if ($get_all_sh) {
            foreach ($get_all_sh as $sh_key => $sh_val) {
                if ($sh_key != "action" && $sh_key != "postID") {
                    if (preg_match('/(wtp-*)/', $sh_key, $output_array) || preg_match('/(postID)/', $sh_key, $output_array)) {
                        $wtp_fields[$sh_key] = $sh_val;
                    }

                }
            }
        }
        $date_time = null;
        if (isset($wtp_fields['wtp-shipment-date']) && isset($wtp_fields['wtp-shipment-time'])) {
            $date_time = date("Y-m-d H:i:s", strtotime($wtp_fields['wtp-shipment-date'] . ' ' . $wtp_fields['wtp-shipment-time']));
        }
        $json_enc_wtp_fields = json_encode($wtp_fields);

        $update_product_info = $wpdb->update("{$wpdb->prefix}wtp_shipment_history", array('prod_values' => $json_enc_wtp_fields, 'date_time' => $date_time), array('sh_id' => $get_wtp_sh_id));

        if ($update_product_info) {
            $wtp_results['status'] = true;
            $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-shipment-history.json');
            $decode_wtp_fields = json_decode($wtp_fields_json);
            $get_field_name = [];
            if ($decode_wtp_fields) {
                foreach ($decode_wtp_fields as $wtp_field) {
                    if ($wtp_field->display_metabox == 1) {
                        $get_field_name[] = $wtp_field->name;
                    }
                }
            }

            $get_display_field_value = [];
            if ($json_enc_wtp_fields) {
                foreach (json_decode($json_enc_wtp_fields, true) as $key => $value) {
                    if (in_array($key, $get_field_name)) {
                        $get_display_field_value[$key] = $value;
                    }
                }
            }
            $wtp_results['date_time'] = $date_time;
            $wtp_results['fields'] = $get_display_field_value['wtp-field-shipment-history-id'] = $get_wtp_sh_id;
            $wtp_results['fields'] = $get_display_field_value;

        }

        if ($update_product_info === 0) {
            $wtp_results['status'] = 'no-rows-updated';
        }

        echo json_encode($wtp_results);
        wp_die();

    }

    public function wtp_shipment_history_delete()
    {
        global $wpdb;
        $get_wtp_sh_id = $_REQUEST['shipmentHistoryID'];
        $wtp_results['status'] = false;

        $table = $wpdb->prefix . 'wtp_shipment_history';
        $wtp_delete_product_info = $wpdb->delete($table, array('sh_id' => $get_wtp_sh_id));
        if ($wtp_delete_product_info) {
            $wtp_results['shipmenthistoryID'] = $get_wtp_sh_id;
            $wtp_results['status'] = true;
        }

        echo json_encode($wtp_results);
        wp_die();
    }

    public function wtp_shipment_history_sort()
    {
        global $wpdb;

        $wtp_id = $_REQUEST['postID'];

        $get_shipment_history = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wtp_shipment_history WHERE post_id = '$wtp_id' ORDER BY date_time DESC");

        $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-shipment-history.json');
        $decode_wtp_fields = json_decode($wtp_fields_json);
        $get_display_field_name = [];
        if ($decode_wtp_fields) {
            foreach ($decode_wtp_fields as $wtp_field) {
                if ($wtp_field->display_metabox == 1) {
                    $get_display_field_name[] = $wtp_field->name;
                }
            }
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
                <div type="button" class="wtp-button wtp-view swal2-styled" name="wtp-shipment-history-edit" btn-action="view"
                    sh-id="<?php echo $shipment_history->sh_id; ?>" placeholder="View">
                    <span class="dashicons dashicons-visibility"></span> View
                </div>
                <div type="button" class="wtp-button wtp-edit swal2-styled" name="wtp-shipment-history-edit"
                    sh-id="<?php echo $shipment_history->sh_id; ?>" btn-action="edit"><span
                        class="dashicons dashicons-edit-page"></span> Edit
                </div>
                <div type="button" class="wtp-button wtp-delete swal2-styled" name="wtp-shipment-history-delete"
                    btn-action="delete" sh-id="<?php echo $shipment_history->sh_id; ?>">
                    <span class="dashicons dashicons-trash"></span> Delete
                </div>
            </div>
        </div>
        <?php
            }

        }

        wp_die();
    }

}

return new WP_TrackPro_Admin;