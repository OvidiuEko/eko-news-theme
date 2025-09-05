<?php ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>
<header class="site-header">
  <div class="container wrap">
    <a class="site-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <?php if ( has_custom_logo() ) { the_custom_logo(); } else { ?>
        <span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
      <?php } ?>
    </a>
    <nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'eko-news' ); ?>">
      <?php
      wp_nav_menu(
        array(
          'theme_location' => 'primary',
          'container'      => false,
          'fallback_cb'    => false,
          'items_wrap'     => '<ul>%3$s</ul>',
        )
      );
      ?>
    </nav>
  </div>
</header>
