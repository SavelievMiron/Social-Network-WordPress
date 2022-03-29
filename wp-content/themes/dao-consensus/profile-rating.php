<?php /* Template Name: Profile Rating */ ?>

<? get_header(); ?>

<div class="lp-page lp-page-rating">
    <div class="lp-wrapper">
        <div class="lp-grid lp-container lp-spacing-3">
            <div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-12">
                <? get_template_part('template-parts/profile', 'sidebar'); ?>
            </div>

            <div class="lp-grid lp-item lp-lg-9 lp-md-8 lp-sm-12">
                <div class="lp-paper lp-data-grid lp-elevation lp-dense-6">
                    <div class="lp-grid lp-container lp-spacing-3">
                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-data-grid__table lp-table lp-table-rating">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="lp-cell-inline">
                                                <div class="lp-flex lp-justify-center">
                                                    <span class="lp-typo lp-sub lp-grey">№</span>
                                                </div>
                                            </th>

                                            <th class="lp-align-center">
                                                <div class="lp-flex lp-justify-center">
                                                    <span class="lp-typo lp-sub lp-grey">ФИО</span>
                                                </div>
                                            </th>

                                            <th class="lp-align-center">
                                                <div class="lp-flex lp-justify-center">
                                                    <span class="lp-typo lp-sub lp-grey">Рейтинг</span>
                                                    <button id="rating" class="lp-button-base lp-button-icon lp-size-small lp-theme-primary lp-variant-flat lp-rounded">
                                                        <i class="lp-icon lp-triangle-down"></i>
                                                    </button>
                                                </div>
                                            </th>

                                            <th class="lp-align-center">
                                                <div class="lp-flex lp-justify-center">
                                                    <span class="lp-typo lp-sub lp-grey">Количество встреч</span>
                                                    <button id="meetings" class="lp-button-base lp-button-icon lp-size-small lp-theme-primary lp-variant-flat lp-rounded">
                                                        <i class="lp-icon lp-triangle-filter"></i>
                                                    </button>
                                                </div>
                                            </th>

                                            <th class="lp-align-center">
                                                <div class="lp-flex lp-justify-center">
                                                    <span class="lp-typo lp-sub lp-grey">Количество сделок</span>
                                                    <button id="transactions" class="lp-button-base lp-button-icon lp-size-small lp-theme-primary lp-variant-flat lp-rounded">
                                                        <i class="lp-icon lp-triangle-filter"></i>
                                                    </button>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="data-container">
                                        <?
                                            $args = array(
                                                'role__in' => 'contributor',
                                                'orderby' => array('rating' => 'DESC', 'completed_transactions' => 'DESC'),
                                                'meta_query' => array(
                                                    'rating' => array(
                                                        'key' => 'rating',
                                                        'compare' => 'EXISTS',
                                                        'type' => 'decimal',
                                                    ),
                                                    'completed_transactions' => array(
                                                        'key' => 'completed_transactions',
                                                        'compare' => 'EXISTS',
                                                        'type' => 'integer',
                                                    )
                                                ),
                                                'fields' => 'all',
                                                'number' => 10,
                                                'paged' => 1
                                            );

                                            $users = new WP_User_Query( $args );

                                            $found_users = $users->get_total();
                                            $max_num_pages = ceil( $users->get_total() / $args['number'] );

                                            $result = $users->get_results();

                                            if ( ! empty( $result ) ) :
                                                foreach ( $result as $k => $user ) :
                                                    get_template_part('template-parts/profile-rating-row', null, array('user' => $user, 'per_page' => $args['number'], 'page' => $args['paged'], 'index' => $k + 1));
                                                endforeach;
                                            else: 
                                                printf('<p class="no-users">%s</p>', __('Пока ни одного пользователя не зарегистрировано.', 'dao-consensus'));
                                            endif;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-grid lp-item lp-xs-12">
                                <nav id="pagination" class="tui-pagination lp-pagination">
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<? get_footer('', array( 'query_vars' => $args, 'found_users' => $found_users, 'max_num_pages' => $max_num_pages ) ); ?>
