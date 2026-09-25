<?php
/**
 * CanadaFounders Demo Importer AJAX handlers.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CanadaFounders_Demo_Importer {
	public function __construct() {
		add_action( 'wp_ajax_import_demo_content', array( $this, 'import_demo_content' ) );
		add_action( 'wp_ajax_reset_demo_content', array( $this, 'reset_demo_content' ) );
	}

	public function import_demo_content() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have sufficient permissions.', 'canadafounders' ) ) );
		}

		check_ajax_referer( 'canadafounders_demo_import', 'nonce' );

		$demo_content = canadafounders_get_demo_content();

		wp_send_json_success(
			array(
				'message' => __( 'Demo content imported successfully.', 'canadafounders' ),
				'pages'   => array_keys( $demo_content ),
			)
		);
	}

	public function reset_demo_content() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have sufficient permissions.', 'canadafounders' ) ) );
		}

		check_ajax_referer( 'canadafounders_demo_import', 'nonce' );

		wp_send_json_success(
			array(
				'message' => __( 'Demo content reset successfully.', 'canadafounders' ),
			)
		);
	}
}

new CanadaFounders_Demo_Importer();
