<?php

/**
 * ----------------------- ARCHIVE FILTERS --------------------- /
 */

add_action( 'wp_ajax_dao-filter-archive-posts', 'dao_cons_filter_archive_posts' );
add_action( 'wp_ajax_nopriv_dao-filter-archive-posts', 'dao_cons_filter_archive_posts' );
function dao_cons_filter_archive_posts() {
	check_ajax_referer( 'dao-consensus-filter-archive-posts' );

	/* query_vars is neccessary */
	if ( ! isset( $_POST['query_vars'] ) ) {
		die();
	}

	$args = map_deep( $_POST['query_vars'], 'sanitize_text_field' );

	$args['post_status'] = array( 'active', 'inactive' );

	/* Add filters to main query */

	if ( isset( $_POST['filters'] ) ) {
		parse_str( $_POST['filters'], $filters );

		if ( ! empty( $filters ) ) {
			/* Sanitize filters */
			$filters = array_map( 'sanitize_text_field', $filters );

			if ( isset( $filters['status'] ) && ! empty( $filters['status'] ) ) {
				if ( ! in_array( $filters['status'], array('active', 'inactive') ) ) {
					wp_send_json_error( array( 'message' => __('Вы можете запросить только карточки со статусом "Активно" или "Неактивно".', 'dao-consensus') ) );
				}
				$args['post_status'] = $filters['status'];
			}

			if ( isset( $filters['person_type'] ) && ! empty( $filters['person_type'] ) ) {
				$authors = get_users(
					array(
						'meta_key'     => 'person_type',
						'meta_value'   => $filters['person_type'],
						'meta_compare' => '=',
						'fields'       => 'ID',
					)
				);

				if ( ! empty( $authors ) ) {
					$args['author__in'] = $authors;
				} else {
					$args['author'] = 99999999; // provocate not found any post
				}
			}

			if ( isset( $filters['categories'] ) && ! empty( $filters['categories'] ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'categories',
					'field'    => 'slug',
					'terms'    => explode( ',', $filters['categories'] ),
				);
			}
		}
	}

	$the_query = new WP_Query( $args );

	$vars = array();

	$vars['found_posts']   = $the_query->found_posts;
	$vars['max_num_pages'] = $the_query->max_num_pages;

	ob_start();

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) :
			$the_query->the_post();

			get_template_part( 'template-parts/content', get_post_type() );
		endwhile;
	} else {
		get_template_part( 'template-parts/content', 'none' );
	}

	wp_reset_postdata();

	$html = ob_get_clean();

	wp_send_json_success( array( 'posts' => $html ) + $vars );
}

add_action('wp_ajax_dao-filter-archive-transactions', 'dao_cons_filter_archive_transactions');
add_action('wp_ajax_nopriv_dao-filter-archive-transactions', 'dao_cons_filter_archive_transactions');
function dao_cons_filter_archive_transactions () {
	check_ajax_referer('dao-consensus-filter-archive-transactions');

	/* query_vars is neccessary */
	if ( ! isset( $_POST['query_vars'] ) ) {
		wp_send_json( 'query_vars param is required', 403 );
	}

	$args = map_deep( $_POST['query_vars'], 'sanitize_text_field' );

	$args['post_status'] = 'completed';
	$args['meta_query'] = array();

	$args['meta_query'][] = array(
		'key'     => 'initiator_testimonial',
		'value'   => '',
		'compare' => '!=',
	);
	$args['meta_query'][] = array(
		'key'     => 'deal_person_testimonial',
		'value'   => '',
		'compare' => '!=',
	);

	/* Add filters to main query */
	if ( isset( $_POST['filters'] ) ) {

		parse_str( $_POST['filters'], $filters );

		if ( ! empty( $filters ) ) {
			/* Sanitize filters */
			$filters = array_map( 'sanitize_text_field', $filters );

			if ( ! empty( $filters['search_query'] ) ) {
				$args['s'] = $filters['search_query'];
			}

			if ( ! empty( $filters['cryptocurrency'] ) ) {
				$args['meta_query'][] = array(
					'key'   => 'cryptocurrency',
					'value' => $filters['cryptocurrency']
				);
			}
		}
	}

	if ( isset( $args['orderby']['total_price'] ) ) {
		$args['meta_query']['total_price'] = array(
			'key' 	  => 'total_price',
			'compare' => 'EXISTS'
		);
	}

	$the_query = new WP_Query( $args );

	$vars = array();

	$vars['found_posts']   = $the_query->found_posts;
	$vars['max_num_pages'] = $the_query->max_num_pages;

	ob_start();

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) :
			$the_query->the_post();

			get_template_part( 'template-parts/content', 'transactions' );
		endwhile;
	else: 
		get_template_part( 'template-parts/content', 'none' );
	endif;

	wp_reset_postdata();

	$html = ob_get_clean();

	wp_send_json_success( array( 'posts' => $html ) + $vars + $args );
}

/**
 * ------------------ CREATE DEMAND AND OFFER ------------------- /
 */

add_action( 'wp_ajax_daoc_create_demand', 'daoc_create_demand' );
function daoc_create_demand() {
	check_ajax_referer( 'dao-consensus-create-demand' );

	if (
		! isset( $_FILES['cover'] ) ||
		! isset( $_POST['title'] ) ||
		! isset( $_POST['categories'] ) ||
		! isset( $_POST['description'] ) ||
		! isset( $_POST['total_price'] ) ||
		! isset( $_POST['cryptocurrency'] ) ||
		! isset( $_POST['deadline'] ) ||
		! isset( $_POST['deadline_period'] ) ||
		! isset( $_POST['skills'] )
	) {
		die();
	}

	$total_price = floatval( sanitize_text_field( $_POST['total_price'] ) );
	if ( ! $total_price ) {
		wp_send_json_error( array( 'message' => 'Значение поля стоимость должно быть целым или десятиричным числом.' ) );
	}

	$currency = sanitize_text_field( $_POST['cryptocurrency'] );
	if ( ! isset( DAO_CONSENSUS::cryptocurrencies[ $currency ] ) ) {
		wp_send_json_error( array( 'message' => 'Такого формата расчёта не зарегистрировано.' ) );
	}

	$deadline = sanitize_text_field( $_POST['deadline'] );
	if ( ! intval( $deadline ) ) {
		wp_send_json_error( array( 'message' => 'Значение поля срок выполнения должно быть целым числом.' ) );
	}

	$deadline_period = sanitize_text_field( $_POST['deadline_period'] );
	if ( ! isset( DAO_CONSENSUS::deadline_periods[ $deadline_period ] ) ) {
		wp_send_json_error( array( 'message' => 'Такого периода не зарегистировано.' ) );
	}

	$title       = sanitize_text_field( $_POST['title'] );
	$description = trim( wp_kses_post( $_POST['description'] ) );

	/* check if post exists */
	if ( post_exists( $title, $description, '', 'demands' ) !== 0 ) {
		wp_send_json_error( array( 'message' => 'Такой спрос уже существует.' ) );
	}

	/* validate cover */
	$cover_file = $_FILES['cover'];

	$allowed_file_types = array( 'image/jpg', 'image/jpeg', 'image/png', 'video/mp4' );
	$image_formats      = array( 'image/jpg', 'image/jpeg', 'image/png' );

	/* check image format */
	if ( ! in_array( $cover_file['type'], $image_formats ) ) {
		wp_send_json_error( array( 'message' => 'Обложка должна быть в формате png, jpg или jpeg.' ) );
	}

	/* check image size */
	if ( $cover_file['size'] > 2097152 ) {
		wp_send_json_error( array( 'message' => __( 'Вес обложки не должен превышать 2MB.', 'dao-consensus' ) ) );
	}

	/* check if media files are recieved */
	$media_files = array();

	if ( isset( $_FILES['media_files'] ) && ! empty( $_FILES['media_files']['name'] ) ) {
		/* sort out array */
		$media_files = rearrange_files_arr( $_FILES['media_files'] );
		if ( count( $media_files ) > 5 ) {
			wp_send_json_error( array( 'message' => 'Вы можете загрузить не больше 5-ти файлов.' ) );
		}

		/** check image and videos filetype */
		foreach ( $media_files as $k => $file ) {
			if ( ! in_array( $file['type'], $allowed_file_types ) ) {
				/* if is video format */
				if ( strstr( $file['type'], 'video/' ) ) {
					wp_send_json_error( array( 'message' => 'Видео должно быть в формате mp4.' ) );
				} else {
					wp_send_json_error( array( 'message' => 'Изображения должны быть в формате png, jpg или jpeg.' ) );
				}
			}
		}

		/** check image and video size */
		foreach ( $media_files as $k => $file ) {
			if ( strstr( $file['type'], 'video/' ) ) {
				if ( $file['size'] > 52428800 ) {
					wp_send_json_error( array( 'message' => __( 'Вес видео не должен превышать 50MB.', 'dao-consensus' ) ) );
				}
			} else {
				if ( $file['size'] > 2097152 ) {
					wp_send_json_error( array( 'message' => __( 'Вес изображения не должен превышать 2MB.', 'dao-consensus' ) ) + $file );
				}
			}
		}
	}

	$demand_id = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_author'  => get_current_user_id(),
			'post_type'    => 'demands',
			'post_status'  => 'pending',
			'post_content' => $description,
			'meta_input'   => array(
				'total_price'     => $total_price,
				'cryptocurrency'  => $currency,
				'deadline'        => $deadline,
				'deadline_period' => $deadline_period,
			),
		)
	);

	/* check on error */
	if ( is_wp_error( $demand_id ) ) {
		error_log( $demand_id->get_error_message() );
		wp_send_json_error( array( 'message' => 'Во время создания спроса возникла ошибка. Попробуйте ещё раз.' ) );
	}

	/* fill skills meta */
	$skills = array_map( 'sanitize_text_field', explode( ',', $_POST['skills'][0] ) );
	update_post_meta( $demand_id, 'skills', $skills );

	/* set categories */
	$categories = array_map( 'sanitize_text_field', explode( ',', $_POST['categories'][0] ) );
	wp_set_object_terms( $demand_id, $categories, 'categories' );

	$wp_upload_dir = wp_upload_dir();

	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	/**
	 * UPLOAD COVER
	 */

	$cover_ext          = pathinfo( $cover_file['name'], PATHINFO_EXTENSION );
	$cover_file['name'] = "demand{$demand_id}-cover.{$cover_ext}";

	$upload_cover = wp_handle_upload( $cover_file, array( 'test_form' => false ) );

	/* check on error */
	if ( isset( $upload_cover['error'] ) && ! empty( $upload_cover['error'] ) ) {
		error_log( $upload_cover['error'] );
		wp_send_json_error( array( 'message' => __( 'Во время загрузки обложки возникла ошибка. Попробуйте ещё раз.', 'dao-consensus' ) ) );
	}

	$upload_cover_filename = basename( $upload_cover['file'] );

	if ( $upload_cover ) {
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . $upload_cover_filename,
			'post_mime_type' => $upload_cover['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', $upload_cover_filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$thumb_id    = wp_insert_attachment( $attachment, $upload_cover['file'] );
		$attach_data = wp_generate_attachment_metadata( $thumb_id, $upload_cover['file'] );
		wp_update_attachment_metadata( $thumb_id, $attach_data );
	}

	/* set post thumbnail */
	set_post_thumbnail( $demand_id, $thumb_id );

	/**
	 * UPLOAD MEDIA FILES
	 */

	if ( ! empty( $media_files ) ) {
		$attachments = array();

		foreach ( $media_files as $k => $file ) {
			$ext          = pathinfo( $file['name'], PATHINFO_EXTENSION );
			$file['name'] = "demand{$demand_id}-{$k}.{$ext}";

			$upload_mediafile = wp_handle_upload( $file, array( 'test_form' => false ) );

			/* check on error */
			if ( isset( $upload_mediafile['error'] ) && ! empty( $upload_mediafile['error'] ) ) {
				error_log( $upload_mediafile['error'] );
				wp_send_json_error( array( 'message' => __( 'Во время загрузки медиа файлов возникла ошибка. Попробуйте ещё раз.', 'dao-consensus' ) ) );
			}

			$upload_mediafile_filename = basename( $upload_mediafile['file'] );

			if ( $upload_mediafile ) {
				$attachment  = array(
					'guid'           => $wp_upload_dir['url'] . '/' . $upload_mediafile_filename,
					'post_mime_type' => $upload_mediafile['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', $upload_mediafile_filename ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				);
				$attach_id   = wp_insert_attachment( $attachment, $upload_mediafile['file'] );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_mediafile['file'] );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				$attachments[ $attach_id ] = $upload_mediafile['file'];// $wp_upload_dir['url'] . "/" . $upload_mediafile_filename;
			}
		}

		if ( ! empty( $attachments ) ) {
			update_post_meta( $demand_id, 'media_files', $attachments );
		}
	}

	wp_send_json_success();
}

