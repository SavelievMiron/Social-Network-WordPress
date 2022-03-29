<?php /* Template Name: Components */  ?>

<? get_header(); ?>

<div class="lp-page">
  <div class="lp-wrapper">
    <div class="lp-grid lp-item lp-xs-12">
      <div class="lp-grid lp-container lp-spacing-5">
        <!-- .loader -->
        <div class="lp-grid lp-item lp-xs-12">
          <div class="lp-loader">
            <svg class="lp-loader__circle" width="48" height="48" viewBox="0 0 48 48"
              xmlns="http://www.w3.org/2000/svg">
              <circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round" cx="24" cy="24"
                r="20" />
            </svg>
          </div>
        </div>

        <!-- .select2 -->
        <div class="lp-grid lp-item lp-xs-12">
          <div class="lp-grid lp-container lp-spacing-2">
            <div class="lp-item lp-grid lp-xs-4">
              <select class="select2-1" name="state">
                <option value="AL">Alabama</option>
                <option value="WY">Wyoming</option>
                <option value="WY">Wyoming</option>
              </select>
            </div>

            <div class="lp-item lp-grid lp-xs-4">
              <select class="select2-2" name="state">
                <option value="AL">Alabama</option>
                <option value="WY">Wyoming</option>
                <option value="WY">Wyoming</option>
              </select>
            </div>
          </div>
        </div>

        <!-- .date-picker-modal -->
        <div class="lp-grid lp-item lp-xs-12">
          <button class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled" id="date-picker">
            Date Picker
          </button>

          <div class="lp-modal" data-lp-modal aria-hidden="true" aria-label="date-picker" id="date-picker">
            <div class="lp-modal__wrapper">
              <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                <div class="lp-modal__body">
                  <div class="lp-grid lp-container lp-justify-center">
                    <div class="lp-grid lp-item">
                      <div class="lp-date-picker" id="test-datepicker"></div>
                    </div>
                  </div>
                </div>

                <footer class="lp-modal__footer">
                  <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                    <div class="lp-grid lp-item">
                      <button class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                        data-lp-modal="cancel">
                        Отмена
                      </button>
                    </div>

                    <div class="lp-grid lp-item">
                      <button
                        class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">Принять</button>
                    </div>
                  </div>
                </footer>

                <div class="lp-modal__close">
                  <button
                    class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                    data-lp-modal="close">
                    <i class="lp-icon lp-times-flat"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- .tabs -->
        <div class="lp-grid lp-item lp-xs-12">
          <div class="lp-grid lp-container lp-spacing-4" data-lp-tabs id="test-tabs">
            <div class="lp-grid lp-item lp-xs-12">
              <div class="lp-tabs" data-lp-tabs="tabs">
                <button class="lp-button-base lp-button-tab">Спрос</button>
                <button class="lp-button-base lp-button-tab" data-lp-selected="true">Мой спрос</button>
              </div>
            </div>

            <div class="lp-grid lp-item lp-xs-12">
              <div class="lp-grid lp-container lp-spacing-3" data-lp-tabs="views">
                <div class="lp-grid lp-item lp-xs-12">
                  <div class="lp-paper lp-elevation lp-dense-6">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Officia, facere?
                  </div>
                </div>

                <div class="lp-grid lp-item lp-xs-12">
                  <div class="lp-paper lp-elevation lp-dense-6">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nostrum possimus eligendi vero ipsa
                    doloribus
                    expedita quae velit eveniet sint doloremque!
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- .card-offer -->
        <div class="lp-grid lp-item lp-xs-12">
          <div class="lp-paper lp-elevation lp-dense-6">
            <div class="lp-grid lp-container lp-spacing-3">
              <div class="lp-grid lp-item">
                <div class="lp-paper__title">
                  <span class="lp-typo lp-footnote lp-uppercase"> Карточки предложения </span>
                </div>
              </div>

              <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-grid lp-container lp-spacing-3">
                  <div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-6 lp-xs-12">
                    <article class="lp-card lp-card-offer lp-status-active" data-lp-card>
                      <header class="lp-card__header">
                        <div class="lp-card-offer__actions lp-flex lp-align-start lp-justify-between">
                          <div class="lp-flex lp-wrap">
                            <div class="lp-chip">
                              <span class="lp-chip__label">Активно</span>
                            </div>
                          </div>

                          <button
                            class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled"
                            data-lp-card="button">
                            <i class="lp-icon lp-ellipsis-hr-flat"></i>
                          </button>

                          <div class="lp-card__options lp-paper lp-elevation lp-dense-3 lp-popover"
                            data-lp-card="popover" aria-hidden="true">
                            <ul class="lp-list lp-no-style" data-lp-card="options">
                              <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                                <i class="lp-icon lp-prefix lp-edit-flat"></i>
                                <span>Редактировать</span>
                              </li>

                              <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                                <i class="lp-icon lp-prefix lp-trash-flat"></i>
                                <span>Удалить</span>
                              </li>

                              <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                                <i class="lp-icon lp-prefix lp-check-outlined"></i>
                                <span>Изменить статус</span>
                              </li>
                            </ul>
                          </div>
                        </div>

                        <div class="lp-card__media">
                          <img src="/assets/images/LqKhnDzSj.png" alt="demand card" height="210" class="lp-card__img" />
                        </div>

                        <div class="lp-chip lp-primary lp-variant-filled lp-card-offer__chip">
                          <div class="lp-chip__label">Физ. лицо</div>
                        </div>
                      </header>

                      <div class="lp-card__body">
                        <div class="lp-card__content">
                          <h5>Любая правка сайта</h5>

                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__info">
                          <ul class="lp-list lp-no-style">
                            <li>
                              <span class="lp-typo lp-sub lp-grey">Формат расчёта: ETH</span>
                            </li>
                            <li>
                              <span class="lp-typo lp-sub lp-grey">Cрок выполнения: до 10 дней</span>
                            </li>
                          </ul>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__user lp-flex lp-justify-between">
                          <span class="lp-typo lp-footnote lp-flex lp-align-center">
                            <i class="lp-icon lp-star-outlined"></i>
                            <span>Artemrav</span>
                          </span>

                          <span class="lp-typo lp-sub"><b>6.50</b></span>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__skills">
                          <span class="lp-typo lp-sub lp-grey">
                            Web Design, UX/UI Design, Web developer, Front-End developer, Design,
                          </span>
                          <a href="/good.html" class="lp-link lp-theme-primary"><small>Подробнее...</small></a>
                        </div>
                      </div>

                      <div class="lp-modal" data-lp-modal aria-label="edit card modal" data-lp-card="edit-modal"
                        aria-hidden="true">
                        <div class="lp-modal__wrapper">
                          <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                            <header class="lp-modal__header">
                              <h5 class="lp-typo lp-h5">Редактировать?</h5>
                            </header>

                            <div class="lp-modal__body">
                              <p class="lp-typo lp-body">Редактировать</p>
                            </div>

                            <footer class="lp-modal__footer">
                              <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Отмена
                                  </button>
                                </div>

                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Подтвердить
                                  </button>
                                </div>
                              </div>
                            </footer>

                            <div class="lp-modal__close">
                              <button
                                class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                                data-lp-modal="close">
                                <i class="lp-icon lp-times-flat"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="lp-modal" data-lp-modal aria-label="change card status modal"
                        data-lp-card="change-status-modal" aria-hidden="true">
                        <div class="lp-modal__wrapper">
                          <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                            <header class="lp-modal__header">
                              <h5 class="lp-typo lp-h5">Поменять статус?</h5>
                            </header>

                            <div class="lp-modal__body">
                              <p class="lp-typo lp-body">Поменять статус</p>
                            </div>

                            <footer class="lp-modal__footer">
                              <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Отмена
                                  </button>
                                </div>

                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Подтвердить
                                  </button>
                                </div>
                              </div>
                            </footer>

                            <div class="lp-modal__close">
                              <button
                                class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                                data-lp-modal="close">
                                <i class="lp-icon lp-times-flat"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="lp-modal" data-lp-modal aria-label="delete card modal" data-lp-card="delete-modal"
                        aria-hidden="true">
                        <div class="lp-modal__wrapper">
                          <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                            <header class="lp-modal__header">
                              <h5 class="lp-typo lp-h5">Удалить?</h5>
                            </header>

                            <div class="lp-modal__body">
                              <p class="lp-typo lp-body">Удалить</p>
                            </div>

                            <footer class="lp-modal__footer">
                              <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Отмена
                                  </button>
                                </div>

                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Подтвердить
                                  </button>
                                </div>
                              </div>
                            </footer>

                            <div class="lp-modal__close">
                              <button
                                class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                                data-lp-modal="close">
                                <i class="lp-icon lp-times-flat"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>

                    </article>

                  </div>

                  <div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-6 lp-xs-12">
                    <article class="lp-card lp-card-offer lp-status-inactive" data-lp-card>
                      <header class="lp-card__header">
                        <div class="lp-card-offer__actions lp-flex lp-align-start lp-justify-between">
                          <div class="lp-flex lp-wrap">
                            <div class="lp-chip">
                              <span class="lp-chip__label">Активно</span>
                            </div>
                          </div>

                          <button
                            class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled"
                            data-lp-card="button">
                            <i class="lp-icon lp-ellipsis-hr-flat"></i>
                          </button>

                          <div class="lp-card__options lp-paper lp-elevation lp-dense-3 lp-popover"
                            data-lp-card="popover" aria-hidden="true">
                            <ul class="lp-list lp-no-style" data-lp-card="options">
                              <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                                <i class="lp-icon lp-prefix lp-edit-flat"></i>
                                <span>Редактировать</span>
                              </li>

                              <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                                <i class="lp-icon lp-prefix lp-trash-flat"></i>
                                <span>Удалить</span>
                              </li>

                              <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                                <i class="lp-icon lp-prefix lp-check-outlined"></i>
                                <span>Изменить статус</span>
                              </li>
                            </ul>
                          </div>
                        </div>

                        <div class="lp-card__media">
                          <img src="/assets/images/LqKhnDzSj.png" alt="demand card" height="210" class="lp-card__img" />
                        </div>

                        <div class="lp-chip lp-primary lp-variant-filled lp-card-offer__chip">
                          <div class="lp-chip__label">Физ. лицо</div>
                        </div>
                      </header>

                      <div class="lp-card__body">
                        <div class="lp-card__content">
                          <h5>Любая правка сайта</h5>

                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__info">
                          <ul class="lp-list lp-no-style">
                            <li>
                              <span class="lp-typo lp-sub lp-grey">Формат расчёта: ETH</span>
                            </li>
                            <li>
                              <span class="lp-typo lp-sub lp-grey">Cрок выполнения: до 10 дней</span>
                            </li>
                          </ul>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__user lp-flex lp-justify-between">
                          <span class="lp-typo lp-footnote lp-flex lp-align-center">
                            <i class="lp-icon lp-star-outlined"></i>
                            <span>Artemrav</span>
                          </span>

                          <span class="lp-typo lp-sub"><b>6.50</b></span>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__skills">
                          <span class="lp-typo lp-sub lp-grey">
                            Web Design, UX/UI Design, Web developer, Front-End developer, Design,
                          </span>
                          <a href="/good.html" class="lp-link lp-theme-primary"><small>Подробнее...</small></a>
                        </div>
                      </div>

                      <div class="lp-modal" data-lp-modal aria-label="edit card modal" data-lp-card="edit-modal"
                        aria-hidden="true">
                        <div class="lp-modal__wrapper">
                          <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                            <header class="lp-modal__header">
                              <h5 class="lp-typo lp-h5">Редактировать?</h5>
                            </header>

                            <div class="lp-modal__body">
                              <p class="lp-typo lp-body">Редактировать</p>
                            </div>

                            <footer class="lp-modal__footer">
                              <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Отмена
                                  </button>
                                </div>

                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Подтвердить
                                  </button>
                                </div>
                              </div>
                            </footer>

                            <div class="lp-modal__close">
                              <button
                                class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                                data-lp-modal="close">
                                <i class="lp-icon lp-times-flat"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="lp-modal" data-lp-modal aria-label="change card status modal"
                        data-lp-card="change-status-modal" aria-hidden="true">
                        <div class="lp-modal__wrapper">
                          <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                            <header class="lp-modal__header">
                              <h5 class="lp-typo lp-h5">Поменять статус?</h5>
                            </header>

                            <div class="lp-modal__body">
                              <p class="lp-typo lp-body">Поменять статус</p>
                            </div>

                            <footer class="lp-modal__footer">
                              <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Отмена
                                  </button>
                                </div>

                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Подтвердить
                                  </button>
                                </div>
                              </div>
                            </footer>

                            <div class="lp-modal__close">
                              <button
                                class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                                data-lp-modal="close">
                                <i class="lp-icon lp-times-flat"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="lp-modal" data-lp-modal aria-label="delete card modal" data-lp-card="delete-modal"
                        aria-hidden="true">
                        <div class="lp-modal__wrapper">
                          <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                            <header class="lp-modal__header">
                              <h5 class="lp-typo lp-h5">Удалить?</h5>
                            </header>

                            <div class="lp-modal__body">
                              <p class="lp-typo lp-body">Удалить</p>
                            </div>

                            <footer class="lp-modal__footer">
                              <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Отмена
                                  </button>
                                </div>

                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Подтвердить
                                  </button>
                                </div>
                              </div>
                            </footer>

                            <div class="lp-modal__close">
                              <button
                                class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                                data-lp-modal="close">
                                <i class="lp-icon lp-times-flat"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>

                    </article>

                  </div>

                  <div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-6 lp-xs-12">
                    <article class="lp-card lp-card-offer lp-status-completed" data-lp-card>
                      <header class="lp-card__header">
                        <div class="lp-card-offer__actions lp-flex lp-align-start lp-justify-between">
                          <div class="lp-flex lp-wrap">
                            <div class="lp-chip">
                              <span class="lp-chip__label">Активно</span>
                            </div>
                          </div>

                          <button
                            class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled"
                            data-lp-card="button">
                            <i class="lp-icon lp-ellipsis-hr-flat"></i>
                          </button>

                          <div class="lp-card__options lp-paper lp-elevation lp-dense-3 lp-popover"
                            data-lp-card="popover" aria-hidden="true">
                            <ul class="lp-list lp-no-style" data-lp-card="options">
                              <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                                <i class="lp-icon lp-prefix lp-edit-flat"></i>
                                <span>Редактировать</span>
                              </li>

                              <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                                <i class="lp-icon lp-prefix lp-trash-flat"></i>
                                <span>Удалить</span>
                              </li>

                              <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                                <i class="lp-icon lp-prefix lp-check-outlined"></i>
                                <span>Изменить статус</span>
                              </li>
                            </ul>
                          </div>
                        </div>

                        <div class="lp-card__media">
                          <img src="/assets/images/LqKhnDzSj.png" alt="demand card" height="210" class="lp-card__img" />
                        </div>

                        <div class="lp-chip lp-primary lp-variant-filled lp-card-offer__chip">
                          <div class="lp-chip__label">Физ. лицо</div>
                        </div>
                      </header>

                      <div class="lp-card__body">
                        <div class="lp-card__content">
                          <h5>Любая правка сайта</h5>

                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__info">
                          <ul class="lp-list lp-no-style">
                            <li>
                              <span class="lp-typo lp-sub lp-grey">Формат расчёта: ETH</span>
                            </li>
                            <li>
                              <span class="lp-typo lp-sub lp-grey">Cрок выполнения: до 10 дней</span>
                            </li>
                          </ul>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__user lp-flex lp-justify-between">
                          <span class="lp-typo lp-footnote lp-flex lp-align-center">
                            <i class="lp-icon lp-star-outlined"></i>
                            <span>Artemrav</span>
                          </span>

                          <span class="lp-typo lp-sub"><b>6.50</b></span>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__skills">
                          <span class="lp-typo lp-sub lp-grey">
                            Web Design, UX/UI Design, Web developer, Front-End developer, Design,
                          </span>
                          <a href="/good.html" class="lp-link lp-theme-primary"><small>Подробнее...</small></a>
                        </div>
                      </div>

                      <div class="lp-modal" data-lp-modal aria-label="edit card modal" data-lp-card="edit-modal"
                        aria-hidden="true">
                        <div class="lp-modal__wrapper">
                          <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                            <header class="lp-modal__header">
                              <h5 class="lp-typo lp-h5">Редактировать?</h5>
                            </header>

                            <div class="lp-modal__body">
                              <p class="lp-typo lp-body">Редактировать</p>
                            </div>

                            <footer class="lp-modal__footer">
                              <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Отмена
                                  </button>
                                </div>

                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Подтвердить
                                  </button>
                                </div>
                              </div>
                            </footer>

                            <div class="lp-modal__close">
                              <button
                                class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                                data-lp-modal="close">
                                <i class="lp-icon lp-times-flat"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="lp-modal" data-lp-modal aria-label="change card status modal"
                        data-lp-card="change-status-modal" aria-hidden="true">
                        <div class="lp-modal__wrapper">
                          <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                            <header class="lp-modal__header">
                              <h5 class="lp-typo lp-h5">Поменять статус?</h5>
                            </header>

                            <div class="lp-modal__body">
                              <p class="lp-typo lp-body">Поменять статус</p>
                            </div>

                            <footer class="lp-modal__footer">
                              <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Отмена
                                  </button>
                                </div>

                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Подтвердить
                                  </button>
                                </div>
                              </div>
                            </footer>

                            <div class="lp-modal__close">
                              <button
                                class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                                data-lp-modal="close">
                                <i class="lp-icon lp-times-flat"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="lp-modal" data-lp-modal aria-label="delete card modal" data-lp-card="delete-modal"
                        aria-hidden="true">
                        <div class="lp-modal__wrapper">
                          <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                            <header class="lp-modal__header">
                              <h5 class="lp-typo lp-h5">Удалить?</h5>
                            </header>

                            <div class="lp-modal__body">
                              <p class="lp-typo lp-body">Удалить</p>
                            </div>

                            <footer class="lp-modal__footer">
                              <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Отмена
                                  </button>
                                </div>

                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Подтвердить
                                  </button>
                                </div>
                              </div>
                            </footer>

                            <div class="lp-modal__close">
                              <button
                                class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                                data-lp-modal="close">
                                <i class="lp-icon lp-times-flat"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>

                    </article>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- .card-in-progress -->
        <div class="lp-grid lp-item lp-xs-12">
          <div class="lp-paper lp-elevation lp-dense-6">
            <div class="lp-grid lp-container lp-spacing-3">
              <div class="lp-grid lp-item">
                <div class="lp-paper__title">
                  <span class="lp-typo lp-footnote lp-uppercase"> Карточки в работе </span>
                </div>
              </div>

              <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-grid lp-container lp-spacing-3">
                  <div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-6 lp-xs-12">
                    <article class="lp-card lp-card-offer lp-status-in-process" data-lp-card>
                      <header class="lp-card__header">
                        <div class="lp-card-offer__actions lp-flex lp-align-start lp-justify-between">
                          <div class="lp-flex lp-wrap">
                            <div class="lp-chip">
                              <span class="lp-chip__label">В работе</span>
                            </div>
                          </div>

                          <button
                            class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled"
                            data-lp-card="button">
                            <i class="lp-icon lp-ellipsis-hr-flat"></i>
                          </button>

                          <div class="lp-card__options lp-paper lp-elevation lp-dense-3 lp-popover"
                            data-lp-card="popover" aria-hidden="true">
                            <ul class="lp-list lp-no-style" data-lp-card="options">
                              <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
                                <i class="lp-icon lp-prefix lp-edit-flat"></i>
                                <span>Завершить</span>
                              </li>
                            </ul>
                          </div>
                        </div>

                        <div class="lp-card__media">
                          <img src="/assets/images/LqKhnDzSj.png" alt="demand card" height="210" class="lp-card__img" />
                        </div>

                        <div class="lp-chip lp-primary lp-variant-filled lp-card-offer__chip">
                          <div class="lp-chip__label">Физ. лицо</div>
                        </div>
                      </header>

                      <div class="lp-card__body">
                        <div class="lp-card__content">
                          <h5>Любая правка сайта</h5>

                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__info">
                          <ul class="lp-list lp-no-style">
                            <li>
                              <span class="lp-typo lp-sub lp-grey">Формат расчёта: ETH</span>
                            </li>
                            <li>
                              <span class="lp-typo lp-sub lp-grey">Cрок выполнения: до 10 дней</span>
                            </li>
                          </ul>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__user lp-flex lp-justify-between">
                          <span class="lp-typo lp-footnote lp-flex lp-align-center">
                            <i class="lp-icon lp-star-outlined"></i>
                            <span>Artemrav</span>
                          </span>

                          <span class="lp-typo lp-sub"><b>6.50</b></span>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__skills">
                          <span class="lp-typo lp-sub lp-grey">
                            Web Design, UX/UI Design, Web developer, Front-End developer, Design,
                          </span>
                          <a href="/good.html" class="lp-link lp-theme-primary"><small>Подробнее...</small></a>
                        </div>
                      </div>

                      <div class="lp-modal" data-lp-modal aria-label="finish card in process modal"
                        data-lp-card="finish-modal" aria-hidden="true">
                        <div class="lp-modal__wrapper">
                          <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                            <header class="lp-modal__header">
                              <h5 class="lp-typo lp-h5">Завершить?</h5>
                            </header>

                            <div class="lp-modal__body">
                              <p class="lp-typo lp-body">Завершить</p>
                            </div>

                            <footer class="lp-modal__footer">
                              <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Отмена
                                  </button>
                                </div>

                                <div class="lp-grid lp-item">
                                  <button
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                    <i class="lp-icon lp-prefix lp-star-outlined"></i>
                                    Подтвердить
                                  </button>
                                </div>
                              </div>
                            </footer>

                            <div class="lp-modal__close">
                              <button
                                class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                                data-lp-modal="close">
                                <i class="lp-icon lp-times-flat"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>

                    </article>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- .card-demand -->
        <div class="lp-grid lp-item lp-xs-12">
          <div class="lp-paper lp-elevation lp-dense-6">
            <div class="lp-grid lp-container lp-spacing-3">
              <div class="lp-grid lp-item">
                <div class="lp-paper__title">
                  <span class="lp-typo lp-footnote lp-uppercase"> Карточки спроса </span>
                </div>
              </div>

              <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-grid lp-container lp-spacing-3">
                  <div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-6 lp-xs-12">
                    <article class="lp-card lp-card-demand" data-lp-card>
                      <header class="lp-card__header">
                        <div class="lp-card-offer__actions lp-flex lp-align-start lp-justify-end">
                          <button
                            class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled">
                            <i class="lp-icon lp-heart-flat"></i>
                          </button>
                        </div>

                        <div class="lp-card__media">
                          <img src="/assets/images/LqKhnDzSl.png" alt="profile offer card" height="210"
                            class="lp-card__img" />
                        </div>

                        <div class="lp-chip lp-primary lp-variant-filled lp-card-offer__chip">
                          <div class="lp-chip__label">Физ. лицо</div>
                        </div>
                      </header>

                      <div class="lp-card__body">
                        <div class="lp-card__content">
                          <h5>Любая правка сайта</h5>

                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__info">
                          <ul class="lp-list lp-no-style">
                            <li>
                              <span class="lp-typo lp-sub">Формат расчёта: ETH</span>
                            </li>
                          </ul>
                        </div>
                        <hr class="lp-divider" />

                        <div class="lp-card-offer__rate lp-flex lp-align-center lp-justify-between">
                          <span class="lp-typo lp-sub">
                            <i class="lp-icon lp-star-outlined"></i>
                            <b>4.9</b>
                          </span>

                          <span class="lp-typo lp-footnote"><b>98.08</b></span>
                        </div>
                      </div>
                    </article>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- .text-fields -->
        <div class="lp-grid lp-item lp-xs-12">
          <div class="lp-paper lp-elevation lp-dense-6">
            <div class="lp-grid lp-container lp-spacing-3">
              <div class="lp-grid lp-item">
                <div class="lp-paper__title">
                  <span class="lp-typo lp-footnote lp-uppercase">Текстовые поля</span>
                </div>
              </div>

              <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-grid lp-container lp-spacing-3">
                  <div class="lp-grid lp-item lp-lg-4 lp-xs-12">
                    <div class="lp-select" data-lp-select id="test-select">
                      <div class="lp-select__textfield lp-textfield lp-variant-flat" data-lp-select="textfield">
                        <div class="lp-textfield__input">
                          <label class="lp-textfield__label">
                            <span data-lp-select="value"></span>
                            <input type="hidden" data-lp-textfield="input" data-lp-select="input" />

                            <span>Заголовок</span>
                          </label>

                          <div class="lp-textfield__postfix">
                            <button
                              class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
                              data-lp-select="clear">
                              <i class="lp-icon lp-times-flat"></i>
                            </button>

                            <button
                              class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded"
                              data-lp-select="button">
                              <i class="lp-icon lp-angle-down-flat"></i>
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="lp-select__options lp-paper lp-elevation lp-dense-3" data-lp-select="popover"
                        aria-hidden="true">
                        <div class="lp-textfield lp-variant-outlined" data-lp-select="search">
                          <div class="lp-textfield__input">
                            <label class="lp-textfield__label">
                              <input type="text" data-lp-textfield="input" />

                              <span>Поиск</span>
                            </label>
                          </div>
                        </div>

                        <ul class="lp-list lp-no-style" data-lp-select="options">
                          <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                            data-lp-value="option 1">
                            <i class="lp-icon lp-prefix lp-star-outlined"></i>
                            <span>Lorem, ipsum dolor.</span>
                          </li>

                          <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                            data-lp-value="option 2">
                            <span>Lorem ipsum dolor sit.</span>
                            <i class="lp-icon lp-postfix lp-star-outlined"></i>
                          </li>

                          <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                            data-lp-value="option 3">
                            <i class="lp-icon lp-prefix lp-star-outlined"></i>
                            <span>Lorem, ipsum.</span>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>

                  <div class="lp-grid lp-item lp-lg-4 lp-xs-12">
                    <div class="lp-textfield lp-variant-outlined" data-lp-textfield id="test-textfield">
                      <div class="lp-textfield__input">
                        <label class="lp-textfield__label">
                          <input type="text" data-lp-textfield="input" value="test" />

                          <span>Заголовок</span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="lp-grid lp-item lp-lg-4 lp-xs-12">
                    <div class="lp-textfield lp-area lp-variant-outlined" data-lp-textfield id="test-textarea">
                      <div class="lp-textfield__input">

                        <div class="lp-textfield__prefix">
                          <button class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flatlp-rounded" aria-label="icon button" title="icon-button">
                            <i class="lp-icon lp-star-outlined"></i>
                          </button>
                        </div>


                        <label class="lp-textfield__label">
                          <textarea data-lp-textfield="input"></textarea>
                          <span>Заголовок</span>
                        </label>


                        <div class="lp-textfield__postfix">
                          <button class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded" aria-label="icon button" title="icon-button">
                            <i class="lp-icon lp-star-outlined"></i>
                          </button>

                        </div>

                      </div>


                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- .calendar -->
        <div class="lp-grid lp-item lp-xs-12">
          <div class="lp-paper lp-elevation lp-dense-6">
            <div class="lp-grid lp-container lp-spacing-3">
              <div class="lp-grid lp-item">
                <div class="lp-paper__title">
                  <span class="lp-typo lp-footnote lp-uppercase">Календарь</span>
                </div>
              </div>

              <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-calendar" data-lp-calendar id="test-calendar"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- .chips&.radio&.checkbox -->
        <div class="lp-grid lp-item lp-xs-12">
          <div class="lp-paper lp-elevation lp-dense-6">
            <div class="lp-grid lp-container lp-spacing-3">
              <div class="lp-grid lp-item">
                <div class="lp-paper__title">
                  <span class="lp-typo lp-footnote lp-uppercase">Чипсы</span>
                </div>
              </div>

              <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-grid lp-container lp-spacing-1">
                  <div class="lp-grid lp-item lp-xs-2">
                    <a class="lp-chip lp-default lp-variant-outlined" href="#">
                      <span class="lp-chip__label">Back-end</span>
                    </a>
                  </div>

                  <div class="lp-grid lp-item lp-xs-2">
                    <div class="lp-chip lp-default lp-variant-filled lp-icon">
                      <span class="lp-chip__label">Back-end</span>

                      <button class="
                                  lp-button-base
                                  lp-button-icon
                                  lp-size-small
                                  lp-theme-default
                                  lp-variant-filled
                                  lp-rounded
                                ">
                        <i class="lp-icon lp-star-outlined"></i>
                      </button>
                    </div>
                  </div>

                  <div class="lp-grid lp-item lp-xs-2">
                    <a class="lp-chip lp-primary lp-variant-outlined" href="#">
                      <span class="lp-chip__label">Back-end</span>
                    </a>
                  </div>

                  <div class="lp-grid lp-item lp-xs-2">
                    <div class="lp-chip lp-primary lp-variant-filled lp-icon">
                      <span class="lp-chip__label">Back-end</span>

                      <button class="
                                  lp-button-base
                                  lp-button-icon
                                  lp-size-small
                                  lp-theme-primary
                                  lp-variant-filled
                                  lp-rounded
                                ">
                        <i class="lp-icon lp-star-outlined"></i>
                      </button>
                    </div>
                  </div>

                  <div class="lp-grid lp-item lp-xs-2">
                    <a class="lp-chip lp-success lp-variant-filled" href="#">
                      <span class="lp-chip__label">Back-end</span>
                    </a>
                  </div>

                  <div class="lp-grid lp-item lp-xs-2">
                    <div class="lp-chip lp-success lp-variant-outlined lp-icon">
                      <span class="lp-chip__label">Back-end</span>

                      <button class="
                                  lp-button-base
                                  lp-button-icon
                                  lp-size-small
                                  lp-theme-secondary
                                  lp-variant-flat
                                  lp-rounded
                                ">
                        <i class="lp-icon lp-star-outlined"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="lp-grid lp-item">
                <div class="lp-paper__title">
                  <span class="lp-typo lp-footnote lp-uppercase">Чекбоксы и радиокнопки</span>
                </div>
              </div>

              <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-grid lp-container lp-spacing-1">
                  <div class="lp-grid lp-item lp-xs-1">
                    <div class="lp-checkbox">
                      <label class="lp-checkbox__label">
                        <input type="checkbox" />
                        <span class="lp-checkbox__checkmark"></span>
                      </label>
                    </div>
                  </div>

                  <div class="lp-grid lp-item lp-xs-1">
                    <div class="lp-radio">
                      <label class="lp-radio__label">
                        <input type="radio" />
                        <span class="lp-radio__checkmark"></span>
                      </label>
                    </div>
                  </div>

                  <div class="lp-grid lp-item lp-xs-1">
                    <div class="lp-radio lp-checkmark">
                      <label class="lp-radio__label">
                        <input type="radio" />
                        <span class="lp-radio__checkmark"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- .file-upload -->
        <div class="lp-grid lp-item lp-xs-12">
          <div class="lp-paper lp-elevation lp-dense-6">
            <div class="lp-grid lp-container lp-spacing-3">
              <div class="lp-grid lp-item">
                <div class="lp-paper__title">
                  <span class="lp-typo lp-footnote lp-uppercase"> Загрузка изображения или видео </span>
                </div>
              </div>

              <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-file-upload" id="test-file-ipload">
                  <i class="lp-icon lp-cloud-upload-flat"></i>

                  <label class="lp-file-upload__label">
                    <span class="lp-typo lp-body lp-grey">Перетащите или</span>

                    <span class="lp-file-upload__input">
                      <input type="file" name="file" id="file" tabindex="-1" />
                      <span role="button" tabindex="0">выберите файл</span>
                    </span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- .table -->
        <div class="lp-grid lp-item lp-xs-12">
          <div class="lp-data-grid lp-paper lp-elevation lp-dense-6">
            <div class="lp-grid lp-container lp-spacing-3">
              <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-data-grid__table lp-table">
                  <table>
                    <thead>
                      <tr>
                        <th class="lp-cell-inline">
                          <div class="lp-checkbox">
                            <label class="lp-checkbox__label">
                              <input type="checkbox" />
                              <span class="lp-checkbox__checkmark"></span>
                            </label>
                          </div>
                        </th>

                        <th class="lp-align-left">
                          <div class="lp-flex lp-align-center">
                            <span class="lp-typo lp-sub lp-grey">Дата</span>
                            <button
                              class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded">
                              <i class="lp-icon lp-triangle-filter"></i>
                            </button>
                          </div>
                        </th>

                        <th class="lp-align-left">
                          <div class="lp-flex lp-align-center">
                            <span class="lp-typo lp-sub lp-grey">Тема</span>
                            <button
                              class="lp-button-base lp-button-icon lp-size-small lp-theme-primary lp-variant-flat lp-rounded">
                              <i class="lp-icon lp-triangle-up"></i>
                            </button>
                          </div>
                        </th>

                        <th class="lp-align-left">
                          <div class="lp-flex lp-align-center">
                            <span class="lp-typo lp-sub lp-grey">Статус</span>
                            <button
                              class="lp-button-base lp-button-icon lp-size-small lp-theme-primary lp-variant-flat lp-rounded">
                              <i class="lp-icon lp-triangle-down"></i>
                            </button>
                          </div>
                        </th>

                        <th class="lp-align-right">
                          <span class="lp-typo lp-sub lp-grey">Действия</span>
                        </th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr>
                        <td class="lp-cell-inline">
                          <div class="lp-checkbox">
                            <label class="lp-checkbox__label">
                              <input type="checkbox" />
                              <span class="lp-checkbox__checkmark"></span>
                            </label>
                          </div>
                        </td>

                        <td class="lp-align-left">
                          <span class="lp-typo lp-sub lp-grey">30 июня, 20:30</span>
                        </td>

                        <td class="lp-align-left">
                          <span class="lp-typo lp-sub">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam, voluptatum suscipit
                            non dolorum
                            explicabo quibusdam harum magni accusamus et culpa rerum molestias at labore totam
                            tenetur doloremque
                            quis perferendis autem?
                          </span>
                        </td>

                        <td class="lp-align-left">
                          <span class="lp-typo lp-sub lp-grey"> На рассмотрении </span>
                        </td>

                        <td class="lp-align-right">
                          <button
                            class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded">
                            <i class="lp-icon lp-ellipsis-vt-flat"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="lp-snackbar">
  <ul class="lp-list lp-no-style lp-flex lp-align-start lp-direction-column"></ul>
</div>

<? get_footer(); ?>