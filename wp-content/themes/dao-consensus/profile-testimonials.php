<? /* Template Name: Profile Testimonials */ ?>

<? get_header(); ?>

<?
    $curr_user = wp_get_current_user();

    $user_testimonials = dao_get_user_testimonials( $curr_user->ID, 9 );
    
    $vars = array(
        'found' => $user_testimonials['found'],
        'max_num_pages' => $user_testimonials['max_num_pages'],
        'page' => $user_testimonials['page'],
        'per_page' => $user_testimonials['per_page']
    );
?>

<div class="lp-page lp-testimonials-page">
    <div class="lp-wrapper">
        <div class="lp-grid lp-container lp-spacing-3">
            <div class="lp-grid lp-item lp-flex lp-align-center lp-justify-center lp-xs-12">
                <div style="position: absolute; left: 0">
                    <a href="<?= get_permalink( 21 ); ?>" class="lp-button-base lp-theme-primary lp-variant-flat lp-size-small lp-button">
                        <i class="lp-icon lp-angle-left-flat lp-prefix"></i>
                        <b>Назад</b>
                    </a>
                </div>

                <h4 class="lp-typo lp-h4">Ваши отзывы</h4>
            </div>

            <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-paper lp-elevation lp-dense-6">
                    <div class="lp-grid lp-container lp-spacing-3">
                        <div class="lp-grid lp-item lp-xs-12 lp-flex lp-align-center lp-justify-between">
                            <div class="lp-paper__title">
                                <span class="lp-typo lp-sub lp-uppercase">Отзывы</span>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-xs-12">
                            <div id="data-container" class="lp-testimonials lp-grid lp-container lp-spacing-2">
                                <? 
                                if ( ! empty( $user_testimonials['testimonials'] ) ) : 
                                    foreach ( $user_testimonials['testimonials'] as $testimonial ) :
                                        get_template_part('template-parts/testimonial-card', null, array('testimonial' => $testimonial));
                                    endforeach;
                                endif; 
                                ?>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-xs-12">
							<nav id="pagination" class="tui-pagination lp-pagination"></nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<? get_footer( '', array( 'vars' => $vars ) ); ?>
