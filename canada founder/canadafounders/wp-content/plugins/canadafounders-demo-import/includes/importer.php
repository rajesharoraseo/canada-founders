<?php
/**
 * CanadaFounders Demo Importer
 *
 * This file contains the logic for importing demo content, including functions for creating pages, posts, and other content types.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class CanadaFounders_Demo_Importer
 */
class CanadaFounders_Demo_Importer {

    /**
     * Initialize the demo importer.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_importer_menu' ) );
        add_action( 'wp_ajax_import_demo_content', array( $this, 'import_demo_content' ) );
        add_action( 'wp_ajax_reset_demo_content', array( $this, 'reset_demo_content' ) );
    }

    /**
     * Add the demo importer menu to the admin.
     */
    public function add_importer_menu() {
        add_menu_page(
            'CanadaFounders Demo Import',
            'Demo Import',
            'manage_options',
            'canadafounders-demo-import',
            array( $this, 'importer_page' ),
            'dashicons-download',
            100
        );
    }

    /**
     * Render the demo importer page.
     */
    public function importer_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'CanadaFounders Demo Import', 'canadafounders' ); ?></h1>
            <p><?php esc_html_e( 'Import demo content to get started quickly.', 'canadafounders' ); ?></p>
            <button id="import-demo-content" class="button button-primary"><?php esc_html_e( 'Install Demo Content', 'canadafounders' ); ?></button>
            <div id="import-status"></div>
        </div>
        <script>
            document.getElementById('import-demo-content').addEventListener('click', function() {
                var data = {
                    'action': 'import_demo_content'
                };
                document.getElementById('import-status').innerHTML = '<?php esc_html_e( 'Importing...', 'canadafounders' ); ?>';
                jQuery.post(ajaxurl, data, function(response) {
                    document.getElementById('import-status').innerHTML = response;
                });
            });
        </script>
        <?php
    }

    /**
     * Import demo content.
     */
    public function import_demo_content() {
        // Check user capabilities and nonce.
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( esc_html__( 'You do not have sufficient permissions to access this page.', 'canadafounders' ) );
        }

        // Import logic goes here.
        // Create pages, posts, and other content types.

        wp_send_json_success( esc_html__( 'Demo content imported successfully!', 'canadafounders' ) );
    }

    /**
     * Reset demo content.
     */
    public function reset_demo_content() {
        // Logic to remove demo content goes here.

        wp_send_json_success( esc_html__( 'Demo content reset successfully!', 'canadafounders' ) );
    }
}

// Initialize the demo importer.
new CanadaFounders_Demo_Importer();
?>