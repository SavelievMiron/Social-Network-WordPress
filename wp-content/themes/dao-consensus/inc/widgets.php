<?php

/**
 * Enqueue styles and scripts for dashboard widgets
 *
 * @param int $hook Hook suffix for the current admin page.
 */
function wpdocs_selectively_enqueue_admin_script( $hook ) {
    if ( 'index.php' != $hook ) {
        return;
    }
    wp_enqueue_style( 'dao-statistics', get_template_directory_uri() . '/assets/css/admin-dashboard.css', array(), filemtime( get_theme_file_path( '/assets/css/admin-dashboard.css' ) ) );
    wp_enqueue_script( 'dao-statistics', get_template_directory_uri() . '/assets/js/admin/dashboard.js', array(), filemtime( get_theme_file_path( '/assets/js/admin/dashboard.js' ) ) );
}
add_action( 'admin_enqueue_scripts', 'wpdocs_selectively_enqueue_admin_script' );

/* Add all widgets */
function dao_cons_add_dashboard_widgets() {
    wp_add_dashboard_widget( 'dao_cons_statistics', __( 'Статистика по сайту', 'dao-consensus' ), 'dao_cons_dashboard_widget_statistics' );
}
add_action( 'wp_dashboard_setup', 'dao_cons_add_dashboard_widgets' );
 
/* Statistics */
function dao_cons_dashboard_widget_statistics() {
    
    /* --------------------- ΤΟΤΑL ---------------------- */

    function filter_cards ( $status ) {
        return ( in_array( $status, array( 'active', 'inactive', 'completed' ) ) );
    }
    
    function filter_meetings ( $status ) {
        return ( in_array( $status, array( 'waiting', 'accepted', 'declined', 'canceled', 'reschedule', 'went' ) ) );
    }		
    
    function filter_transactions ( $status ) {
        return ( in_array( $status, array( 'publish', 'in-process', 'completed' ) ) );
    }
    

    $total_users  = count_users()['avail_roles']['contributor'];
    $demands      = array_filter( (array) wp_count_posts( 'demands' ), 'filter_cards', ARRAY_FILTER_USE_KEY );
    $offers       = array_filter( (array) wp_count_posts( 'offers' ), 'filter_cards', ARRAY_FILTER_USE_KEY );
    $meetings     = array_filter( (array) wp_count_posts( 'meetings' ), 'filter_meetings', ARRAY_FILTER_USE_KEY );
    $transactions = array_filter( (array) wp_count_posts( 'transactions' ), 'filter_transactions', ARRAY_FILTER_USE_KEY );

    /* --------------------- PER MONTH ---------------------- */

    $curr_month = strtotime('now');
    $first_day = date('Y-m-01', $curr_month);
    $last_day = date('Y-m-t', $curr_month);

    $date_query = array(
        array(
            'before' => $last_day
        ),
        array(
            'after' => $first_day,
            'inclusive' => true
        )
    );

    $users_per_month = new WP_User_Query( array(
        'role' => 'contributor',
        'date_query' => $date_query
    ) );

    $demands_per_month = new WP_Query( array(
        'post_type' => 'demands',
        'fields' => 'ids',
        'date_query' => $date_query,
    ) );

    $offers_per_month = new WP_Query( array(
        'post_type' => 'offers',
        'fields' => 'ids',
        'date_query' => $date_query,
    ) );

    $transactions_per_month = new WP_Query( array(
        'post_type' => 'transactions',
        'fields' => 'ids',
        'date_query' => $date_query,
    ) );

    $meetings_per_month = new WP_Query( array(
        'post_type' => 'meetings',
        'fields' => 'ids',
        'date_query' => $date_query,
    ) );
?>
    <div class="dao_dashboard_widget_statistics">
        <div class="container">
            <div class="tabs">
                <button class="tab-btn active" data-tab="1"><? _e( 'Всего', 'dao-consensus' ) ?></button>
                <button class="tab-btn" data-tab="2"><? _e( 'За месяц', 'dao-consensus' ) ?></button>
            </div>
            <div id="content-1" class="tab-content">
                <div class="tab-content__row">
                    <span><b>Пользователей</b></span><span><?= $total_users; ?></span>
                </div>
                <div class="tab-content__row">
                    <span><b>Спросов</b></span><span><?= array_sum( array_values( $demands ) ); ?></span>
                </div>
                <div class="tab-content__row">
                    <span><b>Предложений</b></span><span><?= array_sum( array_values( $offers ) ); ?></span>
                </div>
                <div class="tab-content__row">
                    <span><b>Сделок</b></span><span><?= array_sum( array_values( $transactions ) ); ?></span>
                </div>
                <div class="tab-content__row">
                    <span><b>Встреч</b></span><span><?= array_sum( array_values( $meetings ) ); ?></span>
                </div>
            </div>
            <div id="content-2" class="tab-content hide">
                <div class="tab-content__row">
                    <span><b>Пользователей</b></span><span><?= $users_per_month->get_total(); ?></span>
                </div>
                <div class="tab-content__row">
                    <span><b>Спросов</b></span><span><?= $demands_per_month->found_posts; ?></span>
                </div>
                <div class="tab-content__row">
                    <span><b>Предложений</b></span><span><?= $offers_per_month->found_posts; ?></span>
                </div>
                <div class="tab-content__row">
                    <span><b>Сделок</b></span><span><?= $transactions_per_month->found_posts; ?></span>
                </div>
                <div class="tab-content__row">
                    <span><b>Встреч</b></span><span><?= $meetings_per_month->found_posts; ?></span>
                </div>
            </div>
        </div>
    </div>

<?}
