# wptrackpro
Track, manage, and streamline your shipments with WP TrackPro, the all-in-one tracking shipment plugin for WordPress. Keep a detailed shipment history, provide tracking codes for clients, send notifications via email and SMS, and customize fields to fit your business needs. 

<b>Free Features:</b>
- Store Shipment Details
- Track Shipment
- Shipment History

<b>Pro Features(to be added):</b>
- SMS Notification
- Custom Fields
- Analytics Dashboard

<b>Documentations:</b>
- Shortcode for Tracking of Shipment is <code>[wtp-track-shipment]</code>
- You can add/edit the product information fields by using <code>add_filter('wtp-product-fields-json', 'your_function_here');</code>. And you can find the JSON file for your reference under /wp-trackpro/admin/assets/js/json/wtp-fields.json
- You can add/edit the shipment history fields by using <code>add_filter('wtp-shipment-history-fields-json', 'your_function_here');</code>. And you can find the JSON file for your reference under /wp-trackpro/admin/assets/js/json/wtp-shipment-history.json

<b>WP TrackPro allows you to customize the appearance and layout of the tracking form and shipment results by following these steps:</b>

1. Create a new folder in your WordPress theme directory and name it "wptrackpro". This folder will serve as the location for your customized templates.

2. Inside the "wptrackpro" folder, create two new files: "wtp-track-shipment-form.php" and "wtp-track-shipment-results.php". These files will hold the customized templates for the tracking form and shipment results, respectively.

3. Open the "wtp-track-shipment-form.php" file and customize the HTML and CSS code to modify the tracking form's appearance and layout. You can add your own styling, adjust the form structure, or include additional elements as per your design requirements. Make sure to preserve the necessary form functionality, such as the input field for the tracking code and the track button.

4. Similarly, open the "wtp-track-shipment-results.php" file and customize the HTML and CSS code to tailor the appearance of the shipment results section. You can modify the layout, typography, colors, and add any additional information or styling that aligns with your desired design.

5. Once you have made the necessary changes to the templates, save the files and upload them to the "wptrackpro" folder in your WordPress theme directory, replacing the existing templates.

6. After updating the templates, the changes will take effect on your WP TrackPro plugin. You can now refresh the tracking page to see the customized tracking form and shipment results reflecting your design modifications.

<b>Notes:</b>
- If changing the fields on both Product Information and Shipment History the name should start on <code>wtp-</code>. Example <code>wtp-test or wtp-shipment-name</code>
- If changing the shipment history fields the <code>wtp-shipment-status and wtp-field-shipment-history-id</code> postmeta key should be on the edited fields.
- If changing the product information fields the <code>wtp-field-product-info-id</code> postmeta key should be on the edited fields.
- Please refer to this files <code>/wp-trackpro/admin/assets/js/json/wtp-shipment-history.json OR /wp-trackpro/admin/assets/js/json/wtp-fields.json</code>


Please contact me if you need a custom features or feedbacks at <a href="mailto:wptrackpro@gmail.com">wptrackpro@gmail.com</a>. Thank you!

