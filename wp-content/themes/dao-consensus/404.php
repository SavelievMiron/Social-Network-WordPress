<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage DAO Consensus
 */

get_header(); 
?>

<div class="lp-page lp-page-404">
    <div class="lp-wrapper">
        <div class="lp-grid lp-container lp-spacing-6">
            <div class="lp-grid lp-item lp-xs-12 lp-flex lp-direction-column lp-align-center">
                <h1 class="lp-typo lp-h1">404</h1>
                <h2 class="lp-typo lp-h3"><? _e( 'Ничего не найдено', 'dao-consensus' ); ?></h2>
                <div class="lp-grid lp-item lp-xs-12">
                    <div class="lp-grid lp-item lp-lg-4">
                        <a href="<?php echo home_url(); ?>" class="lp-button-base lp-button lp-size-large lp-theme-primary lp-variant-outlined">
                            Вернуться на главную
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
get_footer(); 
?>
