<?php

add_shortcode( 'cmb-edit-media-files', 'cmb2_edit_media_files_shortcode' );
/**
 * Shortcode to display a CMB2 form for a post ID.
 *
 * @param  array $atts Shortcode attributes
 * @return string       Form HTML markup
 */
function cmb2_edit_media_files_shortcode( $atts = array() ) {
	global $post;

	// if ( ! current_user_can( 'edit_posts' ) ) {
	// return __( 'You do not have permissions to edit this post.', 'lang_domain' );
	// }

	if ( ! isset( $atts['post_id'] ) ) {
		$atts['post_id'] = $post->ID;
	}

	if ( empty( $atts['id'] ) ) {
		return __( "Please add an 'id' attribute to specify the CMB2 form to display.", 'dao-consensus' );
	}

	$metabox_id = esc_attr( $atts['id'] );
	$object_id  = absint( $atts['post_id'] );
	// Get our form
	$form = cmb2_get_metabox_form( $metabox_id, $object_id );

	return $form;
}
