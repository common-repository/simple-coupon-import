<?php

/*
* Plugin Name: Simple Coupon Import
* Description: Import Coupon From CVS
* Version: 1.0.2
* Author: Nguyen Van Tinh
* Author URI: https://tweb.com.vn
* License: GPLv3
* Text Domain: simple-import-coupon
*/

if (!defined('ABSPATH')) {
    die('Direct access not allowed!');
}

if ( ! defined( 'SIMPLE_COUPON_IMPORT_PLUGIN_FILE' ) ) {
    define( 'SIMPLE_COUPON_IMPORT_PLUGIN_FILE', __FILE__ );
}

require dirname(__FILE__).'/vendor/autoload.php';

$page = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 'coupon-import';
$page_include = [
    'coupon-import' => 'imports.php',
    'coupon-import-api' => 'connects.php',
];

if (array_key_exists($page, $page_include)) {
    include_once dirname(__FILE__).'/src/controller/admin/'.$page_include[$page];
}

class SimpleCouponImport
{
    public function __construct()
    {
    }

}

function sci_load()
{
    global $wci;

    $wci = new SimpleCouponImport();
}

add_action('plugin_loaded', 'sci_load');


function sci_register_mysettings()
{
    register_setting('wci-settings-group', 'sci_option_name');
}

function sci_create_menu()
{
    add_menu_page(
        "Coupon Import",
        "Coupon Import",
        "manage_options",
        'coupon-import',
        "sci_settings_page",
        "dashicons-database-import"
    );

    add_submenu_page(
        "coupon-import",
        "Upload file csv",
        "From file csv",
        'manage_options',
        'coupon-import',
        'sci_settings_page'
    );

    add_submenu_page(
        "coupon-import",
        "Connect API",
        "From connect API",
        'manage_options',
        'coupon-import-api',
        'sci_settings_page_api'
    );

    add_action("admin_init", "sci_register_mysettings");
}

add_action("admin_menu", "sci_create_menu");

/**
 * page import file
 */
function sci_settings_page()
{
    $import = new SCI_Imports();
    $import->upload();
}

function sci_settings_page_api()
{
    $import = new SCI_Connects();
    $import->access_trade();
}