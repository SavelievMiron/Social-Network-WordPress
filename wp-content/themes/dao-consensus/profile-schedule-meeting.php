<?php /* Template Name: Profile Schedule Meeting */  ?>

<? get_header(); ?>

<?
    $invitor = wp_get_current_user();
    $invited = get_user_by( 'ID', $_GET['user_id'] );

    $card = get_post( $_GET['card_id'] );
?>

<div class="lp-page lp-page-create-meeting">
    <div class="lp-wrapper">
        <form class="lp-grid lp-container lp-spacing-5">
            <div class="lp-grid lp-item lp-flex lp-align-center lp-justify-center lp-xs-12">
                <div style="position: absolute; left: 0">
                    <a href="<?php echo get_permalink( 190 ); ?>"
                        class="lp-button-base lp-theme-primary lp-variant-flat lp-size-small lp-button">
                        <i class="lp-icon lp-angle-left-flat lp-prefix"></i>
                        <b>Назад</b>
                    </a>
                </div>

                <h4 class="lp-typo lp-h4">Запланировать встречу с <?= $invited->display_name; ?></h4>
            </div>

            <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-paper lp-elevation lp-dense-6">
                    <div class="lp-grid lp-container lp-spacing-3">
                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                <div class="lp-textfield__input">
                                    <label class="lp-textfield__label">
                                        <input type="text" value="<?= $card->post_title; ?>" data-lp-textfield="input" disabled/>
                                        <span><?= ( $card->post_type === 'offers' ) ? __('Предложение', 'dao-consensus') : __('Спрос', 'dao-consensus'); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                <div class="lp-textfield__input">
                                    <label class="lp-textfield__label">
                                        <input name="invitor" type="text" value="<?= $invitor->first_name; ?>" data-lp-textfield="input" />
                                        <span>Ваше Имя</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                <div class="lp-textfield__input">
                                    <label class="lp-textfield__label">
                                        <input name="invited" type="text" value="<?= $invited->first_name; ?>" data-lp-textfield="input" />
                                        <span>Имя пользователя с которым будет проходить встреча</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                <div class="lp-textfield__input">
                                    <label class="lp-textfield__label">
                                        <input name="title" type="text" data-lp-textfield="input" />
                                        <span>Заголовок</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                            <div id="datetime" class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                <div class="lp-textfield__input">
                                    <label class="lp-textfield__label">
                                        <input name="datetime" type="text" data-lp-textfield="input" />
                                        <span>Дата и время встречи</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-grid lp-container lp-spacing-2 lp-align-center">
                                <div class="lp-grid lp-item lp-lg-6 lp-xs-12">
                                    <span class="lp-typo lp-body">Формат встречи</span>
                                </div>

                                <div class="lp-grid lp-item lp-lg-6 lp-xs-12">
                                    <div class="radio-group lp-grid lp-container lp-spacing-1">
                                        <div class="lp-grid lp-item lp-xs-5">
                                            <div class="lp-radio">
                                                <label class="lp-radio__label">
                                                    <input name="format" type="radio" name="meeting-format"
                                                        value="online" />
                                                    <span class="lp-radio__checkmark"></span>
                                                    <span>Онлайн</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-xs-5">
                                            <div class="lp-radio">
                                                <label class="lp-radio__label">
                                                    <input name="format" type="radio" name="meeting-format"
                                                        value="offline" />
                                                    <span class="lp-radio__checkmark"></span>
                                                    <span>Оффлайн</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                <div class="lp-textfield__input">
                                    <label class="lp-textfield__label">
                                        <input name="venue" type="text" data-lp-textfield="input" />
                                        <span>Место встречи</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="lp-grid lp-item lp-xs-12">
                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                <div class="lp-textfield__input">
                                    <label class="lp-textfield__label">
                                        <textarea name="description" data-lp-textfield="input"></textarea>
                                        <span>Краткое описание встречи</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lp-grid lp-item lp-xs-12">
                <div class="lp-grid lp-container lp-spacing-2">
                    <div class="lp-grid lp-item lp-xs-6 lp-flex lp-justify-end">
                        <button type="button"
                            class="lp-button-base lp-theme-default lp-variant-flat lp-size-large lp-button">
                            Отмена
                        </button>
                    </div>

                    <div class="lp-grid lp-item lp-xs-6 lp-flex lp-align-center">
                        <button type="submit"
                            class="lp-button-base lp-theme-primary lp-variant-filled lp-size-large lp-button">
                            Отправить
                        </button>
                        <span class="lp-loader lp-hide">
                            <svg class="lp-loader__circle" width="40" height="40" viewBox="0 0 48 48"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round" cx="24"
                                    cy="24" r="20" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <input type="hidden" name="action" value="dao-schedule-meeting">
            <input type="hidden" name="card_id" value="<?= $_GET['card_id']; ?>">
            <input type="hidden" name="invited_id" value="<?= $invited->ID; ?>">
            <input type="hidden" name="_wpnonce" value="<?= wp_create_nonce('dao-consensus-schedule-meeting'); ?>">
        </form>
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
                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled" data-lp-modal="accept">Принять</button>
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
</div>

<? get_footer(); ?>