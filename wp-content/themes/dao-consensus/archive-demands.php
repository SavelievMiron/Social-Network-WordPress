<? get_header(); ?>

<div class="lp-page lp-page-demand">
    <div class="lp-wrapper">
        <div class="lp-grid lp-container lp-spacing-6">
            <div class="lp-grid lp-item lp-xs-12 lp-flex lp-justify-center">
                <h1 class="lp-typo lp-h1">
                    <? post_type_archive_title(); ?>
                </h1>
            </div>

            <!-- FORM FILTERS -->
            <form class="archive-filters lp-grid lp-item lp-xs-12 lp-flex" action="">
                <!-- SEARCH -->
                <div class="lp-grid lp-item lp-xs-12 lp-flex lp-justify-center">
                    <div class="lp-grid lp-item lp-lg-6 lp-md-8 lp-xs-12">
                        <div id="search" class="lp-textfield lp-variant-outlined" data-lp-textfield>
                            <div class="lp-textfield__input">
                                <label class="lp-textfield__label">
                                    <input name="search_query" type="text" data-lp-textfield="input" />
                                    <span>Поиск по именам или заголовкам</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SEARCH -->

                <!-- FILTERS -->
                <div class="lp-grid lp-item lp-xs-12 lp-mt-10 lp-mb-10">
                    <div class="lp-grid lp-container lp-spacing-3">
                        <div class="lp-grid lp-item lp-lg-8 lp-md-8 lp-xs-12 lp-flex">
                            <div class="lp-grid lp-container lp-spacing-1 lp-align-center">
                                <div class="lp-item">
                                    <button type="button" id="publication_date"
                                        class="lp-button-base lp-button lp-size-small lp-theme-secondary lp-variant-flat">
                                        Дата публикации <i class="lp-icon lp-postfix lp-triangle-filter"></i>
                                    </button>
                                </div>

                                <div class="lp-item">
                                    <button type="button" id="rating"
                                        class="lp-button-base lp-button lp-size-small lp-theme-secondary lp-variant-flat">
                                        Рейтинг <i class="lp-icon lp-postfix lp-triangle-filter"></i>
                                    </button>
                                </div>

                                <div class="lp-grid lp-item lp-lg-3 lp-md-3 lp-xs-3 lp-xs-ml-auto">
                                    <div id="status" class="lp-select" data-lp-select>
                                        <div class="lp-select__textfield lp-textfield lp-variant-outlined" data-lp-select="textfield">
                                            <div class="lp-textfield__input">
                                            <label class="lp-textfield__label">
                                                <span data-lp-select="value"></span>
                                                <input type="hidden" name="status" data-lp-textfield="input" data-lp-select="input" />

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
                                                <i class="lp-icon lp-angle-down-flat"></i>
                                                </button>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="lp-select__options lp-paper lp-elevation lp-dense-3" data-lp-select="popover"
                                            aria-hidden="true">
                                            <div class="lp-select__search lp-textfield lp-variant-outlined" data-lp-select="search">
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                    <input type="text" data-lp-textfield="input" />

                                                    <span>Поиск</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <ul class="lp-list lp-no-style" data-lp-select="options">
                                                <? foreach( DAO_CONSENSUS::archive_statuses as $k => $v ): ?>
                                                    <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                                                        data-lp-value="<?= $k; ?>">
                                                        <span><?= $v; ?></span>
                                                    </li>
                                                <? endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="lp-grid lp-item lp-lg-3 lp-md-3 lp-xs-3">
                                    <div id="person_type" class="lp-select" data-lp-select>
                                        <div class="lp-select__textfield lp-textfield lp-variant-outlined" data-lp-select="textfield">
                                            <div class="lp-textfield__input">
                                            <label class="lp-textfield__label">
                                                <span data-lp-select="value"></span>
                                                <input type="hidden" name="person_type" data-lp-textfield="input" data-lp-select="input" />

                                                <span>Лицо</span>
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
                                                <i class="lp-icon lp-angle-down-flat"></i>
                                                </button>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="lp-select__options lp-paper lp-elevation lp-dense-3" data-lp-select="popover"
                                            aria-hidden="true">
                                            <div class="lp-select__search lp-textfield lp-variant-outlined" data-lp-select="search">
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                    <input type="text" data-lp-textfield="input" />

                                                    <span>Поиск</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <ul class="lp-list lp-no-style" data-lp-select="options">
                                                <? foreach( DAO_CONSENSUS::person_types as $k => $v ): ?>
                                                    <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                                                        data-lp-value="<?= $k ?>">
                                                        <span><?= $v ?></span>
                                                    </li>
                                                <? endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-lg-4 lp-md-4 lp-xs-12 lp-flex">
                            <div class="lp-grid lp-container lp-spacing-2 lp-justify-end">
                                <div class="lp-grid lp-item lp-lg-7 lp-md-7 lp-xs-4">

                                    <?
                                        $category = $_GET['category'] ?? '';
                                    ?>
                                    
                                    <div class="lp-select" data-lp-select id="categories">
                                        <div class="lp-select__textfield lp-textfield lp-variant-outlined"
                                            data-lp-select="textfield">
                                            <div class="lp-textfield__input">
                                                <label class="lp-textfield__label">
                                                    <span data-lp-select="value"></span>
                                                    <input type="hidden" name="categories" data-lp-textfield="input"
                                                        data-lp-select="input" />

                                                    <span>Категории</span>
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
                                                        <i class="lp-icon lp-angle-down-flat"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-select__options lp-paper lp-elevation lp-dense-3"
                                            data-lp-select="popover" aria-hidden="true">
                                            <div class="lp-select__search lp-textfield lp-variant-outlined" data-lp-select="search">
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                        <input type="text" data-lp-textfield="input" />
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
													data-lp-value="<?php echo $cat->slug; ?>" <?php echo ( ! empty( $category ) && $category === $cat->slug ) ? 'data-lp-selected="true"' : ''; ?>>
													<span><?php echo $cat->name; ?></span>
												</li>
												<?  endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END FILTERS -->
            </form>
            <!-- END FORM FILTERS -->

            <div class="lp-grid lp-item lp-xs-12 lp-flex">
				<div class="selected-categories lp-grid lp-container lp-spacing-2 lp-align-center">
					<?
						foreach ( $categories as $cat ):
							if ( $cat->slug === $category ):
					?>
					<div class="tag lp-grid lp-item lp-chip lp-default lp-variant-outlined" data-text="<?php echo esc_attr( $cat->name ); ?>" data-value="<?php echo esc_attr( $cat->slug ); ?>">
						<span class="lp-chip__label"><?php echo esc_attr( $cat->name ); ?></span> <button type="button" class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded" data-lp-select="clear"> <i class="lp-icon lp-times-flat"></i> </button> 
					</div>
					<?      endif;
						endforeach; 
					?>
				</div>
			</div>

            <!-- DEMANDS -->
            <div class="lp-grid lp-item lp-xs-12">
                <div id="data-container" class="lp-grid lp-container lp-spacing-4">
                    <?php if ( have_posts() ) :

                        while ( have_posts() ) :
                            the_post();

                            get_template_part( 'template-parts/content', get_post_type() );

                        endwhile;

                    else :

                        get_template_part( 'template-parts/content', 'none' );

                    endif;
                    ?>
                </div>
            </div>
            <!-- END DEMANDS -->

            <!-- PAGINATION -->
            <div class="lp-grid lp-item lp-xs-12">
                <nav id="pagination" class="tui-pagination lp-pagination" data-lp-pagination></nav>
            </div>
            <!-- END PAGINATION -->
        </div>
    </div>
</div>

<? get_footer(); ?>