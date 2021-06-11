<?php
/*
  Plugin Name: WebinarPress Pro
  Plugin URI: https://getwebinarpress.com
  Description: Host live and automated webinars within your WordPress website, and customize everything.
  Version:2.24.24
  Author: WebinarPress
  Author URI: https://getwebinarpress.com
  License: GPLv2 or later
  Text Domain: _wswebinar
  Domain Path: ./localization/
 */

include 'includes/core-import.php';

$plug_version = '2.24.24';

define('WPWS_PLUGIN_VERSION', $plug_version);
define('WPWS_PLUGIN_FOLDER', __DIR__);
define('WPWS_MEDIA_SERVER_API', 'https://live.getwebinarpress.com');

new WebinarSysteem(__FILE__, __DIR__, WPWS_PLUGIN_VERSION);

function wpws_plugin_activated($plugin) {
    if ($plugin == plugin_basename(__FILE__)) {

        $settings = WebinarSysteemSettings::instance();

        if ($settings->has_run_once()) {
            return;
        }

        $settings->set_has_run();
        wp_redirect(admin_url('admin.php?page=wswbn-webinars'));
        exit();
    }
}

add_action('activated_plugin', 'wpws_plugin_activated');
