<?php
/**
 * Footer template
 *
 * @package EkoNews
 */
?>

<footer class="site-footer">
    <div class="container">
        <nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer Menu', 'eko-news' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'footer',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'depth'          => 1,
                )
            );
            ?>
        </nav>
        <p class="meta" style="margin-top: var(--eko-gap);">
            &copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
        </p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

