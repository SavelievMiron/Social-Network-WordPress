<? 
if ( in_array( $args['notification']->type, ['system', 'demand-in-process', 'offer-in-process', 'transaction'] ) ) : 
?>
    <div id="<?php echo $args['notification']->ID; ?>" class="lp-notification <?php echo ( ! $args['notification']->seen ) ? 'lp-unseen' : ''; ?> lp-system">
        <div class="lp-notification__body lp-paper lp-dense-4">
            <div class="lp-notification__content">
                <span class="lp-typo lp-sub lp-danger"><?php echo $args['notification']->message; ?></span>
            </div>

            <div class="lp-notification__date">
                <span class="lp-typo lp-sub lp-grey">
                    <? echo date('d.m.Y (H:i:s)', strtotime($args['notification']->created_at)); ?>
                </span>
            </div>
        </div>
    </div>
<? 
elseif ( $args['notification']->type === 'card' ) : 
?>
    <div id="<?php echo $args['notification']->ID; ?>" class="lp-notification <?php echo ( ! $args['notification']->seen ) ? 'lp-unseen' : ''; ?> lp-default">
        <div class="lp-notification__body lp-paper lp-dense-4">
            <div class="lp-notification__content">
                <span class="lp-typo lp-sub lp-danger"><?php echo $args['notification']->message; ?></span>
            </div>

            <div class="lp-notification__date">
                <span class="lp-typo lp-sub lp-grey">
                    <? echo date('d.m.Y (H:i:s)', strtotime($args['notification']->created_at)); ?>
                </span>
            </div>
        </div>
    </div>
<? 
elseif ( $args['notification']->type === 'meeting' ) : 
    $meeting = get_post( $args['notification']->post_id );
    
    if ( in_array( $meeting->post_status, ['declined'] ) ) {
        $user = get_userdata( $meeting->invited_id );
        $user_link = get_author_posts_url( $user->ID );    
    } elseif ( in_array( $meeting->post_status, ['canceled', 'waiting', 'accepted', 'went'] ) ) {
        $user = get_userdata( $meeting->post_author );
        $user_link = get_author_posts_url( $user->ID );
    } elseif ( $meeting->post_status === 'reschedule' ) {
        $user = get_userdata( $meeting->reschedule_initiator );
        $user_link = get_author_posts_url( $user->reschedule_initiator );
    }
    
?>
    <div id="<?php echo $args['notification']->ID; ?>" class="lp-notification <?php echo ( ! $args['notification']->seen ) ? 'lp-unseen' : ''; ?> lp-meeting">
        <div class="lp-notification__body lp-paper lp-dense-4">
            <div class="lp-paper lp-notification__content">
                <a class="lp-user-avatar <?php echo ( dao_is_user_online( $user->ID ) ? 'lp-online' : 'lp-offline' ) ?>" href="<?php echo $user_link; ?>" title="<?php echo esc_attr( $user->display_name ); ?>">
                    <img src="<?php echo get_avatar_url( $user->ID, array( 'size' => 50, 'default' => 'mystery', ) ); ?>" alt="user avatar">
                </a>
                <span class="lp-typo lp-sub lp-danger"><?php echo $args['notification']->message; ?> <a href="<?php echo get_permalink( 120 ); ?>">Перейти к встречам</a>.</span>
            </div>
            <div class="lp-notification__date">
                <span class="lp-typo lp-sub lp-grey">
                    <? echo date('d.m.Y (H:i:s)', strtotime($args['notification']->created_at)); ?>
                </span>
            </div>
        </div>
    </div>
<? 
elseif ( $args['notification']->type === 'after_meeting' ) : 
    $meeting = get_post( $args['notification']->post_id );

    $invitor = get_userdata( $meeting->post_author );
    $invitor_link = get_author_posts_url( $invitor->ID );  
?>
    <div id="<?php echo $args['notification']->ID; ?>" class="lp-notification <?php echo ( ! $args['notification']->seen ) ? 'lp-unseen' : ''; ?> lp-meeting lp-after-meeting">
        <div class="lp-notification__body lp-paper lp-dense-4">
            <div class="lp-notification__content">
                <span class="lp-typo lp-sub lp-danger"><?php echo $args['notification']->message; ?></span>
                <div class="lp-options lp-flex lp-justify-between lp-align-center">
                    <form>
                        <button class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled" type="submit" data-result="success"><? _e('Да', 'dao-consensus') ?></button>
                        <button class="lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled" type="submit" data-result="failure"><? _e('Нет', 'dao-consensus') ?></button>
                        
                        <input type="hidden" name="action" value="dao-after-meeting-result">
                        <input type="hidden" name="notification_id" value="<?php echo $args['notification']->ID; ?>">
                        <input type="hidden" name="meeting_id" value="<?php echo $meeting->ID; ?>">
                        <input type="hidden" name="result">
                        <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('dao-consensus-after-meeting-result'); ?>">
                    </form>
                    <span class="lp-loader lp-hide">
                        <svg class="lp-loader__circle" width="40" height="40"
                            viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <circle class="lp-loader__path" fill="none" stroke-width="5"
                                stroke-linecap="round" cx="24" cy="24" r="20" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="lp-notification__date">
                <span class="lp-typo lp-sub lp-grey">
                    <? echo date('d.m.Y (H:i:s)', strtotime($args['notification']->created_at)); ?>
                </span>
            </div>
        </div>
    </div>

<? elseif ($args['notification']->type === 'did-not-agree') : ?>

<div id="<?php echo $args['notification']->ID; ?>" class="lp-notification <?php echo ( ! $args['notification']->seen ) ? 'lp-unseen' : ''; ?> lp-danger">
    <div class="lp-notification__body lp-paper lp-dense-4">
        <div class="lp-notification__content">
            <span class="lp-typo lp-sub lp-danger"><?php echo $args['notification']->message; ?></span>
        </div>

        <div class="lp-notification__date">
            <span class="lp-typo lp-sub lp-grey">
                <? echo date('d.m.Y (H:i:s)', strtotime($args['notification']->created_at)); ?>
            </span>
        </div>
    </div>
</div>

<? endif; ?>
