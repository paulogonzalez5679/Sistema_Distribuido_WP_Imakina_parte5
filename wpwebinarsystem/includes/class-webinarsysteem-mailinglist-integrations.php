<?php

class WebinarsysteemMailingListIntegrations {

    public static $consumerKey = "Akz3wAZDmnUvC4U8Bo1y4Fo1"; # For Aweber developer account.
    public static $consumerSecret = "agCxf2Av1IfEla2mPhJaN3UIHeppGUMxv3jU8Rjw";  # For Aweber developer account.

    public static function is_valid_enormail_api_key($key) {
        if (!empty($key)) {
            $acccount = new EM_Account(new Em_Rest($key));
            $api_check = $acccount->info($key);
            $decoded_apicheck = json_decode($api_check);

            if (isset($decoded_apicheck->error) && $decoded_apicheck->error) {
                $update_option_api_key_error = true;
                $valid_key = false;
            } else {
                $update_option_api_key_error = false;
                $valid_key = true;
            }
        } else {
            $update_option_api_key_error = false;
            $valid_key = false;
        }
        update_option('_wswebinar_enormail_api_key_error', $update_option_api_key_error);
        return $valid_key;
    }
    /**
	* Drip API Key validation
	* 
	* @param string $key API key
	* @return boolean
	*/
	public static function is_valid_drip_key($key) {
		$update_option_api_key_error = true;
		$valid_key = false;
		$_drip_api = new WP_GetDrip_API(empty( $key ) ? null : $key);
		if (!empty($key)){
			$valid = $_drip_api->validate_drip_token( $key );
			if(!$valid) {
				$update_option_api_key_error = true;
				$valid_key = false;
			} else {
				$update_option_api_key_error = false;
				$valid_key = true;
			}
		} else {
			$update_option_api_key_error = false;
			$valid_key = false;
		}
		update_option('_wswebinar_drip_api_key_error', $update_option_api_key_error);
		return $valid_key;
	}

    /**
     * aWeber API Key validation
     * 
     * @return boolean
     */
    public static function is_aweber_connected() {
        $has_tokens = false;
        $can_communicate = false;
        $token_secret = get_option('_wswebinar_aweber_accessTokenSecret');
        $token_secret_token = get_option('_wswebinar_aweber_accessToken');
        $has_tokens = (!empty($token_secret) & !empty($token_secret_token) ? TRUE : FALSE);

        if ($has_tokens) {
            $aweber = new WSAWeberAPI(self::$consumerKey, self::$consumerSecret);
            try {
                $account = $aweber->getAccount(get_option('_wswebinar_aweber_accessToken'), get_option('_wswebinar_aweber_accessTokenSecret'));
            } catch (Exception $ex) {
                update_option(WebinarSysteem::$lang_slug . '_aweber_key_revoked', true);
                self::revokeAweberConfig();
                return false;
            }

            $account_id = $account->id;
            $can_communicate = (!empty($account_id) ? true : false);
        }
        return ($has_tokens && $can_communicate ? TRUE : FALSE);
    }

    /**
     * Checks if ActiveCampaign API Key and URL is valid.
     * 
     * @param string $key
     * @param string $url
     * @return boolean
     */
    public static function is_activecampaign_connected($key = NULL, $url = NULL) {
        $key = $key ? $key : get_option('_wswebinar_activecampaignapikey');
        $url = $url ? $url : get_option('_wswebinar_activecampaignurl');

        if (!$key && !$url)
            return FALSE;

        $ac = new WPWS_ActiveCampaign($url, $key);
        if ((int) $ac->credentials_test())
            return TRUE;

        return FALSE;
    }

    /**
     * Get ActiveCampaign list of Lists. API: list/list
     * 
     * @return boolean|array
     */
    static function getActiveCampaignListList() {
        $key = get_option('_wswebinar_activecampaignapikey');
        $url = get_option('_wswebinar_activecampaignurl');
        $list = array();
        
        if (!$key || !$url)
            return FALSE;
        
        $ac = new WPWS_ActiveCampaign($url, $key);
        $result = $ac->api("list/list", array('ids' => 'all'));
        
        if (!$result->result_code) //Exit if response failed.
            return FALSE;
        
        foreach ($result as $a => $b)
            if (is_object($b))
                array_push($list, $b);
            
        return $list;
    }