add_action( 'wp_ajax_daoc_create_offer', 'daoc_create_offer' );
function daoc_create_offer() {
	check_ajax_referer( 'dao-consensus-create-offer' );

	if (
		! isset( $_FILES['cover'] ) ||
		! isset( $_POST['title'] ) ||
		! isset( $_POST['categories'] ) ||
		! isset( $_POST['description'] ) ||
		! isset( $_POST['total_price'] ) ||
		! isset( $_POST['cryptocurrency'] ) ||
		! isset( $_POST['deadline'] ) ||
		! isset( $_POST['deadline_period'] ) ||
		! isset( $_POST['skills'] )
	) {
		die();
	}

	$total_price = floatval( sanitize_text_field( $_POST['total_price'] ) );
	if ( ! $total_price ) {
		wp_send_json_error( array( 'message' => 'Значение поля стоимость должно быть целым или десятиричным числом.' ) );
	}

	$currency = sanitize_text_field( $_POST['cryptocurrency'] );
	if ( ! isset( DAO_CONSENSUS::cryptocurrencies[ $currency ] ) ) {
		wp_send_json_error( array( 'message' => 'Такого формата расчёта не зарегистрировано.' ) );
	}

	$deadline = sanitize_text_field( $_POST['deadline'] );
	if ( ! intval( $deadline ) ) {
		wp_send_json_error( array( 'message' => 'Значение поля срок выполнение должно быть целым числом.' ) );
	}

	$deadline_period = sanitize_text_field( $_POST['deadline_period'] );
	if ( ! isset( DAO_CONSENSUS::deadline_periods[ $deadline_period ] ) ) {
		wp_send_json_error( array( 'message' => 'Такого периода не зарегистировано.' ) );
	}

	$title       = sanitize_text_field( $_POST['title'] );
	$description = trim( wp_kses_post( $_POST['description'] ) );

	/* check if post exists */
	if ( post_exists( $title, $description, '', 'offers' ) !== 0 ) {
		wp_send_json_error( array( 'message' => 'Такое предложение уже существует.' ) );
	}

	/* validate cover */
	$cover_file = $_FILES['cover'];

	$allowed_file_types = array( 'image/jpg', 'image/jpeg', 'image/png', 'video/mp4' );
	$image_formats      = array( 'image/jpg', 'image/jpeg', 'image/png' );

	/* check image format */
	if ( ! in_array( $cover_file['type'], $image_formats ) ) {
		wp_send_json_error( array( 'message' => 'Обложка должна быть в формате png, jpg или jpeg.' ) );
	}

	/* check image size */
	if ( $cover_file['size'] > 2097152 ) {
		wp_send_json_error( array( 'message' => __( 'Вес обложки не должен превышать 2MB.', 'dao-consensus' ) ) );
	}

	if ( isset( $_FILES['media_files'] ) && ! empty( $_FILES['media_files']['name'] ) ) {
		/* sort out array */
		$media_files = rearrange_files_arr( $_FILES['media_files'] );
		if ( count( $media_files ) > 5 ) {
			wp_send_json_error( array( 'message' => 'Вы можете загрузить не больше 5-ти файлов.' ) );
		}

		/* check image and videos filetype */
		$allowed_file_types = array( 'image/jpg', 'image/jpeg', 'image/png', 'video/mp4' );
		foreach ( $media_files as $k => $file ) {
			if ( ! in_array( $file['type'], $allowed_file_types ) ) {
				/* if is video format */
				if ( strstr( $file['type'], 'video/' ) ) {
					wp_send_json_error( array( 'message' => 'Видео должно быть в формате mp4.' ) );
				} else {
					wp_send_json_error( array( 'message' => 'Изображения должны быть в формате png, jpg или jpeg.' ) );
				}
			}
		}

		/* check media file size */
		foreach ( $media_files as $k => $file ) {
			if ( strstr( $file['type'], 'video/' ) ) {
				if ( $file['size'] > 52428800 ) {
					wp_send_json_error( array( 'message' => __( 'Вес видео не должен превышать 50MB.', 'dao-consensus' ) ) );
				}
			} else {
				if ( $file['size'] > 2097152 ) {
					wp_send_json_error( array( 'message' => __( 'Вес изображения не должен превышать 2MB.', 'dao-consensus' ) ) + $file );
				}
			}
		}
	}

	$offer_id = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_author'  => get_current_user_id(),
			'post_type'    => 'offers',
			'post_status'  => 'pending',
			'post_content' => $description,
			'meta_input'   => array(
				'total_price'     => $total_price,
				'cryptocurrency'  => $currency,
				'deadline'        => $deadline,
				'deadline_period' => $deadline_period,
			),
		)
	);

	/* check on error */
	if ( is_wp_error( $offer_id ) ) {
		error_log( $offer_id->get_error_message() );
		wp_send_json_error( array( 'message' => 'Во время создания предложения возникла ошибка. Попробуйте ещё раз.' ) );
	}

	/* fill skills meta */
	$skills = array_map( 'sanitize_text_field', explode( ',', $_POST['skills'][0] ) );
	update_post_meta( $offer_id, 'skills', $skills );

	/* set categories */
	$categories = array_map( 'sanitize_text_field', explode( ',', $_POST['categories'][0] ) );
	wp_set_object_terms( $offer_id, $categories, 'categories' );

	$wp_upload_dir = wp_upload_dir();

	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	/**
	 * UPLOAD COVER
	 */

	$cover_ext          = pathinfo( $cover_file['name'], PATHINFO_EXTENSION );
	$cover_file['name'] = "offer{$offer_id}-cover.{$cover_ext}";

	$upload_cover = wp_handle_upload( $cover_file, array( 'test_form' => false ) );

	/* check on error */
	if ( isset( $upload_cover['error'] ) && ! empty( $upload_cover['error'] ) ) {
		error_log( $upload_cover['error'] );
		wp_send_json_error( array( 'message' => __( 'Во время загрузки обложки возникла ошибка. Попробуйте ещё раз.', 'dao-consensus' ) ) );
	}

	$upload_cover_filename = basename( $upload_cover['file'] );

	if ( $upload_cover ) {
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . $upload_cover_filename,
			'post_mime_type' => $upload_cover['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', $upload_cover_filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$thumb_id    = wp_insert_attachment( $attachment, $upload_cover['file'] );
		$attach_data = wp_generate_attachment_metadata( $thumb_id, $upload_cover['file'] );
		wp_update_attachment_metadata( $thumb_id, $attach_data );
	}

	/* set post thumbnail */
	set_post_thumbnail( $offer_id, $thumb_id );

	/**
	 * UPLOAD MEDIA FILES
	 */

	if ( ! empty( $media_files ) ) {
		$attachments = array();

		foreach ( $media_files as $k => $file ) {
			$ext          = pathinfo( $file['name'], PATHINFO_EXTENSION );
			$file['name'] = "offer{$offer_id}-{$k}.{$ext}";

			$upload_mediafile = wp_handle_upload( $file, array( 'test_form' => false ) );

			/* check on error */
			if ( isset( $upload_mediafile['error'] ) && ! empty( $upload_mediafile['error'] ) ) {
				error_log( $upload_mediafile['error'] );
				wp_send_json_error( array( 'message' => __( 'Во время загрузки медиа файлов возникла ошибка. Попробуйте ещё раз.', 'dao-consensus' ) ) );
			}

			$upload_mediafile_filename = basename( $upload_mediafile['file'] );

			if ( $upload_mediafile ) {
				$attachment  = array(
					'guid'           => $wp_upload_dir['url'] . '/' . $upload_mediafile_filename,
					'post_mime_type' => $upload_mediafile['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', $upload_mediafile_filename ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				);
				$attach_id   = wp_insert_attachment( $attachment, $upload_mediafile['file'] );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_mediafile['file'] );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				$attachments[ $attach_id ] = $upload_mediafile['file'];// $wp_upload_dir['url'] . "/" . $upload_mediafile_filename;
			}
		}

		if ( ! empty( $attachments ) ) {
			update_post_meta( $offer_id, 'media_files', $attachments );
		}
	}

	wp_send_json_success();
}

/**
 * ------------------ GET PROFILE CARDS ------------------- /
 */

// for displaying cards on profile.php ( private ) and author.php ( public )
add_action( 'wp_ajax_dao-get-profile-cards', 'dao_cons_get_profile_cards' );
add_action( 'wp_ajax_nopriv_dao-get-profile-cards', 'dao_cons_get_profile_cards' );
function dao_cons_get_profile_cards() {
	check_ajax_referer( 'dao-consensus-get-profile-cards' );

	/* query_vars is neccessary */
	if ( ! isset( $_POST['query_vars'] ) ) {
		die();
	}

	$args = map_deep( $_POST['query_vars'], 'sanitize_text_field' );

	/* prevent quering offers for non-authors */
	if ( get_current_user_id() != $args['author'] ) {
		die();
	}

	/* prevent quering cards with other statuses */
	if ( empty( array_intersect( $args['post_status'], array('active', 'inactive') ) ) ) {
		wp_send_json_error( array( 'message' => __('Вы этом разделе вы можете просмотреть карточки только со статусом "Активно" и "Неактивно"', 'dao-consensus') ) );
	}

	$the_query = new WP_Query( $args );

	ob_start();

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) :
			$the_query->the_post();

			get_template_part( 'template-parts/profile-content', 'card' );
		endwhile;
	} else {
		get_template_part( 'template-parts/content', 'none' );
	}

	wp_reset_postdata();

	$html = ob_get_clean();

	wp_send_json_success( array( 'posts' => $html ) );
}

// profile demands and offers
add_action( 'wp_ajax_dao-get-profile-own-posts', 'dao_cons_get_profile_own_posts' );
function dao_cons_get_profile_own_posts() {
	check_ajax_referer( 'dao-consensus-get-profile-posts' );

	$args = map_deep( $_POST['query_vars'], 'sanitize_text_field' );

	/* prevent quering demands for other users */
	if ( get_current_user_id() !== (int) $args['author'] ) {
		die();
	}

	$args['post_status'] = array('active', 'inactive');

	if ( isset( $_POST['filters'] ) && ! empty( $_POST['filters'] ) ) {
		parse_str( $_POST['filters'], $filters );

		if ( ! empty( $filters ) ) {
			/* Sanitize filters */
			$filters = array_map( 'sanitize_text_field', $filters );

			if ( isset( $filters['search_query'] ) && ! empty( $filters['search_query'] ) ) {
				$args['s'] = $filters['search_query'];
			}

			if ( isset( $filters['status'] ) && ! empty( $filters['status'] ) ) {
				if ( ! in_array( $filters['status'], array('active', 'inactive') ) ) {
					wp_send_json_error( array( 'message' => __('Вы можете запросить только карточки со статусом "Активно" или "Неактивно".', 'dao-consensus') ) );
				}
				$args['post_status'] = $filters['status'];
			}

			if ( isset( $filters['categories'] ) && ! empty( $filters['categories'] ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'categories',
					'field'    => 'slug',
					'terms'    => explode(',', $filters['categories']),
				);
			}
		}
	}

	$the_query = new WP_Query( $args );

	$vars = array();

	$vars['found_posts']   = $the_query->found_posts;
	$vars['max_num_pages'] = $the_query->max_num_pages;

	ob_start();

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) :
			$the_query->the_post();

			get_template_part( 'template-parts/profile-content', 'card' );
		endwhile;
	} else {
		get_template_part( 'template-parts/content', 'none' );
	}

	wp_reset_postdata();

	$html = ob_get_clean();

	wp_send_json_success( array( 'posts' => $html ) + $vars );
}

/**
 * ---------------- USER ACTIONS PERFORMED ON CARDS ------------------ /
 */

add_action( 'wp_ajax_dao-change-card-status', 'dao_cons_change_card_status' );
function dao_cons_change_card_status() {
	check_ajax_referer( 'dao-consensus-change-card-status' );

	if ( empty( $_POST['card_id'] ) || empty( $_POST['status'] ) ) {
		die();
	}

	// check user role
	$user = wp_get_current_user();
	if ( ! in_array( 'contributor', (array) $user->roles ) && ! in_array( 'administrator', (array) $user->roles ) ) {
		die();
	}

	$id         = sanitize_text_field( $_POST['card_id'] );
	$new_status = sanitize_text_field( $_POST['status'] );

	$card = get_post( $id );

	/* compare ids post_author and current user id */
	if ( get_post_field( 'post_author', $id ) != $user->ID ) {
		wp_send_json_error( array( 'message' => 'Вы не можете изменить статус карточки, поскольку не являетесь её автором.' ) );
	}

	$old_status = get_post_status( $id );

	if ( $card->post_type === 'demands' ) {
		if ( $card->post_status === 'in-process' && ! empty( get_post_meta( $card->ID, 'performer', true ) ) ) {
			wp_send_json_error( array( 'message' => 'Вы не можете изменить статус спроса, поскольку спрос находиться в работе и имеет своего исполнителя.' ) );
		}
	} elseif ( $card->post_type === 'offers' ) {
		if ( $card->post_status === 'in-process' && ! empty( get_post_meta( $card->ID, 'customers', true ) ) ) {
			wp_send_json_error( array( 'message' => 'Вы не можете изменить статус предложения, поскольку оно ещё имеет заказчиков.' ) );
		}
	} else {
		wp_send_json_error( array( 'message' => 'С таким ИД ни спроса, ни предложения не зарегистрировано.' ) );
	}

	$res = wp_update_post(
		array(
			'ID'          => $id,
			'post_status' => $new_status,
		),
		true
	);

	/* check if successfull */
	if ( is_wp_error( $res ) ) {
		$errors = $res->get_error_messages();
		foreach ( $errors as $error ) {
			error_log( $error );
		}
		wp_send_json_error();
	} else {
		wp_send_json_success(
			array(
				'id'   => $id,
				'from' => $old_status,
				'to'   => $new_status,
				'text' => DAO_CONSENSUS::card_statuses[ $new_status ],
			)
		);
	}
}

add_action( 'wp_ajax_dao-delete-card', 'dao_cons_delete_card' );
function dao_cons_delete_card() {
	check_ajax_referer( 'dao-consensus-delete-card' );

	if ( ! isset( $_POST['card_id'] ) ) {
		die();
	}

	$user = wp_get_current_user();
	if ( ! in_array( 'contributor', (array) $user->roles ) && ! in_array( 'administrator', (array) $user->roles ) ) {
		wp_send_json_error( array( 'message' => 'У вас нету прав для удаления данной карточки.' ) );
	}

	$id = sanitize_text_field( $_POST['card_id'] );

	$post_type = get_post_type( $id );

	/* check post type */
	if ( $post_type !== 'demands' && $post_type !== 'offers' ) {
		die();
	}

	/* compare ids post_author and current user id */
	$author_id = (int) get_post_field( 'post_author', $id );
	if ( $author_id !== (int) $user->ID ) {
		wp_send_json_error( array( 'message' => 'Вы не можете удалить данную карточку, поскольку не являетесь её автором.' ) );
	}

	$res = wp_trash_post( $id );

	if ( ! empty( $res ) ) {
		wp_send_json_success( array( 'card_id' => $id ) );
	} else {
		wp_send_json_error();
	}

}

/**
 * -------------------------- CARD EDIT -------------------------- /
 */

