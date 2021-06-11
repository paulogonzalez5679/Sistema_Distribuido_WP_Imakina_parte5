<?php

class WebinarSysteemPages {
    protected static function write_page($id, $params = []) {
        $ajax_url = admin_url('admin-ajax.php');
        ?>
        <div
            id="<?= $id ?>"
            data-url="<?= $ajax_url ?>"
            data-params='<?= str_replace('\'', '&apos;', json_encode($params)) ?>'
        ></div>
        <?php
    }

    public static function registration_widgets() {
        wp_enqueue_editor();
        self::write_page("wpws-registration-widgets");
    }

    public static function webinar_list() {
        if (isset($_GET['webinar_id'])) {
            wp_enqueue_editor();
            // wp_enqueue_media();
            self::write_page("wpws-webinar-editor", [
                'webinar_id' => $_GET['webinar_id'],
                'enabled_mailinglist_providers' => WebinarsysteemMailingListIntegrations::get_enabled_providers(),
                'woo_commerce_is_enabled' => WebinarSysteemWooCommerceIntegration::is_ready(),
                'is_license_active' => WebinarSysteemLicense::is_license_active(),
                'is_cron_active' => WebinarSysteemCron::was_active_within(),
                'license_status' => WebinarSysteemLicense::get_license_status(),
                'use_realtime_servers' => WebinarSysteemSettings::instance()->get_use_realtime_servers(),
                'renewal_url' => WebinarSysteemLicense::get_renewal_url(),
                'translations' => WebinarSysteemSettings::instance()->get_translations(),
                'max_hosted_attendee_count' => WebinarSysteemLicense::get_max_hosted_attendee_count()
            ]);
            return;
        }

        self::write_page("wpws-webinar-list");
    }

    public static function new_webinar() {
        wp_enqueue_editor();

        self::write_page("wpws-webinar-editor", [
            'webinar_id' => null,
            'enabled_mailinglist_providers' => WebinarsysteemMailingListIntegrations::get_enabled_providers(),
            'woo_commerce_is_enabled' => WebinarSysteemWooCommerceIntegration::is_ready(),
            'is_license_active' => WebinarSysteemLicense::is_license_active(),
            'is_cron_active' => WebinarSysteemCron::was_active_within(),
            'license_status' => WebinarSysteemLicense::get_license_status(),
            'use_realtime_servers' => WebinarSysteemSettings::instance()->get_use_realtime_servers(),
            'renewal_url' => WebinarSysteemLicense::get_renewal_url(),
            'translations' => WebinarSysteemSettings::instance()->get_translations(),
            'max_hosted_attendee_count' => WebinarSysteemLicense::get_max_hosted_attendee_count()
        ]);
    }

    public static function attendees() {
        $webinar_id = isset($_GET['id']) ? $_GET['id'] : null;
        self::write_page("wpws-attendees", [
            'webinar_id' => (int) $webinar_id,
        ]);
    }

    public static function chats() {
        $webinar_id = isset($_GET['id']) ? $_GET['id'] : null;
        self::write_page("wpws-chats", [
            'webinar_id' => (int) $webinar_id,
        ]);
    }

    public static function questions() {
        $webinar_id = isset($_GET['id']) ? $_GET['id'] : null;
        self::write_page("wpws-questions", [
            'webinar_id' => (int) $webinar_id,
        ]);
    }

    public static function settings() {
        wp_enqueue_editor();
        self::write_page("wpws-settings", []);
    }

    public static function webinar_recordings() {
        self::write_page("wpws-webinar-recordings", [
            'license_key' => WebinarSysteemLicense::get_license()
        ]);
    }
}
