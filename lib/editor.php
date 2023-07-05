<?php

$unwrap_cite_tags = function ( $content ) {
	return preg_replace( '/<p>(<cite[^>]*>.+?<\/cite>)<\/p>/', '$1', $content );
};

add_filter( 'acf_the_content', $unwrap_cite_tags, 20, 3 );
add_filter( 'the_content', $unwrap_cite_tags );