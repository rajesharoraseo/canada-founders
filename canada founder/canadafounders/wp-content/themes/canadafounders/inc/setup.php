<?php
// Setup functions for the CanadaFounders theme

if ( ! function_exists( 'canadafounders_setup' ) ) :
    function canadafounders_setup() {
        // Add support for various theme features
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo', array(
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
        ) );

        // Register custom navigation menus
        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'canadafounders' ),
        ) );

        // Load theme textdomain for translations
        load_theme_textdomain( 'canadafounders', get_template_directory() . '/languages' );
    }
endif;
add_action( 'after_setup_theme', 'canadafounders_setup' );

// Register custom post types, taxonomies, or other initialization tasks here
function canadafounders_custom_post_types() {
    // Example: Register a custom post type for Members
    register_post_type( 'member', array(
        'labels' => array(
            'name' => __( 'Members', 'canadafounders' ),
            'singular_name' => __( 'Member', 'canadafounders' ),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor', 'thumbnail' ),
        'rewrite' => array( 'slug' => 'members' ),
    ) );

    // Register other custom post types as needed
}
add_action( 'init', 'canadafounders_custom_post_types' );
?>