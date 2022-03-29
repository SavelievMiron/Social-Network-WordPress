<?php /* Template Name: Profile Change Userdata */ ?>

<? get_header(); ?>

<div class="lp-page lp-page-edit-user">
    <div class="lp-wrapper">
        <div class="lp-grid lp-container lp-spacing-3">
            <div class="lp-grid lp-item lp-lg-3 lp-md-4 lp-xs-12">
                <? get_template_part('template-parts/profile', 'sidebar'); ?>
            </div>

            <?
                $curr_user = wp_get_current_user();
            ?>

            <div class="lp-grid lp-item lp-lg-9 lp-md-8 lp-xs-12">
                <form class="change-userdata-form lp-grid lp-container lp-spacing-3">
                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-paper lp-elevation lp-dense-6">
                            <div class="lp-grid lp-container lp-spacing-3">
                                <div class="lp-grid lp-item lp-xs-12">
                                    <div class="lp-paper__title">
                                        <span class="lp-typo lp-sub lp-grey lp-uppercase">Личные данные</span>
                                    </div>
                                </div>

                                <div class="lp-grid lp-item lp-lg-2 lp-xs-12">
                                    <div class="lp-grid lp-container lp-spacing-2">
                                        <div class="lp-grid lp-item lp-xs-12 lp-flex lp-justify-center">
                                            <div class="lp-avatar lp-profile-picture">
                                                <img src="<?= get_avatar_url( $curr_user->ID, ["size" => 150] ); ?>"
                                                    alt="profile avatar" />
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-xs-12 lp-flex lp-flex-column lp-justify-center">
                                            <label for="profile_picture"
                                                class="lp-upload-profile-picture lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-flat">
                                                <i class="lp-icon lp-prefix lp-cloud-upload-flat"></i>
                                                <span class="lp-file-upload__input">
                                                    <input id="profile_picture" type="file" name="profile_picture"
                                                        accept=".jpg,.jpeg,.png" tabindex="-1" />
                                                    <span role="button" tabindex="0">Заменить</span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="lp-grid lp-item lp-lg-10">
                                    <div class="lp-grid lp-container lp-spacing-3">
                                        <div class="lp-grid lp-item lp-lg-6 lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                        <input type="text" name="first_name"
                                                            value="<?= $curr_user->first_name; ?>"
                                                            data-lp-textfield="input" />
                                                        <span>Имя</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-lg-6 lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                        <input type="text" name="last_name"
                                                            value="<?= $curr_user->last_name; ?>"
                                                            data-lp-textfield="input" />
                                                        <span>Фамилия</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-lg-6 lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                        <input type="text" name="login"
                                                            value="<?= $curr_user->user_login; ?>"
                                                            data-lp-textfield="input" readonly disabled />
                                                        <span>Логин</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-lg-6 lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                        <input type="text" name="email"
                                                            value="<?= $curr_user->user_email; ?>"
                                                            data-lp-textfield="input" />
                                                        <span>Email</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                        <input type="text" name="specialization"
                                                            value="<?= get_user_meta( $curr_user->ID, 'specialization', true ); ?>"
                                                            data-lp-textfield="input" />
                                                        <span>Специализация</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                        <textarea name="about"
                                                            value="<?= esc_attr( get_user_meta($curr_user->ID, 'description', true) ); ?>"
                                                            data-lp-textfield="input"><?= get_user_meta($curr_user->ID, 'description', true); ?></textarea>
                                                        <span>О себе</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-xs-12">
                                            <div class="lp-grid lp-container lp-spacing-2 lp-align-center">
                                                <div class="lp-grid lp-item lp-lg-6 lp-xs-12">
                                                    <span class="lp-typo lp-body lp-grey">Общий статус</span>
                                                </div>
                                                <?
                                                    $person_type = get_user_meta( $curr_user->ID, 'person_type', true );
                                                ?>
                                                <div class="lp-grid lp-item lp-lg-6 lp-xs-12">
                                                    <div class="lp-grid lp-container lp-spacing-1">
                                                        <div class="lp-grid lp-item lp-xs-5">
                                                            <div class="lp-radio">
                                                                <label class="lp-radio__label">
                                                                    <input type="radio" name="user_type" <? checked(
                                                                        $person_type, 'physical' ); ?>
                                                                    value="physical" />
                                                                    <span class="lp-radio__checkmark"></span>
                                                                    <span>Физ. лицо</span>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="lp-grid lp-item lp-xs-5">
                                                            <div class="lp-radio">
                                                                <label class="lp-radio__label">
                                                                    <input type="radio" name="user_type" <? checked(
                                                                        $person_type, 'juridical' ); ?>
                                                                    value="juridical" />
                                                                    <span class="lp-radio__checkmark"></span>
                                                                    <span>Юр. лицо</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-xs-12">
                                            <div class="lp-grid lp-container lp-spacing-2 lp-align-center">
                                                <div class="lp-grid lp-item lp-lg-6 lp-xs-4">
                                                    <span class="lp-typo lp-body lp-grey">Пароль</span>
                                                </div>

                                                <div class="lp-grid lp-item lp-lg-6 lp-xs-8">
                                                    <button type="button"
                                                        class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-outlined"
                                                        id="change-password">
                                                        Сменить пароль
                                                    </button>
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
                                        <span class="lp-typo lp-footnote lp-grey lp-uppercase">Сведения о навыках</span>
                                    </div>
                                </div>

                                <div class="lp-grid lp-item lp-xs-12">
                                    <div class="lp-grid lp-container lp-spacing-4">
                                        <div class="lp-grid lp-item lp-xs-12">
                                            <select class="skills select2" name="skills[]" multiple>
                                                <?
                                                    $user_skills = get_user_meta( $curr_user->ID, 'skills', true );
                                                    $skills = array_map( 'trim', explode(', ', cmb2_get_option('daoc_others', 'all_skills')) );

                                                    foreach( $skills as $v ):
                                                ?>
                                                <option value="<?= $v; ?>"
                                                    <?= ( in_array($v, $user_skills) ) ? 'selected' : ''; ?>><?= $v; ?>
                                                </option>
                                                <?  endforeach; ?>
                                            </select>
                                            <p class="lp-typo lp-footnote lp-grey available-skills">Доступно для
                                                добавления <span id="counter">20</span> навыков</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="lp-grid lp-item lp-xs-12">
                                    <div class="lp-grid lp-container lp-spacing-2">
                                        <div class="lp-grid lp-item">
                                            <span class="lp-typo lp-body">Рекомендуемые вам навыки:</span>
                                        </div>

                                        <div class="lp-grid lp-item lp-xs-12">
                                            <div class="lp-skills__chips lp-flex lp-wrap">
                                                <div class="lp-chip lp-default lp-variant-outlined lp-icon">
                                                    <span class="lp-chip__label">HTML</span>

                                                    <button class="
                                      lp-button-base
                                      lp-button-icon
                                      lp-size-small
                                      lp-theme-default
                                      lp-variant-flat
                                      lp-rounded
                                    ">
                                                        <i class="lp-icon lp-times-flat"></i>
                                                    </button>
                                                </div>

                                                <div class="lp-chip lp-default lp-variant-outlined lp-icon">
                                                    <span class="lp-chip__label">CSS</span>

                                                    <button class="
                                      lp-button-base
                                      lp-button-icon
                                      lp-size-small
                                      lp-theme-default
                                      lp-variant-flat
                                      lp-rounded
                                    ">
                                                        <i class="lp-icon lp-times-flat"></i>
                                                    </button>
                                                </div>

                                                <div class="lp-chip lp-default lp-variant-outlined lp-icon">
                                                    <span class="lp-chip__label">PHP</span>

                                                    <button class="
                                      lp-button-base
                                      lp-button-icon
                                      lp-size-small
                                      lp-theme-default
                                      lp-variant-flat
                                      lp-rounded
                                    ">
                                                        <i class="lp-icon lp-times-flat"></i>
                                                    </button>
                                                </div>

                                                <div class="lp-chip lp-default lp-variant-outlined lp-icon">
                                                    <span class="lp-chip__label">JS</span>

                                                    <button class="
                                      lp-button-base
                                      lp-button-icon
                                      lp-size-small
                                      lp-theme-default
                                      lp-variant-flat
                                      lp-rounded
                                    ">
                                                        <i class="lp-icon lp-times-flat"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <div
                        class="company-info lp-grid lp-item lp-xs-12 <?= ($person_type !== 'juridical') ? 'lp-hide' : ''; ?>">
                        <div class="lp-paper lp-elevation lp-dense-6">
                            <div class="lp-grid lp-container lp-spacing-3">
                                <div class="lp-grid lp-item lp-xs-12 lp-flex lp-justify-between">
                                    <div class="lp-paper__title">
                                        <span class="lp-typo lp-footnote lp-grey lp-uppercase">Компания
                                            (опционально)</span>
                                    </div>

                                    <a href="#" class="lp-typo lp-footnote lp-primary">Подключить бота ассистента</a>
                                </div>

                                <div class="lp-grid lp-item lp-xs-12">
                                    <div class="lp-grid lp-container lp-spacing-2">
                                        <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <div class="lp-textfield__prefix">
                                                        <button type="button" class="
                                        lp-button-base
                                        lp-button-icon
                                        lp-variant-flat
                                        lp-theme-secondary
                                        lp-size-medium
                                        lp-rounded
                                      ">
                                                            <i class="lp-icon lp-search-flat"></i>
                                                        </button>
                                                    </div>

                                                    <label class="lp-textfield__label">
                                                        <input type="text" name="company_name"
                                                            value="<?= get_user_meta( $curr_user->ID, 'company_name', true ); ?>"
                                                            data-lp-textfield="input" />

                                                        <span>Введите название компании</span>
                                                    </label>

                                                    <div class="lp-textfield__postfix">
                                                        <button type="button" class="
                                        lp-button-base
                                        lp-button-icon
                                        lp-variant-flat
                                        lp-theme-secondary
                                        lp-size-medium
                                        lp-rounded
                                      ">
                                                            <i class="lp-icon lp-check-polygon-flat"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                                            <div class="lp-select" data-lp-select>
                                                <div class="lp-select__textfield lp-textfield lp-variant-outlined"
                                                    data-lp-select="textfield">
                                                    <div class="lp-textfield__input">
                                                        <label class="lp-textfield__label">
                                                            <span data-lp-select="value"></span>
                                                            <input type="hidden" name="company_workarea"
                                                                data-lp-textfield="input" data-lp-select="input" />

                                                            <span>Категория деятельности</span>
                                                        </label>

                                                        <div class="lp-textfield__postfix">
                                                            <button type="button" class="
                                        lp-button-base
                                        lp-button-icon
                                        lp-size-small
                                        lp-theme-default
                                        lp-variant-flat
                                        lp-rounded
                                      " data-lp-select="clear">
                                                                <i class="lp-icon lp-times-flat"></i>
                                                            </button>

                                                            <button type="button" class="
                                        lp-button-base
                                        lp-button-icon
                                        lp-size-small
                                        lp-theme-default
                                        lp-variant-flat
                                        lp-rounded
                                      " data-lp-select="button">
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
                                                        <?
                                                            $categories = get_terms( array(
                                                                'taxonomy' => 'categories',
                                                                'hide_empty' => false
                                                            ));

                                                            $user_cat = get_user_meta( $curr_user->ID, 'company_workarea', true );

                                                            foreach( $categories as $cat ):
                                                        ?>
                                                        <li class="lp-button-base lp-button-option lp-theme-secondary lp-variant-flat"
                                                            data-lp-value="<?= $cat->slug; ?>"
                                                            <?= ( $user_cat === $cat->slug ) ? 'data-lp-selected="true"' : ''; ?>>
                                                            <span><?= $cat->name; ?></span>
                                                        </li>
                                                        <?  endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">

                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                        <input type="text" name="company_juraddress"
                                                            value="<?= get_user_meta( $curr_user->ID, 'company_juraddress', true ); ?>"
                                                            data-lp-textfield="input" />

                                                        <span>Юридический адрес</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                        <input type="text" name="company_phone"
                                                            value="<?= get_user_meta( $curr_user->ID, 'company_phone', true ); ?>"
                                                            data-lp-textfield="input" />

                                                        <span>Номер телефона</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                        <input type="email" name="company_email"
                                                            value="<?= get_user_meta( $curr_user->ID, 'company_email', true ); ?>"
                                                            data-lp-textfield="input" />

                                                        <span>Email</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <label class="lp-textfield__label">
                                                        <input type="url" name="company_website"
                                                            value="<?= get_user_meta( $curr_user->ID, 'company_website', true ); ?>"
                                                            data-lp-textfield="input" />

                                                        <span>Сайт компании</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-paper lp-elevation lp-dense-6">
                            <div class="lp-grid lp-container lp-spacing-2">
                                <div class="lp-grid lp-item">
                                    <div class="lp-paper__title">
                                        <span class="lp-typo lp-footnote lp-grey lp-uppercase">Cоциальные сети</span>
                                    </div>
                                </div>

                                <div class="lp-grid lp-item lp-xs-12">
                                    <div class="lp-grid lp-container lp-spacing-2">
                                        <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <div class="lp-textfield__prefix">
                                                        <button type="button" class="
                                        lp-button-base
                                        lp-button-icon
                                        lp-variant-flat
                                        lp-theme-secondary
                                        lp-size-medium
                                        lp-rounded
                                      ">
                                                            <i class="lp-icon lp-twitter-filled"></i>
                                                        </button>
                                                    </div>

                                                    <label class="lp-textfield__label">
                                                        <input type="text" name="twitter"
                                                            value="<?= get_user_meta( $curr_user->ID, 'twitter', true ); ?>"
                                                            data-lp-textfield="input" />

                                                        <span>Введите ссылку</span>
                                                    </label>

                                                    <div class="lp-textfield__postfix">
                                                        <button type="button" class="
                                        lp-button-base
                                        lp-button-icon
                                        lp-variant-flat
                                        lp-theme-secondary
                                        lp-size-medium
                                        lp-rounded
                                      ">
                                                            <i class="lp-icon lp-check-polygon-flat"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-lg-6 lp-md-6 lp-xs-12">
                                            <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                                                <div class="lp-textfield__input">
                                                    <div class="lp-textfield__prefix">
                                                        <button type="button" class="
                                        lp-button-base
                                        lp-button-icon
                                        lp-variant-flat
                                        lp-theme-secondary
                                        lp-size-medium
                                        lp-rounded
                                      ">
                                                            <i class="lp-icon lp-instagram-outlined"></i>
                                                        </button>
                                                    </div>

                                                    <label class="lp-textfield__label">
                                                        <input type="text" name="instagram"
                                                            value="<?= get_user_meta( $curr_user->ID, 'instagram', true ); ?>"
                                                            data-lp-textfield="input" />

                                                        <span>Введите ссылку</span>
                                                    </label>

                                                    <div class="lp-textfield__postfix">
                                                        <button type="button" class="
                                        lp-button-base
                                        lp-button-icon
                                        lp-variant-flat
                                        lp-theme-secondary
                                        lp-size-medium
                                        lp-rounded
                                      ">
                                                            <i class="lp-icon lp-times-flat"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-flex lp-xs-12">
                                            <div class="lp-grid lp-container lp-align-center lp-spacing-1">
                                                <div class="lp-grid lp-item">
                                                    <span class="lp-typo lp-h4 lp-inline-flex">
                                                        <i class="lp-icon lp-youtube-filled"></i>
                                                    </span>
                                                </div>

                                                <div class="lp-grid lp-item">
                                                    <span class="lp-item lp-typo lp-typo-body">YouTube</span>
                                                </div>
                                            </div>

                                            <div class="lp-grid lp-item">
                                                <button type="button"
                                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                                    Привязать
                                                </button>
                                            </div>
                                        </div>

                                        <div class="lp-grid lp-item lp-flex lp-xs-12">
                                            <div class="lp-grid lp-container lp-align-center lp-spacing-1">
                                                <div class="lp-grid lp-item">
                                                    <span class="lp-typo lp-h4 lp-inline-flex">
                                                        <i class="lp-icon lp-facebook-outlined"></i>
                                                    </span>
                                                </div>

                                                <div class="lp-grid lp-item">
                                                    <span class="lp-item lp-typo lp-typo-body">Facebook</span>
                                                </div>
                                            </div>

                                            <div class="lp-grid lp-item">
                                                <button type="button"
                                                    class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">
                                                    Привязать
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-grid lp-container lp-spacing-3">
                            <div class="lp-grid lp-item lp-xs-12">
                                <div class="lp-grid lp-container lp-spacing-2">
                                    <!-- <div class="lp-grid lp-item lp-xs-6 lp-flex lp-justify-end">
                                        <button type="button"
                                            class="lp-button-base lp-button lp-size-large lp-theme-default lp-variant-flat">
                                            Отмена
                                        </button>
                                    </div> -->
                                    <div class="lp-grid lp-item lp-xs-12 lp-flex lp-justify-center lp-align-center">
                                        <button type="submit"
                                            class="lp-button-base lp-button lp-size-large lp-theme-primary lp-variant-filled">
                                            Сохранить
                                        </button>
                                        <span class="lp-loader lp-hide">
                                            <svg class="lp-loader__circle" width="40" height="40" viewBox="0 0 48 48"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <circle class="lp-loader__path" fill="none" stroke-width="5"
                                                    stroke-linecap="round" cx="24" cy="24" r="20" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="action" value="dao-change-userdata">
                    <input type="hidden" name="user_id" value="<?= $curr_user->ID; ?>">
                    <input type="hidden" name="_wpnonce"
                        value="<?= wp_create_nonce('dao-consensus-change-userdata') ?>">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="lp-modal" data-lp-modal aria-label="change password" aria-hidden="true" id="change-password">
    <div class="lp-modal__wrapper">
        <form class="change-password-form lp-paper lp-elevation lp-dense-6 lp-modal__inner lp-full"
            data-lp-modal="content">
            <header class="lp-modal__header">
                <h5 class="lp-typo lp-h5">Смена пароля</h5>
            </header>

            <div class="lp-modal__body">
                <div class="lp-grid lp-container lp-spacing-4">
                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                            <div class="lp-textfield__input">
                                <label class="lp-textfield__label">
                                    <input type="password" name="old_password" data-lp-textfield="input" />

                                    <span>Старый пароль</span>
                                </label>

                                <div class="lp-textfield__postfix">
                                    <button type="button"
                                        class="show-pass lp-button-base lp-button-icon lp-theme-secondary lp-variant-flat lp-size-medium">
                                        <i class="lp-icon lp-eye-flat"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                            <div class="lp-textfield__input">
                                <label class="lp-textfield__label">
                                    <input type="password" name="new_password" data-lp-textfield="input" />
                                    <span>Новый пароль</span>
                                </label>

                                <div class="lp-textfield__postfix">
                                    <button type="button"
                                        class="show-pass lp-button-base lp-button-icon lp-theme-secondary lp-variant-flat lp-size-medium">
                                        <i class="lp-icon lp-eye-flat"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lp-grid lp-item lp-xs-12">
                        <div class="lp-textfield lp-variant-outlined" data-lp-textfield>
                            <div class="lp-textfield__input">
                                <label class="lp-textfield__label">
                                    <input type="password" name="repeat_new_password" data-lp-textfield="input" />
                                    <span>Повторите новый пароль</span>
                                </label>

                                <div class="lp-textfield__postfix">
                                    <button type="button"
                                        class="show-pass lp-button-base lp-button-icon lp-theme-secondary lp-variant-flat lp-size-medium">
                                        <i class="lp-icon lp-eye-flat"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="lp-modal__footer">
                <div class="lp-flex lp-justify-center lp-align-center">
                    <button type="submit"
                        class="lp-button-base lp-button lp-size-small lp-full lp-theme-primary lp-variant-filled">
                        Сменить пароль
                    </button>
                    <span class="lp-loader lp-hide">
                        <svg class="lp-loader__circle" width="40" height="40" viewBox="0 0 48 48"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round" cx="24"
                                cy="24" r="20" />
                        </svg>
                    </span>
                </div>
            </footer>

            <div class="lp-modal__close">
                <button type="button"
                    class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded"
                    data-lp-modal="close">
                    <i class="lp-icon lp-times-flat"></i>
                </button>
            </div>

            <input type="hidden" name="action" value="dao-change-userpassword">
            <input type="hidden" name="user_id" value="<?= $curr_user->ID; ?>">
            <? wp_nonce_field( 'dao-consensus-change-userpassword', '_wpnonce', false ); ?>
        </form>
    </div>
</div>

<? get_footer(); ?>