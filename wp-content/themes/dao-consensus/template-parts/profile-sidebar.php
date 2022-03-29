<aside class="lp-paper lp-sidebar lp-elevation lp-dense-6">
	<?
		$curr_user = wp_get_current_user();
	?>
	<div class="lp-sidebar__avatar lp-sidebar-avatar lp-online">
		<div class="lp-avatar">
			<img src="<?php echo get_avatar_url(
				$curr_user->get( 'ID' ),
				array(
					'size'    => 50,
					'default' => 'mystery',
				)
			); ?>" alt="profile avatar" />
		</div>

		<div class="lp-inline-flex lp-direction-column">
			<h5 class="lp-typo lp-h5"><?php echo $curr_user->get( 'display_name' ); ?></h5>

			<span class="lp-typo lp-footnote lp-grey">98.08</span>
		</div>
	</div>
	<hr class="lp-divider" />

	<nav class="lp-sidebar__nav">
		<ul class="lp-list lp-no-style">
			<? $request = $_SERVER['REQUEST_URI']; ?>
			<li>
				<a href="<?php echo get_permalink( 21 ); ?>" class="lp-link lp-theme-default <? echo ( "/profile/" === $request || "/profile/change-userdata/" === $request ) ? 'lp-active' : '' ?>">
					<i class="lp-icon lp-prefix lp-user-outlined"></i>
					Профиль
				</a>
			</li>

			<li>
				<a href="<?php echo get_permalink( 119 ); ?>" class="lp-link lp-theme-default <? echo ( "/profile/demands/" === $request ) ? 'lp-active' : '' ?>">
					<i class="lp-icon lp-prefix lp-apps-outlined"></i>
					Спрос
				</a>
			</li>

			<li>
				<a href="<?php echo get_permalink( 150 ); ?>" class="lp-link lp-theme-default  <? echo ( "/profile/offers/" === $request ) ? 'lp-active' : '' ?>">
					<i class="lp-icon lp-prefix lp-receipt-outlined"></i>
					Предложения
				</a>
			</li>

			<li>
				<a href="<?php echo get_permalink( 126 ); ?>" class="lp-link lp-theme-default <? echo ( "/profile/in-process/" === $request ) ? 'lp-active' : '' ?>">
					<i class="lp-icon lp-prefix lp-check-dashed-half"></i>
					В работе
				</a>
				<?
					// user demands with status active
					$in_process_1 = new WP_Query(array(
						'post_type' => 'demands',
						'post_status' => 'in-process',
						'author' => $curr_user->ID,
						'fields' => 'ids',
					));

					// demands which I perform
					$in_process_2 = new WP_Query(array(
						'post_type' => 'demands',
						'post_status' => 'in-process',
						'meta_key' => 'performer',
						'meta_value' => $curr_user->ID,
						'fields' => 'ids',
					));


					$in_process_3 = new WP_Query(array(
						'post_type' => 'offers',
						'post_status' => 'active',
						'fields' => 'ids',
						'author' => $curr_user->ID,
						'meta_key' => 'customers',
						'meta_value' => array('', 'a:0:{}'),
						'meta_compare' => 'NOT IN'
					));

					// offers of other users in which I am a customer
					$in_process_4 = new WP_Query(array(
						'post_type' => 'offers',
						'post_status' => 'active',
						'fields' => 'ids',
						'meta_key' => 'customers',
						'meta_value' => ":{i:{$curr_user->ID};",
						'meta_compare' => 'LIKE'
					));

					$total_in_process = $in_process_1->found_posts + $in_process_2->found_posts + $in_process_3->found_posts + $in_process_4->found_posts;

					if ( $total_in_process !== 0 ):
				?>
					<div class="lp-badge">
						<span class="lp-badge__label"><?php echo $total_in_process; ?></span>
					</div>
				<?  endif; ?>
			</li>

			<li>
				<a href="<?php echo get_permalink( 120 ); ?>" class="lp-link lp-theme-default <? echo ( strpos( $request, "/profile/meetings/" ) !== false || strpos( $request, "/profile/meetings/calendar/" ) !== false ) ? 'lp-active' : '' ?>">
					<i class="lp-icon lp-prefix lp-users-outlined"></i>
					Встречи
				</a>
				<?
					$args = array(
						'post_type' => 'meetings',
						'post_status' => 'waiting',
						'fields' => 'ids',
						'meta_key' => 'invited_id',
						'meta_value' => $curr_user->ID,
					);

					$rec_inv = new WP_Query( $args );

					if ( $rec_inv->found_posts !== 0 ): ?>
					<div class="lp-badge">
						<span class="lp-badge__label"><?php echo $rec_inv->found_posts; ?></span>
					</div>
				<? endif; ?>
			</li>

			<li>
				<a href="<?php echo get_permalink( 190 ); ?>" class="lp-link lp-theme-default <? echo ( strpos( $request, "/profile/chat/" ) !== false ) ? 'lp-active' : '' ?>">
					<i class="lp-icon lp-prefix lp-comment-message-outlined"></i>
					Чат
				</a>
				<!--
				<div class="lp-badge">
					<span class="lp-badge__label">9</span>
				</div> -->
			</li>

			<li>
				<a href="<?php echo get_permalink( 348 ); ?>" class="lp-link lp-theme-default <? echo ( strpos( $request, "/profile/notifications/" ) !== false ) ? 'lp-active' : '' ?>">
					<i class="lp-icon lp-prefix lp-bell-flat"></i>
					Уведомления
				</a>
				<?
					$unseen_notifications = dao_count_user_unseen_notifications( $curr_user->ID );

					if ( $unseen_notifications != 0 ): ?>
					<div class="lp-badge">
						<span class="lp-badge__label"><?php echo $unseen_notifications; ?></span>
					</div>
				<? 	endif; ?>
			</li>

			<li>
				<a href="<?php echo get_permalink( 474 ); ?>" class="lp-link lp-theme-default <? echo ( strpos( $request, "/profile/favourites/" ) !== false ) ? 'lp-active' : '' ?>">
					<i class="lp-icon lp-prefix lp-heart-flat"></i>
					Избранное
				</a>

				<?
					$favourites = get_user_meta( $curr_user->ID, 'favourites', true );

					if ( is_array( $favourites ) && ! empty( $favourites ) ) : ?>
					<div class="lp-badge">
						<span class="lp-badge__label"><?php echo count($favourites); ?></span>
					</div>
				<? 	endif; ?>
			</li>

			<li>
				<a href="<?php echo get_permalink( 318 ); ?>" class="lp-link lp-theme-default <? echo ( strpos( $request, "/profile/support/" ) !== false || strpos( $request, "/ticket/" ) !== false ) ? 'lp-active' : '' ?>">
					<i class="lp-icon lp-prefix lp-file-exclamation-outlined"></i>
					Служба поддержки
				</a>
			</li>

			<li>
				<a href="<?php echo get_permalink( 127 ); ?>" class="lp-link lp-theme-default <? echo ( strpos( $request, "/profile/rating/" ) !== false ) ? 'lp-active' : '' ?>">
					<i class="lp-icon lp-prefix lp-star-outlined"></i>
					Рейтинг
				</a>
			</li>
		</ul>
	</nav>
	<hr class="lp-divider" />

	<footer class="lp-sidebar__footer">
		<p class="lp-typo lp-sub lp-grey">
			Дата регистрации <br />
			<?php echo date( 'd.m.Y', strtotime( $curr_user->get( 'user_registered' ) ) ); ?>
		</p>
	</footer>
</aside>