    public static function validate_enormail_key($key) {
        $lists = new EM_Account(new Em_Rest($key));
        $set = $lists->info();

        $decoded_set = json_decode($set);
        return !isset($decoded_set->error);
    }

    public static function validate_drip_api_key($key) {
        $drip = new WP_GetDrip_API($key);
        return $drip->validate_drip_token($key);
    }

    public static function validate_activecampaign_api_key($key, $url) {
        $ac = new WPWS_ActiveCampaign($url, $key);
        return $ac->credentials_test();
    }

    public static function validate_convertkit_api_key($api_key = null) {
        $client = new calderawp\convertKit\forms($api_key);
        $response = $client->get_all();

        if (!$response) {
            return false;
        }

        return $response->success;
    }

    public static function validate_mailchimp_api_key($api_key) {
        $client = new MailChimpSimpleClient($api_key);
        return $client->get_lists() != null;
    }

    public static function validate_mailrelay_api_key($api_key, $host) {
        $client = new MailrelaySimpleClient($api_key, $host);
        return $client->get_lists() != null;
    }

    public static function is_mailrelay_connected() {
        $settings = WebinarSysteemSettings::instance();
        return strlen($settings->get_mailrelay_host()) > 0 &&
            strlen($settings->get_mailrelay_key());
    }

    public static function validate_mailerlite_api_key($api_key) {
        $client = new MailerliteSimpleClient($api_key);
        return $client->get_lists() != null;
    }

    public static function is_mailerlite_connected() {
        $settings = WebinarSysteemSettings::instance();
        return strlen($settings->get_mailerlite_key()) > 0;
    }

    public static function is_newsletterplugin_installed() {
        return class_exists('TNP');
    }

	/**
	* Get Drip Campaigns
	* 
	* @return Campaign List
	*/
	public static function getDripCampaigns(){
		
		$account_id = $_GET['account_id'];	

		$account_campaigns = array(
			array(
				'label' => '',
				'value' => ''
			)
		);
		$api_key = get_option('_wswebinar_dripapikey');
			if(!empty($account_id)){
						$_drip_api = new WP_GetDrip_API($api_key);
		$_drip_api->set_drip_api_token($api_key);
		$campaigns = $_drip_api->list_campaigns($account_id);
		if( ! empty( $campaigns )) {
			if ( 1 < $campaigns[ 'meta' ][ 'total_pages' ] ) {

					$all_campaigns = $campaigns[ 'campaigns' ];

					while ( $campaigns[ 'meta' ][ 'page' ] < $campaigns[ 'meta' ][ 'total_pages' ] ) {

						$campaigns = $_drip_api->list_campaigns( $account_id, $campaigns[ 'meta' ][ 'page' ] + 1 );

						if ( ! empty( $campaigns ) ) {

							$all_campaigns = array_merge( $all_campaigns, $campaigns[ 'campaigns' ] );

						}

					}
		}
		else
		{
			$all_campaigns = $campaigns[ 'campaigns' ];
		}
		foreach ( $all_campaigns as $campaign ) {

					$account_campaigns[ ] = array( 'label' => $campaign[ 'name' ], 'value' => $campaign[ 'id' ] );

				}
			}
	}
	
	echo json_encode($account_campaigns);
	wp_die();		

	}
    public static function getDripCampaignList($account_id){
		
	$account_campaigns = array(
			array(
				'label' => '',
				'value' => ''
			)
		);
		$api_key = get_option('_wswebinar_dripapikey');
			if(!empty($account_id)){
						$_drip_api = new WP_GetDrip_API($api_key);
		$_drip_api->set_drip_api_token($api_key);
		$campaigns = $_drip_api->list_campaigns($account_id);
		if( ! empty( $campaigns )) {
			if ( 1 < $campaigns[ 'meta' ][ 'total_pages' ] ) {

					$all_campaigns = $campaigns[ 'campaigns' ];

					while ( $campaigns[ 'meta' ][ 'page' ] < $campaigns[ 'meta' ][ 'total_pages' ] ) {

						$campaigns = $_drip_api->list_campaigns( $account_id, $campaigns[ 'meta' ][ 'page' ] + 1 );

						if ( ! empty( $campaigns ) ) {

							$all_campaigns = array_merge( $all_campaigns, $campaigns[ 'campaigns' ] );

						}

					}
		}
		else
		{
			$all_campaigns = $campaigns[ 'campaigns' ];
		}
		foreach ( $all_campaigns as $campaign ) {

					$account_campaigns[ ] = array( 'label' => $campaign[ 'name' ], 'value' => $campaign[ 'id' ] );

				}
			}
	}
	
	return $account_campaigns;		

	}
	/**
	* Get Drip Account Choices
	* 
	* @return
	*/
	public static function get_drip_account_lists($key) {
		$account_choices = [
            [
                'label' => '',
                'value' => ''
            ]
		];
		
		$_drip_api = new WP_GetDrip_API($key);
		$_drip_api->set_drip_api_token($key);
			
		$accounts = $_drip_api->list_accounts();
		
		if( !empty($accounts)) {
			foreach ($accounts['accounts'] as $account){
				$account_choices[] = array('label' => $account['name'], 'value' => $account['id'] );
			}
		}

		return $account_choices;
	}

