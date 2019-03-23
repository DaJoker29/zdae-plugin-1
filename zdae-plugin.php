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

        if ( isset($alias) && !siteExists($alias) ) {
            // Display form if site does not exist.
          echo '<div>';
          echo 'Your current alias is <strong>'.$alias.'</strong>';
          echo '</div>';

          echo '<form action="'.admin_url( 'admin-post.php').'" method="post">';
          echo '<input type="hidden" name="action" value="zdae_replicator_action" />';
          echo '<input type="hidden" name="alias" value="'.$alias.'" />';
          echo '<input type="submit" value="Create Website" />';
          echo '</form>';
        } elseif ( isset($alias) ) {
            // Display a notice that the site already exists and display a link to it.
            $url = get_site_url(get_current_blog_id(), $alias);
            echo '<div>';
            echo 'The site already exists';
            echo '</div>';
            echo '<a href="'.$url.'">'.$url.'</a>';
        } else {
            // Display an error if someone navigates to the page directly.
            echo '<div>';
            echo 'No user provided to create a website.';
            echo '</div>';
        }
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
  $blog_details = get_blog_details( get_current_blog_id() );

  $domain = $blog_details->domain; 
  $path = $alias;
  $title = $alias;
  $user_id = 1; // FIXME: fetch super admin id and use that instead.


  // wpmu_create_blog( $domain, $path, $title, $user_id );
  zdae_clone($domain,$path,$title,$user_id);

  // TODO: Check if blog creation was successful. If so, redirect there. Otherwise, handle error.

  $newID = get_blog_details( $alias );

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



add_action( 'elementor/editor/after_save', function( $post_id ) {
  ThreeWP_Broadcast()->api()->update_children( $post_id );
}



 ?>