add_action( 'wp_ajax_dao-card-edit', 'dao_cons_card_edit' );
function dao_cons_card_edit() {
	check_ajax_referer( 'dao-consensus-card-edit' );

	if (
		! isset( $_POST['id'] ) ||
		! isset( $_POST['title'] ) ||
		! isset( $_POST['categories'] ) ||
		! isset( $_POST['description'] ) ||
		! isset( $_POST['total_price'] ) ||
		! isset( $_POST['cryptocurrency'] ) ||
		! isset( $_POST['deadline'] ) ||
		! isset( $_POST['deadline_period'] ) ||
		! isset( $_POST['skills'] )
	) {
		die();
	}

	$id          = sanitize_text_field( $_POST['id'] );
	$title       = sanitize_text_field( $_POST['title'] );
	$description = trim( wp_kses_post( $_POST['description'] ) );

	/* check if post exists */
	if ( ! get_post_status( $id ) ) {
		wp_send_json_error( array( 'message' => 'Карточки с таким ID не зарегистировано.' ) );
	}

	/* check if editor is the same person as a card author */
	$curr_user   = wp_get_current_user();
	$card_author = (int) get_post_field( 'post_author', $id );
	if ( (int) $curr_user->ID !== $card_author ) {
		wp_send_json_error( array( 'message' => 'Вы не можете редактировать данную карточку, поскольку не являетесь её автором.' ) );
	}

	$total_price = floatval( sanitize_text_field( $_POST['total_price'] ) );
	if ( ! $total_price ) {
		wp_send_json_error( array( 'message' => 'Значение поля стоимость должно быть целым или десятиричным числом.' ) );
	}

	$currency = sanitize_text_field( $_POST['cryptocurrency'] );
	if ( ! isset( DAO_CONSENSUS::cryptocurrencies[ $currency ] ) ) {
		wp_send_json_error( array( 'message' => 'Такого формата расчёта не зарегистрировано.' ) );
	}

	$deadline = sanitize_text_field( $_POST['deadline'] );
	if ( ! intval( $deadline ) ) {
		wp_send_json_error( array( 'message' => 'Значение поля срок выполнения должно быть целым числом.' ) );
	}

	$deadline_period = sanitize_text_field( $_POST['deadline_period'] );
	if ( ! isset( DAO_CONSENSUS::deadline_periods[ $deadline_period ] ) ) {
		wp_send_json_error( array( 'message' => 'Такого периода не зарегистировано.' ) );
	}

	$allowed_file_types = array( 'image/jpg', 'image/jpeg', 'image/png', 'video/mp4' );
	$image_formats      = array( 'image/jpg', 'image/jpeg', 'image/png' );

	/* ------ VALIDATE COVER AND MEDIA FILES ------ */
	if ( ! empty( $_FILES['cover']['name'] ) ) {
		$cover_file = $_FILES['cover'];

		/* check image format */
		if ( ! in_array( $cover_file['type'], $image_formats ) ) {
			wp_send_json_error( array( 'message' => 'Обложка должна быть в формате png, jpg или jpeg.' ) );
		}

		/* check image size */
		if ( $cover_file['size'] > 2097152 ) {
			wp_send_json_error( array( 'message' => __( 'Вес обложки не должен превышать 2MB.', 'dao-consensus' ) ) );
		}
	}

	if ( isset( $_FILES['media_files'] ) && ! empty( $_FILES['media_files']['name'] ) ) {
		/* sort out array */
		$media_files = rearrange_files_arr( $_FILES['media_files'] );
		if ( count( $media_files ) > 5 ) {
			wp_send_json_error( array( 'message' => 'Вы можете загрузить не больше 5-ти файлов.' ) );
		}

		/** check image and videos filetype */
		foreach ( $media_files as $k => $file ) {
			if ( ! in_array( $file['type'], $allowed_file_types ) ) {
				/* if is video format */
				if ( strstr( $file['type'], 'video/' ) ) {
					wp_send_json_error( array( 'message' => 'Видео должно быть в формате mp4.' ) );
				} else {
					wp_send_json_error( array( 'message' => 'Изображения должны быть в формате png, jpg или jpeg.' ) );
				}
			}
		}

		/** check image and video size */
		foreach ( $media_files as $k => $file ) {
			if ( strstr( $file['type'], 'video/' ) ) {
				if ( $file['size'] > 52428800 ) {
					wp_send_json_error( array( 'message' => __( 'Вес видео не должен превышать 50MB.', 'dao-consensus' ) ) );
				}
			} else {
				if ( $file['size'] > 2097152 ) {
					wp_send_json_error( array( 'message' => __( 'Вес изображения не должен превышать 2MB.', 'dao-consensus' ) ) + $file );
				}
			}
		}
	}

	$post_id = wp_update_post(
		array(
			'ID'           => $id,
			'post_title'   => $title,
			'post_status'  => 'pending',
			'post_content' => $description,
			'meta_input'   => array(
				'total_price'     => $total_price,
				'cryptocurrency'  => $currency,
				'deadline'        => $deadline,
				'deadline_period' => $deadline_period,
			),
		),
		true
	);

	/* check on error */
	if ( is_wp_error( $post_id ) ) {
		error_log( $post_id->get_error_message() );
		wp_send_json_error( array( 'message' => 'Во время изменения данных произошла ошибка. Попробуйте ещё раз.' ) );
	}

	/* fill skills meta */
	$skills = explode( ',', $_POST['skills'][0] );
	update_post_meta( $post_id, 'skills', array_map( 'sanitize_text_field', $skills ) );

	/* set category */
	$categories = array_map( 'sanitize_text_field', explode( ',', $_POST['categories'][0] ) );
	wp_set_object_terms( $post_id, $categories, 'categories' );

	/* delete old attachments */
	if ( ! empty( $_POST['remove_media_files'] ) ) {
		$remove_attachs = array_map( 'sanitize_text_field', explode( ',', $_POST['remove_media_files'] ) );
		if ( ! empty( $remove_attachs ) ) {
			$media_files = get_post_meta( $post_id, 'media_files', true );
			foreach ( $remove_attachs as $id ) {
				if ( is_attachment( $id ) ) {
					wp_delete_attachment( $id, true );
				}
				unset( $media_files[$id] );
			}
			update_post_meta( $post_id, 'media_files', $media_files );
		}
	}

	$wp_upload_dir = wp_upload_dir();

	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	$post_type 	= substr( get_post_type( $post_id ), 0, -1 ); //remove 's' letter

	/**
	 * UPLOAD COVER
	 */
	if ( isset( $_FILES['cover'] ) && ! empty( $_FILES['cover']['name'] ) ) {
		$cover_ext          = pathinfo( $cover_file['name'], PATHINFO_EXTENSION );
		$cover_file['name'] = "{$post_type}{$post_id}-cover.{$cover_ext}";
	
		$upload_cover = wp_handle_upload( $cover_file, array( 'test_form' => false ) );
	
		/* check on error */
		if ( isset( $upload_cover['error'] ) && ! empty( $upload_cover['error'] ) ) {
			error_log( $upload_cover['error'] );
			wp_send_json_error( array( 'message' => __( 'Во время загрузки обложки возникла ошибка. Попробуйте ещё раз.', 'dao-consensus' ) ) );
		}
	
		$upload_cover_filename = basename( $upload_cover['file'] );
	
		if ( $upload_cover ) {
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . $upload_cover_filename,
				'post_mime_type' => $upload_cover['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $upload_cover_filename ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);
	
			$thumb_id    = wp_insert_attachment( $attachment, $upload_cover['file'] );
			$attach_data = wp_generate_attachment_metadata( $thumb_id, $upload_cover['file'] );
			wp_update_attachment_metadata( $thumb_id, $attach_data );
		}
	 
		/* remove old post thumbnail */
		wp_delete_attachment( get_post_thumbnail_id( $post_id ), true );

		/* set new post thumbnail */
		set_post_thumbnail( $post_id, $thumb_id );
	}

	/**
	 * UPLOAD MEDIA FILES
	 */

	if ( isset( $_FILES['media_files'] ) && ! empty( $_FILES['media_files']['name'] ) ) {
		$attachments = array();

		$def_media_files = get_post_meta( $post_id, 'media_files', true );

		if ( ! empty( $def_media_files ) ) {
			$attachments = $def_media_files;
			unset( $def_media_files );
		}

		$media_files = rearrange_files_arr( $_FILES['media_files'] );

		foreach ( $media_files as $k => $file ) {
			$ext          = pathinfo( $file['name'], PATHINFO_EXTENSION );
			$file['name'] = "{$post_type}{$post_id}-{$k}.{$ext}";

			$upload_mediafile = wp_handle_upload( $file, array( 'test_form' => false ) );

			/* check on error */
			if ( isset( $upload_mediafile['error'] ) && ! empty( $upload_mediafile['error'] ) ) {
				error_log( $upload_mediafile['error'] );
				wp_send_json_error( array( 'message' => __( 'Во время загрузки медиа файлов возникла ошибка. Попробуйте ещё раз.', 'dao-consensus' ) ) );
			}

			$upload_mediafile_filename = basename( $upload_mediafile['file'] );

			if ( $upload_mediafile ) {
				$attachment  = array(
					'guid'           => $wp_upload_dir['url'] . '/' . $upload_mediafile_filename,
					'post_mime_type' => $upload_mediafile['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', $upload_mediafile_filename ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);
				$attach_id   = wp_insert_attachment( $attachment, $upload_mediafile['file'] );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_mediafile['file'] );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				$attachments[ $attach_id ] = $upload_mediafile['file'];
			}
		}

		if ( ! empty( $attachments ) ) {
			update_post_meta( $post_id, 'media_files', $attachments );
		}
	}

	wp_send_json_success();
}

/**
 * ----------------------- GET CARDS IN PROCESS ----------------------- /
 */

add_action( 'wp_ajax_dao-get-profile-demands-inprocess', 'dao_cons_get_profile_demands_inprocess' );
function dao_cons_get_profile_demands_inprocess() {
	check_ajax_referer( 'dao-consensus-get-profile-card-inprocess' );

	$args = map_deep( $_POST['query_vars'], 'sanitize_text_field' );

	$curr_user = wp_get_current_user();

	if ( isset( $_POST['filters'] ) ) {

		parse_str( $_POST['filters'], $filters );

		if ( ! empty( $filters ) ) {
			/* Sanitize filters */
			$filters = array_map( 'sanitize_text_field', $filters );

			if ( ! empty( $filters['search_query'] ) ) {
				$args['s'] = $filters['search_query'];
			}

			if ( ! empty( $filters['type'] ) ) {
				
				if ( $filters['type'] === 'mine' ) {
					$args['author'] = $curr_user->ID;
				} elseif ( $filters['type'] === 'other' ) {
					if ( ! isset( $args['meta_query'] ) ) {
						$args['meta_query'] = [];
					}
					$args['meta_query'][] = array(
						'key' => 'performer',
						'value' => $curr_user->ID,
						'compare' => '='
					);
				}

			}
		}
	}

	$the_demands = new WP_Query( $args );

	$vars = array();

	ob_start();

	$vars['found_posts']   = $the_demands->found_posts;
	$vars['max_num_pages'] = $the_demands->max_num_pages;

	if ( $the_demands->have_posts() ) :
		while ( $the_demands->have_posts() ) :
			$the_demands->the_post();

			get_template_part( 'template-parts/profile-content-demand', 'inprocess' );
		endwhile;
	else :
		get_template_part( 'template-parts/content', 'none' );
	endif;

	$demands = ob_get_clean();

	wp_send_json_success( array( 'cards' => $demands ) + $vars );
}

add_action( 'wp_ajax_dao-get-profile-offers-inprocess', 'dao_cons_get_profile_offers_inprocess' );
function dao_cons_get_profile_offers_inprocess() {
	check_ajax_referer( 'dao-consensus-get-profile-card-inprocess' );

	if ( ! isset( $_POST['query_vars'] ) ) {
		wp_send_json('', 403);
	}

	$args = map_deep( $_POST['query_vars'], 'sanitize_text_field' );

	$curr_user = wp_get_current_user();

	if ( isset( $_POST['filters'] ) ) {

		parse_str( $_POST['filters'], $filters );

		if ( ! empty( $filters ) ) {
			/* Sanitize filters */
			$filters = array_map( 'sanitize_text_field', $filters );

			if ( ! empty( $filters['search_query'] ) ) {
				$args['s'] = $filters['search_query'];
			}

			if ( ! empty( $filters['type'] ) ) {

				if ( $filters['type'] === 'mine' ) {
					$args['author'] = $curr_user->ID;
				} elseif ( $filters['type'] === 'other' ) {
					if ( ! isset( $args['meta_query'] ) ) {
						$args['meta_query'] = [];
					}
					$args['meta_query'][] = array(
						'key' => 'customers',
						'value' => ":{i:{$curr_user->ID};",
						'compare' => 'LIKE'
					);
				}

			}
		}
	}

	$the_offers = new WP_Query( $args );

	$vars = array();

	ob_start();

	$vars['found_posts']   = $the_offers->found_posts;
	$vars['max_num_pages'] = $the_offers->max_num_pages;

	if ( $the_offers->have_posts() ) :
		while ( $the_offers->have_posts() ) :
			$the_offers->the_post();

			get_template_part( 'template-parts/profile-content-offer', 'inprocess' );
		endwhile;
	else :
		get_template_part( 'template-parts/content', 'none' );
	endif;

	$offers = ob_get_clean();

	wp_send_json_success( array( 'cards' => $offers ) + $vars );
}

/**
 * ------------------------- CHANGE USERDATA -------------------------- /
 */

add_action( 'wp_ajax_dao-change-userdata', 'dao_cons_change_userdata' );
function dao_cons_change_userdata() {
	check_ajax_referer( 'dao-consensus-change-userdata' );

	if (
		! isset( $_POST['first_name'] ) ||
		! isset( $_POST['last_name'] ) ||
		! isset( $_POST['email'] ) ||
		! isset( $_POST['about'] ) ||
		! isset( $_POST['user_type'] )
	) {
		die();
	}

	$user_id = (int) sanitize_text_field( $_POST['user_id'] );

	$curr_user = wp_get_current_user();

	if ( $user_id !== $curr_user->ID ) {
		die;
	}

	$first_name  = sanitize_text_field( $_POST['first_name'] );
	$last_name   = sanitize_text_field( $_POST['last_name'] );
	$email       = sanitize_email( $_POST['email'] );
	$description = sanitize_textarea_field( $_POST['about'] );

	$user_type = sanitize_text_field( $_POST['user_type'] );
	if ( ! isset( DAO_CONSENSUS::person_types[ $user_type ] ) ) {
		die;
	}

	/* validate profile picture if set */
	if ( ! empty( $_FILES['profile_picture']['name'] ) ) {
		$allowed_image_formats = array( 'image/jpg', 'image/jpeg', 'image/png' );

		$profile_picture = $_FILES['profile_picture'];
		/* check image format */
		if ( ! in_array( $profile_picture['type'], $allowed_image_formats ) ) {
			wp_send_json_error( array( 'message' => __( 'Изображение профиля должно быть в формате jpg, jpeg и png.', 'dao-consensus' ) ) );
		}
		/* check image size */
		if ( $profile_picture['size'] > 3145728 ) {
			wp_send_json_error( array( 'message' => __( 'Вес изображения профиля не должен превышать 3MB.', 'dao-consensus' ) ) );
		}
	}

	$specialization = sanitize_text_field( $_POST['specialization'] );

	/* conditional statements is added due to different ways of sending data from user side */
	$skills = array();
	if ( $user_type === 'physical' ) {
		$skills = array_map( 'sanitize_text_field', explode( ',', $_POST['skills'][0] ) );
	} else {
		$skills = array_map( 'sanitize_text_field', $_POST['skills'] );
	}

	$twitter   = sanitize_text_field( $_POST['twitter'] );
	$instagram = sanitize_text_field( $_POST['instagram'] );

	$user_data = wp_update_user(
		array(
			'ID'         => $user_id,
			'first_name' => $first_name,
			'last_name'  => $last_name,
			'user_email' => $email,
		)
	);

	if ( is_wp_error( $user_data ) ) {
		wp_send_json_error( array( 'message' => 'Во время изменения личных данных произошла ошибка. Попробуйте ещё раз.' ) );
	}

	/* update about yourself */
	update_user_meta( $user_id, 'description', $description );
	/* update user type */
	update_user_meta( $user_id, 'person_type', $user_type );
	/* update specialization */
	update_user_meta( $user_id, 'specialization', $specialization );
	/* update skills */
	update_user_meta( $user_id, 'skills', $skills );
	/* update social media */
	update_user_meta( $user_id, 'twitter', $twitter );
	update_user_meta( $user_id, 'instagram', $instagram );

	/* set company info for juridical user type */
	if ( ! empty( $user_type ) && $user_type === 'juridical' ) {
		if ( isset( $_POST['company_name'] ) ) {
			$company_name = sanitize_text_field( $_POST['company_name'] );
			update_user_meta( $curr_user->ID, 'company_name', $company_name );
		}

		if ( isset( $_POST['company_workarea'] ) ) {
			$company_workarea = sanitize_text_field( $_POST['company_workarea'] );
			update_user_meta( $curr_user->ID, 'company_workarea', $company_workarea );
		}

		if ( isset( $_POST['company_juraddress'] ) ) {
			$company_juraddress = sanitize_text_field( $_POST['company_juraddress'] );
			update_user_meta( $curr_user->ID, 'company_juraddress', $company_juraddress );
		}

		if ( isset( $_POST['company_phone'] ) ) {
			$company_phone = sanitize_text_field( $_POST['company_phone'] );
			update_user_meta( $curr_user->ID, 'company_phone', $company_phone );
		}

		if ( isset( $_POST['company_email'] ) ) {
			$company_email = sanitize_email( $_POST['company_email'] );
			update_user_meta( $curr_user->ID, 'company_email', $company_email );
		}

		if ( isset( $_POST['company_website'] ) ) {
			$company_website = esc_url_raw( $_POST['company_website'] );
			update_user_meta( $curr_user->ID, 'company_website', $company_website );
		}
	}

	/* empty company info if user type is physical */
	if ( ! empty( $user_type ) && $user_type === 'physical' ) {
		$company_info = array( 'company_name', 'company_workarea', 'company_juraddress', 'company_phone', 'company_email', 'company_website' );

		foreach ( $company_info as $k ) {
			update_user_meta( $user_id, $k, '' );
		}
	}

	if ( ! empty( $_FILES['profile_picture']['name'] ) ) {
		$wp_upload_dir = wp_upload_dir();

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$profile_picture = $_FILES['profile_picture'];

		$ext                     = pathinfo( $profile_picture['name'], PATHINFO_EXTENSION );
		$profile_picture['name'] = "user_avatar-{$user_id}.{$ext}";

		/** handle upload */
		$upload_profile = wp_handle_upload( $profile_picture, array( 'test_form' => false ) );

		$upload_profile_filename = basename( $upload_profile['file'] );

		if ( $upload_profile && empty( $upload_profile['error'] ) ) {
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . $upload_profile_filename,
				'post_mime_type' => $upload_profile['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $upload_profile_filename ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			$attach_id   = wp_insert_attachment( $attachment, $upload_profile['file'] );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_profile['file'] );
			wp_update_attachment_metadata( $attach_id, $attach_data );

			if ( function_exists( 'wp_user_avatars_update_avatar' ) ) {
				wp_user_avatars_update_avatar( $curr_user->ID, $attach_id );
			}
		} else {
			error_log( $upload_profile['error'] );
			wp_send_json_error( array( 'message' => __( 'Во время загрузки изображения профиля произошла ошибка. Попробуйте ещё раз.', 'dao-consensus' ) ) );
		}
	}

	wp_send_json_success( $skills );
}

add_action( 'wp_ajax_dao-change-userpassword', 'dao_cons_change_userpassword' );
function dao_cons_change_userpassword() {
	check_ajax_referer( 'dao-consensus-change-userpassword' );

	$curr_user = wp_get_current_user();
	$user_id   = (int) sanitize_text_field( $_POST['user_id'] );

	if ( $curr_user->ID !== $user_id ) {
		die;
	}

	$old_pass = sanitize_text_field( $_POST['old_password'] );
	if ( ! wp_check_password( $old_pass, $curr_user->data->user_pass, $curr_user->ID ) ) {
		wp_send_json_error( array( 'message' => 'Текущий пароль не совпадает с тем, что вы ввели. Измените и попробуйте ещё раз.' ) );
	}

	$new_pass    = sanitize_text_field( $_POST['new_password'] );
	$repeat_pass = sanitize_text_field( $_POST['repeat_new_password'] );

	if ( $new_pass !== $repeat_pass ) {
		wp_send_json_error( array( 'message' => 'Повторите новый пароль верно.' ) );
	}

	if ( $old_pass === $new_pass ) {
		wp_send_json_error( array( 'message' => 'Этот пароль уже установлен.' ) );
	}

	wp_set_password( $new_pass, $curr_user->ID );

	wp_send_json_success();
}

/**
 * ------------------------- USER PORTFOLIO -------------------------- /
 */

add_action( 'wp_ajax_dao-get-portfolio-info', 'dao_cons_get_portfolio_info' );
function dao_cons_get_portfolio_info() {
	check_ajax_referer( 'dao-consensus-get-portfolio-info' );

	if (
		! isset( $_POST['id'] ) ||
		! isset( $_POST['type'] )
	) {
		die;
	}

	$id = sanitize_text_field( $_POST['id'] );

	$portfolio_item = get_post( $id );

	if ( $_POST['type'] === 'view' ) {

		$categories = wp_get_post_terms( $id, 'portfolio_categories', array('fields' => 'names') );

		$data = array(
			'title' => $portfolio_item->post_title,
			'date'  => $portfolio_item->post_date,
			'content' => apply_filters( 'the_content', $portfolio_item->post_content ),
		);
		$data['categories'] = implode( ', ', $categories );
	
		$media_files = get_post_meta( $portfolio_item->ID, 'media_files', true );
	
		ob_start();
	
		foreach ( $media_files as $id => $path ):
			if ( pathinfo($path, PATHINFO_EXTENSION) !== 'mp4'  ) :
			?>
				<img src="<?= wp_get_attachment_url( $id ); ?>" alt="portfolio item image">
			<?	
			else :
			?>
				<video src="<?= wp_get_attachment_url( $id ); ?>" controls="controls"></video>
			<?
			endif;
		endforeach;
	
		$data['media_files'] = ob_get_clean();
	
		wp_send_json_success( $data );

	} elseif ( $_POST['type'] === 'edit' ) {

		$curr_user = wp_get_current_user();

		if ( $curr_user->ID != $portfolio_item->post_author ) {
			wp_send_json_error( array( 'message' => __('Вы не можете редактировать данную работу, поскольку не являетесь её автором.', 'dao-consensus') ) );
		}

		$categories = wp_get_post_terms( $id, 'portfolio_categories' );

		$slugs = array();

		foreach ( $categories as $cat ) {
			$slugs[] = $cat->slug;
		}

		$data = array(
			'item_id' => $portfolio_item->ID,
			'title' => $portfolio_item->post_title,
			'date'  => $portfolio_item->post_date,
			'categories' => $slugs,
			'content' => apply_filters( 'the_content', $portfolio_item->post_content ),
		);

		ob_start();
		?>
			<div class="image-view">
				<?
					$thumb_id = get_post_thumbnail_id( $portfolio_item->ID );
					$thumb_file = get_attached_file( $thumb_id );
				?>
				<img src="<?php echo wp_get_attachment_url( $thumb_id ); ?>" alt="card cover">
				<span class="title"><?php echo basename( $thumb_file ); ?></span>
				<span class="size"><? printf("%4.2f MB", filesize( $thumb_file )/1048576); ?></span>
				<span class="close">&times;</span>
			</div>
		<?
		$data['cover'] = ob_get_clean();
	
		$data['media_files'] = dao_cmb2_get_file_list_media( $portfolio_item->ID, 'media_files' );
	
		wp_send_json_success( $data );

	} else {
		wp_send_json( '', 403 );
	}

}
add_action( 'wp_ajax_dao-add-portfolio', 'dao_cons_add_portfolio' );
function dao_cons_add_portfolio() {
	check_ajax_referer( 'dao-consensus-add-portfolio' );

	if (
		! isset( $_POST['title'] ) ||
		! isset( $_POST['description'] ) ||
		! isset( $_POST['categories'] ) ||
		! isset( $_FILES['cover'] ) ||
		! isset( $_FILES['media_files'] )
	) {
		wp_send_json('', 403);
	}

	$curr_user = wp_get_current_user();

	$title = sanitize_text_field( $_POST['title'] );
	if ( strlen( $title ) < 5 ) {
		wp_send_json_error( array( 'message' => 'Название работы не должно быть короче 5 символов.' ) );
	}
	if ( strlen( $title ) > 200 ) {
		wp_send_json_error( array( 'message' => 'Название работы не должно быть длинее 200 символов.' ) );
	}

	$description = wp_kses_post( $_POST['description'] );
	if ( strlen( $description ) < 50 ) {
		wp_send_json_error( array( 'message' => 'Описание работы не должно быть короче 50 символов.' ) );
	}
	if ( strlen( $description ) > 2000 ) {
		wp_send_json_error( array( 'message' => 'Описание работы не должно быть длинее 2000 символов.' ) );
	}

	/* check if post exists */
	if ( post_exists( $title, $description, '', 'portfolio' ) !== 0 ) {
		wp_send_json_error( array( 'message' => 'Такая работа уже добавлена в ваше портфолио.' ) );
	}

	/* validate cover */
	if ( isset( $_FILES['cover']['name'] ) && ! empty( $_FILES['cover']['name'] ) ) {
		$cover_file = $_FILES['cover'];

		$image_formats      = array( 'image/jpg', 'image/jpeg', 'image/png' );

		/* check cover format */
		if ( ! in_array( $cover_file['type'], $image_formats ) ) {
			wp_send_json_error( array( 'message' => 'Изображение обложки должно быть в формате jpg, jpeg или png.' ) );
		}
		/* check cover size */
		if ( $cover_file['size'] > 3145728 ) {
			wp_send_json_error( array( 'message' => __( 'Вес обложки не должен превышать 3MB.', 'dao-consensus' ) ) );
		}
	}

	/* validate media files */
	if ( isset( $_FILES['media_files'] ) && ! empty( $_FILES['media_files'] ) ) {
		$allowed_file_types = array( 'image/jpg', 'image/jpeg', 'image/png', 'video/mp4' );

		$media_files = rearrange_files_arr( $_FILES['media_files'] );

		if ( count( $media_files ) > 5 ) {
			wp_send_json_error( array( 'message' => 'Вы можете загрузить не больше 5-ти файлов.' ) );
		}

		/** check image and video filetype */
		foreach ( $media_files as $k => $file ) {
			if ( ! in_array( $file['type'], $allowed_file_types ) ) {
				/* if is video format */
				if ( strstr( $file['type'], 'video/' ) ) {
					wp_send_json_error( array( 'message' => 'Видео должно быть в формате mp4.' ) );
				} else {
					wp_send_json_error( array( 'message' => 'Изображения должны быть в формате png, jpg или jpeg.' ) );
				}
			}
		}

		/** check image and video size */
		foreach ( $media_files as $k => $file ) {
			if ( strstr( $file['type'], 'video/' ) ) {
				if ( $file['size'] > 52428800 ) {
					wp_send_json_error( array( 'message' => __( 'Вес видео не должен превышать 50MB.', 'dao-consensus' ) ) );
				}
			} else {
				if ( $file['size'] > 3145728 ) {
					wp_send_json_error( array( 'message' => __( 'Вес изображения не должен превышать 3MB.', 'dao-consensus' ) ) + $file );
				}
			}
		}
	}

	$portfolio_id = wp_insert_post(
		array(
			'post_type'    => 'portfolio',
			'post_title'   => $title,
			'post_status'  => 'pending',
			'post_content' => $description,
			'author'       => $curr_user->ID,
		),
		true
	);

	if ( is_wp_error( $portfolio_id ) ) {
		error_log( $portfolio_id->get_error_message() );
		wp_send_json_error( array( 'message' => 'Во время добавления работы в портфолио произошла ошибка. Попробуйте ещё раз.' ) );
	}

	/* set categories */
	$categories = array_map( 'sanitize_text_field', explode( ',', $_POST['categories'][0] ) );
	wp_set_object_terms( $portfolio_id, $categories, 'portfolio_categories' );

	/**
	 * UPLOAD
	 */
	$wp_upload_dir = wp_upload_dir();

	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	/* COVER */
	if ( isset( $_FILES['cover']['name'] ) && ! empty( $_FILES['cover']['name'] ) ) {
		$cover_ext          = pathinfo( $cover_file['name'], PATHINFO_EXTENSION );
		$cover_file['name'] = "portfolio-item-cover-{$user_id}-{$portfolio_id}.{$cover_ext}";
	
		$upload_cover = wp_handle_upload( $cover_file, array( 'test_form' => false ) );
	
		/* check on error */
		if ( isset( $upload_cover['error'] ) && ! empty( $upload_cover['error'] ) ) {
			error_log( $upload_cover['error'] );
			wp_send_json_error( array( 'message' => __( 'Во время загрузки обложки произошла ошибка. Попробуйте ещё раз.', 'dao-consensus' ) ) );
		}
	
		$upload_cover_filename = basename( $upload_cover['file'] );
	
		if ( $upload_cover ) {
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . $upload_cover_filename,
				'post_mime_type' => $upload_cover['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $upload_cover_filename ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);
	
			$thumb_id    = wp_insert_attachment( $attachment, $upload_cover['file'] );
			$attach_data = wp_generate_attachment_metadata( $thumb_id, $upload_cover['file'] );
			wp_update_attachment_metadata( $thumb_id, $attach_data );
		}
	
		/* set post thumbnail */
		set_post_thumbnail( $portfolio_id, $thumb_id );
	}

	/* MEDIA FILES*/
	if ( isset( $_FILES['media_files'] ) && ! empty( $_FILES['media_files'] ) ) {
		$media_files = rearrange_files_arr( $_FILES['media_files'] );

		foreach ( $media_files as $k => $file ) {
			$media_ext    = pathinfo( $file['name'], PATHINFO_EXTENSION );
			$file['name'] = "portfolio-item-file-{$user_id}-{$portfolio_id}-{$k}.{$media_ext}";

			$upload_mediafile = wp_handle_upload( $file, array( 'test_form' => false ) );

			/* check on error */
			if ( isset( $upload_mediafile['error'] ) && ! empty( $upload_mediafile['error'] ) ) {
				error_log( $upload_mediafile['error'] );
				wp_send_json_error( array( 'message' => __( 'Во время загрузки медиа файлов произошла ошибка. Попробуйте ещё раз.', 'dao-consensus' ) ) );
			}

			$upload_mediafile_filename = basename( $upload_mediafile['file'] );

			if ( $upload_mediafile ) {
				$attachment = array(
					'guid'           => $wp_upload_dir['url'] . '/' . $upload_mediafile_filename,
					'post_mime_type' => $upload_mediafile['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', $upload_mediafile_filename ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				);
				$attach_id   = wp_insert_attachment( $attachment, $upload_mediafile['file'] );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_mediafile['file'] );
				wp_update_attachment_metadata( $thumb_id, $attach_data );	

				$attachments[ $attach_id ] = $upload_mediafile['file'];
			}
		}

		if ( ! empty( $attachments ) ) {
			update_post_meta( $portfolio_id, 'media_files', $attachments );
		}
	}

	wp_send_json_success();
}
add_action( 'wp_ajax_dao-edit-portfolio-item', 'dao_cons_edit_portfolio_item' );
function dao_cons_edit_portfolio_item() {
	check_ajax_referer( 'dao-consensus-edit-portfolio-item' );

	if (
		! isset( $_POST['item_id'] ) ||
		! isset( $_POST['title'] ) ||
		! isset( $_POST['description'] ) ||
		! isset( $_POST['categories'] )
	) {
		wp_send_json('', 403);
	}

	$item_id   = sanitize_text_field( $_POST['item_id'] );

	$portfolio_item = get_post( $item_id );
	$curr_user = wp_get_current_user();

	if ( $curr_user->ID != $portfolio_item->post_author ) {
		wp_send_json_error( array( 'message' => __( 'Вы не можете редактировать эту работу, поскольку не являетесь её автором.', 'dao-consensus' ) ) );
	}

	$title = sanitize_text_field( $_POST['title'] );
	if ( strlen( $title ) < 5 ) {
		wp_send_json_error( array( 'message' => 'Название работы не должно быть короче 5 символов.' ) );
	}
	if ( strlen( $title ) > 200 ) {
		wp_send_json_error( array( 'message' => 'Название работы не должно быть длинее 200 символов.' ) );
	}

	$description = wp_kses_post( $_POST['description'] );
	if ( strlen( $description ) < 50 ) {
		wp_send_json_error( array( 'message' => 'Описание работы не должно быть короче 50 символов.' ) );
	}
	if ( strlen( $description ) > 2000 ) {
		wp_send_json_error( array( 'message' => 'Описание работы не должно быть длинее 2000 символов.' ) );
	}

	/* validate cover */
	if ( isset( $_FILES['cover']['name'] ) && ! empty( $_FILES['cover']['name'] ) ) {
		$cover_file = $_FILES['cover'];

		$image_formats      = array( 'image/jpg', 'image/jpeg', 'image/png' );

		/* check cover format */
		if ( ! in_array( $cover_file['type'], $image_formats ) ) {
			wp_send_json_error( array( 'message' => 'Изображение обложки должно быть в формате jpg, jpeg или png.' ) );
		}
		/* check cover size */
		if ( $cover_file['size'] > 3145728 ) {
			wp_send_json_error( array( 'message' => __( 'Вес обложки не должен превышать 3MB.', 'dao-consensus' ) ) );
		}
	}

	/* validate media files */
	if ( isset( $_FILES['media_files'] ) && ! empty( $_FILES['media_files'] ) ) {
		$allowed_file_types = array( 'image/jpg', 'image/jpeg', 'image/png', 'video/mp4' );

		$media_files = rearrange_files_arr( $_FILES['media_files'] );

		if ( count( $media_files ) > 5 ) {
			wp_send_json_error( array( 'message' => 'Вы можете загрузить не больше 5-ти файлов.' ) );
		}

		/** check image and video filetype */
		foreach ( $media_files as $k => $file ) {
			if ( ! in_array( $file['type'], $allowed_file_types ) ) {
				/* if is video format */
				if ( strstr( $file['type'], 'video/' ) ) {
					wp_send_json_error( array( 'message' => 'Видео должно быть в формате mp4.' ) );
				} else {
					wp_send_json_error( array( 'message' => 'Изображения должны быть в формате png, jpg или jpeg.' ) );
				}
			}
		}

		/** check image and video size */
		foreach ( $media_files as $k => $file ) {
			if ( strstr( $file['type'], 'video/' ) ) {
				if ( $file['size'] > 52428800 ) {
					wp_send_json_error( array( 'message' => __( 'Вес видео не должен превышать 50MB.', 'dao-consensus' ) ) );
				}
			} else {
				if ( $file['size'] > 3145728 ) {
					wp_send_json_error( array( 'message' => __( 'Вес изображения не должен превышать 3MB.', 'dao-consensus' ) ) + $file );
				}
			}
		}
	}

	$portfolio_id = wp_update_post(
		array(
			'ID' => $portfolio_item->ID,
			'post_title'   => $title,
			'post_status'  => 'pending',
			'post_content' => $description,
		),
		true
	);

	if ( is_wp_error( $portfolio_id ) ) {
		error_log( $portfolio_id->get_error_message() );
		wp_send_json_error( array( 'message' => 'Во время добавления работы в портфолио произошла ошибка. Попробуйте ещё раз.' ) );
	}

	/* set categories */
	$categories = array_map( 'sanitize_text_field', explode( ',', $_POST['categories'][0] ) );
	wp_set_object_terms( $portfolio_id, $categories, 'portfolio_categories' );

	/* delete old attachments */
	if ( ! empty( $_POST['remove_media_files'] ) ) {
		$remove_attachs = array_map( 'sanitize_text_field', explode( ',', $_POST['remove_media_files'] ) );
		if ( ! empty( $remove_attachs ) ) {
			$media_files = get_post_meta( $portfolio_id, 'media_files', true );
			foreach ( $remove_attachs as $id ) {
				// if ( is_attachment( $id ) ) {
				// 	wp_delete_attachment( $id, true );
				// }
				unset( $media_files[$id] );
			}
			update_post_meta( $portfolio_id, 'media_files', $media_files );
		}
	}

	/**
	 * UPLOAD
	 */
	$user_id = get_current_user_id();
	$wp_upload_dir = wp_upload_dir();

	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	/* COVER */
	if ( isset( $_FILES['cover'] ) && ! empty( $_FILES['cover'] ) ) {
		$cover_file = $_FILES['cover'];
		
		$cover_ext          = pathinfo( $cover_file['name'], PATHINFO_EXTENSION );
		$cover_file['name'] = "portfolio-item-cover-{$user_id}-{$portfolio_id}.{$cover_ext}";
	
		$upload_cover = wp_handle_upload( $cover_file, array( 'test_form' => false ) );
	
		/* check on error */
		if ( isset( $upload_cover['error'] ) && ! empty( $upload_cover['error'] ) ) {
			error_log( $upload_cover['error'] );
			wp_send_json_error( array( 'message' => __( 'Во время загрузки обложки произошла ошибка. Попробуйте ещё раз.', 'dao-consensus' ) ) );
		}
	
		$upload_cover_filename = basename( $upload_cover['file'] );
	
		if ( $upload_cover ) {
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . $upload_cover_filename,
				'post_mime_type' => $upload_cover['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', $upload_cover_filename ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);
	
			$thumb_id    = wp_insert_attachment( $attachment, $upload_cover['file'] );
			$attach_data = wp_generate_attachment_metadata( $thumb_id, $upload_cover['file'] );
			wp_update_attachment_metadata( $thumb_id, $attach_data );
		}
	
		/* remove old post thumbnail */
		wp_delete_attachment( get_post_thumbnail_id( $portfolio_id ), true );

		/* set new post thumbnail */
		set_post_thumbnail( $portfolio_id, $thumb_id );
	}

	/* MEDIA FILES*/
	if ( isset( $_FILES['media_files'] ) && ! empty( $_FILES['media_files'] ) ) {
		$attachments = array();

		$def_media_files = get_post_meta( $portfolio_id, 'media_files', true );

		if ( ! empty( $def_media_files ) ) {
			$attachments = $def_media_files;
			unset( $def_media_files );
		}

		$media_files = rearrange_files_arr( $_FILES['media_files'] );

		foreach ( $media_files as $k => $file ) {
			$media_ext    = pathinfo( $file['name'], PATHINFO_EXTENSION );
			$file['name'] = "portfolio-item-file-{$user_id}-{$portfolio_id}-{$k}.{$media_ext}";

			$upload_mediafile = wp_handle_upload( $file, array( 'test_form' => false ) );

			/* check on error */
			if ( isset( $upload_mediafile['error'] ) && ! empty( $upload_mediafile['error'] ) ) {
				error_log( $upload_mediafile['error'] );
				wp_send_json_error( array( 'message' => __( 'Во время загрузки медиа файлов произошла ошибка. Попробуйте ещё раз.', 'dao-consensus' ) ) );
			}

			$upload_mediafile_filename = basename( $upload_mediafile['file'] );

			if ( $upload_mediafile ) {
				$attachment = array(
					'guid'           => $wp_upload_dir['url'] . '/' . $upload_mediafile_filename,
					'post_mime_type' => $upload_mediafile['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', $upload_mediafile_filename ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				);
				$attach_id   = wp_insert_attachment( $attachment, $upload_mediafile['file'] );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $upload_mediafile['file'] );
				wp_update_attachment_metadata( $thumb_id, $attach_data );	

				$attachments[ $attach_id ] = $upload_mediafile['file'];
			}
		}

		if ( ! empty( $attachments ) ) {
			update_post_meta( $portfolio_id, 'media_files', $attachments );
		}
	}

	wp_send_json_success();
}

add_action( 'wp_ajax_dao-delete-portfolio-item', 'dao_cons_delete_portfolio_item' );
function dao_cons_delete_portfolio_item() {
	check_ajax_referer('dao-consensus-delete-portfolio-item');

	if (
		! intval( $_POST['item_id'] )
	) {
		die('success');
	}

	$id = sanitize_text_field( $_POST['item_id'] );

	// check if such porfolio item exists
	if ( ! get_post_status( $id ) ) {
		wp_send_json_error( array( 'message' => __('Работу с таким ИД не зарегистрировано.', 'dao-consensus') ) );
	}

	// check whether initiator is true author of work
	$curr_user = wp_get_current_user();
	$author = get_post_field( 'post_author', $id );
	if ( $curr_user->ID != $author ) {
		wp_send_json_error( array( 'message' => __('Вы не можете удалить данную работу, поскольку не являетесь её автором.', 'dao-consensus') ) );
	}

	$res = wp_delete_post( $id, true );

	// check if result is successful
	if ( ! is_object( $res ) ) {
		wp_send_json_error( array( 'message' => __('Во время удаления произошла ошибка. Попробуйте ещё раз.', 'dao-consensus') ) );
	} else {
		wp_send_json_success( array( 'id' => $id ) );
	}

}

/**
 * ------------------------- MEETINGS -------------------------- /
 */

add_action( 'wp_ajax_dao-get-profile-meetings', 'dao_cons_get_profile_meetings' );
function dao_cons_get_profile_meetings() {
	check_ajax_referer( 'dao-consensus-get-profile-meetings' );

	$args = map_deep( $_POST['query_vars'], 'sanitize_text_field' );

	if ( isset( $_POST['filters'] ) && ! empty( $_POST['filters'] ) ) {
		parse_str( $_POST['filters'], $filters );
		$filters = array_map( 'sanitize_text_field', $filters );

		if ( isset( $filters['search'] ) && ! empty( $filters['search'] ) ) {
			$args['s'] = $filters['search'];
		}

		if ( isset( $filters['status'] ) && ! empty( $filters['status'] ) ) {
			$args['post_status'] = $filters['status'];
		}

		if ( isset( $filters['format'] ) && ! empty( $filters['format'] ) ) {
			$args['tax_query']   = array();
			$args['tax_query'][] = array(
				array(
					'taxonomy' => 'formats',
					'field'    => 'slug',
					'terms'    => $filters['format'],
				),
			);
		}

		if ( isset( $filters['user'] ) && ! empty( $filters['user'] ) ) {
			$args['meta_query'][] = array(
				'key'   => 'invited_id',
				'value' => $filters['user'],
			);
		}
	}

	$the_meetings = new WP_Query( $args );

	$vars = array();

	$vars['found_posts']   = $the_meetings->found_posts;
	$vars['max_num_pages'] = $the_meetings->max_num_pages;

	ob_start();

	if ( $the_meetings->have_posts() ) :
		while ( $the_meetings->have_posts() ) :
			$the_meetings->the_post();

			get_template_part( 'template-parts/profile-meeting-card', 'sent' );
		endwhile;
	else :
		get_template_part( 'template-parts/content-table', 'none' );
	endif;

	$html = ob_get_clean();

	wp_send_json_success( array( 'meetings' => $html ) + $vars );
}

add_action( 'wp_ajax_dao-get-profile-meetings-received', 'dao_cons_get_profile_meetings_received' );
function dao_cons_get_profile_meetings_received() {
	check_ajax_referer( 'dao-consensus-get-profile-meetings' );

	$args = map_deep( $_POST['query_vars'], 'sanitize_text_field' );

	if ( isset( $_POST['filters'] ) && ! empty( $_POST['filters'] ) ) {
		parse_str( $_POST['filters'], $filters );
		$filters = array_map( 'sanitize_text_field', $filters );

		if ( isset( $filters['search'] ) && ! empty( $filters['search'] ) ) {
			$args['s'] = $filters['search'];
		}

		if ( isset( $filters['status'] ) && ! empty( $filters['status'] ) ) {
			$args['post_status'] = $filters['status'];
		}

		if ( isset( $filters['format'] ) && ! empty( $filters['format'] ) ) {
			$args['tax_query']   = array();
			$args['tax_query'][] = array(
				array(
					'taxonomy' => 'formats',
					'field'    => 'slug',
					'terms'    => $filters['format'],
				),
			);
		}

		if ( isset( $filters['user'] ) && ! empty( $filters['user'] ) ) {
			$args['post_author'] = $filters['user'];
		}
	}

	$the_meetings = new WP_Query( $args );

	$vars = array();

	$vars['found_posts']   = $the_meetings->found_posts;
	$vars['max_num_pages'] = $the_meetings->max_num_pages;

	ob_start();

	if ( $the_meetings->have_posts() ) :
		while ( $the_meetings->have_posts() ) :
			$the_meetings->the_post();

			get_template_part( 'template-parts/profile-meeting-card', 'received' );
		endwhile;
	else :
		get_template_part( 'template-parts/content-table', 'none' );
	endif;

	$html = ob_get_clean();

	wp_send_json_success( array( 'meetings' => $html ) + $vars );
}

add_action( 'wp_ajax_dao-get-profile-meetings-sent', 'dao_cons_get_profile_meetings_sent' );
function dao_cons_get_profile_meetings_sent() {
	check_ajax_referer( 'dao-consensus-get-profile-meetings' );

	$args = map_deep( $_POST['query_vars'], 'sanitize_text_field' );

	if ( isset( $_POST['filters'] ) && ! empty( $_POST['filters'] ) ) {
		parse_str( $_POST['filters'], $filters );
		$filters = array_map( 'sanitize_text_field', $filters );

		if ( isset( $filters['search'] ) && ! empty( $filters['search'] ) ) {
			$args['s'] = $filters['search'];
		}

		if ( isset( $filters['status'] ) && ! empty( $filters['status'] ) ) {
			$args['post_status'] = $filters['status'];
		}

		if ( isset( $filters['format'] ) && ! empty( $filters['format'] ) ) {
			$args['tax_query']   = array();
			$args['tax_query'][] = array(
				array(
					'taxonomy' => 'formats',
					'field'    => 'slug',
					'terms'    => $filters['format'],
				),
			);
		}

		if ( isset( $filters['user'] ) && ! empty( $filters['user'] ) ) {
			$args['post_author'] = $filters['user'];
		}
	}

	$the_meetings = new WP_Query( $args );

	$vars = array();

	$vars['found_posts']   = $the_meetings->found_posts;
	$vars['max_num_pages'] = $the_meetings->max_num_pages;

	ob_start();

	if ( $the_meetings->have_posts() ) :
		while ( $the_meetings->have_posts() ) :
			$the_meetings->the_post();

			get_template_part( 'template-parts/profile-meeting-card', 'sent' );
		endwhile;
	else :
		get_template_part( 'template-parts/content-table', 'none' );
	endif;

	$html = ob_get_clean();

	wp_send_json_success( array( 'meetings' => $html ) + $vars );
}

add_action( 'wp_ajax_dao-schedule-meeting', 'dao_cons_schedule_meeting' );
function dao_cons_schedule_meeting() {
	check_ajax_referer( 'dao-consensus-schedule-meeting' );

	if (
		! isset( $_POST['invitor'] ) ||
		! isset( $_POST['invited'] ) ||
		! isset( $_POST['invited_id'] ) ||
		! isset( $_POST['card_id'] ) ||
		! isset( $_POST['title'] ) ||
		! isset( $_POST['venue'] ) ||
		! isset( $_POST['datetime'] ) ||
		! isset( $_POST['format'] ) ||
		! isset( $_POST['description'] )
	) {
		die();
	}

	$invitor_name = sanitize_text_field( $_POST['invitor'] );
	if ( strlen( $invitor_name ) > 50 ) {
		wp_send_json_error( array( 'message' => 'Имя приглашающего не должно быть длинее 50 символов.' ) );
	}

	$invited_name = sanitize_text_field( $_POST['invited'] );
	if ( strlen( $invitor_name ) > 50 ) {
		wp_send_json_error( array( 'message' => 'Имя приглашаемого не должно быть длинее 50 символов.' ) );
	}

	$title = sanitize_text_field( $_POST['title'] );
	if ( strlen( $title ) > 100 ) {
		wp_send_json_error( array( 'message' => 'Заголовок встречи не должен быть длинее 100 символов.' ) );
	}

	$datetime = sanitize_text_field( $_POST['datetime'] );
	if ( ! validateDate( $datetime, 'd.m.Y H:i' ) ) {
		wp_send_json_error( array( 'message' => 'Введите дату и время в таком формате DD.MM.YYYY HH:MM.' ) );
	}

	$format  = sanitize_text_field( $_POST['format'] );
	$formats = (array) get_terms(
		array(
			'taxonomy'   => 'formats',
			'hide_empty' => false,
			'fields'     => 'slugs',
		)
	);
	if ( ! in_array( $format, $formats ) ) {
		wp_send_json_error( array( 'message' => 'Такого формата встречи не зарегистировано.' ) );
	}

	$venue = sanitize_text_field( $_POST['venue'] );
	if ( strlen( $venue ) > 100 ) {
		wp_send_json_error( array( 'message' => 'Указание места встречи не должно быть длинее 100 символов.' ) );
	}

	$description = sanitize_textarea_field( $_POST['description'] );
	if ( strlen( $description ) > 1000 ) {
		wp_send_json_error( array( 'message' => 'Описание встречи не должно быть длинее 1000.' ) );
	}

	$card_id = sanitize_text_field( $_POST['card_id'] );
	if ( ! get_post_status( $card_id ) ) {
		wp_send_json_error( array( 'message' => 'Карточки с таким ID не зарегистрировано.' ) );
	}

	$invited_id = sanitize_text_field( $_POST['invited_id'] );

	$curr_user = wp_get_current_user();

	$post_id = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_author'  => $curr_user->ID,
			'post_type'    => 'meetings',
			'post_status'  => 'waiting',
			'post_content' => $description,
			'meta_input'   => array(
				'invitor_name' => $invitor_name,
				'invited_name' => $invited_name,
				'card_id'      => $card_id,
				'venue'        => $venue,
				'invited_id'   => $invited_id,
				'datetime'     => strtotime( $datetime ),
			),
		)
	);

	/* check on error */
	if ( is_wp_error( $post_id ) ) {
		error_log( $post_id->get_error_message() );
		wp_send_json_error( array( 'message' => 'Во время создания встречи произошла ошибка. Попробуйте ещё раз.' ) );
	}

	/* set meeting format */
	wp_set_object_terms( $post_id, sanitize_text_field( $_POST['format'] ), 'formats' );

	/* notify invited user about invitation */
	do_action( 'dao_create_meeting_notification', $post_id, 'invitation' );

	wp_send_json_success();
}

add_action( 'wp_ajax_dao-accept-meeting', 'dao_cons_accept_meeting' );
function dao_cons_accept_meeting() {
	check_ajax_referer( 'dao-consensus-accept-meeting' );

	$id = sanitize_text_field( $_POST['meeting_id'] );

	/* check if current user is really invited person */
	$meeting = get_post( $id );
	$curr_user = wp_get_current_user();

    if ( $meeting->invited_id != $curr_user->ID ) {
        wp_send_json_error( array( 'message' => __( 'Вы не можете принять приглашение на встречу, которое адресуется не вам.', 'dao-consensus' ) ) );
    }

	$old_status = get_post_status( $meeting->ID );

	$meeting_id = wp_update_post(
		array(
			'ID'          => $id,
			'post_status' => 'accepted',
		),
		true
	);

	/* check on error */
	if ( is_wp_error( $meeting_id ) ) {
		$errors = $meeting_id->get_error_messages();
		foreach ( $errors as $error ) {
			error_log( $error );
		}
		wp_send_json_error();
	}

	do_action('dao_create_meeting_notification', $meeting_id, 'acceptance');

	wp_send_json_success(
		array(
			'id'     => $id,
			'status' => DAO_CONSENSUS::meeting_statuses[ get_post_status( $id ) ],
		)
	);

}

add_action( 'wp_ajax_dao-reject-meeting', 'dao_cons_reject_meeting' );
function dao_cons_reject_meeting() {
	check_ajax_referer( 'dao-consensus-reject-meeting' );

	$id = sanitize_text_field( $_POST['meeting_id'] );

	/* check if current user is really invited person */
	$meeting = get_post( $id );
	$curr_user = wp_get_current_user();

    if ( $meeting->invited_id != $curr_user->ID ) {
        wp_send_json_error( array( 'message' => __( 'Вы не можете отклонить приглашение на встречу, которое адресуется не вам.', 'dao-consensus' ) ) );
    }

	$meeting_id = wp_update_post(array(
        'ID' => $id,
        'post_status' => 'declined'
    ), true);

	if (is_wp_error($meeting_id)) {
        $errors = $meeting_id->get_error_messages();
        foreach ($errors as $error) {
            error_log($error);
        }
        wp_send_json_error();
	}

    do_action('dao_create_meeting_notification', $meeting_id, 'rejection');

	wp_send_json_success(
		array(
			'id'     => $id,
			'status' => DAO_CONSENSUS::meeting_statuses[ get_post_status( $id ) ],
		)
	);
}

add_action( 'wp_ajax_dao-cancel-meeting', 'dao_cons_cancel_meeting' );
function dao_cons_cancel_meeting() {
	check_ajax_referer( 'dao-consensus-cancel-meeting' );

	if (
		! isset( $_POST['meeting_id'] )
	) {
		die();
	}

    $id = sanitize_text_field( $_POST['meeting_id'] );

    /* check if current user is invitor */
	$meeting = get_post( $id );
	$curr_user = wp_get_current_user();

    if ( $meeting->post_author != $curr_user->ID ) {
        wp_send_json_error( array( 'message' => __( 'Вы не можете отменить встречу, поскольку не являетесь её инициатором.', 'dao-consensus' ) ) );
    }

    $meeting_id = wp_update_post(
		array(
			'ID'          => $id,
			'post_status' => 'canceled',
		),
		true
	);

	if ( is_wp_error( $meeting_id ) ) {
		$errors = $meeting_id->get_error_messages();
		foreach ( $errors as $error ) {
			error_log( $error );
		}
		wp_send_json_error();
	}

	do_action('dao_create_meeting_notification', $meeting_id, 'cancellation');

	wp_send_json_success(
		array(
			'id'     => $id,
			'status' => DAO_CONSENSUS::meeting_statuses[ get_post_status( $id ) ],
		)
	);

	wp_send_json_success();
}

// add_action( 'wp_ajax_dao-delete-meeting', 'dao_cons_delete_meeting' );
function dao_cons_delete_meeting() {
	check_ajax_referer( 'dao-consensus-delete-meeting' );

	$id = sanitize_text_field( $_POST['meeting_id'] );

	$curr_user_id   = get_current_user_id();
	$meeting_author = get_post_field( 'post_author', $id );

	if ( $curr_user_id != $meeting_author ) {
		wp_send_json_error( array( 'message' => __( 'Вы не можете удалить данную встречу, поскольку не являетесь её инициатором.', 'dao-consensus' ) ) );
	}

	wp_trash_post( $id );

	wp_send_json_success( array( 'id' => $id ) );
}

add_action( 'wp_ajax_dao-reschedule-meeting', 'dao_cons_reschedule_meeting' );
function dao_cons_reschedule_meeting() {
	check_ajax_referer( 'dao-consensus-reschedule-meeting' );

	if (
		! isset( $_POST['meeting_id'] ) ||
		! isset( $_POST['datetime'] )
	) {
		die();
	}

	$meeting_id = sanitize_text_field( $_POST['meeting_id'] );
	$datetime = sanitize_text_field( $_POST['datetime'] );

	if ( ! validateDate( $datetime, 'd.m.Y H:i' ) ) {
		wp_send_json_error( array( 'message' => 'Введите дату и время в таком формате DD.MM.YYYY HH:MM.' ) );
	}

	$meeting = get_post( $meeting_id );

	$curr_user = wp_get_current_user();

	if ( ( $curr_user->ID != $meeting->post_author ) && ( $curr_user->ID != $meeting->invited_id ) ) {
		wp_send_json_error( array( 'message' => __('Вы не можете перенести встречу, поскольку не являетесь ни пригласителем, ни приглашённым.', 'dao-consensus') ) );
	}

	$old_datetime = $meeting->datetime;

	$id = wp_update_post(array(
		'ID' => $meeting->ID,
		'post_status' => 'reschedule',
		'meta_input' => array(
			'datetime' => strtotime( $datetime ),
			'old_datetime' => $old_datetime,
			'reschedule_initiator' => $curr_user->ID,
		)
	), true);

	if ( is_wp_error($id) ) {
		$errors = $meeting_id->get_error_messages();
		foreach ( $errors as $error ) {
			error_log( $error );
		}
		wp_send_json_error();
	}

	do_action('dao_create_meeting_notification', $id, 'reschedule', $curr_user->ID);

	wp_send_json_success(array(
		'id'     => $id,
		'status' => DAO_CONSENSUS::meeting_statuses[ get_post_status( $id ) ],
	));
}

add_action( 'wp_ajax_dao-accept-reschedule-meeting', 'dao_cons_accept_reschedule_meeting' );
function dao_cons_accept_reschedule_meeting() {
	check_ajax_referer( 'dao-consensus-accept-reschedule-meeting' );

	$id = sanitize_text_field( $_POST['meeting_id'] );

	/* check if current user is really invited person */
	$meeting = get_post( $id );
	$curr_user = wp_get_current_user();

    if ( $meeting->invited_id != $curr_user->ID && $meeting->post_author != $curr_user->ID ) {
        wp_send_json_error( array( 'message' => __( 'Вы не можете принять перенос встречи, поскольку не являетесь ни приглашённым, ни пригласителем.', 'dao-consensus' ) ) );
    }

	$meeting_id = wp_update_post(
		array(
			'ID'          => $id,
			'post_status' => 'accepted',
		),
		true
	);

	/* check on error */
	if ( is_wp_error( $meeting_id ) ) {
		$errors = $meeting_id->get_error_messages();
		foreach ( $errors as $error ) {
			error_log( $error );
		}
		wp_send_json_error();
	}

	// clear used meta data for rescheduling
	update_post_meta( $meeting_id, 'old_datetime', '' );
	update_post_meta( $meeting_id, 'reschedule_initiator', '' );

	// do_action('dao_create_meeting_notification', $meeting_id, 'reschedule-acceptance', $curr_user->ID);

	wp_send_json_success(
		array(
			'id'     => $id,
			'status' => DAO_CONSENSUS::meeting_statuses[ get_post_status( $meeting_id ) ],
		)
	);
}

add_action( 'wp_ajax_dao-reject-reschedule-meeting', 'dao_cons_reject_reschedule_meeting' );
function dao_cons_reject_reschedule_meeting() {
	check_ajax_referer( 'dao-consensus-reject-reschedule-meeting' );

	$id = sanitize_text_field( $_POST['meeting_id'] );

	/* check if current user is really invited person */
	$meeting = get_post( $id );
	$curr_user = wp_get_current_user();

    if ( $meeting->invited_id != $curr_user->ID && $meeting->post_author != $curr_user->ID ) {
        wp_send_json_error( array( 'message' => __( 'Вы не можете отклонить приглашение на встречу, которое адресуется не вам.', 'dao-consensus' ) ) );
    }

	$old_datetime = $meeting->old_datetime;

	$meeting_id = wp_update_post(array(
        'ID' => $id,
        'post_status' => 'accepted',
		'meta_input' => array(
			'datetime' => $old_datetime
		)
    ), true);

	if (is_wp_error($meeting_id)) {
        $errors = $meeting_id->get_error_messages();
        foreach ($errors as $error) {
            error_log($error);
        }
        wp_send_json_error();
	}

	// clear used meta data for rescheduling
	update_post_meta( $meeting_id, 'old_datetime', '' );
	update_post_meta( $meeting_id, 'reschedule_initiator', '' );

    // do_action('dao_create_meeting_notification', $meeting_id, 'reschedule-rejection', $curr_user->ID);

	wp_send_json_success(
		array(
			'id'     => $id,
			'status' => DAO_CONSENSUS::meeting_statuses[ get_post_status( $id ) ],
		)
	);
}

// AFTER MEETING RESULT
add_action( 'wp_ajax_dao-after-meeting-result', 'dao_cons_after_meeting_result' );
function dao_cons_after_meeting_result() {
	check_ajax_referer( 'dao-consensus-after-meeting-result' );

	if ( 
		! isset( $_POST['meeting_id'] ) ||
		! isset( $_POST['notification_id'] ) ||
		! isset( $_POST['result'] )
	) {
		wp_send_json('', 403);
	}
	
	$notification_id = sanitize_text_field( $_POST['notification_id'] );
	$meeting_id = sanitize_text_field( $_POST['meeting_id'] );
	$result = sanitize_text_field( $_POST['result'] );

	$meeting = get_post( $meeting_id );
	$card = get_post( $meeting->card_id );

	$curr_user = wp_get_current_user();

	global $wpdb;

	if ( $card->post_type === 'offers' ) {
		if ( $curr_user->ID != $meeting->post_author ) {
			wp_send_json_error( array( 'message' => __( 'Вы не можете подтвердить результат встречи, поскольку не являетесь её инициатором.', 'dao-consensus' ) ) );
		}
	} else {
		if ( $curr_user->ID != $card->post_author ) {
			wp_send_json_error( array( 'message' => __( 'Вы не можете подтвердить результат встречи, поскольку не являетесь автором спроса.', 'dao-consensus' ) ) );
		}
	}

	$notification_user = $wpdb->get_var(
		$wpdb->prepare("SELECT user_id FROM {$wpdb->prefix}user_notifications WHERE ID = %d", $notification_id)
	);

	if ( $notification_user != $curr_user->ID ) {
		wp_send_json( __( 'Это уведомление адресовано не вам, поэтому вы не можете совершить действия, которые в нём содержаться.', 'dao-consensus' ), 403 );
	}

	$data = array();

	if ( $result === 'success' ) {

		$data['result'] = 'success';

		if ( $card->post_type === 'offers' ) {
			$data['type'] = 'offer';
			$data['title'] = $card->post_title;
			$data['performer'] = dao_get_user_display_name( $card->post_author );

			/* create transaction */
			$transaction_id = wp_insert_post( array(
				'post_title' => $card->post_title,
				'post_type' => 'transactions',
				'post_status' => 'in-process',
				'meta_input' => array(
					'initiator' => $meeting->post_author,
					'deal_person' => $card->post_author,
					'card_id' => $card->ID,
					'total_price' => $card->total_price,
					'cryptocurrency' => $card->cryptocurrency,
				)
			), true);

			if ( is_wp_error( $transaction_id ) ) {
				$errors = $transaction_id->get_error_messages();
				foreach ( $errors as $error ) {
					error_log( $error );
				}
				wp_send_json_error( array( 'message' => __('Во время создания сделки возникла ошибка. Попробуйте ещё раз.', 'dao-consensus') ) );
			}

			$customers = get_post_meta( $card->ID, 'customers', true );

			if ( is_array( $customers ) ) {
				$customers[ $meeting->post_author ] = $transaction_id;
			} else {
				$customers = array( $meeting->post_author => $transaction_id );
			}

			/* add new customer to offer card */
			$card_id = wp_update_post( array(
				'ID' => $card->ID,
				'meta_input' => array(
					'customers' => $customers
				)
			), true );
		
			if ( is_wp_error( $card_id ) ) {
				$errors = $card_id->get_error_messages();
				foreach ( $errors as $error ) {
					error_log( $error );
				}
				wp_send_json_error(  array( 'message' => __('Во время добавления вас в заказчики предложения возникла ошибка. Попробуйте ещё раз.', 'dao-consensus') ) );
			}

			/* notify user about card status change */
			do_action('dao_create_meeting_notification', $meeting->ID, 'offer-in-process' );
		}

		if ( $card->post_type === 'demands' ) {
			$data['type'] = 'demand';
			$data['title'] = $card->post_title;
			$data['performer'] = dao_get_user_display_name( $meeting->post_author );

			/* create transaction */
			$transaction_id = wp_insert_post( array(
				'post_title' => $card->post_title,
				'post_type' => 'transactions',
				'post_status' => 'in-process',
				'meta_input' => array(
					'initiator'   => $meeting->post_author,
					'deal_person' => $card->post_author,
					'card_id'     => $card->ID,
					'total_price' => $card->total_price,
					'cryptocurrency' => $card->cryptocurrency
				)
			), true);

			if ( is_wp_error( $transaction_id ) ) {
				$errors = $transaction_id->get_error_messages();
				foreach ( $errors as $error ) {
					error_log( $error );
				}
				wp_send_json_error( array( 'message' => __('Во время создания сделки возникла ошибка. Попробуйте ещё раз.', 'dao-consensus') ) );
			}

			/* change demand status */
			$card_id = wp_update_post( array(
				'ID' => $meeting->card_id,
				'post_status' => 'in-process',
				'meta_input' => array(
					'performer'		 => $meeting->post_author,
					'transaction_id' => $transaction_id,
				)
			), true );

			if ( is_wp_error( $card_id ) ) {
				$errors = $card_id->get_error_messages();
				foreach ( $errors as $error ) {
					error_log( $error );
				}
				wp_send_json_error( array( 'message' => __('Во время изменения статуса спроса возникла ошибка. Попробуйте ещё раз.', 'dao-consensus') ) );
			}

			/* notify user about card status change */
			do_action('dao_create_meeting_notification', $meeting->ID, 'demand-in-process' );
		}

		/* delete notification */
		do_action('dao_delete_notification', $notification_id);

	} elseif ( $result === 'failure' ) {

		$data['result']    = 'failure';
		$data['type']      = ($card->post_type === 'offers') ? 'offer' : 'demand';
		$data['title']     = $card->post_title;
		$data['performer'] = dao_get_user_display_name( $card->post_author );

		do_action('dao_create_meeting_notification', $meeting->ID, 'did-not-agree' );
		/* delete notification */
		do_action('dao_delete_notification', $notification_id);

	} else {
		wp_send_json_error( array( 'message' => __( 'Результат встречи может быть либо success, либо failure.', 'dao-consensus' ) ) );
	}
 
	if ( ! empty( $data ) ) {
		wp_send_json_success( $data );
	} else {
		wp_send_json_success();
	}
}

/**
 * ----------------------------- CALENDAR ------------------------------ /
 */

add_action( 'wp_ajax_dao-get-meeting-info', 'dao_cons_get_meeting_info' );
function dao_cons_get_meeting_info() {
	check_ajax_referer( 'dao-consensus-get-meeting-info' );

	if ( ! isset( $_GET['id'] ) ) {
		die;
	}

	$meeting = get_post( sanitize_text_field( $_GET['id'] ) );

	$user_id = get_current_user_id();
	// check if curr user is invitor or invited
	if ( $user_id != $meeting->post_author && $user_id != $meeting->invited_id ) {
		wp_send_json_error( array( 'message' => __( 'Вы не можете просмотреть инфо об этой встрече, поскольку не являетесь ни пригласителем, ни приглашенным.', 'dao-consensus' ) ) );
	}

	$datetime = new DateTime( '@' . get_post_meta( $meeting->ID, 'datetime', true ) );
	$format   = wp_get_post_terms( $meeting->ID, 'formats', array( 'fields' => 'names' ) );

	$data            = array();
	$data['id']   = $meeting->ID;
	$data['title']   = $meeting->post_title;
	$data['desc']    = apply_filters( 'the_content', $meeting->post_content );
	$data['date']    = eng_months_to_ru( $datetime->format( 'j F' ) );
	$data['time']    = $datetime->format( 'H:i' );
	$data['invited'] = $meeting->invited_name;
	$data['venue']   = $meeting->venue;
	$data['format']  = $format[0];
	$data['status']  = DAO_CONSENSUS::meeting_statuses[ $meeting->post_status ];

	/* check whether curr user is the author of meeting or not */
	$data['author'] = ( $user_id == $meeting->post_author ) ? true : false;

	wp_send_json_success( $data );
}

add_action( 'wp_ajax_dao-get-meetings-for-month', 'dao_cons_get_meetings_month' );
function dao_cons_get_meetings_month() {
	check_ajax_referer( 'dao-consensus-get-meetings-for-month' );

	if (
		! isset( $_GET['month'] ) ||
		 ! isset( $_GET['year'] )
	) {
		die;
	}

	if ( ! intval( $_GET['month'] ) || ! intval( $_GET['year'] ) ) {
		die();
	}

	$curr_user = wp_get_current_user();

	$month_num = sanitize_text_field( $_GET['month'] );
	$year      = sanitize_text_field( $_GET['year'] );

	$month = strtotime( "{$year}-{$month_num}-01" );

	/* first and last day of month */
	$first_day = strtotime( date( 'Y-m-01', $month ) );
	$last_day  = strtotime( date( 'Y-m-t', $month ) );

	$args1 = array(
		'post_type'   => 'meetings',
		'post_status' => 'accepted',
		'author'      => $curr_user->ID,
		'fields'      => 'ids',
	);
	$args2 = array(
		'post_type'   => 'meetings',
		'post_status' => 'accepted',
		'fields'      => 'ids',
		'meta_key'    => 'invited_id',
		'meta_value'  => $curr_user->ID,
	);

	$my_inv       = new WP_Query( $args1 );
	$recieved_inv = new WP_Query( $args2 );

	$posts = array_merge( $my_inv->posts, $recieved_inv->posts );

	$the_meetings = new WP_Query(
		array(
			'post_type'      => 'meetings',
            'post_status'    => 'accepted',
			'post__in'       => $posts,
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'orderby'        => array( 'datetime' => 'ASC' ),
			'meta_query'     => array(
				'datetime' => array(
					'key'     => 'datetime',
					'value'   => array( $first_day, $last_day ),
					'compare' => 'BETWEEN',
				),
			),
		)
	);

	$month_meetings = array();

	if ( $the_meetings->have_posts() ) :
		while ( $the_meetings->have_posts() ) :
			$the_meetings->the_post();

			$datetime = (int) get_post_meta( get_the_ID(), 'datetime', true );
			$day      = date( 'j', $datetime );
			$id       = get_the_ID();

			$month_meetings[ $day ][ $id ]['title']        = get_the_title();
			$month_meetings[ $day ][ $id ]['status']       = DAO_CONSENSUS::meeting_statuses[ get_post_status() ];
			$month_meetings[ $day ][ $id ]['date']         = eng_months_to_ru( date( 'j F', $datetime ) );
			$month_meetings[ $day ][ $id ]['time']         = eng_months_to_ru( date( 'H:i', $datetime ) );
			$month_meetings[ $day ][ $id ]['invited_user'] = get_post_meta( get_the_ID(), 'invited_name', true );
			$month_meetings[ $day ][ $id ]['venue']        = get_post_meta( get_the_ID(), 'venue', true );
			$month_meetings[ $day ][ $id ]['description']  = apply_filters( 'the_content', get_the_content() );
		endwhile;
	endif;

	wp_reset_postdata();

	wp_send_json_success( array( 'meetings' => $month_meetings ) );
}

/**
 * ------------------------ SUBMIT TESTIMONIAL -------------------------- /
 */

add_action('wp_ajax_dao-submit-testimonial', 'dao_cons_submit_testimonial');
function dao_cons_submit_testimonial () {
	check_ajax_referer('dao-consensus-submit-testimonial');

	if ( 
		! isset( $_POST['quality'] ) || 
		! isset( $_POST['professionality'] ) ||
		! isset( $_POST['cost'] ) ||
		! isset( $_POST['sociability'] ) ||
		! isset( $_POST['deadline'] ) ||
		! isset( $_POST['body'] ) ||
		! isset( $_POST['transaction_id'] )
	) {
		wp_send_json( 'Wrong input values', 403 );
	}

	global $wpdb;

	$curr_user = wp_get_current_user();

	/* check if testimonial is already submitted */
	$res = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}user_testimonials WHERE author_id = %d AND transaction_id = %d", $curr_user->ID, $_POST['transaction_id'] ) );
	if ( ! empty( $res ) ) {
		wp_send_json_error( array('message' => __('Вы уже отправили отзыв.', 'dao-consensus')) );
	}

	$transaction = get_post( $_POST['transaction_id'] );

	$initiator = $transaction->initiator;
	$card_author = get_post_field( 'post_author', $transaction->card_id, true );

	$receiver_id = ( $curr_user->ID == $initiator ) ? $card_author : $initiator;

	$quality = intval( $_POST['quality'] );
	$professionality = intval( $_POST['professionality'] );
	$cost = intval( $_POST['cost'] );
	$sociability = intval( $_POST['sociability'] );
	$deadline = intval( $_POST['deadline'] );

	$rating = ( $quality + $professionality + $cost + $sociability + $deadline ) / 5;

	$data = array(
		'author_id' => $curr_user->ID,
		'receiver_id' => $receiver_id,
		'transaction_id' => sanitize_text_field( $_POST['transaction_id'] ),
		'message' => sanitize_textarea_field( $_POST['body'] ),
		'quality' => $quality,
		'professionality' => $professionality,
		'cost' => $cost,
		'sociability' => $sociability,
		'deadline' => $deadline,
		'rating' => $rating,
		'created_at' => current_time( 'mysql' )
	);

	$testimonial_id = $wpdb->insert( "{$wpdb->prefix}user_testimonials", $data );

	if ($curr_user->ID == $initiator) {
		update_post_meta( $transaction->ID, 'initiator_testimonial', $testimonial_id );
	} else {
		update_post_meta( $transaction->ID, 'deal_person_testimonial', $testimonial_id );
	}

	wp_send_json_success();
}

