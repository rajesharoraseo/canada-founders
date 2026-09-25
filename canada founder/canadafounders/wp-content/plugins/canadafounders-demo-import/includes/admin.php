<?php
/**
 * CanadaFounders Demo Import admin screen.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CanadaFounders_Demo_Import_Admin {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	public function add_admin_menu() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

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

	public function admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'CanadaFounders Demo Import', 'canadafounders' ); ?></h1>
			<p><?php echo esc_html__( 'Import demo content to set up your site quickly.', 'canadafounders' ); ?></p>
			<p><?php echo esc_html__( 'Use the importer to create starter pages for the community, members, events, and partners.', 'canadafounders' ); ?></p>
		</div>
		<?php
	}
}

new CanadaFounders_Demo_Import_Admin();
