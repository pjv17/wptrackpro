<?php
/**
 * Settings
 *
 * @package WP TrackPro\Classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * WP TrackPro Admin Settings class.
 */

class WTP_Settings
{
    private $options;
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_submenu_page_to_post_type'));
        add_action('admin_init', array($this, 'sub_menu_page_init'));
        add_action('admin_init', array($this, 'media_selector_scripts'));
    }

    /**
     * Add sub menu page to the custom post type
     */
    public function add_submenu_page_to_post_type()
    {
        add_submenu_page(
            'edit.php?post_type=wp_trackpro',
            __('Settings', 'wp-trackpro'),
            __('Settings', 'wp-trackpro'),
            'manage_options',
            'wp_trackpro_settings',
            array($this, 'wp_trackpro_options_display')
        );
    }

    /**
     * Options page callback
     */
    public function wp_trackpro_options_display()
    {
        $this->options = get_option('wp-trackpro_archive');

        wp_enqueue_media();

        echo '<div class="wrap">';

        printf('<h1>%s</h1>', __('WP TrackPro Settings', 'wp-trackpro'));

        echo '<form method="post" action="options.php">';

        settings_fields('wp_trackpro_settings');

        do_settings_sections('wp-trackpro-settings-page');

        submit_button();

        echo '</form></div>';
    }

    /**
     * Register and add settings
     */
    public function sub_menu_page_init()
    {
        register_setting(
            'wp_trackpro_settings',
            'wp-trackpro_archive',
            array($this, 'sanitize')
        );

        add_settings_section(
            'header_settings_section',
            __('General Settings', 'wp-trackpro'),
            '',
            'wp-trackpro-settings-page'
        );

        add_settings_field(
            'wtp_settings_countries',
            __('Shipment Countries', 'wp-trackpro'),
            array($this, 'countries_callback'),
            'wp-trackpro-settings-page',
            'header_settings_section'
        );

        add_settings_field(
            'logo_attachment',
            __('Logo', 'wp-trackpro'),
            array($this, 'header_bg_image_callback'),
            'wp-trackpro-settings-page',
            'header_settings_section'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = array();

        if (isset($input['wtp_settings_countries']))
            $new_input['wtp_settings_countries'] = sanitize_text_field($input['wtp_settings_countries']);

        if (isset($input['logo_attachment']))
            $new_input['logo_attachment'] = absint($input['logo_attachment']);

        return $new_input;
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function countries_callback()
    {
        printf(
            '<textarea rows="5" cols="70" id="wtp-setting-countries" name="wp-trackpro_archive[countries]" />%s</textarea>',
            isset($this->options['wtp_settings_countries']) ? esc_attr($this->options['wtp_settings_countries']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function header_bg_image_callback()
    {
        $attachment_id = isset($this->options['logo_attachment']) ? $this->options['logo_attachment'] : '';

        // Image Preview
        printf('<div class="image-preview-wrapper"><img id="image-preview" src="%s" ></div>', wp_get_attachment_url($attachment_id));

        // Image Upload Button
        printf(
            '<input id="upload_image_button" type="button" class="button" value="%s" />',
            __('Upload image', 'wp-trackpro')
        );

        // Hidden field containing the value of the image attachment id
        printf(
            '<input type="hidden" name="wp-trackpro_archive[logo_attachment]" id="logo_attachment" value="%s">',
            $attachment_id
        );
    }

    public function media_selector_scripts()
    {
        $my_saved_attachment_post_id = get_option('media_selector_attachment_id', 0);

        wp_register_script('sub_menu_media_selector_scripts', get_template_directory_uri() . '/admin/js/media-selector.js', array('jquery'), false, true);

        $selector_data = array(
            'attachment_id' => get_option('media_selector_attachment_id', 0)
        );

        wp_localize_script('sub_menu_media_selector_scripts', 'selector_data', $selector_data);

        wp_enqueue_script('sub_menu_media_selector_scripts');
    }
}

return new WTP_Settings;