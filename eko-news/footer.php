<footer class="site-footer">
  <div class="container" style="padding:24px 0;">
    <nav class="site-nav" aria-label="<?php esc_attr_e( 'Footer Menu', 'eko-news' ); ?>">
      <?php
      wp_nav_menu(
        array(
          'theme_location' => 'footer',
          'container'      => false,
          'fallback_cb'    => false,
          'items_wrap'     => '<ul>%3$s</ul>',
        )
      );
      ?>
    </nav>
    <small class="meta">&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></small>

    <?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) : ?>
      <div class="meta" style="margin-top:8px;">
        <?php
        $tpl = get_page_template();
        if ( ! $tpl ) {
          $tpl = get_query_template( 'index' );
        }
        echo 'EKO THEME OK — ' . esc_html( basename( (string) $tpl ) );
        ?>
      </div>
    <?php endif; ?>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
