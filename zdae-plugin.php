<?php 
/**
 * Plugin Name: zDae Plugin
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function replicator_handler() {
  $alias = $_GET['alias'];
  return "Your current alias is <strong>$alias<strong>";
}

add_shortcode( 'replicator', 'replicator_handler' );
 ?>