<?php
/**
 * Template part for displaying page content in single-offers.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package DAO_Consensus
 */

?>

<div class="lp-grid lp-container lp-spacing-3">
    <div class="lp-grid lp-item lp-lg-8 lp-md-7 lp-xs-12">
        <div class="lp-grid lp-container lp-spacing-3">
            <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-paper lp-slider lp-elevation lp-dense-6">
                    <div class="lp-grid lp-container lp-spacing-6">
                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-slider__wrapper lp-galery">
                                <div class="swiper-container page-good-slider-galery">
                                    <div class="swiper-wrapper">
                                    <?
                                        $media_files = get_post_meta( get_the_ID(), 'media_files', true );

                                        $videos = array();
                                        $video_formats = array('.mp4');

                                        foreach( $media_files as $id => $path ) {

                                            foreach( $video_formats as $format ) {
                                                if( stripos( $path, $format ) ) {
                                                    $videos[$id] = $path;
                                                    unset($media_files[$id]);
                                                }
                                            }

                                        }

                                        if( ! empty( $videos ) ) :
                                            foreach( $videos as $id => $path ):?>
                                    <div class="lp-slide swiper-slide">
                                        <? $url = wp_get_attachment_url( $id ); ?>
                                        <?= do_shortcode("[evp_embed_video url='{$url}']") ?>
                                    </div>
                                    <?      endforeach;
                                        endif;

                                        foreach( $media_files as $id => $path ): ?>
                                    <div class="lp-slide swiper-slide">
                                        <img data-src="<?= wp_get_attachment_image_url($id, 'medium-large'); ?>"
                                            class="swiper-lazy" alt="swiper slide" />
                                    </div>
                                    <?  endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-grid lp-container lp-flex lp-justify-center">
                                <div class="lp-grid lp-item lp-xs-8">
                                    <div class="lp-slider__wrapper lp-thumbs">
                                        <div class="swiper-container page-good-slider-thumbs">
                                            <div class="swiper-wrapper">
                                                <?
                                                if( ! empty( $videos ) ) :
                                                        foreach( $videos as $id => $url ):?>
                                                            <div class="lp-slide swiper-slide">
                                                                <img data-src="<? echo get_stylesheet_directory_uri(); ?>/assets/images/LqKhnDzSa.png"
                                                                    class="swiper-lazy" />
                                                            </div>
                                                <?      endforeach;
                                                endif;

                                                foreach( $media_files as $id => $url ): ?>
                                                    <div class="lp-slide swiper-slide">
                                                        <img data-src="<?= wp_get_attachment_image_url($id, 'medium-large'); ?>"
                                                            class="swiper-lazy" />
                                                    </div>
                                                <?
                                                endforeach;
                                                ?>
                                            </div>
                                        </div>

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

            <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-paper lp-elevation lp-dense-6">
                    <div class="lp-grid lp-container lp-spacing-3">
                        <div class="lp-grid lp-item">
                            <div class="lp-paper__title">
                                <span class="lp-typo lp-sub lp-uppercase">Описание</span>
                            </div>
                        </div>

                        <div class="description lp-grid lp-item">
                            <p class="lp-typo lp-sub">
                                <? the_content(); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lp-grid lp-item lp-lg-4 lp-md-5 lp-xs-12">
        <div class="lp-grid lp-container lp-spacing-3">
            <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-paper lp-offer lp-elevation lp-dense-6">
                    <h4 class="lp-typo lp-h4"><? the_title(); ?></h4>

                    <hr class="lp-divider" />
                    <div class="lp-offer__info">
                        <ul class="lp-list lp-no-style">
                            <?php
                                $cryptocurrencies = DAO_CONSENSUS::cryptocurrencies;
                                $post_cryptocurrency = get_post_meta( get_the_ID(), 'cryptocurrency', true );
                            ?>
                            <li><span class="lp-typo lp-sub lp-grey">Формат расчёта: <? echo ( $post_cryptocurrency ) ? $cryptocurrencies[$post_cryptocurrency] : ''; ?></span></li>
                            <li><span class="lp-typo lp-sub lp-grey">Cрок выполнения: до <?= dao_deadline_format( (int) get_post_meta( get_the_ID(), 'deadline', true ), get_post_meta( get_the_ID(), 'deadline_period', true ) ); ?></span></li>
                        </ul>
                    </div>
                    <hr class="lp-divider" />

                    <div class="lp-flex lp-align-center lp-justify-between">
                        <h2 class="lp-typo lp-h2"><?= esc_html( get_post_meta( get_the_ID(), 'total_price', true ) ); ?></h2>

                        <?
                            if ( is_user_logged_in() ):
                                if ( (int) get_current_user_id() !== (int) $post->post_author ) :
                        ?>
                        <a href="<?php echo get_permalink( 190 ); ?>?user_id=<?= $post->post_author; ?>&card_id=<?= $post->ID; ?>" class="lp-button-base lp-button lp-size-medium lp-theme-primary lp-variant-filled">
                            Договориться
                        </a>
                        <?      endif;
                            endif;
                        ?>
                    </div>
                </div>
            </div>

            <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-paper lp-common-info lp-elevation lp-dense-6">
                    <div class="lp-common-info__avatar lp-flex lp-align-center">
                        <div class="lp-avatar">
                            <img src="<?= get_avatar_url( $post->post_author, array('size' => 100, 'default' => 'mystery') ); ?>" alt="author avatar" />
                        </div>

                        <h5 class="lp-typo lp-h5"><a href="<?= get_author_posts_url( $post->post_author ); ?>"><? the_author(); ?></a></h5>
                    </div>
                    <hr class="lp-divider" />

                    <div class="lp-common-info__info">
                        <ul class="lp-list lp-no-style">
                            <li>
                                <? $user_type = get_the_author_meta( 'person_type', $post->post_author ); ?>
                                <span class="lp-typo lp-footnote lp-grey">
                                    <i class="lp-icon lp-user-outlined"></i>
                                    <?= DAO_CONSENSUS::person_types[$user_type]; ?>
                                </span>
                            </li>

                            <li>
                                <span class="lp-typo lp-footnote lp-grey">
                                    <i class="lp-icon lp-users-outlined"></i>
                                    <?
                                        $user_activity = dao_is_user_online( $post->post_author );
                                        if ( $user_activity === true ) {
                                            echo 'Онлайн';
                                        } else {
                                            $curr_time = new DateTime( current_time( 'mysql' ) );

                                            $last_act_time = new DateTime();
                                            $last_act_time->setTimestamp($user_activity);

                                            $time = 0;
                                            $diff = $curr_time->diff( $last_act_time );
                                            if ( $diff->h < 1 ) {
                                                $time = $diff->format("%i минут");
                                            } else {
                                                $time = $diff->format("%H часов %i минут");
                                            }
                                            echo "Офлайн {$time}";
                                        }
                                    ?>
                                </span>
                            </li>

                            <li>
                                <span class="lp-typo lp-footnote lp-grey">
                                    <i class="lp-icon lp-clock-outlined"></i>
                                    <?= "На сайте c " . eng_months_to_ru( date( 'j F Y', strtotime( get_the_author_meta( 'user_registered', $post->post_author ) ) ) ); ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <hr class="lp-divider" />

                    <div class="lp-common-info__stats">
                        <ul class="lp-list lp-no-style">
                            <li>
                                <span class="lp-typo lp-sub"><b><?= dao_count_user_completed_transactions( $post->post_author ); ?></b></span>
                                <span class="lp-typo lp-footnote lp-grey"> сделок совершено </span>
                            </li>

                            <li>
                                <span class="lp-typo lp-sub"><b><?= dao_count_user_testimonials( $post->post_author ); ?></b></span>
                                <span class="lp-typo lp-footnote lp-grey"> отзывов получено </span>
                            </li>

                            <!-- <li>
                                <span class="lp-typo lp-sub"><b>23%</b></span>
                                <span class="lp-typo lp-footnote lp-grey"> повторных сделок </span>
                            </li> -->
                        </ul>
                    </div>
                    <hr class="lp-divider" />

                    <div class="lp-common-info__rate">
                        <span class="lp-typo lp-sub">
                            <i class="lp-icon lp-star-outlined"></i>
                            <b><?= dao_get_user_rating( $post->post_author ); ?></b>
                        </span>

                        <span class="lp-typo lp-footnote lp-grey"><?= dao_get_user_rating_label( $post->post_author ); ?></span>
                    </div>
                </div>
            </div>

            <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-paper lp-skills lp-elevation lp-dense-6">
                    <div class="lp-grid lp-container lp-spacing-3">
                        <div class="lp-grid lp-item">
                            <div class="lp-paper__title">
                                <span class="lp-typo lp-footnote lp-uppercase">Сведения о навыках</span>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-skills__chips lp-flex lp-wrap">
                                <?
                                $post_skills = get_post_meta( get_the_ID(), 'skills', true );

                                if( ! empty( $post_skills ) ):
                                    foreach( $post_skills as $skill ): ?>
                                        <div class="lp-chip lp-default lp-variant-outlined">
                                            <span class="lp-chip__label"><?= $skill ?></span>
                                        </div>
                                <?
                                    endforeach;
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
