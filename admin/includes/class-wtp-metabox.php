<?php
/**
 * Metabox
 *
 * @package WP TrackPro\Classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * WP TrackPro Metabox class.
 */

class WTP_Metabox
{
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_metabox'));
        add_action('save_post_wp_trackpro', array($this, 'save_metabox'), 10);
        add_filter('post_updated_messages', array($this, 'change_post_updated_messages'));
        add_filter('gettext', array($this, 'wtp_custom_title_text'));

    }

    /**
     * Renders the meta box.
     */
    public function render_sender_metabox($post)
    {
        wp_nonce_field('wtp_metabox_action', 'wtp_metabox_nonce');
        ?>
        <div id="wrap">
            <?php
            apply_filters('wtp_sender_information_meta', $this->wtp_sender_information_template_part());
            ?>
        </div>
        <?php
    }

    public function render_receiver_metabox($post)
    {
        wp_nonce_field('wtp_metabox_action', 'wtp_metabox_nonce');
        ?>
        <div id="wrap">
            <?php
            apply_filters('wtp_receiver_information_meta', $this->wtp_receiver_information_template_part());
            ?>
        </div>
        <?php
    }
    public function render_product_info_metabox($post)
    {
        wp_nonce_field('wtp_metabox_action', 'wtp_metabox_nonce');
        ?>
        <div id="wrap">
            <?php
            apply_filters('wtp_product_information_meta', $this->wtp_product_information_template_part());
            ?>
        </div>
        <?php
    }

    public function render_shipment_info_metabox($post)
    {
        wp_nonce_field('wtp_metabox_action', 'wtp_metabox_nonce');
        ?>
        <div id="wrap">
            <?php
            apply_filters('wtp_shipment_information_meta', $this->wtp_shipment_information_template_part());
            ?>
        </div>
        <?php
    }

    public function render_shipment_history_metabox($post)
    {
        wp_nonce_field('wtp_metabox_action', 'wtp_metabox_nonce');
        ?>
        <div id="wrap">
            <?php
            apply_filters('wtp_shipment_history_meta', $this->wtp_shipment_history_template_part());
            ?>
        </div>
        <?php
    }


    /**
     * Add the meta box.
     */

    public function add_metabox()
    {
        add_meta_box(
            'wtp_sender_information',
            'Sender Information',
            array($this, 'render_sender_metabox'),
            'wp_trackpro'
        );

        add_meta_box(
            'wtp_receiver_information',
            'Receiver Information',
            array($this, 'render_receiver_metabox'),
            'wp_trackpro'
        );

        add_meta_box(
            'wtp_product_information',
            'Product Information',
            array($this, 'render_product_info_metabox'),
            'wp_trackpro'
        );

        add_meta_box(
            'wtp_shipment_information',
            'Shipment Information',
            array($this, 'render_shipment_info_metabox'),
            'wp_trackpro'
        );

        add_meta_box(
            'wtp_shipment_history',
            'Shipment History',
            array($this, 'render_shipment_history_metabox'),
            'wp_trackpro'
        );


    }

    public function save_metabox($post_id)
    {

        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if (!isset($_POST['wtp_metabox_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['wtp_metabox_nonce'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'wtp_metabox_action')) {
            return $post_id;
        }

        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the user's permissions.
        if ('post' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }

        /* OK, it's safe for us to save the data now. */

        // Update the meta field.
        foreach ($_POST as $key => $value) {
            $meta_value = is_array($value) ? $value : sanitize_text_field($value);
            update_post_meta($post_id, $key, $meta_value);
        }
    }

    public function change_post_updated_messages($messages)
    {
        global $post, $post_ID;

        if ($post->post_type != 'wp_trackpro')
            return $messages;

        $messages['post'] = array(
            0 => '',
            1 => __('Shipment updated.'),
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __('Shipment updated.'),
            5 => isset($_GET['revision']) ? sprintf(__('Shipment restored to revision from %s'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
            6 => __('Shipment published.'),
            7 => __('Shipment saved.'),
            8 => __('Shipment submitted.'),
            9 => sprintf(
                __('Shipment scheduled for: <strong>%1$s</strong>.'),
                date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date))
            ),
            10 => __('Shipment draft updated.'),
        );

        return $messages;
    }


    public function wtp_custom_title_text($input)
    {
        global $post_type;

        if ('Add title' == $input && 'wp_trackpro' == $post_type)
            return 'Enter Tracking Code';

        return $input;
    }

    private function wtp_get_product_information($post_id)
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wtp_product_information WHERE post_id = {$post_id}", OBJECT);
        return $results;
    }

    private function wtp_get_shipment_history($post_id)
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wtp_shipment_history WHERE post_id = {$post_id}", OBJECT);
        return $results;
    }

    public function wtp_display_field_metabox()
    {
        $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-fields.json');
        $decode_wtp_fields = json_decode($wtp_fields_json);
        $get_display_field_name = [];
        if ($decode_wtp_fields) {
            foreach ($decode_wtp_fields as $wtp_field) {
                if ($wtp_field->display_metabox == 1) {
                    $get_display_field_name[] = $wtp_field->name;
                }
            }
        }
        return $get_display_field_name;
    }

    public function wtp_sh_display_field_metabox()
    {
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
        return $get_display_field_name;
    }

    public function wtp_sender_information_template_part()
    {
        include_once(WP_TRACKPRO_PLUGIN_PATH . 'admin/template-parts/content/sender-information.php');
    }

    public function wtp_receiver_information_template_part()
    {
        include_once(WP_TRACKPRO_PLUGIN_PATH . 'admin/template-parts/content/receiver-information.php');
    }

    public function wtp_product_information_template_part()
    {
        global $post;
        $wtp_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-fields.json');
        $get_product_info = $this->wtp_get_product_information($post->ID);
        $get_display_field_name = $this->wtp_display_field_metabox();
        include_once(WP_TRACKPRO_PLUGIN_PATH . 'admin/template-parts/content/product-information.php');
    }

    public function wtp_shipment_information_template_part()
    {
        include_once(WP_TRACKPRO_PLUGIN_PATH . 'admin/template-parts/content/shipment-information.php');
    }

    public function wtp_shipment_history_template_part()
    {
        global $post;
        $wtp_sh_fields_json = file_get_contents(WP_TRACKPRO_PLUGIN_PATH . 'admin/assets/json/wtp-shipment-history.json');
        $get_shipment_history = $this->wtp_get_shipment_history($post->ID);
        $get_display_field_name = $this->wtp_sh_display_field_metabox();
        include_once(WP_TRACKPRO_PLUGIN_PATH . 'admin/template-parts/content/shipment-history.php');
    }
}
return new WTP_Metabox;