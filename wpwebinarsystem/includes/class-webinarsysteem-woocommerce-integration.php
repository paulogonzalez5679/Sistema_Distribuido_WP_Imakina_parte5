<?php

/**
 * Description of WebinarSysteemWooCommerceIntegration
 * Integrate WooCommerce for paid webinars. Tickets aka Products will be sold.
 *
 * @package  WebinarSysteem/WooCommerceIntegration
 * @author Thambaru Wijesekara <howdy@thambaru.com>
 */

define('ASSFURL', WP_PLUGIN_URL . "/" . dirname(plugin_basename(__FILE__)));

class WebinarSysteemWooCommerceIntegration
{
    /**
     * Checks if WooCommerce plugin exists
     *
     * @return boolean
     */
    public static function is_woo_commerce_ready()
    {
        return class_exists('WooCommerce');
    }

    /**
     * Checks if user enabled the integration
     *
     * @return boolean
     */
    public static function is_enabled()
    {
        return get_option('_wswebinar_enable_woocommerce_integration') == 'on';
    }

    /**
     * Checks whether WooCommerce plugin exists and the user enabled the integration
     *
     * @return boolean
     */
    public static function is_ready()
    {
        return self::is_woo_commerce_ready() && self::is_enabled();
    }

    /**
     * Shows an admin notice if WooCommerce is not ready but the integration
     *
     * @return void
     */
    public static function check_woo_commerce_is_ready()
    {
        if (self::is_enabled() && !self::is_woo_commerce_ready()) {
            ?>
            <div class="error">
                <p><?php printf(__('Please install/activate WooCommerce first to integrate with WebinarSystem. You can find WooCommerce <a href="%s" class="thickbox" aria-label="Download WooCommerce for WebinarSystem" data-title="WooCommerce">here</a>.', WebinarSysteem::$lang_slug), admin_url("plugin-install.php?tab=plugin-information&plugin=woocommerce&TB_iframe=true")); ?></p>
            </div>
            <?php
        }
    }

    /**
     * Adds custom fields to the product page for Recurring Webinar type
     *
     * @return
     */
    public static function add_webinar_recurring_fields()
    {
        global $post;
        $webinar_id = get_post_meta($post->ID, '_wpws_webinarID', true);
        $timeabbr = get_post_meta($webinar_id, '_wswebinar_timezoneidentifier', true);
        $wpoffset = get_option('gmt_offset');
        $gmt_offset = WebinarSysteemDateTime::format_timezone(($wpoffset > 0) ? '+' . $wpoffset : $wpoffset);
        $timeZone = '(' . ((!empty($timeabbr)) ? WebinarSysteemDateTime::get_timezone_abbreviation($timeabbr) : 'UTC ' . $gmt_offset) . ') ';

        $product = wc_get_product($post->ID);
        $gener_time_occur_saved = get_post_meta($webinar_id, '_wswebinar_gener_time_occur', true);

        if ($gener_time_occur_saved == 'jit' || $gener_time_occur_saved == 'recur') {
            echo '<h3>Select Webinar Date and Time</h3>';
            if ($gener_time_occur_saved == 'recur' || $gener_time_occur_saved == 'jit') {
                include 'templates/template-webinar-sessions-selects-boxes.php';
            }
            ?>
            <style>
                select[name="session_datetime"] {
                    margin: 10px 0px;
                }
            </style>

            <?php
        }
    }

    /**
     * Validate Custom Webinar product fields
     *
     * $passed_validation set to TRUE by default
     * @param bool $passed_validation
     * @param int $product_id
     *
     * @return bool
     */
    public static function webinar_fields_validation($passed_validation, $product_id)
    {
        $webinar_id = get_post_meta($product_id, '_wpws_webinarID', true);
        $webinar = WebinarSysteemWebinar::create_from_id($webinar_id);

        if ($webinar == null) {
            return $passed_validation;
        }

        $already_in_cart = self::is_product_in_cart($product_id);

        if ($already_in_cart) {
            $product = wc_get_product($product_id);
            $error_message = sprintf(
                '<a href="%s" class="button wc-forward">%s</a> %s',
                wc_get_cart_url(), __('View Cart', 'woocommerce'),
                sprintf(__('You cannot add another &quot;%s&quot; to your cart.', 'woocommerce'), $product->get_title())
            );
            wc_add_notice($error_message, 'error');
            return false;
        }

        if ($webinar->is_recurring() && !$webinar->is_right_now()) {
            if (empty($_REQUEST['session_datetime'])) {
                wc_add_notice(__('Please select a session.', 'woocommerce'), 'error');
                return false;
            }
        }

        return $passed_validation;
    }

