<?php /* Template Name: Profile Demand */ ?>

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
                                        <button class="lp-button-base lp-button-tab lp-selected" data-lp-selected="true">Мой спрос</button>
                                    </div>
                                </div>

                                <div class="lp-grid lp-item lp-lg-4 lp-md-5 lp-sm-6 lp-xs-12">
                                    <a href="<?php echo get_permalink(19) . '?type=demand'; ?>" class="lp-button-base lp-button lp-size-large lp-theme-primary lp-variant-outlined lp-full">
                                        Создать <i class="lp-icon lp-postfix lp-plus-flat"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <?
                            $args = array(
                                'post_type' => 'demands',
                                'post_status' => ['active', 'inactive'],
                                'posts_per_page' => 9,
                                'author' => $current_user->get('ID'),
                                'paged' => 1
                            );

                            $demands = new WP_Query( $args );

                            $vars = array();

                            $vars['query_vars'] = $args;
                            $vars['found_posts'] = $demands->found_posts;
                            $vars['max_num_pages'] = $demands->max_num_pages;
                            $vars['posts_per_page'] = $demands->query_vars['posts_per_page'];

                            if ( $demands->have_posts() ):
                        ?>
                        <div class="lp-grid lp-item lp-xs-12">
                            <form class="filters lp-grid lp-container lp-spacing-2 lp-flex lp-align-center">
                                <div class="lp-grid lp-item lp-lg-8">
                                    <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-align-center">
                                        <div class="lp-item">
                                            <button type="button" id="publication_date"
                                                class="lp-button-base lp-button lp-size-small lp-theme-secondary lp-variant-flat">
                                                Дата <i class="lp-icon lp-postfix lp-triangle-filter"></i>
                                            </button>
                                        </div>

                                        <div class="lp-grid lp-item lp-xs-3">
                                            <div id="status" class="lp-select" data-lp-select>
                                                <div class="lp-select__textfield lp-textfield lp-variant-flat"
                                                    data-lp-select="textfield">
                                                    <div class="lp-textfield__input">
                                                        <label class="lp-textfield__label">
                                                            <span data-lp-select="value"></span>
                                                            <input name="status" type="hidden" data-lp-textfield="input"
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
                                                        <? foreach( DAO_CONSENSUS::card_statuses as $k => $v ): ?>
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
                                                while( $demands->have_posts() ): $demands->the_post();

                                                    get_template_part( 'template-parts/profile-content-card' );

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
                        <?  else: ?>
                                <div class="no-results not-found">
                                    <p>
                                        <? _e('Вы ещё не опубликовали ни одного спроса.', 'dao-consensus'); ?>
                                    </p>
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

<? get_template_part( 'template-parts/snackbar' ); ?>

<? get_footer( '', $vars ); ?>
