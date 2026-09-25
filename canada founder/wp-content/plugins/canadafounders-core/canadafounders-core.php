<?php
/**
 * Plugin Name: CanadaFounders Core
 * Description: Core functionality for the CanadaFounders website, including custom post types and demo content tools.
 * Version: 1.1.0
 * Author: CanadaFounders
 * Text Domain: canadafounders
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cf_core_dir = plugin_dir_path( __FILE__ ) . 'includes/';

require_once $cf_core_dir . 'cpt-events.php';
require_once $cf_core_dir . 'cpt-members.php';
require_once $cf_core_dir . 'cpt-partners.php';
require_once $cf_core_dir . 'demo-importer.php';
