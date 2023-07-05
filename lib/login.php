<?php 

/**
 * Remove the shake effect following unsuccessful login attempts
 */
add_action( 'login_footer', function() {
	remove_action( 'login_footer', 'wp_shake_js', 12 );
} );

/**
 * Replace link to wordpress.org from login logo
 * with the site's home url
 * @return str site home url
 */
add_filter( 'login_headerurl', function() {
	return get_bloginfo( 'url' );
} );

/**
 * Replace reference to WordPress in logo text
 * with the site's title
 */
add_filter( 'login_headertext', function() {
	return 'Return to ' . get_bloginfo( 'name' );
} );