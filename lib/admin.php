<?php

/**
 * Customise admin footer text
 * to prompt action when needing support
 * @return string The updated text
 */
add_filter( 'admin_footer_text', function() {
	$text = 'Website developed by <a href="https://studiofinesse.co.uk" target="_blank" rel="noreferrer">Studio Finesse</a>. Contact <a href="mailto:support@studiofinesse.co.uk">support@studiofinesse.co.uk</a> for support';

	$text = apply_filters( 'fin/admin_footer_text', $text );

	echo $text;
} );

/**
 * Clean up admin bar
 */
add_action( 'wp_before_admin_bar_render',function() {
	global $wp_admin_bar;

	/* Remove their stuff */
	$wp_admin_bar->remove_menu( 'wp-logo' );
	$wp_admin_bar->remove_menu( 'comments' );

	// Remove 'How are you <user>? text'
	$user_id = get_current_user_id();
	$avatar = get_avatar(  $user_id, 16  );
	$wp_admin_bar->add_menu(  array(
		'id' => 'my-account',
		'title' => ' ' . $avatar  )
	 );
}, 0 );