    /*
     * Connect with Aweber Mailing API
     * Set cookies and update options.
     */
    public static function aweber_connect() {
        if (!isset($_GET['wswebinar_aweber_connect'])) {
            return;
        }

        $aweber = new WSAWeberAPI(self::$consumerKey, self::$consumerSecret);
        $_wswebinar_aweber_accessToken = get_option('_wswebinar_aweber_accessToken');
        if (empty($_wswebinar_aweber_accessToken)) {
            $webinar_aweber_access_token = get_option('_wswebinar_aweber_accessToken');
            if (empty($webinar_aweber_access_token)) {
                $auth_token = @$_GET['oauth_token'];
                if (empty($auth_token)) {
                    $callbackUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    list($requestToken, $requestTokenSecret) = $aweber->getRequestToken($callbackUrl);
                    update_option(WebinarSysteem::$lang_slug . '_aweber_request_token_secret', $requestTokenSecret);
                    setcookie('webinar_aweberrtkns', $requestTokenSecret);
                    header("Location: {$aweber->getAuthorizeUrl()}");
                    exit();
                }

                $aweber->user->tokenSecret = $_COOKIE['webinar_aweberrtkns'];
                $aweber->user->requestToken = $_GET['oauth_token'];
                $aweber->user->verifier = $_GET['oauth_verifier'];
                list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();

                update_option(WebinarSysteem::$lang_slug.'_aweber_accessTokenSecret', $accessTokenSecret);
                update_option(WebinarSysteem::$lang_slug.'_aweber_accessToken', $accessToken);
                update_option(WebinarSysteem::$lang_slug.'_aweber_key_success', 1);

                $home_url = home_url();
                header('Location: ' . "$home_url/wp-admin/admin.php?page=wswbn-settings#mailing-lists");
                exit();
            }
        }
    }
   
    public static function check_aweber_disconnected() {
        $showed = get_option('_wswebinar_aweber_key_revoked');
        if ($showed == 1) {
            ?>
            <div class="error">
                <p><?php echo sprintf(__('Unexpectedly aWeber has been disconnected from the server. You are no longer subscribed to aWeber mailinglist. For Changes go to <a href="%s">WebinarSysteem Settings</a>.', WebinarSysteem::$lang_slug), "admin.php?page=wswbn-settings"); ?></p>
            </div>
            <?php
            update_option(WebinarSysteem::$lang_slug . '_aweber_key_revoked', false);
        }
    }

