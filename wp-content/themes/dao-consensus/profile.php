<?php /* Template Name: Profile */ ?>

<? get_header(); ?>

<div class="lp-page lp-page-profile">
	<div class="lp-wrapper">
		<div class="lp-grid lp-container lp-spacing-3">
			<div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-xs-12">
				<? get_template_part('template-parts/profile', 'sidebar'); ?>
			</div>

			<?
				$curr_user = wp_get_current_user();
			?>

			<div class="lp-grid lp-item lp-lg-9 lp-md-8 lp-xs-12">
				<div class="lp-grid lp-container lp-spacing-3">
					<div class="lp-grid lp-item lp-xs-12">
						<div class="lp-paper lp-common-info lp-elevation lp-dense-6">
							<div class="lp-grid lp-container lp-spacing-3">
								<div class="lp-grid lp-item lp-lg-4 lp-md-12 lp-xs-12">
									<div class="lp-common-info__avatar lp-avatar">
										<img src="
										<?php
										echo get_avatar_url(
											$curr_user->get( 'ID' ),
											array(
												'size'    => 300,
												'default' => 'mystery',
											)
										);
										?>
										"
											alt="profile avatar" />
									</div>

									<div class="lp-common-info__info">
										<ul class="lp-list lp-no-style">
											<li>
												<? $user_type = get_user_meta( $curr_user->ID, 'person_type', true ); ?>
												<span class="lp-typo lp-footnote lp-grey">
													<i class="lp-icon lp-user-outlined"></i>
													<?php echo DAO_CONSENSUS::person_types[ $user_type ]; ?>
												</span>
											</li>

											<li>
												<span class="lp-typo lp-footnote lp-grey">
													<?
													$user_activity = dao_is_user_online( $curr_user->get('ID') );
													?>
													<i class="lp-icon lp-users-outlined"></i>
													<?
														if ( $user_activity === true ) {
															echo 'Онлайн';
														} else {
															$curr_time = new DateTime( current_time( 'mysql' ) );
															$last_act_time = new DateTime( $user_activity );

															$diff = $curr_time->diff( $last_act_time )->format('%H hours %i minutes %s seconds');
															echo "Офлайн {$diff}";
														}
													?>
												</span>
											</li>

											<li>
												<span class="lp-typo lp-footnote lp-grey">
													<i class="lp-icon lp-clock-outlined"></i>
													<?php echo 'На сайте c ' . eng_months_to_ru( date( 'j F Y', strtotime( $curr_user->get( 'user_registered' ) ) ) ); ?>
												</span>
											</li>
										</ul>
									</div>
									<hr class="lp-divider" />

									<div class="lp-common-info__stats">
										<ul class="lp-list lp-no-style">
											<li>
												<span class="lp-typo lp-sub"><b><?= dao_count_user_completed_transactions( $curr_user->ID ); ?></b></span>
												<span class="lp-typo lp-footnote lp-grey"> сделок совершено </span>
											</li>

											<li>
												<span class="lp-typo lp-sub"><b><?= dao_count_user_testimonials( $curr_user->ID ); ?></b></span>
												<span class="lp-typo lp-footnote lp-grey"> отзывов получено </span>
											</li>

											<!-- <li>
												<span class="lp-typo lp-sub"><b>23%</b></span>
												<span class="lp-typo lp-footnote lp-grey"> повторных сделок </span>
											</li> -->
										</ul>
									</div>
									<hr class="lp-divider" />

									<div class="lp-common-info__rate">
										<span class="lp-typo lp-sub">
											<i class="lp-icon lp-star-outlined"></i>
											<b><?= dao_get_user_rating( $curr_user->ID ); ?></b>
										</span>

										<span class="lp-typo lp-footnote lp-grey"><?= dao_get_user_rating_label( $curr_user->ID ); ?></span>
									</div>
									<hr class="lp-divider" />

									<div class="lp-common-info__share">
										<a href="<?= get_author_posts_url( $curr_user->ID ); ?>" class="lp-link lp-theme-primary">
											<small class="lp-flex lp-align-center">
												<i class="lp-icon lp-prefix lp-users-outlined"></i>
												Поделиться
											</small>
										</a>
									</div>
								</div>

								<div class="lp-grid lp-item lp-lg-8 lp-md-12 lp-xs-12">
									<div class="lp-common-info__user lp-flex lp-align-start lp-justify-between">
										<div class="lp-flex lp-direction-column">
											<h4 class="lp-typo lp-h4"><?php echo $curr_user->get( 'display_name' ); ?></h4>
											<h5 class="lp-typo lp-h5"><?php echo get_user_meta( $curr_user->ID, 'specialization', true ); ?></h5>
										</div>

										<a href="<?php echo get_permalink( 228 ); ?>"
											class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled"
											aria-label="edit user" title="edit user" id="start-editing">
											<i class="lp-icon lp-edit-flat"></i>
										</a>
									</div>

									<div class="lp-common-info__content">
										<p>
											<?php echo esc_html( get_user_meta( $curr_user->get( 'ID' ), 'description', true ) ); ?>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?
					$profile_skills = get_user_meta( $curr_user->ID, 'skills', true );

					if ( ! empty( $profile_skills ) ) :
					?>
					<div class="lp-grid lp-item lp-xs-12">
						<div class="lp-paper lp-skills lp-elevation lp-dense-6">
							<div class="lp-grid lp-container lp-spacing-3">
								<div class="lp-grid lp-item">
									<div class="lp-paper__title">
										<span class="lp-typo lp-footnote lp-uppercase">Сведения о навыках</span>
									</div>
								</div>

								<div class="lp-grid lp-item lp-xs-12">
									<?
										$profile_skills = get_user_meta( $curr_user->ID, 'skills', true );
									?>
									<div class="lp-skills__chips lp-flex lp-wrap">
										<? foreach( $profile_skills as $skill ) : ?>
										<div class="lp-chip lp-default lp-variant-outlined">
											<span class="lp-chip__label"><?php echo $skill; ?></span>
										</div>
										<? endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<? 
					endif;
					?>

					<div class="lp-grid lp-item lp-xs-12">
						<div class="lp-paper lp-portfolio lp-elevation lp-dense-6">
							<div class="lp-grid lp-container lp-spacing-3">
								<div class="lp-grid lp-item">
									<div class="lp-paper__title">
										<span class="lp-typo lp-footnote lp-uppercase">Портфолио</span>
									</div>
								</div>

								<div class="lp-grid lp-item lp-xs-12">
									<div class="lp-grid lp-container lp-spacing-2">
										<div class="lp-grid lp-item lp-lg-3">
											<div class="lp-card lp-portfolio__item lp-new-item" id="add-portfolio">
												<div class="lp-card__body" role="button">
													<div class="lp-card__content">
														<i class="lp-icon lp-plus-outlined"></i>
														<h4 class="lp-typo lp-sub"><b>Добавить работу</b></h4>
													</div>
												</div>
											</div>
										</div>

										<?
										$portfolio_items = new WP_Query(
											array(
												'post_type' => 'portfolio',
												'post_status' => 'publish',
												'orderby' => array( 'post_date' => 'ASC' ),
												'author' => get_current_user_id()
											)
										);

										if ( $portfolio_items->have_posts() ) :

											while ( $portfolio_items->have_posts() ) : $portfolio_items->the_post();

												get_template_part( 'template-parts/profile-portfolio-card', 'private' );

											endwhile;

										endif;

										wp_reset_postdata();
										?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="lp-grid lp-item lp-xs-12">
						<div class="lp-paper lp-testimonails lp-elevation lp-dense-6">
							<div class="lp-grid lp-container lp-spacing-3">
								<div class="lp-grid lp-item lp-xs-12 lp-flex lp-align-center lp-justify-between">
									<div class="lp-paper__title">
										<span class="lp-typo lp-footnote lp-uppercase">Отзывы</span>
									</div>
									<?
									$user_testimonials = dao_get_user_testimonials( $curr_user->ID );

									if (! empty( $user_testimonials['testimonials'] ) ) :
									?>
									<a href="<?= get_permalink( 424 ); ?>" class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-flat">
										Больше отзывов
										<i class="lp-icon lp-arrow-right-flat lp-postfix"></i>
									</a>
									<? endif; ?>
								</div>

								<div class="lp-grid lp-item lp-xs-12">
									<div class="lp-slider">
										<div class="lp-slider__wrapper lp-slider-testimonails">
											<div class="lp-grid lp-container lp-spacing-2 <?= ( empty( $user_testimonials['testimonials'] )) ? 'lp-flex lp-justify-center' : ''; ?>">
												<?
												if ( ! empty( $user_testimonials['testimonials'] ) ) :
												?>
												<div class="lp-grid lp-item lp-xs-12">
													<div class="swiper-container page-profile-slider-testimonails">
														<div class="swiper-wrapper">
														<?
															foreach ( $user_testimonials['testimonials'] as $testimonial ) :
																echo "<div class='lp-slide swiper-slide'>";
																get_template_part('template-parts/testimonial-card', null, array('testimonial' => $testimonial));
																echo "</div>";
															endforeach;
														?>
														</div>
													</div>
												</div>

												<div class="lp-grid lp-item lp-xs-12">
													<div class="lp-grid lp-container lp-spacing-3">
														<div class="lp-grid lp-item lp-xs-12">
															<div
																class="lp-grid lp-container lp-spacing-5 lp-align-center lp-justify-end">
																<div class="lp-grid lp-item">
																	<span class="lp-slider__button lp-prev">
																		<button
																			class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-flat">
																			<i class="lp-icon lp-angle-left-flat"></i>
																		</button>

																		<span class="lp-typo lp-grey">Пред</span>
																	</span>
																</div>

																<div class="lp-grid lp-item">
																	<span class="lp-slider__button lp-next">
																		<span class="lp-typo lp-grey">След</span>

																		<button
																			class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-flat">
																			<i class="lp-icon lp-angle-right-flat"></i>
																		</button>
																	</span>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?
												else:
													printf('<p class="no-testimonials">%s</p>', __('У вас пока нету отзывов.', 'dao-consensus'));
												endif;
												?>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>

					<div class="lp-grid lp-item lp-xs-12">
						<div class="lp-paper lp-offers lp-elevation lp-dense-6">
							<div class="lp-grid lp-container lp-spacing-3">
								<div class="lp-grid lp-item">
									<div class="lp-paper__title">
										<span class="lp-typo lp-footnote lp-uppercase">Предложения</span>
									</div>
								</div>

								<div class="lp-grid lp-item lp-xs-12">
									<div id="data-container" class="lp-grid lp-container lp-spacing-2">
										<?
											$args = array(
												'post_type' => 'offers',
												'post_status' => array('active', 'inactive'),
												'posts_per_page' => 6,
												'author' => $curr_user->get('ID'),
												'paged' => 1
											);

											$offers = new WP_Query( $args );

											$vars = array();

											$vars['found_posts'] = $offers->found_posts;
											$vars['max_num_pages'] = $offers->max_num_pages;
											$vars['posts_per_page'] = $offers->query_vars['posts_per_page'];

											if ( $offers->have_posts() ):
												while( $offers->have_posts() ): $offers->the_post();

													get_template_part( 'template-parts/profile-content', 'card' );

												endwhile;
											else: 
										?>
										<div class="no-results not-found">
											<p>
												<? _e('Вы ещё не создали ни одного предложения.', 'dao-consensus'); ?>
											</p>
										</div>
										<?
											endif;
										?>
									</div>
								</div>

								<!-- PAGINATION -->
								<div class="lp-grid lp-item lp-xs-12">
									<nav id="pagination" class="tui-pagination lp-pagination"></nav>
								</div>
								<!-- END PAGINATION -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="lp-modal" data-lp-modal="add-portfolio" aria-label="add new portfolio item" aria-hidden="true">
	<div class="lp-modal__wrapper">
		<form class="add-portfolio-form lp-paper lp-elevation lp-dense-6 lp-modal__inner lp-full" data-lp-modal="content">
			<header class="lp-modal__header">
				<h5 class="lp-typo lp-h5">Добавить работу в Портфолио</h5>
			</header>

			<div class="lp-modal__body">
				<div class="lp-grid lp-container lp-spacing-4">
					<div class="lp-grid lp-item lp-xs-12">
						<div class="lp-textfield lp-variant-outlined" data-lp-textfield>
							<div class="lp-textfield__input">
								<label class="lp-textfield__label">
									<input type="text" name="title" data-lp-textfield="input" />

									<span>Название работы</span>
								</label>
							</div>
						</div>
					</div>

					<div class="lp-grid lp-item lp-xs-12">
						<label class="lp-typo lp-grey" for="description">Описание</label>
						<textarea id="description" name="description"></textarea>
					</div>

					<div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
						<select class="select2 categories" name="categories[]" multiple>
							<?
								$categories = get_terms( array(
									'taxonomy' => 'categories',
									'hide_empty' => false
								));

								foreach( $categories as $cat ):
							?>
								<option value="<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></option>
							<?  endforeach; ?>
						</select>

						<div class="lp-grid lp-item">
							<span class="lp-typo lp-grey lp-footnote available-categories">Доступно для добавления <span class="counter">5</span> категорий.</span>
						</div>
					</div>


					<div class="lp-grid lp-item lp-xs-12">
						<div class="lp-grid lp-container lp-spacing-1">
							<div class="lp-grid lp-item">
								<p class="lp-typo lp-body lp-grey">Обложка работы</p>
								<span class="lp-typo lp-footnote lp-grey">
									Загрузите отдельное изображение обложки, чтобы ваша работа в каталоге смотрелась
									привлекательно
								</span>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="cover lp-file-upload">
									<div class="image-preview"></div>
									<i class="lp-icon lp-cloud-upload-flat"></i>
									<label class="lp-file-upload__label" for="cover">
										<span class="lp-typo lp-body lp-grey">Перетащите или</span>

										<span class="lp-file-upload__input">
											<input type="file" name="cover" id="cover" tabindex="-1" />
											<span role="button" tabindex="0">выберите файл</span>
										</span>
									</label>

									<input type="hidden" name="cover_check">
								</div>
							</div>

							<div class="lp-grid lp-item">
								<span class="lp-typo lp-grey lp-footnote">Рекомендуемый размер — 300х300.
									Макс. вес изображения — 3 МВ.</span>
							</div>
						</div>
					</div>

					<div class="lp-grid lp-item lp-xs-12">
						<div class="lp-grid lp-container lp-spacing-1">
							<div class="lp-grid lp-item">
								<p class="lp-typo lp-body lp-grey">
									Загрузите <span class="lp-typo lp-primary">изображение (обязательно)</span> или
									<span class="lp-typo lp-primary">видео</span>
								</p>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="media-files lp-file-upload">
									<div class="image-preview"></div>
									<i class="lp-icon lp-cloud-upload-flat"></i>
									<label class="lp-file-upload__label" for="media_files">
										<span class="lp-typo lp-body lp-grey">Перетащите или</span>

										<span class="lp-file-upload__input">
											<input type="file" name="media_files" id="media_files" tabindex="-1" multiple />
											<span role="button" tabindex="0">выберите файл</span>
										</span>
									</label>

									<input class="file-checker" type="hidden" name="media_check">
								</div>
							</div>

							<div class="lp-grid lp-item">
								<span class="lp-typo lp-grey lp-footnote">Рекомендуемый размер изображения —
									900х600. Макс. вес изображения — 3 МВ. Макс. вес видео — 50 МВ</span>
							</div>
						</div>
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
						<button type="submit"
							class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">Завершить</button>
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
				<button type="button" class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
					data-lp-modal="close">
					<i class="lp-icon lp-times-flat"></i>
				</button>
			</div>

			<input type="hidden" name="action" value="dao-add-portfolio">
			<? wp_nonce_field('dao-consensus-add-portfolio', '_wpnonce', false); ?>
		</form>
	</div>
