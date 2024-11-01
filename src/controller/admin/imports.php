<?php

include_once 'base.php';
include_once dirname(__FILE__, 3).'/model/coupon.php';
include_once dirname(__FILE__, 3).'/model/media.php';
require_once(ABSPATH.'wp-admin/includes/file.php');

class SCI_Imports extends SCI_Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function upload()
    {
        $error = null;
        $success = null;

        $coupon = new SCI_Coupon();
        $media = new SCI_Media();

        if (isset($_POST['submit']) && is_uploaded_file($_FILES['url_csv']['tmp_name'])) {
            $uploaded_file = $_FILES['url_csv'];
            $wp_filetype = wp_check_filetype_and_ext($uploaded_file['tmp_name'], $uploaded_file['name']);
            if (!wp_match_mime_types('csv', $wp_filetype['type'])) {
                wp_die(__('The uploaded file is not a valid image. Please try again.'));
            }

            $overrides = array('test_form' => false);
            $file = wp_handle_upload($uploaded_file, $overrides);

            if (isset($file['error'])) {
                wp_die($file['error']);
            }

            $upload_id = wp_insert_attachment(
                array(
                    'guid' => $file['url'],
                    'post_mime_type' => $file['type'],
                    'post_title' => wp_basename($file['file']),
                    'post_content' => 'import_coupon',
                    'post_status' => 'inherit'
                ),
                $file['file']
            );

            if (!empty($upload_id)) {
                $data = $media->read_file($file['file']);
                $result = $coupon->save_coupon($data);
                if ($result) {
                    $success = __('Success save coupon');
                }
            }
        }

        // load list file upload
        $list_import = $coupon->get_list();

        compact('list_import', 'error', 'success');
        include_once $this->dir.'/view/admin/import/form.php';
    }
}