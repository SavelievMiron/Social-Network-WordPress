<?
    $user = $args['user'];
?>
<div class="lp-slide swiper-slide">
    <div class="lp-card lp-card-slide">
        <div class="lp-card__body">
            <div class="lp-avatar">
                <img src="<?= get_avatar_url( $user->ID, array( 'size' => 100, 'default' => 'mystery' ) ); ?>" alt="profile avatar" />
            </div>

            <h4 class="lp-typo lp-h4">
                <a href="<?= get_author_posts_url( $user->ID ) ?>"><? printf('%s %s', $user->first_name, $user->last_name); ?></a>
            </h4>

            <p class="lp-typo lp-body">
                <?= wp_trim_words( get_user_meta( $user->ID, 'description', true ), 20, null ); ?>
            </p>

            <h5 class="lp-typo lp-h5"><?= get_user_meta($user->ID, 'specialization', true); ?></h5>

            <div class="lp-card__rate">
                <span class="lp-typo lp-sub">
                    <i class="lp-icon lp-star-outlined"></i>
                    <b><?= dao_get_user_rating( $user->ID ); ?></b>
                </span>

                <span class="lp-typo lp-footnote lp-grey"><?= dao_get_user_rating_label( $user->ID ); ?></span>
            </div>
        </div>
    </div>
</div>