    public static function validate_getresponse_key($key) {
        $client = new GetResponseSimpleClient($key);
        return $client->ping();
    }

    public static function get_getresponse_api_key() {
        return get_option('_wswebinar_getresponseapikey');
    }

    public static function is_getresponse_ready() {
        $key = self::get_getresponse_api_key();
        return ($key && strlen($key) > 0);
    }

    /*
     * Remoke the Aweber API configuration from the App.
     */
    
    public static function revokeAweberConfig() {
        unset($_COOKIE['webinar_aweberrtkns']);
        update_option(WebinarSysteem::$lang_slug . '_aweber_accessTokenSecret', '');
        update_option(WebinarSysteem::$lang_slug . '_aweber_accessToken', '');
        update_option(WebinarSysteem::$lang_slug . '_aweber_key_success', 1);
        return true;
    }

    public static function list_convertkit_forms($api_key) {
        if (!$api_key || strlen($api_key) == 0) {
            return [];
        }

        $client = new calderawp\convertKit\forms($api_key);
        $response = $client->get_all();

        if (!$response || !$response->success) {
            return [];
        }

        return $response->data->forms;
    }

    public static function get_convertkit_api_key() {
        return get_option('_wswebinar_convertkit_key');
    }

    public static function is_convertkit_connected() {
        $key = WebinarsysteemMailingListIntegrations::get_convertkit_api_key();
        return ($key && strlen($key) > 0);
    }

    public static function get_accounts_for_provider($provider) {
        switch ($provider) {
            case 'drip':
                $key = get_option('_wswebinar_dripapikey');
                $res = self::get_drip_account_lists($key);
                return array_map(function ($val) {
                    return (object) [
                        'id' => $val['value'],
                        'name' => $val['label']
                    ];
                }, $res);

            default:
                return [];
        }
    }

    public static function get_mailchimp_lists() {
        $key = get_option('_wswebinar_mailchimpapikey');
        $client = new MailChimpSimpleClient($key);
        $lists = $client->get_lists();

        return $lists == null
            ? []
            : $lists;
    }

    public static function get_mailpoet_lists() {
        if (!self::is_mailpoet_connected()) {
            return [];
        }

        $res = [];

        $model = WYSIJA::get('list', 'model');
        $lists = $model->get(
            ['name', 'list_id'],
            ['is_enabled' => 1]
        );

        foreach ($lists as $value) {
            $res[] = (object) [
                'id' => $value['list_id'],
                'name' => $value['name']
            ];
        }

        return $res;
    }

    public static function get_mailpoet3_lists() {
        if (!self::is_mailpoet3_connected()) {
            return [];
        }

        $res = [];

        $lists = \MailPoet\API\API::MP('v1')->getLists();

        foreach ($lists as $value) {
            $res[] = (object) [
                'id' => $value['id'],
                'name' => $value['name']
            ];
        }

        return $res;
    }

    public static function get_enormail_lists() {
        if (!self::is_enormail_connected()) {
            return [];
        }

        $res = [];

        $key = get_option('_wswebinar_enormailapikey');

        $api = new EM_Lists(new Em_Rest($key));
        $lists = json_decode($api->get());

        foreach ($lists as $list) {
            $res[] = (object) [
                'id' => $list->listid,
                'name' => $list->title
            ];
        }

        return $res;
    }

    public static function get_aweber_lists() {

        if (!self::is_aweber_connected()) {
            return [];
        }

        $res = [];

        $access_token = get_option('_wswebinar_aweber_accessToken');
        $secret = get_option('_wswebinar_aweber_accessTokenSecret');

        $aweber = new WSAWeberAPI(self::$consumerKey, self::$consumerSecret);
        $account = $aweber->getAccount($access_token, $secret);

        foreach ($account->lists as $list) {
            $res[] = (object) [
                'id' => $list->id,
                'name' => $list->name
            ];
        }

        return $res;
    }

