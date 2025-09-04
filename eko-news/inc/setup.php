<?php
/**
 * Theme setup and defaults.
 *
 * @package EkoNews\Inc
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default branding options used as fallbacks.
 *
 * @return array
 */
function eko_news_default_branding() {
    return array(
        'brand'  => array(
            'name'        => 'Eko News',
            'logo_url'    => '',
            'favicon_url' => '',
        ),
        'colors' => array(
            'primary'    => '#0a7cff',
            'secondary'  => '#00b894',
            'accent'     => '#ffc107',
            'text'       => '#222222',
            'background' => '#ffffff',
        ),
        'images' => array(
            'hero_default' => '',
            'placeholder'  => '',
            'pattern'      => '',
        ),
    );
}

/**
 * Get branding merged with defaults (deep).
 *
 * @return array
 */
function eko_news_get_branding() {
    $defaults = eko_news_default_branding();
    $current  = get_option( 'eko_branding' );

    if ( ! is_array( $current ) ) {
        $current = array();
    }

    $branding = array(
        'brand'  => array(
            'name'        => isset( $current['brand']['name'] ) ? (string) $current['brand']['name'] : $defaults['brand']['name'],
            'logo_url'    => isset( $current['brand']['logo_url'] ) ? (string) $current['brand']['logo_url'] : $defaults['brand']['logo_url'],
            'favicon_url' => isset( $current['brand']['favicon_url'] ) ? (string) $current['brand']['favicon_url'] : $defaults['brand']['favicon_url'],
        ),
        'colors' => array(
            'primary'    => isset( $current['colors']['primary'] ) ? (string) $current['colors']['primary'] : $defaults['colors']['primary'],
            'secondary'  => isset( $current['colors']['secondary'] ) ? (string) $current['colors']['secondary'] : $defaults['colors']['secondary'],
            'accent'     => isset( $current['colors']['accent'] ) ? (string) $current['colors']['accent'] : $defaults['colors']['accent'],
            'text'       => isset( $current['colors']['text'] ) ? (string) $current['colors']['text'] : $defaults['colors']['text'],
            'background' => isset( $current['colors']['background'] ) ? (string) $current['colors']['background'] : $defaults['colors']['background'],
        ),
        'images' => array(
            'hero_default' => isset( $current['images']['hero_default'] ) ? (string) $current['images']['hero_default'] : $defaults['images']['hero_default'],
            'placeholder'  => isset( $current['images']['placeholder'] ) ? (string) $current['images']['placeholder'] : $defaults['images']['placeholder'],
            'pattern'      => isset( $current['images']['pattern'] ) ? (string) $current['images']['pattern'] : $defaults['images']['pattern'],
        ),
    );

    return $branding;
}

/**
 * Theme setup.
 */
function eko_news_setup() {
    // Core supports.
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support(
        'html5',
        array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
    );
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 100,
            'width'       => 400,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );

    // Menus.
    register_nav_menus(
        array(
            'primary' => __( 'Primary Menu', 'eko-news' ),
            'footer'  => __( 'Footer Menu', 'eko-news' ),
        )
    );

    // Initialize defaults on first run.
    $existing = get_option( 'eko_branding', null );
    if ( false === $existing || null === $existing ) {
        update_option( 'eko_branding', eko_news_default_branding() );
    }
}
add_action( 'after_setup_theme', 'eko_news_setup' );

