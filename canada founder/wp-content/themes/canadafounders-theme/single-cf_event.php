<?php
/**
 * Single Event Template
 */

get_header(); ?>

<div class="container site-content" style="max-width: 800px;">
    <?php while ( have_posts() ) : the_post(); 
        $date = get_post_meta( get_the_ID(), '_cf_event_date', true );
        $start_time = get_post_meta( get_the_ID(), '_cf_event_start_time', true );
        $end_time = get_post_meta( get_the_ID(), '_cf_event_end_time', true );
        $venue = get_post_meta( get_the_ID(), '_cf_event_venue', true );
        $reg_url = get_post_meta( get_the_ID(), '_cf_event_registration_url', true );
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <a href="<?php echo get_post_type_archive_link('cf_event'); ?>" style="display:inline-block; margin-bottom: 1rem;">&larr; Back to Events</a>
                <?php the_title( '<h1 class="entry-title page-title" style="display:block;">', '</h1>' ); ?>
            </header>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail" style="margin-bottom: 2rem;">
                    <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%; height:auto; border-radius: 8px;' ) ); ?>
                </div>
            <?php endif; ?>

            <div style="background: var(--cf-light-bg); padding: 2rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid var(--cf-border);">
                <ul style="list-style: none; margin: 0; padding: 0;">
                    <?php if($date) : ?>
                        <li style="margin-bottom: 0.5rem;"><strong>Date:</strong> <?php echo date('F j, Y', strtotime($date)); ?></li>
                    <?php endif; ?>
                    
                    <?php if($start_time || $end_time) : ?>
                        <li style="margin-bottom: 0.5rem;"><strong>Time:</strong> <?php echo esc_html($start_time); ?> <?php if($end_time) echo ' - ' . esc_html($end_time); ?></li>
                    <?php endif; ?>

                    <?php if($venue) : ?>
                        <li style="margin-bottom: 0.5rem;"><strong>Location:</strong> <?php echo esc_html($venue); ?></li>
                    <?php endif; ?>
                </ul>

                <?php if($reg_url) : ?>
                    <div style="margin-top: 1.5rem;">
                        <a href="<?php echo esc_url($reg_url); ?>" target="_blank" class="btn btn-primary">Register Now</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
