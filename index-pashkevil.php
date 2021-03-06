<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              avitrop
 * @since             1.0.0
 * @package           Index_Pashkevil
 *
 * @wordpress-plugin
 * Plugin Name:       index_pashkevil
 * Plugin URI:        index
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            tziki trop
 * Author URI:        avitrop
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       index-pashkevil
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );
// Define global constants
$constant_name_prefix = 'MB_';
defined( $constant_name_prefix . 'REGISTER' ) or define( $constant_name_prefix . 'DIR', dirname( plugin_basename( __FILE__ ) ) );
defined( $constant_name_prefix . 'LOGIN' ) or define( $constant_name_prefix . 'BASE', plugin_basename( __FILE__ ) );
defined( $constant_name_prefix . 'TEX1' ) or define( $constant_name_prefix . 'TEX1', 40 );



/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-index-pashkevil-activator.php
 */
function activate_index_pashkevil() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-index-pashkevil-activator.php';
	Index_Pashkevil_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-index-pashkevil-deactivator.php
 */
function deactivate_index_pashkevil() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-index-pashkevil-deactivator.php';
	Index_Pashkevil_Deactivator::deactivate();
}
function mb_redirect($page,$vars = []){
	//$url  = '';
	//$url = add_query_arg($vars,get_permalink(40));
	$page_id = 0;
	switch($page){
		case 'tex1':
		$page_id = 40;
		break;
		case 'register':
		$page_id = 224;
		break;
		case 'login':
		$page_id = 256;
		break;
	}
	return add_query_arg($vars,get_permalink($page_id));

}
register_activation_hook( __FILE__, 'activate_index_pashkevil' );
register_deactivation_hook( __FILE__, 'deactivate_index_pashkevil' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-index-pashkevil.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_index_pashkevil() {

	$plugin = new Index_Pashkevil();
	$plugin->run();

}
run_index_pashkevil();
