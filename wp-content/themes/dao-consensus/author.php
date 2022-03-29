<?php /* Template Name: Profile */ ?>

<? get_header(); ?>

<div class="lp-page lp-page-profile">
    <div class="lp-wrapper">
        <div class="lp-grid lp-container lp-justify-center">
            <?
                $curr_author = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) );
            ?>
            <div class="lp-grid lp-item lp-lg-9 lp-md-8 lp-xs-12">
                <div class="lp-grid lp-container lp-spacing-3">
                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-paper lp-common-info lp-elevation lp-dense-6">
                            <div class="lp-grid lp-container lp-spacing-3">
                                <div class="lp-grid lp-item lp-lg-4 lp-md-12 lp-xs-12">
                                    <div class="lp-common-info__avatar lp-avatar">
                                        <img src="<?= get_avatar_url( $curr_author->get('ID'), array('size' => 300, 'default' => 'mystery') ); ?>"
                                            alt="profile avatar" />
                                    </div>

                                    <div class="lp-common-info__info">
                                        <ul class="lp-list lp-no-style">
                                            <li>
                                                <span class="lp-typo lp-footnote lp-grey">
                                                    <i class="lp-icon lp-user-outlined"></i>
                                                    <?= DAO_CONSENSUS::person_types[get_user_meta( $curr_author->get('ID'), 'person_type', true )]; ?>
                                                </span>
                                            </li>

                                            <li>
                                                <span class="lp-typo lp-footnote lp-grey">
                                                    <?
													$user_activity = dao_is_user_online( $curr_author->get('ID') );
													?>
													<i class="lp-icon lp-users-outlined"></i>
													<?
														if ( $user_activity === true ) {
															echo 'Онлайн';
														} else {
															$curr_time = new DateTime( current_time( 'mysql' ) );
															$last_act_time = new DateTime();
                                                            $last_act_time->setTimestamp( $user_activity );

															$diff = $curr_time->diff( $last_act_time )->format('%H часов %i минут');
															echo "Офлайн {$diff}";
														}
													?>
                                                </span>
                                            </li>

                                            <li>
                                                <span class="lp-typo lp-footnote lp-grey">
                                                    <i class="lp-icon lp-clock-outlined"></i>
                                                    <?= "На сайте c " . eng_months_to_ru( date( 'j F Y', strtotime( $curr_author->get('user_registered') ) ) ); ?>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                    <hr class="lp-divider" />

                                    <div class="lp-common-info__stats">
                                        <ul class="lp-list lp-no-style">
                                            <li>
                                                <span class="lp-typo lp-sub"><b><?= dao_count_user_completed_transactions( $curr_author->ID ); ?></b></span>
                                                <span class="lp-typo lp-footnote lp-grey"> сделок совершено </span>
                                            </li>

                                            <li>
                                                <span class="lp-typo lp-sub"><b><?= dao_count_user_testimonials( $curr_author->ID ); ?></b></span>
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
                                            <b><?= dao_get_user_rating( (int) get_user_meta( $curr_author->ID, 'rating', true ) ); ?></b>
                                        </span>

                                        <span class="lp-typo lp-footnote lp-grey"><?= dao_get_user_rating_label( $curr_author->ID ); ?></span>
                                    </div>
                                    <hr class="lp-divider" />

                                    <div class="lp-common-info__share">
                                        <a href="<?= get_author_posts_url( $curr_author->ID ); ?>" class="lp-link lp-theme-primary">
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
                                            <h4 class="lp-typo lp-h4"><?= $curr_author->get('display_name'); ?></h4>
                                            <h5 class="lp-typo lp-h5"><?= esc_html( get_user_meta( $curr_author->get('ID'), 'specialization', true ) ); ?></h5>
                                        </div>
                                    </div>

                                    <div class="lp-common-info__content">
                                        <p>
                                            <?= esc_html( get_user_meta( $curr_author->get('ID'), 'description', true ) ); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <? 
                    $profile_skills = get_user_meta( $curr_author->get('ID'), 'skills', true ); 
                    if ( ! empty( $profile_skills ) ):
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
                                    <div class="lp-skills__chips lp-flex lp-wrap">
                                        <?
                                            foreach( $profile_skills as $skill ):
                                        ?>
                                                <div class="lp-chip lp-default lp-variant-outlined">
                                                    <span class="lp-chip__label"><?= $skill ?></span>
                                                </div>
                                        <?
                                            endforeach;
                                        ?>
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
                                <?
                                    $portfolio_items = new WP_Query(
                                        array(
                                            'post_type' => 'portfolio',
                                            'post_status' => 'publish',
                                            'orderby' => array( 'post_date' => 'ASC' ),
                                            'author' => $curr_author->ID
                                        )
                                    );
                                ?>
								<div class="lp-grid lp-item lp-xs-12">
									<div class="lp-grid lp-container lp-spacing-2 <?= ( ! $portfolio_items->have_posts() ) ? 'lp-justify-center' : ''; ?>">
										<?
										if ( $portfolio_items->have_posts() ) :

											while ( $portfolio_items->have_posts() ) : $portfolio_items->the_post();

												get_template_part( 'template-parts/profile-portfolio-card', 'public' );

											endwhile;

										else:
                                            printf('<p class="no-portfolio">%s</p>', __('У даного пользователя пока нету работ в портфолио.', 'dao-consensus'));
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
									$user_testimonials = dao_get_user_testimonials( $curr_author->ID );

									if (! empty( $user_testimonials['testimonials'] ) ) :
									?>
									<!-- <a href="#" class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-flat">
										Больше отзывов
										<i class="lp-icon lp-arrow-right-flat lp-postfix"></i>
									</a> -->
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
														printf('<p class="no-testimonials">%s</p>', __('У данного пользователя пока нету отзывов.', 'dao-consensus'));
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
                                                'posts_per_page' => 9,
                                                'author' => $curr_author->get('ID'),
                                                'paged' => 1
                                            );

                                            $cards = new WP_Query( $args );

                                            $vars = array();

                                            $vars['found_posts'] = $cards->found_posts;
                                            $vars['max_num_pages'] = $cards->max_num_pages;
                                            $vars['posts_per_page'] = $cards->query_vars['posts_per_page'];

                                            if ( $cards->have_posts() ):
                                                while( $cards->have_posts() ): $cards->the_post();

                                                    get_template_part( 'template-parts/public-profile-content-card' );

                                                endwhile;
                                            else: ?>
                                                <div class="no-results not-found">
                                                    <p>
                                                        <? _e('У данного пользователя нету предложений.', 'dao-consensus'); ?>
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

<div class="lp-modal" data-lp-modal="view-portfolio" aria-label="view portfolio item" aria-hidden="true">
	<div class="lp-modal__wrapper">
		<div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner lp-full" data-lp-modal="content">
			<header class="lp-modal__header">
				<div class="lp-inline-flex lp-align-center">
					<div class="lp-avatar">
						<img src="<?= get_avatar_url( $curr_author->get( 'ID' ), array( 'size' => 50, 'default' => 'mystery' ) ); ?>" alt="user profile picture" />
					</div>

					<div class="lp-flex lp-direction-column">
						<h5 class="title lp-typo lp-h5">Название проекта</h5>

						<div class="lp-inline-flex lp-align-center">
							<span class="display-name lp-typo lp-body"><?= dao_get_user_display_name( $curr_author->ID ); ?></span>&bull;<span class="categories lp-typo lp-body">Категории</span>
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

<? get_footer( '', array( 'query_vars' => $args ) + array( 'vars' => $vars ) + array( 'nonce' => wp_create_nonce( 'dao_get_profile_offers' ) ) ); ?>