/**
 * ------------------------ PROFILE TESTIMONIALS -------------------------- /
 */

add_action( 'wp_ajax_dao-get-profile-testimonials', 'dao_cons_get_profile_testimonials' );
function dao_cons_get_profile_testimonials () {
	check_ajax_referer( 'dao-consensus-get-profile-testimonials' );

	if ( 
		! isset( $_POST['page'] ) ||
		! isset( $_POST['per_page'] )
	) {
		wp_send_json( 'page param is required', 403 );
	}

	$page = intval( $_POST['page'] );
	if ( ! $page ) {
		wp_send_json( '', 403 );
	}

	$per_page = intval( $_POST['per_page'] );
	if ( ! $per_page ) {
		wp_send_json( '', 403 );
	}

	$curr_user = wp_get_current_user();

	$result = dao_get_user_testimonials( $curr_user->ID, $per_page, $page );

	ob_start();

	foreach ( $result['testimonials'] as $testimonial ) {
		get_template_part( 'template-parts/testimonial-card', null, array( 'testimonial' => $testimonial ) );
	}

	$html = ob_get_clean();

	wp_send_json_success( array( 'testimonials' => $html ) );
}

/**
 * --------------------- (NOT) COMPLETE OFFER/DEMAND ----------------------- /
 */

add_action( 'wp_ajax_dao-complete-demand', 'dao_cons_complete_demand' );
function dao_cons_complete_demand() {
	check_ajax_referer( 'dao-consensus-complete-demand' );

	if ( ! intval( $_POST['id'] ) ) {
		wp_send_json( 'ID is required', 403 );
	}

	$id = sanitize_text_field( $_POST['id'] );

	$demand = get_post( $id );

	if ( get_current_user_id() != $demand->post_author ) {
		wp_send_json_error( array( 'message' => 'Вы не можете подтвердить выполнение спроса, поскольку не являетесь его автором.' ) );
	}

	wp_update_post( array(
		'ID' => $demand->ID,
		'post_status' => 'completed'
	) );

	$transaction_id = get_post_meta( $demand->ID, 'transaction_id', true );

	wp_update_post( array(
		'ID' => $transaction_id,
		'post_status' => 'completed'
	) );

	do_action('dao_create_transaction_notification', $transaction_id, 'completed');
	
	$data = array(
		'type' => 'demand', 
		'title' => $demand->post_title, 
		'performer' => dao_get_user_display_name( $demand->performer ),
		'transaction_id' => $transaction_id
	);

	wp_send_json_success( $data );
}

