<? /* Template Name: Profile Submit Testimonial */ ?>

<? get_header(); ?>

<?
    $curr_user = wp_get_current_user();

    $transaction = get_post( $_GET['transaction_id'] );

    $initiator = $transaction->initiator;
    $card_author = get_post_field( 'post_author', $transaction->card_id, true );

    $res = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM {$wpdb->prefix}user_testimonials WHERE author_id = %d AND transaction_id = %d", $curr_user->ID, $_GET['transaction_id']) );
?>

<div class="lp-page lp-testimonails-page lp-submit-testimonial-page">
    <div class="lp-wrapper">
        <div class="lp-grid lp-container lp-spacing-3 lp-paper lp-elevation">
            <? if ( is_null( $res ) ) : ?>
            <div class="lp-grid lp-item lp-flex lp-align-center lp-justify-center lp-xs-12">
                <h4 class="lp-typo lp-h4">Оставить отзыв о сотрудничестве с <?= ( $curr_user->ID == $initiator ) ? dao_get_user_display_name( $card_author ) : dao_get_user_display_name( $initiator ); ?></h4>
            </div>

            <form class="submit-testimonial lp-grid lp-item lp-xs-12">
                <div class="lp-paper lp-dense-6">
                    <div class="lp-grid lp-container lp-spacing-3 lp-direction-column">
                        <div class="lp-rating__box lp-paper">
                            <div class="lp-rating lp-flex lp-justify-between">
                                <span class="lp-typo lp-body">Качество работы</span> <span class="rating-scale"></span>
                                <input class="grade" type="hidden" name="quality">
                            </div>
                            <div class="lp-rating lp-flex lp-justify-between">
                                <span class="lp-typo lp-body">Профессионализм</span> <span class="rating-scale"></span>
                                <input class="grade" type="hidden" name="professionality">
                            </div>
                            <div class="lp-rating lp-flex lp-justify-between">                       
                                <span class="lp-typo lp-body">Стоимость</span> <span class="rating-scale"></span>
                                <input class="grade" type="hidden" name="cost">
                            </div>
                            <div class="lp-rating lp-flex lp-justify-between">
                                <span class="lp-typo lp-body">Контактность</span> <span class="rating-scale"></span>
                                <input class="grade" type="hidden" name="sociability">
                            </div>
                            <div class="lp-rating lp-flex lp-justify-between">
                                <span class="lp-typo lp-body">Сроки</span> <span class="rating-scale"></span>
                                <input class="grade" type="hidden" name="deadline">
                            </div>
                        </div>
                        <div class="lp-textfield lp-area lp-variant-outlined" data-lp-textfield id="testimonial-body">
                            <div class="lp-textfield__input">
                                <label class="lp-textfield__label">
                                    <textarea data-lp-textfield="input" name="body"></textarea>
                                    <span>Отзыв</span>
                                </label>
                            </div>
                        </div>
                        <div class="lp-grid lp-item lp-flex lp-align-center lp-justify-center">
                            <button type="submit"
                                class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                Отправить
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
                </div>

                <input type="hidden" name="action" value="dao-submit-testimonial">
                <input type="hidden" name="transaction_id" value="<?= $_GET['transaction_id']; ?>">
                <input type="hidden" name="_wpnonce" value="<?= wp_create_nonce('dao-consensus-submit-testimonial'); ?>">
            </form>
            <? else : ?>
                <p class="already-submitted">Вы уже отправили отзыв о сотрудничестве с <?= ( $curr_user->ID == $initiator ) ? dao_get_user_display_name( $card_author ) : dao_get_user_display_name( $initiator ); ?></p>
            <? endif; ?>
        </div>
    </div>
</div>

<? get_footer(); ?>
