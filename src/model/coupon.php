<?php

class SCI_Coupon
{
    public function get_list(): array
    {
        $condition = array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'post_mime_type' => 'text/csv',
            'post_content' => 'import_coupon',
            'posts_per_page' => 20,
        );

        $get_posts = new WP_Query;
        return $get_posts->query($condition);
    }


    /**
     *  [0] => Array
     * (
     *      [0] => Tên khuyến mại
     *      [1] => Ngày bắt đầu
     *      [2] => Ngày kết thúc
     *      [3] => Chiến dịch
     *      [4] => Ngành hàng
     *      [5] => Mã giảm giá
     *      [6] => Thông tin mã giảm giá
     *      [7] => Mô tả khuyến mại
     *      [8] => Banner link
     *      [9] => Mã Nhúng Banner
     *      [10] => Kích thước banner
     *      [11] => Link gốc
     *      [12] => Link phân phối
     * )
     *
     * [1] => Array
     * (
     * [0] => [GIAYHANAM]-Giảm 8K
     * [1] => 13-09-2021
     * [2] => 30-09-2021
     * [3] => TIKI
     * [4] => Khác
     * [5] => 11112
     * [6] =>
     * [7] => Giảm 8K
     * [8] =>
     * [9] =>
     * [10] =>
     * [11] => https://tiki.vn/cua-hang/giayhanam?ref=sansandeals
     * [12] => https://go.isclix.com/deep_link/4659213201682656309?url=https://tiki.vn/cua-hang/giayhanam?ref=sansandeals
     * )
     *
     * @param $items
     * @return int
     */
    public function save_coupon($items): int
    {
        foreach ($items as $key => $item) {
            if ($key == 0) {
                continue;
            }

            $data_item = [
                'title' => $item[7],
                'slug' => $item[0].'-'.$item[3].'-'.$item[5],
                'excerpt' => $item[6],
                'content' => $item[7],
                'date_expired' => $item[2],
                'date_started' => $item[1],
                '_wpc_coupon_type' => !empty($item[5]) ? 'code' : 'sale',
                '_wpc_coupon_type_code' => $item[5],
                '_wpc_destination_url' => $item[12],
                'coupon_store' => $item[3],
                'coupon_category' => $item[4],
            ];
            $this->save_item($data_item);
        }

        return 1;
    }

    /**
     * @param  array  $items
     */
    public function save_data_connect($items = [])
    {
        foreach ($items as $item) {
            $data_item = [
                'title' => $item['name'],
                'slug' => $item['id'],
                'excerpt' => $item['content'],
                'content' => $item['content'],
                'date_expired' => $item['end_time'],
                'date_started' => $item['start_time'],
                '_wpc_coupon_type' => !empty($item['coupons'][0]['coupon_code']) ? 'code' : 'sale',
                '_wpc_coupon_type_code' => !empty($item['coupons'][0]['coupon_code']) ? $item['coupons'][0]['coupon_code'] : '',
                '_wpc_destination_url' => $item['aff_link'],
                'coupon_store' => $item['domain'],
                'coupon_category' => !empty($item['categories'][0]['category_name_show']) ? $item['categories'][0]['category_name_show'] : 'Other',
            ];

            $this->save_item($data_item);
        }
    }

    /**
     * @param $item
     *  - title
     *  - slug
     *  - excerpt
     *  - content
     *  - date_expired
     *  - date_started
     *  - _wpc_coupon_type
     *  - _wpc_coupon_type_code
     *  - _wpc_destination_url
     *  - coupon_store
     *  - coupon_category
     *
     * @return int
     */
    public function save_item($item): int
    {
        $title = $item['title'];
        $slug = sanitize_title($item['slug']);
        $condition = array(
            'name' => $slug,
            'post_type' => 'coupon',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $post = get_posts($condition);

        // data update or insert
        $date_expired = 0;
        if (!empty($item['date_expired'])) {
            $date_expired = explode('-', $item['date_expired']);
            $date_expired = strtotime($date_expired[2].'-'.$date_expired[1].'-'.$date_expired[0]);
            if ($date_expired < time()) {
                return 0;
            }
        }

        $date_started = 0;
        if (!empty($item['date_started'])) {
            $date_started = explode('-', $item['date_expired']);
            $date_started = strtotime($date_started[2].'-'.$date_started[1].'-'.$date_started[0]);
        }

        if (empty($item['content'])) {
            $item['content'] = $title;
        }

        if (empty($item['excerpt'])) {
            $item['excerpt'] = $title;
        }

        $data_insert = array(
            'post_title' => wp_strip_all_tags($title),
            'post_name' => $slug,
            'post_content' => $item['content'],
            'post_excerpt' => $item['excerpt'],
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'coupon',
            'comment_status' => 'open',
            'meta_input' => [
                '_wpc_coupon_type' => $item['_wpc_coupon_type'],
                '_wpc_coupon_type_code' => $item['_wpc_coupon_type_code'],
                '_wpc_destination_url' => $item['_wpc_destination_url'],
                '_wpc_coupon_type_printable_id' => 0,
                '_wpc_expires' => $date_expired,
                '_wpc_start_on' => $date_started,
                '_wpc_vote_up' => 0,
                '_wpc_vote_down' => 0,
                '_wpc_used' => 0,
                '_wpc_percent_success' => 100,
            ]
        );

        if (!empty($post)) {
            $data_insert['ID'] = $post[0]->ID;
        }
        $id = wp_insert_post($data_insert);

        // save store
        $conditionStore = array(
            'hide_empty' => false, // also retrieve terms which are not used yet
            'meta_query' => array(
                array(
                    'key' => '_wpc_store_url',
                    'value' => $item['coupon_store'],
                    'compare' => 'LIKE'
                )
            ),
            'taxonomy' => 'coupon_store',
        );
        $termStore = get_terms($conditionStore);
        if (!empty($termStore[0]->slug)) {
            $coupon_store = $termStore[0]->slug;
            update_post_meta($id, '_wpc_store', $coupon_store);
            wp_set_object_terms($id, $coupon_store, 'coupon_store');
        }

        // category
        $conditionCategory = array(
            'hide_empty' => false, // also retrieve terms which are not used yet
            'meta_query' => array(
                array(
                    'key' => '_wpc_store_url',
                    'value' => $item['coupon_category'],
                    'compare' => 'LIKE'
                )
            ),
            'taxonomy' => 'coupon_category',
        );
        $termCategory = get_terms($conditionCategory);
        if (!empty($termCategory[0]->slug)) {
            $coupon_category = $termCategory[0]->slug;
            wp_set_object_terms($id, $coupon_category, 'coupon_category');
        }


        return 1;
    }
}