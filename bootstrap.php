<?php
/**
 * The DWS WordPress Framework Settings bootstrap file.
 *
 * @since               1.0.0
 * @version             1.0.0
 * @package             DeepWebSolutions\WP-Framework\Settings
 * @author              Deep Web Solutions GmbH
 * @copyright           2020 Deep Web Solutions GmbH
 * @license             GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:         DWS WordPress Framework Settings
 * Description:         A set of related classes to create option pages and custom fields in WordPress.
 * Version:             1.0.0
 * Requires at least:   5.5
 * Requires PHP:        7.4
 * Author:              Deep Web Solutions GmbH
 * Author URI:          https://www.deep-web-solutions.com
 * License:             GPL-3.0+
 * License URI:         http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:         dws-wp-framework-settings
 * Domain Path:         /src/languages
 */

namespace DeepWebSolutions\Framework;

if ( ! defined( 'ABSPATH' ) ) {
	return; // Since this file is autoloaded by Composer, 'exit' breaks all external dev tools.
}

// Start by autoloading dependencies and defining a few functions for running the bootstrapper.
// The conditional check makes the whole thing compatible with Composer-based WP management.
file_exists( __DIR__ . '/vendor/autoload.php' ) && require_once __DIR__ . '/vendor/autoload.php';

// Define settings constants
define( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_SETTINGS_NAME', dws_wp_framework_get_whitelabel_name() . ': Framework Settings' );
define( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_SETTINGS_VERSION', '1.0.0' );

/**
 * Returns the whitelabel name of the framework's settings within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_settings_name(): string {
	return constant( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_SETTINGS_NAME' );
}

/**
 * Returns the version of the framework's settings within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_settings_version(): string {
	return constant( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_SETTINGS_VERSION' );
}

// Define minimum environment requirements.
define( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_SETTINGS_MIN_PHP', '7.4' );
define( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_SETTINGS_MIN_WP', '5.5' );

/**
 * Returns the minimum PHP version required to run the Bootstrapper of the framework's settings within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_settings_min_php(): string {
	return constant( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_SETTINGS_MIN_PHP' );
}

/**
 * Returns the minimum WP version required to run the Bootstrapper of the framework's settings within the context of the current plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  string
 */
function dws_wp_framework_get_settings_min_wp(): string {
	return constant( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_SETTINGS_MIN_WP' );
}

// Bootstrap the settings (maybe)!
if ( dws_wp_framework_check_php_wp_requirements_met( dws_wp_framework_get_settings_min_php(), dws_wp_framework_get_settings_min_wp() ) ) {
	add_action(
		'plugins_loaded',
		function() {
			define(
				__NAMESPACE__ . '\DWS_WP_FRAMEWORK_SETTINGS_INIT',
				defined( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_BOOTSTRAPPER_INIT' ) && DWS_WP_FRAMEWORK_BOOTSTRAPPER_INIT &&
				defined( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_HELPERS_INIT' ) && DWS_WP_FRAMEWORK_HELPERS_INIT &&
				defined( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_UTILITIES_INIT' ) && DWS_WP_FRAMEWORK_UTILITIES_INIT
			);
		},
		PHP_INT_MIN + 10
	);
} else {
	define( __NAMESPACE__ . '\DWS_WP_FRAMEWORK_SETTINGS_INIT', false );
	dws_wp_framework_output_requirements_error( dws_wp_framework_get_settings_name(), dws_wp_framework_get_settings_version(), dws_wp_framework_get_settings_min_php(), dws_wp_framework_get_settings_min_wp() );

	// Stop the core from initializing if the settings module failed.
	add_filter( 'dws_wp_framework_core_init_status', function( bool $init, string $namespace ) {
		return ( __NAMESPACE__ === $namespace ) ? false : $init;
	}, 10, 2 );
}
