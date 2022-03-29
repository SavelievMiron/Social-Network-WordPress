<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package DAO_Consensus
 */

?>

		  <? get_template_part( 'template-parts/snackbar'); ?>

		  </div>

			<footer class="lp-footer">
			 <div class="lp-wrapper">
				   <div class="lp-footer__inner lp-flex">
				 <div class="lp-footer__logo">
						<a href="<?php echo home_url(); ?>" class="lp-inline-flex" title="home">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icons/footer-logo-secondary.svg" alt="footer logo secondary" />
					   </a>
				   </div>

					<div class="lp-footer__contacts">
					  <ul class="lp-list lp-no-style lp-flex lp-wrap">
						   <li>
								<a href="<?php echo get_privacy_policy_url(); ?>" class="lp-link lp-theme-auxiliary">Политика конфиденциальности</a>
						  </li>
						  <li>
							   <a href="tel:88005553535" class="lp-link lp-theme-auxiliary">8-800-555-35-35</a>
						   </li>
						  <li>
							   <a href="mailto:daomau@support.com" class="lp-link lp-theme-auxiliary">daomau@support.com</a>
						  </li>
					  </ul>
				  </div>

					<nav class="lp-footer__nav">
					   <ul class="lp-list lp-no-style lp-flex lp-wrap">
					   <li>
							<a href="<?php echo get_post_type_archive_link( 'demands' ); ?>" class="lp-link lp-theme-auxiliary">Cпросы</a>
					  </li>
					  <li>
							<a href="<?php echo get_post_type_archive_link( 'offers' ); ?>" class="lp-link lp-theme-auxiliary">Предложения</a>
					  </li>
					  <li>
							<a href="<?php echo get_post_type_archive_link( 'transactions' ); ?>" class="lp-link lp-theme-auxiliary">Сделки</a>
					 </li>
					  <li>
							<a href="<?php echo get_permalink( 6 ); ?>" class="lp-link lp-theme-auxiliary">О проекте</a>
						</li>
					  </ul>
				  </nav>
				 </div>
			 </div>
		 </footer>
			<?
			 if ( is_page_template('profile.php') || is_page_template( 'create-demand.php' ) || is_page_template( 'profile-card-edit.php' ) ):
			?>
			 	<script>
				   var formData = new FormData();
				   var get_portfolio_info_nonce = '<?= wp_create_nonce( 'dao-consensus-get-portfolio-info' ); ?>';
			 	</script>
			<?
			 endif;
			?>

			<?
			 if ( is_page_template( 'profile.php' ) ):
				  if( ! empty( $args ) ):
			?>
				<script>
					var query_vars = <?php echo wp_json_encode( $args['query_vars'] ); ?>;
					var profile_cards = <?php echo wp_json_encode( $args['vars'] ); ?>;
					var nonce = <?php echo wp_json_encode( $args['nonce'] ); ?>;
			  	</script>
		  <?      endif;
			 endif;

			if ( is_page_template( 'profile-chat.php' ) ):
				 if ( ! empty( $_GET['user_id'] ) ):
				 $userdata = get_user_by('ID', sanitize_text_field($_GET['user_id']));
		  ?>
			 	<script>
					var invite_username = '<?php echo $userdata->display_name; ?>';
			  	</script>
		  <?      endif;
			 endif;

			if ( is_page_template( 'profile-notifications.php' ) ) :
			?>
				<script>
					var profile_inprocess = '<?= get_permalink( 348 ); ?>';
				</script>
			<?
			endif;

			/* -------------------------------------------------------------------------------- */
		 /* ------------------------ PROFILE DEMANDS AND OFFERS ---------------------------- */
		 /* -------------------------------------------------------------------------------- */

				if ( is_page_template('profile-demand.php') || is_page_template('profile-offers.php') ):
				   if( ! empty( $args ) && isset($args['query_vars']) ):
		  ?>
			 <script>
				   var action = 'dao-get-profile-own-posts';
					var query_vars = <?php echo wp_json_encode( $args['query_vars'] ); ?>;
					var found_posts = <?php echo $args['found_posts']; ?>;
					var max_num_pages = <?php echo $args['max_num_pages']; ?>;
					var posts_per_page = <?php echo $args['posts_per_page']; ?>;
					var nonce = <?php echo wp_json_encode( wp_create_nonce( 'dao-consensus-get-profile-posts' ) ); ?>;
			  </script>
		  <?      endif;
			 endif;

			/* -------------------------------------------------------------------------------- */
		 /* ------------------------- PROFILE CARDS IN PROCESS ----------------------------- */
		 /* -------------------------------------------------------------------------------- */

			if ( is_page_template('profile-inprocess.php') ):
		  ?>
			 <script>
				var demands_action = 'dao-get-profile-demands-inprocess';
				var offers_action = 'dao-get-profile-offers-inprocess'
				<? if ( isset( $args['demands'] ) ) : ?>
					var demands_inprocess = <?php echo wp_json_encode( $args['demands'] ); ?>;
				<? endif; 
				if ( isset( $args['offers'] ) ) : ?>
					var offers_inprocess = <?php echo wp_json_encode( $args['offers'] ); ?>;
				<? endif; ?>
				var nonce = <?php echo wp_json_encode( wp_create_nonce( 'dao-consensus-get-profile-card-inprocess' ) ); ?>;
			</script>
		  <?     
			endif;

			/* ---------------------------------------------------------------------- */
		   /* ----------------------------- MEETINGS ------------------------------- */
		   /* ---------------------------------------------------------------------- */

			  if ( is_page_template( 'profile-meetings.php' ) ):
		 ?>
			 <script>
				   var action = 'dao-get-profile-meetings';
					var sent_inv = <?php echo wp_json_encode( $args['sent_inv'] ); ?>;
					var rec_inv = <?php echo wp_json_encode( $args['rec_inv'] ); ?>;
					var nonce = <?php echo wp_json_encode( wp_create_nonce( 'dao-consensus-get-profile-meetings' ) ); ?>;
					var get_meetinginfo_nonce = <?php echo wp_json_encode( wp_create_nonce( 'dao-consensus-get-meeting-info' ) ); ?>;
			   </script>
		  <?
			 endif;
		 ?>

			<?
			 if ( is_page_template( 'profile-meetings-calendar.php' ) ):

					$curr_user = wp_get_current_user();

					$args1 = array(
						'post_type' => 'meetings',
						'post_status' => array('accepted'),
						'author' => $curr_user->ID,
						'fields' => 'ids'
					);
					$args2 = array(
						'post_type' => 'meetings',
						'post_status' => array('accepted'),
						'fields' => 'ids',
						'meta_key' => 'invited_id',
						'meta_value' => $curr_user->ID,
					);

					$my_inv = new WP_Query( $args1 );
					$recieved_inv = new WP_Query( $args2 );

					$posts = array_merge( $my_inv->posts, $recieved_inv->posts );

					$curr_date = strtotime( date('Y-m-d') );
					/* first and last day of month */
					$first_day = strtotime( date('Y-m-01', $curr_date) );
					$last_day = strtotime( date('Y-m-t', $curr_date) );

					   $the_meetings = new WP_Query( array(
						'post_type' => 'meetings',
						'post_status' => 'accepted',
						'post__in' => $posts,
						'posts_per_page' => -1,
						'meta_query' => array(
						 array(
							'key' => 'datetime',
							'value' => array($first_day, $last_day),
							'compare' => 'BETWEEN'
						 )
					  )
				  ));

				   $month_meetings = array();

					if ($the_meetings->have_posts()):

					  while ($the_meetings->have_posts()): $the_meetings->the_post();
							$datetime = (int) get_post_meta(get_the_ID(), 'datetime', true);
							$day = date('j', $datetime);
							$id = get_the_ID();

							$month_meetings[$day][$id]['title'] = get_the_title();
							$month_meetings[$day][$id]['status'] = DAO_CONSENSUS::meeting_statuses[get_post_status()];
							$month_meetings[$day][$id]['date'] = eng_months_to_ru( date('j F', $datetime) );
							$month_meetings[$day][$id]['time'] = eng_months_to_ru( date('H:i', $datetime) );
							$month_meetings[$day][$id]['invited_user'] = get_post_meta( get_the_ID(), 'invited_name', true );
							$month_meetings[$day][$id]['venue'] = get_post_meta( get_the_ID(), 'venue', true );
							$month_meetings[$day][$id]['description'] = apply_filters( 'the_content', get_the_content() );
						endwhile;

					endif;

					wp_reset_postdata();
			?>
			 <script>
				var month_meetings = <?php echo wp_json_encode( $month_meetings ); ?>;
				var get_meetinginfo_nonce = <?php echo wp_json_encode( wp_create_nonce( 'dao-consensus-get-meeting-info' ) ); ?>;
				var get_meetings_month_nonce = <?php echo wp_json_encode( wp_create_nonce( 'dao-consensus-get-meetings-for-month' ) ); ?>;
			</script>
			  <?
			 endif;
			 
				if ( is_author() ) :
			?>
			<script>
				var get_portfolio_info_nonce = '<?= wp_create_nonce( 'dao-consensus-get-portfolio-info' ); ?>';
			</script>
			<? 	
				endif; 
			?>

			<? if ( is_page('favourites') ) : ?>
			<script>
				var get_fav_action = 'dao-get-profile-favourites';
				var query_vars = <?= wp_json_encode( $args['query_vars'] ); ?>;
				var found_posts = <?= $args['found_posts']; ?>;
				var max_num_pages = <?= $args['max_num_pages']; ?>;
				var get_fav_nonce = '<?= wp_create_nonce('dao-consensus-get-profile-favourites'); ?>';
			</script>
			<? endif; ?>

			<? if ( is_page('testimonials') ) : ?>
			<script>
				var get_test_action = 'dao-get-profile-testimonials';
				var vars = <?= wp_json_encode( $args['vars'] ); ?>;
				var get_test_nonce = '<?= wp_create_nonce('dao-consensus-get-profile-testimonials'); ?>';
			</script>
			<? endif; ?>

			<? if ( is_page( 'rating' ) ) : ?>
			<script>
				var action = 'dao-get-users-rating-table';
				var query_vars = <?php echo wp_json_encode( $args['query_vars'] ); ?>;
				var found_users = <?php echo $args['found_users']; ?>;
				var max_num_pages = <?php echo $args['max_num_pages']; ?>;
				var nonce = <?php echo wp_json_encode( wp_create_nonce( 'dao-consensus-get-users-rating-table' ) ); ?>;
			</script>
			<? endif; ?>

			<? if ( is_page( 'notifications' ) ) : ?>
			<script>
				var action = 'dao-get-user-notifications';
				var query_vars = <?php echo wp_json_encode( $args['query_vars'] ); ?>;
				var found_notifications = <?php echo $args['total_found']; ?>;
				var max_num_pages = <?php echo $args['max_num_pages']; ?>;
				var nonce = <?php echo wp_json_encode( wp_create_nonce( 'dao-consensus-get-user-notifications' ) ); ?>;
				var mark_unseen_not_nonce = '<?= wp_create_nonce('dao-consensus-mark-unseen-notifications'); ?>';
			</script>
			<? endif; ?>

			<?php wp_footer(); ?>
	  </div>
 </body>
</html>
