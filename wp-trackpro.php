<?php
/*
 * Plugin Name: WPTrackPro
 * Plugin URI: https://github.com/pjv17/wptrackpro
 * Description: Track, manage, and streamline your shipments with WP TrackPro, the all-in-one tracking shipment plugin for WordPress. Keep a detailed shipment history, provide tracking codes for clients, send notifications via email and SMS(Pro Version), and customize fields(Pro Version) to fit your business needs. 
 * Author: <a href="https://pjv17.github.io/pjvillanueva.github.io/">WPTrackPro</a>
 * Text Domain: wptrackpro
 * Domain Path: /languages
 * Version: 1.5.0
 */

/* 
License

Copyright (c) 2023 WPTRACKPRO

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

This plugin is also under GNU GENERAL PUBLIC LICENSE. Read more about here https://github.com/pjv17/wptrackpro/blob/master/LICENSE

*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('WP_TRACKPRO_VERSION', '1.0.0');
define('WP_TRACKPRO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WP_TRACKPRO_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WP_TRACKPRO_PLUGIN_FILE', __FILE__);

include_once WP_TRACKPRO_PLUGIN_PATH . 'includes/class-wp-trackpro.php';