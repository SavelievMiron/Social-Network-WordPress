<? /* Template for displaying user testimonial */ ?>

<?
    $testimonial = $args['testimonial'];
    $card = get_post( get_post_meta( $testimonial->transaction_id, 'card_id', true ) );
    $author_name = dao_get_user_display_name( $testimonial->author_id );
    $author_link = get_author_posts_url( $testimonial->author_id );
?>

<article class="lp-card lp-card-testimonial lp-grid <?= ( is_page('testimonials') ) ? 'lp-xs-4' : 'lp-xs-12'; ?>">
    <div class="lp-card__body">
        <div class="lp-card__content">
            <span class="lp-typo lp-footnote lp-grey"><?= date( 'd.m.Y', strtotime($testimonial->created_at) ); ?></span>
            <h5><a href="<?= get_permalink( $card->ID ); ?>"><?= $card->post_title; ?></a></h5>

            <p>
                <?= esc_html( $testimonial->message ); ?>
            </p>

            <div class="lp-flex lp-align-center">
                <div class="lp-avatar">
                    <img src="<?= get_avatar_url( $testimonial->author_id, array( 'size' => 100, 'default' => 'mystery', ) ); ?>" alt="testimonial author avatar" />
                </div>

                <h6 class="lp-typo lp-sub lp-uppercase lp-grey"><a href="<?= $author_link; ?>"><?= $author_name; ?></a></h6>
            </div>

            <div class="lp-card__rate">
                <div class="lp-flex lp-justify-between">
                    <span class="lp-typo lp-footnote">Качество работы</span>

                    <div class="lp-testimonial-score lp-typo lp-footnote">
                        <span>
                        <? for ($i = 0; $i < $testimonial->quality; $i++) : ?>
                            <i class="lp-icon lp-star-filled"></i>
                        <? endfor; ?>
                        </span>
                        <span>
                            <?= $testimonial->quality; ?>
                        </span>
                    </div>
                </div>

                <div class="lp-flex lp-justify-between">
                    <span class="lp-typo lp-footnote">Профессионализм</span>

                    <div class="lp-testimonial-score lp-typo lp-footnote">
                        <span>
                        <? for ($i = 0; $i < $testimonial->professionality; $i++) : ?>
                            <i class="lp-icon lp-star-filled"></i>
                        <? endfor; ?>
                        </span>
                        <span>
                            <?= $testimonial->professionality; ?>
                        </span>
                    </div>
                </div>

                <div class="lp-flex lp-justify-between">
                    <span class="lp-typo lp-footnote">Стоимость</span>

                    <div class="lp-testimonial-score lp-typo lp-footnote">
                        <span>
                        <? for ($i = 0; $i < $testimonial->cost; $i++) : ?>
                            <i class="lp-icon lp-star-filled"></i>
                        <? endfor; ?>
                        </span>
                        <span>
                        <?= $testimonial->cost; ?>
                        </span>
                    </div>
                </div>

                <div class="lp-flex lp-justify-between">
                    <span class="lp-typo lp-footnote">Контактность</span>

                    <div class="lp-testimonial-score lp-typo lp-footnote">
                        <span>
                        <? for ($i = 0; $i < $testimonial->sociability; $i++) : ?>
                            <i class="lp-icon lp-star-filled"></i>
                        <? endfor; ?>
                        </span>
                        <span>
                        <?= $testimonial->sociability; ?>
                        </span>
                    </div>
                </div>

                <div class="lp-flex lp-justify-between">
                    <span class="lp-typo lp-footnote">Сроки</span>

                    <div class="lp-testimonial-score lp-typo lp-footnote">
                        <span>
                        <? for ($i = 0; $i < $testimonial->deadline; $i++) : ?>
                            <i class="lp-icon lp-star-filled"></i>
                        <? endfor; ?>
                        </span>
                        <span>
                        <?= $testimonial->deadline; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
