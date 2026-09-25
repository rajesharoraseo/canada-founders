<?php
// CanadaFounders Theme Functions

// Enqueue styles and scripts
function canadafounders_enqueue_scripts() {
    wp_enqueue_style('canadafounders-style', get_stylesheet_uri());
    wp_enqueue_style('canadafounders-theme-css', get_template_directory_uri() . '/assets/css/theme.css');
    wp_enqueue_script('canadafounders-theme-js', get_template_directory_uri() . '/assets/js/theme.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'canadafounders_enqueue_scripts');

// Theme setup
function canadafounders_setup() {
    // Add support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    // Register custom navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'canadafounders'),
    ));
}
add_action('after_setup_theme', 'canadafounders_setup');

// Custom excerpt length
function canadafounders_custom_excerpt_length($length) {
    return 20; // Change this value to set the number of words in the excerpt
}
add_filter('excerpt_length', 'canadafounders_custom_excerpt_length');

// Register widget area
function canadafounders_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'canadafounders'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'canadafounders'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'canadafounders_widgets_init');

// Include additional files
require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/demo-import.php';
?>