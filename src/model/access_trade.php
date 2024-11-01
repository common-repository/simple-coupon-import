<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;


class SCI_Access_Trade
{
    protected $link_api;
    protected $client;
    protected $headers;

    public function __construct()
    {
        $this->headers = [
            'headers' => [
                'Authorization' => 'Token  '.get_option('wic_access_token', '')
            ]
        ];

        $this->client = new Client([
            'base_uri' => get_option('wic_link_api', ''),
            'timeout' => 2.0,
        ]);
    }

    /**
     * Docs: https://developers.accesstrade.vn/api-publisher-vietnamese/lay-thong-tin-cac-khuyen-mai-dang-hoat-dong
     *
     *  [aff_link] => https://go.isclix.com/deep_link/4659213201682656309?url=https%3A%2F%2Ftiki.vn%2Fkhuyen-mai%2Ftiki-sale-tet-2021%3Fref%3Dsansandeals
     * [banners] => Array()
     *
     * [categories] => Array
     * (
     *      [0] => Array
     *          (
     *              [category_name] => EC-29
     *              [category_name_show] => Other
     *              [category_no] =>
     *          )
     *  )
     *
     * [content] =>
     * [coupons] => Array
     *      (
     *          [0] => Array
     *              (
     *                  [coupon_code] => NEWTIKI
     *                  [coupon_desc] => GIẢM 30%
     *                  [coupon_save] =>
     *              )
     *      )
     *
     * [domain] => tiki.vn
     * [end_time] => 2021-12-31
     * [id] => 5640923717369646104
     * [image] => https://content.accesstrade.vn/adv/1630929502_avatar_1630929502.gif
     * [link] => https://tiki.vn/khuyen-mai/tiki-sale-tet-2021?ref=sansandeals
     * [merchant] => tikivn
     * [name] => GIẢM 30%
     * [start_time] => 2021-09-14
     *
     *
     * @param  array  $params
     * @return array|mixed
     */
    public function offers_information($params = []): array
    {
        try {
//            $queryString['categories'] = 'voucher-dich-vu';
//            $queryString['merchant'] = 'tiki';
//            $queryString['scope'] = 'expiring';
//            $queryString['coupon'] = 1;
            $queryString['status'] = 1;
            $queryString['page'] = !empty($params['page_number']) ? $params['page_number'] : 1;
            $queryString['limit'] = !empty($params['limit']) ? $params['limit'] : 10;
            $queryString['domain'] = !empty($params['domain']) ? $params['domain'] : 10;

            $url = $this->link_api.'/v1/offers_informations?'.http_build_query($queryString);
            $respone = $this->client->get($url, $this->headers);

            $respone = json_decode($respone->getBody(), true);

            return !empty($respone['data']) ? $respone['data'] : [];

        } catch (GuzzleException $exception) {
            return [];
        }
    }
}