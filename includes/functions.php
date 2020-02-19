<?php

// TEMPORARY TILL I'M USING MAI ENGINE.

function mai_temp_get_available_image_sizes() {
	// Cache.
	static $image_sizes = array();
	if ( ! empty( $image_sizes ) ) {
		return $image_sizes;
	}
	// Get image sizes.
	global $_wp_additional_image_sizes;
	$default_image_sizes = array( 'thumbnail', 'medium', 'large' );
	foreach ( $default_image_sizes as $size ) {
		$image_sizes[ $size ] = array(
			'height' => intval( get_option( "{$size}_size_h" ) ),
			'width'  => intval( get_option( "{$size}_size_w" ) ),
			'crop'   => get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false,
		);
	}
	if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
		$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
	}
	return $image_sizes;
}

function mai_temp_get_image_sizes() {
	$breakpoints = mai_temp_get_breakpoints();
	return [
		'sm' => $breakpoints['xs'], // 384px.
		'md' => $breakpoints['md'], // 768px.
		'lg' => $breakpoints['xl'], // 1152px.
	];
}

function mai_temp_get_breakpoints() {

	// "screen-xs": "400px", // mobile portrait
	// "screen-sm": "600px", // mobile landscape
	// "screen-md": "800px", // tablet portrait
	// "screen-lg": "1000px", // tablet landscape
	// "screen-xl": "1200px", // desktop

	return [
		'xs' => '',
		'sm' => 512,
		'md' => 768,
		'lg' => 1024,
		'xl' => 1152,
	];
}

add_action( 'after_setup_theme', function() {

	$image_sizes = mai_temp_get_image_sizes();

	$sizes = [
		'full'         => mai_apply_aspect_ratio( 1600, '16:9' ),
		'landscape-lg' => mai_apply_aspect_ratio( $image_sizes['lg'], '4:3' ),
		'landscape-md' => mai_apply_aspect_ratio( $image_sizes['md'], '4:3' ),
		'landscape-sm' => mai_apply_aspect_ratio( $image_sizes['sm'], '4:3' ),
		'portrait-lg'  => mai_apply_aspect_ratio( $image_sizes['lg'], '3:4' ),
		'portrait-md'  => mai_apply_aspect_ratio( $image_sizes['md'], '3:4' ),
		'portrait-sm'  => mai_apply_aspect_ratio( $image_sizes['sm'], '3:4' ),
		'square-lg'    => mai_apply_aspect_ratio( $image_sizes['lg'], '1:1' ),
		'square-md'    => mai_apply_aspect_ratio( $image_sizes['md'], '1:1' ),
		'square-sm'    => mai_apply_aspect_ratio( $image_sizes['sm'], '1:1' ),
		'tiny'         => mai_apply_aspect_ratio( 80, '1:1' ),
	];

	foreach( $sizes as $name => $values ) {
		add_image_size( $values[0], $values[1], $values[2] );
	}

});
function mai_apply_aspect_ratio( $width = 896, $ratio = '16:9' ) {
	$ratio       = explode( ':', $ratio );
	$x           = $ratio[0];
	$y           = $ratio[1];
	$height      = (int) $width / $x * $y;
	return [ $width, $height, true ];
}

//  END TEMPORARY.

/**
 * // Loop.
 * @link  https://github.com/studiopress/genesis/blob/master/lib/structure/loops.php#L64
 * // Post.
 * @link  https://github.com/studiopress/genesis/blob/master/lib/structure/post.php
 */

// do_action( 'genesis_entry_header' );
// do_action( 'genesis_before_entry_content' );
// do_action( 'genesis_entry_content' );
// do_action( 'genesis_after_entry_content' );
// do_action( 'genesis_entry_footer' );

