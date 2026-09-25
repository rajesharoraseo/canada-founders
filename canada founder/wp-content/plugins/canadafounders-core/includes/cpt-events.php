<?php
// Register Event Custom Post Type
function cf_register_event_cpt() {
    $labels = array(
        'name'                  => _x( 'Events', 'Post type general name', 'canadafounders' ),
        'singular_name'         => _x( 'Event', 'Post type singular name', 'canadafounders' ),
        'menu_name'             => _x( 'Events', 'Admin Menu text', 'canadafounders' ),
        'name_admin_bar'        => _x( 'Event', 'Add New on Toolbar', 'canadafounders' ),
        'add_new'               => __( 'Add New', 'canadafounders' ),
        'add_new_item'          => __( 'Add New Event', 'canadafounders' ),
        'new_item'              => __( 'New Event', 'canadafounders' ),
        'edit_item'             => __( 'Edit Event', 'canadafounders' ),
        'view_item'             => __( 'View Event', 'canadafounders' ),
        'all_items'             => __( 'All Events', 'canadafounders' ),
        'search_items'          => __( 'Search Events', 'canadafounders' ),
        'not_found'             => __( 'No events found.', 'canadafounders' ),
        'not_found_in_trash'    => __( 'No events found in Trash.', 'canadafounders' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'events' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'       => true, // Enable Gutenberg
    );

    register_post_type( 'cf_event', $args );

    // Register Category Taxonomy for Events
    register_taxonomy( 'cf_event_category', array( 'cf_event' ), array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => _x( 'Event Categories', 'taxonomy general name', 'canadafounders' ),
            'singular_name'     => _x( 'Event Category', 'taxonomy singular name', 'canadafounders' ),
            'search_items'      => __( 'Search Event Categories', 'canadafounders' ),
            'all_items'         => __( 'All Event Categories', 'canadafounders' ),
            'parent_item'       => __( 'Parent Event Category', 'canadafounders' ),
            'parent_item_colon' => __( 'Parent Event Category:', 'canadafounders' ),
            'edit_item'         => __( 'Edit Event Category', 'canadafounders' ),
            'update_item'       => __( 'Update Event Category', 'canadafounders' ),
            'add_new_item'      => __( 'Add New Event Category', 'canadafounders' ),
            'new_item_name'     => __( 'New Event Category Name', 'canadafounders' ),
            'menu_name'         => __( 'Event Categories', 'canadafounders' ),
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'event-category' ),
        'show_in_rest'      => true,
    ) );
}
add_action( 'init', 'cf_register_event_cpt' );

// Event Meta Boxes
function cf_add_event_meta_boxes() {
    add_meta_box(
        'cf_event_details',
        __( 'Event Details', 'canadafounders' ),
        'cf_event_details_callback',
        'cf_event',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cf_add_event_meta_boxes' );

function cf_event_details_callback( $post ) {
    wp_nonce_field( 'cf_save_event_data', 'cf_event_meta_nonce' );

    $event_date = get_post_meta( $post->ID, '_cf_event_date', true );
    $start_time = get_post_meta( $post->ID, '_cf_event_start_time', true );
    $end_time   = get_post_meta( $post->ID, '_cf_event_end_time', true );
    $venue      = get_post_meta( $post->ID, '_cf_event_venue', true );
    $reg_url    = get_post_meta( $post->ID, '_cf_event_registration_url', true );

    echo '<p><label for="cf_event_date">' . __( 'Event Date (YYYY-MM-DD)', 'canadafounders' ) . '</label><br/>';
    echo '<input type="date" id="cf_event_date" name="cf_event_date" value="' . esc_attr( $event_date ) . '" size="25" /></p>';

    echo '<p><label for="cf_event_start_time">' . __( 'Start Time (e.g., 9:00 AM)', 'canadafounders' ) . '</label><br/>';
    echo '<input type="text" id="cf_event_start_time" name="cf_event_start_time" value="' . esc_attr( $start_time ) . '" size="25" /></p>';

    echo '<p><label for="cf_event_end_time">' . __( 'End Time (e.g., 5:00 PM)', 'canadafounders' ) . '</label><br/>';
    echo '<input type="text" id="cf_event_end_time" name="cf_event_end_time" value="' . esc_attr( $end_time ) . '" size="25" /></p>';

    echo '<p><label for="cf_event_venue">' . __( 'Venue (or "Online")', 'canadafounders' ) . '</label><br/>';
    echo '<input type="text" id="cf_event_venue" name="cf_event_venue" value="' . esc_attr( $venue ) . '" size="50" /></p>';

    echo '<p><label for="cf_event_registration_url">' . __( 'Registration URL', 'canadafounders' ) . '</label><br/>';
    echo '<input type="url" id="cf_event_registration_url" name="cf_event_registration_url" value="' . esc_attr( $reg_url ) . '" size="50" /></p>';
}

function cf_save_event_meta( $post_id ) {
    if ( ! isset( $_POST['cf_event_meta_nonce'] ) || ! wp_verify_nonce( $_POST['cf_event_meta_nonce'], 'cf_save_event_data' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['cf_event_date'] ) ) {
        update_post_meta( $post_id, '_cf_event_date', sanitize_text_field( $_POST['cf_event_date'] ) );
    }
    if ( isset( $_POST['cf_event_start_time'] ) ) {
        update_post_meta( $post_id, '_cf_event_start_time', sanitize_text_field( $_POST['cf_event_start_time'] ) );
    }
    if ( isset( $_POST['cf_event_end_time'] ) ) {
        update_post_meta( $post_id, '_cf_event_end_time', sanitize_text_field( $_POST['cf_event_end_time'] ) );
    }
    if ( isset( $_POST['cf_event_venue'] ) ) {
        update_post_meta( $post_id, '_cf_event_venue', sanitize_text_field( $_POST['cf_event_venue'] ) );
    }
    if ( isset( $_POST['cf_event_registration_url'] ) ) {
        update_post_meta( $post_id, '_cf_event_registration_url', esc_url_raw( $_POST['cf_event_registration_url'] ) );
    }
}
add_action( 'save_post_cf_event', 'cf_save_event_meta' );