add_action( 'wp_ajax_dao-not-complete-demand', 'dao_cons_not_complete_demand' );
function dao_cons_not_complete_demand() {
	check_ajax_referer( 'dao-consensus-not-complete-demand' );

	if ( ! intval( $_POST['id'] ) ) {
		wp_send_json( 'ID is required', 403 );
	}

	$id = sanitize_text_field( $_POST['id'] );

	$demand = get_post( $id );

	if ( get_current_user_id() != $demand->post_author ) {
		wp_send_json_error( array( 'message' => 'Вы не можете подтвердить не выполнение спроса, поскольку не являетесь его автором.' ) );
	}

	wp_update_post(array(
		'ID' => $demand->ID,
		'post_status' => 'active'
	));

	$transaction_id = $demand->transaction_id;

	do_action('dao_create_transaction_notification', $transaction_id, 'not-completed');

	wp_send_json_success( array( 'id' => $transaction_id ) );
}

add_action( 'wp_ajax_dao-complete-offer', 'dao_cons_complete_offer' );
function dao_cons_complete_offer() {
	check_ajax_referer( 'dao-consensus-complete-offer' );

	if ( ! intval( $_POST['id'] ) ) {
		wp_send_json( 'ID is required', 403 );
	}

	$id = sanitize_text_field( $_POST['id'] );

	$curr_user = wp_get_current_user();

	$offer = get_post( $id );
	$customers = get_post_meta( $offer->ID, 'customers', true );
	$customers_keys = array_keys( get_post_meta( $offer->ID, 'customers', true ) );

	if ( ! in_array( $curr_user->ID, $customers_keys ) ) {
		wp_send_json_error( array( 'message' => 'Вы не можете подтвердить выполнение предложения, поскольку не являетесь его заказчиком.' ) );
	}

	// get transaction id
	$transaction_id = $customers[ $curr_user->ID ];

	// change transaction status from in-process to completed
	$res = wp_update_post( array(
		'ID' => $transaction_id,
		'post_status' => 'completed',
	), true);

	if (is_wp_error($res)) {
		$errors = $res->get_error_messages();
		foreach ( $errors as $error ) {
			error_log( $error );
		}
		wp_send_json_error( array( 'message' => 'Во время изменения статуса сделки произошла ошибка. Попробуйте ещё раз.' ) );
	}

	do_action('dao_create_transaction_notification', $transaction_id, 'completed');

	// remove curr user from customers
	unset( $customers[ $curr_user->ID ] );
	update_post_meta( $offer->ID, 'customers', $customers );

	$data = array(
		'type' => 'offer', 
		'title' => $offer->post_title, 
		'performer' => dao_get_user_display_name( $offer->post_author ),
		'transaction_id' => $transaction_id
	);

	wp_send_json_success( $data );
}

