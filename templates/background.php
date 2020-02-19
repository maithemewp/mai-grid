<?php

$entry_classes = 'mai-grid__entry';
if ( $data->args['show_image'] && $data->image_id ) {
	$entry_classes .= ' has-background';
}
if ( $data->args['show_title'] ) {
	$entry_classes .= ' has-content';
}
echo sprintf( '<div class="%s">', $entry_classes );

	// Aspect ratio outer.
	// printf( '<a href="%s" class="mai-grid__entry-outer">', $data->link );

		// Aspect ratio inner.
		// echo '<div class="mai-grid__inner">';

		printf( '<a href="%s" class="mai-grid__inner">', $data->link );

			// Image.
			if ( $data->args['show_image'] && $data->image_id ) {
				$image_size  = ( empty( $data->image_size ) || ( 'default' == $data->image_size ) ) ? 'thumbnail' : $data->image_size;
				$image_position = 'none';
				echo wp_get_attachment_image( $data->image_id, $image_size, false, array( 'class' => 'mai-grid__image' ) );
			}

			// Title.
			echo ( $data->args['show_title'] && $data->title ) ? sprintf( '<h3 class="mai-grid__title">%s</h3>', $data->title ) : '';

		echo '</a>';

		// echo '</div>';

	echo '</a>';

echo '</div>';
