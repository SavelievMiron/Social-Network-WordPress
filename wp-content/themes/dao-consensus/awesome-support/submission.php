<?php
/**
 * This is a built-in template file. If you need to customize it, please,
 * DO NOT modify this file directly. Instead, copy it to your theme's directory
 * and then modify the code. If you modify this file directly, your changes
 * will be overwritten during next update of the plugin.
 */

global $post;
?>

<div class="lp-page lp-page-feedback">
	<div class="lp-wrapper">
		<div class="lp-grid lp-container lp-spacing-3">
			<div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-12">
				<? get_template_part( 'template-parts/profile', 'sidebar' ); ?>
			</div>

			<div class="lp-grid lp-item lp-lg-9 lp-md-8 lp-sm-12">
				<div class="lp-grid lp-container lp-spacing-3">
					<div class="lp-grid lp-item lp-xs-12">
						<div class="lp-paper lp-elevation lp-dense-6">
							<div class="lp-grid lp-container lp-spacing-3 lp-justify-center">
								<div class="lp-grid lp-item lp-xs-12 lp-justify-center">
									<h4 class="lp-typo lp-h4 lp-align-center lp-mb-10">Создать тикет</h4>
								</div>
								<div class="wpas wpas-submit-ticket lp-mb-20">

									<?php
										// wpas_get_template( 'partials/ticket-navigation' );

										do_action( 'wpas_ticket_submission_form_outside_top' );
									?>

									<form class="wpas-form" role="form" method="post"
										action="<?php echo get_permalink( $post->ID ); ?>" id="wpas-new-ticket"
										enctype="multipart/form-data">

										<?php
										/**
										 * The wpas_submission_form_inside_top has to be placed
										 * inside the form, right in between the form opening tag
										 * and the first field being rendered.
										 *
										 * @since  4.4.0
										 */
										do_action( 'wpas_submission_form_inside_top' );

										/**
										* Filter the subject field arguments
										*
										* @since 3.2.0
										*
										* Note the use of the The wpas_submission_form_inside_before
										* action hook.  It will be placed inside the form, usually
										* right in between the form opening tag
										* and the subject field.
										*
										* However, the hook can be moved if the subject field is set
										* to a different sort order in the custom fields array.
										*
										* The wpas_submission_form_inside_after_subject action
										* hook is also declared as a post-render hook.
										*/
										$subject_args = apply_filters( 'wpas_subject_field_args', array(
											'name' => 'title',
											'args' => array(
												'required'   => true,
												'field_type' => 'text',
												'label'      => '',
												'placeholder' => __( 'Название заявки', 'dao-consensus' ),
												'sanitize'   => 'sanitize_text_field',
												'order'		 => '-2',
												'pre_render_action_hook_fe'		=> 'wpas_submission_form_inside_before_subject',
												'post_render_action_hook_fe'	=> 'wpas_submission_form_inside_after_subject',
											)
										) );
										?>
										<!-- <div class="title lp-textfield lp-variant-outlined lp-mb-10" data-lp-textfield>
											<div class="lp-textfield__input">
												<label class="lp-textfield__label">
													<input type="text" name="title" data-lp-textfield="input" required/>
													<span>Название тикета</span>
												</label>
											</div>
										</div> -->
										<?

										wpas_add_custom_field($subject_args['name'], $subject_args['args']);

										/**
										* Filter the description field arguments
										*
										* @since 3.2.0
										*/
										$body_args = apply_filters( 'wpas_description_field_args', array(
											'name' => 'message',
											'args' => array(
												'required'   => true,
												'field_type' => 'wysiwyg',
												'label'      => '',//__( 'Описание', 'dao-consensus' ),
												'sanitize'   => 'sanitize_text_field',
												'order'		 => '-1',
												'pre_render_action_hook_fe'		=> 'wpas_submission_form_inside_before_description',
												'post_render_action_hook_fe'	=> 'wpas_submission_form_inside_after_description',
											)
										) );

										wpas_add_custom_field($body_args['name'], $body_args['args']);

										/**
										* Declare an action hook just before rendering all the fields...
										*/
										do_action( 'wpas_submission_form_pre_render_fields' );

										/* All custom fields have been declared so render them all */
										WPAS()->custom_fields->submission_form_fields();

										/**
										* Declare an action hook just after rendering all the fields...
										*/
										do_action( 'wpas_submission_form_post_render_fields' );


										/**
										* The wpas_submission_form_inside_before hook has to be placed
										* right before the submission button.
										*
										* @since  3.0.0
										*/
										do_action( 'wpas_submission_form_inside_before_submit' );

										wp_nonce_field( 'new_ticket', 'wpas_nonce', true, true );
										wpas_make_button( __( 'Отправить', 'dao-consensus' ), array( 'name' => 'wpas-submit' ) );

										/**
										* The wpas_submission_form_inside_before hook has to be placed
										* right before the form closing tag.
										*
										* @since  3.0.0
										*/
										do_action( 'wpas_submission_form_inside_after' );
										wpas_do_field( 'submit_new_ticket' );
										?>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
