<?php /* Template Name: Profile Inprocess */ ?>

<? get_header(); ?>

<?
    $curr_user = wp_get_current_user();

    $args1 = array(
        'post_type' => 'demands',
        'post_status' => 'in-process',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'author' => $curr_user->get('ID'),
    );

    $args2 = array(
        'post_type' => 'demands',
        'post_status' => 'in-process',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => 'performer',
        'meta_value' => $curr_user->get('ID')
    );

    $demands_in_process1 = new WP_Query( $args1 );
    $demands_in_process2 = new WP_Query( $args2 );

    unset( $args1 );
    unset( $args2 );

    $args1 = array(
        'post_type' => 'offers',
        'post_status' => 'active',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'author' => $curr_user->get('ID'),
        'meta_key' => 'customers',
        'meta_value' => array('', 'a:0:{}'),
        'meta_compare' => 'NOT IN'
    );

    $args2 = array(
        'post_type' => 'offers',
        'post_status' => 'active',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => 'customers',
        'meta_value' => ":{i:{$curr_user->get('ID')};",
        'meta_compare' => 'LIKE'
    );

    $offers_in_process1 = new WP_Query( $args1 );
    $offers_in_process2 = new WP_Query( $args2 );

    $demands_in_process = array_merge( $demands_in_process1->posts, $demands_in_process2->posts );
    $offers_in_process = array_merge( $offers_in_process1->posts, $offers_in_process2->posts );

?>

