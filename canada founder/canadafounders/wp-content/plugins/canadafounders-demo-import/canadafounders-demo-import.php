<?php
/**
 * Plugin Name: CanadaFounders Demo Import
 * Description: One-click demo content importer for the CanadaFounders theme.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'CF_DI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CF_DI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files
require_once CF_DI_PLUGIN_DIR . 'includes/admin.php';
require_once CF_DI_PLUGIN_DIR . 'includes/importer.php';
require_once CF_DI_PLUGIN_DIR . 'includes/content.php';

// Initialize the plugin
function cf_demo_import_init() {
    // Add admin menu
    add_action( 'admin_menu', 'cf_demo_import_admin_menu' );
}
add_action( 'plugins_loaded', 'cf_demo_import_init' );

// Activation hook
function cf_demo_import_activate() {
    // Code to run on plugin activation
}
register_activation_hook( __FILE__, 'cf_demo_import_activate' );

// Deactivation hook
function cf_demo_import_deactivate() {
    // Code to run on plugin deactivation
}
register_deactivation_hook( __FILE__, 'cf_demo_import_deactivate' );
?>