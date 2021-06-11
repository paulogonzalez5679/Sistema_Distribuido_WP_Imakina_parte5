<?php

class WebinarSysteemHelperFunctions extends WebinarSysteem
{
    public static function is_media_element_js_player($source)
    {
        return !in_array($source, array('youtubelive', 'vimeo', 'image', 'iframe'));
    }

    public static function get_post_meta_with_default($post_id, $key, $default = '')
    {
        $value = get_post_meta($post_id, $key, true);

        if (empty($value)) {
            return $default;
        }

        return $value;
    }

    public static function get_post_meta_content_with_default($post_id, $key, $default)
    {
        $value = WebinarSysteemHelperFunctions::get_post_meta_with_default($post_id, $key, $default);
        return apply_filters('meta_content', $value);
    }

    /*
     * WordPress adds smartquotes which can break the parse_args function below so we replace them with normal quotes
     */

    public static function remove_smart_quotes($content) {

        $content = str_replace(
            ["\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"],
            ["'", "'", '"', '"', '-', '--', '...'], $content);

        $content = str_replace(
            [chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)],
            ["'", "'", '"', '"', '-', '--', '...'], $content);

        return $content;
    }

    /*
     * Hi [attendee-name],
     *
     *  Thank you for your registration for the webinar. Below you will find the details of the webinar.
     *
     * Webinar name: [webinar-title]
     * Date: [webinar-date]
     * Time: [webinar-time]
     * Timezone: [webinar-timezone]
     *
     * [webinar-link-button text="Join the webinar"]
     *
     * Regards
     * My Great Blog
    */

    protected static function parse_args($args_text) {
        $result = [];

        preg_match_all(
            "/\s*([^=]+)=[\"|\”]([^\"]*)[\"|\”]\s*/",
            self::remove_smart_quotes($args_text),
            $matches,
            PREG_OFFSET_CAPTURE
        );

        if ($matches == null || count($matches) != 3) {
            return [];
        }

        $keys = $matches[1];
        $values = $matches[2];

        for ($index = 0; $index < count($keys); $index += 1) {
            $result[$keys[$index][0]] = $values[$index][0];
        }

        return (object) $result;
    }

    public static function replace_tags($text, $replacements, $is_html = true) {
        foreach ($replacements as $what => $with) {
            while (true) {
                $matches = [];
                $count = preg_match(
                    "/\[$what(\s.*?)?\](?:([^\[]+)?\[\/$what\])?/",
                    $text,
                    $matches,
                    PREG_OFFSET_CAPTURE
                );

                if ($count == 0 || sizeof($matches) == 0) {
                    break;
                }

                // PHP regex is fun :)
                $match_string = $matches[0][0];

                if (is_callable($with)) {
                    $args = sizeof($matches) > 1
                        ? self::parse_args($matches[1][0])
                        : [];

                    $replacement = $with($args);
                } else {
                    $replacement = $with;
                }

                $text = str_replace($match_string, $replacement, $text);
            }
        }

        return $text;
    }

    public static function add_hash_to_color($color) {
        if (strlen($color) == 6 && $color[0] != '#') {
            return '#'.$color;
        }
        return $color;
    }

    public static function generate_uuid() {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public static function add_param_to_url($url, $params) {
        return $url.(parse_url($url, PHP_URL_QUERY) ? '&' : '?').$params;
    }
}
