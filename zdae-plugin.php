<?php 
/**
 * Plugin Name: zDae Plugin
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function replicator_handler() {
  $alias = get_query_var( 'alias' );
  return $alias;
}

add_shortcode( 'replicator', 'replicator_handler' );
 ?>