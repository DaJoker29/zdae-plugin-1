<?php 
/**
 * Plugin Name: zDae Plugin
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function replicator_handler() {
    $alias;
    if ( isset($_GET['alias'] ) && $_GET['alias']) {
        $alias = $_GET['alias'];
    }

    if( is_multisite() ) {
        include 'finish-form.php';
    } else {
        exit('Not a multisite build');
    }
}
add_shortcode( 'replicator', 'replicator_handler' );

function personal_info_handler() {
    include 'header-tags.php';
}
add_shortcode('zdae_header_tags', 'personal_info_handler');

function zdae_clone($domain,$path,$title,$user_id) {
	$_POST['action'] = 'process';
	$_POST['clone_mode'] = 'core';
	$_POST['source_id'] = 1;
	$_POST['target_name'] = $path;
	$_POST['target_title'] = $title;
	$_POST['disable_addons'] = true;
	$_POST['clone_nonce'] = wp_create_nonce('ns_cloner');
	
	$ns_site_cloner = new ns_cloner();
	$ns_site_cloner->process();
}

function replicate() {
  status_header(200);
  $alias = $_POST['alias'];
  $show_phone = $_POST['phone'];
  $show_email = $_POST['email'];
  $blog_details = get_blog_details( get_current_blog_id() );

  $domain = $blog_details->domain; 
  $path = $alias;
  $title = $alias;
  $user_id = 1; // FIXME: fetch super admin id and use that instead.

  zdae_clone($domain,$path,$title,$user_id);

  $newID = get_blog_details( $alias );

  $created_site = get_blog_details( $alias );

  switch_to_blog ( $created_site->blog_id);

  update_option('zdae_show_phone', $show_phone);
  update_option('zdae_show_email', $show_email);

  restore_current_blog();

  wp_redirect( get_site_url( get_current_blog_id(), $alias) );
  // Broadcast all pages to the new child
  switch_to_blog( 1 );
  $pages = get_pages();
  $api = ThreeWP_Broadcast()->api();

  foreach ( $pages as $page ) {
    $api->update_children( $page->ID, [ $newID ]);
  }
  restore_current_blog();

  exit();
}
add_action( 'admin_post_zdae_replicator_action', 'replicate' );

add_action('admin_menu', 'add_zdae_local_page');
function add_zdae_local_page() {
    $page_title = 'zDae Options';
    $menu_title = 'zDae Replication';
    $capability = 'manage_options';
    $menu_slug = 'zdae_options';
    $function = 'zdae_options_display';
    $icon_url = '';
    $position = 2;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}

function zdae_options_display() {
   	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

    if (isset($_POST['phone'])) {
        update_option('zdae_show_phone', $_POST['phone']);
    }

    if (isset($_POST['email'])) {
        update_option('zdae_show_email', $_POST['email']);
    }
 
    if (isset ($_POST['reset'])) {
        delete_option('zdae_show_phone');
        delete_option('zdae_show_email');
    }

	$phone = get_option( 'zdae_show_phone', "true");
	$email = get_option( 'zdae_show_email', "true");

    include 'local-options.php';
}

add_action('network_admin_menu', 'add_zdae_network_page');
function add_zdae_network_page() {
    $page_title = 'zDae Options';
    $menu_title = 'zDae Replication';
    $capability = 'manage_options';
    $menu_slug = 'zdae_network_options';
    $function = 'zdae_network_options_display';
    $icon_url = '';
    $position = 2;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}

function zdae_network_options_display() {
   	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

    if (isset($_POST['phone_label'])) {
        $phone_label = $_POST['phone_label'];
        update_site_option('zdae_network_phone_label', $phone_label);
    }

    if (isset($_POST['email_label'])) {
        $email_label = $_POST['email_label'];
        update_site_option('zdae_network_email_label', $email_label);
    }

    if (isset ($_POST['reset'])) {
        delete_site_option('zdae_network_phone_label');
        delete_site_option('zdae_network_email_label');
    }

    $phone_label = get_site_option( 'zdae_network_phone_label', 'Display your phone number?' );
    $email_label = get_site_option( 'zdae_network_email_label', 'Display your email address?' );

    include 'network-options.php';
}


add_action( 'elementor/editor/after_save', function( $post_id ) {
  ThreeWP_Broadcast()->api()->update_children( $post_id );
});



 ?>
