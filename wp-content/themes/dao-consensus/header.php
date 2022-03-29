<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package DAO_Consensus
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <div id="application">
    <div class="lp-page-wrapper">
      <header class="lp-header">
        <div class="lp-wrapper lp-flex lp-align-center">
          <a href="<?php echo home_url(); ?>" class="lp-inline-flex" title="home">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icons/header-logo-primary.svg"
              alt="header logo primary" />
          </a>

          <? $request = $_SERVER['REQUEST_URI']; ?>

          <nav class="lp-header__nav">
            <ul class="lp-list lp-no-style lp-flex lp-wrap">
              <li>
                <a href="<?php echo get_post_type_archive_link('demands'); ?>"
                  class="lp-link lp-theme-default <?php echo ( strpos( $request, "/demands/" ) !== false ) ? 'lp-active' : '' ?>"> Спрос </a>
              </li>
              <li>
                <a href="<?php echo get_post_type_archive_link('offers'); ?>"
                  class="lp-link lp-theme-default <?php echo ( strpos( $request, "/offers/" ) !== false ) ? 'lp-active' : '' ?>"> Предложения
                </a>
              </li>
              <li>
                <a href="<?php echo get_post_type_archive_link('transactions'); ?>"
                  class="lp-link lp-theme-default <?php echo ( strpos( $request, "/transactions/" ) !== false ) ? 'lp-active' : '' ?>"> Сделки
                </a>
              </li>
              <li>
                <a href="<?php echo get_permalink( 6 ); ?>" 
                class="lp-link lp-theme-default <?php echo ( strpos( $request, "/about-us/" ) !== false ) ? 'lp-active' : '' ?>"> О проекте </a>
              </li>

              <!-- Для тестов -->
              <? if( is_user_logged_in() && current_user_can('administrator') ) : ?>
              <li>
                <a href="<?php echo get_permalink( 25 ); ?>" class="lp-link lp-theme-default">
                  <i class="lp-icon lp-prefix lp-message-flat"></i>
                  Компоненты (тест)
                </a>
              </li>
              <? endif; ?>
            </ul>
          </nav>
          <!-- <a href="<?= wp_logout_url( $redirect ); ?>">Выйти</a> -->
          <div class="lp-header__actions lp-flex lp-align-center">
            <? if ( is_user_logged_in() ) : ?>

              <? $curr_user = wp_get_current_user(); ?>

              <div class="lp-header__notifications" data-lp-notifications>
                <? 
                  $unseen_notifications = dao_count_user_unseen_notifications( $curr_user->ID ); 
                ?>
                <button class="lp-button-base lp-button-icon lp-size-large lp-theme-default lp-variant-flat"
                  aria-label="notifications" title="<? _e( 'Уведомления', 'dao-consensus' ) ?>" data-lp-notifications="button">
                  <i class="lp-icon lp-bell-flat">
                    <span class="lp-button-base__badge lp-badge">
                      <span class="lp-badge__dot <?= ( $unseen_notifications ) ? 'active' : ''; ?>"></span>
                    </span>
                  </i>
                </button>

                <div class="lp-paper lp-elevation lp-outlined lp-dense-3 lp-popover" data-lp-notifications="popover"
                  aria-hidden="true">
                  <ul class="lp-grid lp-container lp-spacing-2 lp-list lp-no-style">
                    <? 
                    if ($unseen_notifications == 0) :
                      printf('<li class="topbar no-notifications">%s</li>', __('Новых уведомлений нету.', 'dao-consensus'));
                    endif; 
                    ?>
                  </ul>
                </div>
              </div>

              <a href="<?php echo get_permalink( 21 ); ?>"
                class="lp-button-base lp-button-icon lp-size-large lp-theme-default lp-variant-flat"
                aria-label="user profile" title="<? _e( 'Профиль', 'dao-consensus' ) ?>">
                <i class="lp-icon lp-user-outlined"></i>
              </a>

              <a href="<?php echo get_permalink( 19 ); ?>"
                class="lp-button-base lp-button lp-size-large lp-theme-primary lp-variant-filled">
                Создать спрос/предложение
              </a>
            <? else: ?>
              <a href="<?php echo wp_login_url( home_url( '/profile/' ) ); ?>"
                class="lp-button-base lp-button lp-size-large lp-theme-primary lp-variant-filled">
                Вход\Регистрация
              </a>
            <? endif; ?>
          </div>
        </div>
      </header>
