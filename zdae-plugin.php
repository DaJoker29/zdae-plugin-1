<?php 
/**
 * Plugin Name: zDae Plugin
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function replicator_handler() {
    if ( isset($_GET['alias'] ) ) {
      $alias = $_GET['alias'];

      echo '<div>';
      echo 'Your current alias is <strong>'.$alias.'</strong>';
      echo '</div>';

      echo '<form action="'.admin_url( 'admin-post.php').'" method="post">';
      echo '<input type="hidden" name="action" value="zdae_replicator_action" />';
      echo '<input type="hidden" name="alias" value="'.$alias.'" />';
      echo '<input type="submit" value="Create Website" />';
      echo '</form>';
    } else {
        echo '<div>';
        echo 'No user provided to create a website.';
        echo '</div>';
    }
}
add_shortcode( 'replicator', 'replicator_handler' );

function zdae_clone($domain,$path,$title,$user_id) {
	
	$_POST['action'] = 'process';
	$_POST['clone_mode'] = 'core';
	$_POST['source_id'] = get_blog_details('template')->blog_id;
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
  $blog_details = get_blog_details( get_current_blog_id() );

  $domain = $blog_details->domain; 
  $path = $alias;
  $title = $alias;
  $user_id = 1; // FIXME: fetch super admin id and use that instead.


  // wpmu_create_blog( $domain, $path, $title, $user_id );
  zdae_clone($domain,$path,$title,$user_id);

  // TODO: Check if blog creation was successful. If so, redirect there. Otherwise, handle error.

  wp_redirect( get_site_url( get_current_blog_id(), $alias) );
  exit();
}
add_action( 'admin_post_zdae_replicator_action', 'replicate' );

 ?>
