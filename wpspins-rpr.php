<?php
/**
 * The plugin bootstrap file
 * php version 7.2.10
 *
 * @category Tools
 * @package  Wpspins
 * @author   WpSpins <wpspins@gmail.com>
 * @license  GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link     https://wpspins.com/
 * @since    1.0.0
 */

/**
 * Plugin Name: Region or ZIP code restriction
 * Plugin URI:
 * Description: The plugin restricts Billing/Shipping for custom states and custom zip codes ranges.
 * Version:           1.0.0
 * Author:            WPSpins
 * Author URI:        http://wpspins.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpspins-rpr
 * Domain Path:       /languages
 *
 * @package wpspins-rpr
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define( 'WPX_CLZ_VERSION', '1.0.0' );
define( 'WPX_CLZ_NAME', 'Region or ZIP code restriction' );
define( 'WPX_CLZ_PLUGIN_NAME', 'wpspins-rpr' );
define( 'WPX_CLZ_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define( 'WPX_CLZ_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define(
	'WPX_CLZ_PLUGIN_URL',
	trailingslashit(
		plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) )
	)
);

// Load Everything.
require 'classes/load-classes.php';

