<? 
if ( in_array( $args['notification']->type, ['system', 'transaction'] ) ) : 
?>
    <li id="<?= $args['notification']->ID ?>" class="lp-item lp-xs-12 lp-notification lp-system">
        <div class="lp-paper lp-dense-4">
            <div class="lp-notification__body">
                <div class="lp-avatar"></div>

                <p class="lp-typo lp-sub"><?= $args['notification']->message; ?></p>
            </div>

            <div class="lp-notification__close">
                <button
                    class="lp-button-base lp-button-icon lp-size-small lp-theme-secondary lp-variant-flat lp-rounded">
                    <i class="lp-icon lp-times-flat"></i>
                </button>
            </div>
        </div>
    </li>
<? 
elseif ( in_array( $args['notification']->type, ['demand-in-process', 'offer-in-process'] ) ) : 
?>
    <li id="<?= $args['notification']->ID ?>" class="lp-item lp-xs-12 lp-notification lp-system">
        <div class="lp-paper lp-dense-4">
            <div class="lp-notification__body">
                <div class="lp-avatar"></div>

                <p class="lp-typo lp-sub"><?= $args['notification']->message; ?></p>

                <a href="<?= get_permalink( 126 ); ?>" class="lp-typo lp-sub">Перейти к разделу "В работе"</a>
            </div>

            <div class="lp-notification__close">
                <button
                    class="lp-button-base lp-button-icon lp-size-small lp-theme-secondary lp-variant-flat lp-rounded">
                    <i class="lp-icon lp-times-flat"></i>
                </button>
            </div>
        </div>
    </li>
<? 
elseif ( $args['notification']->type === 'card' ) : 
?>
    <li id="<?= $args['notification']->ID ?>" class="lp-item lp-xs-12 lp-notification lp-default">
        <div class="lp-paper lp-dense-4">
            <div class="lp-notification__body">
                <div class="lp-avatar"></div>

                <!-- <h5 class="lp-typo lp-body">Изменения не применены</h5> -->

                <p class="lp-typo lp-sub"><?= $args['notification']->message; ?></p>
            </div>

            <div class="lp-notification__close">
                <button
                    class="lp-button-base lp-button-icon lp-size-small lp-theme-secondary lp-variant-flat lp-rounded">
                    <i class="lp-icon lp-times-flat"></i>
                </button>
            </div>
        </div>
    </li>
<? 
elseif ( $args['notification']->type === 'meeting' ) : 
    $meeting = get_post( $args['notification']->post_id );
    
    if ( in_array( $meeting->post_status, ['declined'] ) ) {
        $user = get_userdata( $meeting->invited_id );
        $user_link = get_author_posts_url( $user->ID );    
    } elseif ( in_array( $meeting->post_status, ['canceled', 'waiting', 'accepted'] ) ) {
        $user = get_userdata( $meeting->post_author );
        $user_link = get_author_posts_url( $user->ID );
    } elseif ( $meeting->post_status === 'reschedule' ) {
        $user = get_userdata( $meeting->reschedule_initiator );
        $user_link = get_author_posts_url( $user->reschedule_initiator );
    }
    
?>
    <li id="<?= $args['notification']->ID ?>" class="lp-item lp-xs-12 lp-notification lp-meeting">
        <div class="lp-paper lp-dense-4">
            <div class="lp-notification__body">
                <div class="lp-avatar">
                    <a class="lp-user-avatar <?= ( dao_is_user_online( $user->ID ) ? 'lp-online' : 'lp-offline' ) ?>" href="<?= $user_link; ?>" title="<?= esc_attr( $user->display_name ); ?>">
                        <img src="<?= get_avatar_url( $user->ID, array( 'size' => 50, 'default' => 'mystery', ) ); ?>" alt="user avatar">
                    </a>
                </div>

                <!-- <h5 class="lp-typo lp-body">Изменения не применены</h5> -->

                <p class="lp-typo lp-sub"><?= $args['notification']->message; ?></p>

                <a href="<?php echo get_permalink( 120 ); ?>" class="lp-typo lp-sub">Перейти к встречам</a>
            </div>

            <div class="lp-notification__close">
                <button
                    class="lp-button-base lp-button-icon lp-size-small lp-theme-secondary lp-variant-flat lp-rounded">
                    <i class="lp-icon lp-times-flat"></i>
                </button>
            </div>
        </div>
    </li>
<? 
elseif ( $args['notification']->type === 'after_meeting' ) : 
?>
    <li id="<?= $args['notification']->ID ?>" class="lp-item lp-xs-12 lp-notification lp-meeting">
        <div class="lp-paper lp-dense-4">
            <div class="lp-notification__body">

                <!-- <h5 class="lp-typo lp-body">Изменения не применены</h5> -->

                <p class="lp-typo lp-sub"><?= $args['notification']->message; ?></p>

                <a href="<?php echo get_permalink( 348 ); ?>" class="lp-typo lp-sub">Перейти к уведомлениям</a>
            </div>

            <div class="lp-notification__close">
                <button
                    class="lp-button-base lp-button-icon lp-size-small lp-theme-secondary lp-variant-flat lp-rounded">
                    <i class="lp-icon lp-times-flat"></i>
                </button>
            </div>
        </div>
    </li>

<? elseif ($args['notification']->type === 'did-not-agree') : ?>

<li id="<?= $args['notification']->ID ?>" class="lp-item lp-xs-12 lp-notification lp-danger">
    <div class="lp-paper lp-dense-4">
        <div class="lp-notification__body">
            <p class="lp-typo lp-sub"><?= $args['notification']->message; ?></p>

            <a href="<?php echo get_permalink( 348 ); ?>" class="lp-typo lp-sub">Перейти к уведомлениям</a>
        </div>

        <div class="lp-notification__close">
            <button
                class="lp-button-base lp-button-icon lp-size-small lp-theme-secondary lp-variant-flat lp-rounded">
                <i class="lp-icon lp-times-flat"></i>
            </button>
        </div>
    </div>
</li>

<? endif; ?>
