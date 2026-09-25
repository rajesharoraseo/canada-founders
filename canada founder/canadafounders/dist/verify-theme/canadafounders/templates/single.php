<?php
get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php
    while ( have_posts() ) :
        the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </header>

            <div class="entry-content">
                <?php
                the_content();
                ?>
            </div>

            <footer class="entry-footer">
                <?php
                // Display post meta information if needed
                ?>
            </footer>
        </article>

    <?php
    endwhile; // End of the loop.
    ?>

</main>

<?php
get_footer();