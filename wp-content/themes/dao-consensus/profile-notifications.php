<?php /* Template Name: Profile Notifications */ ?>

<? get_header(); ?>


<?
	$curr_user = wp_get_current_user();

	global $wpdb;
	$query  = "SELECT COUNT(*) FROM {$wpdb->prefix}user_notifications WHERE user_id=%d";

	$amount = $wpdb->get_var( $wpdb->prepare($query, $curr_user->ID) );
	$per_page = 5;
	$max_num_pages = ( $amount ) ? round( $amount / $per_page ) : 0;

	$notifications = dao_get_user_notifications( $curr_user->ID, 1 );
?>

<div class="lp-page lp-page-notifications">
	<div class="lp-wrapper">
		<div class="lp-grid lp-container lp-spacing-3">
			<div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-12">
				<? get_template_part('template-parts/profile', 'sidebar'); ?>
			</div>

			<div class="lp-grid lp-item lp-lg-9 lp-md-8 lp-sm-12">
				<div class="lp-paper lp-data-grid lp-elevation lp-dense-6">
					<div class="lp-grid lp-container lp-spacing-3">
						<? if ( ! empty( $notifications ) ) : ?>
						<div class="lp-grid lp-item lp-xs-12">
							<div class="lp-notifications">
								<? 	
								foreach ( $notifications as $notification ) : 
									get_template_part( 'template-parts/profile-notification', 'card', array( 'notification' => $notification ) );
								endforeach; 
								?>
							</div>
						</div>
						
						<? if ( $max_num_pages > 2 ) : ?>
						<!-- PAGINATION -->
						<div class="lp-grid lp-item lp-xs-12 lp-flex lp-justify-center">
							<nav id="pagination" class="tui-pagination lp-pagination" data-lp-pagination></nav>
						</div>
						<!-- END PAGINATION -->
						<? endif; ?>

						<? else: ?>
							<div class="lp-grid lp-item lp-xs-12 lp-flex lp-justify-center">
								<p><? _e('Для вас пока нету никаких уведомлений.', 'dao-consensus'); ?></p>
							</div>
						<? endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<? get_footer('', array( 'query_vars' => array( 'page' => 1, 'per_page' => 5 ), 'total_found' => $amount, 'per_page' => $per_page, 'max_num_pages' => $max_num_pages ) ); ?>
