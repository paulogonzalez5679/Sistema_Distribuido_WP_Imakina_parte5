<?php
// uncomment this line for testing
//set_site_transient( 'update_plugins', null );
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

class WebinarSysteemUpdater {
    private $api_url = '';
    private $api_data = array();
    private $name = '';
    private $slug = '';
    private $version = '';

    function __construct($api_url, $plugin_file, $api_data = null) {

        global $edd_plugin_data;

        $this->api_url = trailingslashit($api_url);
        $this->api_data = $api_data;
        $this->name = plugin_basename($plugin_file);
        $this->slug = basename($plugin_file, '.php');
        $this->version = $api_data['version'];

        $edd_plugin_data[$this->slug] = $this->api_data;

        $this->init();
    }

    public function init() {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_update']);
        add_filter('plugins_api', [$this, 'plugins_api_filter'], 10, 3);
        add_action('admin_init', [$this, 'show_changelog']);
        remove_action('after_plugin_row_'.$this->name, 'wp_plugin_update_row', 10);
        add_action('after_plugin_row_'.$this->name, [$this, 'show_update_notification'], 10, 2);
    }

    function check_update($data) {

        global $pagenow;

        if (!is_object($data)) {
            $data = new stdClass;
        }

        if ('plugins.php' == $pagenow && is_multisite()) {
            return $data;
        }

        if (empty($data->response) || empty($data->response[$this->name])) {

            $version_info = $this->get_plugin_version(['slug' => $this->slug]);

            if ($version_info !== false && is_object($version_info) && isset($version_info->new_version)) {

                if (version_compare($this->version, $version_info->new_version, '<')) {
                    $data->response[$this->name] = $version_info;
                }

                $data->last_checked = time();
                $data->checked[$this->name] = $this->version;
            }
        }

        return $data;
    }

