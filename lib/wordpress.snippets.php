<?php

add_action( 'admin_init', 'ibex_image_default_link_type_checker' );
function ibex_image_default_link_type_checker()
{
    if ( get_option( 'image_default_link_type', '' ) != 'blank' ) {
        update_option( 'image_default_link_type', 'blank' );
    }
}

// source: http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file

// remove unncessary header info
function remove_header_info() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'adjacent_posts_rel_link');
}
add_action('init', 'remove_header_info');

// Disable Upgrade Now Message for Non-Administrators
if ( !current_user_can( 'manage_options' ) ) {
  add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
  add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
}

//Disable browser upgrade warning in wordpress 3.2
function disable_browser_upgrade_warning() {
    remove_meta_box( 'dashboard_browser_nag', 'dashboard', 'normal' );
}
add_action( 'wp_dashboard_setup', 'disable_browser_upgrade_warning' );

/**resize on upload to the largest size in media setting */

function replace_uploaded_image($image_data) {
	// if there is no large image : return
	if (!isset($image_data['sizes']['large'])) return $image_data;
	
	// path to the uploaded image and the large image
	$upload_dir = wp_upload_dir();
	$uploaded_image_location = $upload_dir['basedir'] . '/' .$image_data['file'];
	$large_image_location = $upload_dir['path'] . '/'.$image_data['sizes']['large']['file'];
	
	// delete the uploaded image
	unlink($uploaded_image_location);
	
	// rename the large image
	rename($large_image_location,$uploaded_image_location);
	
	// update image metadata and return them
	$image_data['width'] = $image_data['sizes']['large']['width'];
	$image_data['height'] = $image_data['sizes']['large']['height'];
	unset($image_data['sizes']['large']);
	
	return $image_data;
}
// add_filter('wp_generate_attachment_metadata','replace_uploaded_image');

// unregister all default WP Widgets
function unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    //unregister_widget('WP_Widget_Search');
    //unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Tag_Cloud');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);

/**
 * Set the post revisions unless the constant was set in wp-config.php
 */
if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 5);

// add excerpt field for pages 
if ( function_exists('add_post_type_support') ) 
{
    add_action('init', 'add_page_excerpts');
    function add_page_excerpts() 
    {        
        add_post_type_support( 'page', 'excerpt' );
    }
}
// Prevents WordPress from testing ssl capability on domain.com/xmlrpc.php?rsd
remove_filter('atom_service_url','atom_service_url_filter');

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets',1000);

function my_custom_dashboard_widgets() {
   global $wp_meta_boxes;
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
   unset($wp_meta_boxes['dashboard']['normal']['core']['w3tc_pagespeed']); // w3tc
   unset($wp_meta_boxes['dashboard']['normal']['core']['w3tc_latest']); // w3tc
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_browser_nag']); //yoast
   unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']); //yoast
   foreach ($wp_meta_boxes['dashboard']['normal'] as $index) {
   		//var_dump($index);
   }
   foreach ($wp_meta_boxes['dashboard']['side'] as $index) {
   }
   wp_add_dashboard_widget('ibex_help_widget', 'Pomoc i informacje ', 'ibex_help_widget');
}   
function ibex_help_widget() {
    echo '<p><strong>Wsparcie dla klientów ibex.pl:</strong></p><p>gsm: 607 594 208, info@ibex.pl</p>';
}

?>