</div>

<div class="lp-modal" data-lp-modal="edit-portfolio-item" aria-label="edit portfolio item" aria-hidden="true">
	<div class="lp-modal__wrapper">
		<form class="edit-portfolio-form lp-paper lp-elevation lp-dense-6 lp-modal__inner lp-full" data-lp-modal="content">
			<header class="lp-modal__header">
				<h5 class="lp-typo lp-h5">Редактировать работу в Портфолио</h5>
			</header>

			<div class="lp-modal__body">
				<div class="lp-grid lp-container lp-spacing-4">
					<div class="lp-grid lp-item lp-xs-12">
						<div class="title lp-textfield lp-variant-outlined" data-lp-textfield>
							<div class="lp-textfield__input">
								<label class="lp-textfield__label">
									<input type="text" name="title" data-lp-textfield="input" />

									<span>Название работы</span>
								</label>
							</div>
						</div>
					</div>

					<div class="lp-grid lp-item lp-xs-12">
						<label class="lp-typo lp-grey" for="description">Описание</label>
						<textarea id="description" name="description"></textarea>
					</div>

					<div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
						<select class="select2 categories" name="categories[]" multiple>
							<?
								$categories = get_terms( array(
									'taxonomy' => 'categories',
									'hide_empty' => false
								));

								foreach( $categories as $cat ):
							?>
								<option value="<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></option>
							<?  endforeach; ?>
						</select>

						<div class="lp-grid lp-item">
							<span class="lp-typo lp-grey lp-footnote available-categories">Доступно для добавления <span class="counter">5</span> категорий.</span>
						</div>
					</div>


					<div class="lp-grid lp-item lp-xs-12">
						<div class="lp-grid lp-container lp-spacing-1">
							<div class="lp-grid lp-item">
								<p class="lp-typo lp-body lp-grey">Обложка работы</p>
								<span class="lp-typo lp-footnote lp-grey">
									Загрузите отдельное изображение обложки, чтобы ваша работа в каталоге смотрелась
									привлекательно
								</span>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="cover lp-file-upload">
									<div class="image-preview"></div>
									<i class="lp-icon lp-cloud-upload-flat"></i>
									<label class="lp-file-upload__label" for="cover">
										<span class="lp-typo lp-body lp-grey">Перетащите или</span>

										<span class="lp-file-upload__input">
											<input type="file" name="cover" id="cover" tabindex="-1" />
											<span role="button" tabindex="0">выберите файл</span>
										</span>
									</label>

									<input type="hidden" name="cover_check">
								</div>
							</div>

							<div class="lp-grid lp-item">
								<span class="lp-typo lp-grey lp-footnote">Рекомендуемый размер — 300х300.
									Макс. вес изображения — 3 МВ.</span>
							</div>
						</div>
					</div>

					<div class="lp-grid lp-item lp-xs-12">
						<div class="lp-grid lp-container lp-spacing-1">
							<div class="lp-grid lp-item">
								<p class="lp-typo lp-body lp-grey">
									Загрузите <span class="lp-typo lp-primary">изображение (обязательно)</span> или
									<span class="lp-typo lp-primary">видео</span>
								</p>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="media-files lp-file-upload">
									<div class="image-preview"></div>
									<i class="lp-icon lp-cloud-upload-flat"></i>
									<label class="lp-file-upload__label" for="media_files">
										<span class="lp-typo lp-body lp-grey">Перетащите или</span>

										<span class="lp-file-upload__input">
											<input type="file" name="media_files" id="media_files" tabindex="-1" multiple />
											<span role="button" tabindex="0">выберите файл</span>
										</span>
									</label>
									<input class="file-checker" type="hidden" name="media_check">
									<input type="hidden" name="remove_media_files">
								</div>
							</div>

							<div class="lp-grid lp-item">
								<span class="lp-typo lp-grey lp-footnote">Рекомендуемый размер изображения —
									900х600. Макс. вес изображения — 3 МВ. Макс. вес видео — 50 МВ</span>
							</div>
						</div>
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
					<div class="lp-grid lp-item">
						<button type="button" class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-outlined"
							data-lp-modal="delete">
							Удалить
						</button>
					</div>
					<div class="lp-grid lp-item lp-flex lp-align-center">
						<button type="submit"
							class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">Завершить</button>
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
				<button type="button" class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
					data-lp-modal="close">
					<i class="lp-icon lp-times-flat"></i>
				</button>
			</div>

			<input type="hidden" name="action" value="dao-edit-portfolio-item">
			<input type="hidden" name="item_id">
			<input type="hidden" name="_wpnonce" value="<?= wp_create_nonce('dao-consensus-edit-portfolio-item'); ?>">
		</form>
	</div>
