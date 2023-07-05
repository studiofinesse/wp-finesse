<?php

/**
 * Output copyright symbol and current year
 * @return str e.g. '© 2023'
 */
function sc_copyright_message( $atts ) {

	$current_year = date( 'Y' );

	extract( shortcode_atts( array(
		'start' => $current_year,
		'append' => ''

	), $atts ) );

	if( $start == $current_year ) {
		$years = $current_year;
	} else {
		$years = "$start-$current_year";
	}

	return '&copy ' . $years . $append;

}
add_shortcode( 'copyright', 'sc_copyright_message' );

add_shortcode( 'company_email', function() {
	return fin_get_primary_company_email()['value'];
} );

add_shortcode( 'company_email_link', function() {
	ob_start();
	fin_email_link_html( fin_get_primary_company_email()['value'] );
	return ob_get_clean();
} );

add_shortcode( 'company_tel', function() {
	return fin_get_primary_company_tel()['value'];
} );

add_shortcode( 'company_tel_link', function() {
	ob_start();
	fin_tel_link_html( fin_get_primary_company_tel()['value'] );
	return ob_get_clean();
} );

add_shortcode( 'company_address', function() {
	ob_start();
	fin_address_html( fin_get_company_address(), ', ' );
	return ob_get_clean();
} );

add_shortcode( 'company_google_maps_link', function( $atts ) {
	extract( shortcode_atts ( array(
		'type' => 'place',
		'text' => '',
		'class' => ''
	), $atts ) );

	ob_start();
	fin_google_maps_link_html( fin_get_company_address(), $type, $text, $class );
	return ob_get_clean();
} );