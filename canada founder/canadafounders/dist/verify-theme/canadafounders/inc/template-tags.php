<?php
/**
 * Template tags for the CanadaFounders theme.
 *
 * This file contains helper functions for rendering content dynamically
 * throughout the theme.
 */

/**
 * Display the site title.
 *
 * @return void
 */
function canadafounders_site_title() {
    echo esc_html(get_bloginfo('name'));
}

/**
 * Display the site description.
 *
 * @return void
 */
function canadafounders_site_description() {
    echo esc_html(get_bloginfo('description'));
}

/**
 * Display a list of members.
 *
 * @return void
 */
function canadafounders_display_members() {
    $args = array(
        'post_type' => 'member',
        'posts_per_page' => -1,
    );
    $members = new WP_Query($args);
    
    if ($members->have_posts()) {
        echo '<ul class="members-list">';
        while ($members->have_posts()) {
            $members->the_post();
            echo '<li>' . esc_html(get_the_title()) . '</li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<p>No members found.</p>';
    }
}

/**
 * Display a list of upcoming events.
 *
 * @return void
 */
function canadafounders_display_upcoming_events() {
    $args = array(
        'post_type' => 'event',
        'posts_per_page' => 5,
        'meta_query' => array(
            array(
                'key' => 'event_date',
                'value' => current_time('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE',
            ),
        ),
        'orderby' => 'event_date',
        'order' => 'ASC',
    );
    $events = new WP_Query($args);
    
    if ($events->have_posts()) {
        echo '<ul class="events-list">';
        while ($events->have_posts()) {
            $events->the_post();
            echo '<li>' . esc_html(get_the_title()) . ' - ' . esc_html(get_post_meta(get_the_ID(), 'event_date', true)) . '</li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<p>No upcoming events found.</p>';
    }
}

/**
 * Display the partners.
 *
 * @return void
 */
function canadafounders_display_partners() {
    $args = array(
        'post_type' => 'partner',
        'posts_per_page' => -1,
    );
    $partners = new WP_Query($args);
    
    if ($partners->have_posts()) {
        echo '<ul class="partners-list">';
        while ($partners->have_posts()) {
            $partners->the_post();
            echo '<li>' . esc_html(get_the_title()) . '</li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<p>No partners found.</p>';
    }
}
?>