add_action( 'wp_ajax_dao-not-complete-offer', 'dao_cons_not_complete_offer' );
function dao_cons_not_complete_offer() {
	check_ajax_referer( 'dao-consensus-not-complete-offer' );

	if ( ! intval( $_POST['id'] ) ) {
		wp_send_json( 'ID is required', 403 );
	}

	$id = sanitize_text_field( $_POST['id'] );

	$curr_user = wp_get_current_user();

	$offer = get_post( $id );
	$customers = get_post_meta( $offer->ID, 'customers', true );
	$customers_keys = array_keys( get_post_meta( $offer->ID, 'customers', true ) );

	if ( ! in_array( $curr_user->ID, $customers_keys ) ) {
		wp_send_json_error( array( 'message' => 'Вы не можете подтвердить не выполнение предложения, поскольку не являетесь его заказчиком.' ) );
	}

	// get transaction id
	$transaction_id = $customers[ $curr_user->ID ];

	do_action('dao_create_transaction_notification', $transaction_id, 'not-completed');

	// remove curr user from customers
	unset( $customers[ $curr_user->ID ] );
	update_post_meta( $offer->ID, 'customers', $customers );

	wp_trash_post( $transaction_id );

	$data = array(
		'type' => 'offer', 
		'title' => $offer->post_title, 
		'performer' => dao_get_user_display_name( $offer->post_author ), 
	);

	wp_send_json_success( $data );
}