    public static function get_first_webinar_id_in_cart() {
        if (!self::is_woo_commerce_ready())
            return false;

        $cart_items = WC()->cart->get_cart();

        foreach ($cart_items as $cart_item) {
            $product_id = $cart_item['product_id'];
            $webinar_id = get_post_meta($product_id, '_wpws_webinarID', true);
            if ($webinar_id) {
                return $webinar_id;
            }
        }

        return null;
    }

    /**
     * Add GDPR Opt-in field on checkout page
     *
     * @return
     */
    public static function customise_checkout_field()
    {
        $cart_items = WC()->cart->get_cart();
        $gdpr_optin = false;
        $gdpr_text = '';

        // TODO, refactor this to use get_first_webinar_id_in_cart
        foreach ($cart_items as $cart_item) {
            $product_id = $cart_item['product_id'];
            $wbnId = get_post_meta($product_id, '_wpws_webinarID', true);
            if ($wbnId) {
                $regp_gdpr_optin_yn_value = get_post_meta($wbnId, '_wswebinar_regp_wc_gdpr_optin_yn', true);
                $showGDPROptin = ($regp_gdpr_optin_yn_value == "yes") ? true : false;
                if ($showGDPROptin) {
                    $gdpr_optin = TRUE;
                    $gdpr_text = get_post_meta($wbnId, '_wswebinar_regp_gdpr_optin_text', true);
                }
                break;
            }
        }
        if ($gdpr_optin) {
            echo '<div id="customise_checkout_field">';
            woocommerce_form_field('wpws_gdpr_optin', array(
                'type' => 'checkbox',
                'class' => array(
                    'input-checkbox form-row-wide'
                ),
                'label' => $gdpr_text,
                'required' => true,
            ));
            echo '</div>';
        }
    }

    /**
     * Validate GDPR Opt-in field on checkout page
     *
     * @return
     */
    public static function customise_checkout_field_process()
    {
        $cart_items = WC()->cart->get_cart();
        $gdpr_optin = false;

        // TODO, refactor this to use get_first_webinar_id_in_cart
        foreach ($cart_items as $cart_item) {
            $product_id = $cart_item['product_id'];
            $wbnId = get_post_meta($product_id, '_wpws_webinarID', true);
            if ($wbnId) {
                $regp_gdpr_optin_yn_value = get_post_meta($wbnId, '_wswebinar_regp_wc_gdpr_optin_yn', true);
                $showGDPROptin = ($regp_gdpr_optin_yn_value == "yes") ? true : false;
                if ($showGDPROptin) {
                    $gdpr_optin = TRUE;
                }
                break;
            }
        }
        if ($gdpr_optin) {
            if (!$_POST['wpws_gdpr_optin']) {
                wc_add_notice(__('Please check the box to proceed further.'), 'error');
            }
        }
    }


