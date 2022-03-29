<?php
/**
 * The template for displaying all offers
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package DAO_Consensus
 */

get_header();
?>

    <div class="lp-page lp-page-good">
        <div class="lp-wrapper">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/single-content', get_post_type() );

		endwhile;
		?>

        </div>
    </div>


<?php
get_footer();