/**
 * ------------------------------ USER RATING ------------------------------ /
 */

add_action('wp_ajax_dao-get-users-rating-table', 'dao_cons_get_users_rating_table');
function dao_cons_get_users_rating_table() {
	check_ajax_referer( 'dao-consensus-get-users-rating-table' );

	$args = map_deep( $_POST['query_vars'], 'sanitize_text_field' );

	$args['role__in'] = 'contributor';

	$users = new WP_User_Query( $args );

	ob_start();

	if ( $users->get_results() ) {
		foreach ( $users->get_results() as $k => $user ) {
			get_template_part('template-parts/profile-rating-row', null,  array('user' => $user, 'per_page' => $args['number'], 'page' => $args['paged'], 'index' => $k + 1));
		}
	}

	$html = ob_get_clean();

	wp_send_json_success( array( 'rows' => $html ) );
}

/**
 * ------------------------------- FAVOURITES ------------------------------- /
 */

add_action('wp_ajax_dao-add-to-favourites', 'dao_cons_add_to_favourites');
function dao_cons_add_to_favourites () {
	check_ajax_referer( 'dao-consensus-add-to-favourites' );

	if ( ! isset( $_POST['id'] ) ) {
		wp_send_json( '', 403 );
	}

	$id = sanitize_text_field( $_POST['id'] );
	$card = get_post( $id );

	$curr_user = wp_get_current_user();

	// add to favourites
	$favourites = get_user_meta( $curr_user->ID, 'favourites', true );
	if ( is_array( $favourites ) ) {
		/* check if card is already in favourites */
		if ( isset( $favourites[$id] ) ) {
			wp_send_json_error( array( 'message' => __('Данная карточка уже добавлена в Избранное', 'dao-consensus') ) );
		}
		$favourites[] = $id;
	} else {
		$favourites = [ $id ];
	}
	update_user_meta( $curr_user->ID, 'favourites', $favourites );

	$data = array(
		'type' => ($card->post_type === 'offers') ? 'Предложение' : 'Спрос',
		'title' => $card->post_title
	);

	wp_send_json_success( $data );
}

