<?
    $user = $args['user'];
    $per_page = $args['per_page'];
    $page = $args['page'];
    $curr_index = $args['index']; // curr position in query result
?>

<tr>
    <td class="lp-cell-inline">
        <span class="lp-typo lp-sub"><?= ( intval($page) * $per_page - $per_page ) + intval( $curr_index ); ?></span>
    </td>

    <td class="lp-align-center">
        <a class="lp-user" href="<?= get_author_posts_url( $user->ID ) ?>" title="<? printf('%s %s', $user->first_name, $user->last_name); ?>">
            <img src="<?= get_avatar_url( $user->ID, array( 'size' => 50, 'default' => 'mystery' ) ); ?>" alt="profile avatar">
            <span class="lp-typo lp-sub lp-grey">
                <? printf('%s %s', $user->first_name, $user->last_name); ?>
            </span>
        </a>
    </td>

    <td class="lp-align-center">
        <span class="lp-typo lp-sub"><?= get_user_meta( $user->ID, 'rating', true ); ?></span>
    </td>

    <td class="lp-align-center">
        <span class="lp-typo lp-sub"><?= get_user_meta( $user->ID, 'completed_meetings', true ); ?></span>
    </td>

    <td class="lp-align-center">
        <span class="lp-typo lp-sub"><?= get_user_meta( $user->ID, 'completed_transactions', true ); ?></span>
    </td>
</tr>
