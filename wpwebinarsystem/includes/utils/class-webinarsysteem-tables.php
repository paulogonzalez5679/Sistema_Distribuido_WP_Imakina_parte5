<?php

class WebinarSysteemTables {
    public static $chats = 'wswebinars_chats';
    public static $questions = 'wswebinars_questions';
    public static $subscribers = 'wswebinars_subscribers';
    public static $notifications = 'wswebinars_notifications';
    public static $email_queue = 'wswebinars_email_queue';
    public static $unsubscribe = 'wswebinars_unsubscribe';

    public static function get_chats() {
        global $wpdb;
        return $wpdb->prefix.self::$chats;
    }

    public static function get_questions() {
        global $wpdb;
        return $wpdb->prefix.self::$questions;
    }

    public static function get_subscribers() {
        global $wpdb;
        return $wpdb->prefix.self::$subscribers;
    }

    public static function get_notifications() {
        global $wpdb;
        return $wpdb->prefix.self::$notifications;
    }

    public static function get_email_queue() {
        global $wpdb;
        return $wpdb->prefix.self::$email_queue;
    }

    public static function get_unsubscribe() {
        global $wpdb;
        return $wpdb->prefix.self::$unsubscribe;
    }
}
