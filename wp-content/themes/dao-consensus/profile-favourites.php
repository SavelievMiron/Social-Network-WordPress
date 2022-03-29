<? /* Template Name: Profile Favourites */ ?>

<? get_header(); ?>

<div class="lp-page lp-demand-page">
	<div class="lp-wrapper">
		<div class="lp-grid lp-container lp-spacing-3">
			<div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-12">
				<? get_template_part('template-parts/profile', 'sidebar'); ?>
			</div>

			<? $current_user = wp_get_current_user(); ?>

			<div class="lp-grid lp-item lp-lg-9 lp-md-8 lp-sm-12">
				<div class="lp-paper lp-elevation lp-dense-6">
					<div id="tabs" class="lp-grid lp-container lp-spacing-3" data-lp-tabs>
						<div class="lp-grid lp-item lp-xs-12">
							<div class="lp-grid lp-container lp-spacing-2 lp-align-center">
								<div class="lp-grid lp-item lp-lg-8 lp-md-7 lp-sm-6 lp-xs-12">
									<div class="lp-tabs" data-lp-tabs="tabs">
										<button class="lp-button-base lp-button-tab lp-selected" data-lp-selected="true">Избранное</button>
									</div>
								</div>
							</div>
						</div>
						<?
                        $favourites = get_user_meta( $current_user->ID, 'favourites', true );

                        if ( is_array( $favourites ) && ! empty( $favourites ) ) :

                            $args = array(
                                'post_type' => ['demands', 'offers'],
                                'post__in' => $favourites,
                                'post_status' => ['active', 'inactive'],
                                'posts_per_page' => 1,
                                'paged' => 1
                            );
            
                            $cards = new WP_Query( $args );
                            
                            $vars = array();
                            $vars['query_vars'] = $args;
                            $vars['found_posts'] = $cards->found_posts;
                            $vars['max_num_pages'] = $cards->max_num_pages;
                            $vars['posts_per_page'] = $cards->query_vars['posts_per_page'];

                            if ( $cards->have_posts() ): ?>
                            <div class="lp-grid lp-item lp-xs-12">
                                <form class="filters lp-grid lp-container lp-spacing-2 lp-flex lp-align-center">
                                    <div class="lp-grid lp-item lp-lg-8">
                                        <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-align-center">
                                            <div class="lp-grid lp-item lp-xs-4">
                                                <div id="categories" class="lp-select" data-lp-select>
                                                    <div class="lp-select__textfield lp-textfield lp-variant-flat"
                                                        data-lp-select="textfield">
                                                        <div class="lp-textfield__input">
                                                            <label class="lp-textfield__label">
                                                                <span data-lp-select="value"></span>
                                                                <input name="categories" type="hidden" data-lp-textfield="input"
                                                                    data-lp-select="input" />
                                                                <span>Категории</span>
                                                            </label>

                                                            <div class="lp-textfield__postfix">
                                                                <button type="button"
                                                                    class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded" data-lp-select="clear">
                                                                    <i class="lp-icon lp-times-flat"></i>
                                                                </button>
                                                                <button type="button"
                                                                    class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
                                                                    data-lp-select="button">
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
                                                        <ul class="lp-list lp-no-style" data-lp-select="options">
                                                            <?
                                                                $categories = get_terms( array(
                                                                    'taxonomy' => 'categories',
                                                                    'hide_empty' => false
                                                                ));

                                                                foreach( $categories as $cat ):
                                                            ?>
                                                            <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                                                                data-lp-value="<?php echo $cat->slug; ?>">
                                                                <span><?php echo $cat->name; ?></span>
                                                            </li>
                                                            <? endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="lp-grid lp-item lp-lg-4">
                                        <div id="search" class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                            <div class="lp-textfield__input">
                                                <label class="lp-textfield__label">
                                                    <input name="search_query" type="text" data-lp-textfield="input" />
                                                    <span>Поиск</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="lp-grid lp-item lp-xs-12">
                                <div class="selected-categories lp-grid lp-container lp-spacing-2">
                                    
                                </div>
                            </div>
                            <div class="lp-grid lp-item lp-xs-12">
                                <div class="lp-grid lp-container lp-spacing-3" data-lp-tabs="views">
                                    <div class="lp-grid lp-item lp-xs-12">
                                        <div class="lp-grid lp-container lp-spacing-3">
                                            <div class="lp-grid lp-item lp-xs-12">
                                                <div id="data-container" class="lp-grid lp-container lp-spacing-2">
                                                <?
                                                    print_r( $cards );
                                                    while( $cards->have_posts() ): $cards->the_post();
                                                        echo 1;
                                                        get_template_part( 'template-parts/profile-content-card', 'favourite' );

                                                    endwhile;
                                                ?>
                                                </div>
                                            </div>

                                            <div class="lp-grid lp-item lp-xs-12">
                                                <div class="lp-grid lp-item lp-xs-12">
                                                    <nav id="pagination" class="tui-pagination lp-pagination"></nav>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?
                            else:
                            ?>
                            <div class="lp-grid lp-item lp-xs-12 lp-flex lp-justify-center">
                                <p class="no-favourites"><? _e('В Избранное пока что ничего не добавлено.', 'dao-consensus') ?></p>
                            </div>
                            <?
                            endif;

                            wp_reset_postdata();
                            
                        else:
                        ?>
                            <div class="lp-grid lp-item lp-xs-12 lp-flex lp-justify-center">
                                <p class="no-favourites"><? _e('В Избранное пока что ничего не добавлено.', 'dao-consensus') ?></p>
                            </div>
                        <?
                        endif;
                        ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<? get_template_part( 'template-parts/snackbar' ); ?>

<? get_footer( '', $vars ); ?>
