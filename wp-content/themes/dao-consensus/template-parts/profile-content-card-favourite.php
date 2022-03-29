<?php
/**
 * Template part for displaying cards in profile-favourites.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package DAO_Consensus
 */
?>

<div id="<? the_ID(); ?>" class="lp-card-wrapper lp-grid lp-item lp-lg-4 lp-md-6 lp-sm-12">
    <? $status = get_post_status(); ?>
    <article class="lp-card lp-card-offer lp-status-<?= $status ?>" data-lp-card>
        <header class="lp-card__header">
            <div class="lp-card-offer__actions lp-flex lp-align-start lp-justify-between">
                <div class="lp-flex lp-wrap">
                    <div class="post-status lp-chip lp-variant-filled">
                        <span class="lp-chip__label"><?= DAO_CONSENSUS::card_statuses[$status]; ?></span>
                    </div>
                </div>

                <button class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled"
                    data-lp-card="button">
                    <i class="lp-icon lp-ellipsis-hr-flat"></i>
                </button>

                <div class="lp-card__options lp-paper lp-elevation lp-dense-3 lp-popover" data-lp-card="popover"
                    aria-hidden="true">
                    <ul class="lp-list lp-no-style" data-lp-card="options">
                        <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                            <i class="lp-icon lp-prefix lp-edit-flat"></i>
                            <span>Удалить из Избранное</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="lp-card__media">
                <? if( has_post_thumbnail() ):
                    the_post_thumbnail('medium', ['class' => 'lp-card__img', 'title' => get_the_title(), 'alt' => 'profile demand card']);
                    else: ?>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/card-placeholder.jpg"
                    alt="profile demand card" height="210" class="lp-card__img" />
                <? endif; ?>
            </div>

            <? $user_type = get_the_author_meta('person_type', $post->post_author); ?>
            <div class="lp-chip lp-user-type-<?= $user_type; ?> lp-card-offer__chip">
                <div class="lp-chip__label"><?= DAO_CONSENSUS::person_types[$user_type] ?></div>
            </div>
        </header>

        <div class="lp-card__body">
            <div class="lp-card__content">
                <h5><a href="<? the_permalink(); ?>">
                        <? the_title(); ?></a></h5>

                <p><?= wp_trim_words( get_the_content(), 20, null ); ?></p>
            </div>
            <hr class="lp-divider" />

            <div class="lp-card-offer__info">
                <ul class="lp-list lp-no-style">
                    <?php
                            $cryptocurrencies = DAO_CONSENSUS::cryptocurrencies;
                            $post_cryptocurrency = get_post_meta( get_the_ID(), 'cryptocurrency', true );
                    ?>
                    <li>
                        <span class="lp-typo lp-sub lp-grey">Формат
                            расчёта:
                            <? echo ( $post_cryptocurrency ) ? $cryptocurrencies[$post_cryptocurrency] : ''; ?></span>
                    </li>
                    <li>
                        <span class="lp-typo lp-sub lp-grey">Cрок выполнения: до
                            <?= dao_deadline_format( (int) get_post_meta( get_the_ID(), 'deadline', true ), get_post_meta( get_the_ID(), 'deadline_period', true ) ); ?></span>
                    </li>
                </ul>
            </div>
            <hr class="lp-divider" />

            <div class="lp-card-offer__user lp-flex lp-justify-between">
                <span class="lp-typo lp-footnote lp-flex lp-align-center">
                    <i class="lp-icon lp-user-outlined"></i>
                    <span><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                            title="<?php echo esc_attr( get_the_author() ); ?>"><?php the_author(); ?></a></span>
                </span>

                <span class="lp-typo lp-sub"><b><?= esc_html( get_post_meta( get_the_ID(), 'total_price', true ) ); ?> <?= $cryptocurrencies[$post_cryptocurrency]; ?></b></span>
            </div>
            <hr class="lp-divider" />

            <div class="lp-card-offer__skills">
                <?
                    $offer_skills = get_post_meta( get_the_ID(), 'skills', true );
                ?>
                <div class="lp-typo lp-sub lp-grey">
                    <?= ( ! empty( $offer_skills ) ) ? implode(', ', $offer_skills): ''; ?>
                </div>
                <a href="<?= get_permalink(); ?>" class="lp-link lp-theme-primary"><small>
                        <? _e('Подробнее...', 'dao-consensus'); ?></small></a>
            </div>
        </div>

        <div class="lp-modal" data-lp-modal aria-label="delete from favourites modal" data-lp-card="delete-from-favourites-modal"
            aria-hidden="true">
            <form class="lp-modal__wrapper">
                <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                    <header class="lp-modal__header">
                        <h5 class="lp-typo lp-h5">Удалить из Избранное</h5>
                    </header>

                    <div class="lp-modal__body">
                        <p class="lp-typo lp-body">Вы действительно хотите удалить "<?= $post->post_title; ?>" из Избранное?</p>
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
                        <button type="button"
                            class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                            data-lp-close data-lp-modal="close">
                            <i class="lp-icon lp-times-flat"></i>
                        </button>
                    </div>
                </div>

                <input type="hidden" name="action" value="dao-delete-from-favourites">
                <input type="hidden" name="id" value="<?= $post->ID; ?>">
                <input type="hidden" name="_wpnonce" value="<?= wp_create_nonce('dao-consensus-delete-from-favourites'); ?>">
            </form>
        </div>
    </article>
</div>