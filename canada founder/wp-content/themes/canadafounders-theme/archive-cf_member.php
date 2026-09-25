<?php
/**
 * The template for displaying Member Directory
 */

get_header(); ?>

<div class="container site-content">
    <header class="page-header text-center" style="margin-bottom: 3rem;">
        <h1 class="page-title">Members Directory</h1>
        <p class="lead">Discover founders, professionals, and mentors building businesses across Canada.</p>
    </header>

    <?php if ( have_posts() ) : ?>
        <div class="card-grid">
            <?php while ( have_posts() ) : the_post(); 
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
            <?php endwhile; ?>
        </div>
        
        <div class="pagination" style="margin-top: 2rem; text-align: center;">
            <?php
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( 'Previous', 'canadafounders' ),
                'next_text' => __( 'Next', 'canadafounders' ),
            ) );
            ?>
        </div>
    <?php else : ?>
        <div class="empty-state">
            <p>The network is growing. Join CanadaFounders today to be featured.</p>
            <a href="<?php echo esc_url( home_url( '/join' ) ); ?>" class="btn btn-primary" style="margin-top: 1rem;">Join CanadaFounders</a>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