add_action('wp_ajax_dao-delete-from-favourites', 'dao_cons_delete_from_favourites');
function dao_cons_delete_from_favourites () {
	check_ajax_referer( 'dao-consensus-delete-from-favourites' );

	if ( ! isset( $_POST['id'] ) ) {
		wp_send_json( '', 403 );
	}

	$id = sanitize_text_field( $_POST['id'] );
	$card = get_post( $id );

	$curr_user = wp_get_current_user();

	// delete from favourites
	$favourites = get_user_meta( $curr_user->ID, 'favourites', true );
	
	if ( ! in_array( $id, $favourites ) ) {
		wp_send_json_error( array( 'message' => __('Такой карточки нету в Избранном.', 'dao-consensus') ) );
	}

	$favourites = array_diff( $favourites, [$id] );

	update_user_meta( $curr_user->ID, 'favourites', $favourites );

	$data = array(
		'id'	=> $card->ID,
		'type' 	=> ($card->post_type === 'offers') ? 'Предложение' : 'Спрос',
		'title' => $card->post_title
	);

	wp_send_json_success( $data );
}

add_action('wp_ajax_dao-get-profile-favourites', 'dao_cons_get_profile_favourites');
function dao_cons_get_profile_favourites() {
	check_ajax_referer( 'dao-consensus-get-profile-favourites' );

	if ( ! isset( $_POST['query_vars'] ) ) {
		wp_send_json( '', 403 );
	}

	$args = map_deep( $_POST['query_vars'], 'sanitize_text_field' );

	$args['post_status'] = array( 'active', 'inactive' );

	/* Add filters to main query */

	if ( isset( $_POST['filters'] ) ) {

		parse_str( $_POST['filters'], $filters );

		if ( ! empty( $filters ) ) {
			/* Sanitize filters */
			$filters = array_map( 'sanitize_text_field', $filters );

			if ( ! empty( $filters['search_query'] ) ) {
				$args['s'] = $filters['search_query'];
			}

			if ( ! empty( $filters['categories'] ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'categories',
					'field'    => 'slug',
					'terms'    => explode( ',', $filters['categories'] ),
				);
			}
		}
	}

	$the_query = new WP_Query( $args );

	$vars = array();

	$vars['found_posts']   = $the_query->found_posts;
	$vars['max_num_pages'] = $the_query->max_num_pages;

	ob_start();

	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) :
			$the_query->the_post();

			get_template_part( 'template-parts/profile-content-card', 'favourite' );
		endwhile;
	} else {
		get_template_part( 'template-parts/content', 'none' );
	}

	wp_reset_postdata();

	$html = ob_get_clean();

	wp_send_json_success( array( 'cards' => $html ) + $vars );
}


/**
 * ------------------------------ NOTIFICATIONS ------------------------------ /
 */
add_action('wp_ajax_dao-check-user-notifications', 'dao_cons_check_user_notifications');
function dao_cons_check_user_notifications () {
	check_ajax_referer('dao-consensus-check-user-notifications');

	$curr_user = wp_get_current_user();	

	$res = dao_count_user_unseen_notifications( $curr_user->ID );

	wp_send_json_success( array( 'count' => $res ) );
}

add_action('wp_ajax_dao-get-user-notifications', 'dao_cons_get_user_notifications');
function dao_cons_get_user_notifications () {
	check_ajax_referer('dao-consensus-get-user-notifications');

	if ( ! isset( $_POST['query_vars'] ) ) {
		wp_send_json('', 403);
	}

	$query_vars = array_map( 'sanitize_text_field', $_POST['query_vars'] );

	$curr_user = wp_get_current_user();

	$notifications = dao_get_user_notifications( $curr_user->ID, $query_vars['page'], $query_vars['per_page'] );

	ob_start();

	if ( ! empty( $notifications ) ) :

		foreach ( $notifications as $notification ) : 
			get_template_part( 'template-parts/profile-notification', 'card', array( 'notification' => $notification ) );
		endforeach; 

	endif;

	$html = ob_get_clean();

	wp_send_json_success( array( 'notifications' => $html ) );
}

add_action('wp_ajax_dao-get-user-notifications-top', 'dao_cons_get_user_notifications_top');
function dao_cons_get_user_notifications_top () {
	check_ajax_referer('dao-consensus-get-user-notifications-top');

	$curr_user = wp_get_current_user();	

	$notifications = dao_get_user_notifications_topbar( $curr_user->ID );

	ob_start();

	if ( ! empty( $notifications ) ) :
		foreach($notifications as $notification) {
			get_template_part( 'template-parts/profile-notification-card', 'top', array( 'notification' => $notification ) );
		}
	else:
		printf('<li class="topbar no-notifications">%s</li>', __('Новых уведомлений нету.', 'dao-consensus'));
	endif;

	$html = ob_get_clean();

	wp_send_json_success( array( 'count' => count( $notifications ), 'notifications' => $html ) );
}

add_action('wp_ajax_dao-mark-unseen-notifications', 'dao_cons_mark_unseen_notifications');
function dao_cons_mark_unseen_notifications () {
	check_ajax_referer('dao-consensus-mark-unseen-notifications');

	if ( ! isset( $_POST['ids'] ) ) {
		wp_send_json( 'Notification IDs are required', 403 );
	}

	$ids = array_map( 'sanitize_text_field', $_POST['ids'] );

	$curr_time = current_time( 'mysql' );

	global $wpdb;
	$res = dao_wpdb_update( "{$wpdb->prefix}user_notifications", ['seen' => 1, 'seen_at' => $curr_time], ['ID' => $ids] );

	// check on error
	if ( ! $res ) {
		wp_send_json_error();
	}
		
	wp_send_json_success();
}
