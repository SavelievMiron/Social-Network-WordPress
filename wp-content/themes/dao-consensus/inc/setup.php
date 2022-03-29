<?php

/**
 * Register theme options
 */

add_action( 'admin_init', 'dao_consensus_register_settings' );
function dao_consensus_register_settings() {
	register_setting(
		'dao-consensus-options-group',
		'skills',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => null,
		)
	);
}

/**
* Activation and deactivation theme
*/
add_action( 'switch_theme', 'deactivate_dao_consensus' );
function deactivate_dao_consensus() {
	remove_role( 'community_manager' );
}

add_action( 'after_setup_theme', 'activate_dao_consensus' );
add_action( 'after_switch_theme', 'activate_dao_consensus' );
function activate_dao_consensus() {
	add_role(
		'community_manager',
		'Community manager',
		array(
			'remove_users'           => true,
			'upload_files'           => true,
			'list_users'             => true,
			'manage_options'		 => true,
			'manage_categories'      => true,
			'moderate_comments'      => true,
			'promote_users'          => true,
			'publish_pages'          => true,
			'publish_posts'          => true,
			'read_private_pages'     => true,
			'read_private_posts'     => true,
			'read'                   => true,
			'export'                 => true,
			'import'                 => true,
			'delete_others_posts'    => true,
			'delete_posts'           => true,
			'delete_private_posts'   => true,
			'delete_published_pages' => true,
			'delete_published_posts' => true,
			'edit_dashboard'         => true,
			'edit_others_posts'      => true,
			'edit_posts'             => true,
			'edit_private_posts'     => true,
			'edit_published_posts'   => true,
			// awesome support capabilities
			'view_ticket' 	   		 => true,
			'create_ticket' 	     => true,
			'close_ticket'           =>	true,
			'reply_ticket'           =>	true,
			'attach_files' 			 =>	true,
		)
	);
	add_image_size( 'card-thumb', 300, 210, true );

	// add new capabilites to Contributor role for Awesome Suppport
	$contributor = get_role( 'contributor' );

	$contributor->add_cap( 'view_ticket' );
	$contributor->add_cap( 'create_ticket' );
	$contributor->add_cap( 'close_ticket' );
	$contributor->add_cap( 'reply_ticket' );
	$contributor->add_cap( 'attach_files' );
}

/**
 * Set constants
 */
class DAO_CONSENSUS {

	public const cryptocurrencies = array(
		'sens' => 'SENS',
		'cons' => 'CONS',
		'usdt' => 'USDT',
		'bnb'  => 'BNB',
		'dmt'  => 'DMT',
	);

	public const person_types = array(
		'physical'  => 'Физ. лицо',
		'juridical' => 'Юр. лицо',
	);

	public const card_statuses = array(
		'active'   => 'Активно',
		'inactive' => 'Неактивно',
	);

	public const card_inprocess_statuses = array(
		'canceled'  => 'Отмена',
		'completed' => 'Завершено',
	);

	public const archive_statuses = array(
		'active'   => 'Активно',
		'inactive' => 'Неактивно',
	);

	public const transaction_statuses = array(
		'in-process'   => 'В работе',
		'completed'    => 'Завершено',
	);

	public const deadline_periods = array(
		'days'   => 'День',
		'months' => 'Месяц',
		'years'  => 'Год',
	);

	public const meeting_statuses = array(
		'waiting'    => 'В ожидании ответа',
		'accepted'   => 'Принято',
		'declined'   => 'Отклонено',
		'canceled'   => 'Отменено',
		'reschedule' => 'Перенос',
		'went'       => 'Прошла',
	);

	public const meeting_formats = array(
		'offline' => 'Офлайн',
		'online'  => 'Онлайн',
	);
}

/**
 * Register custom post types -- offers, demands, transactions and taxonomies
 */

