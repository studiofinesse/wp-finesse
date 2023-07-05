<?php

/**
 * Simply output results of var_dump function
 * inside preformatted tags for easy reading
 * @param  mixed $data Data to explore
 * @return str       Result of data inside <pre> tags
 */
function dump( $data ) {
	echo '<pre class="debug">' , var_dump( $data ) , '</pre>';
}

function dump_r( $data ) {
	echo '<pre class="debug">' , print_r( $data ) , '</pre>';
}

function fin_attr( $atts ) {
	$attr = '';

	foreach ( $atts as $key => $value ) {
		if ( is_numeric( $key ) ) {
			continue;
		} elseif ( $value === true ) {
			$attr .= " $key";
		} elseif ( is_scalar( $value ) && strlen( $value ) ) {
			$attr .= " $key=\"" . esc_attr( $value ) . '"';
		}
	}

	return $attr;
}

function imgix_args( $args = [] ) {
	$defaults = [
		'h'    => 900,
		'w'    => 1600,
		'crop' => 'entropy',
		'cs'   => 'tinysrgb',
		'fit'  => 'crop',
		'fm'   => 'jpg',
	];

	$args = wp_parse_args( $args, $defaults );

	return $args;
}

function unsplash_img_url( $id, $args = [] ) {
	$args = imgix_args( $args );
	$url = 'https://images.unsplash.com/photo-' . $id;
	$params = http_build_query( $args );

	return $url . '?' . $params;
}

function unsplash_img( $id, $echo = true, $args = [] ) {
	$args = imgix_args( $args );
	$src = unsplash_img_url( $id, $args );
	$html = '<img width="' . $args['w'] . '" height="' . $args['h'] . '" src="' . $src . '" alt="" loading="lazy" />';

	if( $echo ) {
		echo $html;
	} else {
		return $html;
	}
}