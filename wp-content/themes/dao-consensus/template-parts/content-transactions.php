<?php
/**
 * Template part for displaying page content in archive-transactions.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package DAO_Consensus
 */

$transaction_id = get_the_ID();
$card = get_post( get_post_meta( $transaction_id, 'card_id', true ) );

$initiator = get_post_meta( $transaction_id, 'initiator', true);
$card_author = $card->post_author;

$initiator_testimonial = dao_get_user_testimonial( $transaction_id, $initiator );
$author_testimonial    = dao_get_user_testimonial( $transaction_id, $card_author );

?>

<div id="<? the_ID(); ?>" class="lp-grid lp-item lp-xs-12">
    <div class="lp-paper lp-transaction lp-elevation lp-dense-10">
        <div class="lp-grid lp-container lp-spacing-3 lp-flex lp-align-center lp-justify-between">
            <div class="lp-grid lp-item lp-lg-4 lp-md-4 lp-xs-12">
                <div class="lp-card">
                    <div class="lp-card__body">
                        <div class="lp-avatar">
                            <img src="<?= get_avatar_url( $initiator, array('size' => 60, 'default' => 'mystery') ); ?>" alt="profile avatar" />
                        </div>

                        <div class="lp-flex lp-align-center lp-justify-between">
                            <h3 class="lp-typo lp-h3"><a href="<?= get_author_posts_url( $initiator ); ?>"><?= dao_get_user_display_name( $initiator ); ?></a></h3>
                            <span class="lp-typo lp-footnote lp-grey">Рейтинг: <?= $initiator_testimonial->rating; ?></span>
                        </div>

                        <p class="lp-typo lp-body">
                            <?= $initiator_testimonial->message; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="lp-grid lp-item lp-lg-4 lp-md-4 lp-xs-12">
                <div class="lp-card lp-transparent">
                    <div class="lp-card__body">
                        <div class="lp-flex lp-direction-column lp-align-center">
                            <a class="lp-typo lp-h4" href="<?= get_permalink( $card->ID ); ?>"><?= $card->post_title; ?></a>

                            <span class="lp-typo lp-footnote lp-grey"><?= eng_months_to_ru( get_the_date( 'j F Y' ) ); ?></span>

                            <span class="lp-card__icon">
                                <i class="lp-icon lp-arrow-right-flat"></i>
                            </span>

                            <h3 class="lp-typo lp-h3"><?= get_post_meta( $transaction_id, 'total_price', true ) ?> <?= DAO_CONSENSUS::cryptocurrencies[ get_post_meta( $transaction_id, 'cryptocurrency', true ) ]; ?></h3>

                            <span class="lp-typo lp-footnote lp-grey">Стоимость сделки</span>

                            <a href="<?= get_permalink( $card->ID ); ?>" class="lp-share-card lp-link lp-theme-primary">
                                <i class="lp-icon lp-prefix lp-user-outlined"></i>
                                Поделиться
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lp-grid lp-item lp-lg-4 lp-md-4 lp-xs-12">
                <div class="lp-card">
                    <div class="lp-card__body">
                        <div class="lp-avatar">
                            <img src="<?= get_avatar_url( $card_author, array('size' => 60, 'default' => 'mystery') ); ?>" alt="profile avatar" />
                        </div>

                        <div class="lp-flex lp-align-center lp-justify-between">
                            <h3 class="lp-typo lp-h3"><a href="<?= get_author_posts_url( $card_author ); ?>"><?= dao_get_user_display_name( $card_author ); ?></a></h3>

                            <span class="lp-typo lp-footnote lp-grey">Рейтинг: <?= $author_testimonial->rating; ?></span>
                        </div>

                        <p class="lp-typo lp-body">
                            <?= $author_testimonial->message; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