function dao_consensus_post_types_init() {
	register_taxonomy(
		'categories',
		array( 'demands', 'offers' ),
		array(
			'labels'                => array(
				'name'          => 'Категории',
				'singular_name' => 'Категория',
				'search_items'  => 'Искать',
				'all_items'     => 'Все категории',
				'view_item '    => 'Просмотреть',
				'edit_item'     => 'Изменить',
				'update_item'   => 'Обновить',
				'add_new_item'  => 'Добавить',
				'new_item_name' => 'Новая',
				'menu_name'     => 'Категории',
			),
			'description'           => '', // описание таксономии
			'public'                => true,
			'hierarchical'          => false,
			'show_admin_column'     => true,
			'show_in_rest'          => true,
			'update_count_callback' => 'dao_update_count_callback',
		)
	);

	register_taxonomy(
		'portfolio_categories',
		array( 'portfolio' ),
		array(
			'labels'            => array(
				'name'          => 'Категории',
				'singular_name' => 'Категория',
				'search_items'  => 'Искать',
				'all_items'     => 'Все категории',
				'view_item '    => 'Просмотреть',
				'edit_item'     => 'Изменить',
				'update_item'   => 'Обновить',
				'add_new_item'  => 'Добавить',
				'new_item_name' => 'Новая',
				'menu_name'     => 'Категории',
			),
			'description'       => '', // описание таксономии
			'public'            => true,
			'hierarchical'      => false,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			// 'update_count_callback' => 'dao_update_count_callback',
		)
	);

	register_taxonomy(
		'formats',
		array( 'meetings' ),
		array(
			'labels'                => array(
				'name'          => 'Форматы',
				'singular_name' => 'Формат',
				'search_items'  => 'Искать',
				'all_items'     => 'Все форматы',
				'view_item '    => 'Просмотреть',
				'edit_item'     => 'Изменить',
				'update_item'   => 'Обновить',
				'add_new_item'  => 'Добавить',
				'new_item_name' => 'Новая',
				'menu_name'     => 'Форматы',
			),
			'description'           => '', // описание таксономии
			'public'                => true,
			'hierarchical'          => false,
			'show_admin_column'     => true,
			'show_in_rest'          => true,
			'update_count_callback' => 'dao_update_count_callback',
		)
	);

	function dao_update_count_callback( $terms, $taxonomy ) {
		global $wpdb;

		$object_types = (array) $taxonomy->object_type;

		foreach ( $object_types as &$object_type ) {
			list( $object_type ) = explode( ':', $object_type );
		}

		$object_types = array_unique( $object_types );

		$check_attachments = array_search( 'attachment', $object_types, true );
		if ( false !== $check_attachments ) {
			unset( $object_types[ $check_attachments ] );
			$check_attachments = true;
		}

		if ( $object_types ) {
			$object_types = esc_sql( array_filter( $object_types, 'post_type_exists' ) );
		}

		$post_statuses = array( 'publish', 'active', 'inactive', 'completed', 'in-process', 'waiting', 'accepted', 'declined', 'went' );

		/**
		 * Filters the post statuses for updating the term count.
		 *
		 * @since 5.7.0
		 *
		 * @param string[]    $post_statuses List of post statuses to include in the count. Default is 'publish'.
		 * @param WP_Taxonomy $taxonomy      Current taxonomy object.
		 */
		// $post_statuses = esc_sql( apply_filters( 'update_post_term_count_statuses', $post_statuses, $taxonomy ) );

		foreach ( (array) $terms as $term ) {
			$count = 0;

			// Attachments can be 'inherit' status, we need to base count off the parent's status if so.
			if ( $check_attachments ) {
				// phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.QuotedDynamicPlaceholderGeneration
				$count += (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts p1 WHERE p1.ID = $wpdb->term_relationships.object_id AND ( post_status IN ('" . implode( "', '", $post_statuses ) . "') OR ( post_status = 'inherit' AND post_parent > 0 AND ( SELECT post_status FROM $wpdb->posts WHERE ID = p1.post_parent ) IN ('" . implode( "', '", $post_statuses ) . "') ) ) AND post_type = 'attachment' AND term_taxonomy_id = %d", $term ) );
			}

			if ( $object_types ) {
				// phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.QuotedDynamicPlaceholderGeneration
				$count += (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts WHERE $wpdb->posts.ID = $wpdb->term_relationships.object_id AND post_status IN ('" . implode( "', '", $post_statuses ) . "') AND post_type IN ('" . implode( "', '", $object_types ) . "') AND term_taxonomy_id = %d", $term ) );
			}

			/** This action is documented in wp-includes/taxonomy.php */
			do_action( 'edit_term_taxonomy', $term, $taxonomy->name );
			$wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );

			/** This action is documented in wp-includes/taxonomy.php */
			do_action( 'edited_term_taxonomy', $term, $taxonomy->name );
		}
	}

	/**
	 * Demands
	 */
	$demands_labels = array(
		'name'                  => _x( 'Спросы', 'Post type general name', 'dao-consensus' ),
		'singular_name'         => _x( 'Спрос', 'Post type singular name', 'dao-consensus' ),
		'menu_name'             => _x( 'Спросы', 'Admin Menu text', 'dao-consensus' ),
		'name_admin_bar'        => _x( 'Спросы', 'Add New on Toolbar', 'dao-consensus' ),
		'add_new'               => __( 'Добавить спрос', 'dao-consensus' ),
		'add_new_item'          => __( 'Добавить спрос', 'dao-consensus' ),
		'new_item'              => __( 'Новый', 'dao-consensus' ),
		'edit_item'             => __( 'Редактировать', 'dao-consensus' ),
		'view_item'             => __( 'Просмотреть', 'dao-consensus' ),
		'all_items'             => __( 'Все спросы', 'dao-consensus' ),
		'search_items'          => __( 'Искать', 'dao-consensus' ),
		'not_found'             => __( 'Ничего не найдено.', 'dao-consensus' ),
		'not_found_in_trash'    => __( 'Корзина пустая.', 'dao-consensus' ),
		'featured_image'        => _x( 'Изображение', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
		'set_featured_image'    => _x( 'Установить', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
		'remove_featured_image' => _x( 'Удалить', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
	);
	$demands_args   = array(
		'labels'             => $demands_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'taxonomies'         => array( 'categories' ),
		'rewrite'            => array( 'slug' => 'demands' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-media-document',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'author', 'revisions' ),
		'show_in_rest'       => true,
	);
	register_post_type( 'demands', $demands_args );

	/**
	 * Offers
	 */
	$offers_labels = array(
		'name'                  => _x( 'Предложения', 'Post type general name', 'dao-consensus' ),
		'singular_name'         => _x( 'Предложение', 'Post type singular name', 'dao-consensus' ),
		'menu_name'             => _x( 'Предложения', 'Admin Menu text', 'dao-consensus' ),
		'name_admin_bar'        => _x( 'Предложения', 'Add New on Toolbar', 'dao-consensus' ),
		'add_new'               => __( 'Добавить предложение', 'dao-consensus' ),
		'add_new_item'          => __( 'Добавить предложение', 'dao-consensus' ),
		'new_item'              => __( 'Добавить', 'dao-consensus' ),
		'edit_item'             => __( 'Изменить', 'dao-consensus' ),
		'view_item'             => __( 'Просмотреть', 'dao-consensus' ),
		'all_items'             => __( 'Все предложения', 'dao-consensus' ),
		'search_items'          => __( 'Искать', 'dao-consensus' ),
		'not_found'             => __( 'Ничего не найдено.', 'dao-consensus' ),
		'not_found_in_trash'    => __( 'Корзина пустая.', 'dao-consensus' ),
		'featured_image'        => _x( 'Изображение', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
		'set_featured_image'    => _x( 'Установить', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
		'remove_featured_image' => _x( 'Удалить', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
	);
	$offers_args   = array(
		'labels'             => $offers_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'taxonomies'         => array( 'categories' ),
		'rewrite'            => array( 'slug' => 'offers' ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'has_archive'        => true,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-testimonial',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'author', 'revisions' ),
		'show_in_rest'       => true,
	);
	register_post_type( 'offers', $offers_args );

	/**
	 * Transactions
	 */
	$transactions_labels = array(
		'name'                  => _x( 'Сделки', 'Post type general name', 'dao-consensus' ),
		'singular_name'         => _x( 'Сделка', 'Post type singular name', 'dao-consensus' ),
		'menu_name'             => _x( 'Сделки', 'Admin Menu text', 'dao-consensus' ),
		'name_admin_bar'        => _x( 'Сделки', 'Add New on Toolbar', 'dao-consensus' ),
		'add_new'               => __( 'Добавить сделку', 'dao-consensus' ),
		'add_new_item'          => __( 'Добавить сделку', 'dao-consensus' ),
		'new_item'              => __( 'Добавить', 'dao-consensus' ),
		'edit_item'             => __( 'Изменить', 'dao-consensus' ),
		'view_item'             => __( 'Просмотреть', 'dao-consensus' ),
		'all_items'             => __( 'Все сделки', 'dao-consensus' ),
		'search_items'          => __( 'Искать', 'dao-consensus' ),
		'not_found'             => __( 'Ничего не найдено.', 'dao-consensus' ),
		'not_found_in_trash'    => __( 'Корзина пустая.', 'dao-consensus' ),
		'featured_image'        => _x( 'Изображение', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
		'set_featured_image'    => _x( 'Установить', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
		'remove_featured_image' => _x( 'Удалить', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
	);
	$transactions_args   = array(
		'labels'             => $transactions_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'transactions' ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'has_archive'        => true,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-book-alt',
		'supports'           => array( 'title', 'editor' ),
		'show_in_rest'       => true,
	);
	register_post_type( 'transactions', $transactions_args );

	/**
	 * Meetings
	 */
	$meetings_labels = array(
		'name'                  => _x( 'Встречи', 'Post type general name', 'dao-consensus' ),
		'singular_name'         => _x( 'Встреча', 'Post type singular name', 'dao-consensus' ),
		'menu_name'             => _x( 'Встречи', 'Admin Menu text', 'dao-consensus' ),
		'name_admin_bar'        => _x( 'Встречи', 'Add New on Toolbar', 'dao-consensus' ),
		'add_new'               => __( 'Добавить встречу', 'dao-consensus' ),
		'add_new_item'          => __( 'Добавить встречу', 'dao-consensus' ),
		'new_item'              => __( 'Добавить', 'dao-consensus' ),
		'edit_item'             => __( 'Изменить', 'dao-consensus' ),
		'view_item'             => __( 'Просмотреть', 'dao-consensus' ),
		'all_items'             => __( 'Все встречи', 'dao-consensus' ),
		'search_items'          => __( 'Искать', 'dao-consensus' ),
		'not_found'             => __( 'Ничего не найдено.', 'dao-consensus' ),
		'not_found_in_trash'    => __( 'Корзина пустая.', 'dao-consensus' ),
		'featured_image'        => _x( 'Изображение', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
		'set_featured_image'    => _x( 'Установить', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
		'remove_featured_image' => _x( 'Удалить', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
	);
	$meetings_args   = array(
		'labels'             => $meetings_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'meetings' ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'has_archive'        => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-calendar-alt',
		'supports'           => array( 'title', 'editor', 'author' ),
		'show_in_rest'       => true,
	);
	register_post_type( 'meetings', $meetings_args );

	/**
	 * Tickets (support)
	 */
	$portfolio_labels = array(
		'name'                  => _x( 'Портфолио', 'Post type general name', 'dao-consensus' ),
		'singular_name'         => _x( 'Портфолио', 'Post type singular name', 'dao-consensus' ),
		'menu_name'             => _x( 'Портфолио', 'Admin Menu text', 'dao-consensus' ),
		'name_admin_bar'        => _x( 'Портфолио', 'Add New on Toolbar', 'dao-consensus' ),
		'add_new'               => __( 'Добавить работу', 'dao-consensus' ),
		'add_new_item'          => __( 'Добавить работу', 'dao-consensus' ),
		'new_item'              => __( 'Добавить', 'dao-consensus' ),
		'edit_item'             => __( 'Изменить', 'dao-consensus' ),
		'view_item'             => __( 'Просмотреть', 'dao-consensus' ),
		'all_items'             => __( 'Все работы', 'dao-consensus' ),
		'search_items'          => __( 'Искать', 'dao-consensus' ),
		'not_found'             => __( 'Ничего не найдено.', 'dao-consensus' ),
		'not_found_in_trash'    => __( 'Корзина пустая.', 'dao-consensus' ),
		'featured_image'        => _x( 'Изображение', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
		'set_featured_image'    => _x( 'Установить', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
		'remove_featured_image' => _x( 'Удалить', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dao-consensus' ),
	);
	$portfolio_args   = array(
		'labels'             => $portfolio_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'portfolio' ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'has_archive'        => false,
		'menu_position'      => 25,
		'menu_icon'          => 'dashicons-images-alt2',
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'revisions' ),
		'show_in_rest'       => true,
	);
	register_post_type( 'portfolio', $portfolio_args );

	/* register post statuses for demands and offers */
	register_post_status(
		'active',
		array(
			'label'                     => _x( 'Активно', 'dao-consensus' ),
			'post_type'                 => array( 'demands', 'offers' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Активно <span class="count">(%s)</span>', 'Активно <span class="count">(%s)</span>' ),
		)
	);

	register_post_status(
		'inactive',
		array(
			'label'                     => _x( 'Неактивно', 'dao-consensus' ),
			'post_type'                 => array( 'demands', 'offers' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Неактивно <span class="count">(%s)</span>', 'Неактивно <span class="count">(%s)</span>' ),
		)
	);

	register_post_status(
		'in-process',
		array(
			'label'                     => _x( 'В работе', 'dao-consensus' ),
			'post_type'                 => array( 'demands', 'offers', 'transactions' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'В работе <span class="count">(%s)</span>', 'В работе <span class="count">(%s)</span>' ),
		)
	);

	register_post_status(
		'completed',
		array(
			'label'                     => _x( 'Завершено', 'dao-consensus' ),
			'post_type'                 => array( 'demands', 'offers', 'transactions' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Завершено <span class="count">(%s)</span>', 'Завершено <span class="count">(%s)</span>' ),
		)
	);

	/* register post statuses for meetings */
	register_post_status(
		'waiting',
		array(
			'label'                     => _x( 'В ожидании ответа', 'dao-consensus' ),
			'post_type'                 => array( 'meetings' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'В ожидании ответа <span class="count">(%s)</span>', 'В ожидании ответа <span class="count">(%s)</span>' ),
		)
	);

	register_post_status(
		'reschedule',
		array(
			'label'                     => _x( 'Перенос', 'dao-consensus' ),
			'post_type'                 => array( 'meetings' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Перенос <span class="count">(%s)</span>', 'Перенос <span class="count">(%s)</span>' ),
		)
	);

	register_post_status(
		'accepted',
		array(
			'label'                     => _x( 'Принято', 'dao-consensus' ),
			'post_type'                 => array( 'meetings' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Принято <span class="count">(%s)</span>', 'Принято <span class="count">(%s)</span>' ),
		)
	);

	register_post_status(
		'declined',
		array(
			'label'                     => _x( 'Отклонено', 'dao-consensus' ),
			'post_type'                 => array( 'meetings' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Отклонено <span class="count">(%s)</span>', 'Отклонено <span class="count">(%s)</span>' ),
		)
	);

	register_post_status(
		'canceled',
		array(
			'label'                     => _x( 'Отменено', 'dao-consensus' ),
			'post_type'                 => array( 'meetings' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Отменено <span class="count">(%s)</span>', 'Отменено <span class="count">(%s)</span>' ),
		)
	);

	register_post_status(
		'went',
		array(
			'label'                     => _x( 'Прошла', 'dao-consensus' ),
			'post_type'                 => array( 'meetings' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Прошла <span class="count">(%s)</span>', 'Прошла <span class="count">(%s)</span>' ),
		)
	);

}
add_action( 'init', 'dao_consensus_post_types_init' );

/* Prevent adding new terms to formats taxonomy for non-administrator */
add_action( 'pre_insert_term', 'dao_consensus_prevent_terms', 1, 2 );
function dao_consensus_prevent_terms( $term, $taxonomy ) {
	if ( 'formats' === $taxonomy && ! current_user_can( 'activate_plugins' ) ) {
		return new WP_Error( 'term_addition_blocked', __( 'You cannot add terms to this taxonomy' ) );
	}
	return $term;
}

/* add custom post statuses to quick edit menu */
function dao_consensus_status_into_inline_edit() {
	global $post;
	
	if ( empty( $post ) ) {
		return;
	}

	if ( $post->post_type === 'demands' || $post->post_type === 'offers' ) {
		echo "<script>
		jQuery(document).ready( function() {
			jQuery( 'select[name=\"_status\"]' ).prepend( '<option value=\"active\">Активно</option><option value=\"inactive\">Неактивно</option><option value=\"in-process\">В работе</option><option value=\"completed\">Завершено</option>' );
		});
		</script>";
	}

	if ( $post->post_type === 'meetings' ) {
		echo "<script>
		jQuery(document).ready( function() {
			jQuery( 'select[name=\"_status\"]' ).prepend( '<option value=\"waiting\">В ожидании ответа</option><option value=\"reschedule\">Перенос</option><option value=\"accepted\">Принято</option><option value=\"declined\">Отклонено</option><option value=\"canceled\">Отменено</option><option value=\"went\">Прошла</option>' );
		});
		</script>";
	}

	if ( $post->post_type === 'transactions' ) {
		echo "<script>
		jQuery(document).ready( function() {
			jQuery( 'select[name=\"_status\"]' ).prepend( '<option value=\"in-process\">В работе</option><option value=\"completed\">Завершено</option>' );
		});
		</script>";
	}
}
add_action( 'admin_footer-edit.php', 'dao_consensus_status_into_inline_edit' );

/* display post status beside post title */
function dao_consensus_display_status_label( $statuses ) {
	global $post;
	if ( get_query_var( 'post_status' ) !== 'active' ) {
		if ( $post->post_status == 'active' ) {
			return array( 'Активно' );
		}
	}
	if ( get_query_var( 'post_status' ) !== 'inactive' ) {
		if ( $post->post_status == 'inactive' ) {
			return array( 'Неактивно' );
		}
	}
	if ( get_query_var( 'post_status' ) !== 'in-process' ) {
		if ( $post->post_status == 'in-process' ) {
			return array( 'В работе' );
		}
	}
	if ( get_query_var( 'post_status' ) !== 'completed' ) {
		if ( $post->post_status == 'completed' ) {
			return array( 'Завершено' );
		}
	}
	if ( get_query_var( 'post_status' ) !== 'waiting' ) {
		if ( $post->post_status == 'waiting' ) {
			return array( 'В ожидании ответа' );
		}
	}
	if ( get_query_var( 'post_status' ) !== 'reschedule' ) {
		if ( $post->post_status == 'reschedule' ) {
			return array( 'Перенос' );
		}
	}
	if ( get_query_var( 'post_status' ) !== 'accepted' ) {
		if ( $post->post_status == 'accepted' ) {
			return array( 'Принято' );
		}
	}
	if ( get_query_var( 'post_status' ) !== 'declined' ) {
		if ( $post->post_status == 'declined' ) {
			return array( 'Отклонено' );
		}
	}
	if ( get_query_var( 'post_status' ) !== 'canceled' ) {
		if ( $post->post_status == 'canceled' ) {
			return array( 'Отменено' );
		}
	}
	if ( get_query_var( 'post_status' ) !== 'went' ) {
		if ( $post->post_status == 'went' ) {
			return array( 'Прошла' );
		}
	}
	return $statuses; // returning the array with default statuses
}
add_filter( 'display_post_states', 'dao_consensus_display_status_label' );

/* when post is published set 'active' status */
function dao_consensus_restoring_status_saved( $data, $postarr ) {
	if ( ! isset( $postarr['ID'] ) || ! $postarr['ID'] ) {
		return $data;
	}

	if ( $postarr['post_type'] === 'demands' || $postarr['post_type'] === 'offers' ) {
		$old = get_post( $postarr['ID'] );

		if (
			$old->post_status !== 'trash' &&
			$data['post_status'] === 'publish'
		) {
			if ( $old->post_status === 'pending' || $old->post_status === 'publish' ) {
				$data['post_status'] = 'active';
			} else {
				$data['post_status'] = $old->post_status;
			}
		}
	}

	if ( $postarr['post_type'] === 'transactions' ) {
		$old = get_post( $postarr['ID'] );

		if (
			$old->post_status !== 'trash' &&
			$data['post_status'] === 'publish'
		) {
			if ( $old->post_status === 'publish' ) {
				$data['post_status'] = 'completed';
			} else {
				$data['post_status'] = $old->post_status;
			}
		}
	}

	if ( $postarr['post_type'] === 'meetings' ) {
		$old = get_post( $postarr['ID'] );

		if (
			$old->post_status !== 'trash' &&
			$data['post_status'] === 'publish'
		) {
			if ( $old->post_status === 'publish' ) {
				$data['post_status'] = 'waiting';
			} else {
				$data['post_status'] = $old->post_status;
			}
		}
	}

	return $data;
}
add_filter( 'wp_insert_post_data', 'dao_consensus_restoring_status_saved', 20, 2 );

/* add custom post statuses to page-new status&visibility*/
function dao_consensus_status_add_in_post_page() {
	global $post;

	if ( $post->post_type === 'demands' || $post->post_type === 'offers' ) {
		echo "<script>
		jQuery(document).ready( function() {
			jQuery( 'select[name=\"post_status\"]' ).prepend( '<option value=\"active\">Активно</option><option value=\"inactive\">Неактивно</option><option value=\"in-process\">В работе</option><option value=\"completed\">Завершено</option>' );
		});
		</script>";
	}

	if ( $post->post_type === 'meetings' ) {
		echo "<script>
		jQuery(document).ready( function() {
			jQuery( 'select[name=\"post_status\"]' ).prepend( '<option value=\"waiting\">В ожидании ответа</option><option value=\"reschedule\">Перенос</option><option value=\"accepted\">Принято</option><option value=\"declined\">Отклонено</option>' );
		});
		</script>";
	}
}
add_action( 'admin_footer-post.php', 'dao_consensus_status_add_in_post_page' );
add_action( 'admin_footer-post-new.php', 'dao_consensus_status_add_in_post_page' );

/*
* whenever a demand or offer is created or updated
* check field 'skills' and add unknown ones to common list
*/
function dao_check_skills_field( $post_id, $post, $update ) {

	$status = get_post_status( $post_id );
	if ( $status === 'draft' ) {
		return;
	}

	$all_skills   = array_map( 'trim', explode( ', ', cmb2_get_option( 'daoc_others', 'all_skills' ) ) );
	$offer_skills = (array) $post->skills;

	$new_skills = array();

	foreach ( $offer_skills as $skill ) {
		if ( ! in_array( $skill, $all_skills ) ) {
			$new_skills[] = $skill;
		}
	}

	if ( ! empty( $new_skills ) ) {
		$str_tmp = implode( ', ', array_merge( $all_skills, $new_skills ) );
		cmb2_update_option( 'daoc_others', 'all_skills', $str_tmp );
	}

}
add_action( 'save_post_demands', 'dao_check_skills_field', 10, 3 );
add_action( 'save_post_offers', 'dao_check_skills_field', 10, 3 );

/* set counters of pending posts to admin menu label for CPTs */

function dao_consensus_get_pending_items( $post_type ) {
	global $wpdb;
	$pending_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = %s AND post_status = 'pending'", $post_type ) );
	return (int) $pending_count;
}

function notification_bubble_in_demands_menu() {
	global $menu;
	$pending_items = dao_consensus_get_pending_items( 'demands' );
	$menu[21][0]  .= $pending_items ? " <span class='update-plugins count-1' title='title'><span class='update-count'>$pending_items</span></span>" : '';
}
function notification_bubble_in_offers_menu() {
	 global $menu;
	$pending_items = dao_consensus_get_pending_items( 'offers' );
	$menu[22][0]  .= $pending_items ? " <span class='update-plugins count-1' title='title'><span class='update-count'>$pending_items</span></span>" : '';
}
function notification_bubble_in_portfolio_menu() {
	global $menu;
	$pending_items = dao_consensus_get_pending_items( 'portfolio' );
	$menu[27][0]  .= $pending_items ? " <span class='update-plugins count-1' title='title'><span class='update-count'>$pending_items</span></span>" : '';
}
add_action( 'admin_menu', 'notification_bubble_in_demands_menu' );
add_action( 'admin_menu', 'notification_bubble_in_offers_menu' );
add_action( 'admin_menu', 'notification_bubble_in_portfolio_menu' );

/**
 * Add custom fields CPTs (offers, demands, transactions, meetings)
 */
add_action( 'cmb2_admin_init', 'dao_consensus_metaboxes_for_cpts' );
function dao_consensus_metaboxes_for_cpts() {
	$demand_info = new_cmb2_box(
		array(
			'id'           => 'demand_info_metabox',
			'title'        => __( 'Информация о спросе', 'dao-consensus' ),
			'object_types' => 'demands', // Post type
		)
	);

	/* thumbnail */
	$demand_info->add_field(
		array(
			'name'         => __( 'Изображения\Видео', 'dao-consensus' ),
			'id'           => 'media_files',
			'type'         => 'file_list',
			'preview_size' => array( 100, 100 ),
		)
	);

	/* total price */
	$demand_info->add_field(
		array(
			'name' => __( 'Стоимость', 'dao-consensus' ),
			'id'   => 'total_price',
			'type' => 'text',
		)
	);

	/* cryptocurrency */
	$demand_info->add_field(
		array(
			'name'             => __( 'Формат расчёта', 'dao-consensus' ),
			'id'               => 'cryptocurrency',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => DAO_CONSENSUS::cryptocurrencies,
		)
	);

	/* deadline */
	$demand_info->add_field(
		array(
			'name' => __( 'Срок выполнения', 'dao-consensus' ),
			'id'   => 'deadline',
			'type' => 'text',
		)
	);

	/* deadline period */
	$demand_info->add_field(
		array(
			'name'             => __( 'Период', 'dao-consensus' ),
			'id'               => 'deadline_period',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => DAO_CONSENSUS::deadline_periods,
		)
	);

	/* skills */
	$skills = array_map( 'trim', explode( ', ', cmb2_get_option( 'daoc_others', 'all_skills' ) ) );
	$demand_info->add_field(
		array(
			'name'       => __( 'Навыки', 'dao-consensus' ),
			'id'         => 'skills',
			'desc'       => '',
			'type'       => 'pw_multiselect',
			'options'    => array_combine( $skills, $skills ),
			'attributes' => array(
				'data-placeholder'              => 'Выберите навыки',
				'data-maximum-selection-length' => '20',
				'data-tags'                     => 'true',
			),
		)
	);

	/* result of the query is used by demand and transaction */
	$the_query = get_users(
		array(
			'role'   => 'contributor',
			'fields' => array(
				'ID',
				'display_name',
			),
		)
	);
	$users     = array();
	foreach ( $the_query as $user ) {
		$users[ $user->{'ID'} ] = $user->{'display_name'};
	}

	/* performer */
	$demand_info->add_field(
		array(
			'name'    => __( 'Исполнитель', 'dao-consensus' ),
			'id'      => 'performer',
			'type'    => 'pw_select',
			'desc'    => __( 'Выберите исполнителя', 'dao-consensus' ),
			'options' => $users,
		)
	);

	$offer_info = new_cmb2_box(
		array(
			'id'           => 'offer_info_metabox',
			'title'        => __( 'Информация о предложении', 'dao-consensus' ),
			'object_types' => 'offers', // Post type
		)
	);

	/* thumbnail */
	$offer_info->add_field(
		array(
			'name'         => __( 'Изображения\Видео', 'dao-consensus' ),
			'id'           => 'media_files',
			'type'         => 'file_list',
			'preview_size' => array( 100, 100 ),
		)
	);

	/* total price */
	$offer_info->add_field(
		array(
			'name' => __( 'Стоимость', 'dao-consensus' ),
			'id'   => 'total_price',
			'type' => 'text',
		)
	);

	/* cryptocurrency */
	$offer_info->add_field(
		array(
			'name'             => __( 'Формат расчёта', 'dao-consensus' ),
			'id'               => 'cryptocurrency',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => DAO_CONSENSUS::cryptocurrencies,
		)
	);

	/* deadline */
	$offer_info->add_field(
		array(
			'name' => __( 'Срок выполнения', 'dao-consensus' ),
			'id'   => 'deadline',
			'type' => 'text',
		)
	);

	/* deadline period */
	$offer_info->add_field(
		array(
			'name'             => __( 'Период', 'dao-consensus' ),
			'id'               => 'deadline_period',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => DAO_CONSENSUS::deadline_periods,
		)
	);

	/* customers */
	$offer_info->add_field(
		array(
			'id'         => 'customers',
			'type'       => 'hidden',
		)
	);

	/* skills */
	$skills = array_map( 'trim', explode( ', ', cmb2_get_option( 'daoc_others', 'all_skills' ) ) );
	$offer_info->add_field(
		array(
			'name'       => __( 'Навыки', 'dao-consensus' ),
			'id'         => 'skills',
			'desc'       => '',
			'type'       => 'pw_multiselect',
			'options'    => array_combine( $skills, $skills ),
			'attributes' => array(
				'data-placeholder'              => 'Выберите навыки',
				'data-maximum-selection-length' => '20',
				'data-tags'                     => 'true',
			),
		)
	);

	/**
	* Transactions fields
	*/
	$transactions_info = new_cmb2_box(
		array(
			'id'           => 'transaction_metabox',
			'title'        => __( 'Информация о сделке', 'dao-consensus' ),
			'object_types' => array( 'transactions' ), // Post type
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true, // Show field names on the left
		)
	);

	$transactions_info->add_field(
		array(
			'name'    => __( 'Инициатор', 'dao-consensus' ),
			'id'      => 'initiator',
			'type'    => 'pw_select',
			'desc'    => __( 'Выберите пользователя', 'dao-consensus' ),
			'options' => $users,
		)
	);

	$transactions_info->add_field(
		array(
			'name' => __( 'Стоимость сделки', 'dao-consensus' ),
			'id'   => 'total_price',
			'type' => 'text',
		)
	);

	$transactions_info->add_field(
		array(
			'name'             => __( 'Формат расчёта', 'dao-consensus' ),
			'id'               => 'cryptocurrency',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => 'none',
			'options'          => DAO_CONSENSUS::cryptocurrencies,
		)
	);

	$transactions_info->add_field(
		array(
			'name'       => __( 'Спрос\Предложение', 'dao-consensus' ),
			'id'         => 'card_id',
			'type'       => 'post_search_ajax',
			// Optional :
			'limit'      => 1,       // Limit selection to X items only (default 1)
			'sortable'   => false,   // Allow selected items to be sortable (default false)
			'query_args' => array(
				'post_type'      => array( 'offers', 'demands' ),
				'post_status'    => array( 'active', 'inactive' ),
				'posts_per_page' => -1,
			),
		)
	);

	// hidden meta data
	$transactions_info->add_field(
		array(
			'id'      => 'deal_person',
			'type'    => 'hidden',
		)
	);
	$transactions_info->add_field(
		array(
			'id'      => 'initiator_testimonial',
			'type'    => 'hidden',
		)
	);
	$transactions_info->add_field(
		array(
			'id'      => 'deal_person_testimonial',
			'type'    => 'hidden',
		)
	);

	/**
	* Meetings fields
	*/
	$meetings_info = new_cmb2_box(
		array(
			'id'           => 'meeting_metabox',
			'title'        => __( 'Информация о встрече', 'dao-consensus' ),
			'object_types' => array( 'meetings' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$meetings_info->add_field(
		array(
			'name' => __( 'Имя пригласителя', 'dao-consensus' ),
			'id'   => 'invitor_name',
			'type' => 'text',
		)
	);

	$meetings_info->add_field(
		array(
			'name' => __( 'Имя приглашенного', 'dao-consensus' ),
			'id'   => 'invited_name',
			'type' => 'text',
		)
	);

	$meetings_info->add_field(
		array(
			'name' => __( 'Место встречи', 'dao-consensus' ),
			'id'   => 'venue',
			'type' => 'text',
		)
	);

	$meetings_info->add_field(
		array(
			'name' => __( 'Дата и время встречи', 'dao-consensus' ),
			'id'   => 'datetime',
			'type' => 'text_datetime_timestamp',
		)
	);

	$the_query = get_users(
		array(
			'role'   => 'contributor',
			'fields' => array(
				'ID',
				'display_name',
			),
		)
	);
	$users     = array();
	foreach ( $the_query as $user ) {
		$users[ $user->{'ID'} ] = $user->{'display_name'};
	}
	$meetings_info->add_field(
		array(
			'name'    => __( 'Приглашенный', 'dao-consensus' ),
			'id'      => 'invited_id',
			'type'    => 'pw_select',
			'desc'    => __( 'Выберите приглашенного пользователя', 'dao-consensus' ),
			'options' => $users,
		)
	);
	
	$meetings_info->add_field(
		array(
			'name'       => __( 'Спрос\Предложение', 'dao-consensus' ),
			'id'         => 'card_id',
			'type'       => 'post_search_ajax',
			// Optional :
			'limit'      => 1,       // Limit selection to X items only (default 1)
			'sortable'   => false,   // Allow selected items to be sortable (default false)
			'query_args' => array(
				'post_type'      => array( 'offers', 'demands' ),
				'post_status'    => array( 'active', 'inactive', 'in-process' ),
				'posts_per_page' => -1,
			),
		)
	);

	$meetings_info->add_field( array(
		'name'    => 'Результат',
		'id'      => 'result',
		'type'    => 'radio_inline',
		'options' => array(
			'' => __( 'В ожидании', 'dao-consensus' ),
			'success'   => __( 'Успешно', 'dao-consensus' ),
			'failure'     => __( 'Не успешно', 'dao-consensus' ),
		),
		'default' => '',
	) );

	/* meta fields for rescheduling */
	$meetings_info->add_field(
		array(
			'id'   => 'old_datetime',
			'type' => 'hidden',
		)
	);
	$meetings_info->add_field(
		array(
			'id'   => 'reschedule_initiator',
			'type' => 'hidden',
		)
	);

	/* register custom fields for user profile page */
	$cmb_user = new_cmb2_box(
		array(
			'id'               => 'user-edit',
			'title'            => esc_html__( 'Доп. инфо о пользователе', 'dao-consensus' ), // Doesn't output for user boxes
			'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta vs post_meta
			'show_names'       => true,
			'show_on_roles'    => array( 'contributor', 'editor', 'community_manager', 'administrator' ),
			'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
		)
	);

	$cmb_user->add_field(
		array(
			'name'             => 'Общий статус',
			'id'               => 'person_type',
			'type'             => 'radio_inline',
			'show_option_none' => false,
			'options'          => array(
				'physical'  => __( 'Физ. лицо', 'dao-consensus' ),
				'juridical' => __( 'Юр. лицо', 'dao-consensus' ),
			),
			'default'          => 'physical',
		)
	);

	$cmb_user->add_field(
		array(
			'name' => 'Специализация',
			'id'   => 'specialization',
			'type' => 'text',
		)
	);

	$cmb_user->add_field(
		array(
			'name' => esc_html__( 'Twitter', 'dao-consensus' ),
			'id'   => 'twitter',
			'type' => 'text',
		)
	);

	$cmb_user->add_field(
		array(
			'name' => esc_html__( 'Instagram', 'dao-consensus' ),
			'id'   => 'instagram',
			'type' => 'text',
		)
	);

	/* skills */
	$skills = array_map( 'trim', explode( ', ', cmb2_get_option( 'daoc_others', 'all_skills' ) ) );
	$cmb_user->add_field(
		array(
			'name'       => __( 'Навыки', 'dao-consensus' ),
			'id'         => 'skills',
			'desc'       => '',
			'type'       => 'pw_multiselect',
			'options'    => array_combine( $skills, $skills ),
			'attributes' => array(
				'data-placeholder'              => 'Выберите навыки',
				'data-maximum-selection-length' => '20',
				'data-tags'                     => 'true',
			),
		)
	);

	$cmb_user->add_field(
		array(
			'name'         => __( 'Портфолио', 'dao-consensus' ),
			'id'           => 'portfolio',
			'type'         => 'file_list',
			'preview_size' => array( 100, 100 ),
		)
	);

	/* rating */
	$cmb_user->add_field( array(
		'name' => __('Рейтинг', 'dao-consensus'),
		'type' => 'text',
		'id'   => 'rating',
		'default' => '0',
		'attributes' => array(
			'readonly' => 'readonly',
			'type' => 'number',
			'pattern' => '\d*',
		),
		'sanitization_cb' => 'absint',
		'escape_cb'       => 'absint',
	) );

	/* completed meetings */
	$cmb_user->add_field( array(
		'name' => __('К-во проведённых встреч', 'dao-consensus'),
		'type' => 'text',
		'id'   => 'completed_meetings',
		'default' => '0',
		'attributes' => array(
			'readonly' => 'readonly',
			'type' => 'number',
			'pattern' => '\d*',
		),
		'sanitization_cb' => 'absint',
		'escape_cb'       => 'absint',
	) );

	/* completed transactions */
	$cmb_user->add_field( array(
		'name' => __('К-во совершённых сделок', 'dao-consensus'),
		'type' => 'text',
		'id'   => 'completed_transactions',
		'default' => '0',
		'attributes' => array(
			'readonly' => 'readonly',
			'type' => 'number',
			'pattern' => '\d*',
		),
		'sanitization_cb' => 'absint',
		'escape_cb'       => 'absint',
	) );

	$cmb_user->add_field( array(
		'id'   => 'favourites',
		'type' => 'hidden',
	) );

	/* register custom fields for company info */
	$cmb_user_company = new_cmb2_box(
		array(
			'id'               => 'user-edit',
			'title'            => esc_html__( 'Информация о компании', 'dao-consensus' ), // Doesn't output for user boxes
			'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta vs post_meta
			'show_names'       => true,
			'show_on_roles'    => array( 'contributor', 'editor', 'community_manager', 'administrator' ),
			'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
		)
	);

	$cmb_user_company->add_field(
		array(
			'name' => esc_html__( 'Название', 'dao-consensus' ),
			'id'   => 'company_name',
			'type' => 'text',
		)
	);

	$category_terms = get_terms(
		array(
			'taxonomy'   => 'categories',
			'hide_empty' => false,
		)
	);
	$categories     = array();
	foreach ( $category_terms as $cat ) {
		$categories[ $cat->slug ] = $cat->name;
	}

	$cmb_user_company->add_field(
		array(
			'name'             => esc_html__( 'Категория деятельности', 'dao-consensus' ),
			'id'               => 'company_workarea',
			'type'             => 'pw_select',
			'show_option_none' => true,
			'options'          => $categories,
		)
	);

	$cmb_user_company->add_field(
		array(
			'name' => esc_html__( 'Юридический адрес', 'dao-consensus' ),
			'id'   => 'company_juraddress',
			'type' => 'text',
		)
	);

	$cmb_user_company->add_field(
		array(
			'name' => esc_html__( 'Номер телефона', 'dao-consensus' ),
			'id'   => 'company_phone',
			'type' => 'text',
		)
	);

	$cmb_user_company->add_field(
		array(
			'name' => esc_html__( 'Email', 'dao-consensus' ),
			'id'   => 'company_email',
			'type' => 'text_email',
		)
	);

	$cmb_user_company->add_field(
		array(
			'name'      => esc_html__( 'Сайт компании', 'dao-consensus' ),
			'id'        => 'company_website',
			'type'      => 'text_url',
			'protocols' => array( 'http', 'https' ),
		)
	);

	/**
	* Portfolio fields
	*/
	$portfolio_info = new_cmb2_box(
		array(
			'id'           => 'portfolio_metabox',
			'title'        => __( 'Информация о работе', 'dao-consensus' ),
			'object_types' => array( 'portfolio' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$portfolio_info->add_field(
		array(
			'name'      => esc_html__( 'Медиа файлы', 'dao-consensus' ),
			'id'        => 'media_files',
			'type'      => 'file_list',
			'preview_size' => array( 100, 100 ),
		)
	);
}

/**
 * Add custom columns
 */

/* demands and offers */
add_filter( 'manage_offers_posts_columns', 'dao_cons_offers_custom_admin_columns' );
function dao_cons_offers_custom_admin_columns( $columns ) {
	// Remove Date
	unset( $columns['date'] );

	$columns['total_price']    = 'Стоимость';
	$columns['cryptocurrency'] = 'Формат расчёта';
	$columns['customers'] 	   = 'Заказчики';
	$columns['date']           = __( 'Date' );

	return $columns;
}
add_filter( 'manage_demands_posts_columns', 'dao_cons_demands_custom_admin_columns' );
function dao_cons_demands_custom_admin_columns( $columns ) {
	// Remove Date
	unset( $columns['date'] );

	$columns['total_price']    = 'Стоимость';
	$columns['cryptocurrency'] = 'Формат расчёта';
	$columns['performer']      = 'Исполнитель';
	$columns['date']           = __( 'Date' );

	return $columns;
}

add_action( 'manage_offers_posts_custom_column', 'dao_cons_fill_offers_custom_admin_columns', 10, 2 );
function dao_cons_fill_offers_custom_admin_columns( $column_key, $post_id ) {
	if ( $column_key == 'total_price' ) {
		echo get_post_meta( $post_id, 'total_price', true );
	}
	if ( $column_key == 'cryptocurrency' ) {
		echo DAO_CONSENSUS::cryptocurrencies[ get_post_meta( $post_id, 'cryptocurrency', true ) ];
	}
	if ( $column_key == 'customers' ) {
		$customers = get_post_meta( $post_id, 'customers', true );
		print_r( $customers );
		if ( ! empty( $customers ) ) {
			$links = array();
			foreach ( $customers as $id => $transaction_id ) {
				$links[] = sprintf( '<a href="%1$s">%2$s</a>', get_author_posts_url( $id ), dao_get_user_display_name( $id ) );
			}
			echo implode( ', ', $links );
		} else {
			echo '-';
		}
	}
}
add_action( 'manage_demands_posts_custom_column', 'dao_cons_fill_demands_custom_admin_columns', 10, 2 );
function dao_cons_fill_demands_custom_admin_columns( $column_key, $post_id ) {
	if ( $column_key == 'total_price' ) {
		echo get_post_meta( $post_id, 'total_price', true );
	}
	if ( $column_key == 'cryptocurrency' ) {
		echo DAO_CONSENSUS::cryptocurrencies[ get_post_meta( $post_id, 'cryptocurrency', true ) ];
	}
	if ( $column_key === 'performer' ) {
		$id = get_post_meta( $post_id, 'performer', true );

		if ( $id ) {
			$performer_name = dao_get_user_display_name( $id );
			$performer_link = get_author_posts_url( $id );
	
			printf( '<a href="%1$s">%2$s</a>', $performer_link, $performer_name );	
		} else {
			echo '-';
		}
	}
}

/* transactions */
add_filter( 'manage_transactions_posts_columns', 'dao_cons_transactions_custom_admin_columns' );
function dao_cons_transactions_custom_admin_columns( $columns ) {
	// Remove Date
	unset( $columns['date'] );

	$columns['initiator'] = __('Инициатор', 'dao-consensus');
	$columns['card_id'] = __('Спрос\Предложение', 'dao-consensus');
	$columns['total_price'] = __('Стоимость', 'dao-consensus');
	$columns['cryptocurrency'] = __('Формат расчёта', 'dao-consensus');
	$columns['date'] = __( 'Date' );

	return $columns;
}

add_action( 'manage_transactions_posts_custom_column', 'dao_cons_fill_transactions_custom_admin_columns', 10, 2 );
function dao_cons_fill_transactions_custom_admin_columns( $column_key, $post_id ) {
	if ( $column_key == 'initiator' ) {
		$initiator = get_post_meta( $post_id, 'initiator', true );
		printf('<a href="%s">%s</a>', get_author_posts_url( $initiator ), dao_get_user_display_name( $initiator ) );
	}
	if ( $column_key == 'card_id' ) {
		$card_id = get_post_meta( $post_id, 'card_id', true );
		printf('<a href="%s">%s</a>', get_permalink( $card_id ), get_the_title( $card_id ));
	}
	if ( $column_key == 'total_price' ) {
		echo get_post_meta( $post_id, 'total_price', true );
	}
	if ( $column_key == 'cryptocurrency' ) {
		echo DAO_CONSENSUS::cryptocurrencies[get_post_meta( $post_id, 'cryptocurrency', true )];
	}
}

/* users */
add_filter( 'manage_users_columns', 'dao_cons_users_custom_admin_columns' );
function dao_cons_users_custom_admin_columns( $columns ) {
    $columns['rating'] = __('Рейтинг', 'dao-consensus');
    return $columns;
}

add_filter( 'manage_users_custom_column', 'dao_cons_users_fill_custom_admin_columns', 10, 3 );
function dao_cons_users_fill_custom_admin_columns( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'rating' :
            return get_user_meta( $user_id, 'rating', true );
        default:
    }
    return $val;
}

/* ------------------- meetings ----------------- */
add_filter(
	'manage_meetings_posts_columns',
	function ( $columns ) {
		// Remove Date
		unset( $columns['date'] );

		$columns['post-card'] = 'Карточка';
		$columns['datetime']   = 'Дата и время';
		$columns['invited_id'] = 'Приглашенный';
		$columns['result']     = 'Результат';
		$columns['date']       = __( 'Date' );

		return $columns;
	}
);

add_action(
	'manage_meetings_posts_custom_column',
	function ( $column_key, $post_id ) {
		if ( $column_key === 'invited_id' ) {
			$user_id = get_post_meta( $post_id, 'invited_id', true );
			$user    = get_userdata( $user_id );

			printf( "<a href='%s'>%s</a>", get_author_posts_url( $user_id ), $user->display_name );
		} elseif ( $column_key === 'post-card' ) {
			$card_id = get_post_meta( $post_id, 'card_id', true );
			if ( $card_id ) {
				$card_title = get_the_title( $card_id );
				$card_link = get_permalink( $card_id );
	
				printf( "<a href='%s'>%s</a>", $card_link, $card_title );	
			} else {
				echo "-";
			}
		} elseif ( $column_key === 'datetime' ) {
			echo date( 'd.m.Y H:i', get_post_meta( $post_id, 'datetime', true ) );
		} elseif ( $column_key === 'result' ) {
			$result = get_post_meta( $post_id, 'result', true );
			if ( $result === 'success' ) {
				printf( '<span style="background-color: green;">%s</span>', 'Успешно' );
			} elseif ( $result === 'failure' ) {
				printf( '<span style="background-color: red;">%s</span>', 'Не успешно' );
			} else {
				_e( 'В ожидании', 'dao-consensus' );
			}
		}
	},
	10,
	2
);

/** --------- CUSTOMIZE ARCHIVE PAGEs FOR CPTS --------- */
function dao_consensus_archive_per_page( $query ) {
	if ( $query->is_archive() && $query->is_main_query() && ! is_admin() && ! is_author() ) {
		if ( 'demands' === $query->query_vars['post_type'] || 'offers' === $query->query_vars['post_type'] ) {
			$query->set( 'posts_per_page', 20 );
		} elseif ( 'transactions' === $query->query_vars['post_type'] ) {
			$query->set( 'posts_per_page', 2 );

			$meta_query = (array) $query->get('meta_query');

			$meta_query[] = array(
				'key'     => 'initiator_testimonial',
				'value'   => '',
				'compare' => '!=',
			);

			$meta_query[] = array(
				'key'     => 'deal_person_testimonial',
				'value'   => '',
				'compare' => '!=',
			);    
	
			// Set the meta query to the complete, altered query
			$query->set('meta_query',$meta_query);
		}
	}

	if ( $query->is_archive() && $query->is_main_query() && ! empty( $_GET['category'] ) ) {
		$category = sanitize_text_field( $_GET['category'] );

		$taxquery = array(
			array(
				'taxonomy' => 'categories',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);

		$query->set( 'tax_query', $taxquery );
	}

	return $query;
}
add_filter( 'pre_get_posts', 'dao_consensus_archive_per_page' );

add_filter( 'navigation_markup_template', 'dao_consensus_navigation_template', 10, 2 );
function dao_consensus_navigation_template( $template, $class ) {
	return '
	<nav class="navigation lp-pagination %1$s" role="navigation">
		<div class="nav-links">%3$s</div>
	</nav>
	';
}

add_action( 'wp', 'dao_update_online_users_status' );
function dao_update_online_users_status() {
	if ( is_user_logged_in() ) {
		if ( ( $logged_in_users = get_transient( 'users_online' ) ) === false ) {
			$logged_in_users = array();
		}

		$current_user = wp_get_current_user();
		$current_user = $current_user->ID;
		$current_time = current_time( 'timestamp' );

		if ( ! isset( $logged_in_users[ $current_user ] ) || ( $logged_in_users[ $current_user ] < ( $current_time - ( 15 * 60 ) ) ) ) {
			$logged_in_users[ $current_user ] = $current_time;
			set_transient( 'users_online', $logged_in_users, 30 * 60 );
		}
	}
}

/**
 * ------------------- REDIRECTION RULES ------------------- //
 */ 

add_action( 'template_redirect', 'dao_cons_redirection_rules' );
function dao_cons_redirection_rules() {

	if ( is_admin() ) {
		$user = wp_get_current_user();
		if ( in_array( 'contributor', (array) $user->roles ) ) {
			wp_safe_redirect( home_url( '/profile/' ) );
		}
	}

	if ( is_post_type_archive( 'demands' ) || is_post_type_archive( 'offers' ) ) {
		if ( isset( $_GET['category'] ) && ! empty( $_GET['category'] ) ) {
			$category = sanitize_text_field( $_GET['category'] );

			if ( ! term_exists( $category, 'categories' ) ) {
				if ( is_post_type_archive( 'demands' ) ) {
					wp_safe_redirect( get_post_type_archive_link( 'demands' ) );
				} else {
					wp_safe_redirect( get_post_type_archive_link( 'offers' ) );
				}
			}
		}
	}

	if ( is_post_type_archive( 'transactions' ) ) {
		if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
			if ( ! intval( $_GET['id'] ) ) {
				wp_safe_redirect( home_url() );
			}

			if ( ! get_post_status( $_GET['id'] ) ) {
				wp_safe_redirect( home_url() );
			}

			if ( ! get_post_meta( $_GET['id'], 'initiator_testimonial', true ) || ! get_post_meta( $_GET['id'], 'deal_person_testimonial', true ) ) {
				wp_safe_redirect( home_url() );
			}
		}
	}

	if ( is_singular( 'offers' ) || is_singular( 'demands' ) ) {
		if ( get_post_status( get_the_ID() ) !== 'active' ) {
			if ( is_user_logged_in() ) {
				global $post;
				$curr_user = wp_get_current_user();
				if ( $curr_user->ID != get_post_field( 'post_author', $post->ID ) ) {
					global $wp_query;
					$wp_query->set_404();
					status_header(404);
				}
			} else {
				global $wp_query;
				$wp_query->set_404();
				status_header(404);
			}
		}
	}

	global $post;
	if ( is_page( 'profile' ) || in_array( 21, get_post_ancestors( $post ) ) ) {
		if ( ! is_user_logged_in() ) {
			wp_safe_redirect( wp_login_url() );
		}

		if ( is_page( 'edit' ) ) {
			/* check if ID is set and not empty */
			if ( ! isset( $_GET['ID'] ) || empty( $_GET['ID'] ) ) {
				wp_safe_redirect( home_url() );
			}

			/* check if value is number */
			if ( ! intval( $_GET['ID'] ) ) {
				wp_safe_redirect( home_url() );
			}

			/* check if post exists */
			if ( ! get_post_status( $_GET['ID'] ) ) {
				wp_safe_redirect( home_url() );
			}

			/* check post type of post by post id */
			$post_type = get_post_type( $_GET['ID'] );
			if ( $post_type !== 'demands' && $post_type !== 'offers' ) {
				wp_safe_redirect( home_url() );
			}

			/* check if editor is the same person with post author */
			if ( (int) get_current_user_id() !== (int) get_post_field( 'post_author', $_GET['ID'] ) ) {
				wp_safe_redirect( home_url() );
			}

		}

		if ( is_page( 'chat' ) ) {
			if ( isset( $_GET['user_id'] ) ) {

				$user_id = $_GET['user_id'];

				/* check if user id is number */
				if ( ! intval( $user_id ) ) {
					wp_safe_redirect( home_url( '/profile/' ) );
				}

				/* check if user exists */
				if ( ! dao_does_user_exists( $user_id ) ) {
					wp_safe_redirect( home_url( '/profile/' ) );
				}

				/* check whether current isn't trying make a deal with yourself */
				$curr_user = wp_get_current_user();
				if ( (int) $curr_user->ID === (int) $user_id ) {
					wp_safe_redirect( home_url( '/profile/chat/' ) );
				}

				if ( isset( $_GET['card_id'] ) ) {

					$card_id = $_GET['card_id'];

					/* check if card id is number */
					if ( ! intval( $card_id ) ) {
						wp_safe_redirect( home_url( '/profile/' ) );
					}

					/* check if card exists */
					if ( ! get_post_status( $card_id ) ) {
						wp_safe_redirect( home_url( '/profile/' ) );
					}

					/* check if user id in url correspond with card author */
					$card_author = (int) get_post_field( 'post_author', $card_id );
					if ( (int) $user_id !== $card_author ) {
						wp_safe_redirect( home_url( '/profile/' ) );
					}
				}
			}
		}

		if ( is_page( 'schedule-meeting' ) ) {
			/* check if ID is set and not empty */
			if ( 
				! isset( $_GET['user_id'] ) || empty( $_GET['user_id'] ) ||
				! isset( $_GET['card_id'] ) || empty( $_GET['card_id'] )
			) {
				wp_safe_redirect( home_url( '/profile/' ) );
			}

			/* check if value is number */
			if ( ! intval( $_GET['user_id'] ) ) {
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
			}

			/* check if user exists */
			if ( ! dao_does_user_exists( $_GET['user_id'] ) ) {
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
			}

			/* check if curr user is not trying send invitation to yourself */
			$curr_user = wp_get_current_user();
			if ( $curr_user->ID == $_GET['user_id'] ) {
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
			}

			/* check if card with such ID exists */
			if ( ! get_post_status( $_GET['card_id'] ) ) {
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
			}

			/* check if passed user_id is the card author */
			$card = get_post( sanitize_text_field( $_GET['card_id'] ) );
			if ( $card->post_author != $_GET['user_id'] ) {
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
			}
		}

		if ( is_page( 'submit-testimonial' ) ) {
			/* transaction_id parameter is required */
			if ( ! isset( $_GET['transaction_id'] ) ) {
				wp_safe_redirect( '/profile/', 301 );
			}

			/* id must be an integer number */
			if ( ! intval( $_GET['transaction_id'] ) ) {
				wp_safe_redirect( '/profile/', 301 );
			}

			/* check transaction record existence */
			if ( ! get_post_status( $_GET['transaction_id'] ) ) {
				wp_safe_redirect( '/profile/', 301 );
			}

			$curr_user = wp_get_current_user();

			$initiator = get_post_meta( $_GET['transaction_id'], 'initiator', true );
			$card_id = get_post_meta( $_GET['transaction_id'], 'card_id', true );
			$card_author = get_post_field( 'post_author', $card_id, true );

			/* check if current user is attached to this transaction */
			if ( ($curr_user->ID != $initiator) && ($curr_user->ID != $card_author) ) {
				wp_safe_redirect( '/profile/', 301 );
			}
		}
	}

	if ( is_page( 'create-demand' ) ) {
		if ( ! is_user_logged_in() ) {
			wp_safe_redirect( home_url() );
		}
	}
}

add_action(
	'init',
	function () {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) && ( current_user_can( 'subscriber' ) || current_user_can( 'contributor' ) ) ) {
			wp_safe_redirect( home_url( '/profile/' ) );
			die;
		}
	}
);

/**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */
function dao_cons_login_redirect( $redirect_to, $request, $user ) {
	 // is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		// check for admins
		if (
			in_array( 'administrator', $user->roles ) ||
			in_array( 'community_manager', $user->roles ) ||
			in_array( 'editor', $user->roles )
		) {
			return $redirect_to;
		} else {
			return home_url( '/profile/' );
		}
	} else {
		return $redirect_to;
	}
}
add_filter( 'login_redirect', 'dao_cons_login_redirect', 10, 3 );

/**
 * Hide admin bar for specific users
 */
function dao_cons_show_admin_bar() {
	$user = wp_get_current_user();
	if ( ! is_user_logged_in() || in_array( 'contributor', (array) $user->roles ) ) {
		return false;
	} else {
		return true;
	}
}
add_filter('show_admin_bar' , 'dao_cons_show_admin_bar');

/*
* --------------------------------------------------------------- *
* ----------------------- THEME SETTINGS ------------------------ *
*---------------------------------------------------------------- *
*/

/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function dao_consensus_register_main_options_metabox() {
	/**
	 * Registers main options page menu item and form.
	 */
	$args = array(
		'id'           => 'daoc_admin_menu',
		'menu_title'   => __( 'DAOC Настройки', 'dao-consensus' ),
		'title'        => __( 'Общие', 'dao-consensus' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'daoc_admin_menu',
		'tab_group'    => 'daoc_admin_menu',
		'tab_title'    => __( 'Общие', 'dao_consensus' ),
		'capability'   => 'unknown',
	);

	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'dao_consensus_options_display_with_tabs';
	}

	$admin_menu = new_cmb2_box( $args );

	$args = array(
		'id'           => 'daoc_primary_options_page',
		'menu_title'   => __( 'Общие', 'dao-consensus' ), // Use menu title, & not title to hide main h2.
		'object_types' => array( 'options-page' ),
		'option_key'   => 'daoc_main_options',
		'parent_slug'  => 'daoc_admin_menu',
		'tab_group'    => 'daoc_main_options',
		'tab_title'    => __( 'Общие', 'dao-consensus' ),
	);

	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'dao_consensus_options_display_with_tabs';
	}

	$main_options = new_cmb2_box( $args );

	/**
	 * Registers secondary options page, and set main item as parent.
	 */
	$args = array(
		'id'           => 'daoc_secondary_options_page',
		'menu_title'   => __( 'Разное', 'dao-consensus' ), // Use menu title, & not title to hide main h2.
		'object_types' => array( 'options-page' ),
		'option_key'   => 'daoc_others',
		'parent_slug'  => 'daoc_admin_menu',
		'tab_group'    => 'daoc_main_options',
		'tab_title'    => __( 'Разное', 'dao-consensus' ),
	);

	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'dao_consensus_options_display_with_tabs';
	}

	$others = new_cmb2_box( $args );

	$others->add_field(
		array(
			'name'    => 'Навыки',
			'desc'    => 'Список зарегистрированных навыков',
			'default' => 'Навыки',
			'id'      => 'all_skills',
			'type'    => 'textarea',
		)
	);
}
add_action( 'cmb2_admin_init', 'dao_consensus_register_main_options_metabox' );

/**
 * A CMB2 options-page display callback override which adds tab navigation among
 * CMB2 options pages which share this same display callback.
 *
 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
 */
function dao_consensus_options_display_with_tabs( $cmb_options ) {
	$tabs = dao_consensus_options_page_tabs( $cmb_options );
	?>
	<div class="wrap cmb2-options-page option-<?php echo $cmb_options->option_key; ?>">
		<?php if ( get_admin_page_title() ) : ?>
			<h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
		<?php endif; ?>
		<h2 class="nav-tab-wrapper">
			<?php foreach ( $tabs as $option_key => $tab_title ) : ?>
				<a class="nav-tab
				<?php
				if ( isset( $_GET['page'] ) && $option_key === $_GET['page'] ) :
					?>
					 nav-tab-active
					 <?php
								 endif;
				?>
				" href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
			<?php endforeach; ?>
		</h2>
		<form class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="<?php echo $cmb_options->cmb->cmb_id; ?>" enctype="multipart/form-data" encoding="multipart/form-data">
			<input type="hidden" name="action" value="<?php echo esc_attr( $cmb_options->option_key ); ?>">
			<?php $cmb_options->options_page_metabox(); ?>
			<?php submit_button( esc_attr( $cmb_options->cmb->prop( 'save_button' ) ), 'primary', 'submit-cmb' ); ?>
		</form>
	</div>
	<?php
}

/**
 * Gets navigation tabs array for CMB2 options pages which share the given
 * display_cb param.
 *
 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
 *
 * @return array Array of tab information.
 */
function dao_consensus_options_page_tabs( $cmb_options ) {
	$tab_group = $cmb_options->cmb->prop( 'tab_group' );
	$tabs      = array();

	foreach ( CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
		if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
			$tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
				? $cmb->prop( 'tab_title' )
				: $cmb->prop( 'title' );
		}
	}

	return $tabs;
}

/**
 * Fire a callback only when demands and offers are transitioned from 'pending' to 'active'.
 *
 * @param string  $new_status New post status.
 * @param string  $old_status Old post status.
 * @param WP_Post $post       Post object.
 */
add_action( 'transition_post_status', 'dao_transition_post_status', 10, 3 );
function dao_transition_post_status( $new_status, $old_status, $post ) {

	if (
		( ( 'publish' === $new_status || 'active' === $new_status ) && 'pending' === $old_status )
		&& ( 'demands' === $post->post_type || 'offers' === $post->post_type )
	) {
		$author = $post->post_author; /* Post author ID. */
		$name   = get_the_author_meta( 'display_name', $author );
		$email  = get_the_author_meta( 'user_email', $author );

		$title     = $post->post_title;
		$permalink = get_permalink( $post->ID );

		$to[]    = sprintf( '%s <%s>', $name, $email );
		$subject = sprintf( 'Модерация DAOC: %s', $title );

		if ( 'offers' === $post->post_type ) {
			$message  = sprintf( 'Поздравляем, %s. Ваша заявка на публикацию предложения "%s" успешно прошла модерацию.' . "\n\n", $name, $title );
			$message .= sprintf( 'Просмотреть: %s', $permalink );
		} else {
			$message  = sprintf( 'Поздравляем, %s. Ваша заявка на публикацию спроса "%s" успешно прошла модерацию.' . "\n\n", $name, $title );
			$message .= sprintf( 'Просмотреть: %s', $permalink );
		}

		$headers[] = '';

		wp_mail( $to, $subject, $message, $headers );
	}

	if (
		(  ( 'publish' === $new_status && 'pending' === $old_status )
		&& 'portfolio' === $post->post_type )
	) {
		$author = $post->post_author;
		$name   = get_the_author_meta( 'display_name', $author );
		$email  = get_the_author_meta( 'user_email', $author );

		$title     = $post->post_title;
		$permalink = get_permalink( $post->ID );

		$to[]    = sprintf( '%s <%s>', $name, $email );
		$subject = sprintf( 'Модерация работы в Портфолио DAOC: %s', $title );

		$message  = sprintf( 'Поздравляем, %s. Ваша заявка на добавление работы "%s" в Портфолио успешно прошла модерацию.' . "\n\n", $name, $title );

		$headers[] = '';

		wp_mail( $to, $subject, $message, $headers );
	}
}

/* notify author when offer or demand is moved to bin */

add_action( 'wp_trash_post', 'dao_move_post_to_trash', 10, 1 );
function dao_move_post_to_trash( $post_id = '' ) {
	// Verify if is trashing multiple posts
	if ( isset( $_GET['post'] ) && is_array( $_GET['post'] ) ) {
		foreach ( $_GET['post'] as $post_id ) {
			$post = get_post( $post_id );

			if (
				'pending' === $post->post_status
				&& ( 'demands' === $post->post_type || 'offers' === $post->post_type )
			) {
				$author = $post->post_author; /* Post author ID. */
				$name   = get_the_author_meta( 'display_name', $author );
				$email  = get_the_author_meta( 'user_email', $author );

				$title = $post->post_title;

				$to[]    = sprintf( '%s <%s>', $name, $email );
				$subject = sprintf( 'Модерация DAOC: %s', $title );

				if ( 'offers' === $post->post_type ) {
					$message = sprintf( 'К сожалению, %s. Ваша заявка на публикацию предложения "%s" не прошла модерацию.', $name, $title );
				} else {
					$message = sprintf( 'К сожалению, %s. Ваша заявка на публикацию спроса "%s" не прошла модерацию.', $name, $title );
				}
				$headers[] = '';

				wp_mail( $to, $subject, $message, $headers );
			}
		}
	} else {
		$post = get_post( $post_id );

		if (
			'pending' === $post->post_status
			&& ( 'demands' === $post->post_type || 'offers' === $post->post_type )
		) {
			$author = $post->post_author; /* Post author ID. */
			$name   = get_the_author_meta( 'display_name', $author );
			$email  = get_the_author_meta( 'user_email', $author );

			$title = $post->post_title;

			$to[]    = sprintf( '%s <%s>', $name, $email );
			$subject = sprintf( 'Модерация DAOC: %s', $title );

			if ( 'offers' === $post->post_type ) {
				$message = sprintf( 'К сожалению, %s. Ваша заявка на публикацию предложения "%s" не прошла модерацию.', $name, $title );
			} else {
				$message = sprintf( 'К сожалению, %s. Ваша заявка на публикацию спроса "%s" не прошла модерацию.', $name, $title );
			}
			$headers[] = '';

			wp_mail( $to, $subject, $message, $headers );
		}
	}
}

/* notify author when invitation on meeting has been accepted */

// add_action( 'transition_post_status', 'dao_transition_meeting_status', 10, 3 );
function dao_transition_meeting_status( $new_status, $old_status, $post ) {
	 /* from pending to active */
	if (
		( ( 'accepted' === $new_status || 'declined' === $new_status ) && 'waiting' === $old_status )
		&& ( 'meetings' === $post->post_type )
	) {
		$author = $post->post_author; /* Post author ID. */
		$name   = get_post_meta( $post->ID, 'invited_name', true );
		$email  = get_the_author_meta( 'user_email', $author );

		$title     = $post->post_title;
		$permalink = get_permalink( 120 );

		$to[]    = sprintf( '%s <%s>', $name, $email );
		$subject = sprintf( 'Встреча DAOC: %s', $title );

		if ( 'accepted' === $new_status ) {
			$message  = sprintf( '%s принял ваше приглашение на встречу "%s".' . "\n\n", $name, $title );
			$message .= sprintf( 'Просмотреть: %s', $permalink );
		} else {
			$message  = sprintf( '%s отклонил ваше приглашение на встречу "%s".' . "\n\n", $name, $title );
			$message .= sprintf( 'Просмотреть: %s', $permalink );
		}

		$headers[] = '';

		wp_mail( $to, $subject, $message, $headers );
	}
}

/** ----------------------------------------------------------------
 *  -----------------Additional customization-----------------------
 *  ----------------------------------------------------------------
 */

 /* post excerpt */
function dao_consensus_excerpt_length( $length ) {
	return 2;
}
add_filter( 'excerpt_length', 'dao_consensus_excerpt_length', 999 );

function dao_consensus_excerpt_more( $more ) {
	return sprintf(
		'<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		__( 'Подробнее...', 'dao-consensus' )
	);
}
add_filter( 'excerpt_more', 'dao_consensus_excerpt_more' );

/* remove p tags wrapper around content */
remove_filter( 'the_content', 'wpautop' );

/* add custom image size */
add_image_size( 'medium-large', 860, 560 );

/* Custom Author Base */
function dao_cons_custom_author_base() {
	global $wp_rewrite;
	$wp_rewrite->author_base = 'user';
}
add_action( 'init', 'dao_cons_custom_author_base' );

/* synchronize categories taxonomy of two different post types ( demands|offers and portfolio_item  ) */
function dao_cons_synchronize_categories_with_portfolio_categories( $term_id, $tt_id, $update ) {
	$categories_terms = get_terms(
		array(
			'taxonomy'   => 'categories',
			'hide_empty' => 0,
			'fields'     => 'all',
		)
	);

	$portfolio_categories_slugs = get_terms(
		array(
			'taxonomy'   => 'portfolio_categories',
			'hide_empty' => 0,
			'fields'     => 'slugs',
		)
	);

	if ( ! empty( $categories_terms ) ) {
		foreach ( $categories_terms as $term ) {
			if ( ! in_array( $term->slug, $portfolio_categories_slugs ) ) {
				$res = wp_insert_term( $term->name, 'portfolio_categories', array( 'slug' => $term->slug ) );

				if ( is_wp_error( $res ) ) {
					$errors = $res->get_error_messages();
					error_log( print_r( $errors, 1 ) );
				}
			}
		}
	}
}
add_filter( 'saved_categories', 'dao_cons_synchronize_categories_with_portfolio_categories', 10, 3 );

function dao_cons_synchronize_portfolio_categories_with_categories( $term_id, $tt_id, $update ) {
	$portfolio_categories_terms = get_terms(
		array(
			'taxonomy'   => 'portfolio_categories',
			'hide_empty' => 0,
			'fields'     => 'all',
		)
	);

	$categories_slugs = get_terms(
		array(
			'taxonomy'   => 'categories',
			'hide_empty' => 0,
			'fields'     => 'slugs',
		)
	);

	if ( ! empty( $portfolio_categories_terms ) ) {
		foreach ( $portfolio_categories_terms as $term ) {
			if ( ! in_array( $term->slug, $categories_slugs ) ) {
				$res = wp_insert_term( $term->name, 'categories', array( 'slug' => $term->slug ) );

				if ( is_wp_error( $res ) ) {
					$errors = $res->get_error_messages();
					error_log( print_r( $errors, 1 ) );
				}
			}
		}
	}
}
add_filter( 'saved_portfolio_categories', 'dao_cons_synchronize_portfolio_categories_with_categories', 10, 3 );

/*
* ------------------------------------------ *
* --------------- Cron Events -------------- *
* ------------------------------------------ *
*/

// add custom intervals
add_filter( 'cron_schedules', 'dao_add_cron_intervals' );
function dao_add_cron_intervals( $schedules ) {
	$schedules['ten_minutes']    = array(
		'interval' => 10 * 60,
		'display'  => esc_html__( 'Каждые 10 минут', 'dao-consensus' ),
	);
	$schedules['thirty_minutes']     = array(
		'interval' => HOUR_IN_SECONDS / 2,
		'display'  => esc_html__( 'Каждые 30 минут', 'dao-consensus' ),
	);
	$schedules['fourtyfive_minutes'] = array(
		'interval' => 45 * 60,
		'display'  => esc_html__( 'Каждые 45 минут', 'dao-consensus' ),
	);

	return $schedules;
}

add_action('dao_check_meetings_datetime', 'dao_check_meetings_datetime');
function dao_check_meetings_datetime() {
	$args = array(
		'post_type'      => 'meetings',
		'post_status'    => 'accepted',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	);

	$meetings = new WP_Query( $args );
	
	if ( $meetings->have_posts() ) {
		$now = new DateTime('now', new DateTimeZone('Europe/Moscow'));

		while ( $meetings->have_posts() ) {
			$meetings->the_post();

			$id       		  = get_the_ID();
			$meeting_datetime = get_post_meta( get_the_ID(), 'datetime', true );

			if ( $meeting_datetime ) {

				$datetime = new DateTime();
				$datetime->setTimestamp( $meeting_datetime );

				if ( $datetime->format('d.m.Y H:i') < $now->format('d.m.Y H:i') ) {
					$res = wp_update_post(
						array(
							'ID'          => $id,
							'post_status' => 'went',
						)
					);
					// check on error
					if ( is_wp_error( $res ) ) {
						$errors = $res->get_error_messages();
						foreach ( $errors as $error ) {
							error_log( $error );
						}
					} else {
						$invitor_id = get_post_field( 'post_author', $id );
						$invited_id = get_post_meta( 'invited_id', $id, true );

						$invitor_meetings = (int) get_user_meta( $invitor_id, 'completed_meetings', true );
						$invited_meetings = (int) get_user_meta( $invited_id, 'completed_meetings', true );

						update_user_meta( $invitor_id, 'completed_meetings', $invitor_meetings + 1 );
						update_user_meta( $invited_id, 'completed_meetings', $invited_meetings + 1 );
						
						do_action('dao_create_meeting_notification', $id, 'after_meeting');
					}
				}
			}
		}
	}

	wp_reset_postdata();
}

if ( ! wp_next_scheduled( 'dao_check_meetings_datetime' ) ) {
	wp_schedule_event( time(), 'ten_minutes', 'dao_check_meetings_datetime' );
}

/* when transaction is saved */
add_action( 'save_post_transactions', 'dao_cons_save_transactions', 10, 3 );
function dao_cons_save_transactions( $post_id, $post, $update ) {
    // return if transaction record has just created
    if ( ! $update ) {
        return;
    }
	
	$card_id     = get_post_meta( $post_id, 'card_id', true );
	$card_author = get_post_field( 'post_author', $card_id );
	update_post_meta( $post_id, 'deal_person', $card_author );
}

/* set rating, completed transactions, completed_meetings to 0 when user register */
function dao_cons_set_rating_for_new_user( $user_id ) {
    update_user_meta( $user_id, 'rating', 0 );
	update_user_meta( $user_id, 'completed_meetings', 0 );
    update_user_meta( $user_id, 'completed_transactions', 0 );
}
add_action( 'user_register', 'dao_cons_set_rating_for_new_user', 10, 1 );


