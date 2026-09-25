<?php
/**
 * Demo importer for CanadaFounders.
 *
 * Creates default pages, sets up nav menus, and assigns the home page as the
 * static front page. Includes Gutenberg block HTML for the homepage and other
 * starter pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class CanadaFounders_Demo_Importer {
    public static function boot() {
        add_action( 'admin_menu', array( __CLASS__, 'register_admin_menu' ) );
        add_action( 'admin_post_cf_import_demo_data', array( __CLASS__, 'handle_import' ) );
    }

    public static function register_admin_menu() {
        add_menu_page(
            __( 'Import Demo Data', 'canadafounders' ),
            __( 'Import Demo Data', 'canadafounders' ),
            'manage_options',
            'cf-demo-importer',
            array( __CLASS__, 'render_admin_page' ),
            'dashicons-download',
            26
        );
    }

    public static function render_admin_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $status = isset( $_GET['cf_import_status'] ) ? sanitize_key( wp_unslash( $_GET['cf_import_status'] ) ) : '';
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Import Demo Data', 'canadafounders' ); ?></h1>

            <?php if ( 'success' === $status ) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo esc_html__( 'Demo content was imported successfully.', 'canadafounders' ); ?></p>
                </div>
            <?php elseif ( 'error' === $status ) : ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo esc_html__( 'The demo content could not be imported. Please try again.', 'canadafounders' ); ?></p>
                </div>
            <?php endif; ?>

            <p>
                <?php echo esc_html__( 'This importer creates a professional starter homepage, default service pages, and a primary navigation menu. It also assigns the Home page as the static front page.', 'canadafounders' ); ?>
            </p>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'cf_demo_import_nonce', 'cf_demo_import_nonce' ); ?>
                <input type="hidden" name="action" value="cf_import_demo_data" />
                <button type="submit" class="button button-primary button-large">
                    <?php echo esc_html__( 'Import Demo Content', 'canadafounders' ); ?>
                </button>
            </form>
        </div>
        <?php
    }

    public static function handle_import() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have permission to do this.', 'canadafounders' ) );
        }

        if ( ! isset( $_POST['cf_demo_import_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cf_demo_import_nonce'] ) ), 'cf_demo_import_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed.', 'canadafounders' ) );
        }

        $result = self::run_import();
        $redirect_url = add_query_arg(
            'cf_import_status',
            $result ? 'success' : 'error',
            admin_url( 'admin.php?page=cf-demo-importer' )
        );

        wp_safe_redirect( $redirect_url );
        exit;
    }

    public static function run_import() {
        $pages = array(
            'home'      => array(
                'title'   => 'Home',
                'slug'    => 'home',
                'content' => self::build_home_page_content(),
            ),
            'community' => array(
                'title'   => 'Community',
                'slug'    => 'community',
                'content' => self::build_standard_page_content(
                    'Community',
                    'A growing network of founders, professionals, and decision-makers building stronger businesses across Canada.',
                    'CanadaFounders brings together people who want to grow, connect, and support one another. Whether you are launching a venture, expanding a business, or building strategic partnerships, the community is designed to help you find the right conversations and opportunities.',
                    'Explore the Community'
                ),
            ),
            'events'    => array(
                'title'   => 'Events',
                'slug'    => 'events',
                'content' => self::build_standard_page_content(
                    'Events',
                    'Connect at thoughtful, practical events designed for founders, operators, and business professionals.',
                    'From networking breakfasts to workshops and founder conversations, event listings create space for meaningful business relationships and actionable learning. Use this page to highlight how members can meet, learn, and build momentum together across Canada.',
                    'View Upcoming Events'
                ),
            ),
            'resources' => array(
                'title'   => 'Resources',
                'slug'    => 'resources',
                'content' => self::build_standard_page_content(
                    'Resources',
                    'Helpful guidance, tools, and practical insights for growing a strong Canadian business.',
                    'This is the place to gather practical resources for founders and small businesses, from local business context to growth planning, operational support, and market education. It is built to help members navigate the opportunities and realities of building in Canada.',
                    'Explore Resources'
                ),
            ),
            'funding'   => array(
                'title'   => 'Funding',
                'slug'    => 'funding',
                'content' => self::build_standard_page_content(
                    'Funding',
                    'Explore financing pathways, capital readiness, and strategic support for ambitious businesses.',
                    'Finding the right funding path requires clarity, timing, and the right network. This page gives founders and growing businesses a place to learn about funding conversations, readiness thinking, and the broader support ecosystem that can help move opportunities forward.',
                    'Learn About Funding'
                ),
            ),
            'members'   => array(
                'title'   => 'Members',
                'slug'    => 'members',
                'content' => self::build_standard_page_content(
                    'Members',
                    'Meet founders, operators, investors, and professionals shaping Canada’s business landscape.',
                    'Members are the heart of the community. This directory is designed to connect people who are building businesses, investing in growth, or offering expertise to support the next generation of Canadian entrepreneurs. As the network grows, this page becomes a living directory of trust and opportunity.',
                    'Join the Network'
                ),
            ),
            'partners'  => array(
                'title'   => 'Partners',
                'slug'    => 'partners',
                'content' => self::build_standard_page_content(
                    'Partners',
                    'Support the ecosystem and connect with organizations building stronger business communities.',
                    'CanadaFounders partners with organizations that share a commitment to entrepreneurship, local business growth, and practical support. This page helps highlight the people and organizations creating opportunities for founders and professionals across the country.',
                    'Become a Partner'
                ),
            ),
            'about'     => array(
                'title'   => 'About',
                'slug'    => 'about',
                'content' => self::build_standard_page_content(
                    'About',
                    'CanadaFounders is a community built for people who are building businesses and opportunities across the country.',
                    'We bring together founders, business owners, investors, mentors, and professionals to create a stronger and more connected ecosystem. Our focus is practical connection—helping people meet the right people, access useful information, and build momentum in the right communities.',
                    'Learn More'
                ),
            ),
            'join'      => array(
                'title'   => 'Join',
                'slug'    => 'join',
                'content' => self::build_standard_page_content(
                    'Join',
                    'Become part of a community focused on collaboration, growth, and meaningful opportunity.',
                    'Joining CanadaFounders is about connecting with people who care about building resilient businesses and stronger communities. Whether you are starting, scaling, advising, investing, or mentoring, there is room for you in the network.',
                    'Join the Community'
                ),
            ),
        );

        $page_ids = array();

        foreach ( $pages as $key => $page ) {
            $page_ids[ $key ] = self::create_or_update_page( $page['title'], $page['slug'], $page['content'] );
        }

        if ( empty( $page_ids['home'] ) ) {
            return false;
        }

        $menu_name = 'CanadaFounders Primary Navigation';
        $menu_obj  = wp_get_nav_menu_object( $menu_name );

        if ( ! $menu_obj ) {
            $menu_id = wp_create_nav_menu( $menu_name );
            if ( is_wp_error( $menu_id ) ) {
                return false;
            }
        } else {
            $menu_id = $menu_obj->term_id;
        }

        $menu_items = array(
            'home',
            'community',
            'events',
            'resources',
            'funding',
            'members',
            'partners',
            'about',
            'join',
        );

        $existing_menu_items = wp_get_nav_menu_items( $menu_id );
        if ( is_array( $existing_menu_items ) ) {
            foreach ( $existing_menu_items as $existing_menu_item ) {
                if ( isset( $existing_menu_item->ID ) ) {
                    wp_delete_post( (int) $existing_menu_item->ID, true );
                }
            }
        }

        foreach ( $menu_items as $item_key ) {
            if ( empty( $page_ids[ $item_key ] ) ) {
                continue;
            }

            $menu_item = array(
                'menu-item-object-id' => $page_ids[ $item_key ],
                'menu-item-object'    => 'page',
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-title'     => get_the_title( $page_ids[ $item_key ] ),
            );

            wp_update_nav_menu_item( $menu_id, 0, $menu_item );
        }

        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['menu-1'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );

        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', (int) $page_ids['home'] );

        return true;
    }

    private static function create_or_update_page( $title, $slug, $content ) {
        $page = get_page_by_path( $slug, OBJECT, 'page' );

        if ( $page ) {
            $page_id = $page->ID;
            wp_update_post(
                array(
                    'ID'           => $page_id,
                    'post_title'   => $title,
                    'post_name'    => $slug,
                    'post_content' => $content,
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                )
            );

            return $page_id;
        }

        $page_id = wp_insert_post(
            array(
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ),
            true
        );

        if ( is_wp_error( $page_id ) ) {
            return 0;
        }

        return $page_id;
    }

    private static function build_home_page_content() {
        $sections = array();

        $sections[] = self::cover_block(
            'Where Canada Builds Business',
            'A community connecting founders, entrepreneurs, business owners, investors, mentors, and professionals across Canada.',
            'Join the Community',
            '/join',
            'Explore the Network',
            '/community'
        );

        $sections[] = self::section_heading(
            'Who We Serve',
            'CanadaFounders brings together the people and organizations shaping business across the country.'
        );
        $sections[] = <<<'HTML'
<!-- wp:columns {"style":{"spacing":{"padding":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}}}} -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","color":"#e5e7eb"},"spacing":{"padding":"1.75rem"},"borderRadius":"12px"}} -->
<div class="wp-block-group" style="border:1px solid #e5e7eb;border-radius:12px;padding:1.75rem"><!-- wp:heading {"level":3} -->
<h3>Founders</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Early-stage builders seeking community, feedback, and practical momentum.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","color":"#e5e7eb"},"spacing":{"padding":"1.75rem"},"borderRadius":"12px"}} -->
<div class="wp-block-group" style="border:1px solid #e5e7eb;border-radius:12px;padding:1.75rem"><!-- wp:heading {"level":3} -->
<h3>Small Business Owners</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Operators looking for practical support, relationships, and relevant opportunities.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","color":"#e5e7eb"},"spacing":{"padding":"1.75rem"},"borderRadius":"12px"}} -->
<div class="wp-block-group" style="border:1px solid #e5e7eb;border-radius:12px;padding:1.75rem"><!-- wp:heading {"level":3} -->
<h3>Growing Businesses</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Teams scaling operations, partnerships, talent, and market visibility.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
HTML;

        $sections[] = <<<'HTML'
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","color":"#e5e7eb"},"spacing":{"padding":"1.75rem"},"borderRadius":"12px"}} -->
<div class="wp-block-group" style="border:1px solid #e5e7eb;border-radius:12px;padding:1.75rem"><!-- wp:heading {"level":3} -->
<h3>Professionals</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Advisors, leaders, and specialists who support founders and growing companies.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","color":"#e5e7eb"},"spacing":{"padding":"1.75rem"},"borderRadius":"12px"}} -->
<div class="wp-block-group" style="border:1px solid #e5e7eb;border-radius:12px;padding:1.75rem"><!-- wp:heading {"level":3} -->
<h3>Investors</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Capital partners looking for strong opportunities and aligned founders.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","color":"#e5e7eb"},"spacing":{"padding":"1.75rem"},"borderRadius":"12px"}} -->
<div class="wp-block-group" style="border:1px solid #e5e7eb;border-radius:12px;padding:1.75rem"><!-- wp:heading {"level":3} -->
<h3>Mentors</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Experienced professionals ready to share guidance and support meaningful growth.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
HTML;

        $sections[] = self::section_heading(
            'What Are You Looking to Do?',
            'Find the next right step for your business, your network, and your growth.'
        );
        $sections[] = <<<'HTML'
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","color":"#dbeafe"},"spacing":{"padding":"1.5rem"},"borderRadius":"12px","backgroundColor":"#f8fbff"}} -->
<div class="wp-block-group has-background" style="background-color:#f8fbff;border:1px solid #dbeafe;border-radius:12px;padding:1.5rem"><!-- wp:heading {"level":3} -->
<h3>Connect with the community</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Meet founders, professionals, and peers working across the Canadian ecosystem.</p>
<!-- /wp:paragraph --><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/community">Learn more</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","color":"#dbeafe"},"spacing":{"padding":"1.5rem"},"borderRadius":"12px","backgroundColor":"#f8fbff"}} -->
<div class="wp-block-group has-background" style="background-color:#f8fbff;border:1px solid #dbeafe;border-radius:12px;padding:1.5rem"><!-- wp:heading {"level":3} -->
<h3>Learn and access resources</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Explore practical guidance, business support, and insights designed for growth.</p>
<!-- /wp:paragraph --><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/resources">Explore resources</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","color":"#dbeafe"},"spacing":{"padding":"1.5rem"},"borderRadius":"12px","backgroundColor":"#f8fbff"}} -->
<div class="wp-block-group has-background" style="background-color:#f8fbff;border:1px solid #dbeafe;border-radius:12px;padding:1.5rem"><!-- wp:heading {"level":3} -->
<h3>Explore funding information</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Learn about financing paths, capital readiness, and strategic support opportunities.</p>
<!-- /wp:paragraph --><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/funding">View funding</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
HTML;

        $sections[] = <<<'HTML'
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","color":"#e5e7eb"},"spacing":{"padding":"1.5rem"},"borderRadius":"12px"}} -->
<div class="wp-block-group" style="border:1px solid #e5e7eb;border-radius:12px;padding:1.5rem"><!-- wp:heading {"level":3} -->
<h3>Discover events</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Find practical networking sessions, workshops, and conversations that matter.</p>
<!-- /wp:paragraph --><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/events">Browse events</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"width":"1px","color":"#e5e7eb"},"spacing":{"padding":"1.5rem"},"borderRadius":"12px"}} -->
<div class="wp-block-group" style="border:1px solid #e5e7eb;border-radius:12px;padding:1.5rem"><!-- wp:heading {"level":3} -->
<h3>Find members and partners</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Meet the people and organizations that can help you move faster and smarter.</p>
<!-- /wp:paragraph --><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/members">Explore members</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
HTML;

        $sections[] = self::section_heading(
            'The Community Journey',
            'A clear path for how members connect, learn, and grow together.'
        );
        $sections[] = <<<'HTML'
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"backgroundColor":"#eff6ff","borderRadius":"12px","spacing":{"padding":"1.5rem"}}} -->
<div class="wp-block-group has-background" style="background-color:#eff6ff;border-radius:12px;padding:1.5rem"><!-- wp:heading {"level":3} -->
<h3>Connect</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Meet the people, businesses, and professionals who shape Canada’s ecosystem.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"backgroundColor":"#f0fdf4","borderRadius":"12px","spacing":{"padding":"1.5rem"}}} -->
<div class="wp-block-group has-background" style="background-color:#f0fdf4;border-radius:12px;padding:1.5rem"><!-- wp:heading {"level":3} -->
<h3>Learn</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Access insight, events, and practical guidance designed to help you move forward.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"backgroundColor":"#fdf2f8","borderRadius":"12px","spacing":{"padding":"1.5rem"}}} -->
<div class="wp-block-group has-background" style="background-color:#fdf2f8;border-radius:12px;padding:1.5rem"><!-- wp:heading {"level":3} -->
<h3>Access</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Find resources, capital conversations, and support that suit your stage of growth.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"backgroundColor":"#fff7ed","borderRadius":"12px","spacing":{"padding":"1.5rem"}}} -->
<div class="wp-block-group has-background" style="background-color:#fff7ed;border-radius:12px;padding:1.5rem"><!-- wp:heading {"level":3} -->
<h3>Grow</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Build momentum through relationships, visibility, partnerships, and opportunity.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"backgroundColor":"#f5f3ff","borderRadius":"12px","spacing":{"padding":"1.5rem"}}} -->
<div class="wp-block-group has-background" style="background-color:#f5f3ff;border-radius:12px;padding:1.5rem"><!-- wp:heading {"level":3} -->
<h3>Give Back</h3>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>Support others with your experience, insight, and encouragement.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
HTML;

        $sections[] = self::section_heading(
            'Members & Businesses',
            'Explore the people and businesses building Canada’s next chapter.'
        );
        $sections[] = <<<'HTML'
<!-- wp:query {"query":{"perPage":6,"pages":0,"offset":0,"postType":"cf_member","order":"asc","orderBy":"title","inherit":false},"layout":{"type":"grid","columns":3}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columns":3}} -->
<!-- wp:post-featured-image {"isLink":true,"sizeSlug":"medium"} /-->

<!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-excerpt {"moreText":"Learn more"} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:group {"style":{"border":{"width":"1px","style":"dashed","color":"#cbd5e1"},"spacing":{"padding":"2rem"},"borderRadius":"12px","backgroundColor":"#f8fafc"}} -->
<div class="wp-block-group has-background" style="background-color:#f8fafc;border:1px dashed #cbd5e1;border-radius:12px;padding:2rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">Join the community</h3>
<!-- /wp:heading --><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">This directory is ready for real member profiles. Join the community to add your business, expertise, or story.</p>
<!-- /wp:paragraph --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/join">Join the Network</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
HTML;

        $sections[] = self::section_heading(
            'Upcoming Events',
            'Discover the gatherings, networking opportunities, and conversations shaping the community.'
        );
        $sections[] = <<<'HTML'
<!-- wp:query {"query":{"perPage":3,"pages":0,"offset":0,"postType":"cf_event","order":"asc","orderBy":"date","inherit":false},"layout":{"type":"grid","columns":3}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columns":3}} -->
<!-- wp:post-featured-image {"isLink":true,"sizeSlug":"medium"} /-->

<!-- wp:post-date {"format":"F j, Y"} /-->

<!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-excerpt {"moreText":"Learn more"} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:group {"style":{"border":{"width":"1px","style":"dashed","color":"#cbd5e1"},"spacing":{"padding":"2rem"},"borderRadius":"12px","backgroundColor":"#f8fafc"}} -->
<div class="wp-block-group has-background" style="background-color:#f8fafc;border:1px dashed #cbd5e1;border-radius:12px;padding:2rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">No events scheduled yet</h3>
<!-- /wp:heading --><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">New conversations and community gatherings are being planned. Check back soon for upcoming sessions.</p>
<!-- /wp:paragraph --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/events">View Events</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
HTML;

        $sections[] = self::section_heading(
            'Funding & Resources Preview',
            'Practical guidance, growth support, and capital conversations for ambitious businesses.'
        );
        $sections[] = <<<'HTML'
<!-- wp:group {"style":{"backgroundColor":"#0f172a","spacing":{"padding":{"top":"2.5rem","right":"2.5rem","bottom":"2.5rem","left":"2.5rem"}},"borderRadius":"18px"}} -->
<div class="wp-block-group has-background" style="background-color:#0f172a;border-radius:18px;padding:2.5rem"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"65%"} -->
<div class="wp-block-column" style="flex-basis:65%"><!-- wp:heading {"level":2,"style":{"color":{"text":"#ffffff"}}} -->
<h2 class="has-text-color" style="color:#ffffff">Funding and resources built for real business momentum</h2>
<!-- /wp:heading --><!-- wp:paragraph {"style":{"color":{"text":"#dbeafe"}}} -->
<p class="has-text-color" style="color:#dbeafe">From strategic guidance to practical capital conversations, our resources are designed to help founders and businesses move with confidence.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->
<!-- wp:column {"width":"35%"} -->
<div class="wp-block-column" style="flex-basis:35%"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"black"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-black-color has-white-background-color has-text-color has-background wp-element-button" href="/funding">Explore Funding</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
HTML;

        $sections[] = self::section_heading(
            'Founder Stories',
            'Real experiences from the people building businesses across Canada.'
        );
        $sections[] = <<<'HTML'
<!-- wp:query {"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"layout":{"type":"grid","columns":3}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columns":3}} -->
<!-- wp:post-featured-image {"isLink":true,"sizeSlug":"medium"} /-->

<!-- wp:post-date /-->

<!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:post-excerpt {"moreText":"Read more"} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->
HTML;

        $sections[] = self::section_heading(
            'Partners',
            'Organizations and businesses supporting growth across the Canadian ecosystem.'
        );
        $sections[] = <<<'HTML'
<!-- wp:query {"query":{"perPage":6,"pages":0,"offset":0,"postType":"cf_partner","order":"asc","orderBy":"title","inherit":false},"layout":{"type":"grid","columns":4}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columns":4}} -->
<!-- wp:post-featured-image {"isLink":true,"sizeSlug":"medium"} /-->

<!-- wp:post-title {"level":3,"isLink":true} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:group {"style":{"border":{"width":"1px","style":"dashed","color":"#cbd5e1"},"spacing":{"padding":"2rem"},"borderRadius":"12px","backgroundColor":"#f8fafc"}} -->
<div class="wp-block-group has-background" style="background-color:#f8fafc;border:1px dashed #cbd5e1;border-radius:12px;padding:2rem"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">Partner opportunities are open</h3>
<!-- /wp:heading --><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">This page is ready to feature partner organizations and support the broader business network.</p>
<!-- /wp:paragraph --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/partners">View Partners</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
HTML;

        $sections[] = <<<'HTML'
<!-- wp:group {"style":{"backgroundColor":"#111827","spacing":{"padding":{"top":"3rem","right":"3rem","bottom":"3rem","left":"3rem"}},"borderRadius":"20px"}} -->
<div class="wp-block-group has-background" style="background-color:#111827;border-radius:20px;padding:3rem"><!-- wp:heading {"textAlign":"center","style":{"color":{"text":"#ffffff"}}} -->
<h2 class="has-text-align-center has-text-color" style="color:#ffffff">Build what’s next with CanadaFounders</h2>
<!-- /wp:heading --><!-- wp:paragraph {"align":"center","style":{"color":{"text":"#e5e7eb"}}} -->
<p class="has-text-align-center has-text-color" style="color:#e5e7eb">Connect with people who are building stronger businesses, smarter partnerships, and better communities across Canada.</p>
<!-- /wp:paragraph --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"black"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-black-color has-white-background-color has-text-color has-background wp-element-button" href="/join">Join the Community</a></div>
<!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/about">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
HTML;

        return implode( "\n\n", $sections );
    }

    private static function build_standard_page_content( $title, $intro, $body, $cta_label ) {
        return implode(
            "\n\n",
            array(
                self::page_intro_block( $title, $intro, $cta_label ),
                self::story_block( $body ),
            )
        );
    }

    private static function page_intro_block( $title, $intro, $cta_label ) {
        return <<<HTML
<!-- wp:cover {"url":"https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80","dimRatio":25,"minHeight":260,"minHeightUnit":"px","contentPosition":"center center","align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:260px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim has-background-dim-25" style="background-image:url(https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80)"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"style":{"color":{"text":"#ffffff"}}} -->
<h1 class="has-text-align-center has-text-color" style="color:#ffffff">{$title}</h1>
<!-- /wp:heading --><!-- wp:paragraph {"align":"center","style":{"color":{"text":"#f8fafc"}}} -->
<p class="has-text-align-center has-text-color" style="color:#f8fafc">{$intro}</p>
<!-- /wp:paragraph --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/join">{$cta_label}</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
HTML;
    }

    private static function story_block( $body ) {
        return <<<HTML
<!-- wp:group {"style":{"border":{"width":"1px","color":"#e5e7eb"},"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}},"borderRadius":"16px"}} -->
<div class="wp-block-group" style="border:1px solid #e5e7eb;border-radius:16px;padding:2rem"><!-- wp:heading {"level":2} -->
<h2>Why this matters</h2>
<!-- /wp:heading --><!-- wp:paragraph -->
<p>{$body}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
HTML;
    }

    private static function section_heading( $title, $subtitle ) {
        return <<<HTML
<!-- wp:group {"style":{"spacing":{"margin":{"top":"2rem","bottom":"1rem"}}}} -->
<div class="wp-block-group" style="margin-top:2rem;margin-bottom:1rem"><!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="has-text-align-center">{$title}</h2>
<!-- /wp:heading --><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">{$subtitle}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
HTML;
    }

    private static function cover_block( $title, $intro, $primary_label, $primary_link, $secondary_label, $secondary_link ) {
        return <<<HTML
<!-- wp:cover {"url":"https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80","dimRatio":35,"minHeight":620,"minHeightUnit":"px","contentPosition":"center center","align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:620px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim has-background-dim-35" style="background-image:url(https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80)"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"left","level":1,"style":{"typography":{"fontSize":"56px","lineHeight":"1.1"},"color":{"text":"#ffffff"}}} -->
<h1 class="has-text-align-left has-text-color" style="font-size:56px;line-height:1.1;color:#ffffff">{$title}</h1>
<!-- /wp:heading --><!-- wp:paragraph {"style":{"typography":{"fontSize":"24px","lineHeight":"1.6"},"color":{"text":"#f8fafc"}}} -->
<p class="has-text-color" style="font-size:24px;line-height:1.6;color:#f8fafc">{$intro}</p>
<!-- /wp:paragraph --><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{$primary_link}">{$primary_label}</a></div>
<!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="{$secondary_link}">{$secondary_label}</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
HTML;
    }
}

CanadaFounders_Demo_Importer::boot();
