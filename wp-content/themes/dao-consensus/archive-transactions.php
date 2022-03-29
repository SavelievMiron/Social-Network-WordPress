<?php /* Template Name: Transactions */ ?>

<? get_header(); ?>

<div class="lp-page lp-page-transactions">
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
                <div class="lp-grid lp-item lp-xs-12">
                    <div class="lp-grid lp-container lp-spacing-3">
                        <div class="lp-grid lp-item lp-lg-6 lp-md-7 lp-xs-12 lp-flex">
                            <div class="lp-grid lp-container lp-spacing-1 lp-align-center">
                                <div class="lp-item">
                                    <button type="button" id="publication_date"
                                        class="lp-button-base lp-button lp-size-small lp-theme-secondary lp-variant-flat">
                                        Дата <i class="lp-icon lp-postfix lp-triangle-filter"></i>
                                    </button>
                                </div>

                                <div class="lp-item">
                                    <button type="button" id="total_price"
                                        class="lp-button-base lp-button lp-size-small lp-theme-secondary lp-variant-flat">
                                        Стоимость <i class="lp-icon lp-postfix lp-triangle-filter"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="lp-grid lp-item lp-lg-6 lp-md-7 lp-xs-12 lp-flex">
                            <div class="lp-grid lp-container lp-spacing-1 lp-justify-end">
                                <div class="lp-grid lp-item lp-lg-4 lp-md-3 lp-xs-3">
                                    <div class="lp-select" data-lp-select id="cryptocurrencies">
                                        <div class="lp-select__textfield lp-textfield lp-variant-outlined"
                                            data-lp-select="textfield">
                                            <div class="lp-textfield__input">
                                                <label class="lp-textfield__label">
                                                    <span data-lp-select="value"></span>
                                                    <input type="hidden" name="cryptocurrency" data-lp-textfield="input"
                                                        data-lp-select="input" />

                                                    <span>Формат расчёта</span>
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
                                                <? foreach ( DAO_CONSENSUS::cryptocurrencies as $k => $v ) : ?>
                                                    <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                                                        data-lp-value="<?= $k; ?>">
                                                        <span><?= $v; ?></span>
                                                    </li>
                                                <? endforeach; ?>
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

            <div class="lp-grid lp-item lp-xs-12">
                <div id="data-container" class="lp-grid lp-container lp-spacing-4">

                    <?php 
                        if ( have_posts() ) :
                            
                            $trans_id = ( isset( $_GET['id'] ) ) ? $_GET['id'] : false;

                            if ( ! $trans_id ) {
                                while ( have_posts() ) :
                                    the_post();

                                    get_template_part( 'template-parts/content', get_post_type() );

                                endwhile;
                            } else {
                                $transaction = new WP_Query( array(
                                    'post_type' => 'transactions',
                                    'p' => $trans_id
                                ) );
                                if ( $transaction->have_posts() ) :
                                    while ( $transaction->have_posts() ) :
                                        $transaction->the_post();

                                        get_template_part( 'template-parts/content', get_post_type() );    
                                    endwhile;
                                endif;
                            }

                        else :
                    ?>
                        <div class="no-results not-found">
                            <p><? _e('Никаких сделок пока ещё не совершено.', 'dao-consensus') ?></p>
                        </div>
                    <?
                        endif;
                    ?>
                </div>
            </div>

            <!-- PAGINATION -->
            <div class="lp-grid lp-item lp-xs-12">
                <nav <?= ( have_posts() && ! empty( $trans_id ) ) ? 'style="display: none;"' : ''; ?> id="pagination" class="tui-pagination lp-pagination" data-lp-pagination></nav>
            </div>
            <!-- END PAGINATION -->
        </div>
    </div>
</div>

<? get_footer(); ?>
