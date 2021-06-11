<?php

class WebinarSysteemSubscribe
{
    public static function subscribe($post_id, $email, $name)
    {
        self::subscribe_mailpoet($post_id, $email, $name);
        self::subscribe_mailpoet3($post_id, $email, $name);
        self::subscribe_enormail($post_id, $email, $name);
        self::subscribe_getresponse($post_id, $email, $name);
        self::subscribe_aweber($post_id, $email, $name);
        self::subscribe_activecampaign($post_id, $email, $name);
        self::subscribe_drip($post_id, $email, $name);
        self::subscribe_mailchimp($post_id, $email, $name);
        self::subscribe_convertkit($post_id, $email, $name);
        self::subscribe_mailrelay($post_id, $email, $name);
        self::subscribe_mailerlite($post_id, $email, $name);
        self::subscribe_newsletter_plugin($post_id, $email, $name);
    }

    public static function explode_name($user)
    {
        $first_name = "";
        $last_name = "";
        $parts = explode(" ", $user);
        $getvar = 0;

        if (count($parts) > 1) {
            $first_name = $parts[$getvar];
            $getvar++;
            for ($var = $getvar; $var < count($parts); $var++) {
                $last_name .= ' ' . $parts[$var];
            }
        } else {
            $first_name = $user;
        }

        return array('fname' => $first_name, 'lname' => $last_name);
    }

    public static function subscribe_mailpoet($post_id, $email, $name)
    {
        $list_id = get_post_meta($post_id, '_wswebinar_mailpoet_list', true);
        if (!class_exists('WYSIJA') || empty($list_id)) {
            return;
        }

        $exploded_name = self::explode_name($name);
        $list = array('list_ids' => array($list_id));
        $user_data = array('email' => $email, 'firstname' => $exploded_name['fname'], 'lastname' => $exploded_name['lname']);
        $data_subscriber = array('user' => $user_data, 'user_list' => $list);

        $helper_user = WYSIJA::get('user', 'helper');
        $helper_user->no_confirmation_email = true;
        $added_user_id = $helper_user->addSubscriber($data_subscriber);
        $helper_user->confirm_user($added_user_id);
        $helper_user->subscribe($added_user_id, true, false, array($list_id));
    }

    public static function subscribe_mailpoet3($post_id, $email, $name)
    {
        $list_id = get_post_meta($post_id, '_wswebinar_mailpoet3_list', true);

        if (!class_exists('\MailPoet\API\API') || empty($list_id)) {
            return;
        }

        $exploded_name = self::explode_name($name);
        $list = array($list_id);

        $user_data = [
            'email' => $email,
            'first_name' => $exploded_name['fname'],
            'last_name' => $exploded_name['lname']
        ];

        $options = [
            'send_confirmation_email' => false, // default: true
            'schedule_welcome_email' => false, // default: true
        ];

        try {
            \MailPoet\API\API::MP('v1')->addSubscriber($user_data, $list, $options);
        } catch (Exception $exception) {
            try {
                $subscriber = \MailPoet\API\API::MP('v1')->subscribeToLists($email, $list);
            } catch (Exception $exception) {
            }
        }
    }

    public static function subscribe_enormail($post_id, $email, $name)
    {
        $provider = get_post_meta($post_id, '_wswebinar_default_mail_provider', true);
        $api_key = get_option('_wswebinar_enormailapikey');
        $enormail_list = get_post_meta($post_id, '_wswebinar_enormail_list', true);

        if ($provider != 'enormail' || empty($enormail_list) || !class_exists('EM_Contacts') || get_option('_wswebinar_enormail_api_key_error')) {
            return false;
        }

        $contact = new EM_Contacts(new Em_Rest($api_key));
        $expolde_name = self::explode_name($name);
        $fname = $expolde_name['fname'];
        $lname = $expolde_name['lname'];

        $subscriber = $contact->add($enormail_list, $fname, $email, array('lastname' => $lname));
        $subscriber_status = json_decode($subscriber);

        if ($subscriber_status->status == 'error') {
            return false;
        }

        return true;
    }

    public static function subscribe_getresponse($post_id, $email, $name)
    {
        $api_key = get_option('_wswebinar_getresponseapikey');
        $campaign_id = get_post_meta($post_id, '_wswebinar_getresponse_list', true);
        $mail_provider = get_post_meta($post_id, '_wswebinar_default_mail_provider', true);

        if (empty($api_key) || $mail_provider != 'getresponse' || empty($campaign_id)) {
            return false;
        }

        $client = new GetResponseSimpleClient($api_key);

        $exploded_name = self::explode_name($name);
        $first_name = $exploded_name['fname'];
        $last_name = $exploded_name['lname'];

        $client->add_contact($campaign_id, $first_name . ' ' . $last_name, $email);

        return true;
    }

