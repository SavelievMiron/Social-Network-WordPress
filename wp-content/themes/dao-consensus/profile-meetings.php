<?php /* Template Name: Profile Meetings */ ?>

<? get_header(); ?>


<?
	$curr_user = wp_get_current_user();

	$args1 = array(
		'post_type' => 'meetings',
		'posts_per_page' => 5,
		'author' => $curr_user->ID,
		'fields' => 'ids',
		'paged' => 1,
		'orderby' => array( 'datetime' => 'DESC' ),
		'meta_query' => array(
			'datetime' => array(
				'key' => 'datetime',
				'value' => '',
				'compare' => '!='
			)
		)
	);
	$sent_invitations = new WP_Query( $args1 );

	$args2 = array(
		'post_type' => 'meetings',
		'posts_per_page' => 5,
		'fields' => 'ids',
		'paged' => 1,
		'meta_key' => 'invited_id',
		'meta_value' => $curr_user->ID,
		'orderby' => array( 'datetime' => 'DESC' ),
		'meta_query' => array(
			'datetime' => array(
				'key' => 'datetime',
				'value' => '',
				'compare' => '!='
			)
		)
	);
	$received_invitations = new WP_Query( $args2 );

	do_action('dao_create_meeting_notification', 431, 'after_meeting');
?>

