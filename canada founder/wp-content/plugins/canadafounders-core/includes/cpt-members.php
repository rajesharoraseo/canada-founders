<?php
// Register Member Custom Post Type
function cf_register_member_cpt() {
    $labels = array(
        'name'                  => _x( 'Members', 'Post type general name', 'canadafounders' ),
        'singular_name'         => _x( 'Member', 'Post type singular name', 'canadafounders' ),
        'menu_name'             => _x( 'Members', 'Admin Menu text', 'canadafounders' ),
        'name_admin_bar'        => _x( 'Member', 'Add New on Toolbar', 'canadafounders' ),
        'add_new'               => __( 'Add New', 'canadafounders' ),
        'add_new_item'          => __( 'Add New Member', 'canadafounders' ),
        'new_item'              => __( 'New Member', 'canadafounders' ),
        'edit_item'             => __( 'Edit Member', 'canadafounders' ),
        'view_item'             => __( 'View Member', 'canadafounders' ),
        'all_items'             => __( 'All Members', 'canadafounders' ),
        'search_items'          => __( 'Search Members', 'canadafounders' ),
        'not_found'             => __( 'No members found.', 'canadafounders' ),
        'not_found_in_trash'    => __( 'No members found in Trash.', 'canadafounders' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'members' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'cf_member', $args );

    // Industry Taxonomy
    register_taxonomy( 'cf_industry', array( 'cf_member' ), array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => _x( 'Industries', 'taxonomy general name', 'canadafounders' ),
            'singular_name'     => _x( 'Industry', 'taxonomy singular name', 'canadafounders' ),
            'menu_name'         => __( 'Industries', 'canadafounders' ),
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ) );
}
add_action( 'init', 'cf_register_member_cpt' );

// Member Meta Boxes
function cf_add_member_meta_boxes() {
    add_meta_box(
        'cf_member_details',
        __( 'Member Details', 'canadafounders' ),
        'cf_member_details_callback',
        'cf_member',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cf_add_member_meta_boxes' );

function cf_member_details_callback( $post ) {
    wp_nonce_field( 'cf_save_member_data', 'cf_member_meta_nonce' );

    $role     = get_post_meta( $post->ID, '_cf_member_role', true );
    $company  = get_post_meta( $post->ID, '_cf_member_company', true );
    $location = get_post_meta( $post->ID, '_cf_member_location', true );
    $website  = get_post_meta( $post->ID, '_cf_member_website', true );
    $linkedin = get_post_meta( $post->ID, '_cf_member_linkedin', true );

    echo '<p><label for="cf_member_role">' . __( 'Professional Title / Role', 'canadafounders' ) . '</label><br/>';
    echo '<input type="text" id="cf_member_role" name="cf_member_role" value="' . esc_attr( $role ) . '" size="50" /></p>';

    echo '<p><label for="cf_member_company">' . __( 'Company / Business Name', 'canadafounders' ) . '</label><br/>';
    echo '<input type="text" id="cf_member_company" name="cf_member_company" value="' . esc_attr( $company ) . '" size="50" /></p>';

    echo '<p><label for="cf_member_location">' . __( 'City and Province', 'canadafounders' ) . '</label><br/>';
    echo '<input type="text" id="cf_member_location" name="cf_member_location" value="' . esc_attr( $location ) . '" size="50" /></p>';

    echo '<p><label for="cf_member_website">' . __( 'Website URL (Optional)', 'canadafounders' ) . '</label><br/>';
    echo '<input type="url" id="cf_member_website" name="cf_member_website" value="' . esc_attr( $website ) . '" size="50" /></p>';

    echo '<p><label for="cf_member_linkedin">' . __( 'LinkedIn URL (Optional)', 'canadafounders' ) . '</label><br/>';
    echo '<input type="url" id="cf_member_linkedin" name="cf_member_linkedin" value="' . esc_attr( $linkedin ) . '" size="50" /></p>';
}

function cf_save_member_meta( $post_id ) {
    if ( ! isset( $_POST['cf_member_meta_nonce'] ) || ! wp_verify_nonce( $_POST['cf_member_meta_nonce'], 'cf_save_member_data' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['cf_member_role'] ) ) {
        update_post_meta( $post_id, '_cf_member_role', sanitize_text_field( $_POST['cf_member_role'] ) );
    }
    if ( isset( $_POST['cf_member_company'] ) ) {
        update_post_meta( $post_id, '_cf_member_company', sanitize_text_field( $_POST['cf_member_company'] ) );
    }
    if ( isset( $_POST['cf_member_location'] ) ) {
        update_post_meta( $post_id, '_cf_member_location', sanitize_text_field( $_POST['cf_member_location'] ) );
    }
    if ( isset( $_POST['cf_member_website'] ) ) {
        update_post_meta( $post_id, '_cf_member_website', esc_url_raw( $_POST['cf_member_website'] ) );
    }
    if ( isset( $_POST['cf_member_linkedin'] ) ) {
        update_post_meta( $post_id, '_cf_member_linkedin', esc_url_raw( $_POST['cf_member_linkedin'] ) );
    }
}
add_action( 'save_post_cf_member', 'cf_save_member_meta' );
