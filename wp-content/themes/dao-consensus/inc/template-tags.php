<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package DAO_Consensus
 */

if ( ! function_exists( 'dao_consensus_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function dao_consensus_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'dao-consensus' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'dao_consensus_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function dao_consensus_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'dao-consensus' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'dao_consensus_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function dao_consensus_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'dao-consensus' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'dao-consensus' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'dao-consensus' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'dao-consensus' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'dao-consensus' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'dao-consensus' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'dao_consensus_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function dao_consensus_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;

/**
 * Check if user online
 *
 * @param string $user_id User ID
 */
function dao_is_user_online( $user_id ) {
	// get the online users list
	$logged_in_users = get_transient( 'users_online' );
	// online, if (s)he is in the list and last activity was less than 15 minutes ago
	if ( isset( $logged_in_users[ $user_id ] ) && ( $logged_in_users[ $user_id ] > ( current_time( 'timestamp' ) - ( 15 * 60 ) ) ) ) {
		return true;
	} else {
		return $logged_in_users[ $user_id ];
	}
}

/**
 * Convert english months in russian
 *
 * @param string $date
 */
function eng_months_to_ru( $date ) {
	$ruMonths = array( 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря' );
	$enMonths = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );

	return str_replace( $enMonths, $ruMonths, $date );
}

/**
 * Sort $_FILES array
 *
 * @param array $arr_files
 */
function rearrange_files_arr( $files_arr ) {
	foreach ( $files_arr as $key => $all ) {
		foreach ( $all as $i => $val ) {
			$new[ $i ][ $key ] = $val;
		}
	}
	return $new;
}

/**
 * Format deadline
 *
 * @param int    $deadline
 * @param string $period
 */

function dao_deadline_format( $deadline, $period ) {

	if ( empty( $deadline ) ) {
		return 0;
	}

	if ( $deadline === 1 ) {
		if ( $period === 'days' ) {
			return "{$deadline} дня";
		}
		if ( $period === 'months' ) {
			return "{$deadline} месяца";
		}
		if ( $period === 'years' ) {
			return "{$deadline} года";
		}
	} else {
		if ( $period === 'days' ) {
			return "{$deadline} дней";
		}
		if ( $period === 'months' ) {
			return "{$deadline} месяцев";
		}
		if ( $period === 'years' ) {
			return "{$deadline} лет";
		}
	}

}

/**
 * Add admin column
 *
 * @param string   $column_title
 * @param string   $post_type
 * @param function $cb callback function
 */
function add_admin_column( $column_title, $post_type, $cb ) {

	// Column Header
	add_filter(
		'manage_' . $post_type . '_posts_columns',
		function( $columns ) use ( $column_title ) {
			$columns[ sanitize_title( $column_title ) ] = $column_title;
			return $columns;
		}
	);

	// Column Content
	add_action(
		'manage_' . $post_type . '_posts_custom_column',
		function( $column, $post_id ) use ( $column_title, $cb ) {

			if ( sanitize_title( $column_title ) === $column ) {
				$cb( $post_id );
			}

		},
		10,
		2
	);
}


/**
 * Validate date
 *
 * @param string $date datetime string
 * @param string $format date format
 *
 * @return bool
 */
function validateDate( string $date, string $format = 'd.m.Y' ) {
	$d = DateTime::createFromFormat( $format, $date );
	return $d && $d->format( $format ) === $date;
}

/**
 * Check if user exists by id
 *
 * @param string|int|WP_User $user_id User ID or Object
 *
 * @return bool
 */
function dao_does_user_exists( $user_id ) {

	if ( empty( $user_id ) ) {
		return false;
	}

	if ( $user_id instanceof WP_User ) {
		$user_id = $user_id->ID;
	}

	return (bool) get_user_by( 'ID', $user_id );

}

/**
 * Get all invited users by invitations
 *
 * @param array $invitations
 * @return array $invited_users
 */
function dao_get_all_invited_users( array $invitations ) {

	if ( empty( $invitations ) ) {
		return array();
	}

	$invited_users = array();

	foreach ( $invitations as $id ) {
		$user_id                   = get_post_meta( $id, 'invited_id', true );
		$user                      = get_userdata( $user_id );
		$invited_users[ $user_id ] = $user->get( 'display_name' );
	}

	return $invited_users;

}

/**
 * Get all invitor by invitations
 *
 * @param array $invitations
 * @return array $invited_users
 */
function dao_get_all_invitοr_users( array $invitations ) {

	if ( empty( $invitations ) ) {
		return array();
	}

	$invitor_users = array();

	foreach ( $invitations as $id ) {
		$user_id                   = get_post_field( 'post_author', $id );
		$user                      = get_userdata( $user_id );
		$invitor_users[ $user_id ] = $user->get( 'display_name' );
	}

	return $invitor_users;

}

/**
 * Returns a cmb2 file_list
 *
 * @param  string $file_list_meta_key The field meta key. ($prefix . 'file_list')
 * @param  string $img_size           Size of image to show
 * @return string                      The html markup for the images
 */
function dao_cmb2_get_file_list_media( $post_id, $file_list_meta_key, $img_size = 'medium' ) {

	// Get the list of files
	$files = get_post_meta( $post_id, $file_list_meta_key, 1 );

	if ( ! empty( $files ) ) {

		ob_start();

		foreach ( (array) $files as $attach_id => $attach_url ) :
			$mime_type   = get_post_mime_type( $attach_id );
			$attach_file = get_attached_file( $attach_id );
	?>
			<div class="image-view default" data-id="<?php echo $attach_id; ?>">
				<? if ( ! in_array( $mime_type, ['image/png', 'image/jpeg', 'image/jpg'] )  ) : ?>
					<video width="300" height="300" controls="controls" src="<?php echo wp_get_attachment_url( $attach_id, $img_size ); ?>">
					</video>
				<? else: ?>
					<img src="<?php echo wp_get_attachment_image_url( $attach_id, 'medium' ); ?>" alt="">
				<? endif; ?>
				<span class="title"><?php echo basename( $attach_file ); ?></span>
				<span class="size"><? printf("%4.2f MB", filesize( $attach_file )/1048576); ?></span>
				<span class="close">&times;</span>
			</div>
	<?
		endforeach;

		$media = ob_get_clean();

		return array( 'file_list' => $media, 'count' => count($files) );

	} else {

		return array();

	}

}


/**
 * Get user display name
 * 
 * @param int $user_id User ID
 * 
 * @return string $display_name
 */
function dao_get_user_display_name( int $user_id ) {
	global $wpdb;
	return $wpdb->get_var( $wpdb->prepare( "SELECT display_name FROM {$wpdb->users} WHERE ID = %d", $user_id ) );
}

/**
 * Get user testimonials
 * 
 * @param int $user_id User ID
 * @param int $amount per page
 * @param int $page index of current page
 * 
 * @return array $testimonials Array with objects
 */
function dao_get_user_testimonials( int $user_id, int $per_page = 10, int $page = 1 ) {

	$user = dao_does_user_exists( $user_id );
	if ( ! $user ) {
		throw new Exception("There is no User with ID {$user_id}");
	}

	$offset = ( $page !== 1 ) ? ( $page * $per_page ) - $per_page : 0;

	global $wpdb;
	$result = $wpdb->get_results(
		$wpdb->prepare( "SELECT SQL_CALC_FOUND_ROWS * FROM {$wpdb->prefix}user_testimonials test1 WHERE receiver_id = %d AND EXISTS (SELECT ID FROM {$wpdb->prefix}user_testimonials test2 WHERE author_id = test1.receiver_id AND receiver_id = test1.author_id AND transaction_id = test1.transaction_id) ORDER BY created_at DESC LIMIT {$per_page} OFFSET {$offset}", $user_id )
	);

	$found_rows = (int) $wpdb->get_var( 'SELECT FOUND_ROWS()' );

	$data = array(
		'found' => $found_rows,
		'max_num_pages' => ceil( $found_rows / $per_page ),
		'page' => $page,
		'per_page' => $per_page,
		'testimonials' => $result
	);

	return $data;
}

/**
 * Count user testimonials
 * 
 * @param int $user_id User ID
 * 
 * @return int
 */
function dao_count_user_testimonials( int $user_id ) {
	global $wpdb;
	return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}user_testimonials WHERE receiver_id = %d", $user_id ) );
}

/**
 * Count user completed transactions
 * 
 * @param int $user_id User ID
 * 
 * @return int 
 */
function dao_count_user_completed_transactions( int $user_id ) {
	
	$args = array(
		'post_type' => 'transactions',
		'post_status' => 'completed',
		'posts_per_page' => -1,
		'fields' => 'ids',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' => 'initiator',
				'value' => $user_id
			),
			array(
				'key' => 'deal_person',
				'value' => $user_id
			)
		)
	);

	$transactions = new WP_Query( $args );

	update_user_meta( $user_id, 'completed_transactions', $transactions->found_posts );

	return $transactions->found_posts;
}

