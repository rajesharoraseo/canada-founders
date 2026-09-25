<?php
/**
 * CanadaFounders one-click Gutenberg demo importer.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class CanadaFounders_Demo_Importer {
	const PAGE_META = '_cf_demo_page';
	const MENU_NAME = 'CanadaFounders Primary Navigation';

	public static function boot() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_post_cf_import_demo_data', array( __CLASS__, 'import' ) );
	}

	public static function register_menu() {
	add_menu_page(
		__( 'Import Demo Data', 'canadafounders' ),
		__( 'Import Demo Data', 'canadafounders' ),
		'manage_options',
		'cf-demo-importer',
		array( __CLASS__, 'screen' ),
		'dashicons-download',
		26
	);
	}

	public static function screen() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$status = isset( $_GET['cf_import_status'] ) ? sanitize_key( wp_unslash( $_GET['cf_import_status'] ) ) : '';
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Import Demo Data', 'canadafounders' ); ?></h1>
			<?php if ( 'success' === $status ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php echo esc_html__( 'Demo content was imported successfully.', 'canadafounders' ); ?></p></div>
			<?php elseif ( 'error' === $status ) : ?>
				<div class="notice notice-error is-dismissible"><p><?php echo esc_html__( 'The demo content could not be imported. Check the site error log and try again.', 'canadafounders' ); ?></p></div>
			<?php endif; ?>
			<p><?php echo esc_html__( 'Creates professional starter pages, a primary navigation menu, and a static homepage. Existing pages are never overwritten unless they were created by this importer.', 'canadafounders' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'cf_import_demo_data', 'cf_import_demo_data_nonce' ); ?>
				<input type="hidden" name="action" value="cf_import_demo_data">
				<?php submit_button( __( 'Import Demo Content', 'canadafounders' ), 'primary', 'submit', false ); ?>
			</form>
		</div>
		<?php
	}

	public static function import() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to import demo content.', 'canadafounders' ) );
		}
		check_admin_referer( 'cf_import_demo_data', 'cf_import_demo_data_nonce' );

		$page_ids = array();
		foreach ( self::pages() as $key => $page ) {
			$page_ids[ $key ] = self::upsert_page( $key, $page );
		}

		$success = ! empty( $page_ids['home'] ) && self::configure_menu( $page_ids ) && self::configure_home( $page_ids['home'] );
		$url = add_query_arg( 'cf_import_status', $success ? 'success' : 'error', admin_url( 'admin.php?page=cf-demo-importer' ) );
		wp_safe_redirect( $url );
		exit;
	}

	private static function pages() {
		return array(
			'home'      => array( 'title' => 'Home', 'content' => self::home_content() ),
			'community' => array( 'title' => 'Community', 'content' => self::basic_content( 'Community', 'A connected network of founders, business owners, investors, mentors, and professionals building stronger businesses across Canada.', 'CanadaFounders creates space for practical conversations, useful relationships, and shared progress. Connect with people at a similar stage, learn from different perspectives, and find opportunities to contribute.', 'Explore the Network', 'members' ) ),
			'events'    => array( 'title' => 'Events', 'content' => self::basic_content( 'Events', 'Make meaningful connections through practical events and conversations.', 'Explore workshops, networking sessions, and community gatherings designed for founders, operators, and professionals. Event listings will be added as dates are confirmed.', 'View Upcoming Events', 'events' ) ),
			'resources' => array( 'title' => 'Resources', 'content' => self::basic_content( 'Resources', 'Practical guidance for building, operating, and growing a business in Canada.', 'Use this space for articles, tools, checklists, and local insights that help you make informed decisions. Resources are designed to be useful at every stage of the business journey.', 'Explore Resources', 'resources' ) ),
			'funding'   => array( 'title' => 'Funding', 'content' => self::basic_content( 'Funding', 'Understand funding pathways and prepare for stronger capital conversations.', 'Learn about financing options, capital readiness, and the questions to consider before approaching a funding partner. Funding decisions should be based on your business model, goals, and current stage.', 'Learn About Funding', 'funding' ) ),
			'members'   => array( 'title' => 'Members', 'content' => self::directory_content( 'Members', 'Meet founders, operators, investors, mentors, and professionals contributing to Canada’s business ecosystem.', 'cf_member', 'Join the Network', 'join' ) ),
			'partners'  => array( 'title' => 'Partners', 'content' => self::directory_content( 'Partners', 'Connect with organizations and businesses supporting entrepreneurship and growth across Canada.', 'cf_partner', 'Become a Partner', 'join' ) ),
			'about'     => array( 'title' => 'About', 'content' => self::basic_content( 'About CanadaFounders', 'A community for people building businesses, opportunities, and stronger local economies.', 'CanadaFounders brings together the experience and energy of Canada’s business community. Our focus is simple: make it easier to connect with the right people, find relevant support, and contribute to shared growth.', 'Join the Community', 'join' ) ),
			'join'      => array( 'title' => 'Join', 'content' => self::basic_content( 'Join the Community', 'Build useful relationships and find your place in a practical, welcoming business community.', 'Whether you are starting, scaling, advising, investing, or supporting businesses, CanadaFounders is a place to exchange ideas and create opportunity. Add your perspective and help strengthen the network.', 'Get Involved', 'about' ) ),
		);
	}

	private static function upsert_page( $key, $page ) {
		$existing = get_posts( array( 'post_type' => 'page', 'post_status' => 'any', 'meta_key' => self::PAGE_META, 'meta_value' => $key, 'numberposts' => 1 ) );
		$data = array( 'post_title' => $page['title'], 'post_name' => sanitize_title( $key ), 'post_content' => $page['content'], 'post_status' => 'publish', 'post_type' => 'page' );
		if ( $existing ) {
			$data['ID'] = $existing[0]->ID;
			$id = wp_update_post( $data, true );
		} else {
			$id = wp_insert_post( $data, true );
		}
		if ( is_wp_error( $id ) ) {
			return 0;
		}
		update_post_meta( $id, self::PAGE_META, sanitize_key( $key ) );
		return (int) $id;
	}

	private static function configure_menu( $page_ids ) {
		$menu = wp_get_nav_menu_object( self::MENU_NAME );
		$menu_id = $menu ? $menu->term_id : wp_create_nav_menu( self::MENU_NAME );
		if ( is_wp_error( $menu_id ) ) {
			return false;
		}
		foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $item ) {
			if ( in_array( (int) $item->object_id, array_map( 'intval', $page_ids ), true ) ) {
				wp_delete_post( $item->ID, true );
			}
		}
		foreach ( array( 'home', 'community', 'events', 'resources', 'funding', 'members', 'partners', 'about', 'join' ) as $key ) {
			if ( empty( $page_ids[ $key ] ) || is_wp_error( wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-object-id' => $page_ids[ $key ], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish', 'menu-item-title' => get_the_title( $page_ids[ $key ] ) ) ) ) ) {
				return false;
			}
		}
		$locations = (array) get_theme_mod( 'nav_menu_locations' );
		$locations['menu-1'] = $menu_id;
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
		return true;
	}

	private static function configure_home( $home_id ) {
		return update_option( 'show_on_front', 'page' ) && update_option( 'page_on_front', (int) $home_id );
	}

	private static function heading( $title, $text = '' ) {
		return '<!-- wp:heading {"level":2} --><h2>' . esc_html( $title ) . '</h2><!-- /wp:heading -->' . ( $text ? '<!-- wp:paragraph --><p>' . esc_html( $text ) . '</p><!-- /wp:paragraph -->' : '' );
	}

	private static function button( $label, $slug ) {
		return '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( home_url( '/' . trim( $slug, '/' ) . '/' ) ) . '">' . esc_html( $label ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons -->';
	}

	private static function basic_content( $title, $intro, $body, $cta, $slug ) {
		return '<!-- wp:group {"layout":{"type":"constrained"}} --><div class="wp-block-group">' . self::heading( $title, $intro ) . '<!-- wp:paragraph --><p>' . esc_html( $body ) . '</p><!-- /wp:paragraph -->' . self::button( $cta, $slug ) . '</div><!-- /wp:group -->';
	}

	private static function directory_content( $title, $intro, $post_type, $cta, $slug ) {
		$query = '<!-- wp:query {"query":{"perPage":12,"postType":"' . esc_attr( $post_type ) . '","order":"asc","orderBy":"title","inherit":false},"layout":{"type":"grid","columns":3}} --><div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columns":3}} --><!-- wp:post-featured-image {"isLink":true,"sizeSlug":"medium"} /--><!-- wp:post-title {"level":3,"isLink":true} /--><!-- wp:post-excerpt /--><!-- /wp:post-template --><!-- wp:query-no-results --><p>' . esc_html__( 'No listings are available yet. Check back soon or join the community to be included.', 'canadafounders' ) . '</p><!-- /wp:query-no-results --></div><!-- /wp:query -->';
		return '<!-- wp:group {"layout":{"type":"constrained"}} --><div class="wp-block-group">' . self::heading( $title, $intro ) . $query . self::button( $cta, $slug ) . '</div><!-- /wp:group -->';
	}

	private static function home_content() {
		$parts = array();
		$parts[] = '<!-- wp:cover {"dimRatio":45,"minHeight":520,"minHeightUnit":"px","contentPosition":"center left"} --><div class="wp-block-cover" style="min-height:520px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-50"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":1} --><h1>Where Canada Builds Business</h1><!-- /wp:heading --><!-- wp:paragraph --><p>A community connecting founders, entrepreneurs, business owners, investors, mentors, and professionals across Canada.</p><!-- /wp:paragraph -->' . self::button( 'Join the Community', 'join' ) . self::button( 'Explore the Network', 'community' ) . '</div></div><!-- /wp:cover -->';
		$parts[] = self::heading( 'Who We Serve', 'CanadaFounders is for people contributing to the growth of Canadian businesses and communities.' ) . '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Founders</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Builders looking for connection, insight, and momentum.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Small Business Owners</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Operators seeking practical support and opportunity.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Growing Businesses</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Teams expanding their people, partnerships, and reach.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns -->' . '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><h3>Professionals</h3><p>Advisors and specialists supporting business growth.</p></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><h3>Investors</h3><p>Capital partners seeking aligned opportunities.</p></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><h3>Mentors</h3><p>Experienced people ready to share practical guidance.</p></div><!-- /wp:column --></div><!-- /wp:columns -->';
		$parts[] = self::heading( 'What Are You Looking to Do?', 'Choose a starting point for your next conversation or opportunity.' ) . '<!-- wp:list --><ul><li>Connect with the community</li><li>Learn and access resources</li><li>Explore funding information</li><li>Discover events</li><li>Find members and partners</li></ul><!-- /wp:list -->';
		$parts[] = self::heading( 'The Community Journey', 'Connect, learn, access, grow, and give back.' ) . '<!-- wp:paragraph --><p><strong>Connect</strong> with people who understand your journey. <strong>Learn</strong> from relevant experience. <strong>Access</strong> useful resources and relationships. <strong>Grow</strong> through practical opportunity. <strong>Give Back</strong> by supporting the next builder.</p><!-- /wp:paragraph -->';
		$parts[] = self::directory_content( 'Members & Businesses', 'Explore the people and businesses building Canada’s next chapter.', 'cf_member', 'Join the Network', 'join' );
		$parts[] = self::directory_content( 'Upcoming Events', 'Discover gatherings, workshops, and conversations for the community.', 'cf_event', 'View Events', 'events' );
		$parts[] = self::heading( 'Funding & Resources Preview', 'Practical guidance and capital conversations for ambitious businesses.' ) . '<!-- wp:paragraph --><p>Explore resources that help you make informed decisions about growth, operations, and funding.</p><!-- /wp:paragraph -->' . self::button( 'Explore Funding & Resources', 'funding' );
		$parts[] = self::directory_content( 'Founder Stories', 'Real experiences from people building businesses across Canada.', 'post', 'Read Stories', 'resources' );
		$parts[] = self::directory_content( 'Partners', 'Organizations and businesses supporting the Canadian ecosystem.', 'cf_partner', 'Become a Partner', 'join' );
		$parts[] = self::heading( 'Build What’s Next with CanadaFounders', 'Connect with people who are building stronger businesses and communities across Canada.' ) . self::button( 'Join the Community', 'join' ) . self::button( 'Contact Us', 'about' );
		return '<!-- wp:group {"layout":{"type":"constrained"}} --><div class="wp-block-group">' . implode( "\n\n", $parts ) . '</div><!-- /wp:group -->';
	}
}

CanadaFounders_Demo_Importer::boot();
