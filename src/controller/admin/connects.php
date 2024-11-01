<?php

include_once 'base.php';
include_once dirname(__FILE__, 3).'/model/access_trade.php';
include_once dirname(__FILE__, 3).'/model/coupon.php';

class SCI_Connects extends SCI_Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function access_trade()
    {
        // submit form
        if (isset($_POST['submit'])) {
            update_option('wic_link_api', sanitize_text_field($_POST['link_api']));
            update_option('wic_access_token', sanitize_text_field($_POST['access_token']));
            update_option('wic_limit', sanitize_text_field($_POST['limit']));
            update_option('wic_page', sanitize_text_field($_POST['page_number']));
            update_option('wic_domain', sanitize_text_field($_POST['domain']));

            $access_trade = new SCI_Access_Trade();
            $params = [
                'link_api' => sanitize_text_field($_POST['link_api']),
                'access_token' => sanitize_text_field($_POST['access_token']),
                'limit' => sanitize_text_field($_POST['limit']),
                'page_number' => sanitize_text_field($_POST['page_number']),
                'domain' => sanitize_text_field($_POST['domain']),
            ];
            $list_data = $access_trade->offers_information($params);

            // save data
            $coupon = new SCI_Coupon();
            $coupon->save_data_connect($list_data);
        }

        // get data
        $form_data = [
            'link_api' => get_option('wic_link_api'),
            'access_token' => get_option('wic_access_token'),
            'limit' => get_option('wic_limit'),
            'domain' => get_option('wic_domain'),
            'page_number' => get_option('wic_page_number'),
        ];

	    $listDomain = [
		    'tiki.vn',
		    'shopee.vn',
		    'lazada.vn',
		    'sendo.vn',
		    'nguyenkim.com',
	    ];

        include_once $this->dir.'/view/admin/connect/form.php';
    }
}