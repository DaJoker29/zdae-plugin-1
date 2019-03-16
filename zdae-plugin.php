<?php 
/**
 * Plugin Name: zDae Plugin
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function replicator_handler() {
  $alias = $_GET['alias'];
  echo '<div>';
  echo 'Your current alias is <strong>$alias<strong>';
  echo '</div>';

  echo '<form action="' + $admin_url( 'admin-post.php') + '" method="post">';
  echo '<input type="hidden" name="alias" value="' + $alias + '" />';
  echo '<input type="submit" value="Create Website" />';
  // echo '<button>Create Website</button>'
  echo '</form>';
}

add_shortcode( 'replicator', 'replicator_handler' );
 ?>