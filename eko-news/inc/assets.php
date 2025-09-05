<?php
/**
 * Assets and CSS variables injection.
 *
 * @package EkoNews\Inc
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue styles and inject brand variables.
 */
function eko_news_enqueue_assets() {
    $branding = eko_news_get_branding();
    $defaults = eko_news_default_branding();

    // Sanitize colors with fallbacks.
    $primary   = sanitize_hex_color( $branding['colors']['primary'] ) ?: $defaults['colors']['primary'];
    $secondary = sanitize_hex_color( $branding['colors']['secondary'] ) ?: $defaults['colors']['secondary'];
    $accent    = sanitize_hex_color( $branding['colors']['accent'] ) ?: $defaults['colors']['accent'];
    $text      = sanitize_hex_color( $branding['colors']['text'] ) ?: $defaults['colors']['text'];
    $bg        = sanitize_hex_color( $branding['colors']['background'] ) ?: $defaults['colors']['background'];

    // Sanitize URLs.
    $logo_url     = esc_url_raw( $branding['brand']['logo_url'] );
    $favicon_url  = esc_url_raw( $branding['brand']['favicon_url'] );
    $hero_default = esc_url_raw( $branding['images']['hero_default'] );
    $placeholder  = esc_url_raw( $branding['images']['placeholder'] );
    $pattern      = esc_url_raw( $branding['images']['pattern'] );

    $brand_name     = isset( $branding['brand']['name'] ) ? (string) $branding['brand']['name'] : $defaults['brand']['name'];
    $brand_name_css = '"' . addcslashes( $brand_name, "\"\\" ) . '"';

    // Layout + meta toggles.
    $container_width = isset( $branding['layout']['container_width'] ) ? (int) $branding['layout']['container_width'] : $defaults['layout']['container_width'];
    $logo_max_h      = isset( $branding['layout']['logo_max_h'] ) ? (int) $branding['layout']['logo_max_h'] : $defaults['layout']['logo_max_h'];
    $header_pad_y    = isset( $branding['layout']['header_pad_y'] ) ? (int) $branding['layout']['header_pad_y'] : $defaults['layout']['header_pad_y'];
    $archive_cols    = isset( $branding['layout']['archive_cols'] ) ? (int) $branding['layout']['archive_cols'] : $defaults['layout']['archive_cols'];
    $archive_cols    = max( 1, min( 4, $archive_cols ) );

    $dark_mode = ! empty( $branding['theme']['dark_mode'] ) ? 1 : 0;

    $css  = ":root{\n";
    $css .= "  --eko-primary: {$primary};\n";
    $css .= "  --eko-secondary: {$secondary};\n";
    $css .= "  --eko-accent: {$accent};\n";
    $css .= "  --eko-text: {$text};\n";
    $css .= "  --eko-bg: {$bg};\n";
    $css .= "  --eko-radius: 10px;\n";
    $css .= "  --eko-gap: 16px;\n";
    $css .= "  --eko-logo-url: url('" . esc_url( $logo_url ) . "');\n";
    $css .= "  --eko-favicon-url: url('" . esc_url( $favicon_url ) . "');\n";
    $css .= "  --eko-hero-default: url('" . esc_url( $hero_default ) . "');\n";
    $css .= "  --eko-placeholder: url('" . esc_url( $placeholder ) . "');\n";
    $css .= "  --eko-pattern: url('" . esc_url( $pattern ) . "');\n";
    $css .= "  --eko-brand-name: {$brand_name_css};\n";
    $css .= "  --eko-container-width: {$container_width}px;\n";
    $css .= "  --eko-logo-max-h: {$logo_max_h}px;\n";
    $css .= "  --eko-header-pad-y: {$header_pad_y}px;\n";
    $css .= "  --eko-archive-cols: {$archive_cols};\n";
    $css .= "}\n";

    // Register a style handle without a file and inject variables.
    wp_register_style( 'eko-vars', false, array(), EKO_NEWS_VERSION );
    wp_enqueue_style( 'eko-vars' );
    if ( $dark_mode ) {
        $css .= "body{background:#0b1220;color:#e5e7eb;}\n";
        $css .= ":root{--eko-bg:#0b1220;--eko-text:#e5e7eb;--eko-primary: color-mix(in srgb, var(--eko-primary) 70%, white);}\n";
    }

    wp_add_inline_style( 'eko-vars', $css );

    // Enqueue the main stylesheet, ensuring variables are available first.
    wp_enqueue_style( 'eko-style', get_stylesheet_uri(), array( 'eko-vars' ), EKO_NEWS_VERSION );
}
add_action( 'wp_enqueue_scripts', 'eko_news_enqueue_assets', 20 );