<div class="lp-page lp-page-in-progress">
    <div class="lp-wrapper">
        <div class="lp-grid lp-container lp-spacing-3">
            <div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-12">
                <? get_template_part('template-parts/profile', 'sidebar'); ?>
            </div>


            <div class="lp-grid lp-item lp-lg-9 lp-md-8 lp-sm-12">
                <div class="lp-paper lp-elevation lp-dense-6">
                    <div class="lp-grid lp-container lp-spacing-3" data-lp-tabs id="card-types">
                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-grid lp-container lp-spacing-2 lp-align-center">
                                <div class="lp-grid lp-item lp-xs-12">
                                    <div class="lp-tabs" data-lp-tabs="tabs">
                                        <button class="lp-button-base lp-button-tab" data-lp-selected="true">
                                            <? printf('%s', __('Спросы', 'dao-consensus') ); ?>
                                        </button>
                                        <button class="lp-button-base lp-button-tab">
                                            <? printf('%s', __('Предложения', 'dao-consensus') ); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lp-grid lp-item lp-xs-12">
							<div class="lp-grid lp-container lp-spacing-3" data-lp-tabs="views">
                                <div class="demands-inprocess lp-grid lp-item lp-xs-12">
                                    <?
                                    if ( ! empty( $demands_in_process ) ) :

                                        $args = array(
                                            'post_type' => 'demands',
                                            'post__in' => $demands_in_process,
                                            'posts_per_page' => 6
                                        );

                                        $demands = new WP_Query( $args );

                                        $demands_vars = array();

                                        $demands_vars['query_vars'] = $args;
                                        $demands_vars['found_posts'] = $demands->found_posts;
                                        $demands_vars['max_num_pages'] = $demands->max_num_pages;
                                        $demands_vars['posts_per_page'] = $demands->query_vars['posts_per_page'];

                                        if ( $demands->have_posts() ): ?>
                                            <div class="lp-grid lp-item lp-xs-12">
                                                <form class="filters lp-grid lp-container lp-spacing-2 lp-flex lp-align-center">
                                                    <div class="lp-grid lp-item lp-lg-8">
                                                        <div class="lp-grid lp-item lp-lg-4">
                                                            <div class="type lp-select" data-lp-select>
                                                                <div class="lp-select__textfield lp-textfield lp-variant-outlined"
                                                                data-lp-select="textfield">
                                                                    <div class="lp-textfield__input">
                                                                        <label class="lp-textfield__label">
                                                                            <span data-lp-select="value"></span>
                                                                            <input type="hidden" name="type" data-lp-textfield="input" data-lp-select="input" />

                                                                            <span>Тип</span>
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
                                                                        <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                                                                        data-lp-value="mine">
                                                                            <span>Мои</span>
                                                                        </li>

                                                                        <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                                                                        data-lp-value="other">
                                                                            <span>Другие</span>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="lp-grid lp-item lp-lg-4">
                                                        <div class="lp-textfield lp-variant-outlined" data-lp-textfield id="search">
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

                                            <div class="lp-grid lp-item lp-xs-12 lp-mt-20">
                                                <div id="data-container" class="lp-grid lp-container lp-spacing-2">
                                                    <?
                                                    while( $demands->have_posts() ): $demands->the_post();

                                                        get_template_part( 'template-parts/profile-content-demand', 'inprocess' );

                                                    endwhile;
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="lp-grid lp-item lp-xs-12">
                                                <div class="lp-grid lp-item lp-xs-12">
                                                    <nav id="demands-pagination" class="tui-pagination lp-pagination"></nav>
                                                </div>
                                            </div>
                                        <? 
                                        endif;

                                        wp_reset_postdata();
                                        ?>
                                    <? 
                                    else: 
                                    ?>
                                        <div class="no-results not-found">
                                            <p>
                                                <? _e('Ни одного спроса в работе пока ещё нету.', 'dao-consensus'); ?>
                                            </p>
                                        </div>
                                    <? 
                                    endif; 
                                    ?>
                                </div>
                                <div class="offers-inprocess lp-grid lp-item lp-xs-12">
                                    <?
                                    if ( ! empty( $offers_in_process ) ) :

                                        $args = array(
                                            'post_type' => 'offers',
                                            'post__in' => $offers_in_process,
                                            'posts_per_page' => 6
                                        );

                                        $offers = new WP_Query( $args );

                                        $offers_vars = array();

                                        $offers_vars['query_vars'] = $args;
                                        $offers_vars['found_posts'] = $offers->found_posts;
                                        $offers_vars['max_num_pages'] = $offers->max_num_pages;
                                        $offers_vars['posts_per_page'] = $offers->query_vars['posts_per_page'];

                                        if ( $offers->have_posts() ): ?>
                                            <div class="lp-grid lp-item lp-xs-12">
                                                <form class="filters lp-grid lp-container lp-spacing-2 lp-flex lp-align-center">
                                                    <div class="lp-grid lp-item lp-lg-8">
                                                        <div class="lp-grid lp-item lp-lg-4">
                                                            <div class="type lp-select" data-lp-select>
                                                                <div class="lp-select__textfield lp-textfield lp-variant-outlined"
                                                                data-lp-select="textfield">
                                                                    <div class="lp-textfield__input">
                                                                        <label class="lp-textfield__label">
                                                                            <span data-lp-select="value"></span>
                                                                            <input type="hidden" name="type" data-lp-textfield="input" data-lp-select="input" />

                                                                            <span>Тип</span>
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
                                                                        <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                                                                        data-lp-value="mine">
                                                                            <span>Мои</span>
                                                                        </li>

                                                                        <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                                                                        data-lp-value="other">
                                                                            <span>Другие</span>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="lp-grid lp-item lp-lg-4">
                                                        <div class="lp-textfield lp-variant-outlined" data-lp-textfield id="search">
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

                                            <div class="lp-grid lp-item lp-xs-12 lp-mt-20">
                                                <div id="data-container" class="lp-grid lp-container lp-spacing-2">
                                                    <?
                                                    while( $offers->have_posts() ): $offers->the_post();

                                                        get_template_part( 'template-parts/profile-content-offer', 'inprocess' );

                                                    endwhile;
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="lp-grid lp-item lp-xs-12">
                                                <div class="lp-grid lp-item lp-xs-12">
                                                    <nav id="offers-pagination" class="tui-pagination lp-pagination"></nav>
                                                </div>
                                            </div>
                                        <? 
                                        endif;

                                        wp_reset_postdata();
                                        ?>
                                    <? 
                                    else: 
                                    ?>
                                        <div class="no-results not-found">
                                            <p>
                                                <? _e('Ничего в работе пока ещё нету.', 'dao-consensus'); ?>
                                            </p>
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
        </div>
    </div>
</div>

<?
    $data = array();
    if ( isset( $demands_vars ) ) {
        $data['demands'] = $demands_vars;
    }
    if ( isset( $offers_vars ) ) {
        $data['offers'] = $offers_vars;
    }

    get_footer( '', $data ); 
?>
