<?php

/*
Plugin Name: Surbma - Multisite Transient Cleaner
Plugin URI: http://surbma.com/wordpress-plugins/
Description: Deletes ALL transients from ALL sites in a Multisite.
Network: true

Version: 1.0.0

Author: Surbma
Author URI: http://surbma.com/

License: GPLv2

Text Domain: surbma-multisite-transient-cleaner
Domain Path: /languages/
*/

// Localization
function surbma_multisite_transient_cleaner_init() {
	load_plugin_textdomain( 'surbma-multisite-transient-cleaner', false, dirname( plugin_basename( __FILE__ ) . '/languages/' ) );
}
add_action( 'init', 'surbma_multisite_transient_cleaner_init' );

function surbma_multisite_transient_cleaner_activate() {
	if ( is_multisite() ) {
		global $wpdb;
	
		$all_sites = $wpdb->get_results( "SELECT * FROM $wpdb->blogs" );
	
		if ( $all_sites ) {
			foreach ( $all_sites as $site ) {
				$wpdb->set_blog_id( $site->blog_id );
				$wpdb->query( "DELETE FROM `{$wpdb->prefix}options` WHERE `option_name` LIKE ('_transient_%') or `option_name` LIKE ('_site_transient_%') or `option_name` LIKE ('displayed_galleries_%')" );
				$wpdb->query( "DELETE FROM `{$wpdb->prefix}sitemeta` WHERE `meta_key` LIKE ('_site_transient_%')" );
			}
		}
	}
}
register_activation_hook( __FILE__, 'surbma_multisite_transient_cleaner_activate' );