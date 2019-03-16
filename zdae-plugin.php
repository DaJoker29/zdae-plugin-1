<?php 
/**
 * Plugin Name: zDae Plugin
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function replicator_handler() {
  return "Hello world";
}

add_shortcode( 'replicator', 'replicator_handler' );
 ?>