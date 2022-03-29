<?php

function dao_consensus_scripts() {

	/* main theme style and script */
	wp_enqueue_style( 'dao-consensus', get_stylesheet_uri(), array(), filemtime( get_theme_file_path( 'style.css' ) ) );
	wp_enqueue_script( 'dao-utils', get_stylesheet_directory_uri() . '/assets/js/utils.js', array(), filemtime( get_theme_file_path( '/assets/js/utils.js' ) ), true );

	/* global components */
	wp_enqueue_script( 'snack', get_stylesheet_directory_uri() . '/assets/js/components/Snack.min.js', array(), null, true );

	$deps = array( 'jquery', 'dao-utils' );

	if ( is_user_logged_in() ) {
		wp_enqueue_script( 'notifications', get_stylesheet_directory_uri() . '/assets/js/notifications.js', $deps, filemtime( get_theme_file_path( 'assets/js/notifications.js' ) ), true );
	}

	if ( is_front_page() ) {
		/* Swiper */
		wp_enqueue_script( 'swiper', get_stylesheet_directory_uri() . '/assets/js/swiper-bundle.min.js', array(), '6.8.0', true );
		wp_enqueue_style( 'swiper', get_stylesheet_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), '6.8.0' );
		
		$deps[] = 'swiper';
	}

	/* enqueue libraries and components for archive pages */
	if ( is_post_type_archive( 'demands' ) || is_post_type_archive( 'offers' ) || is_post_type_archive( 'transactions' ) ) {
		/* tui pagination */
		wp_enqueue_script( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/js/tui-pagination.min.js', array(), '3.4.0', true );
		wp_enqueue_style( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/css/tui-pagination.min.css', array(), '3.4.0' );

		/* components */
		wp_enqueue_script( 'select', get_stylesheet_directory_uri() . '/assets/js/components/Select.js', array(), null, true );
		wp_enqueue_script( 'textfield', get_stylesheet_directory_uri() . '/assets/js/components/TextField.min.js', array(), null, true );

		/* add dependecies */
		$deps[] = 'tui-pagination';
		$deps[] = 'select';
		$deps[] = 'textfield';
	}

	/* enqueue libraries and components for single page */
	if ( is_singular( 'demands' ) || is_singular( 'offers' ) ) {
		/* Swiper */
		wp_enqueue_script( 'swiper', get_stylesheet_directory_uri() . '/assets/js/swiper-bundle.min.js', array(), '6.8.0', true );
		wp_enqueue_style( 'swiper', get_stylesheet_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), '6.8.0' );

		/* components */
		wp_enqueue_script( 'good', get_stylesheet_directory_uri() . '/assets/js/pages/Good.min.js', array( 'swiper' ), null, true );

		$deps[] = 'swiper';
		$deps[] = 'good';
	}

	/* enqueue libraries and components for profile page and its child pages */
	if ( is_page('profile') ) {
		/* tui pagination */
		wp_enqueue_script( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/js/tui-pagination.min.js', array(), '3.4.0', true );
		wp_enqueue_style( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/css/tui-pagination.min.css', array(), '3.4.0' );

		/* components */
		wp_enqueue_script( 'textfield', get_stylesheet_directory_uri() . '/assets/js/components/TextField.min.js', array(), null, true );
		wp_enqueue_script( 'select', get_stylesheet_directory_uri() . '/assets/js/components/Select.min.js', array( 'textfield' ), null, true );
		wp_enqueue_script( 'modal', get_stylesheet_directory_uri() . '/assets/js/components/Modal.js', array(), null, true );
		wp_enqueue_script( 'offer-card', get_stylesheet_directory_uri() . '/assets/js/components/OfferCard.min.js', array( 'modal' ), null, true );
		wp_enqueue_editor();

		/* Swiper */
		wp_enqueue_script( 'swiper', get_stylesheet_directory_uri() . '/assets/js/swiper-bundle.min.js', array(), '6.8.0', true );
		wp_enqueue_style( 'swiper', get_stylesheet_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), '6.8.0' );

		/* Select2 */
		wp_enqueue_script( 'select2', get_stylesheet_directory_uri() . '/assets/js/select2.min.js', array(), '4.1.0', true );

		/* jquery-validation */
		wp_enqueue_script( 'validation', get_stylesheet_directory_uri() . '/assets/js/jquery.validate.min.js', array(), '1.19.3', true );
		wp_enqueue_script( 'validation-add', get_stylesheet_directory_uri() . '/assets/js/additional-methods.min.js', array( 'validation' ), '1.19.3', true );

		$deps[] = 'select';
		$deps[] = 'textfield';
		$deps[] = 'modal';
		$deps[] = 'offer-card';
		$deps[] = 'tui-pagination';
		$deps[] = 'select2';
		$deps[] = 'swiper';
	}

	global $post;
	if ( in_array(21, get_post_ancestors($post)) ) {

		/* components */
		wp_enqueue_script( 'textfield', get_stylesheet_directory_uri() . '/assets/js/components/TextField.min.js', array(), null, true );
		wp_enqueue_script( 'select', get_stylesheet_directory_uri() . '/assets/js/components/Select.js', array( 'textfield' ), null, true );
		wp_enqueue_script( 'modal', get_stylesheet_directory_uri() . '/assets/js/components/Modal.js', array(), null, true );

		$deps[] = 'select';
		$deps[] = 'textfield';
		$deps[] = 'modal';

		if ( is_page( 'demands' ) || is_page( 'offers' ) ) {
			/* tui pagination */
			wp_enqueue_script( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/js/tui-pagination.min.js', array(), '3.4.0', true );
			wp_enqueue_style( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/css/tui-pagination.min.css', array(), '3.4.0' );

			/* components */
			wp_enqueue_script( 'tabs', get_stylesheet_directory_uri() . '/assets/js/components/Tabs.min.js', array(), null, true );
			wp_enqueue_script( 'offer-card', get_stylesheet_directory_uri() . '/assets/js/components/OfferCard.min.js', array( 'modal' ), null, true );

			$deps[] = 'tabs';
			$deps[] = 'offer-card';
			$deps[] = 'tui-pagination';
		}

		if ( is_page('in-process') ) {
			/* tui pagination */
			wp_enqueue_script( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/js/tui-pagination.min.js', array(), '3.4.0', true );
			wp_enqueue_style( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/css/tui-pagination.min.css', array(), '3.4.0' );

			/* components */
			wp_enqueue_script( 'inprocess-card', get_stylesheet_directory_uri() . '/assets/js/components/InProcessCard.js', array( 'modal' ), null, true );
			wp_enqueue_script( 'tabs', get_stylesheet_directory_uri() . '/assets/js/components/Tabs.min.js', array(), null, true );

			$deps[] = 'inprocess-card';
			$deps[] = 'tabs';
			$deps[] = 'tui-pagination';
		}

		if ( is_page('submit-testimonial') ) {
			/* jquery-validation */
			wp_enqueue_script( 'validation', get_stylesheet_directory_uri() . '/assets/js/jquery.validate.min.js', array(), '1.19.3', true );
			wp_enqueue_script( 'validation-add', get_stylesheet_directory_uri() . '/assets/js/additional-methods.min.js', array( 'validation' ), '1.19.3', true );
			
			/* Rater.js */
			wp_enqueue_script( 'rater', get_stylesheet_directory_uri() . '/assets/js/rater.min.js', array(), null, true );

			$deps[] = 'validation';
			$deps[] = 'validation-add';
			$deps[] = 'rater';
		}

		if ( is_page('meetings') ) {
			/* tui pagination */
			wp_enqueue_script( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/js/tui-pagination.min.js', array(), '3.4.0', true );
			wp_enqueue_style( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/css/tui-pagination.min.css', array(), '3.4.0' );

			/* jquery-validation */
			wp_enqueue_script( 'validation', get_stylesheet_directory_uri() . '/assets/js/jquery.validate.min.js', array(), '1.19.3', true );
			wp_enqueue_script( 'validation-add', get_stylesheet_directory_uri() . '/assets/js/additional-methods.min.js', array( 'validation' ), '1.19.3', true );

			/* components */
			wp_enqueue_script( 'tabs', get_stylesheet_directory_uri() . '/assets/js/components/Tabs.min.js', array(), null, true );
			wp_enqueue_script( 'meetings-table', get_stylesheet_directory_uri() . '/assets/js/components/MeetingsTable.js', array(), null, true );
			wp_enqueue_script( 'datepicker', get_stylesheet_directory_uri() . '/assets/js/components/DatePicker.min.js', array(), null, true );

			$deps[] = 'meetings-table';
			$deps[] = 'tui-pagination';
			$deps[] = 'validation';
			$deps[] = 'validation-add';
		}

		if ( is_page('favourites') ) {
			/* tui pagination */
			wp_enqueue_script( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/js/tui-pagination.min.js', array(), '3.4.0', true );
			wp_enqueue_style( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/css/tui-pagination.min.css', array(), '3.4.0' );
			
			/* components */
			wp_enqueue_script( 'favourite-card', get_stylesheet_directory_uri() . '/assets/js/components/FavouriteCard.js', array(), null, true );

			$deps[] = 'favourite-card';
			$deps[] = 'tui-pagination';
		}

		if ( is_page('rating') ) {
			/* tui pagination */
			wp_enqueue_script( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/js/tui-pagination.min.js', array(), '3.4.0', true );
			wp_enqueue_style( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/css/tui-pagination.min.css', array(), '3.4.0' );
			
			$deps[] = 'tui-pagination';
		}

		if ( is_page('calendar') ) {
			/* jquery-validation */
			wp_enqueue_script( 'validation', get_stylesheet_directory_uri() . '/assets/js/jquery.validate.min.js', array(), '1.19.3', true );
			wp_enqueue_script( 'validation-add', get_stylesheet_directory_uri() . '/assets/js/additional-methods.min.js', array( 'validation' ), '1.19.3', true );

			/* components */
			wp_enqueue_script( 'calendar', get_stylesheet_directory_uri() . '/assets/js/components/Calendar.js', array(), null, true );
			wp_enqueue_script( 'datepicker', get_stylesheet_directory_uri() . '/assets/js/components/DatePicker.min.js', array(), null, true );

			$deps[] = 'validation';
			$deps[] = 'calendar';
			$deps[] = 'datepicker';
		}

		if ( is_page('edit') ) {
			/* jquery-validation */
			wp_enqueue_script( 'validation', get_stylesheet_directory_uri() . '/assets/js/jquery.validate.min.js', array(), '1.19.3', true );
			wp_enqueue_script( 'validation-add', get_stylesheet_directory_uri() . '/assets/js/additional-methods.min.js', array( 'validation' ), '1.19.3', true );

			/* Select2 */
			wp_enqueue_script( 'select2', get_stylesheet_directory_uri() . '/assets/js/select2.min.js', array(), '4.1.0', true );

			$deps[] = 'validation';
			$deps[] = 'validation-add';
			$deps[] = 'select2';
		}

		if ( is_page('schedule-meeting') ) {
			/* jquery-validation */
			wp_enqueue_script( 'validation', get_stylesheet_directory_uri() . '/assets/js/jquery.validate.min.js', array(), '1.19.3', true );
			wp_enqueue_script( 'validation-add', get_stylesheet_directory_uri() . '/assets/js/additional-methods.min.js', array( 'validation' ), '1.19.3', true );

			/* components */
			wp_enqueue_script( 'datepicker', get_stylesheet_directory_uri() . '/assets/js/components/DatePicker.min.js', array(), null, true );

			$deps[] = 'validation';
			$deps[] = 'validation-add';
		}

		if ( is_page('change-userdata') ) {
			/* jquery-validation */
			wp_enqueue_script( 'validation', get_stylesheet_directory_uri() . '/assets/js/jquery.validate.min.js', array(), '1.19.3', true );
			wp_enqueue_script( 'validation-add', get_stylesheet_directory_uri() . '/assets/js/additional-methods.min.js', array( 'validation' ), '1.19.3', true );

			/* Select2 */
			wp_enqueue_script( 'select2', get_stylesheet_directory_uri() . '/assets/js/select2.min.js', array(), '4.1.0', true );

			$deps[] = 'validation';
			$deps[] = 'validation-add';
			$deps[] = 'select2';
		}

		if ( is_page( 'notifications' ) ) {
			/* tui pagination */
			wp_enqueue_script( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/js/tui-pagination.min.js', array(), '3.4.0', true );
			wp_enqueue_style( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/css/tui-pagination.min.css', array(), '3.4.0' );
		}

		if ( is_page( 'testimonials' ) ) {
			/* tui pagination */
			wp_enqueue_script( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/js/tui-pagination.min.js', array(), '3.4.0', true );
			wp_enqueue_style( 'tui-pagination', get_stylesheet_directory_uri() . '/assets/css/tui-pagination.min.css', array(), '3.4.0' );			
		}

	}

	/* enqueue libraries and components for create demand page */
	if ( is_page( 'create-demand' ) ) {
		/* jquery-validation */
		wp_enqueue_script( 'validation', get_stylesheet_directory_uri() . '/assets/js/jquery.validate.min.js', array(), '1.19.3', true );
		wp_enqueue_script( 'validation-add', get_stylesheet_directory_uri() . '/assets/js/additional-methods.min.js', array( 'validation' ), '1.19.3', true );

		/* Select2 */
		wp_enqueue_script( 'select2', get_stylesheet_directory_uri() . '/assets/js/select2.min.js', array(), '4.1.0', true );

		/* components */
		wp_enqueue_script( 'tabs', get_stylesheet_directory_uri() . '/assets/js/components/Tabs.min.js', array(), null, true );
		wp_enqueue_script( 'textfield', get_stylesheet_directory_uri() . '/assets/js/components/TextField.min.js', array(), null, true );
		wp_enqueue_script( 'select', get_stylesheet_directory_uri() . '/assets/js/components/Select.min.js', array( 'textfield' ), null, true );

		wp_enqueue_editor();
		
		/* add dependencies */
		$deps[] = 'tabs';
		$deps[] = 'select';
		$deps[] = 'textfield';
		$deps[] = 'validation';
		$deps[] = 'validation-add';
		$deps[] = 'select2';
	}

	if ( is_author() ) {
		/* Swiper */
		wp_enqueue_script( 'swiper', get_stylesheet_directory_uri() . '/assets/js/swiper-bundle.min.js', array(), '6.8.0', true );
		wp_enqueue_style( 'swiper', get_stylesheet_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), '6.8.0' );

		/* modal */
		wp_enqueue_script( 'modal', get_stylesheet_directory_uri() . '/assets/js/components/Modal.js', array(), null, true );

		$deps[] = 'swiper';
		$deps[] = 'modal';
	}

	if ( is_page('about-us') ) {
		/* Swiper */
		wp_enqueue_script( 'swiper', get_stylesheet_directory_uri() . '/assets/js/swiper-bundle.min.js', array(), '6.8.0', true );
		wp_enqueue_style( 'swiper', get_stylesheet_directory_uri() . '/assets/css/swiper-bundle.min.css', array(), '6.8.0' );

		/* components */
		wp_enqueue_script( 'textfield', get_stylesheet_directory_uri() . '/assets/js/components/TextField.min.js', array(), null, true );
		
		$deps[] = 'swiper';
	}

	if ( is_page( 'components' ) ) {
		/* components */
		wp_enqueue_script( 'tabs', get_stylesheet_directory_uri() . '/assets/js/components/Tabs.min.js', array(), null, true );
		wp_enqueue_script( 'textfield', get_stylesheet_directory_uri() . '/assets/js/components/TextField.min.js', array(), null, true );
		wp_enqueue_script( 'select', get_stylesheet_directory_uri() . '/assets/js/components/Select.min.js', array( 'textfield' ), null, true );
		wp_enqueue_script( 'modal', get_stylesheet_directory_uri() . '/assets/js/components/Modal.js', array(), null, true );
		wp_enqueue_script( 'offer-card', get_stylesheet_directory_uri() . '/assets/js/components/OfferCard.min.js', array( 'modal' ), null, true );
		wp_enqueue_script( 'calendar', get_stylesheet_directory_uri() . '/assets/js/components/Calendar.min.js', array( 'modal' ), null, true );
		wp_enqueue_script( 'datepicker', get_stylesheet_directory_uri() . '/assets/js/components/DatePicker.min.js', array(), null, true );

		/* Select2 */
		wp_enqueue_script( 'select2', get_stylesheet_directory_uri() . '/assets/js/select2.min.js', array(), '4.1.0', true );

		/* init script */
		wp_enqueue_script( 'components', get_stylesheet_directory_uri() . '/assets/js/pages/Components.js', array(), null, true );
	}

	/* additional script and style */
	wp_enqueue_style( 'dao-consensus-add', get_stylesheet_directory_uri() . '/assets/css/custom.css', array( 'dao-consensus' ), filemtime( get_theme_file_path( '/assets/css/custom.css' ) ) );
	wp_enqueue_script( 'dao-consensus-add', get_stylesheet_directory_uri() . '/assets/js/script.js', $deps, filemtime( get_theme_file_path( '/assets/js/script.js' ) ), true );
}
add_action( 'wp_enqueue_scripts', 'dao_consensus_scripts' );

add_action( 'wp_head', 'dao_consensus_global_js_variables' );
function dao_consensus_global_js_variables() {
	
	ob_start();
?>
	var ajaxurl = <?php echo json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
	<? if ( is_user_logged_in() ) : ?>
	var check_user_not_nonce = '<?= wp_create_nonce('dao-consensus-check-user-notifications'); ?>';
	var get_user_not_top_nonce = '<?= wp_create_nonce('dao-consensus-get-user-notifications-top'); ?>';
	var mark_unseen_not_nonce = '<?= wp_create_nonce('dao-consensus-mark-unseen-notifications'); ?>';
	<? endif; ?>

	<? if ( is_post_type_archive('demands') || is_post_type_archive('offers') ):
		global $wp_query;
	?>
		var query_vars =
		<?php
		echo json_encode(
			array(
				'post_type'      => $wp_query->query_vars['post_type'],
				'posts_per_page' => $wp_query->query_vars['posts_per_page'],
				'paged'          => get_query_var(
					'paged',
					1
				),
			)
		);
		?>
		;
		var found_posts = <?php echo $wp_query->found_posts; ?>;
		var max_num_pages = <?php echo $wp_query->max_num_pages; ?>;
		var nonce = <?php echo json_encode( wp_create_nonce( 'dao-consensus-filter-archive-posts' ) ); ?>;
	<? endif; ?>

	<? if ( is_post_type_archive('transactions') ) : 
		global $wp_query;	
	?>
		var action = 'dao-filter-archive-transactions';
		var query_vars =
		<?php
		echo json_encode(
			array(
				'post_type'      => $wp_query->query_vars['post_type'],
				'posts_per_page' => $wp_query->query_vars['posts_per_page'],
				'paged'          => get_query_var(
					'paged',
					1
				),
			)
		);
		?>;
		var found_posts = <?php echo $wp_query->found_posts; ?>;
		var max_num_pages = <?php echo $wp_query->max_num_pages; ?>;
		var nonce = <?php echo json_encode( wp_create_nonce( 'dao-consensus-filter-archive-transactions' ) ); ?>;
	<? 
		
		endif; 
	?>

	<? if ( is_author() || is_post_type_archive('demands') || is_post_type_archive('offers') ) : ?>
		var add_to_fav_action = 'dao-add-to-favourites';
		var add_to_fav_nonce = '<?php echo wp_create_nonce( 'dao-consensus-add-to-favourites' ); ?>';
		var delete_from_fav_action = 'dao-delete-from-favourites';
		var delete_from_fav_nonce = '<?php echo wp_create_nonce( 'dao-consensus-delete-from-favourites' ); ?>';
	<? endif; ?>

	<? if ( is_user_logged_in() && is_page('favourites') ) : ?>
		var get_fav_action = 'dao-get-profile-favourites';
		var get_fav_nonce = '<?php echo wp_create_nonce( 'dao-consensus-get-profile-favourites' ); ?>';
	<? endif; ?>

	<? if( is_page('change-userdata') ): ?>
		var login_url = <?= wp_json_encode( wp_login_url() );?>;
	<? endif; ?>

	<? if ( is_page_template( 'profile-schedule-meeting.php' ) ): ?>
		var profile_chat_url = '<?= get_permalink( 190 ); ?>';
	<? endif; ?>

	<? if ( is_page_template( 'profile-inprocess.php' ) ): ?>
		var submit_testimonial_url = '<?= get_permalink( 426 ); ?>';
	<? endif; ?>

	<? if ( is_page_template( 'create-demand.php' ) || is_page_template( 'profile-card-edit.php' ) || is_page_template( 'profile-submit-testimonial.php' ) ): ?>
		var profile_url = '<?= get_permalink(21); ?>';
	<? endif;

	$global_js = ob_get_clean();

	$global_js = preg_replace('/\s+/', ' ', $global_js);

	wp_add_inline_script( 'dao-consensus-add', $global_js, 'before' );
}