function mai_do_entries_open( $args ) {

	// Start the attributes.
	$attributes = array(
		'class' => 'entries',
		'style' => '',
	);

	// Boxed.
	if ( $args['boxed'] ) {
		$attributes['class'] .= ' has-boxed';
	}

	// Image position.
	if ( in_array( 'image', $args['show'] ) && $args['image_position'] ) {
		$attributes['class'] .= ' has-image-' . $args['image_position'];
		if ( 'background' === $args['image_position'] ) {
			// TODO: This needs to use the engine config to get available image orientations.
			switch ( $args['image_orientation'] ) {
				case 'landscape':
				case 'portrait':
				case 'square':
					$image_size = sprintf( '%s-md', $args['image_orientation'] );
				break;
				default:
					$image_size = $args['image_size'];
			}
			$attributes['style'] .= sprintf( '--aspect-ratio:%s;', mai_get_aspect_ratio( $args['image_size'] ) );
		}
	}

	// Get the columns breakpoint array.
	$columns = mai_get_breakpoint_columns( $args );

	// Global styles.
	$attributes['style'] .= sprintf( '--columns-lg:%s;', $columns['lg'] );
	$attributes['style'] .= sprintf( '--columns-md:%s;', $columns['md'] );
	$attributes['style'] .= sprintf( '--columns-sm:%s;', $columns['sm'] );
	$attributes['style'] .= sprintf( '--columns-xs:%s;', $columns['xs'] );
	$attributes['style'] .= sprintf( '--column-gap:%s;', $args['column_gap'] );
	$attributes['style'] .= sprintf( '--row-gap:%s;', $args['row_gap'] );
	$attributes['style'] .= sprintf( '--align-columns:%s;', ! empty( $args['align_columns'] ) ? $args['align_columns'] : 'unset' );
	$attributes['style'] .= sprintf( '--align-columns-vertical:%s;', ! empty( $args['align_columns_vertical'] ) ? $args['align_columns_vertical'] : 'unset' );
	$attributes['style'] .= sprintf( '--align-text:%s;', mai_get_align_text( $args['align_text'] ) );
	$attributes['style'] .= sprintf( '--align-text-vertical:%s;', mai_get_align_text( $args['align_text_vertical'] ) );

	genesis_markup(
		[
			'open'    => '<div %s>',
			'context' => 'entries',
			'echo'    => true,
			'atts'    => $attributes,
			'params'  => [
				'args' => $args,
			],
		]
	);

	genesis_markup(
		[
			'open'    => '<div %s>',
			'context' => 'entries-wrap',
			'echo'    => true,
			'params'  => [
				'args' => $args,
			],
		]
	);

}

function mai_do_entries_close( $args ) {

	genesis_markup(
		[
			'close'   => '</div>',
			'context' => 'entries-wrap',
			'echo'    => true,
			'params'  => [
				'args' => $args,
			],
		]
	);

	genesis_markup(
		[
			'close'   => '</div>',
			'context' => 'entries',
			'echo'    => true,
			'params'  => [
				'args' => $args,
			],
		]
	);

}

/**
 * Echo a grid entry.
 *
 * @param   object  The (post, term, user) entry object.
 * @param   object  The object to get the entry.
 *
 * @return  string
 */
function mai_do_entry( $entry, $args ) {
	$entry = new Mai_Entry( $entry, $args );
	$entry->render();
}

function mai_get_breakpoint_columns( $args ) {

	$columns = [
		'lg' => $args['columns'],
	];

	if ( $args['columns_responsive'] ) {
		$columns['md'] = $args['columns_md'];
		$columns['sm'] = $args['columns_sm'];
		$columns['xs'] = $args['columns_xs'];
	} else {
		switch ( $args['columns'] ) {
			case 6:
				$columns['md'] = 4;
				$columns['sm'] = 3;
				$columns['xs'] = 2;
			break;
			case 5:
				$columns['md'] = 3;
				$columns['sm'] = 2;
				$columns['xs'] = 2;
			break;
			case 4:
				$columns['md'] = 4;
				$columns['sm'] = 2;
				$columns['xs'] = 1;
			break;
			case 3:
				$columns['md'] = 3;
				$columns['sm'] = 1;
				$columns['xs'] = 1;
			break;
			case 2:
				$columns['md'] = 2;
				$columns['sm'] = 2;
				$columns['xs'] = 1;
			break;
			case 1:
				$columns['md'] = 1;
				$columns['sm'] = 1;
				$columns['xs'] = 1;
			break;
			case 0: // Auto.
				$columns['md'] = 0;
				$columns['sm'] = 0;
				$columns['xs'] = 0;
			break;
		}
	}

	return $columns;
}

function mai_get_align_text( $alignment ) {
	switch ( $alignment ) {
		case 'start':
		case 'top':
			$value = 'start';
		break;
		case 'center':
		case 'middle':
			$value = 'center';
		break;
		case 'bottom':
		case 'end':
			$value = 'end';
		break;
		default:
			$value = 'unset';
	}
	return $value;
}

/**
 * Return content stripped down and limited content.
 *
 * Strips out tags and shortcodes, limits the output to `$max_char` characters.
 *
 * @param   string  $content The content to limit.
 * @param   int     $limit   The maximum number of characters to return.
 *
 * @return  string  Limited content.
 */
function mai_get_content_limit( $content, $limit ) {

	// Strip tags and shortcodes so the content truncation count is done correctly.
	$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'get_the_content_limit_allowedtags', '<script>,<style>' ) );

	// Remove inline styles / scripts.
	$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

	// Truncate $content to $limit.
	$content = genesis_truncate_phrase( $content, $limit );

	return $content;
}

function mai_get_aspect_ratio( $image_size ) {
	$all_sizes = mai_temp_get_available_image_sizes();
	$sizes     = isset( $all_sizes[ $image_size ] ) ? $all_sizes[ $image_size ] : [4,3];
	return sprintf( '%s/%s', $sizes[0], $sizes[1] );
}

// function mai_is_post_template( $args ) {
// 	return ( 'post' === $args['type'] ) && in_array( $args['context'], [ 'singular', 'archive' ] );
// }
