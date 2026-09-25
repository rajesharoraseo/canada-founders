<?php
/**
 * The template for displaying Event Archives
 */

get_header(); ?>

<div class="container site-content">
    <header class="page-header text-center" style="margin-bottom: 3rem;">
        <h1 class="page-title">Events</h1>
        <p class="lead">Discover workshops, networking events and opportunities to connect with Canada's business community.</p>
    </header>

    <?php
    $today = date('Y-m-d');
    
    // Upcoming Events
    $upcoming_events = new WP_Query( array(
        'post_type'      => 'cf_event',
        'posts_per_page' => -1,
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
    ?>
    
    <h2 style="margin-bottom: 1.5rem;">Upcoming Events</h2>
    <?php if ( $upcoming_events->have_posts() ) : ?>
        <div class="card-grid" style="margin-bottom: 4rem;">
            <?php while ( $upcoming_events->have_posts() ) : $upcoming_events->the_post(); 
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
    <?php else : ?>
        <div class="empty-state" style="margin-bottom: 4rem;">
            <p>New events are being planned. Check back soon to see what's coming up.</p>
        </div>
    <?php endif; ?>

    <?php
    // Past Events
    $past_events = new WP_Query( array(
        'post_type'      => 'cf_event',
        'posts_per_page' => 6,
        'meta_key'       => '_cf_event_date',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
        'meta_query'     => array(
            array(
                'key'     => '_cf_event_date',
                'value'   => $today,
                'compare' => '<',
                'type'    => 'DATE'
            )
        )
    ) );
    ?>

    <?php if ( $past_events->have_posts() ) : ?>
        <h2 style="margin-bottom: 1.5rem;">Past Events</h2>
        <div class="card-grid" style="opacity: 0.8;">
            <?php while ( $past_events->have_posts() ) : $past_events->the_post(); 
                $date = get_post_meta( get_the_ID(), '_cf_event_date', true );
                $venue = get_post_meta( get_the_ID(), '_cf_event_venue', true );
            ?>
                <div class="card event-card">
                    <div class="event-date" style="color: #666;"><?php echo $date ? date('F j, Y', strtotime($date)) : 'TBA'; ?></div>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="event-meta">
                        <?php if($venue) echo esc_html($venue); ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
