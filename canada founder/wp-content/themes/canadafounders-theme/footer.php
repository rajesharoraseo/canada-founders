<?php
/**
 * The template for displaying the footer
 */
?>
</main><!-- #primary -->

<footer id="colophon" class="site-footer">
    <div class="container">
        <div class="footer-inner">
            <div class="footer-widget">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="CanadaFounders" style="max-height: 60px; margin-bottom: 1rem; filter: brightness(0) invert(1);">
                <p>CanadaFounders is a thriving community dedicated to empowering Canadian entrepreneurs and fueling innovation across the nation.</p>
            </div>
            
            <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                <?php dynamic_sidebar( 'footer-1' ); ?>
            <?php else : ?>
                <div class="footer-widget">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>">About Us</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/events' ) ); ?>">Events</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/members' ) ); ?>">Members Directory</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Contact</a></li>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                <?php dynamic_sidebar( 'footer-2' ); ?>
            <?php else : ?>
                <div class="footer-widget">
                    <h3>Resources</h3>
                    <ul>
                        <li><a href="<?php echo esc_url( home_url( '/funding' ) ); ?>">Funding Opportunities</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/partners' ) ); ?>">Our Partners</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Blog</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/faqs' ) ); ?>">FAQs</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="site-info">
            &copy; <?php echo date( 'Y' ); ?> CanadaFounders. All rights reserved.
        </div>
    </div>
</footer><!-- #colophon -->

<?php wp_footer(); ?>
</body>
</html>
