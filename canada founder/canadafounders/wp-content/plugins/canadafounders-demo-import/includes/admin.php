<?php
// CanadaFounders Demo Import Admin Interface

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class CanadaFounders_Demo_Import_Admin {
    
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_import_demo_content', array( $this, 'import_demo_content' ) );
        add_action( 'wp_ajax_reset_demo_content', array( $this, 'reset_demo_content' ) );
    }

    public function add_admin_menu() {
        add_menu_page(
            'CanadaFounders Demo Import',
            'Demo Import',
            'manage_options',
            'canadafounders-demo-import',
            array( $this, 'admin_page' ),
            'dashicons-download',
            100
        );
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'canadafounders-demo-import-admin', plugins_url( 'assets/css/admin.css', __FILE__ ) );
        wp_enqueue_script( 'canadafounders-demo-import-admin', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), null, true );
    }

    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'CanadaFounders Demo Import', 'canadafounders' ); ?></h1>
            <p><?php esc_html_e( 'Import demo content to set up your site quickly.', 'canadafounders' ); ?></p>
            <button id="import-demo-content" class="button button-primary"><?php esc_html_e( 'Install Demo Content', 'canadafounders' ); ?></button>
            <button id="reset-demo-content" class="button button-secondary"><?php esc_html_e( 'Reset Demo Content', 'canadafounders' ); ?></button>
            <div id="import-status"></div>
        </div>
        <?php
    }

    public function import_demo_content() {
        // Security checks
        check_ajax_referer( 'canadafounders_demo_import', 'nonce' );

        // Import logic here
        // ...

        wp_send_json_success( array( 'message' => __( 'Demo content imported successfully.', 'canadafounders' ) ) );
    }

    public function reset_demo_content() {
        // Security checks
        check_ajax_referer( 'canadafounders_demo_import', 'nonce' );

        // Reset logic here
        // ...

        wp_send_json_success( array( 'message' => __( 'Demo content reset successfully.', 'canadafounders' ) ) );
    }
}

new CanadaFounders_Demo_Import_Admin();
?>