<?php
/**
 * The main template file
 */

get_header(); ?>

<div class="container site-content">
    <?php if ( is_home() && ! is_front_page() ) : ?>
        <header class="page-header">
            <h1 class="page-title"><?php single_post_title(); ?></h1>
        </header>
    <?php endif; ?>

    <?php if ( have_posts() ) : ?>
        <div class="card-grid">
            <?php
            while ( have_posts() ) :
                the_post();
                ?>
                <div class="card">
                    <?php if ( has_post_thumbnail() ) {
                        the_post_thumbnail('medium', array('style' => 'width:100%; height:auto; margin-bottom:1rem; border-radius:4px;'));
                    } ?>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-meta" style="font-size:0.85rem; color:#666; margin-bottom:1rem;">
                        <?php echo get_the_date(); ?>
                    </div>
                    <?php the_excerpt(); ?>
                </div>
                <?php
            endwhile;
            ?>
        </div>
        
        <div class="pagination" style="margin-top: 2rem; text-align: center;">
            <?php
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( 'Previous', 'canadafounders' ),
                'next_text' => __( 'Next', 'canadafounders' ),
            ) );
            ?>
        </div>

    <?php else : ?>
        <div class="empty-state">
            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'canadafounders' ); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
