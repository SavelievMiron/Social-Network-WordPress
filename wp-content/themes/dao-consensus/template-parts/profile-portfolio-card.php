<div id="<?php the_ID(); ?>" class="lp-portfolio__box lp-grid lp-item lp-lg-3">
	<div class="lp-card lp-portfolio__item">
		<header class="lp-card__header">
			<div class="lp-card__media">
			<? if( has_post_thumbnail() ):
					the_post_thumbnail('medium', ['class' => 'lp-card__img', 'title' => get_the_title(), 'alt' => 'portfolio card cover']);
				else: ?>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/card-placeholder.jpg" alt="portfolio card cover" class="lp-card__img" />
			<?  endif; ?>
			</div>
			<button class="lp-card__edit lp-button-base lp-button-icon lp-size-medium"><i class="lp-icon lp-edit-flat"></i></button>
		</header>

		<div class="lp-card__body">
			<div class="lp-card__content">
				<h4 class="lp-typo lp-footnote"><? the_title(); ?></h4>

				<span class="lp-typo lp-sub">
					<small>
						<?php echo wp_trim_words( get_the_content(), 15, null ); ?>
					</small>
				</span>
			</div>
		</div>
	</div>
</div>
