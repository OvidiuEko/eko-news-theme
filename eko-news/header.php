<?php
/**
 * Header template
 *
 * @package EkoNews
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    // Optional favicon from theme options.
    $branding = function_exists( 'eko_news_get_branding' ) ? eko_news_get_branding() : array();
    $favicon  = isset( $branding['brand']['favicon_url'] ) ? esc_url( $branding['brand']['favicon_url'] ) : '';
    if ( $favicon ) :
        ?>
        <link rel="icon" href="<?php echo $favicon; // already escaped above ?>" />
    <?php endif; ?>
    <?php wp_head(); ?>
    <style>
        /* Minimal layout helpers using CSS variables */
        .site-header { background: var(--eko-bg); border-bottom: 1px solid rgba(0,0,0,.06); }
        .header-inner { display:flex; align-items:center; justify-content:space-between; padding: var(--eko-gap) 0; }
        .site-title { color: var(--eko-text); font-weight: 700; font-size: 1.25rem; }
        .primary-nav ul { list-style:none; margin:0; padding:0; display:flex; gap: calc(var(--eko-gap) / 2); flex-wrap: wrap; }
        .primary-nav a { color: var(--eko-text); padding: 8px 12px; border-radius: var(--eko-radius); }
        .primary-nav a:hover, .primary-nav .current-menu-item > a { color: #fff; background: var(--eko-primary); }

        .grid { display:grid; gap: var(--eko-gap); }
        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        @media (max-width: 960px) { .grid-3 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) { .grid-3 { grid-template-columns: 1fr; } }

        .card { background: var(--eko-bg); border: 1px solid rgba(0,0,0,.08); border-radius: var(--eko-radius); overflow: hidden; }
        .card-body { padding: calc(var(--eko-gap)); }
        .card h2, .card h3 { margin: 0 0 8px; line-height: 1.25; }
        .card a { color: var(--eko-text); }
        .card a:hover { color: var(--eko-primary); }
        .meta { color: rgba(0,0,0,.6); font-size: .9rem; }
        .badge { display:inline-block; padding: 2px 8px; border-radius: 999px; background: rgba(0,0,0,.06); color: var(--eko-text); font-size: .8rem; }

        .ratio-16x9 { position: relative; width: 100%; padding-top: 56.25%; overflow: hidden; background: rgba(0,0,0,.04); }
        .ratio-16x9 > img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .img-fluid { max-width: 100%; height: auto; display:block; }

        .site-footer { margin-top: calc(var(--eko-gap) * 2); padding: calc(var(--eko-gap) * 2) 0; border-top: 1px solid rgba(0,0,0,.08); color: var(--eko-text); }
        .footer-nav ul { list-style:none; margin:0; padding:0; display:flex; gap: calc(var(--eko-gap) / 2); flex-wrap: wrap; }
        .footer-nav a { color: var(--eko-text); }
        .footer-nav a:hover { color: var(--eko-primary); }
    </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="container header-inner">
        <div class="brand">
            <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
            <?php endif; ?>
        </div>
        <nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary Menu', 'eko-news' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'depth'          => 2,
                )
            );
            ?>
        </nav>
    </div>
</header>
