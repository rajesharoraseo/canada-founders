<?php
/**
 * The header for our theme
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="masthead" class="site-header">
    <div class="container header-inner">
        <div class="site-branding">
            <?php
            if ( has_custom_logo() ) {
                the_custom_logo();
            } else {
                ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo( 'name' ); ?>">
                </a>
                <?php
            }
            ?>
        </div><!-- .site-branding -->

        <nav id="site-navigation" class="main-navigation">
            <button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false">&#9776;</button>
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'menu-1',
                    'menu_id'        => 'primary-menu',
                    'fallback_cb'    => false, // Do not show default page list
                )
            );
            ?>
            <div class="header-cta">
                <a href="<?php echo esc_url( home_url( '/join' ) ); ?>" class="btn btn-primary">Join</a>
            </div>
        </nav><!-- #site-navigation -->
    </div>
</header><!-- #masthead -->

<main id="primary" class="site-main">