/**
 * Get user rating.
 * 
 * @param int $user_id User ID
 * 
 * @return int user_rating
 */
function dao_get_user_rating( int $user_id ) {
	global $wpdb;
	$query = "SELECT rating FROM {$wpdb->prefix}user_testimonials WHERE receiver_id = %d";
	$count = "SELECT COUNT(*) FROM {$wpdb->prefix}user_testimonials WHERE receiver_id = %d";

	$amount = (int) $wpdb->get_var( $wpdb->prepare( $count, $user_id ) );
	$scores = $wpdb->get_results( $wpdb->prepare( $query, $user_id ) );

	$rating = [];
	if ( ! empty( $scores ) ) {
		foreach ( $scores as $score ) {
			$rating[] = $score->rating;
		}
	}

	$sum_up = round( array_sum( $rating ) / $amount, 1 );

	update_user_meta( $user_id, 'rating', $sum_up );

	return $sum_up;
}


/**
 * Get user testimonial
 * 
 * @param int $transaction_id Transaction ID
 * @param int $user_id User ID
 * 
 * @return 
 */
function dao_get_user_testimonial( int $transaction_id, int $user_id ) {
	global $wpdb;
	$query = "SELECT * FROM {$wpdb->prefix}user_testimonials WHERE transaction_id = %d AND author_id = %d";
	return $wpdb->get_row( $wpdb->prepare( $query, $transaction_id, $user_id ) );
}

