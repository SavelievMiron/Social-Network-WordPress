<?php /* Template Name: Create Demand */ ?>

<? get_header(); ?>

<div class="lp-page lp-page-create-offer">
	<div class="lp-wrapper">
		<div class="lp-grid lp-container lp-spacing-4" data-lp-tabs id="tabs-form">
			<div class="lp-grid lp-item lp-xs-12">
				<div class="lp-tabs lp-flex lp-justify-center" data-lp-tabs="tabs">
					<?
						$type = $_GET['type'] ?? '';
					?>
					<button class="lp-button-base lp-button-tab" <? if (empty($type)) { echo 'data-lp-selected="true"'; } echo ('demand' === $type) ? 'data-lp-selected="true"' : ''; ?>>Спрос</button>
					<button class="lp-button-base lp-button-tab" <?php echo ( 'offer' === $type ) ? 'data-lp-selected="true"' : ''; ?>>Предложение</button>
				</div>
			</div>

			<div class="lp-grid lp-item lp-xs-12">
				<div class="lp-grid lp-container lp-spacing-3" data-lp-tabs="views">
					<form class="form-demand lp-grid lp-item lp-xs-12" enctype="multipart/form-data">
						<div class="lp-grid lp-container lp-spacing-3">
							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-paper lp-elevation lp-dense-6">
									<div class="lp-grid lp-container lp-spacing-3">
										<div class="lp-grid lp-item">
											<div class="lp-paper__title">
												<span class="lp-typo lp-footnote lp-uppercase">Загрузите обложку
													(Обязательно)</span>
											</div>
										</div>

										<div class="lp-grid lp-item lp-xs-12">
											<div class="cover lp-file-upload">
												<div class="image-preview"></div>
												<i class="lp-icon lp-cloud-upload-flat"></i>
												<label class="lp-file-upload__label">
													<span class="lp-typo lp-body lp-grey">Перетащите или</span>
													<span class="lp-file-upload__input">
														<input type="file" name="cover" accept=".jpg,.jpeg,.png"
															tabindex="-1" />
														<span role="button" tabindex="0">выберите файл</span>
													</span>
												</label>
												<input class="file-checker" type="hidden" name="cover_check">
											</div>
										</div>

										<div class="lp-grid lp-item">
											<span class="lp-typo lp-grey lp-footnote">Рекомендуемый размер — 300х300.
												Макс. вес изображения — 2 МВ.</span>
										</div>
									</div>
								</div>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-paper lp-elevation lp-dense-6">
									<div class="lp-grid lp-container lp-spacing-3">
										<div class="lp-grid lp-item">
											<div class="lp-paper__title">
												<span class="lp-typo lp-footnote lp-uppercase">Загрузите изображение или
													видео (до 50 МВ), которое описывает ваш спрос</span>
											</div>
										</div>

										<div class="lp-grid lp-item lp-xs-12">
											<div class="media-files lp-file-upload">
												<div class="image-preview"></div>
												<i class="lp-icon lp-cloud-upload-flat"></i>
												<label class="lp-file-upload__label">
													<span class="lp-typo lp-body lp-grey">Перетащите или</span>
													<span class="lp-file-upload__input">
														<input type="file" name="media_files" multiple
															accept=".jpg,.jpeg,.png,.mp4" tabindex="-1" />
														<span role="button" tabindex="0">выберите файл</span>
													</span>
												</label>
												<input class="file-checker" type="hidden" name="media_check">
											</div>
										</div>

										<div class="lp-grid lp-item">
											<span class="lp-typo lp-grey lp-footnote">Рекомендуемый размер изображения —
												900х600. Макс. вес изображения — 2 МВ. Макс. вес видео — 50 МВ.</span>
										</div>
									</div>
								</div>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-paper lp-elevation lp-dense-6">
									<div class="lp-grid lp-container lp-spacing-3">
										<div class="lp-grid lp-item lp-xs-12">
											<div class="title lp-textfield lp-variant-outlined" data-lp-textfield>
												<div class="lp-textfield__input">
													<label class="lp-textfield__label">
														<input type="text" name="title" data-lp-textfield="input" />
														<span>Заголовок</span>
													</label>
												</div>
											</div>
										</div>

										<div class="lp-grid lp-item lp-lg-6 lp-md-8 lp-xs-12">
											<select class="categories select2" name="categories[]" multiple>
												<?
													$categories = get_terms( array(
														'taxonomy' => 'categories',
														'hide_empty' => false
													));

													foreach( $categories as $cat ):
												?>

												<option value="<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></option>

												<?  endforeach; ?>
											</select>
											<div class="lp-grid lp-item">
												<span class="lp-typo lp-grey lp-footnote available-categories">Доступно для добавления <span id="count">5</span> категорий.</span>
											</div>
										</div>

										<div class="lp-grid lp-item lp-xs-12">
											<textarea id="demanddescription" class="wp-editor-area" name="description"></textarea>
										</div>

										<div class="lp-grid lp-item lp-xs-12">
											<div class="lp-grid lp-container lp-spacing-1">
												<div class="lp-grid lp-item lp-lg-3 lp-md-8 lp-xs-12">
													<div class="total_price lp-textfield lp-variant-outlined"
														data-lp-textfield>
														<div class="lp-textfield__input">
															<label class="lp-textfield__label">
																<input name="total_price" type="text"
																	data-lp-textfield="input" />
																<span>Стоимость</span>
															</label>
														</div>
													</div>
												</div>

												<div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-xs-12">
													<div class="cryptocurrency lp-select" data-lp-select>
														<div class="lp-select__textfield lp-textfield lp-variant-outlined"
															data-lp-select="textfield">
															<div class="lp-textfield__input">
																<label class="lp-textfield__label">
																	<span data-lp-select="value"></span>
																	<input type="hidden" name="cryptocurrency"
																		data-lp-textfield="input"
																		data-lp-select="input" />

																	<span>Формат расчёта</span>
																</label>

																<div class="lp-textfield__postfix">
																	<button type="button"
																		class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																		data-lp-select="clear">
																		<i class="lp-icon lp-times-flat"></i>
																	</button>

																	<button type="button"
																		class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																		data-lp-select="button">
																		<i class="lp-icon lp-angle-down-flat"></i>
																	</button>
																</div>
															</div>
														</div>

														<div class="lp-select__options lp-paper lp-elevation lp-dense-3"
															data-lp-select="popover" aria-hidden="true">
															<div class="lp-select__search lp-textfield lp-variant-outlined" data-lp-select="search">
																<div class="lp-textfield__input">
																	<label class="lp-textfield__label">
																	<input type="text" data-lp-textfield="input" />

																	<span>Поиск</span>
																	</label>
																</div>
															</div>
															<ul class="lp-list lp-no-style" data-lp-select="options">
																<? foreach( DAO_CONSENSUS::cryptocurrencies as $k => $v ): ?>
																<li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
																	data-lp-value="<?php echo $k; ?>">
																	<span><?php echo $v; ?></span>
																</li>
																<? endforeach; ?>
															</ul>
														</div>
													</div>
												</div>

												<div class="lp-grid lp-item lp-lg-3 lp-md-8 lp-xs-12">
													<div class="deadline lp-textfield lp-variant-outlined"
														data-lp-textfield>
														<div class="lp-textfield__input">
															<label class="lp-textfield__label">
																<input name="deadline" type="text"
																	data-lp-textfield="input" />
																<span>Срок выполнения</span>
															</label>
														</div>
													</div>
												</div>

												<div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-xs-12">
													<div class="deadline-period lp-select" data-lp-select>
														<div class="lp-select__textfield lp-textfield lp-variant-outlined"
															data-lp-select="textfield">
															<div class="lp-textfield__input">
																<label class="lp-textfield__label">
																	<span data-lp-select="value"></span>
																	<input type="hidden" name="deadline_period"
																		data-lp-textfield="input"
																		data-lp-select="input" />

																	<span>Период</span>
																</label>

																<div class="lp-textfield__postfix">
																	<button type="button"
																		class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																		data-lp-select="clear">
																		<i class="lp-icon lp-times-flat"></i>
																	</button>

																	<button type="button"
																		class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																		data-lp-select="button">
																		<i class="lp-icon lp-angle-down-flat"></i>
																	</button>
																</div>
															</div>
														</div>

														<div class="lp-select__options lp-paper lp-elevation lp-dense-3"
															data-lp-select="popover" aria-hidden="true">
															<div class="lp-select__search lp-textfield lp-variant-outlined" data-lp-select="search">
																<div class="lp-textfield__input">
																	<label class="lp-textfield__label">
																	<input type="text" data-lp-textfield="input" />

																	<span>Поиск</span>
																	</label>
																</div>
															</div>
															<ul class="lp-list lp-no-style" data-lp-select="options">
																<? foreach( DAO_CONSENSUS::deadline_periods as $k => $v ): ?>
																<li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
																	data-lp-value="<?php echo $k; ?>">
																	<span><?php echo $v; ?></span>
																</li>
																<? endforeach; ?>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-paper lp-skills lp-elevation lp-dense-6">
									<div class="lp-grid lp-container lp-spacing-3">
										<div class="lp-grid lp-item">
											<div class="lp-paper__title">
												<span class="lp-typo lp-footnote lp-grey lp-uppercase">Сведения о
													навыках</span>
											</div>
										</div>

										<div class="lp-grid lp-item lp-xs-12">
											<div class="lp-grid lp-container lp-spacing-4">
												<div class="lp-grid lp-item lp-xs-12">
													<select class="skills select2" name="skills[]" multiple>
														<?
															$skills = array_map( 'trim', explode(', ', cmb2_get_option('daoc_others', 'all_skills')) );

															foreach( $skills as $v ):
														?>
														<option value="<?php echo $v; ?>"><?php echo $v; ?></option>
														<?  endforeach; ?>
													</select>
													<div class="lp-grid lp-item">
														<span class="lp-typo lp-grey lp-footnote available-skills">Доступно для добавления <span id="count">20</span> навыков.</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-grid lp-container lp-spacing-3">
									<div class="lp-grid lp-item lp-xs-12 lp-flex lp-justify-center">
										<p class="lp-typo lp-sub lp-grey">
											После нажатия на кнопку “Создать”, ваша заявка отправиться на проверку
											модерацией сайта.
										</p>
									</div>

									<div class="lp-grid lp-item lp-xs-12">
										<div class="lp-grid lp-container lp-spacing-2">
											<div class="lp-grid lp-item lp-xs-6 lp-flex lp-justify-end">
												<button type="button"
													class="lp-button-base lp-button lp-size-large lp-theme-default lp-variant-flat">
													Отмена
												</button>

											</div>
											<div class="lp-grid lp-item lp-xs-6 lp-flex lp-align-center">
												<button type="submit"
													class="lp-button-base lp-button lp-size-large lp-theme-primary lp-variant-filled">
													Cоздать
												</button>
												<span class="lp-loader lp-hide">
													<svg class="lp-loader__circle" width="40" height="40"
														viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
														<circle class="lp-loader__path" fill="none" stroke-width="5"
															stroke-linecap="round" cx="24" cy="24" r="20" />
													</svg>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="action" value="daoc_create_demand">
						<input type="hidden" name="_wpnonce"
							value="<?php echo wp_create_nonce( 'dao-consensus-create-demand' ); ?>">
					</form>

					<form class="form-offer lp-grid lp-item lp-xs-12" enctype="multipart/form-data">
						<div class="lp-grid lp-container lp-spacing-3">
							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-paper lp-elevation lp-dense-6">
									<div class="lp-grid lp-container lp-spacing-3">
										<div class="lp-grid lp-item">
											<div class="lp-paper__title">
												<span class="lp-typo lp-footnote lp-uppercase">Загрузите обложку
													(Обязательно)</span>
											</div>
										</div>

										<div class="lp-grid lp-item lp-xs-12">
											<div class="cover lp-file-upload">
												<div class="image-preview"></div>
												<i class="lp-icon lp-cloud-upload-flat"></i>
												<label class="lp-file-upload__label">
													<span class="lp-typo lp-body lp-grey">Перетащите или</span>
													<span class="lp-file-upload__input">
														<input type="file" name="cover" accept=".jpg,.jpeg,.png"
															tabindex="-1" />
														<span role="button" tabindex="0">выберите файл</span>
													</span>
												</label>
												<input class="file-checker" type="hidden" name="cover_check">
											</div>
										</div>

										<div class="lp-grid lp-item">
											<span class="lp-typo lp-grey lp-footnote">Рекомендуемый размер — 300х300.
												Макс. вес изображения — 2 МВ.</span>
										</div>
									</div>
								</div>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-paper lp-elevation lp-dense-6">
									<div class="lp-grid lp-container lp-spacing-3">
										<div class="lp-grid lp-item">
											<div class="lp-paper__title">
												<span class="lp-typo lp-footnote lp-uppercase">Загрузите изображение или
													видео (до 50 МВ), которое описывает ваше предложение</span>
											</div>
										</div>

										<div class="lp-grid lp-item lp-xs-12">
											<div class="media-files lp-file-upload">
												<div class="image-preview"></div>
												<i class="lp-icon lp-cloud-upload-flat"></i>
												<label class="lp-file-upload__label">
													<span class="lp-typo lp-body lp-grey">Перетащите или</span>
													<span class="lp-file-upload__input">
														<input type="file" name="media_files" multiple
															accept=".jpg,.jpeg,.png,.mp4" tabindex="-1" />
														<span role="button" tabindex="0">выберите файл</span>
													</span>
												</label>
												<input class="file-checker" type="hidden" name="media_check">
											</div>
										</div>

										<div class="lp-grid lp-item">
											<span class="lp-typo lp-grey lp-footnote">Рекомендуемый размер изображения —
												900х600. Макс. вес изображения — 2 МВ. Макс. вес видео — 50 МВ.</span>
										</div>
									</div>
								</div>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-paper lp-elevation lp-dense-6">
									<div class="lp-grid lp-container lp-spacing-3">
										<div class="lp-grid lp-item lp-xs-12">
											<div class="title lp-textfield lp-variant-outlined" data-lp-textfield>
												<div class="lp-textfield__input">
													<label class="lp-textfield__label">
														<input type="text" name="title" data-lp-textfield="input" />
														<span>Заголовок</span>
													</label>
												</div>
											</div>
										</div>

										<div class="lp-grid lp-item lp-lg-6 lp-md-8 lp-xs-12">
											<select class="categories select2" name="categories[]" multiple>
												<?
													$categories = get_terms( array(
														'taxonomy' => 'categories',
														'hide_empty' => false
													));

													foreach( $categories as $cat ):
												?>
												<option value="<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></option>
												<?  endforeach; ?>
											</select>

											<div class="lp-grid lp-item">
												<span class="lp-typo lp-grey lp-footnote available-categories">Доступно для добавления <span id="count">5</span> категорий.</span>
											</div>
										</div>

										<div class="lp-grid lp-item lp-xs-12">
											<textarea id="offerdescription" class="wp-editor-area" name="description"></textarea>
										</div>

										<div class="lp-grid lp-item lp-xs-12">
											<div class="lp-grid lp-container lp-spacing-1">
												<div class="lp-grid lp-item lp-lg-3 lp-md-8 lp-xs-12">
													<div class="total_price lp-textfield lp-variant-outlined"
														data-lp-textfield>
														<div class="lp-textfield__input">
															<label class="lp-textfield__label">
																<input type="text" name="total_price"
																	data-lp-textfield="input" />
																<span>Стоимость</span>
															</label>
														</div>
													</div>
												</div>

												<div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-xs-12">
													<div class="cryptocurrency lp-select" data-lp-select>
														<div class="lp-select__textfield lp-textfield lp-variant-outlined"
															data-lp-select="textfield">
															<div class="lp-textfield__input">
																<label class="lp-textfield__label">
																	<span data-lp-select="value"></span>
																	<input type="hidden" name="cryptocurrency"
																		data-lp-textfield="input"
																		data-lp-select="input" />

																	<span>Формат расчёта</span>
																</label>

																<div class="lp-textfield__postfix">
																	<button type="button"
																		class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																		data-lp-select="clear">
																		<i class="lp-icon lp-times-flat"></i>
																	</button>

																	<button type="button"
																		class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																		data-lp-select="button">
																		<i class="lp-icon lp-angle-down-flat"></i>
																	</button>
																</div>
															</div>
														</div>

														<div class="lp-select__options lp-paper lp-elevation lp-dense-3"
															data-lp-select="popover" aria-hidden="true">
															<div class="lp-select__search lp-textfield lp-variant-outlined" data-lp-select="search">
																<div class="lp-textfield__input">
																	<label class="lp-textfield__label">
																	<input type="text" data-lp-textfield="input" />

																	<span>Поиск</span>
																	</label>
																</div>
															</div>
															<ul class="lp-list lp-no-style" data-lp-select="options">
																<? foreach( DAO_CONSENSUS::cryptocurrencies as $k => $v ): ?>
																<li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
																	data-lp-value="<?php echo $k; ?>">
																	<span><?php echo $v; ?></span>
																</li>
																<? endforeach; ?>
															</ul>
														</div>
													</div>

												</div>

												<div class="lp-grid lp-item lp-lg-3 lp-md-8 lp-xs-12">
													<div class="deadline lp-textfield lp-variant-outlined"
														data-lp-textfield>
														<div class="lp-textfield__input">
															<label class="lp-textfield__label">
																<input type="text" name="deadline"
																	data-lp-textfield="input" />
																<span>Срок выполнения</span>
															</label>
														</div>
													</div>
												</div>

												<div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-xs-12">
													<div class="deadline-period lp-select" data-lp-select>
														<div class="lp-select__textfield lp-textfield lp-variant-outlined"
															data-lp-select="textfield">
															<div class="lp-textfield__input">
																<label class="lp-textfield__label">
																	<span data-lp-select="value"></span>
																	<input type="hidden" name="deadline_period"
																		data-lp-textfield="input"
																		data-lp-select="input" />

																	<span>Период</span>
																</label>

																<div class="lp-textfield__postfix">
																	<button type="button"
																		class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																		data-lp-select="clear">
																		<i class="lp-icon lp-times-flat"></i>
																	</button>

																	<button type="button"
																		class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
																		data-lp-select="button">
																		<i class="lp-icon lp-angle-down-flat"></i>
																	</button>
																</div>
															</div>
														</div>

														<div class="lp-select__options lp-paper lp-elevation lp-dense-3"
															data-lp-select="popover" aria-hidden="true">
															<div class="lp-select__search lp-textfield lp-variant-outlined" data-lp-select="search">
																<div class="lp-textfield__input">
																	<label class="lp-textfield__label">
																	<input type="text" data-lp-textfield="input" />

																	<span>Поиск</span>
																	</label>
																</div>
															</div>
															<ul class="lp-list lp-no-style" data-lp-select="options">
																<? foreach( DAO_CONSENSUS::deadline_periods as $k => $v ): ?>
																<li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
																	data-lp-value="<?php echo $k; ?>">
																	<span><?php echo $v; ?></span>
																</li>
																<? endforeach; ?>
															</ul>
														</div>
													</div>

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-paper lp-skills lp-elevation lp-dense-6">
									<div class="lp-grid lp-container lp-spacing-3">
										<div class="lp-grid lp-item">
											<div class="lp-paper__title">
												<span class="lp-typo lp-footnote lp-grey lp-uppercase">Сведения о
													навыках</span>
											</div>
										</div>

										<div class="lp-grid lp-item lp-xs-12">
											<div class="lp-grid lp-container lp-spacing-4">
												<div class="lp-grid lp-item lp-xs-12">
													<select class="skills select2" name="skills[]" multiple>
														<?
															$skills = array_map( 'trim', explode(', ', cmb2_get_option('daoc_others', 'all_skills')) );

															foreach( $skills as $v ):
														?>
															<option value="<?php echo $v; ?>"><?php echo $v; ?></option>
														<?  endforeach; ?>
													</select>
													<div class="lp-grid lp-item">
														<span class="lp-typo lp-grey lp-footnote available-skills">Доступно для добавления <span id="count">20</span> навыков.</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="lp-grid lp-item lp-xs-12">
								<div class="lp-grid lp-container lp-spacing-3">
									<div class="lp-grid lp-item lp-xs-12 lp-flex lp-justify-center">
										<p class="lp-typo lp-sub lp-grey">
											После нажатия на кнопку “Создать”, ваша заявка отправиться на проверку
											администрацией сайта.
										</p>
									</div>

									<div class="lp-grid lp-item lp-xs-12">
										<div class="lp-grid lp-container lp-spacing-2">
											<div class="lp-grid lp-item lp-xs-6 lp-flex lp-justify-end">
												<button type="button"
													class="lp-button-base lp-button lp-size-large lp-theme-default lp-variant-flat">
													Отмена
												</button>
											</div>
											<div class="lp-grid lp-item lp-xs-6 lp-flex lp-align-center">
												<button type="submit"
													class="lp-button-base lp-button lp-size-large lp-theme-primary lp-variant-filled">
													Cоздать
												</button>
												<span class="lp-loader lp-hide">
													<svg class="lp-loader__circle" width="40" height="40"
														viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
														<circle class="lp-loader__path" fill="none" stroke-width="5"
															stroke-linecap="round" cx="24" cy="24" r="20" />
													</svg>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="action" value="daoc_create_offer">
						<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'dao-consensus-create-offer' ); ?>">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<? get_footer(); ?>