    public function show_update_notification($file, $plugin) {

        if (
            !current_user_can('update_plugins') ||
            !is_multisite() ||
            $this->name != $file
        ) {
            return;
        }

        // Remove our filter on the site transient
        remove_filter('pre_set_site_transient_update_plugins', [$this, 'check_update'], 10);

        // try to get the value from cache
        $update_cache = get_site_transient('update_plugins');

        $update_cache = is_object($update_cache)
            ? $update_cache
            : new stdClass();

        if (empty($update_cache->response) || empty($update_cache->response[$this->name])) {

            $cache_key = md5('edd_plugin_' . sanitize_key($this->name) . '_version_info');
            $version_info = get_transient($cache_key);

            if (false === $version_info) {
                $version_info = $this->get_plugin_version(['slug' => $this->slug]);
                set_transient($cache_key, $version_info, 3600);
            }

            if (!is_object($version_info)) {
                return;
            }

            if (version_compare($this->version, $version_info->new_version, '<')) {
                $update_cache->response[$this->name] = $version_info;
            }

            $update_cache->last_checked = time();
            $update_cache->checked[$this->name] = $this->version;

            set_site_transient('update_plugins', $update_cache);
        } else {
            $version_info = $update_cache->response[$this->name];
        }

        // Restore our filter
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));

        if (!empty($update_cache->response[$this->name]) && version_compare($this->version, $version_info->new_version, '<')) {

            // build a plugin list row, with update notification
            $wp_list_table = _get_list_table('WP_Plugins_List_Table');
            echo '<tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="update-message">';

            $changelog_link = self_admin_url('index.php?edd_sl_action=view_plugin_changelog&plugin=' . $this->name . '&slug=' . $this->slug . '&TB_iframe=true&width=772&height=911');

            if (empty($version_info->download_link)) {
                printf(
                    __('There is a new version of %1$s available. <a target="_blank" class="thickbox" href="%2$s">View version %3$s details</a>.', 'easy-digital-downloads'),
                    esc_html($version_info->name),
                    esc_url($changelog_link),
                    esc_html($version_info->new_version)
                );
            } else {
                printf(
                    __('There is a new version of %1$s available. <a target="_blank" class="thickbox" href="%2$s">View version %3$s details</a> or <a href="%4$s">update now</a>.', 'easy-digital-downloads'),
                    esc_html($version_info->name),
                    esc_url($changelog_link),
                    esc_html($version_info->new_version),
                    esc_url(wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $this->name, 'upgrade-plugin_' . $this->name))
                );
            }

            do_action("in_plugin_update_message-{$file}", $plugin, $version_info);

            echo '</div></td></tr>';
        }
    }

    function plugins_api_filter($data, $action = '', $args = null) {
        if (
            !isset($args->slug) ||
            ($args->slug != $this->slug) ||
            $action != 'plugin_information'
        ) {
            return $data;
        }

        $api_response = $this->get_plugin_version([
            'slug' => $this->slug,
            'is_ssl' => is_ssl(),
            'fields' => [
                'banners' => false, // These will be supported soon hopefully
                'reviews' => false
            ]
        ]);

        if ($api_response != false) {
            $data = $api_response;
        }

        return $data;
    }

    private function get_plugin_version($data) {
        $data = array_merge($this->api_data, $data);

        if ($data['slug'] != $this->slug) {
            return false;
        }

        if ($this->api_url == home_url()) {
            // Don't allow a plugin to ping itself
            return false;
        }

        $api_params = [
            'edd_action' => 'get_version',
            'license' => !empty($data['license']) ? $data['license'] : '',
            'item_name' => isset($data['item_name']) ? $data['item_name'] : false,
            'item_id' => isset($data['item_id']) ? $data['item_id'] : false,
            'slug' => $data['slug'],
            'author' => $data['author'],
            'url' => home_url(),
            'beta' => $data['beta_enabled'] ? '1' : '0'
        ];

        $request = wp_remote_post($this->api_url, [
            'timeout' => 15,
            'sslverify' => false,
            'body' => $api_params
        ]);

        if (!is_wp_error($request)) {
            $request = json_decode(wp_remote_retrieve_body($request));
        }

        if (!$request || !isset($request->sections)) {
            return false;
        }

        $request->sections = maybe_unserialize($request->sections);

        return $request;
    }

    public function show_changelog() {

        global $edd_plugin_data;

        if (empty($_REQUEST['edd_sl_action']) ||
            empty($_REQUEST['plugin']) ||
            empty($_REQUEST['slug'] ||
            'view_plugin_changelog' != $_REQUEST['edd_sl_action'])
        ) {
            return;
        }

        if (!current_user_can('update_plugins')) {
            wp_die(
                __('You do not have permission to install plugin updates', 'easy-digital-downloads'),
                __('Error', 'easy-digital-downloads'),
                ['response' => 403]
            );
        }

        // load the data from cache
        $data = $edd_plugin_data[$_REQUEST['slug']];
        $cache_key = md5('edd_plugin_' . sanitize_key($_REQUEST['plugin']) . '_version_info');
        $version_info = get_transient($cache_key);

        if ($version_info == false) {
            $api_params = [
                'edd_action' => 'get_version',
                'item_name' => isset($data['item_name']) ? $data['item_name'] : false,
                'item_id' => isset($data['item_id']) ? $data['item_id'] : false,
                'slug' => $_REQUEST['slug'],
                'author' => $data['author'],
                'url' => home_url(),
                'beta' => $data['beta_enabled'] ? '1' : '0'
            ];

            $request = wp_remote_post($this->api_url, [
                'timeout' => 15,
                'sslverify' => false,
                'body' => $api_params
            ]);

            if (!is_wp_error($request)) {
                $version_info = json_decode(wp_remote_retrieve_body($request));
            }

            if (!empty($version_info) && isset($version_info->sections)) {
                $version_info->sections = maybe_unserialize($version_info->sections);
            } else {
                $version_info = false;
            }

            set_transient($cache_key, $version_info, 3600);
        }

        if (!empty($version_info) && isset($version_info->sections['changelog'])) {
            echo '<div style="background:#fff;padding:10px;">'.$version_info->sections['changelog'].'</div>';
        }

        exit;
    }

    public static function run_updates() {
        new WebinarSysteemUpdater(
            WSWEB_STORE_URL,
            WSWEB_FILE, [
                'version' => WPWS_PLUGIN_VERSION,
                'license' => WebinarSysteemLicense::get_license_for_update(),
                'item_name' => WSWEB_ITEM_NAME,
                'author' => 'WebinarPress',
                'beta_enabled' => WebinarSysteemSettings::instance()->get_enable_beta_updates()
            ]
        );
    }
}
