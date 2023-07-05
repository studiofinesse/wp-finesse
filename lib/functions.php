<?php

/**
 * Convert a string of items into an array with key value pairs
 *
 * @param string $items
 * @return array 
 */
function fin_get_contact_details( $items ) {
	$data = [];

	// Convert emails to array based on line breaks
	$items = preg_split('/[\r\n]+/', $items, NULL, PREG_SPLIT_NO_EMPTY);

	foreach( $items as $item ) {
		if( str_contains( $item, ':' ) ) {
			$parts = explode( ':', $item );
			$parts = array_map( 'trim', $parts ); // Trim the spaces around the colon, if used
			
			$data[] = [
				'label' => $parts[0],
				'value' => $parts[1]
			];
		} else {
			$data[] = $item;
		}
	}

	return $data;
}

/**
 * Get the primary value from a set of contact details
 *
 * @param array $items
 * @param string $format value | array 
 * @return mixed Returns either an array including a label, or just the value
 */
function fin_get_primary_contact_details( $items, $format = 'array' ) {
	$item = $items[0];

	if( $format == 'array' ) {
		if( is_array( $item ) ) {
			$primary = $item;
		} else {
			$primary = [
				'label' => '',
				'value' => $item
			];
		}
	} else {
		if( is_array( $item ) ) {
			$primary = $item['value'];
		} else {
			$primary = $item;
		}
	}

	return $primary;
}

/**
 * Get a list of company emails from option page
 *
 * @return array An array of email address with optional label
 */
function fin_get_company_email() {
	return fin_get_contact_details( get_field( 'company_email', 'option' ) );
}

/**
 * Get the primary email address
 *
 * @param string $format array | value
 * @return mixed Returns either an array including a label, or just the value
 */
function fin_get_primary_company_email( $format = 'array' ) {
	return fin_get_primary_contact_details( fin_get_company_email(), $format );
}

/**
 * Output the html for an email link
 *
 * @param string $email the raw email address
 * @return string the anchor tag html
 */
function fin_email_link_html( $email ) {
	echo '<a href="mailto:' . antispambot( $email ) . '">' . $email . '</a>';
}

/**
 * Get a list of company telephone numbers from option page
 *
 * @return array An array of telephone numbers with optional label
 */
function fin_get_company_tel() {
	return fin_get_contact_details( get_field( 'company_tel', 'option' ) );
}

/**
 * Get the primary telephone number
 *
 * @param string $format array | value
 * @return mixed Returns either an array including a label, or just the value
 */
function fin_get_primary_company_tel( $format = 'array' ) {
	return fin_get_primary_contact_details( fin_get_company_tel(), $format );
}

function fin_tel_link_html( $tel ) {
	echo '<a href="tel:' . str_replace( ' ', '', $tel ) . '">' . $tel . '</a>';
}

function fin_get_company_address() {
	$address = get_field( 'company_address', 'option' );
	return $address ? array_filter( $address ) : false;
}

function fin_address_html( $address, $separator = '' ) {
	if( ! $address ) return;

	$parts = [];

	foreach( array_filter( $address ) as $label => $line ) {
		$parts[] = '<span class="' . str_replace( '_', '-', $label ) . '">' . $line . '</span>';
	}

	$html = '<p class="address">';
	$html .= implode( $separator, $parts );
	$html .= '</p>';

	echo $html;
}

function fin_google_maps_link_html( $address, $type = 'place', $text = '', $class = '' ) {
	$url = 'https://www.google.com/maps/';
	$url .= $type == 'place' ? 'search/' : 'dir/Current+Location/';
	$url .= str_replace( ' ', '+', get_field( 'company_name', 'option' ) ) . '+';
	$url .= str_replace( ' ', '+', implode( '+', array_filter( $address ) ) );

	$attr = [
		'href' => $url,
		'target' => '_blank',
		'rel' => 'no_referrer',
		'class' => $class
	];

	$attr = fin_attr( $attr );

	$text = $text ?: ( $type == 'place' ? 'Find on Google Maps' : 'Get Directions' );

	echo "<a$attr>$text</a>";
}

function fin_social_account_username_regex( $type ) {

	$regex = [
		'facebook' => "/(?:https?:)?\/\/(?:www\.)?(?:facebook|fb)\.com\/(?P<username>(?![A-z]+\.php)(?!marketplace|gaming|watch|me|messages|help|search|groups)[A-z0-9_\-\.]+)\/?/",
		'twitter' => "/(?:https?:)?\/\/(?:[A-z]+\.)?twitter\.com\/@?(?!home|share|privacy|tos)(?P<username>[A-z0-9_]+)\/?/",
		'instagram' => "/(?:https?:)?\/\/(?:www\.)?(?:instagram\.com|instagr\.am)\/(?P<username>[A-Za-z0-9_](?:(?:[A-Za-z0-9_]|(?:\.(?!\.))){0,28}(?:[A-Za-z0-9_]))?)/",
		'youtube' => '/(?:https?:)?\/\/(?:[A-z]+\.)?youtube.com\/(?P<username>[A-z0-9-\_]+)\/?/',
		'linked' => '/(?:https?:)?\/\/(?:[\w]+\.)?linkedin\.com\/(?P<company_type>(company)|(school))\/(?P<username>[A-z0-9-À-ÿ\.]+)\/?/'
	];

	if( ! array_key_exists( $type, $regex ) ) return;

	return $regex[$type];

}

function fin_get_social_account_username_from_url( $type, $url ) {
	$regex = fin_social_account_username_regex( $type );

	if( ! $regex ) return;

	preg_match( $regex, $url, $matches );

	if( array_key_exists( 'username', $matches ) ) {
		$handle = $slug == 'youtube' && $matches['username'] == 'channel' ? 'YouTube' : '@' . $matches['username'];

		return $handle;
	} else {
		return false;
	}
}

function fin_get_company_social_accounts_raw() {
	return array_filter( get_field( 'company_social_accounts', 'options' ) );
}

function fin_get_company_social_accounts() {
	$data = [];

	foreach( fin_get_company_social_accounts_raw() as $account ) {
		$data[$account['account']['label']] = $account['url'];
	}

	return $data;
}

function fin_social_accounts_links_html( $accounts, $args = [] ) {
	if( ! $accounts ) return;

	$args = wp_parse_args( $args, [
		'before' => '<div class="social-links">',
		'after' => '</div>'
	] );
	
	echo $args['before'];
	foreach( $accounts as $account => $url ) {
		echo "<a href='$url'>$account</a>";
	}
	echo $args['after'];
}

function fin_social_accounts_icons_html( $accounts, $args = [] ) {
	if( ! $accounts ) return;

	$args = wp_parse_args( $args, [
		'before' => '<div class="social-icons">',
		'after' => '</div>'
	] );
	
	echo $args['before'];
	foreach( $accounts as $account => $url ) {
		$icon = file_get_contents( FIN_DIR . '/assets/img/icon-' . strtolower( $account ) . '.svg' );
		echo "<a href='$url' title='$account' target='_blank' rel='noreferrer'>$icon</a>";
	}
	echo $args['after'];
}