    public static function get_convertkit_lists() {

        if (!self::is_convertkit_connected()) {
            return [];
        }

        $res = [];

        $forms = self::list_convertkit_forms(
            WebinarsysteemMailingListIntegrations::get_convertkit_api_key()
        );

        foreach ($forms as $form) {
            $res[] = (object) [
                'id' => $form->id,
                'name' => $form->name
            ];
        }

        return $res;
    }

    public static function get_getresponse_lists() {
        $key = self::get_getresponse_api_key();

        if (empty($key)) {
            return [];
        }

        $api = new GetResponseSimpleClient($key);
        $lists = $api->list_campaigns();
        $res = [];

        foreach ($lists as $key => $value) {
            $res[] = (object) [
                'id' => $value->campaignId,
                'name' => $value->name
            ];
        }

        return $res;
    }

    public static function get_mailinglist_lists_for_provider($provider, $account_id) {
        $settings = WebinarSysteemSettings::instance();

        switch ($provider) {
            case 'drip':
                $res = self::getDripCampaignList($account_id);
                return array_map(function ($val) {
                    return (object) [
                        'id' => $val['value'],
                        'name' => $val['label']
                    ];
                }, $res);

            case 'activecampaign':
                $res = self::getActiveCampaignListList();
                return array_map(function ($val) {
                    return (object) [
                        'id' => $val->id,
                        'name' => $val->name
                    ];
                }, $res);

            case 'mailchimp':
                return self::get_mailchimp_lists();

            case 'mailpoet':
                return self::get_mailpoet_lists();

            case 'mailpoet3':
                return self::get_mailpoet3_lists();

            case 'enormail':
                return self::get_enormail_lists();

            case 'aweber':
                return self::get_aweber_lists();

            case 'convertkit':
                return self::get_convertkit_lists();

            case 'getresponse':
                return self::get_getresponse_lists();

            case 'mailrelay':
                $mailrelay = new MailrelaySimpleClient(
                    $settings->get_mailrelay_key(),
                    $settings->get_mailrelay_host()
                );
                return $mailrelay->get_lists();

            case 'mailerlite':
                $mailerlite = new MailerliteSimpleClient(
                    $settings->get_mailerlite_key()
                );
                return $mailerlite->get_lists();

            default:
                return [];
        }
    }

    public static function is_mailpoet_connected() {
        return class_exists('WYSIJA');
    }

    public static function is_mailpoet3_connected() {
        return class_exists('\MailPoet\API\API');
    }

    public static function is_enormail_connected() {
        $key = get_option('_wswebinar_enormailapikey');
        $is_valid = self::is_valid_enormail_api_key($key);

        return class_exists('EM_Lists') && !empty($key) && $is_valid;
    }

    public static function is_drip_connected() {
        $key = get_option('_wswebinar_dripapikey');
        $is_valid = self::is_valid_drip_key($key);

        return !empty($key) && $is_valid;
    }

    public static function is_mailchimp_connected() {
        $key = get_option('_wswebinar_mailchimpapikey');
        $error = get_option('_wswebinar_mailchimp_api_key_error');
        return $key && !$error;
    }

    public static function get_enabled_providers() {
        $res = [];

        if (self::is_mailchimp_connected())
            $res[] = 'mailchimp';

        if (self::is_mailpoet_connected())
            $res[] = 'mailpoet';

        if (self::is_mailpoet3_connected())
            $res[] = 'mailpoet3';

        if (self::is_aweber_connected())
            $res[] = 'aweber';

        if (self::is_enormail_connected())
            $res[] = 'enormail';

        if (self::is_enormail_connected())
            $res[] = 'enormail';

        if (self::is_drip_connected())
            $res[] = 'drip';

        if (self::is_activecampaign_connected())
            $res[] = 'activecampaign';

        if (self::is_convertkit_connected())
            $res[] = 'convertkit';

        if (self::is_mailrelay_connected())
            $res[] = 'mailrelay';

        if (self::is_mailerlite_connected())
            $res[] = 'mailerlite';

        if (self::is_newsletterplugin_installed())
            $res[] = 'newsletter-plugin';

        if (self::is_getresponse_ready())
            $res[] = 'getresponse';

        return $res;
    }
}