/**
 * Get user rating with label.
 * Rating scale: professional - 4.5-5.0, experienced - 3.0-4.5, beginner - 0-3.0
 * 
 * @param int $user_id User ID
 * 
 * @return string
 */
function dao_get_user_rating_label( int $user_id ) {
	$rating = get_user_meta( $user_id, 'rating', true );

	if ( $rating ) {
		if ( $rating > 0 && $rating < 3 ) {
			return __('начинающий', 'dao-consensus');
		} elseif ( $rating >= 3 && $rating <=4.5 ) {
			return __('опытный', 'dao-consensus');
		} elseif ( $rating >= 4.5 && $rating <=5 ) {
			return __('профессионал', 'dao-consensus');
		}
	} else {
		return __('начинающий', 'dao-consensus');
	}
}

/**
 * Get all (seen and unseen) user notifications
 * 
 * @param int $user_id User ID
 * @param int $page Page number
 * 
 * @return array Array with number of notifications and pages,
 */
function dao_get_user_notifications( int $user_id, int $page, $per_page = 5 ) {
	global $wpdb;
	$offset = ( $per_page * $page ) - $per_page;

	$query = "SELECT ID, post_id, type, message, created_at, seen FROM {$wpdb->prefix}user_notifications WHERE user_id=%d ORDER BY created_at DESC LIMIT {$per_page} OFFSET {$offset}";
	return $wpdb->get_results( $wpdb->prepare( $query, $user_id ) );;
}

