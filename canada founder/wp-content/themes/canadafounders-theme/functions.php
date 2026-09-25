<?php
/**
 * CanadaFounders Theme functions and definitions
 */

if ( ! function_exists( 'canadafounders_setup' ) ) :
    function canadafounders_setup() {
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title.
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support( 'post-thumbnails' );
        
        // Custom Image Sizes
        add_image_size( 'cf-member-thumb', 300, 300, true );
        add_image_size( 'cf-event-card', 600, 400, true );

        // Register navigation menus
        register_nav_menus(
            array(
                'menu-1' => esc_html__( 'Primary', 'canadafounders' ),
                'footer' => esc_html__( 'Footer Menu', 'canadafounders' ),
            )
        );

        // Switch default core markup to output valid HTML5.
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Add support for core custom logo.
        add_theme_support(
            'custom-logo',
            array(
                'height'      => 100,
                'width'       => 400,
                'flex-width'  => true,
                'flex-height' => true,
            )
        );
        
        // Block Editor Support
        add_theme_support( 'align-wide' );
        add_theme_support( 'wp-block-styles' );
    }
endif;
add_action( 'after_setup_theme', 'canadafounders_setup' );

/**
 * Enqueue scripts and styles.
 */
function canadafounders_scripts() {
    wp_enqueue_style( 'canadafounders-style', get_stylesheet_uri(), array(), '1.0.0' );

    wp_enqueue_script( 'canadafounders-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'canadafounders_scripts' );

/**
 * Register widget area.
 */
function canadafounders_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Column 1', 'canadafounders' ),
            'id'            => 'footer-1',
            'description'   => esc_html__( 'Add widgets here.', 'canadafounders' ),
            'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Column 2', 'canadafounders' ),
            'id'            => 'footer-2',
            'description'   => esc_html__( 'Add widgets here.', 'canadafounders' ),
            'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'canadafounders_widgets_init' );
