<?php
/**
 * Single Member Template
 */

get_header(); ?>

<div class="container site-content" style="max-width: 800px;">
    <?php while ( have_posts() ) : the_post(); 
        $role = get_post_meta( get_the_ID(), '_cf_member_role', true );
        $company = get_post_meta( get_the_ID(), '_cf_member_company', true );
        $location = get_post_meta( get_the_ID(), '_cf_member_location', true );
        $website = get_post_meta( get_the_ID(), '_cf_member_website', true );
        $linkedin = get_post_meta( get_the_ID(), '_cf_member_linkedin', true );
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div style="margin-bottom: 2rem;">
                <a href="<?php echo get_post_type_archive_link('cf_member'); ?>">&larr; Back to Directory</a>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 2rem; align-items: flex-start; margin-bottom: 3rem;">
                <div style="flex: 0 0 200px;">
                    <?php if ( has_post_thumbnail() ) {
                        the_post_thumbnail('cf-member-thumb', array('style' => 'width:100%; height:auto; border-radius:50%;'));
                    } else {
                        echo '<div style="width:200px; height:200px; border-radius:50%; background:#eee;"></div>';
                    } ?>
                </div>
                
                <div style="flex: 1; min-width: 300px;">
                    <?php the_title( '<h1 class="entry-title page-title" style="margin-bottom:0.5rem; display:block; border-bottom:none; padding-bottom:0;">', '</h1>' ); ?>
                    
                    <?php if($role) echo '<div style="color: var(--cf-blue); font-size: 1.2rem; font-weight: 500; margin-bottom: 0.5rem;">' . esc_html($role) . '</div>'; ?>
                    <?php if($company) echo '<div style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0.5rem;">' . esc_html($company) . '</div>'; ?>
                    <?php if($location) echo '<div style="color: #666; margin-bottom: 1.5rem;">' . esc_html($location) . '</div>'; ?>

                    <div style="display: flex; gap: 1rem;">
                        <?php if($website) echo '<a href="'.esc_url($website).'" target="_blank" class="btn btn-secondary" style="padding: 0.5rem 1rem;">Website</a>'; ?>
                        <?php if($linkedin) echo '<a href="'.esc_url($linkedin).'" target="_blank" class="btn btn-primary" style="padding: 0.5rem 1rem;">LinkedIn</a>'; ?>
                    </div>
                </div>
            </div>

            <div class="entry-content">
                <h2 style="border-bottom: 1px solid var(--cf-border); padding-bottom: 0.5rem; margin-bottom: 1.5rem;">About</h2>
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
