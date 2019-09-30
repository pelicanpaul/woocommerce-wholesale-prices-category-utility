<?php
/*
Plugin Name: Woocommerce Wholesale Prices Category Utility
Description: Allows for the ability to automate wholesale pricing at the category level.
Version: 1.0
Author: Paul Lyons
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once 'admin/utilities/index.php';

// Include the dependencies needed to instantiate the plugin.
foreach ( glob( plugin_dir_path( __FILE__ ) . 'admin/*.php' ) as $file ) {
	include_once $file;
}

function wholesale_custom_admin_settings() {
	$plugin = new Submenu( new Submenu_Page() );
	$plugin->init();
}

add_action( 'plugins_loaded', 'wholesale_custom_admin_settings' );


function gc_admin_scripts() {
	wp_register_script('gc-script-admin', plugins_url() . '/woocommerce-wholesale-prices-category-utility/js/wholesale-admin.js', array('jquery'), null, true );
	wp_enqueue_script('gc-script-admin');

	// ajax
	wp_enqueue_script('ajaxloadpost', plugins_url().'/woocommerce-wholesale-prices-category-utility/js/wholesale-admin-ajax.js', array('jquery'));
	wp_localize_script( 'ajaxloadpost', 'ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action('admin_enqueue_scripts', 'gc_admin_scripts');

function gc_admin_styles() {
	wp_enqueue_style( 'gc-styles-framework', plugins_url() .'/woocommerce-wholesale-prices-category-utility/css/bootstrap-form.css',false,'1.1','all');
}

add_action('admin_enqueue_scripts', 'gc_admin_styles');


// ajax code ///////////////////////////////////////////////

add_action('wp_ajax_nopriv_ajax_ajaxhandler', 'get_catids_callback' );
add_action('wp_ajax_ajax_ajaxhandler', 'get_catids_callback' );

function get_catids_callback(){

	$catid = $_POST["id"];
	$catids =  trim(ws_woo_cats_ids_string($catid));

	echo $catids;
}












