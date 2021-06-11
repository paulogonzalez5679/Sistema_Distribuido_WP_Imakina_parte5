<?php

global $post;

// set the attendee as attended and update the last seen time
$attendee = WebinarSysteemAttendees::get_attendee($post->ID);

// get the webinar
$webinar = WebinarSysteemWebinar::create_from_id($post->ID);

// if we don't have a valid attendee redirect to the registration page..
if ($attendee == null) {
    wp_redirect($webinar->get_url());
    die();
}

$attendee_name = empty($attendee)
    ? ''
    : $attendee->name;

$attendee_name = explode(' ', $attendee_name);

$ajax_url = admin_url('admin-ajax.php');
$cache_url = WebinarSysteemCache::get_cache_url($post->ID, 2);
$reduce_server_load = get_option('_wswebinar_reduce_server_load') == 'on';

$webinar_start_time = WebinarSysteem::get_webinar_time($post->ID, $attendee);
$server_time_with_timezone = strtotime(WebinarSysteem::getTimezoneTime($post->ID));
$webinar_time_in_seconds = $server_time_with_timezone - $webinar_start_time;

// is this an automated replay?
$now = $webinar->get_now_in_timezone();

if ($now > $webinar_start_time + $webinar->get_duration() && $webinar->get_automated_replay_enabled()) {
    $webinar_start_time = $now;
    $webinar_time_in_seconds = 0;
}

// update the last active time for this webinar
$webinar->update_last_active_time();

// re-write the cache
WebinarSysteemCache::write_cache($post->ID);

$polyfill_script = WebinarSysteemJS::get_polyfill_path();
$script = WebinarSysteemJS::get_js_path() . '?v=' . WebinarSysteemJS::get_version();
$style = WebinarSysteemJS::get_css_path() . '?v=' . WebinarSysteemJS::get_version();

$boot_data = [
    'locale' => get_locale(),
    'language' => 'en',
    'ajax' => admin_url('admin-ajax.php'),
    'security' => wp_create_nonce(WebinarSysteemJS::get_nonce_secret()),
    'base' => WebinarSysteemJS::get_asset_path(),
    'plugin' => WebinarSysteemJS::get_plugin_path()
];

$is_team_member = current_user_can('manage_options');

$params = [
    'cache_url' => $cache_url,
    'secure_room_name' => $webinar->get_secure_room_name(),
    'secure_room_key' => $webinar->get_secure_room_key(),
    'reduce_server_load' => $reduce_server_load,
    'webinar_time_in_seconds' => $webinar_time_in_seconds,
    'webinar_start_time' => $webinar_start_time,
    'duration' => $webinar->get_duration(),
    'timezone_offset' => $webinar->get_timezone_offset() * 60,
    'attendee' => [
        'id' => (int) $attendee->id,
        'name' => $attendee->name,
        'email' => $attendee->email,
        'is_team_member' => $is_team_member
    ],
    'translations' => WebinarSysteemSettings::instance()->get_translations(),
    'scripts' => [
        'countdown' => $webinar->get_countdown_body_script_tag(),
        'webinar' => $webinar->get_live_page_body_script_tag()
    ]
];

// register the webinar
$media_server_settings = WebinarSysteemMediaServer::attempt_register_webinar($webinar);

if ($media_server_settings) {
    $params['media_server'] = [
        'id' => $media_server_settings->id,
        'host' => $media_server_settings->host,
        'port' => $media_server_settings->port,
        'secure' => $media_server_settings->secure,
        'access_key' => $webinar->get_access_key(),
        'team_member_key' => $is_team_member
            ? $webinar->get_team_member_key()
            : null
    ];
}

?>
<!DOCTYPE html>
<html class="wpws">
    <head>   
        <title><?php echo get_the_title(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:title" content="<?php the_title(); ?>">
        <meta property="og:url" content="<?php echo get_permalink($post->ID); ?>">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <?php if (WebinarSysteemJS::get_css_path()) { ?>
            <link rel='stylesheet' href="<?= $style ?>" type='text/css' media='all'/>
        <?php } ?>
        <?php if ($webinar->get_live_media_type() === 'twitch') { ?>
            <script src="https://player.twitch.tv/js/embed/v1.js"></script>
        <?php } ?>
        <?php if ($webinar->get_live_media_type() === 'jitsi') { ?>
            <script src='https://meet.jit.si/external_api.js'></script>
        <?php } ?>
    </head>

    <body>
        <div 
            id="wpws-live"
            data-params='<?= str_replace('\'', '&apos;', json_encode($params)) ?>'
        ></div>
        <script>
            ___wpws = <?php echo json_encode($boot_data) ?>;
        </script>

        <script src="<?= $polyfill_script ?>"></script>
        <script src="<?= $script ?>"></script>

        <!-- placeholder for the body script tag -->
        <div id="body_script"></div>
    </body>
</html>
