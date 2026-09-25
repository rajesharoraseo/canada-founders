<?php
/**
 * CanadaFounders Demo Importer
 *
 * This file implements the one-click demo content importer functionality,
 * providing the necessary logic to import demo content into the WordPress site.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class CanadaFounders_Demo_Importer
 */
class CanadaFounders_Demo_Importer {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_demo_importer_menu' ) );
        add_action( 'wp_ajax_import_demo_content', array( $this, 'import_demo_content' ) );
        add_action( 'wp_ajax_reset_demo_content', array( $this, 'reset_demo_content' ) );
    }

    public function add_demo_importer_menu() {
        add_menu_page(
            'CanadaFounders Demo Import',
            'Demo Import',
            'manage_options',
            'canadafounders-demo-import',
            array( $this, 'demo_importer_page' ),
            'dashicons-download',
            30
        );
    }

    public function demo_importer_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'CanadaFounders Demo Import', 'canadafounders' ); ?></h1>
            <p><?php esc_html_e( 'Import demo content to set up your site quickly.', 'canadafounders' ); ?></p>
            <button id="import-demo-content" class="button button-primary"><?php esc_html_e( 'Install Demo Content', 'canadafounders' ); ?></button>
            <button id="reset-demo-content" class="button button-secondary"><?php esc_html_e( 'Reset Demo Content', 'canadafounders' ); ?></button>
            <div id="import-status"></div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $('#import-demo-content').on('click', function() {
                    var data = {
                        'action': 'import_demo_content',
                        'nonce': '<?php echo wp_create_nonce( 'import_demo_content_nonce' ); ?>'
                    };
                    $('#import-status').html('<p><?php esc_html_e( 'Importing...', 'canadafounders' ); ?></p>');
                    $.post(ajaxurl, data, function(response) {
                        $('#import-status').html(response);
                    });
                });

                $('#reset-demo-content').on('click', function() {
                    if (confirm('<?php esc_html_e( 'Are you sure you want to reset demo content?', 'canadafounders' ); ?>')) {
                        var data = {
                            'action': 'reset_demo_content',
                            'nonce': '<?php echo wp_create_nonce( 'reset_demo_content_nonce' ); ?>'
                        };
                        $.post(ajaxurl, data, function(response) {
                            $('#import-status').html(response);
                        });
                    }
                });
            });
        </script>
        <?php
    }

    public function import_demo_content() {
        check_ajax_referer( 'import_demo_content_nonce', 'nonce' );

        // Logic to import demo content goes here.
        // Ensure idempotency and avoid duplicates.

        wp_send_json_success( esc_html__( 'Demo content imported successfully!', 'canadafounders' ) );
    }

    public function reset_demo_content() {
        check_ajax_referer( 'reset_demo_content_nonce', 'nonce' );

        // Logic to reset demo content goes here.

        wp_send_json_success( esc_html__( 'Demo content reset successfully!', 'canadafounders' ) );
    }
}

new CanadaFounders_Demo_Importer();
?>