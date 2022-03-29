<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://sailet.pro/
 * @since             1.0.0
 * @package           Dao_Notifications
 *
 * @wordpress-plugin
 * Plugin Name:       DAO Notifications
 * Plugin URI:        https://sailet.pro/
 * Description:       This plugin is responsible for functionality of user notifications
 * Version:           1.0.0
 * Author:            Sailet
 * Author URI:        https://sailet.pro/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dao-notifications
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DAO_NOTIFICATIONS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dao-notifications-activator.php
 */
function activate_dao_notifications() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dao-notifications-activator.php';
	Dao_Notifications_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dao-notifications-deactivator.php
 */
function deactivate_dao_notifications() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dao-notifications-deactivator.php';
	Dao_Notifications_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dao_notifications' );
register_deactivation_hook( __FILE__, 'deactivate_dao_notifications' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dao-notifications.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dao_notifications() {

	$plugin = new Dao_Notifications();
	$plugin->run();

}
run_dao_notifications();
