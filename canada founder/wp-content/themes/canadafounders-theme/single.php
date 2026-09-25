<?php
/**
 * The template for displaying all single posts
 */

get_header(); ?>

<div class="container site-content" style="max-width: 800px;">
    <?php
    while ( have_posts() ) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title page-title">', '</h1>' ); ?>
                <div class="entry-meta" style="margin-bottom: 2rem; color: #666;">
                    <?php echo get_the_date(); ?>
                </div>
            </header><!-- .entry-header -->

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="post-thumbnail" style="margin-bottom: 2rem;">
                    <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%; height:auto; border-radius: 8px;' ) ); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php the_content(); ?>
            </div><!-- .entry-content -->
        </article><!-- #post-<?php the_ID(); ?> -->
        <?php
    endwhile; // End of the loop.
    ?>
</div>

<?php get_footer(); ?>
