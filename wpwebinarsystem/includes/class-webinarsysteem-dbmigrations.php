<?php

class WebinarsysteemDbMigrations {

    private $db_version;
    private $migration_versions = array(
        10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23,
        24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36);

    private $charset;

    public function force_migrations_with_debug() {

        echo 'Running db migrations<br>';

        // enable debugging
        ini_set('display_startup_errors', 1);
        ini_set('display_errors', 1);
        error_reporting(-1);

        // run the migrations
        $this->run_migrations(true);

        die('Done<br>');
    }

    public function run_migrations($force_from_start = false) {
        $this->set_attributes();

        $current_db_version = $this->db_version;

        if ($current_db_version >= end($this->migration_versions) && !$force_from_start) {
            return;
        }

        foreach ($this->migration_versions as $version) {
            if ($current_db_version < $version || $force_from_start) {
                $function_to_call = "db_migration_$version";

                if ($force_from_start) {
                    echo 'Running migrations:'.$function_to_call.'<br>';
                }

                if ($this->$function_to_call()) {
                    $current_db_version = $version;
                }
            }
        }

        update_option(WSWEB_OPTION_PREFIX.'db_version', $current_db_version);
    }

    private function db_delta($sql) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        return true;
    }

    private function set_attributes() {
        // change previously saved double value for the version into an integer.
        $saved_version = get_option(WSWEB_OPTION_PREFIX . 'db_version', 0);
        if ($saved_version == "1.0") {
            update_option(WSWEB_OPTION_PREFIX . 'db_version', 10);
        }

        global $wpdb;

        $this->db_version = (int) get_option(WSWEB_OPTION_PREFIX . 'db_version', 0);

        $charset_collate = '';
        if (!empty($wpdb->charset)) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }
        if (!empty($wpdb->collate)) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        $this->charset = $charset_collate;
    }

    /*
     * ------------------------------------------------------------------------
     * Migrations
     * ------------------------------------------------------------------------
     */

    private function db_migration_10() { // create first tables
        $sql1 = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "questions (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            question text NOT NULL,
            webinar_id int(11) NOT NULL,
            PRIMARY KEY (id)
        ) $this->charset;";

        return $this->db_delta($sql1);
    }

    private function db_migration_11() {
        $sql1 = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            secretkey varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY (id)
        )" . $this->charset . ";";

        return $this->db_delta($sql1);
    }

    private function db_migration_12() {
        global $wpdb;
        $loop = new WP_Query(array('post_type' => 'wswebinars'));
        if ($loop->have_posts()) {
            while ($loop->have_posts()) {
                $loop->the_post();
                $subs = get_post_meta(get_the_ID(), '_wswebinar_subscribers', false);
                foreach ($subs as $sub) {
                    $array = unserialize($sub);
                    $num = $wpdb->insert(
                        WSWEB_DB_TABLE_PREFIX . "subscribers", array(
                            'name' => $array['name'],
                            'email' => $array['email'],
                            'time' => $array['date'],
                            'secretkey' => $array['secretkey'],
                            'webinar_id' => get_the_ID(),
                            'onehourmailsent' => $array['1hourmailsent'] == true ? 1 : 0,
                            'onedaymailsent' => $array['1daymailsent'] == true ? 1 : 0,
                            'wbstartingmailsent' => $array['wbstartingmailsent'] == true ? 1 : 0,
                            'replaymailsent' => $array['replaymailsent'] == true ? 1 : 0,
                        )
                    );
                }
            }
        }
        return true;
    }

    private function db_migration_13()
    {
        $sql1 = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "notifications (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            attendee_id int(11) UNSIGNED NOT NULL,
            notification_type int(2) NOT NULL,
            sent_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) " . $this->charset . ";";

        return $this->db_delta($sql1);
    }

    private function db_migration_14()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            secretkey varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            exact_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            active int(1) UNSIGNED NOT NULL DEFAULT 1,
            PRIMARY KEY (id)
        )" . $this->charset . ";";
        return $this->db_delta($sql);
    }

    private function db_migration_15()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            secretkey varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            exact_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            last_seen datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            active int(1) UNSIGNED NOT NULL DEFAULT 1,
            PRIMARY KEY (id)
        )" . $this->charset . ";";
        return $this->db_delta($sql);
    }

    private function db_migration_16()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            secretkey varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            exact_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            last_seen datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            active int(1) UNSIGNED NOT NULL DEFAULT 1,
            high_five int(1) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        )" . $this->charset . ";";
        return $this->db_delta($sql);
    }

    private function db_migration_17()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "chats (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            webinar_id int(11) NOT NULL,
            `admin` int(1) UNSIGNED NOT NULL DEFAULT 0,
            private int(1) UNSIGNED NOT NULL DEFAULT 0,
            attendee_id int(11) UNSIGNED NOT NULL,
            content text NOT NULL,
            timestamp DATETIME NOT NULL,
            PRIMARY KEY (id)
            )" . $this->charset . ";";

        return $this->db_delta($sql);
    }

    private function db_migration_18()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            secretkey varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            exact_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            last_seen datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            active int(1) UNSIGNED NOT NULL DEFAULT 1,
            high_five int(1) UNSIGNED NOT NULL DEFAULT 0,
            attended int(1) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        )" . $this->charset . ";";

        return $this->db_delta($sql);
    }
    
    private function db_migration_19()
    {
        return null;
    }

    private function db_migration_20()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            custom_fields TEXT NULL,
            secretkey varchar(32) NOT NULL,
            random_key varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            exact_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            last_seen datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            active int(1) UNSIGNED NOT NULL DEFAULT 1,
            high_five int(1) UNSIGNED NOT NULL DEFAULT 0,
            attended int(1) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        )" . $this->charset . ";";

        return $this->db_delta($sql);
    }
    
    private function db_migration_21() {
        $sql1 = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "notifications (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            attendee_id int(11) UNSIGNED NOT NULL,
            notification_type int(2) NOT NULL,
            sent_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) " . $this->charset . ";";

        return $this->db_delta($sql1);
    }

    // convert secretkey and random_key to varchar so we can add an index
    private function db_migration_22()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            custom_fields TEXT NULL,
            secretkey varchar(32) NOT NULL,
            random_key varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            exact_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            last_seen datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            active int(1) UNSIGNED NOT NULL DEFAULT 1,
            high_five int(1) UNSIGNED NOT NULL DEFAULT 0,
            attended int(1) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        )" . $this->charset . ";";

        return $this->db_delta($sql);
    }

    private function db_migration_23()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            custom_fields TEXT NULL,
            secretkey varchar(32) NOT NULL,
            random_key varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            exact_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            last_seen datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            active int(1) UNSIGNED NOT NULL DEFAULT 1,
            high_five int(1) UNSIGNED NOT NULL DEFAULT 0,
            attended int(1) UNSIGNED NOT NULL DEFAULT 0,
            INDEX `wpws_subscribers_secretkey` (`secretkey`),
            INDEX `wpws_subscribers_random_key` (`random_key`),
            INDEX `wpws_subscribers_lookup` (`webinar_id`, `email`, `secretkey`),
            INDEX `wpws_subscribers_online` (`webinar_id`, `last_seen`),
            PRIMARY KEY (id)
        )" . $this->charset . ";";

        return $this->db_delta($sql);
    }

    private function db_migration_24()
    {
        // run this again to fix missing tables from users who installed
        // before issue 507 was fixed
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "chats (
            id int(11) UNSIGNED AUTO_INCREMENT,		
            webinar_id int(11) NOT NULL,
            `admin` int(1) UNSIGNED NOT NULL DEFAULT 0,
            private int(1) UNSIGNED NOT NULL DEFAULT 0,
            attendee_id int(11) UNSIGNED NOT NULL,
            content text NOT NULL,
            timestamp DATETIME NOT NULL,
            PRIMARY KEY (id)
            )" . $this->charset . ";";

        return $this->db_delta($sql);
    }

    // add seconds_attended field
    private function db_migration_25()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            custom_fields TEXT NULL,
            secretkey varchar(32) NOT NULL,
            random_key varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            exact_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            last_seen datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            active int(1) UNSIGNED NOT NULL DEFAULT 1,
            high_five int(1) UNSIGNED NOT NULL DEFAULT 0,
            attended int(1) UNSIGNED NOT NULL DEFAULT 0,
            seconds_attended int(11) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        )" . $this->charset . ";";

        return $this->db_delta($sql);
    }

    private function db_migration_26() { // create first tables
        $sql1 = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "questions (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            question text NOT NULL,
            webinar_id int(11) NOT NULL,
            asked_by int(11) UNSIGNED DEFAULT NULL,
            answer text DEFAULT NULL,
            answered_by int(11) UNSIGNED DEFAULT NULL,
            answered_at datetime DEFAULT NULL,
            is_private int(1) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        ) $this->charset;";

        return $this->db_delta($sql1);
    }

    private function db_migration_27() { // create first tables
        $sql1 = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "questions (
            id int(11) UNSIGNED AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            question text NOT NULL,
            webinar_id int(11) NOT NULL,
            asked_by int(11) UNSIGNED DEFAULT NULL,
            answer text DEFAULT NULL,
            answered_by int(11) UNSIGNED DEFAULT NULL,
            answered_at datetime DEFAULT NULL,
            is_private int(1) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        ) $this->charset;";

        return $this->db_delta($sql1);
    }

    private function db_migration_28() { // create first tables
        $sql1 = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "questions (
            id bigint(20) UNSIGNED AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            question text NOT NULL,
            webinar_id int(11) NOT NULL,
            asked_by int(11) UNSIGNED DEFAULT NULL,
            answer text DEFAULT NULL,
            answered_by int(11) UNSIGNED DEFAULT NULL,
            answered_at datetime DEFAULT NULL,
            is_private int(1) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        ) $this->charset;";

        return $this->db_delta($sql1);
    }

    private function db_migration_29()
    {
        // run this again to fix missing tables from users who installed
        // before issue 507 was fixed
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "chats (
            id bigint(20) UNSIGNED AUTO_INCREMENT,
            webinar_id int(11) NOT NULL,
            `admin` int(1) UNSIGNED NOT NULL DEFAULT 0,
            private int(1) UNSIGNED NOT NULL DEFAULT 0,
            attendee_id int(11) UNSIGNED NOT NULL,
            content text NOT NULL,
            timestamp DATETIME NOT NULL,
            PRIMARY KEY (id)
            )" . $this->charset . ";";

        return $this->db_delta($sql);
    }

    private function db_migration_30()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            custom_fields TEXT NULL,
            secretkey varchar(32) NOT NULL,
            random_key varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            exact_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            last_seen datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            active int(1) UNSIGNED NOT NULL DEFAULT 1,
            high_five int(1) UNSIGNED NOT NULL DEFAULT 0,
            attended int(1) UNSIGNED NOT NULL DEFAULT 0,
            seconds_attended int(11) UNSIGNED NOT NULL DEFAULT 0,
            newly_registered int(1) UNSIGNED NOT NULL DEFAULT 1,
            PRIMARY KEY (id)
        )" . $this->charset . ";";

        return $this->db_delta($sql);
    }

    private function db_migration_31() {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "email_queue (
            id int(11) UNSIGNED AUTO_INCREMENT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            args text NOT NULL,
            PRIMARY KEY (id)
        ) " . $this->charset . ";";

        return $this->db_delta($sql);
    }

    private function db_migration_32()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            custom_fields TEXT NULL,
            secretkey varchar(32) NOT NULL,
            random_key varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            exact_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            last_seen datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            active int(1) UNSIGNED NOT NULL DEFAULT 1,
            high_five int(1) UNSIGNED NOT NULL DEFAULT 0,
            attended int(1) UNSIGNED NOT NULL DEFAULT 0,
            seconds_attended int(11) UNSIGNED NOT NULL DEFAULT 0,
            newly_registered int(1) UNSIGNED NOT NULL DEFAULT 1,
            INDEX `wpws_subscribers_secretkey` (`secretkey`),
            INDEX `wpws_subscribers_random_key` (`random_key`),
            INDEX `wpws_subscribers_lookup` (`webinar_id`, `email`, `secretkey`),
            INDEX `wpws_subscribers_online` (`webinar_id`, `last_seen`),
            INDEX `wpws_subscribers_exact_time` (`exact_time`),
            PRIMARY KEY (id)
        )" . $this->charset . ";";

        return $this->db_delta($sql);
    }

    private function db_migration_33()
    {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "subscribers (
            id int(11) UNSIGNED AUTO_INCREMENT,
            name tinytext NOT NULL,
            email varchar(256) NOT NULL,
            custom_fields TEXT NULL,
            secretkey varchar(64) NOT NULL,
            random_key varchar(32) NOT NULL,
            onehourmailsent int(1) NOT NULL DEFAULT 0,
            onedaymailsent int(1) NOT NULL DEFAULT 0,
            wbstartingmailsent int(1) NOT NULL DEFAULT 0,
            replaymailsent int(1) NOT NULL DEFAULT 0,
            webinar_id int(11) NOT NULL,
            exact_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            watch_day varchar(3),
            watch_time time,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            last_seen datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            active int(1) UNSIGNED NOT NULL DEFAULT 1,
            high_five int(1) UNSIGNED NOT NULL DEFAULT 0,
            attended int(1) UNSIGNED NOT NULL DEFAULT 0,
            seconds_attended int(11) UNSIGNED NOT NULL DEFAULT 0,
            newly_registered int(1) UNSIGNED NOT NULL DEFAULT 1,
            INDEX `wpws_subscribers_secretkey` (`secretkey`),
            INDEX `wpws_subscribers_random_key` (`random_key`),
            INDEX `wpws_subscribers_lookup` (`webinar_id`, `email`, `secretkey`),
            INDEX `wpws_subscribers_online` (`webinar_id`, `last_seen`),
            INDEX `wpws_subscribers_exact_time` (`exact_time`),
            PRIMARY KEY (id)
        )" . $this->charset . ";";

        return $this->db_delta($sql);
    }

    private function db_migration_34()
    {
        // Check for and fix duplicate cache keys introduced by a bug in the webinar duplication routine
        try {
            $keys = [];

            $args = [
                'posts_per_page' => -1,
                'orderby' => 'post_title',
                'order' => 'ASC',
                'post_type' => 'wswebinars',
                'post_status' => 'any',
                'suppress_filters' => true
            ];

            $post_data = get_posts($args);

            foreach ($post_data as $post) {
                $webinar = new WebinarSysteemWebinar($post);

                $key = $webinar->get_cache_key();

                if (strlen($key) == 0) {
                    continue;
                }

                if (array_key_exists($key, $keys)) {
                    // create a new key
                    $webinar->reset_cache_key();

                    // re-write the cache using the new id
                    WebinarSysteemCache::write_cache($webinar->id);

                    // set the new key..
                    $keys[$webinar->get_cache_key()] = $webinar->id;
                } else {
                    $keys[$key] = $webinar->id;
                }
            }
        } catch (Exception $e) {
            WebinarSysteemLog::log($e->getMessage().PHP_EOL.$e->getTraceAsString());
            return true;
        }

        return true;
    }

    private function db_migration_35() {
        $sql1 = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "notifications (
            id int(11) UNSIGNED AUTO_INCREMENT,
            attendee_id int(11) UNSIGNED NOT NULL,
            notification_type int(2) NOT NULL,
            sent_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) " . $this->charset . ";";

        $this->db_delta($sql1);

        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "email_queue (
            id int(11) UNSIGNED AUTO_INCREMENT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            args text NOT NULL,
            PRIMARY KEY (id)
        ) " . $this->charset . ";";

        return $this->db_delta($sql);
    }

    private function db_migration_36() {
        $sql = "CREATE TABLE " . WSWEB_DB_TABLE_PREFIX . "unsubscribe (
            email varchar(256) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (email)
        ) " . $this->charset . ";";

        return $this->db_delta($sql);
    }
}
