<?php

/**
 * Fired during plugin activation
 *
 * @link       https://sailet.pro/
 * @since      1.0.0
 *
 * @package    Dao_Notifications
 * @subpackage Dao_Notifications/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dao_Notifications
 * @subpackage Dao_Notifications/includes
 * @author     Sailet <sales@sailet.pro>
 */
class Dao_Notifications_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		/* create plugin table if it doesn't exist */
		$table_name = "{$wpdb->prefix}user_notifications";
		$create_ddl = "CREATE TABLE {$table_name} (ID INT NOT NULL AUTO_INCREMENT, user_id INT NOT NULL, post_id INT DEFAULT NULL, type VARCHAR(64) DEFAULT NULL, message VARCHAR(2048) NOT NULL, seen TINYINT NOT NULL DEFAULT 0, seen_at DATETIME, created_at DATETIME NOT NULL, PRIMARY KEY (ID) ) ENGINE=InnoDB";
		self::maybe_create_table( $table_name, $create_ddl );

	}

	public static function maybe_create_table( string $table_name, string $create_ddl ) {
		global $wpdb;
 
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
	 
		if ( $wpdb->get_var( $query ) === $table_name ) {
			return true;
		}
	 
		// Didn't find it, so try to create it.
		$wpdb->query( $create_ddl );
	 
		// We cannot directly tell that whether this succeeded!
		if ( $wpdb->get_var( $query ) === $table_name ) {
			return true;
		}
	 
		return false;
	}

}
