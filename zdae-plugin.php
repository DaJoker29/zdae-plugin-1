<?php 
/**
 * Plugin Name: zDae Plugin
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function replicator_handler() {
  $alias = $_GET['alias'];

  // TODO: Display a different form if the ALIAS field isn't provided.

  echo '<div>';
  echo 'Your current alias is <strong>'.$alias.'</strong>';
  echo '</div>';

  echo '<form action="'.admin_url( 'admin-post.php').'" method="post">';
  echo '<input type="hidden" name="action" value="zdae_replicator_action" />';
  echo '<input type="hidden" name="alias" value="'.$alias.'" />';
  echo '<input type="submit" value="Create Website" />';
  echo '</form>';
}
add_shortcode( 'replicator', 'replicator_handler' );

function replicate() {
  status_header(200);
  $alias = $_POST['alias'];
  $blog_details = get_blog_details( get_current_blog_id() );

  $domain = $blog_details->domain; // TODO: Verify this is working.
  $path = $alias;
  $title = $alias;
  $user_id = 1; // FIXME: fetch super admin id and user that instead.


  wpmu_create_blog( $domain, $path, $title, $user_id );

  // TODO: Check if blog creation was successful. If so, redirect there. Otherwise, handle error.

  wp_redirect( get_site_url( get_current_blog_id(), $alias) );
  exit();
}
add_action( 'admin_post_zdae_replicator_action', 'replicate' );

 ?>