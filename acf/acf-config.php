<?php

if ( ! class_exists( 'ACF' ) ) {
	return;
}

/**
 * Set local JSON directory for ACF to store field info
 * @param  [type] $path New path
 * @return string       New path for fields
 */
add_filter( 'acf/settings/save_json', function( $path ) {
	$path = plugin_dir_path( __FILE__ ) . 'fields';

	return $path;
} );

/**
 * Set directory for ACF to load fields from
 * @param  [type] $paths Set new paths
 * @return string        New paths
 */
add_filter( 'acf/settings/load_json', function( $paths ) {
	unset( $paths[0] );

	$paths[] = plugin_dir_path( __FILE__ ) . '/fields';

	return $paths;
} );

/**
 * Add field setting for JSON local field file location.
 *
 * @param array $field_group
 */
function fin_acf_local_json_field_setting( $field_group ) {
	$base_dir = wp_normalize_path( trailingslashit( WP_CONTENT_DIR ) );
	$load_dirs = acf_get_setting( 'load_json' );
	$choices = [
		'' => 'Default',
	];
	foreach ( $load_dirs as $dir ) {
		$dir = wp_normalize_path( $dir );
		$rel = str_replace( $base_dir, '', $dir );
		$choices[ $rel ] = $rel;
	}
	acf_render_field_wrap([
		'label'        => 'Local JSON',
		'instructions' => 'Destination of local JSON file',
		'type'         => 'select',
		'prefix'       => 'acf_field_group',
		'name'         => 'local_json_path',
		'choices'      => $choices,
		'value'        => $field_group['local_json_path'] ?? '',
	]);
}
add_action( 'acf/render_field_group_settings', 'fin_acf_local_json_field_setting' );
/**
 * Override ACF local json save path if set.
 *
 * @param string $path
 *
 * @return string
 */
function fin_acf_local_json_path( $path ) {
	$local_json_path = wp_cache_get( 'acf_local_json_path' );
	if ( $local_json_path ) {
		$path = $local_json_path;
	}
	return $path;
}
add_filter( 'acf/settings/save_json', 'fin_acf_local_json_path', 500 );

/**
 * Set ACF local json save path for current field group.
 *
 * @param array $field_group
 */
function fin_acf_local_json_set_path( $field_group ) {
	if ( ! empty( $field_group['local_json_path'] ) ) {
		wp_cache_set( 'acf_local_json_path', path_join( WP_CONTENT_DIR, $field_group['local_json_path'] ) );
	}
}
add_action( 'acf/update_field_group', 'fin_acf_local_json_set_path', 9 );
add_action( 'acf/trash_field_group',  'fin_acf_local_json_set_path', 9 );
add_action( 'acf/delete_field_group', 'fin_acf_local_json_set_path', 9 );

/**
 * Restore original ACF local json save path.
 */
function fin_acf_local_json_unset_path() {
	wp_cache_delete( 'acf_local_json_path' );
}
add_action( 'acf/update_field_group', 'fin_acf_local_json_unset_path', 11 );
add_action( 'acf/trash_field_group',  'fin_acf_local_json_unset_path', 11 );
add_action( 'acf/delete_field_group', 'fin_acf_local_json_unset_path', 11 );

// Run do_shortcode on all text and textarea values
function my_acf_format_value( $value, $post_id, $field ) {
	$value = do_shortcode( $value );
	
	return $value;
}
add_filter( 'acf/format_value/type=textarea', 'my_acf_format_value', 10, 3 );
add_filter( 'acf/format_value/type=text', 'my_acf_format_value', 10, 3 );

/**
 * Add ACF options page for storing company information
 *
 */
function fin_company_options_page() {

	$args = [
		'page_title'  => get_bloginfo( 'name' ),
		'menu_title'  => get_bloginfo( 'name' ),
		'menu_slug'   => 'fin_global_options',
		'position'    => 2,
		'capability'  => 'edit_pages',
		'icon_url'    => 'dashicons-screenoptions',
		'autoload'    => true
	];

	$args = apply_filters( 'fin/company_options_page', $args );
	
	$options = acf_add_options_page( $args );

	return $options;

}
add_action( 'acf/init', 'fin_company_options_page' );
