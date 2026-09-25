<?php
// Register Partner Custom Post Type
function cf_register_partner_cpt() {
    $labels = array(
        'name'                  => _x( 'Partners', 'Post type general name', 'canadafounders' ),
        'singular_name'         => _x( 'Partner', 'Post type singular name', 'canadafounders' ),
        'menu_name'             => _x( 'Partners', 'Admin Menu text', 'canadafounders' ),
        'name_admin_bar'        => _x( 'Partner', 'Add New on Toolbar', 'canadafounders' ),
        'add_new'               => __( 'Add New', 'canadafounders' ),
        'add_new_item'          => __( 'Add New Partner', 'canadafounders' ),
        'new_item'              => __( 'New Partner', 'canadafounders' ),
        'edit_item'             => __( 'Edit Partner', 'canadafounders' ),
        'view_item'             => __( 'View Partner', 'canadafounders' ),
        'all_items'             => __( 'All Partners', 'canadafounders' ),
        'search_items'          => __( 'Search Partners', 'canadafounders' ),
        'not_found'             => __( 'No partners found.', 'canadafounders' ),
        'not_found_in_trash'    => __( 'No partners found in Trash.', 'canadafounders' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'partners' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 7,
        'menu_icon'          => 'dashicons-networking',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'cf_partner', $args );
    
    // Partner Category Taxonomy
    register_taxonomy( 'cf_partner_category', array( 'cf_partner' ), array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => _x( 'Partner Categories', 'taxonomy general name', 'canadafounders' ),
            'singular_name'     => _x( 'Partner Category', 'taxonomy singular name', 'canadafounders' ),
            'menu_name'         => __( 'Partner Categories', 'canadafounders' ),
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ) );
}
add_action( 'init', 'cf_register_partner_cpt' );

// Partner Meta Boxes
function cf_add_partner_meta_boxes() {
    add_meta_box(
        'cf_partner_details',
        __( 'Partner Details', 'canadafounders' ),
        'cf_partner_details_callback',
        'cf_partner',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cf_add_partner_meta_boxes' );

function cf_partner_details_callback( $post ) {
    wp_nonce_field( 'cf_save_partner_data', 'cf_partner_meta_nonce' );

    $website = get_post_meta( $post->ID, '_cf_partner_website', true );

    echo '<p><label for="cf_partner_website">' . __( 'Website URL (Optional)', 'canadafounders' ) . '</label><br/>';
    echo '<input type="url" id="cf_partner_website" name="cf_partner_website" value="' . esc_attr( $website ) . '" size="50" /></p>';
    echo '<p class="description">Upload the partner logo as the Featured Image.</p>';
}

function cf_save_partner_meta( $post_id ) {
    if ( ! isset( $_POST['cf_partner_meta_nonce'] ) || ! wp_verify_nonce( $_POST['cf_partner_meta_nonce'], 'cf_save_partner_data' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['cf_partner_website'] ) ) {
        update_post_meta( $post_id, '_cf_partner_website', esc_url_raw( $_POST['cf_partner_website'] ) );
    }
}
add_action( 'save_post_cf_partner', 'cf_save_partner_meta' );
