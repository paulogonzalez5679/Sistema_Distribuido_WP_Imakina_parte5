<?php

class WebinarSysteemActions
{
    static function fire_new_registration($webinar_id, $attendee) {
        $attend_time = WebinarSysteem::get_webinar_time($webinar_id, $attendee);

        $data = [
            'webinar_id' => $webinar_id,
            'webinar_name' => get_the_title($webinar_id),
            'webinar_time' => $attend_time,
            'attendee_name' => $attendee->name,
            'attendee_email' => $attendee->email
        ];

        do_action('wpws_new_registration', $data);
    }

    static function fire_attended($webinar_id, $attendee) {
        $attend_time = WebinarSysteem::get_webinar_time($webinar_id, $attendee);

        $data = [
            'webinar_id' => $webinar_id,
            'webinar_name' => get_the_title($webinar_id),
            'webinar_time' => $attend_time,
            'attendee_name' => $attendee->name,
            'attendee_email' => $attendee->email,
            'joined_at' => strtotime('now')
        ];

        do_action('wpws_attendee_attended', $data);
    }
}
