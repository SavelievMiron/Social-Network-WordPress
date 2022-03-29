<?php

/* Add all widgets */
function dao_cons_add_dashboard_widgets() {
    wp_add_dashboard_widget( 'dao_cons_statistics', __( 'Статистика', 'dao-consensus' ), 'dao_cons_dashboard_widget_statistics' );
}
add_action( 'wp_dashboard_setup', 'dao_cons_add_dashboard_widgets' );
 
/* Statistics */
function dao_cons_dashboard_widget_statistics() {
    echo "CONTENT";
}