    public static function is_product_in_cart($product_id)
    {
        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
            $_product = $values['data'];

            if ($product_id == $_product->get_id()) {
                return true;
            }
        }
        return FALSE;
    }

    public static function save_webinar_fields($cart_item_data, $product_id)
    {
        // make sure this is a webinar
        if (self::get_webinar_id_from_product_id($product_id) == null) {
            return $cart_item_data;
        }

        if (isset($_REQUEST['session_datetime'])) {
            $cart_item_data['session_datetime'] = $_REQUEST['session_datetime'];
        }

        // make sure every add to cart action as unique line item
        $cart_item_data['unique_key'] = md5(microtime() . rand());

        return $cart_item_data;
    }

    public static function custom_order_meta_handler($itemId, $item, $orderId)
    {
        $product_id = $item['product_id'];
        $wbnId = get_post_meta($product_id, '_wpws_webinarID', true);

        $webinar = WebinarSysteemWebinar::create_from_id($wbnId);

        if ($webinar == null) {
            return;
        }

        try {
            if ($webinar->is_recurring()) {
                // from old method of storing the day and the time, since replaced with session_datetime
                wc_add_order_item_meta($itemId, "inputday", $item->legacy_values['inputday']);

                if (!$webinar->is_right_now()) {
                    wc_add_order_item_meta($itemId, "inputtime", $item->legacy_values['inputtime']);
                }

                wc_add_order_item_meta($itemId, "session_datetime", $item->legacy_values['session_datetime']);
            }
        } catch (Exception $exc) {
        }
    }

    /**
     * Determines whether WC product should be created or updated and
     * call corresponding functions.
     *
     * @param int $webinar_id
     * @return void
     */
    public static function create_or_update_ticket($webinar_id)
    {
        $webinar = WebinarSysteemWebinar::create_from_id($webinar_id);

        if (!self::is_woo_commerce_ready() || $webinar == null) {
            return;
        }

        $product_id = $webinar->get_woo_product_id();
        $existing_status = get_post_status($product_id);

        if (empty($product_id) || !$existing_status || $existing_status === 'trash') {
            $product_id = self::create_woo_product(
                $webinar_id,
                [
                    'price' => esc_attr($webinar->get_price()),
                    'title' => esc_attr($webinar->get_name()),
                    'description' => $webinar->get_description(),
                ]
            );
            $webinar->set_woo_product_id($product_id);
        }

        self::update_woo_product(
            $webinar_id,
            $product_id,
            ['price' => esc_attr($webinar->get_price())]
        );
    }

    /**
     * Creates a WC product.
     *
     * @param int $postId
     * @param array $productDetails
     * @return int $id
     */
    static function create_woo_product($postId, $productDetails)
    {
        $id = wp_insert_post([
            'post_type' => 'product',
            'post_title' => $productDetails['title'],
            'post_content' => $productDetails['description'],
            'post_status' => get_post_status($postId),
            'max_value' => 1,
        ]);

        update_post_meta($id, '_price', $productDetails['price']);
        update_post_meta($id, '_regular_price', $productDetails['price']);
        update_post_meta($id, '_wpws_webinarID', $postId);
        update_post_meta($id, '_sold_individually', 'yes');
        update_post_meta($id, '_virtual', 'yes');
        update_post_meta($id, '_downloadable', 'yes');

        wp_set_object_terms($id, 'webinar', 'product_type');

        return $id;
    }

    /**
     * Updates WC product
     *
     * @param int $webinar_id
     * @param int $product_id
     * @param array $product
     */
    static function update_woo_product($webinar_id, $product_id, $product)
    {
        wp_update_post([
            'ID' => $product_id,
            'post_status' => get_post_status($webinar_id)
        ]);

        update_post_meta($product_id, '_price', $product['price']);
        update_post_meta($product_id, '_regular_price', $product['price']);
        update_post_meta($product_id, '_wpws_webinarID', $webinar_id);
        update_post_meta($product_id, '_sold_individually', 'yes');

        wp_set_object_terms($product_id, 'webinar', 'product_type');
    }

    public static function add_simple_webinar_product($types)
    {
        $types['webinar'] = __('Webinar Product');
        return $types;
    }

    /**
     * Sends an email with a link to register for the webinar
     *
     * @param int $order_id
     * @return void
     */
    static function register_attendee_in_webinar($order_id)
    {
        $webinars = self::get_webinars_from_order($order_id);

        foreach ($webinars as $webinar) {

            // todo, check if we are already registered before registering again and sending emails
            $attendee = WebinarSysteem::register_webinar_attendee(
                $webinar->id,
                $webinar->name,
                $webinar->email,
                $webinar->exact_time,
                $webinar->selected_day,
                $webinar->selected_time
            );

            if ($attendee == null) {
                WebinarSysteemLog::log("register_webinar_attendee returned null attendee after completed WooCommerce order");
                continue;
            }

            $mail = new WebinarSysteemEmails();
            $mail->send_mail_to_reader_on_wc_order_complete($attendee);
        }
    }

    static function on_woo_order_status_completed($order_id)
    {
        self::register_attendee_in_webinar($order_id);
    }

    static function on_woo_commerce_order_status_changed($order_id, $old_status, $new_status)
    {
        if ($new_status == 'completed') {
            self::register_attendee_in_webinar($order_id);
        }
    }

    /**
     * Adds webinar id to WC product.
     *
     * @return void
     */
    static function add_webinar_id_field()
    {
        echo "<script>jQuery('.show_if_simple').addClass('show_if_webinar');</script>";
        woocommerce_wp_hidden_input(
            array(
                'id' => '_wpws_webinarID',
                'value' => ''
            )
        );
    }


    public static function get_unique_params_for_email()
    {
        return '?utm_source=' . rand(846554, 999999999) . '&cont=' . md5(rand()) . '&opt=' . md5(rand()) . '&mapdoi=' . md5(rand(100, 200)) . '&key=' . md5(rand(100, 300));
    }

    public static function get_unique_purchase_url()
    {
        return '?utm_source=' . md5(rand()) . '&unt=' . md5(rand()) . '&opt=' . rand(95127, 999999999) . '&mapdoi=' . md5(rand(100, 200)) . '&key=' . md5(rand(100, 300)) . '&auth=' . md5(rand());
    }

    /**
     * Hide custom fields in order confirmation mail.
     *
     * @param integer $order_id
     * @param bool $is_admin_email
     */
    public static function hide_custom_fields_in_order_email($order, $is_admin_email)
    {
        $order = wc_get_order($order);
        foreach ($order->get_items() as $item_id => $item) {
            $product_id = $item['product_id'];
            $wbnId = get_post_meta($product_id, '_wpws_webinarID', true);
            if (!empty($wbnId)) {
                ?>
                <style>
                    .wc-item-meta {
                        display: none;
                    }
                </style> <?php }
        }
    }

    private static function get_webinar_id_from_product_id($product_id) {
        $webinar_id = get_post_meta($product_id, '_wpws_webinarID', true);

        if (empty($webinar_id))
            return null;

        return $webinar_id;
    }

    private static function get_webinars_from_order($order_id)
    {
        $webinars = [];

        $order = wc_get_order($order_id);
        foreach ($order->get_items() as $item_id => $item) {
            $webinar_id = self::get_webinar_id_from_product_id($item['product_id']);

            if ($webinar_id == null)
                continue;

            try {
                $selected_day = wc_get_order_item_meta($item_id, "inputday", true);
                $selected_time = WC_get_order_item_meta($item_id, "inputtime", true);
                $exact_time = WC_get_order_item_meta($item_id, "session_datetime", true);
            } catch (Exception $exc) {
            }

            if (empty($selected_day)) {
                $selected_day = NULL;
            }

            if (empty($selected_time)) {
                $selected_time = NULL;
            }

            $webinar = WebinarSysteemWebinar::create_from_id($webinar_id);

            if ($webinar == null) {
                continue;
            }

            $webinar_date_time = $webinar->get_session_date_and_time($exact_time, $selected_day, $selected_time);

            $webinars[] = (object) array(
                'id' => $webinar_id,
                'name' => $order->get_billing_first_name(),
                'email' => $order->get_billing_email(),
                'title' => get_the_title($webinar_id),
                'link' => get_permalink($webinar_id, false),
                'selected_day' => $selected_day,
                'selected_time' => $selected_time,
                'exact_time' => $exact_time,
                'timezone' => $webinar->get_timezone(),
                'webinar_date' => $webinar_date_time->date,
                'webinar_time' => $webinar_date_time->time
            );
        }

        return $webinars;
    }

    /**
     * Show webinar ticket details on WC ThankYou/OrderConfirmation page.
     *
     *
     * @param integer $order_id
     */
    static function show_thank_you_message($order_id)
    {
        ?>
        <style>
            .wpwebinarsystem-join-webinar-wc-notice {
                margin: 15px 0px;
                border: 1px solid;
                overflow: auto;
                padding: 10px;
            }

            .wc-item-meta {
                display: none;
            }
        </style>
        <?php
        $webinars = self::get_webinars_from_order($order_id);

        foreach ($webinars as $webinar) {

            $thank_you_message = WebinarSysteemHelperFunctions::get_post_meta_content_with_default($webinar->id, '_wswebinar_ticket_thank_you_message',
                __('You will receive your webinar ticket as soon as the payment for your order completes.', WebinarSysteem::$lang_slug));

            $replacements = array(
                'webinar-title' => $webinar->title,
                'webinar-link' => $webinar->link,
                'webinar-date' => $webinar->webinar_date,
                'webinar-time' => $webinar->webinar_time,
                'webinar-timezone' => $webinar->timezone,
            );

            $thank_you_message = WebinarSysteemHelperFunctions::replace_tags($thank_you_message, $replacements);

            ?>
            <div class="wpwebinarsystem-join-webinar-wc-notice">
                <?php echo $thank_you_message ?>
            </div>
            <?php
        }
    }

    static function show_user_webinars()
    {
        $customer_orders = get_posts(array(
            'meta_key' => '_customer_user',
            'meta_value' => get_current_user_id(),
            'post_type' => wc_get_order_types(),
            'post_status' => array_keys(wc_get_order_statuses()),
        ));

        $webinar_ids = [];

        ?>

        <h2><?php _e('My Webinar Tickets', WebinarSysteem::$lang_slug) ?></h2>
        <table class="shop_table shop_table_responsive my_account_orders">

            <thead>
            <tr>
                <th class="order-number">
                    <span class="nobr"><?php _e('Order', WebinarSysteem::$lang_slug) ?></span>
                </th>
                <th class="webinar-name">
                    <span class="nobr"><?php _e('Webinar', WebinarSysteem::$lang_slug) ?></span>
                </th>
                <th class="webinar-date">
                    <span class="nobr"><?php _e('Session', WebinarSysteem::$lang_slug) ?></span>
                </th>
                <th class="join-webinar">
                    <span class="nobr"><?php _e('Join', WebinarSysteem::$lang_slug) ?></span>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($customer_orders as $order) { //Iterate over orders
                $order_id = $order->ID;
                $order = wc_get_order($order_id);

                if ($order->get_status() != "completed")
                    continue;

                $webinars = self::get_webinars_from_order($order_id);

                foreach ($webinars as $info) {
                    $attendee = WebinarSysteemAttendees::get_attendee_by_email($info->email, $info->id);

                    // only show a single instance of the webinar (not other sessions)
                    if (isset($webinar_ids[$info->id])) {
                        continue;
                    }
                    $webinar_ids[$info->id] = true;

                    $webinar = WebinarSysteemWebinar::create_from_id($info->id);

                    $url = $attendee != null
                        ? $webinar->get_url_with_auth($info->email, $attendee->secretkey)
                        : $webinar->get_url();

                    ?>
                    <tr class="order">
                        <td class="order-number" data-title="<?php _e('Order', WebinarSysteem::$lang_slug) ?>">
                            <a href="<?php echo esc_url($order->get_view_order_url()); ?>">
                                #<?php echo $order->get_order_number() ?>
                            </a>
                        </td>
                        <td class="webinar-name" data-title="<?php _e('Webinar', WebinarSysteem::$lang_slug) ?>">
                            <?php echo $webinar->get_name(); ?>
                        </td>
                        <td class="webinar-time" data-title="<?php _e('Time', WebinarSysteem::$lang_slug) ?>">
                            <?php echo $info->webinar_date ?>
                            &nbsp;
                            <?php echo $info->webinar_time ?>
                        </td>
                        <td class="join-webinar" data-title="<?php _e('Join', WebinarSysteem::$lang_slug) ?>">
                            <a href="<?= $url ?>"
                               class="button view">
                                <?php _e('Join webinar', WebinarSysteem::$lang_slug) ?>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } ?>
            </tbody>
        </table>
        <style>
            @media only screen and (max-width: 320px) {
                .shop_table a {
                    font-size: 8px
                }

                .shop_table > thead > tr > th {
                    font-size: 9px !important;
                }
            }
        </style>
        <?php
    }

    static function add_to_cart_redirect($wc_get_cart_url) {
        if (self::get_first_webinar_id_in_cart() == null) {
            return $wc_get_cart_url;
        }

        $settings = WebinarSysteemSettings::instance();

        switch ($settings->get_woocommerce_add_to_cart_redirect_page()) {
            case 'cart':
                return wc_get_cart_url();

            case 'checkout':
            default:
                return wc_get_checkout_url();
        }
    }

    static function format_session_time($time) {
        $time_format = get_option('time_format');
        $date_format = get_option('date_format');

        return date($date_format.' '.$time_format, $time);
    }

    static function order_item_get_formatted_meta_data($meta) {
        try {
            foreach($meta as $value) {
                if ($value->key != 'session_datetime') {
                    continue;
                }

                // format the date
                $value->display_key =  __('Session', WebinarSysteem::$lang_slug);
                $value->display_value = self::format_session_time($value->value);
            }
        } catch ( Exception $e ) {
        }

        return $meta;
    }

    static function order_item_meta_end($item_id, $item, $order = null, $plain_text = false) {
        try {
            $webinar_id = self::get_webinar_id_from_product_id($item['product_id']);

            if ($webinar_id == null)
                return;

            $exact_time = WC_get_order_item_meta($item_id, "session_datetime", true);

            if ($exact_time) {
                ?>
                <div class="product-description">
                    <p>
                        <?= self::format_session_time($exact_time) ?>
                    </p>
                </div>
                <?php
            }
        } catch (Exception $e) {
        }
    }

    public static function get_item_data($cart_item_data, $cart_item) {
        if (isset($cart_item['session_datetime'])) {
            $cart_item_data[] = [
                "name" => __('Session', WebinarSysteem::$lang_slug),
                "value" => self::format_session_time($cart_item['session_datetime'])
            ];
        }
        return $cart_item_data;
    }
}

?>
