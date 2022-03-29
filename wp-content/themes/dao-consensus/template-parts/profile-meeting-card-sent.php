<?
    $curr_user_id = get_current_user_id();

    $invitor_id = get_post_field('post_author', get_the_ID());
    $invitor_data = get_userdata( $invitor_id );

    $invited_id = get_post_meta( get_the_ID(), 'invited_id', true );
    $invited_data = get_userdata( $invited_id );

    $card_id = get_post_meta( get_the_ID(), 'card_id', true );
    $card = get_post( $card_id );

    $status = get_post_status();
    $row_type = ( get_post_field('post_author', get_the_ID()) == get_current_user_id() ) ? 'sent' : 'received'; ;
?>

<tr id="<? the_ID(); ?>" class="meeting" data-row-type="<?= $row_type; ?>" data-status="<?= $status; ?>" data-id="<? the_ID(); ?>" data-invitor="<?= $invitor_data->first_name; ?>" 
<? if ( $status === 'reschedule' ) : if ( $curr_user_id == get_post_meta( get_the_ID(), 'reschedule_initiator', true ) ) : echo 'data-lp-reschedule-initiator="true"'; else: echo 'data-lp-reschedule-initiator="false"'; endif; endif; ?>>
    <td class="title lp-align-left">
        <span class="lp-typo lp-sub lp-grey" title="<? the_title(); ?>"><? the_title(); ?></span>
    </td>

    <td class="lp-align-left">
        <span class="lp-typo lp-sub lp-grey"><?= eng_months_to_ru( date( 'j F H:i', get_post_meta( get_the_ID(), 'datetime', true ) ) ); ?></span>
    </td>

    <td class="lp-align-left">
        <? $status = DAO_CONSENSUS::meeting_statuses[get_post_status()]; ?>
        <span class="meeting-status lp-typo lp-sub" title="<?= $status; ?>">
            <?= $status; ?>
        </span>
    </td>

    <td class="lp-align-left">
        <span class="lp-typo lp-sub" title="<?= $card->post_title; ?>">
            <? printf('<a href="%1$s">%2$s</a>', get_permalink( $card->ID ), $card->post_title ); ?>
        </span>
    </td>

    <td class="lp-align-left">
        <? $format = wp_get_post_terms( get_the_ID(), 'formats', array( 'fields' => 'names' ) ); ?>
        <span class="lp-typo lp-sub lp-grey"><?= $format[0]; ?></span>
    </td>

    <td class="lp-align-left">
        <span class="lp-typo lp-sub"><? printf( "<a href='%s'>%s</a>", get_author_posts_url( $invited_id ), $invited_data->display_name ); ?></span>
    </td>

    <td class="lp-align-left">
        <? $venue = get_post_meta( get_the_ID(), 'venue', true ); ?>
        <span class="lp-typo lp-sub lp-grey" title="<?= $venue; ?>"><?= $venue; ?></span>
    </td>

    <td class="lp-align-right lp-options">
        <button class="lp-button-base lp-button-icon lp-size-medium lp-theme-default lp-variant-flat lp-rounded" data-lp-row="button">
            <i class="lp-icon lp-ellipsis-vt-flat"></i>
        </button>
    </td>
</tr>