/**
 * Get all unseen user notifications
 * 
 * @param int $user_id
 * 
 * @return array array of objects
 */
function dao_get_user_notifications_topbar( int $user_id ) {
	global $wpdb;
	$query = "SELECT ID, post_id, type, message, created_at FROM {$wpdb->prefix}user_notifications WHERE user_id=%d AND seen=0 ORDER BY created_at DESC";
	return $wpdb->get_results( $wpdb->prepare( $query, $user_id ) );
}

/**
 * Check user unseen notifications
 * 
 * @param int $user_id User ID
 * 
 * @return void
 */
function dao_count_user_unseen_notifications( int $user_id ) {
	global $wpdb;
	$query = "SELECT COUNT(*) FROM {$wpdb->prefix}user_notifications WHERE user_id=%d AND seen=0";
	return $wpdb->get_var( $wpdb->prepare( $query, $user_id ) );
}

/**
 * Update user metafield completed transactions
 * 
 * @param int $user_id
 * 
 * @return void
 */
function dao_update_user_completed_transactions ( int $user_id ) {
	$args = array(
		'post_type' => 'transactions',
		'post_status' => array( 'in-process', 'completed' ),
		'posts_per_page' => -1,
		'fields' => 'ids',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' => 'initiator',
				'value' => $user_id
			),
			array(
				'key' => 'deal_person',
				'value' => $user_id
			)
		)
	);

	$transactions = new WP_Query( $args );

	update_user_meta( $user_id, 'completed_transactions', $transactions->found_posts );
}

/**
 * Update a row in the table
 *
 * Extends basic $wpdb->update to allow pass array in value of $where field array. Passed array become `IN ()` sql statement.
 *
 * $wpdb->update( 'table', [ 'column' => 'foo', 'field' => 1337 ], [ 'ID' => [1,3,5] ] )
 *
 * @param string       $table        Table name
 * @param array        $data         Data to update (in column => value pairs).
 *                                   Both $data columns and $data values should be "raw" (neither should be SQL escaped).
 *                                   Sending a null value will cause the column to be set to NULL.
 * @param array        $where        A named array of WHERE clauses (column => value).
 *                                   value can be an array, it becomes `IN ()` sql statement in this case.
 *                                   Multiple clauses will be joined with ANDs.
 *                                   Both $where columns and $where values should be "raw".
 *                                   Sending a null value will create an IS NULL comparison.
 *
 * @return int|bool Number of rows affected/selected for all other queries. Boolean false on error.

 * @see wpdb::update() https://wp-kama.ru/filecode/wp-includes/wp-db.php#L2214-2255
 *
 * @author Kama
 * 
 * @ver 1.0
 */
function dao_wpdb_update( $table, $data, $where ){
	global $wpdb;

	if ( ! is_array( $data ) || ! is_array( $where ) )
		return false;

	$SET = $WHERE = [];

	// SET
	foreach ( $data as $field => $value ) {
		$field = sanitize_key( $field );

		if ( is_null( $value ) ) {
			$SET[] = "`$field` = NULL";
			continue;
		}

		$SET[] = $wpdb->prepare( "`$field` = %s", $value );
	}

	// WHERE
	foreach ( $where as $field => $value ) {
		$field = sanitize_key( $field );

		if ( is_null( $value ) ) {
			$WHERE[] = "`$field` IS NULL";
			continue;
		}

		if( is_array($value) ){
			foreach( $value as & $val ){
				$val = $wpdb->prepare( "%s", $val );
			}
			unset( $val );

			$WHERE[] = "`$field` IN (". implode(',', $value) .")";
		}
		else
			$WHERE[] = $wpdb->prepare( "`$field` = %s", $value );
	}

	$sql = "UPDATE `$table` SET ". implode( ', ', $SET ) ." WHERE ". implode( ' AND ', $WHERE );

	return $wpdb->query( $sql );
}