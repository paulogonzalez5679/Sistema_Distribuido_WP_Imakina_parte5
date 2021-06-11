<?php

class WebinarSysteemUnsubscribe
{
    public static function prepare_email_for_db($email) {
        return strtolower($email);
    }

    public static function is_unsubscribed($email) {
        global $wpdb;
        $table = WebinarSysteemTables::get_unsubscribe();

        return (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT count(email)
                FROM {$table}
                WHERE email=%s",
                self::prepare_email_for_db($email)
            )
        ) > 0;
    }

    public static function unsubscribe($email) {
        if (!self::is_unsubscribed($email)) {
            WebinarSysteemLog::log('Unsubscribing '.$email);
            global $wpdb;
            $wpdb->insert(
                WebinarSysteemTables::get_unsubscribe(), [
                    'email' => self::prepare_email_for_db($email)
                ]
            );
        }
    }

    public static function subscribe($email) {
        global $wpdb;
        WebinarSysteemLog::log('Re-subscribing '.$email);
        $wpdb->delete(
            WebinarSysteemTables::get_unsubscribe(), [
                'email' => self::prepare_email_for_db($email)
            ]
        );
    }
}
