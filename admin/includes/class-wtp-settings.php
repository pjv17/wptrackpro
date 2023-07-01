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
        register_activation_hook(WP_TRACKPRO_PLUGIN_FILE, array($this, 'wtp_update_settings'));
    }

    /**
     * Add sub menu page to the custom post type
     */
    public function add_submenu_page_to_post_type()
    {
        add_submenu_page(
            'edit.php?post_type=wp_trackpro',
            __('Settings', 'wptrackpro'),
            __('Settings', 'wptrackpro'),
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
        $this->options = get_option('wp_trackpro_options');

        echo '<div class="wrap">';

        printf('<h1>%s</h1>', __('WP TrackPro Settings', 'wptrackpro'));

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
            'wp_trackpro_options',
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'header_settings_section',
            __('General Settings', 'wptrackpro'),
            '',
            'wp-trackpro-settings-page'
        );

        add_settings_field(
            'wtp_settings_countries',
            __('Shipment Countries', 'wptrackpro'),
            array($this, 'countries_callback'),
            'wp-trackpro-settings-page',
            'header_settings_section'
        );

        add_settings_field(
            'wtp_settings_shipping_mode',
            __('Shipping Mode', 'wptrackpro'),
            array($this, 'shipping_mode'),
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
            $new_input['wtp_settings_countries'] = sanitize_textarea_field($input['wtp_settings_countries']);


        if (isset($input['wtp_settings_shipping_mode']))
            $new_input['wtp_settings_shipping_mode'] = sanitize_textarea_field($input['wtp_settings_shipping_mode']);

        return $new_input;
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function countries_callback()
    {
        printf(
            '<textarea rows="5" cols="70" id="wtp-setting-countries" name="wp_trackpro_options[wtp_settings_countries]" />%s</textarea>',
            isset($this->options['wtp_settings_countries']) ? esc_attr($this->options['wtp_settings_countries']) : ""
        );
    }

    public function shipping_mode()
    {
        printf(
            '<textarea rows="5" cols="70" id="wtp-setting-shipping_mode" name="wp_trackpro_options[wtp_settings_shipping_mode]" />%s</textarea>',
            isset($this->options['wtp_settings_shipping_mode']) ? esc_attr($this->options['wtp_settings_shipping_mode']) : ""
        );
    }
    public function wtp_update_settings()
    {
        if (empty(get_option('wtp_settings_countries'))) {
            update_option('wtp_settings_countries', "Afghanistan, Albania, Algeria, Andorra, Angola, Antigua and Barbuda, Argentina, Armenia, Australia, Austria, Azerbaijan, Bahamas, Bahrain, Bangladesh, Barbados, Belarus, Belgium, Belize, Benin, Bhutan, Bolivia, Bosnia and Herzegovina, Botswana, Brazil, Brunei, Bulgaria, Burkina Faso, Burundi, Côte d'Ivoire, Cabo Verde, Cambodia, Cameroon, Canada, Central African Republic, Chad, Chile, China, Colombia, Comoros, Congo (Congo-Brazzaville), Costa Rica, Croatia, Cuba, Cyprus, Czechia (Czech Republic), Democratic Republic of the Congo, Denmark, Djibouti, Dominica, Dominican Republic, Ecuador, Egypt, El Salvador, Equatorial Guinea, Eritrea, Estonia, Ethiopia, Fiji, Finland, France, Gabon, Gambia, Georgia, Germany, Ghana, Greece, Grenada, Guatemala, Guinea, Guinea-Bissau, Guyana, Haiti, Holy See, Honduras, Hungary, Iceland, India, Indonesia, Iran, Iraq, Ireland, Israel, Italy, Jamaica, Japan, Jordan, Kazakhstan, Kenya, Kiribati, Kuwait, Kyrgyzstan, Laos, Latvia, Lebanon, Lesotho, Liberia, Libya, Liechtenstein, Lithuania, Luxembourg, Madagascar, Malawi, Malaysia, Maldives, Mali, Malta, Marshall Islands, Mauritania, Mauritius, Mexico, Micronesia, Moldova, Monaco, Mongolia, Montenegro, Morocco, Mozambique, Myanmar (formerly Burma), Namibia, Nauru, Nepal, Netherlands, New Zealand, Nicaragua, Niger, Nigeria, North Korea, North Macedonia, Norway, Oman, Pakistan, Palau, Palestine State, Panama, Papua New Guinea, Paraguay, Peru, Philippines, Poland, Portugal, Qatar, Romania, Russia, Rwanda, Saint Kitts and Nevis, Saint Lucia, Saint Vincent and the Grenadines, Samoa, San Marino, Sao Tome and Principe, Saudi Arabia, Senegal, Serbia, Seychelles, Sierra Leone, Singapore, Slovakia, Slovenia, Solomon Islands, Somalia, South Africa, South Korea, South Sudan, Spain, Sri Lanka, Sudan, Suriname, Sweden, Switzerland, Syria, Tajikistan, Tanzania, Thailand, Timor-Leste, Togo, Tonga, Trinidad and Tobago, Tunisia, Turkey, Turkmenistan, Tuvalu, Uganda, Ukraine, United Arab Emirates, United Kingdom, United States of America, Uruguay, Uzbekistan, Vanuatu, Venezuela, Vietnam, Yemen, Zambia, Zimbabwe");
        }
        if (empty(get_option('wtp_settings_shipping_mode'))) {
            update_option('wtp_settings_shipping_mode', "Air Transport, Land Transport, Sea Transport");
        }
    }
}

return new WTP_Settings;