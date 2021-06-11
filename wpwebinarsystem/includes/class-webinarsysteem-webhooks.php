<?php

class WebinarSysteemWebHooks {

    public static function send_request($url, $data) {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // send the request
        curl_exec($curl);

        // was an error returned?
        $success = !curl_error($curl);

        // free up memory
        curl_close($curl);

        return $success;
    }

    protected static function  get_webhook_url($config_name) {
        $url = get_option($config_name);

        if (!$url || filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return null;
        }

        return $url;
    }

    protected  static function format_date($date) {
        return date('Y-m-d H:i:s', $date);
    }

    protected  static function send_webhook($webhook_config, $post_id, $attendee, $extra_data = []) {
        $webhook_url = WebinarSysteemWebHooks::get_webhook_url($webhook_config);

        if (!$webhook_url) {
            return false;
        }

        $attend_time = WebinarSysteem::get_webinar_time($post_id, $attendee);

        $data = array_merge([
            'webinar_id' => $post_id,
            'webinar_name' => get_the_title($post_id),
            'webinar_time' => date('Y-m-d H:i:s', $attend_time),
            'attendee_name' => $attendee->name,
            'attendee_email' => $attendee->email
        ], $extra_data);

        return WebinarSysteemWebHooks::send_request($webhook_url, $data);
    }

    public static function send_new_registration($post_id, $attendee) {
        WebinarSysteemWebHooks::send_webhook(
            '_wswebinar_new_registration_webhook',
            $post_id, $attendee);
    }

    public static function send_attended($post_id, $attendee) {
        WebinarSysteemWebHooks::send_webhook(
            '_wswebinar_attended_webinar_webhook',
            $post_id,
            $attendee,
            ['joined_at' => WebinarSysteemWebHooks::format_date(strtotime('now'))]);
    }

    public  static function test_webhook($webhook_url, $extra_data = []) {
        $data = array_merge([
            'webinar_id' => 1,
            'webinar_name' => 'Test Webinar Name',
            'webinar_time' =>  date("Y-m-d H:i:s"),
            'attendee_name' => 'Frank Spencer',
            'attendee_email' => 'frank@example.com'
        ], $extra_data);

        return WebinarSysteemWebHooks::send_request($webhook_url, $data);
    }

    public static function test_new_registration($webhook_url) {
        return WebinarSysteemWebHooks::test_webhook($webhook_url);
    }

    public static function test_attended_webinar($webhook_url) {
        return WebinarSysteemWebHooks::test_webhook(
            $webhook_url,
            ['joined_at' => WebinarSysteemWebHooks::format_date(strtotime('now'))]);
    }
}
