<?php 
/**
 * Plugin Name: zDae Plugin
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function siteExists($alias) {
    $args = array(
        'path' => '/'.$alias.'/',
        'count' => true
    );
    $result = get_sites($args);
    if ( $result > 0 ) {
        return true;
    } else {
        return false;
    }
}

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

function zdae_clone($domain,$path,$title,$user_id) {
	
	$_POST['action'] = 'process';
	$_POST['clone_mode'] = 'core';
	$_POST['source_id'] = 1;
  // $_POST['source_id'] = get_blog_details('template')->blog_id;
	$_POST['target_name'] = $path;
	$_POST['target_title'] = $title;
	$_POST['disable_addons'] = true;
	$_POST['clone_nonce'] = wp_create_nonce('ns_cloner');
	
	$ns_site_cloner = new ns_cloner();
	$ns_site_cloner->process();

	$site_id = $ns_site_cloner->target_id;
	$site_info = get_blog_details( $site_id );
	if ( $site_info ) {
		// Clone successful!
	}
}

function replicate() {
  status_header(200);
  $alias = $_POST['alias'];
  $show_phone = isset($_POST['show_phone']);
  $show_email = isset($_POST['show_email']);
  $blog_details = get_blog_details( get_current_blog_id() );

  $domain = $blog_details->domain; 
  $path = $alias;
  $title = $alias;
  $user_id = 1; // FIXME: fetch super admin id and use that instead.


  // wpmu_create_blog( $domain, $path, $title, $user_id );
  zdae_clone($domain,$path,$title,$user_id);

  // TODO: Check if blog creation was successful. If so, redirect there. Otherwise, handle error.

  $newID = get_blog_details( $alias );

  $created_site = get_blog_details( $alias );
  add_blog_option($created_site->blog_id, 'zdae_show_phone', $show_phone);
  add_blog_option($created_site->blog_id, 'zdae_show_email', $show_email);


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

add_action('admin_menu', 'add_menu_zdae');
function add_menu_zdae() {
    add_menu_page('zDae Options', 'zDae Plugin', 'manage_options', 'zdae-plugin', 'zdae_plugin_options');
}

function zdae_plugin_options() {
   	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$phone = get_blog_option( get_current_blog_id(), 'zdae_show_phone', true);
	$email = get_blog_option( get_current_blog_id(), 'zdae_show_email', true);

	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';

	if ( $phone == true ) {
		echo '<p>Phone Visible</p>';
	}

	if ( $email == true ) {
		echo '<p>Email Visible</p>';
	}
	echo '</div>'; 
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
