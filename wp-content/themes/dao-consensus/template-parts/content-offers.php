<?php
/**
 * Template part for displaying page content in archive-offers.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package DAO_Consensus
 */

?>

<div id="<?php the_ID(); ?>" class="lp-card-wrapper lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-6 lp-xs-12">
    <article class="lp-card lp-card-offer">
        <header class="lp-card__header">
            <div class="lp-card-offer__actions lp-flex lp-align-start lp-justify-between">
                <div class="lp-flex lp-wrap">
                    <? $status = get_post_status(); ?>
                    <div class="lp-chip <? echo ( $status === 'active' ) ? 'lp-success lp-variant-filled' : 'lp-default lp-variant-filled'; ?>">
                        <span class="lp-chip__label"><? echo ( $status === 'active' ) ? 'Активно' : 'Неактивно' ; ?></span>
                    </div>
                </div>

                <? 
                if ( is_user_logged_in() ) : 
                    
                    $curr_user = wp_get_current_user();

                    if ( $curr_user->ID != $post->post_author ) :
                        $favourites = get_user_meta( $curr_user->ID, 'favourites', true );

                        $btn_class = ( is_array( $favourites ) && in_array($post->ID, $favourites) ) ? 'delete-from-favourites' : 'add-to-favourites';
                        $icon_class = ( is_array( $favourites ) && in_array($post->ID, $favourites) ) ? 'lp-heart-filled' : 'lp-heart-flat';
                        $title = ( is_array( $favourites ) && in_array($post->ID, $favourites) ) ? __('Удалить из Избранное', 'dao-consensus') : __('Добавить в Избранное', 'dao-consensus');
                ?>
                        <button class="<?= $btn_class ?> lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled" title="<?= $title; ?>">
                            <i class="lp-icon <?= $icon_class ?>"></i>
                        </button>
                <?
                    endif; 

                endif; 
                ?>
            </div>

            <div class="lp-card__media">
                <? if( has_post_thumbnail() ):
                    the_post_thumbnail('medium', ['class' => 'lp-card__img', 'title' => get_the_title(), 'alt' => 'card thumbnail']);
                else: ?>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/card-placeholder.jpg" alt="card thumbnail" class="lp-card__img" />
                <? endif; ?>
            </div>

            <? $user_type = get_the_author_meta('person_type', $post->post_author); ?>
            <div class="lp-chip lp-user-type-<?= $user_type; ?> lp-card-offer__chip">
                <div class="lp-chip__label"><?= DAO_CONSENSUS::person_types[$user_type] ?></div>
            </div>
        </header>

        <div class="lp-card__body">
            <div class="lp-card__content">
                <h5><a href="<? the_permalink(); ?>"><? the_title(); ?></a></h5>

                <p><?= wp_trim_words( get_the_content(), 20, null ); ?></p>
            </div>
            <hr class="lp-divider" />

            <div class="lp-card-offer__info">
                <ul class="lp-list lp-no-style">
                    <li>
                        <?php
                            $cryptocurrencies = DAO_CONSENSUS::cryptocurrencies;
                            $post_cryptocurrency = get_post_meta( get_the_ID(), 'cryptocurrency', true );
                        ?>
                        <span class="lp-typo lp-sub lp-grey">Формат расчёта: <? echo ( $post_cryptocurrency ) ? $cryptocurrencies[$post_cryptocurrency] : ''; ?></span>
                    </li>
                    <li>
                        <span class="lp-typo lp-sub lp-grey">Cрок выполнения: до <?= dao_deadline_format( (int) get_post_meta( get_the_ID(), 'deadline', true ), get_post_meta( get_the_ID(), 'deadline_period', true ) ); ?></span>
                    </li>
                </ul>
            </div>
            <hr class="lp-divider" />

            <div class="lp-card-offer__user lp-flex lp-justify-between">
                <span class="lp-typo lp-footnote lp-flex lp-align-center">
                    <i class="lp-icon lp-user-outlined"></i>
                    <span><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( get_the_author() ); ?>"><?php the_author(); ?></a></span>
                </span>

                <span class="lp-typo lp-sub"><b><?= esc_html( get_post_meta( get_the_ID(), 'total_price', true ) ); ?> <?= $cryptocurrencies[$post_cryptocurrency]; ?></b></span>
            </div>
            <hr class="lp-divider" />

            <div class="lp-card-offer__skills">
                <?
                    $offer_skills = (array) get_post_meta( get_the_ID(), 'skills', true );
                ?>
                <div class="lp-typo lp-sub lp-grey"><?= implode(', ', $offer_skills); ?></div>
                <a href="<? echo get_permalink( get_the_ID() ); ?>" class="lp-link lp-theme-primary"><small><? _e('Подробнее...', 'dao-consensus'); ?></small></a>
            </div>
        </div>
    </article>
</div>
