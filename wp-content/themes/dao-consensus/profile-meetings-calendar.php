<?php /* Template Name: Profile Meetings Calendar */

get_header();
?>

<div class="lp-page lp-page-meetings">
    <div class="lp-wrapper">
        <div class="lp-grid lp-container lp-spacing-3">
            <div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-sm-12">
                <? get_template_part( 'template-parts/profile', 'sidebar' ); ?>
            </div>

            <div class="lp-grid lp-item lp-lg-9 lp-md-8 lp-sm-12">
                <div class="lp-grid lp-item lp-xs-12">
                    <div class="lp-paper lp-elevation lp-dense-6">
                        <div class="lp-grid lp-item lp-xs-12">
                            <a href="<?php echo get_permalink(120); ?>"
                                class="lp-button-base lp-theme-primary lp-variant-flat lp-size-small lp-button">
                                <i class="lp-icon lp-angle-left-flat lp-prefix"></i>
                                <b>Назад</b>
                            </a>
                        </div>
                        <div class="lp-calendar" data-lp-calendar>
                            <header class="lp-calendar__header lp-flex lp-align-center lp-justify-between">
                                <div class="lp-calendar__date">
                                    <h4></h4>
                                </div>

                                <div class="lp-calendar__actions">
                                    <button
                                        class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled"
                                        aria-label="Переключить на предыдущий месяц">
                                        <i class="lp-icon lp-angle-left-flat"></i>
                                    </button>

                                    <button
                                        class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled"
                                        aria-label="Переключить на следующий месяц">
                                        <i class="lp-icon lp-angle-right-flat"></i>
                                    </button>
                                </div>
                            </header>

                            <div class="lp-calendar__body">
                                <div class="lp-calendar__week">
                                    <div class="lp-calendar__week-day">Пн</div>
                                    <div class="lp-calendar__week-day">Вт</div>
                                    <div class="lp-calendar__week-day">Ср</div>
                                    <div class="lp-calendar__week-day">Чт</div>
                                    <div class="lp-calendar__week-day">Пт</div>
                                    <div class="lp-calendar__week-day">Сб</div>
                                    <div class="lp-calendar__week-day">Вс</div>
                                </div>

                                <div class="lp-calendar__days">
                                    <div class="lp-calendar__day">
                                        <button
                                            class="lp-button-base lp-button-calendar lp-theme-secondary lp-variant-filled">
                                            <span data-lp-calendar="day">1</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lp-modal" data-lp-modal="view-meeting" aria-label="view meeting" aria-hidden="true">
            <div class="lp-modal__wrapper">
                <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                    <header class="lp-modal__header">
                        <h5 class="title lp-typo lp-h5">Заголовок встречи</h5>
                    </header>

                    <div class="lp-modal__body">
                        <div class="lp-grid lp-container lp-spacing-4">
                            <div class="lp-grid lp-item lp-xs-12">
                                <div class="lp-flex lp-align-center lp-justify-between">
                                    <h4 class="time lp-typo lp-h1">20:30</h4>

                                    <span class="format lp-typo lp-body lp-grey"> Онлайн встреча </span>
                                </div>

                                <div class="lp-flex lp-align-center lp-justify-between">
                                    <h5 class="date lp-typo lp-h5">30 июня</h5>

                                    <span class="status lp-typo lp-body lp-grey"> Статус встречи </span>
                                </div>
                            </div>

                            <div class="lp-grid lp-item lp-xs-12">
                                <div class="invited lp-textfield lp-variant-outlined" data-lp-textfield>
                                    <div class="lp-textfield__input">
                                        <label class="lp-textfield__label">
                                            <input name="invited_user" type="text" data-lp-textfield="input" readonly />
                                            <span>Пользователь</span>
                                        </label>
                                    </div>

                                    <!-- <div class="lp-textfield__helpers">
                                        <span>with helpers</span>
                                    </div> -->
                                </div>
                            </div>

                            <div class="lp-grid lp-item lp-xs-12">
                                <div class="venue lp-textfield lp-variant-outlined" data-lp-textfield>
                                    <div class="lp-textfield__input">
                                        <label class="lp-textfield__label">
                                            <input name="venue" type="text" data-lp-textfield="input" readonly />
                                            <span>Место встречи</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="lp-grid lp-item lp-xs-12">
                                <div class="description lp-textfield lp-variant-outlined lp-area" data-lp-textfield>
                                    <div class="lp-textfield__input">
                                        <label class="lp-textfield__label">
                                            <textarea name="description" data-lp-textfield="input" readonly></textarea>
                                            <span>Краткое описание</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <footer class="lp-modal__footer">
                        <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
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

        <div class="lp-modal" data-lp-modal="cancel-meeting" aria-label="cancel meeting confirmation" data-lp-card="cancel-meeting-modal" aria-hidden="true">
            <div class="lp-modal__wrapper">
                <form class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                    <header class="lp-modal__header">
                        <h5 class="lp-typo lp-h5">Отмена встречи</h5>
                    </header>

                    <div class="lp-modal__body">
                        <p class="lp-typo lp-body">Вы действительно хотите отменить встречу <span class="title"></span> <span class="datetime"></span>?</p>
                    </div>

                    <footer class="lp-modal__footer">
                        <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                            <div class="lp-grid lp-item">
                                <button type="button" class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    Отмена
                                </button>
                            </div>

                            <div class="lp-grid lp-item lp-flex lp-align-center">
                                <button type="submit"
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                    Подтвердить
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
                    </footer>

                    <div class="lp-modal__close">
                        <button type="button"
                            class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                            data-lp-modal="close" data-lp-close>
                            <i class="lp-icon lp-times-flat"></i>
                        </button>
                    </div>

                    <input type="hidden" name="action" value="dao-cancel-meeting">
                    <input type="hidden" name="meeting_id">
                    <input type="hidden" name="_wpnonce" value="<?= wp_create_nonce('dao-consensus-cancel-meeting'); ?>">
                </form>
            </div>
        </div>

        <div class="lp-modal" data-lp-modal="reschedule-meeting" aria-label="set new datetime for meeting" data-lp-card="reschedule-meeting-modal" aria-hidden="true">
            <div class="lp-modal__wrapper">
                <form class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                    <header class="lp-modal__header">
                        <h5 class="lp-typo lp-h5">Перенос встречи</h5>
                    </header>

                    <div class="lp-modal__body">
                        <h5 class="lp-typo lp-h5">Встреча "<span class="title"></span>" <span class="datetime"></span></h5>
                        <br>
                        <div id="datetime" class="lp-textfield lp-variant-outlined" data-lp-textfield>
                            <div class="lp-textfield__input">
                                <label class="lp-textfield__label">
                                    <input name="datetime" type="text" data-lp-textfield="input" />
                                    <span>Дата и время встречи</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <footer class="lp-modal__footer">
                        <div class="lp-grid lp-container lp-spacing-1 lp-flex lp-justify-center">
                            <div class="lp-grid lp-item">
                                <button type="button" class="lp-button-base lp-button lp-size-small lp-theme-default lp-variant-flat"
                                    data-lp-modal="cancel">
                                    Отмена
                                </button>
                            </div>

                            <div class="lp-grid lp-item lp-flex lp-align-center">
                                <button type="submit" class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled"
                                data-lp-modal="confirm">
                                    Подтвердить
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
                    </footer>

                    <div class="lp-modal__close">
                        <button type="button"
                            class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                            data-lp-modal="close" data-lp-close>
                            <i class="lp-icon lp-times-flat"></i>
                        </button>
                    </div>

                    <input type="hidden" name="action" value="dao-reschedule-meeting">
                    <input type="hidden" name="meeting_id">
                    <input type="hidden" name="_wpnonce" value="<?= wp_create_nonce('dao-consensus-reschedule-meeting') ?>">
                </form>
            </div>
        </div>

        <div class="lp-modal" data-lp-modal aria-label="date-picker" id="date-picker" aria-hidden="true">
            <div class="lp-modal__wrapper">
                <div class="lp-paper lp-elevation lp-dense-6 lp-modal__inner" data-lp-modal="content">
                    <div class="lp-modal__body">
                        <div class="lp-grid lp-container lp-justify-center">
                            <div class="lp-grid lp-item">
                                <div class="lp-date-picker" id="datepicker"></div>
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
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled" data-lp-modal="close">Принять</button>
                            </div>
                        </div>
                    </footer>

                    <div class="lp-modal__close">
                        <button
                            class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                            data-lp-close>
                            <i class="lp-icon lp-times-flat"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<? get_footer(); ?>