</div>

<div class="lp-modal" data-lp-modal="view-portfolio" aria-label="view portfolio item" aria-hidden="true">
	<div class="lp-modal__wrapper">
		<div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner lp-full" data-lp-modal="content">
			<header class="lp-modal__header">
				<div class="lp-inline-flex lp-align-center">
					<div class="lp-avatar">
						<img src="<?= get_avatar_url( $curr_user->get( 'ID' ), array( 'size' => 50, 'default' => 'mystery' ) ); ?>" alt="user profile picture" />
					</div>

					<div class="lp-flex lp-direction-column">
						<h5 class="title lp-typo lp-h5">Название проекта</h5>

						<div class="lp-inline-flex lp-align-center">
							<span class="display-name lp-typo lp-body"><?= $curr_user->display_name; ?></span><span class="divider">&bull;</span><span class="categories lp-typo lp-body">Категория</span>
						</div>
					</div>
				</div>
			</header>

			<div class="lp-modal__body">
				<h5 class="lp-typo lp-h5 lp-grey">Описание проекта</h5>

				<div class="lp-modal__content">
					<div class="portfolio-content">

					</div>

					<div class="portfolio-gallery">

					</div>
				</div>
			</div>

			<div class="lp-modal__close">
				<button class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
					data-lp-modal="close">
					<i class="lp-icon lp-times-flat"></i>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="lp-modal" data-lp-modal="delete-portfolio-item" aria-label="delete portfolio item modal" aria-hidden="true">
	<div class="lp-modal__wrapper">
		<form class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
			<header class="lp-modal__header">
				<h5 class="lp-typo lp-h5">Удалить работу</h5>
			</header>

			<div class="lp-modal__body">
				<p class="lp-typo lp-body">Вы действительно хотите удалить работу "<span class="title"></span>" с Портфолио?</p>
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
						<button type="submit" class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
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
				<button type="button" class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
					data-lp-close data-lp-modal="close">
					<i class="lp-icon lp-times-flat"></i>
				</button>
			</div>

			<input type="hidden" name="action" value="dao-delete-portfolio-item">
			<input type="hidden" name="item_id">
			<input type="hidden" name="_wpnonce" value="<?= wp_create_nonce('dao-consensus-delete-portfolio-item'); ?>">
		</form>
	</div>
</div>


<? get_footer('', array( 'query_vars' => $args, 'vars' => $vars, 'nonce' => wp_create_nonce('dao-consensus-get-profile-cards') ) ); ?>
