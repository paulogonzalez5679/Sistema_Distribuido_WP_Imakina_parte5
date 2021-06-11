<?php

class MailChimpSimpleClient extends SimpleWebClient\Client {
    protected $api_key = null;

    function __construct($key) {
        $this->api_key = $key;

        $dc = 'us1';

        if (strstr($key, "-")){
            list($key, $dc) = explode("-", $key, 2);
            if (!$dc) {
                $dc = 'us1';
            }
        }

        $endpoint = str_replace(
            'https://api',
            'https://'.$dc.'.api',
            'https://api.mailchimp.com/2.0'
        );

        parent::__construct(
            $endpoint,
            []
        );
    }

    public function get_lists($filters=array(), $start=0, $limit=25, $sort_field='created', $sort_dir='DESC') {
        $params = [
            'apikey' => $this->api_key,
            'filters' => $filters,
            "start" => $start,
            'limit' => $limit,
            'sort_field' => $sort_field,
            'sort_dir' => $sort_dir];

        $response = $this->send_request('/lists/list.json', 'POST', $params);

        if ($response->success != true) {
            return null;
        }

        $res = [];
        $lists = $response->data['data'];

        foreach ($lists as $item) {
            $res[] = (object) [
                'id' => $item['id'],
                'name' => $item['name']
            ];
        }

        return $res;
    }

    public function add_contact($list_id, $first_name, $last_name, $email) {
        $params = [
            'apikey' => $this->api_key,
            'id' => $list_id,
            'email' => ['email' => htmlentities($email)],
            'merge_vars' => [
                'FNAME' => htmlentities($first_name),
                'LNAME' => htmlentities($last_name)
            ],
            'email_type' => 'html',
            'double_optin' => false,
            'update_existing' => false,
            'replace_interests' => true,
            'send_welcome' => false
        ];

        $res = $this->send_request('/lists/subscribe.json', 'POST', $params);
        return $res->success;
    }
}