<div class="lp-page lp-page-meetings">
	<div class="lp-wrapper">
		<div class="lp-grid lp-container lp-spacing-3">
			<div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-12">
				<? get_template_part('template-parts/profile', 'sidebar'); ?>
			</div>

			<div class="lp-grid lp-item lp-lg-9 lp-md-8 lp-sm-12">
				<div class="lp-grid lp-item lp-xs-12">
					<div class="lp-data-grid lp-paper lp-elevation lp-dense-6">
						<div class="lp-grid lp-container lp-spacing-4" data-lp-tabs id="invitation-types">
							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-grid lp-container lp-spacing-2 lp-align-center">
									<div class="lp-grid lp-item lp-lg-8 lp-md-5 lp-sm-6 lp-xs-12">
										<div class="lp-tabs" data-lp-tabs="tabs">
											<button class="lp-button-base lp-button-tab" data-lp-selected="true">
												<? printf('%s (%s)', __('Отправленные', 'dao-consensus'), $sent_invitations->found_posts ); ?></button>
											<button class="lp-button-base lp-button-tab">
												<?printf('%s (%s)', __('Полученные', 'dao-consensus'), $received_invitations->found_posts ); ?></button>
										</div>
									</div>
									<div class="lp-grid lp-item lp-lg-4 lp-md-5 lp-sm-6 lp-xs-12">
										<a href="<?php echo get_permalink( 163 ); ?>"
											class="lp-button-base lp-button lp-size-large lp-theme-primary lp-variant-outlined lp-full">
											<? _e('Перейти в календарь', 'dao-consensus'); ?>
										</a>
									</div>
								</div>
							</div>

							<?
								$vars = [];
								$vars['sent_inv'] = [];
								$vars['rec_inv'] = [];
							?>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-grid lp-container lp-spacing-3" data-lp-tabs="views">
									<div class="sent-invitations lp-grid lp-item lp-xs-12">
										<?
										$vars['sent_inv']['query_vars'] = $args1;
										$vars['sent_inv']['found_posts'] = $sent_invitations->found_posts;
										$vars['sent_inv']['max_num_pages'] = $sent_invitations->max_num_pages;
										$vars['sent_inv']['posts_per_page'] = $sent_invitations->query_vars['posts_per_page'];

										if ( $sent_invitations->have_posts() ): ?>
										<div class="lp-grid lp-item lp-xs-12">
											<form class="filters lp-grid lp-container lp-spacing-2 lp-align-center">
												<div class="lp-grid lp-item lp-xs-8">
													<div class="lp-grid lp-container lp-spacing-2 lp-align-center">
														<div class="lp-grid lp-item lp-xs-4">
															<div class="format lp-select" data-lp-select>
																<div class="lp-select__textfield lp-textfield lp-variant-outlined"
																	data-lp-select="textfield">
																	<div class="lp-textfield__input">
																		<label class="lp-textfield__label">
																			<span data-lp-select="value"></span>
																			<input type="hidden" name="format"
																				data-lp-textfield="input"
																				data-lp-select="input" />

																			<span>Формат</span>
																		</label>

																		<div class="lp-textfield__postfix">
																			<button type="button"
																				class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																				data-lp-select="clear">
																				<i class="lp-icon lp-times-flat"></i>
																			</button>

																			<button type="button"
																				class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																				data-lp-select="button">
																				<i
																					class="lp-icon lp-angle-down-flat"></i>
																			</button>
																		</div>
																	</div>
																</div>

																<div class="lp-select__options lp-paper lp-elevation lp-dense-3"
																	data-lp-select="popover" aria-hidden="true">
																	<div class="lp-select__search lp-textfield lp-variant-outlined"
																		data-lp-select="search">
																		<div class="lp-textfield__input">
																			<label class="lp-textfield__label">
																				<input type="text"
																					data-lp-textfield="input" />

																				<span>Поиск</span>
																			</label>
																		</div>
																	</div>

																	<ul class="lp-list lp-no-style"
																		data-lp-select="options">
																		<? foreach( DAO_CONSENSUS::meeting_formats as $k => $v ): ?>
																		<li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
																			data-lp-value="<?php echo $k; ?>">
																			<span><?php echo $v; ?></span>
																		</li>
																		<? endforeach; ?>
																	</ul>
																</div>
															</div>
														</div>
														<div class="lp-grid lp-item lp-xs-4">
															<div class="status lp-select" data-lp-select>
																<div class="lp-select__textfield lp-textfield lp-variant-outlined"
																	data-lp-select="textfield">
																	<div class="lp-textfield__input">
																		<label class="lp-textfield__label">
																			<span data-lp-select="value"></span>
																			<input type="hidden" name="status"
																				data-lp-textfield="input"
																				data-lp-select="input" />

																			<span>Статус</span>
																		</label>

																		<div class="lp-textfield__postfix">
																			<button type="button"
																				class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																				data-lp-select="clear">
																				<i class="lp-icon lp-times-flat"></i>
																			</button>

																			<button type="button"
																				class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																				data-lp-select="button">
																				<i
																					class="lp-icon lp-angle-down-flat"></i>
																			</button>
																		</div>
																	</div>
																</div>

																<div class="lp-select__options lp-paper lp-elevation lp-dense-3"
																	data-lp-select="popover" aria-hidden="true">
																	<div class="lp-select__search lp-textfield lp-variant-outlined"
																		data-lp-select="search">
																		<div class="lp-textfield__input">
																			<label class="lp-textfield__label">
																				<input type="text"
																					data-lp-textfield="input" />

																				<span>Поиск</span>
																			</label>
																		</div>
																	</div>

																	<ul class="lp-list lp-no-style"
																		data-lp-select="options">
																		<? foreach( DAO_CONSENSUS::meeting_statuses as $k => $v ): ?>
																		<li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
																			data-lp-value="<?php echo $k; ?>">
																			<span><?php echo $v; ?></span>
																		</li>
																		<? endforeach; ?>
																	</ul>
																</div>
															</div>
														</div>
														<div class="lp-grid lp-item lp-xs-4">
															<div class="user lp-select" data-lp-select>
																<div class="lp-select__textfield lp-textfield lp-variant-outlined"
																	data-lp-select="textfield">
																	<div class="lp-textfield__input">
																		<label class="lp-textfield__label">
																			<span data-lp-select="value"></span>
																			<input type="hidden" name="user"
																				data-lp-textfield="input"
																				data-lp-select="input" />

																			<span>Пользователь</span>
																		</label>

																		<div class="lp-textfield__postfix">
																			<button type="button"
																				class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																				data-lp-select="clear">
																				<i class="lp-icon lp-times-flat"></i>
																			</button>

																			<button type="button"
																				class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																				data-lp-select="button">
																				<i
																					class="lp-icon lp-angle-down-flat"></i>
																			</button>
																		</div>
																	</div>
																</div>

																<div class="lp-select__options lp-paper lp-elevation lp-dense-3"
																	data-lp-select="popover" aria-hidden="true">
																	<div class="lp-select__search lp-textfield lp-variant-outlined"
																		data-lp-select="search">
																		<div class="lp-textfield__input">
																			<label class="lp-textfield__label">
																				<input type="text"
																					data-lp-textfield="input" />

																				<span>Поиск</span>
																			</label>
																		</div>
																	</div>

																	<ul class="lp-list lp-no-style"
																		data-lp-select="options">
																		<? 
																		$invited_users = dao_get_all_invited_users($sent_invitations->posts);
																		foreach( $invited_users as $k => $v ): 
																		?>
																		<li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
																			data-lp-value="<?php echo $k; ?>">
																			<span><?php echo $v; ?></span>
																		</li>
																		<? endforeach; ?>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="lp-grid lp-item lp-xs-4">
													<div class="search lp-textfield lp-variant-outlined"
														data-lp-textfield id="test-textfield">
														<div class="lp-textfield__input">
															<label class="lp-textfield__label">
																<input name="search" type="text"
																	data-lp-textfield="input" />

																<span>Поиск по заголовку</span>
															</label>
														</div>
													</div>
												</div>
											</form>
										</div>
										<div class="lp-data-grid__table lp-table lp-table-feedback">
											<table class="meetings-table">
												<thead>
													<tr>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Заголовок</span>
																<button class="title-order lp-button-base lp-button-icon lp-size-small lp-theme-primary lp-variant-flat lp-rounded">
																	<i class="lp-icon lp-triangle-filter"></i>
																</button>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Дата и время</span>
																<button class="datetime-order lp-button-base lp-button-icon lp-size-small lp-theme-primary lp-variant-flat lp-rounded">
																	<i class="lp-icon lp-triangle-down"></i>
																</button>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Статус</span>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Карточка</span>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Формат</span>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Пользователь</span>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Место</span>
															</div>
														</th>

														<th class="lp-align-right">
															<span class="lp-typo lp-sub lp-grey">Действия</span>
														</th>
													</tr>
												</thead>

												<tbody id="data-container">
													<?
														while( $sent_invitations->have_posts() ): $sent_invitations->the_post();

															get_template_part('template-parts/profile-meeting-card', 'sent');

														endwhile;
														?>
												</tbody>
											</table>
										</div>
										<div class="lp-grid lp-item lp-xs-12 lp-mt-20">
											<nav id="sent-inv-pagination" class="lp-pagination tui-pagination"
												data-lp-pagination>
											</nav>
										</div>
										<? else: ?>
										<div class="lp-grid lp-item lp-xs-12">
											<div class="no-results not-found">
												<p>
													<? _e('Вы ещё не отправили ни одного приглашения на встречу.', 'dao-consensus'); ?>
												</p>
											</div>
										</div>
										<?
											endif;
											wp_reset_postdata();
										?>
									</div>

									<div class="received-invitations lp-grid lp-item lp-xs-12">
											<?
											$vars['rec_inv']['query_vars'] = $args2;
											$vars['rec_inv']['found_posts'] = $received_invitations->found_posts;
											$vars['rec_inv']['max_num_pages'] = $received_invitations->max_num_pages;
											$vars['rec_inv']['posts_per_page'] = $received_invitations->query_vars['posts_per_page'];

											if ( $received_invitations->have_posts() ): ?>
											<div class="lp-grid lp-item lp-xs-12">
												<form class="filters lp-grid lp-container lp-spacing-2 lp-align-center">
													<div class="lp-grid lp-item lp-xs-8">
														<div class="lp-grid lp-container lp-spacing-2 lp-align-center">
															<div class="lp-grid lp-item lp-xs-4">
																<div class="format lp-select" data-lp-select id="test-select">
																	<div class="lp-select__textfield lp-textfield lp-variant-outlined"
																		data-lp-select="textfield">
																		<div class="lp-textfield__input">
																			<label class="lp-textfield__label">
																				<span data-lp-select="value"></span>
																				<input type="hidden" name="format"
																					data-lp-textfield="input"
																					data-lp-select="input" />

																				<span>Формат</span>
																			</label>

																			<div class="lp-textfield__postfix">
																				<button type="button" class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded" data-lp-select="clear">
																					<i class="lp-icon lp-times-flat"></i>
																				</button>

																				<button type="button" class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded" data-lp-select="button">
																					<i class="lp-icon lp-angle-down-flat"></i>
																				</button>
																			</div>
																		</div>
																	</div>

																	<div class="lp-select__options lp-paper lp-elevation lp-dense-3"
																		data-lp-select="popover" aria-hidden="true">
																		<div class="lp-select__search lp-textfield lp-variant-outlined"
																			data-lp-select="search">
																			<div class="lp-textfield__input">
																				<label class="lp-textfield__label">
																					<input type="text"
																						data-lp-textfield="input" />

																					<span>Поиск</span>
																				</label>
																			</div>
																		</div>

																		<ul class="lp-list lp-no-style"
																			data-lp-select="options">
																			<? foreach( DAO_CONSENSUS::meeting_formats as $k => $v ): ?>
																			<li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
																				data-lp-value="<?php echo $k; ?>">
																				<span><?php echo $v; ?></span>
																			</li>
																			<? endforeach; ?>
																		</ul>
																	</div>
																</div>
															</div>
															<div class="lp-grid lp-item lp-xs-4">
																<div class="status lp-select" data-lp-select>
																	<div class="lp-select__textfield lp-textfield lp-variant-outlined"
																		data-lp-select="textfield">
																		<div class="lp-textfield__input">
																			<label class="lp-textfield__label">
																				<span data-lp-select="value"></span>
																				<input type="hidden" name="status"
																					data-lp-textfield="input"
																					data-lp-select="input" />

																				<span>Статус</span>
																			</label>

																			<div class="lp-textfield__postfix">
																				<button type="button"
																					class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																					data-lp-select="clear">
																					<i class="lp-icon lp-times-flat"></i>
																				</button>

																				<button type="button"
																					class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																					data-lp-select="button">
																					<i
																						class="lp-icon lp-angle-down-flat"></i>
																				</button>
																			</div>
																		</div>
																	</div>

																	<div class="lp-select__options lp-paper lp-elevation lp-dense-3"
																		data-lp-select="popover" aria-hidden="true">
																		<div class="lp-select__search lp-textfield lp-variant-outlined"
																			data-lp-select="search">
																			<div class="lp-textfield__input">
																				<label class="lp-textfield__label">
																					<input type="text"
																						data-lp-textfield="input" />

																					<span>Поиск</span>
																				</label>
																			</div>
																		</div>

																		<ul class="lp-list lp-no-style"
																			data-lp-select="options">
																			<? foreach( DAO_CONSENSUS::meeting_statuses as $k => $v ): ?>
																			<li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
																				data-lp-value="<?php echo $k; ?>">
																				<span><?php echo $v; ?></span>
																			</li>
																			<? endforeach; ?>
																		</ul>
																	</div>
																</div>
															</div>
															<div class="lp-grid lp-item lp-xs-4">
																<div class="user lp-select" data-lp-select>
																	<div class="lp-select__textfield lp-textfield lp-variant-outlined"
																		data-lp-select="textfield">
																		<div class="lp-textfield__input">
																			<label class="lp-textfield__label">
																				<span data-lp-select="value"></span>
																				<input type="hidden" name="user"
																					data-lp-textfield="input"
																					data-lp-select="input" />

																				<span>Пользователь</span>
																			</label>

																			<div class="lp-textfield__postfix">
																				<button type="button"
																					class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																					data-lp-select="clear">
																					<i class="lp-icon lp-times-flat"></i>
																				</button>

																				<button type="button"
																					class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																					data-lp-select="button">
																					<i
																						class="lp-icon lp-angle-down-flat"></i>
																				</button>
																			</div>
																		</div>
																	</div>

																	<div class="lp-select__options lp-paper lp-elevation lp-dense-3"
																		data-lp-select="popover" aria-hidden="true">
																		<div class="lp-select__search lp-textfield lp-variant-outlined"
																			data-lp-select="search">
																			<div class="lp-textfield__input">
																				<label class="lp-textfield__label">
																					<input type="text"
																						data-lp-textfield="input" />

																					<span>Поиск</span>
																				</label>
																			</div>
																		</div>

																		<ul class="lp-list lp-no-style"
																			data-lp-select="options">
																			<? 
																			$invitor_users = dao_get_all_invitοr_users($received_invitations->posts);
																			foreach( $invitor_users as $k => $v ): 
																			?>
																			<li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
																				data-lp-value="<?php echo $k; ?>">
																				<span><?php echo $v; ?></span>
																			</li>
																			<? endforeach; ?>
																		</ul>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="lp-grid lp-item lp-xs-4">
														<div class="search lp-textfield lp-variant-outlined"
															data-lp-textfield id="test-textfield">
															<div class="lp-textfield__input">
																<label class="lp-textfield__label">
																	<input name="search" type="text"
																		data-lp-textfield="input" />

																	<span>Поиск по заголовку</span>
																</label>
															</div>
														</div>
													</div>
												</form>
											</div>
										<div class="lp-data-grid__table lp-table lp-table-feedback">
											<table class="meetings-table">
												<thead>
													<tr>
														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Заголовок</span>
																<button class="title-order lp-button-base lp-button-icon lp-size-small lp-theme-primary lp-variant-flat lp-rounded">
																	<i class="lp-icon lp-triangle-filter"></i>
																</button>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Дата и время</span>
																<button class="datetime-order lp-button-base lp-button-icon lp-size-small lp-theme-primary lp-variant-flat lp-rounded">
																	<i class="lp-icon lp-triangle-down"></i>
																</button>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Статус</span>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Карточка</span>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Формат</span>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Пользователь</span>
															</div>
														</th>

														<th class="lp-align-left">
															<div class="lp-flex lp-align-center">
																<span class="lp-typo lp-sub lp-grey">Место</span>
															</div>
														</th>

														<th class="lp-align-right">
															<span class="lp-typo lp-sub lp-grey">Действия</span>
														</th>
													</tr>
												</thead>

												<tbody id="data-container">
													<?
														while( $received_invitations->have_posts() ): $received_invitations->the_post();

															get_template_part('template-parts/profile-meeting-card', 'received');

														endwhile;
														?>
												</tbody>
											</table>
										</div>
										<div class="lp-grid lp-item lp-xs-12 lp-mt-20">
											<nav id="rec-inv-pagination" class="lp-pagination tui-pagination"
												data-lp-pagination>
											</nav>
										</div>
										<? else: ?>
										<div class="lp-grid lp-item lp-xs-12">
											<div class="no-results not-found">
												<p>
													<? _e('Вы ещё не получили ни одного приглашения на встречу.', 'dao-consensus'); ?>
												</p>
											</div>
										</div>
										<?
												endif;
												wp_reset_postdata();
											?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="lp-modal" data-lp-modal="view-meeting" aria-label="view meeting" aria-hidden="true">
		<div class="lp-modal__wrapper">
			<div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
				<header class="lp-modal__header">
					<h5 class="title lp-typo lp-h5">Заголовок встречи</h5>
				</header>

				<div class="lp-modal__body">
					<div class="lp-grid lp-container lp-spacing-4">
						<div class="lp-grid lp-item lp-xs-12">
							<div class="lp-flex lp-align-center lp-justify-between">
								<h4 class="time lp-typo lp-h1">20:30</h4>

								<span class="format lp-typo lp-body lp-grey"> Онлайн встреча </span>
							</div>

							<div class="lp-flex lp-align-center lp-justify-between">
								<h5 class="date lp-typo lp-h5">30 июня</h5>

								<span class="status lp-typo lp-body lp-grey"> Статус встречи </span>
							</div>
						</div>

						<div class="lp-grid lp-item lp-xs-12">
							<div class="invited lp-textfield lp-variant-outlined" data-lp-textfield>
								<div class="lp-textfield__input">
									<label class="lp-textfield__label">
										<input name="invited_user" type="text" data-lp-textfield="input" readonly disabled />
										<span>Пользователь</span>
									</label>
								</div>

								<!-- <div class="lp-textfield__helpers">
										<span>with helpers</span>
									</div> -->
							</div>
						</div>

						<div class="lp-grid lp-item lp-xs-12">
							<div class="venue lp-textfield lp-variant-outlined" data-lp-textfield>
								<div class="lp-textfield__input">
									<label class="lp-textfield__label">
										<input name="venue" type="text" data-lp-textfield="input" readonly disabled/>
										<span>Место встречи</span>
									</label>
								</div>
							</div>
						</div>

						<div class="lp-grid lp-item lp-xs-12">
							<div class="description lp-textfield lp-variant-outlined lp-area" data-lp-textfield>
								<div class="lp-textfield__input">
									<label class="lp-textfield__label">
										<textarea name="description" data-lp-textfield="input" readonly disabled></textarea>
										<span>Краткое описание</span>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="lp-modal__close">
					<button
						class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
						data-lp-modal="close" data-lp-close>
						<i class="lp-icon lp-times-flat"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="lp-modal" data-lp-modal="accept-meeting" aria-label="accept meeting modal" aria-hidden="true">
		<div class="lp-modal__wrapper">
			<form class="accept-meeting lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
				<header class="lp-modal__header">
					<h5 class="lp-typo lp-h5">Принять приглашение</h5>
				</header>

				<div class="lp-modal__body">
					<p class="lp-typo lp-body">Вы действительно хотите принять приглашение на встречу от <span
							class="invitor"></span>?</p>
				</div>

				<footer class="lp-modal__footer">
					<div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
						<div class="lp-grid lp-item">
							<button type="button"
								class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
								data-lp-modal="cancel">
								Отмена
							</button>
						</div>

						<div class="lp-grid lp-item lp-flex lp-align-center">
							<button type="submit"
								class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
								Подтвердить
							</button>
							<span class="lp-loader lp-hide">
								<svg class="lp-loader__circle" width="40" height="40" viewBox="0 0 48 48"
									xmlns="http://www.w3.org/2000/svg">
									<circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round"
										cx="24" cy="24" r="20" />
								</svg>
							</span>
						</div>
					</div>
				</footer>

				<div class="lp-modal__close">
					<button type="button"
						class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
						data-lp-modal="close" data-lp-close>
						<i class="lp-icon lp-times-flat"></i>
					</button>
				</div>

				<input type="hidden" name="action" value="dao-accept-meeting">
				<input type="hidden" name="meeting_id">
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'dao-consensus-accept-meeting' ); ?>">
			</form>
		</div>
	</div>
	<div class="lp-modal" data-lp-modal="accept-reschedule-meeting" aria-label="accept meeting modal" aria-hidden="true">
		<div class="lp-modal__wrapper">
			<form class="accept-reschedule-meeting lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
				<header class="lp-modal__header">
					<h5 class="lp-typo lp-h5">Принять перенос встречи</h5>
				</header>

				<div class="lp-modal__body">
					<p class="lp-typo lp-body">Вы действительно хотите принять перенос встречи "<span class="title"></span>"?</p>
				</div>

				<footer class="lp-modal__footer">
					<div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
						<div class="lp-grid lp-item">
							<button type="button"
								class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
								data-lp-modal="cancel">
								Отмена
							</button>
						</div>

						<div class="lp-grid lp-item lp-flex lp-align-center">
							<button type="submit"
								class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
								Подтвердить
							</button>
							<span class="lp-loader lp-hide">
								<svg class="lp-loader__circle" width="40" height="40" viewBox="0 0 48 48"
									xmlns="http://www.w3.org/2000/svg">
									<circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round"
										cx="24" cy="24" r="20" />
								</svg>
							</span>
						</div>
					</div>
				</footer>

				<div class="lp-modal__close">
					<button type="button"
						class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
						data-lp-modal="close" data-lp-close>
						<i class="lp-icon lp-times-flat"></i>
					</button>
				</div>

				<input type="hidden" name="action" value="dao-accept-reschedule-meeting">
				<input type="hidden" name="meeting_id">
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'dao-consensus-accept-reschedule-meeting' ); ?>">
			</form>
		</div>
	</div>
	<div class="lp-modal" data-lp-modal="reject-meeting" aria-label="reject meeting modal" aria-hidden="true">
		<div class="lp-modal__wrapper">
			<form class="reject-meeting lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
				<header class="lp-modal__header">
					<h5 class="lp-typo lp-h5">Отклонить приглашение</h5>
				</header>

				<div class="lp-modal__body">
					<p class="lp-typo lp-body">Вы действительно хотите отклонить приглашение на встречу от <span
							class="invitor"></span>?</p>
				</div>

				<footer class="lp-modal__footer">
					<div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
						<div class="lp-grid lp-item">
							<button type="button"
								class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
								data-lp-modal="cancel">
								Отмена
							</button>
						</div>

						<div class="lp-grid lp-item lp-flex lp-align-center">
							<button type="submit"
								class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
								Подтвердить
							</button>
							<span class="lp-loader lp-hide">
								<svg class="lp-loader__circle" width="40" height="40" viewBox="0 0 48 48"
									xmlns="http://www.w3.org/2000/svg">
									<circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round"
										cx="24" cy="24" r="20" />
								</svg>
							</span>
						</div>
					</div>
				</footer>

				<div class="lp-modal__close">
					<button type="button"
						class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
						data-lp-modal="close" data-lp-close>
						<i class="lp-icon lp-times-flat"></i>
					</button>
				</div>

				<input type="hidden" name="action" value="dao-reject-meeting">
				<input type="hidden" name="meeting_id">
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'dao-consensus-reject-meeting' ); ?>">
			</form>
		</div>
	</div>
	<div class="lp-modal" data-lp-modal="reject-reschedule-meeting" aria-label="reject meeting modal" aria-hidden="true">
		<div class="lp-modal__wrapper">
			<form class="reject-reschedule-meeting lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
				<header class="lp-modal__header">
					<h5 class="lp-typo lp-h5">Отклонить перенос встречи</h5>
				</header>

				<div class="lp-modal__body">
					<p class="lp-typo lp-body">Вы действительно хотите отклонить перенос встречи "<span class="title"></span>"?</p>
				</div>

				<footer class="lp-modal__footer">
					<div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
						<div class="lp-grid lp-item">
							<button type="button"
								class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
								data-lp-modal="cancel">
								Отмена
							</button>
						</div>

						<div class="lp-grid lp-item lp-flex lp-align-center">
							<button type="submit"
								class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
								Подтвердить
							</button>
							<span class="lp-loader lp-hide">
								<svg class="lp-loader__circle" width="40" height="40" viewBox="0 0 48 48"
									xmlns="http://www.w3.org/2000/svg">
									<circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round"
										cx="24" cy="24" r="20" />
								</svg>
							</span>
						</div>
					</div>
				</footer>

				<div class="lp-modal__close">
					<button type="button"
						class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
						data-lp-modal="close" data-lp-close>
						<i class="lp-icon lp-times-flat"></i>
					</button>
				</div>

				<input type="hidden" name="action" value="dao-reject-reschedule-meeting">
				<input type="hidden" name="meeting_id">
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'dao-consensus-reject-reschedule-meeting' ); ?>">
			</form>
		</div>
	</div>
	<div class="lp-modal" data-lp-modal="cancel-meeting" aria-label="cancel meeting modal" aria-hidden="true">
		<div class="lp-modal__wrapper">
			<form class="cancel-meeting lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
				<header class="lp-modal__header">
					<h5 class="lp-typo lp-h5">Отменить встречу</h5>
				</header>

				<div class="lp-modal__body">
					<p class="lp-typo lp-body">Вы действительно хотите отменить встречу с <span
							class="invitor"></span>?</p>
				</div>

				<footer class="lp-modal__footer">
					<div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
						<div class="lp-grid lp-item">
							<button type="button"
								class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
								data-lp-modal="cancel">
								Отмена
							</button>
						</div>

						<div class="lp-grid lp-item lp-flex lp-align-center">
							<button type="submit"
								class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
								Подтвердить
							</button>
							<span class="lp-loader lp-hide">
								<svg class="lp-loader__circle" width="40" height="40" viewBox="0 0 48 48"
									xmlns="http://www.w3.org/2000/svg">
									<circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round"
										cx="24" cy="24" r="20" />
								</svg>
							</span>
						</div>
					</div>
				</footer>

				<div class="lp-modal__close">
					<button type="button"
						data-lp-modal="close" data-lp-close
						class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded">
						<i class="lp-icon lp-times-flat"></i>
					</button>
				</div>

				<input type="hidden" name="action" value="dao-cancel-meeting">
				<input type="hidden" name="meeting_id">
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'dao-consensus-cancel-meeting' ); ?>">
			</form>
		</div>
	</div>
	<div class="lp-modal" data-lp-modal="reschedule-meeting" aria-label="set new datetime for meeting" data-lp-card="reschedule-meeting-modal" aria-hidden="true">
		<div class="lp-modal__wrapper">
			<form class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
				<header class="lp-modal__header">
					<h5 class="lp-typo lp-h5">Перенос встречи</h5>
				</header>

				<div class="lp-modal__body">
					<h5 class="lp-typo lp-h5">Встреча "<span class="title"></span>" <span class="datetime"></span></h5>
					<br>
					<div id="datetime" class="lp-textfield lp-variant-outlined" data-lp-textfield>
						<div class="lp-textfield__input">
							<label class="lp-textfield__label">
								<input name="datetime" type="text" data-lp-textfield="input" />
								<span>Дата и время встречи</span>
							</label>
						</div>
					</div>
				</div>

				<footer class="lp-modal__footer">
					<div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
						<div class="lp-grid lp-item">
							<button type="button" class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
								data-lp-modal="cancel">
								Отмена
							</button>
						</div>

						<div class="lp-grid lp-item lp-flex lp-align-center">
							<button type="submit" class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled"
							data-lp-modal="confirm">
								Подтвердить
							</button>
							<span class="lp-loader lp-hide">
								<svg class="lp-loader__circle" width="40" height="40"
									viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
									<circle class="lp-loader__path" fill="none" stroke-width="5"
										stroke-linecap="round" cx="24" cy="24" r="20" />
								</svg>
							</span>
						</div>
					</div>
				</footer>

				<div class="lp-modal__close">
					<button type="button"
						class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
						data-lp-modal="close" data-lp-close>
						<i class="lp-icon lp-times-flat"></i>
					</button>
				</div>

				<input type="hidden" name="action" value="dao-reschedule-meeting">
				<input type="hidden" name="meeting_id">
				<input type="hidden" name="_wpnonce" value="<?= wp_create_nonce('dao-consensus-reschedule-meeting') ?>">
			</form>
		</div>
	</div>
	<div class="lp-modal" data-lp-modal aria-label="date-picker" aria-hidden="true" id="date-picker">
		<div class="lp-modal__wrapper">
			<div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
				<div class="lp-modal__body">
					<div class="lp-grid lp-container lp-justify-center">
						<div class="lp-grid lp-item">
							<div class="lp-date-picker" id="datepicker"></div>
						</div>
					</div>
				</div>

				<footer class="lp-modal__footer">
					<div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
						<div class="lp-grid lp-item">
							<button class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
								data-lp-modal="cancel">
								Отмена
							</button>
						</div>

						<div class="lp-grid lp-item">
							<button
								class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled" data-lp-close>Принять</button>
						</div>
					</div>
				</footer>

				<div class="lp-modal__close">
					<button
						class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
						data-lp-modal="close">
						<i class="lp-icon lp-times-flat"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<? get_footer('', $vars); ?>
