<div class="lp-page lp-page-feedback">
    <div class="lp-wrapper">
        <div class="lp-grid lp-container lp-spacing-3">
            <div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-12">
                <? get_template_part( 'template-parts/profile', 'sidebar' ); ?>
            </div>

            <div class="lp-grid lp-item lp-lg-9 lp-md-8 lp-sm-12">
                <div class="lp-grid lp-container lp-spacing-3">
                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-paper lp-data-grid lp-elevation lp-dense-6">
                            <div class="lp-grid lp-container lp-spacing-3">
                                <div class="lp-grid lp-item lp-xs-12 lp-container lp-flex lp-justify-between lp-align-center">
                                    <div class="lp-grid lp-item lp-xs-4">
                                        <div class="lp-tabs" data-lp-tabs="tabs">
                                            <button class="lp-button-base lp-button-tab lp-selected" data-lp-selected="true">Мои заявки</button>
                                        </div>
                                    </div>
                                    
                                    <div class="lp-grid lp-item lp-xs-4">
                                        <a href="<?= get_permalink( 319 ); ?>" target="_blank"
                                            class="lp-button-base lp-button lp-size-large lp-theme-primary lp-variant-outlined lp-full">
                                            Создать тикет
                                        </a>
                                    </div>
                                </div>

                                <div class="lp-grid lp-item lp-xs-12">
                                    <div class="lp-data-grid__table lp-table">
									<?php
										/* Get the tickets object */
										global $wpas_tickets;

										if ( $wpas_tickets->have_posts() ):

											/* Get list of columns to display */
											$columns 		  = wpas_get_tickets_list_columns();

											/* Get number of tickets per page */
											$tickets_per_page = wpas_get_option( 'tickets_per_page_front_end' );
											if ( empty($tickets_per_page) ) {
												$tickets_per_page = 5 ; // default number of tickets per page to 5 if no value specified.
											}

											?>
											<style type="text/css">
											.wrap .content-area main article .entry-content
											{
												min-width: 100%;
											}
											</style>
											<div class="wpas wpas-ticket-list alignwide">

												<?php //wpas_get_template( 'partials/ticket-navigation' ); ?>

												<!-- Filters & Search tickets -->
												<div class="wpas-row" id="wpas_ticketlist_filters">
													<div class="wpas-one-third">
														<select class="wpas-form-control wpas-filter-status">
															<option value=""><?php esc_html_e('Any status', 'awesome-support'); ?></option>
														</select>
													</div>
													<div class="wpas-one-third"></div>
													<div class="wpas-one-third" id="wpas_filter_wrap">
														<input class="wpas-form-control" id="wpas_filter" type="text" placeholder="<?php esc_html_e('Search tickets...', 'awesome-support'); ?>">
														<span class="wpas-clear-filter" title="<?php esc_html_e('Clear Filter', 'awesome-support'); ?>"></span>
													</div>
												</div>

												<!-- List of tickets -->
												<table id="wpas_ticketlist" class="wpas-table wpas-table-hover" data-filter="#wpas_filter" data-filter-text-only="true" data-page-navigation=".wpas_table_pagination" data-page-size=" <?php echo $tickets_per_page ?> ">
													<thead>
														<tr>
															<?php foreach ( $columns as $column_id => $column ) {

																$data_attributes = '';

																// Add the data attributes if any
																if ( isset( $column['column_attributes']['head'] ) && is_array( $column['column_attributes']['head'] ) ) {
																	$data_attributes = wpas_array_to_data_attributes( $column['column_attributes']['head'] );
																}

																printf( '<th id="wpas-ticket-%1$s" %3$s>%2$s</th>', $column_id, $column['title'], $data_attributes );

															} ?>
														</tr>
													</thead>
													<tbody>
														<?php
														while( $wpas_tickets->have_posts() ):

															$wpas_tickets->the_post();

															echo '<tr class="wpas-status-' . wpas_get_ticket_status( $wpas_tickets->post->ID ) . '" id="wpas_ticket_' . $wpas_tickets->post->ID . '">';

															foreach ( $columns as $column_id => $column ) {

																$data_attributes = '';

																// Add the data attributes if any
																if ( isset( $column['column_attributes']['body'] ) && is_array( $column['column_attributes']['body'] ) ) {
																	$data_attributes = wpas_array_to_data_attributes( $column['column_attributes']['body'], true );
																}

																printf( '<td %s>', $data_attributes );

																/* Display the content for this column */
																wpas_get_tickets_list_column_content( $column_id, $column );

																echo '</td>';

															}

															echo '</tr>';

														endwhile;

														wp_reset_query(); ?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="<?php echo count($columns); ?>">
																<ul class="wpas_table_pagination"></ul>
															</td>
														</tr>
													</tfoot>
												</table>
											</div>
										<?php
										else:

											echo wpas_get_notification_markup( 'info', sprintf( __( 'Вы ещё не создали ни одного тикета.', 'dao-consensus' )) );

										endif;
										?>
                                    </div>
                                    <!--
                                    <div class="lp-grid lp-item lp-xs-12">
                                        <nav class="lp-pagination" data-lp-pagination>
                                            <ul class="lp-list lp-no-style lp-flex lp-align-center">
                                                <li class="lp-flex lp-align-center">
                                                    <a href="#"
                                                        class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled">
                                                        <i class="lp-icon lp-angle-left-flat"></i>
                                                    </a>

                                                    <span class="lp-typo lp-sub lp-grey">Пред</span>
                                                </li>

                                                <li class="lp-flex lp-align-center lp-justify-center">
                                                    <ul class="lp-list lp-no-style lp-flex lp-align-center">
                                                        <li>
                                                            <a href="#"
                                                                class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">1</a>
                                                        </li>

                                                        <li>
                                                            <a href="#"
                                                                class="lp-button-base lp-button-nav lp-size-medium lp-theme-primary lp-variant-filled">2</a>
                                                        </li>

                                                        <li>
                                                            <a href="#"
                                                                class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">3</a>
                                                        </li>

                                                        <li>
                                                            <a href="#"
                                                                class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">4</a>
                                                        </li>

                                                        <li>
                                                            <a href="#"
                                                                class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">5</a>
                                                        </li>

                                                        <li>
                                                            <div class="lp-pagination__elipsis">...</div>
                                                        </li>

                                                        <li>
                                                            <a href="#"
                                                                class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">13</a>
                                                        </li>
                                                    </ul>
                                                </li>

                                                <li class="lp-flex lp-align-center">
                                                    <span class="lp-typo lp-sub lp-grey">След</span>

                                                    <a href="#"
                                                        class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled">
                                                        <i class="lp-icon lp-angle-right-flat"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
