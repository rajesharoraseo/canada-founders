<?php
/**
 * Plugin Name: CanadaFounders Demo Import
 * Description: One-click demo content importer for the CanadaFounders theme.
 * Version: 1.0.0
 * Author: CanadaFounders
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'CF_DI_PLUGIN_DIR' ) ) {
	define( 'CF_DI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CF_DI_PLUGIN_URL' ) ) {
	define( 'CF_DI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

require_once CF_DI_PLUGIN_DIR . 'includes/content.php';
require_once CF_DI_PLUGIN_DIR . 'includes/admin.php';
require_once CF_DI_PLUGIN_DIR . 'includes/importer.php';

register_activation_hook( __FILE__, function () {
	return true;
} );

register_deactivation_hook( __FILE__, function () {
	return true;
} );
