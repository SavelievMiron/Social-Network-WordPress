<?php
/**
 * Template part for displaying demand card in profile-inprocess.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package DAO_Consensus
 */
?>

<?
    $status = get_post_status();
    $card_author = get_post_field( 'post_author', get_the_ID() );
    $performer = $post->performer;
    $curr_user = wp_get_current_user();

?>
<div class="lp-grid lp-item lp-lg-4 lp-md-6 lp-sm-12">
    <article class="lp-card lp-card-offer <?= ($card_author == $curr_user->ID) ? 'lp-card-mine' : ''; ?> lp-status-<?= $status; ?>" data-lp-card>
        <header class="lp-card__header">
            <div class="lp-card-offer__actions lp-flex lp-align-start lp-justify-between">
                <div class="lp-flex lp-wrap">
                    <div class="lp-chip">
                        <span class="lp-chip__label"><? _e('В работе', 'dao-consensus') ?></span>
                    </div>
                </div>
                <? 
                if ( $card_author == get_current_user_id() ) : 
                ?>
                <button class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled"
                    data-lp-card="button">
                    <i class="lp-icon lp-ellipsis-hr-flat"></i>
                </button>

                <div class="lp-card__options lp-paper lp-elevation lp-dense-3 lp-popover" data-lp-card="popover"
                    aria-hidden="true">
                    <ul class="lp-list lp-no-style" data-lp-card="options">
                        <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                            <i class="lp-icon lp-prefix lp-check-flat"></i>
                            <span>Выполнено</span>
                        </li>
                        <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                            <i class="lp-icon lp-prefix lp-times-flat"></i>
                            <span>Не выполнено</span>
                        </li>
                    </ul>
                </div>
                <? endif; ?>
            </div>

            <div class="lp-card__media">
                <? if( has_post_thumbnail() ):
                    the_post_thumbnail('medium', ['class' => 'lp-card__img', 'title' => get_the_title(), 'alt' => 'profile inprocess card']);
                    else: ?>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/card-placeholder.jpg"
                    alt="profile inprocess card" class="lp-card__img" />
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
                        <span class="lp-typo lp-sub lp-grey">Формат расчёта:
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
                <span><a href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>"
                            title="<?php echo esc_attr( get_the_author() ); ?>"><?php the_author(); ?></a></span>
                </span>

                <span
                    class="lp-typo lp-sub"><b><?= esc_html( get_post_meta( get_the_ID(), 'total_price', true ) ); ?> <?= $cryptocurrencies[$post_cryptocurrency]; ?></b></span>
            </div>
            <hr class="lp-divider" />

            <? if ( $card_author == $curr_user->ID ) : ?>
            <div class="lp-card-offer__user lp-flex lp-justify-between">
                <?
                    $performer_name = dao_get_user_display_name( $performer );
                ?>
                <span>
                    Исполнитель
                </span>
                <span>
                    <a href="<?php echo esc_url( get_author_posts_url( $performer ) ); ?>" title="<?php echo $performer_name; ?>"><?php echo $performer_name; ?></a>
                </span>
            </div>
            <hr class="lp-divider" />
            <? endif; ?>

            <div class="lp-card-offer__skills">
                <?
                    $demand_skills = get_post_meta( get_the_ID(), 'skills', true );
                ?>
                <div class="lp-typo lp-sub lp-grey">
                    <?= ( ! empty($demand_skills) ) ? implode(', ', $demand_skills) : ''; ?>
                </div>
                <a href="<?= get_permalink(); ?>" class="lp-link lp-theme-primary"><small>
                        <? _e('Подробнее...', 'dao-consensus'); ?></small></a>
            </div>
        </div>

        <? if ($card_author == $curr_user->ID) : ?>
            <div class="lp-modal" data-lp-modal aria-label="completed card modal" data-lp-card="completed-modal"
                aria-hidden="true">
                <form class="lp-modal__wrapper">
                    <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                        <? 
                            $performer_name = dao_get_user_display_name( $performer );
                            $performer_link = get_author_posts_url( $performer );
                        ?>
                        <header class="lp-modal__header">
                            <h5 class="lp-typo lp-h5">Подтверждение выполнения</h5>
                        </header>

                        <div class="lp-modal__body">
                            <p class="lp-typo lp-body">Вы подтверждаете выполнение исполнителем <a href="<?= $performer_link ?>"><?= $performer_name; ?></a> спроса "<?= $post->post_title; ?>"?</p>
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

                    <input type="hidden" name="action" value="dao-complete-demand">
                    <input type="hidden" name="id" value="<?= $post->ID; ?>">
                    <input type="hidden" name="_wpnonce" value="<?= wp_create_nonce('dao-consensus-complete-demand'); ?>">
                </form>
            </div>
            <div class="lp-modal" data-lp-modal aria-label="not completed card modal" data-lp-card="not-completed-modal"
                aria-hidden="true">
                <form class="lp-modal__wrapper">
                    <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                        <? 
                            $performer_name = dao_get_user_display_name( $performer );
                            $performer_link = get_author_posts_url( $performer );
                        ?>
                        <header class="lp-modal__header">
                            <h5 class="lp-typo lp-h5">Подтверждение не выполнения</h5>
                        </header>

                        <div class="lp-modal__body">
                            <p class="lp-typo lp-body">Вы подтверждаете не выполнение исполнителем <a href="<?= $performer_link ?>"><?= $performer_name; ?></a> спроса "<?= $post->post_title; ?>"?</p>
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

                    <input type="hidden" name="action" value="dao-not-complete-demand">
                    <input type="hidden" name="id" value="<?= $post->ID; ?>">
                    <input type="hidden" name="_wpnonce" value="<?= wp_create_nonce('dao-consensus-not-complete-demand'); ?>">
                </form>
            </div>
        <? endif; ?>

    </article>
</div>
