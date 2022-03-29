<?php /* Template Name: Front Page*/ ?>

<? get_header(); ?>

<div class="lp-page lp-page-home">
    <div class="lp-grid lp-container lp-spacing-20">
        <div class="lp-grid lp-item lp-xs-12">
            <div class="lp-wrapper lp-banner">
                <div class="lp-grid lp-container lp-spacing-3">
                    <div class="lp-grid lp-item lp-lg-9 lp-md-10">
                        <h1 class="lp-typo lp-h1">DAO Consensus — business community of digital projects</h1>
                    </div>

                    <div class="lp-grid lp-item lp-lg-3 lp-md-3">
                        <h2 class="lp-typo lp-h5 lp-primary">Главная информация о разработке блокчейн проектов</h2>
                    </div>

                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-grid lp-item lp-lg-4 lp-md-4">
                            <p class="lp-typo lp-body">Get a reliable digital ecosystem for business and investment</p>
                        </div>
                    </div>

                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-grid lp-item lp-lg-4">
                            <a href="<?php echo get_permalink( 19 ); ?>" class="lp-button-base lp-button lp-size-large lp-theme-primary lp-variant-filled">
                                Создать спрос/предложение
                                <i class="lp-icon lp-arrow-right-flat lp-postfix"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lp-grid lp-item lp-xs-12">
            <div class="lp-wrapper lp-arrow-down">
                <div class="lp-flex lp-justify-center">
                    <button
                        class="lp-button-base lp-button-icon lp-size-large lp-theme-secondary lp-variant-flat lp-rounded">
                        <i class="lp-icon lp-angle-down-outlined"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="lp-grid lp-item lp-xs-12">
            <div class="lp-wrapper">
                <div class="lp-grid lp-container lp-spacing-3">
                    <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                        <div class="lp-flex lp-direction-column lp-align-center lp-justify-center">
                            <h3 class="lp-typo lp-h5">Общее количество спросов</h3>

                            <h2 class="lp-typo lp-h1 lp-primary">
                            <?
                                $total_offers = wp_count_posts('offers');
                                $total = 0;
                                foreach ( $total_offers as $k => $v ) {
                                    if ( $k === 'draft' || $k === 'trash' || $k === 'pending' ) {
                                        continue;
                                    }
                                    $total += (int) $v;
                                }
                                echo $total;
                            ?>
                            </h2>
                        </div>
                    </div>

                    <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                        <div class="lp-flex lp-direction-column lp-align-center lp-justify-center">
                            <h3 class="lp-typo lp-h5">Общее количество предложений</h3>

                            <h2 class="lp-typo lp-h1 lp-primary">
                            <?
                                $total_offers = wp_count_posts('demands');
                                $total = 0;
                                foreach ( $total_offers as $k => $v ) {
                                    if ( $k === 'draft' || $k === 'trash' || $k === 'pending' ) {
                                        continue;
                                    }
                                    $total += (int) $v;
                                }
                                echo $total;
                            ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?
            $categories = get_terms( array(
                'taxonomy' => 'categories',
                'hide_empty' => false,
                'number' => 7
            ));
        ?>

        <div class="lp-grid lp-item lp-xs-12">
            <div class="lp-wrapper lp-cards">
                <div class="lp-grid lp-container lp-spacing-10">
                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-grid lp-container lp-spacing-3">
                            <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                                <div class="lp-card lp-filled">
                                    <div class="lp-card__header">
                                        <div class="lp-card__actions lp-flex lp-justify-between">
                                            <h3 class="lp-typo lp-h3">Спрос</h3>

                                            <button class="
                                  lp-button-base
                                  lp-button-icon
                                  lp-size-large
                                  lp-theme-secondary
                                  lp-variant-flat
                                  lp-rounded
                                ">
                                                <i class="lp-icon lp-info-circle-outlined"></i>
                                            </button>
                                        </div>

                                        <div class="lp-card__media">
                                            <img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/home-demand-media.png" alt="" />
                                        </div>
                                    </div>

                                    <div class="lp-card__body">
                                        <? 
                                        if ( ! empty( $categories ) ) :
                                            foreach( $categories as $cat ): 
                                        ?>
                                        <a href="<?= get_post_type_archive_link( 'demands' ) . '?category=' . $cat->slug ?>" class="lp-flex lp-align-center lp-justify-between">
                                            <span class="lp-typo lp-body"><?= $cat->name; ?></span>
                                            <i class="lp-icon lp-angle-right-flat"></i>
                                        </a>
                                        <? 
                                            endforeach; 
                                        else:
                                            printf('<div class="lp-flex lp-justify-center"><span>%s</span></div>', __('Никаких категорий пока не зарегистрировано', 'dao-consensus'));
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                                <div class="lp-card lp-filled">
                                    <div class="lp-card__header">
                                        <div class="lp-card__actions lp-flex lp-justify-between">
                                            <h3 class="lp-typo lp-h3">Предложение</h3>

                                            <button class="lp-button-base lp-button-icon lp-size-large lp-theme-secondary lp-variant-flat lp-rounded">
                                                <i class="lp-icon lp-info-circle-outlined"></i>
                                            </button>
                                        </div>

                                        <div class="lp-card__media">
                                            <img src="<?= get_stylesheet_directory_uri(); ?>/assets/images/home-offer-media.png" alt="" />
                                        </div>
                                    </div>

                                    <div class="lp-card__body">
                                    <? 
                                        if ( ! empty( $categories ) ) :
                                            foreach( $categories as $cat ): 
                                        ?>
                                        <a href="<?= get_post_type_archive_link( 'offers' ) . '?category=' . $cat->slug ?>" class="lp-flex lp-align-center lp-justify-between">
                                            <span class="lp-typo lp-body"><?= $cat->name; ?></span>
                                            <i class="lp-icon lp-angle-right-flat"></i>
                                        </a>
                                        <? 
                                            endforeach; 
                                        else:
                                            printf('<div class="lp-flex lp-justify-center"><span>%s</span></div>', __('Никаких категорий пока не зарегистрировано', 'dao-consensus'));
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-card lp-outlined">
                                <div class="lp-grid lp-container lp-spacing-3">
                                    <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                                        <div class="lp-card__header">
                                            <div class="lp-grid lp-container lp-spacing-3">
                                                <div class="lp-grid lp-item lp-xs-12">
                                                    <div class="lp-flex lp-align-center">
                                                        <h3 class="lp-typo lp-h3">Cделки</h3>

                                                        <button class="lp-button-base lp-button-icon lp-size-large lp-theme-secondary lp-variant-flat lp-rounded">
                                                            <i class="lp-icon lp-info-circle-outlined"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="lp-grid lp-item lp-lg-12 lp-md-12 lp-sm-6 lp-xs-12">
                                                    <h3 class="lp-typo lp-h1 lp-primary">
                                                    <?
                                                        function filter_transactions ( $status ) {
                                                            return ( in_array( $status, ['publish', 'in-process', 'completed'] ) );
                                                        }
                                                        $total_transactions = array_filter( (array) wp_count_posts('transactions'), 'filter_transactions', ARRAY_FILTER_USE_KEY );

                                                        echo array_sum( array_values( $total_transactions ) );
                                                    ?>
                                                    </h3>
                                                    <p class="lp-typo lp-body lp-grey">Общее количество сделок</p>
                                                </div>

                                                <div class="lp-grid lp-item lp-lg-12 lp-md-12 lp-sm-6 lp-xs-12">
                                                    <h3 class="lp-typo lp-h1 lp-primary">
                                                    <?
                                                    $today = new DateTime("now", new DateTimeZone('Europe/Moscow'));

                                                    $args = array(
                                                        'post_type' => 'transactions',
                                                        'fields' => 'ids',
                                                        'date_query' => array(
                                                            array(
                                                                'year'  => $today->format( "Y" ),
                                                                'month' => $today->format( "m" ),
                                                                'day'   => $today->format( "d" )
                                                            ),
                                                        ),
                                                    );
                                                    $today_transactions = new WP_Query( $args );

                                                    echo $today_transactions->found_posts;
                                                    ?>
                                                    </h3>
                                                    <p class="lp-typo lp-body lp-grey">Сделок за сегодня</p>
                                                </div>

                                                <div class="lp-grid lp-item lp-lg-12 lp-md-12 lp-sm-6 lp-xs-12">
                                                    <?
                                                        $total_offers = (array) wp_count_posts('transactions');
                                                    ?>
                                                    <h3 class="lp-typo lp-h1 lp-primary"><?php echo $total_offers['in-process']; ?></h3>
                                                    <p class="lp-typo lp-body lp-grey">Сделок в работе</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                                        <div class="lp-card__body">
                                            <?
                                            $per_page = 7;

                                            $args = array(
                                                'post_type' => 'transactions',
                                                'post_status' => array('completed'),
                                                'posts_per_page' => $per_page, 
                                                'meta_query' => array(
                                                    array(
                                                        'key'     => 'initiator_testimonial',
                                                        'value'   => '',
                                                        'compare' => '!=',
                                                    ),
                                                    array(
                                                        'key'     => 'deal_person_testimonial',
                                                        'value'   => '',
                                                        'compare' => '!=',
                                                    )
                                                )
                                            );

                                            $transactions = new WP_Query( $args );

                                            if ( $transactions->have_posts() ) :
                                                while ( $transactions->have_posts() ) : $transactions->the_post();
                                            ?>
                                                <a href="<?= get_post_type_archive_link( 'transactions' ) . '?id=' . get_the_ID() ?>" class="lp-flex lp-align-center lp-justify-between">
                                                    <span class="lp-typo lp-body"><?= get_the_title(); ?></span>
                                                    <i class="lp-icon lp-angle-right-flat"></i>
                                                </a>
                                            <?
                                                endwhile;
                                            
                                                if ( $transactions->found_posts > $per_page ) :
                                            ?>
                                            <a href="<?= get_post_type_archive_link( 'transactions' ) ?>" class="lp-flex lp-align-center lp-justify-between">
                                                <span class="lp-typo lp-body">ещё <?= $transactions->found_posts - $per_page; ?> новых</span>
                                                <i class="lp-icon lp-angle-right-flat"></i>
                                            </a>
                                            <?  endif;

                                            else:
                                                printf('<div class="lp-flex lp-justify-center"><p>%s</p></div>', __('Новых сделок ещё не совершено', 'dao-consensus'));
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
         $users = get_users(array(
            'role__in' => 'contributor',
            'orderby' => array('completed_transactions' => 'DESC'),
            'meta_query' => array(
                'completed_transactions' => array(
                    'key' => 'completed_transactions',
                    'compare' => 'EXISTS',
                    'type' => 'decimal',
                )
            ),
            'fields' => 'all',
            'number' => 5,
            'paged'  => 1
        ));

        if ( ! empty( $users ) ) :
        ?>
        <div class="lp-grid lp-item lp-xs-12">
            <div class="lp-wrapper">
                <div class="lp-grid lp-container lp-spacing-6">
                    <div class="lp-grid lp-item">
                        <h2 class="lp-typo lp-h1">Лидеры по заключенным сделкам</h2>
                    </div>

                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-slider">
                            <div class="lp-slider__wrapper lp-slider-home">
                                <div class="lp-grid lp-container lp-spacing-6">
                                    <div class="lp-grid lp-item lp-xs-12">
                                        <div class="swiper-container page-home-slider-company-develop">
                                            <div class="swiper-wrapper">
                                                <?
                                                    foreach ( $users as $user ) :
                                                        get_template_part( 'template-parts/swiper-slider-rating-leader', null, array('user' => $user) );
                                                    endforeach;
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="lp-grid lp-item lp-xs-12">
                                        <div class="lp-grid lp-container lp-spacing-3">
                                            <div class="lp-grid lp-item lp-lg-8 lp-xs-12 lp-flex lp-align-center">
                                                <div class="lp-slider__scrollbar"></div>
                                            </div>

                                            <div
                                                class="lp-grid lp-item lp-lg-4 lp-xs-12 lp-flex lp-align-center lp-justify-end">
                                                <span class="lp-slider__button lp-prev">
                                                    <button class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled" aria-label="icon button" title="icon-button">
                                                        <i class="lp-icon lp-angle-left-flat"></i>
                                                    </button>

                                                </span>
                                                <span class="lp-slider__button lp-next">
                                                    <button class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled" aria-label="icon button" title="icon-button">
                                                        <i class="lp-icon lp-angle-right-flat"></i>
                                                    </button>

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <? endif; ?>
    </div>
</div>

<? get_footer(); ?>