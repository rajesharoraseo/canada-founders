<?php
/**
 * Plugin Name: CanadaFounders Core
 * Description: Core functionality for the CanadaFounders website. Registers Custom Post Types and Meta Boxes.
 * Version: 1.0.0
 * Author: Jules
 */

if ( ! defined( 'ABSPATH' ) ) {
    die(); // Exit if accessed directly.
}

// Include files
require_once plugin_dir_path( __FILE__ ) . 'includes/cpt-events.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/cpt-members.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/cpt-partners.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/demo-importer.php';