    public static function subscribe_aweber($post_id, $email, $name)
    {
        $default_mail_provider = get_post_meta($post_id, '_wswebinar_default_mail_provider', true);
        $aweber_list = get_post_meta($post_id, '_wswebinar_aweber_list', true);

        if (empty($aweber_list) || !WebinarsysteemMailingListIntegrations::is_aweber_connected() || $default_mail_provider != 'aweber') {
            return false;
        }

        try {
            $aweber = new WSAWeberAPI(WebinarsysteemMailingListIntegrations::$consumerKey,
                WebinarsysteemMailingListIntegrations::$consumerSecret);

            $token_secret = get_option('_wswebinar_aweber_accessTokenSecret');
            $token_secret_token = get_option('_wswebinar_aweber_accessToken');
            $account = $aweber->getAccount($token_secret_token, $token_secret);

            $list_id = $aweber_list;
            $listURL = "/accounts/$account->id/lists/$list_id";
            $list = $account->loadFromUrl($listURL);

            $params = array(
                'email' => $email,
                'name' => $name,
            );
            $subscribers = $list->subscribers;
            $subscribers->create($params);

            return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    public static function subscribe_activecampaign($post_id, $email, $name)
    {
        $mail_provider = get_post_meta($post_id, '_wswebinar_default_mail_provider', true);

        if (!WebinarsysteemMailingListIntegrations::is_activecampaign_connected() || $mail_provider != 'activecampaign') {
            return false;
        }

        $list_id = get_post_meta($post_id, '_wswebinar_activecampaign_list', true);
        $api_key = get_option('_wswebinar_activecampaignapikey');
        $url = get_option('_wswebinar_activecampaignurl');

        $expolde_name = self::explode_name($name);
        $first_name = $expolde_name['fname'];
        $last_name = $expolde_name['lname'];

        $ac = new WPWS_ActiveCampaign($url, $api_key);
        $subscriber = array(
            "email" => $email,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "p[$list_id]" => $list_id,
            "status[$list_id]" => 1, // "Active" status
        );

        $contact_sync = $ac->api("contact/sync", $subscriber);
        return $contact_sync->success;
    }

    public static function subscribe_drip($post_id, $email, $name)
    {
        $provider = get_post_meta($post_id, '_wswebinar_default_mail_provider', true);

        if ($provider != 'drip') {
            return false;
        }

        $api_key = get_option('_wswebinar_dripapikey');
        $drip_account = get_post_meta($post_id, '_wswebinar_drip_accounts');
        $drip_campaign = get_post_meta($post_id, '_wswebinar_drip_campaigns');
        $account_id = (string)$drip_account[0];
        $campaign_id = (string)$drip_campaign[0];

        $drip_api_params = self::get_create_or_update_drip_api_params($name, $email, $campaign_id);

        $_drip_api = new WP_GetDrip_API(empty($api_key) ? null : $api_key);

        if (!empty($campaign_id) && !empty($account_id) && $campaign_id != "no") {
            $_drip_api->subscribe_to_campaign($account_id, $campaign_id, $drip_api_params);
        } else {
            $_drip_api->create_or_update_subscriber($account_id, $drip_api_params);
        }

        return true;
    }

    public static function get_create_or_update_drip_api_params($name, $email, $campaign_id)
    {
        $custom_field = array();
        $custom_field['name'] = $name;
        $drip_api_params['email'] = $email;

        if (!empty($campaign_id) && $campaign_id != "no") {
            $drip_api_params['double_optin'] = 0;
        }

        $drip_api_params['custom_fields'] = $custom_field;

        return array('subscribers' => array($drip_api_params));
    }

    public static function subscribe_mailchimp($post_id, $email, $name)
    {
        $mail_provider = get_post_meta($post_id, '_wswebinar_default_mail_provider', true);
        $mail_list = get_post_meta($post_id, '_wswebinar_mailchimp_list', true);
        $api_key = get_option('_wswebinar_mailchimpapikey');

        if ($mail_provider != 'mailchimp' || empty($api_key) || empty($mail_list)) {
            return array('state' => false, 'reason' => 'API key or Group ID seems to be invalid');
        }

        $expolde_name = WebinarSysteemSubscribe::explode_name($name);
        $fname = $expolde_name['fname'];
        $lname = $expolde_name['lname'];

        $client = new MailChimpSimpleClient($api_key);
        return $client->add_contact($mail_list, $fname, $lname, $email);
    }

    public static function subscribe_convertkit($post_id, $email, $name)
    {
        $mail_provider = get_post_meta($post_id, '_wswebinar_default_mail_provider', true);

        if (!WebinarsysteemMailingListIntegrations::is_convertkit_connected() || $mail_provider != 'convertkit') {
            return false;
        }

        $form_id = get_post_meta($post_id, '_wswebinar_convertkit_form', true);
        $api_key = WebinarsysteemMailingListIntegrations::get_convertkit_api_key();

        $client = new calderawp\convertKit\forms($api_key);

        $exploded_name = self::explode_name($name);
        $first_name = $exploded_name['fname'];

        $res = $client->add($form_id, [
            'email' => $email,
            'first_name' => $first_name
        ]);

        return $res->success;
    }

    public static function subscribe_mailrelay($post_id, $email, $name)
    {
        $webinar = WebinarSysteemWebinar::create_from_id($post_id);

        if ($webinar->get_mail_provider() != 'mailrelay') {
            return false;
        }

        $settings = WebinarSysteemSettings::instance();

        $client = new MailrelaySimpleClient(
            $settings->get_mailrelay_key(),
            $settings->get_mailrelay_host()
        );

        return $client->add_contact($webinar->get_mail_list_id(), $name, $email);
    }

    public static function subscribe_mailerlite($post_id, $email, $name)
    {
        $webinar = WebinarSysteemWebinar::create_from_id($post_id);

        if ($webinar->get_mail_provider() != 'mailerlite') {
            return false;
        }

        $settings = WebinarSysteemSettings::instance();

        $client = new MailerliteSimpleClient(
            $settings->get_mailerlite_key()
        );

        return $client->add_contact($webinar->get_mail_list_id(), $name, $email);
    }

    public static function subscribe_newsletter_plugin($post_id, $email, $name) {
        if (class_exists('TNP') == false) {
            return false;
        }

        $webinar = WebinarSysteemWebinar::create_from_id($post_id);

        if ($webinar->get_mail_provider() != 'newsletter-plugin') {
            return false;
        }

        TNP::subscribe([
            'email' => $email,
            'name' => $name,
            'status' => 'C'
        ]);

        return true;
    }
}
