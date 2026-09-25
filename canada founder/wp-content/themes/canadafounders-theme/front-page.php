<?php
/**
 * The template for the homepage
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1>Where Canada Builds Business.</h1>
        <p class="lead">A community connecting founders, entrepreneurs, business owners, investors, mentors and professionals across Canada.</p>
        <div class="hero-cta">
            <a href="<?php echo esc_url( home_url( '/join' ) ); ?>" class="btn btn-primary">Join CanadaFounders</a>
            <a href="<?php echo esc_url( home_url( '/community' ) ); ?>" class="btn btn-secondary">Explore the Community</a>
        </div>
    </div>
</section>

<!-- Audiences Section -->
<section class="section">
    <div class="container text-center">
        <h2 style="margin-bottom: 0.5rem;">Built for People Building Business in Canada</h2>
        <p style="color: #666; margin-bottom: 2rem;">Whether you're just starting out or scaling up, CanadaFounders is built around the people who make it happen.</p>
        
        <div class="card-grid">
            <div class="card">
                <h3>Founders & Entrepreneurs</h3>
                <p>Building something new? Find the connections, resources and support to move your business forward.</p>
            </div>
            <div class="card">
                <h3>Small & Growing Businesses</h3>
                <p>Access useful tools, professional connections and funding information to keep your business growing.</p>
            </div>
            <div class="card">
                <h3>Professionals</h3>
                <p>Lawyers, accountants, consultants and advisors who work with Canadian businesses and want to be found.</p>
            </div>
            <div class="card">
                <h3>Investors & Capital Partners</h3>
                <p>Discover Canadian businesses and founders looking for investment, partnership and growth capital.</p>
            </div>
        </div>
    </div>
</section>

<!-- Tasks Section -->
<section class="section section-light">
    <div class="container">
        <h2 class="text-center" style="margin-bottom: 2rem;">What Are You Looking to Do?</h2>
        <div class="card-grid">
            <div class="card">
                <h3><a href="<?php echo esc_url( home_url( '/members' ) ); ?>">Make Business Connections &rarr;</a></h3>
                <p>Meet founders, professionals, mentors and business owners who can help you move forward.</p>
            </div>
            <div class="card">
                <h3><a href="<?php echo esc_url( home_url( '/funding' ) ); ?>">Explore Funding & Grants &rarr;</a></h3>
                <p>Find useful information about government grants, business loans, startup funding and investor readiness.</p>
            </div>
            <div class="card">
                <h3><a href="<?php echo esc_url( home_url( '/events' ) ); ?>">Attend Events &rarr;</a></h3>
                <p>Discover workshops, networking events and opportunities to connect with Canada's business community.</p>
            </div>
        </div>
    </div>
</section>

<!-- Journey Section -->
<section class="section">
    <div class="container">
        <h2 class="text-center">The CanadaFounders Journey</h2>
        <p class="text-center" style="margin-bottom: 2rem;">More Than a Website. A Network You Can Use.</p>
        
        <div class="card-grid">
            <div class="card">
                <h3>01. Connect</h3>
                <p>Meet founders, professionals, mentors and business owners across Canada.</p>
            </div>
            <div class="card">
                <h3>02. Learn</h3>
                <p>Access knowledge, events, insights and practical resources.</p>
            </div>
            <div class="card">
                <h3>03. Access</h3>
                <p>Discover funding information, opportunities, and professional support.</p>
            </div>
            <div class="card">
                <h3>04. Grow</h3>
                <p>Build relationships, skills, visibility and real business opportunities.</p>
            </div>
        </div>
    </div>
</section>

<!-- Dynamic Events Section -->
<section class="section section-light">
    <div class="container">
        <h2 class="text-center">Upcoming Events</h2>
        
        <?php
        $today = date('Y-m-d');
        $events_query = new WP_Query( array(
            'post_type'      => 'cf_event',
            'posts_per_page' => 3,
            'meta_key'       => '_cf_event_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'     => '_cf_event_date',
                    'value'   => $today,
                    'compare' => '>=',
                    'type'    => 'DATE'
                )
            )
        ) );

        if ( $events_query->have_posts() ) : ?>
            <div class="card-grid">
                <?php while ( $events_query->have_posts() ) : $events_query->the_post(); 
                    $date = get_post_meta( get_the_ID(), '_cf_event_date', true );
                    $venue = get_post_meta( get_the_ID(), '_cf_event_venue', true );
                ?>
                    <div class="card event-card">
                        <?php if ( has_post_thumbnail() ) {
                            the_post_thumbnail('cf-event-card', array('style' => 'width:100%; height:auto; margin-bottom:1rem; border-radius:4px;'));
                        } ?>
                        <div class="event-date"><?php echo $date ? date('F j, Y', strtotime($date)) : 'TBA'; ?></div>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="event-meta">
                            <?php if($venue) echo esc_html($venue); ?>
                        </div>
                        <?php the_excerpt(); ?>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <div class="text-center" style="margin-top: 2rem;">
                <a href="<?php echo esc_url( home_url( '/events' ) ); ?>" class="btn btn-secondary">View All Events</a>
            </div>
        <?php else : ?>
            <div class="empty-state">
                <p>New events are being planned. Check back soon to see what's coming up.</p>
                <a href="<?php echo esc_url( home_url( '/events' ) ); ?>">Go to Events Page</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Dynamic Members Section -->
<section class="section">
    <div class="container">
        <h2 class="text-center">Meet the Network</h2>
        <p class="text-center" style="margin-bottom: 2rem;">Discover founders, professionals, and mentors building businesses across Canada.</p>
        
        <?php
        $members_query = new WP_Query( array(
            'post_type'      => 'cf_member',
            'posts_per_page' => 4,
            'orderby'        => 'rand' // Show random members on homepage
        ) );

        if ( $members_query->have_posts() ) : ?>
            <div class="card-grid">
                <?php while ( $members_query->have_posts() ) : $members_query->the_post(); 
                    $role = get_post_meta( get_the_ID(), '_cf_member_role', true );
                    $company = get_post_meta( get_the_ID(), '_cf_member_company', true );
                    $location = get_post_meta( get_the_ID(), '_cf_member_location', true );
                ?>
                    <div class="card member-card">
                        <?php if ( has_post_thumbnail() ) {
                            the_post_thumbnail('cf-member-thumb');
                        } else {
                            echo '<div style="width:120px; height:120px; border-radius:50%; background:#eee; margin: 0 auto 1rem;"></div>';
                        } ?>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php if($role) echo '<div class="member-role">' . esc_html($role) . '</div>'; ?>
                        <?php if($company) echo '<div class="member-company">' . esc_html($company) . '</div>'; ?>
                        <?php if($location) echo '<div class="member-location">' . esc_html($location) . '</div>'; ?>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <div class="text-center" style="margin-top: 2rem;">
                <a href="<?php echo esc_url( home_url( '/members' ) ); ?>" class="btn btn-secondary">View Member Directory</a>
            </div>
        <?php else : ?>
            <div class="empty-state">
                <p>The network is growing. Join CanadaFounders today to be featured.</p>
                <a href="<?php echo esc_url( home_url( '/join' ) ); ?>" class="btn btn-primary" style="margin-top: 1rem;">Join CanadaFounders</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Dynamic Partners Section -->
<section class="section section-light">
    <div class="container text-center">
        <h2>Our Partners</h2>
        
        <?php
        $partners_query = new WP_Query( array(
            'post_type'      => 'cf_partner',
            'posts_per_page' => -1,
        ) );

        if ( $partners_query->have_posts() ) : ?>
            <div class="partner-grid">
                <?php while ( $partners_query->have_posts() ) : $partners_query->the_post(); 
                    $website = get_post_meta( get_the_ID(), '_cf_partner_website', true );
                ?>
                    <div class="partner-item">
                        <?php if ( has_post_thumbnail() ) {
                            if($website) echo '<a href="'.esc_url($website).'" target="_blank">';
                            the_post_thumbnail('medium', array('class' => 'partner-logo'));
                            if($website) echo '</a>';
                        } else {
                            echo '<strong>' . get_the_title() . '</strong>';
                        } ?>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div class="empty-state">
                <p>Become a partner and support Canada's business builders.</p>
                <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Contact Us</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Final CTA Section -->
<section class="section text-center" style="background: var(--cf-dark); color: white;">
    <div class="container">
        <h2 style="color: white; font-size: 2.5rem; margin-bottom: 1rem;">You Don't Have to Build Alone.</h2>
        <p class="lead" style="margin-bottom: 2rem; color: #ccc;">Build your business. Build your network. Build Canada.</p>
        <div class="hero-cta">
            <a href="<?php echo esc_url( home_url( '/join' ) ); ?>" class="btn btn-primary">Join CanadaFounders</a>
            <a href="<?php echo esc_url( home_url( '/community' ) ); ?>" class="btn btn-secondary" style="border-color: white; color: white;">Explore the Community</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
