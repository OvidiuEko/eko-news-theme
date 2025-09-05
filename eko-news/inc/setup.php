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
        'layout' => array(
            'container_width' => 1200,
            'logo_max_h'      => 42,
            'header_pad_y'    => 10,
            'archive_cols'    => 3,
        ),
        'meta' => array(
            'show_author'  => 1,
            'show_date'    => 1,
            'show_reading' => 1,
        ),
        'theme' => array(
            'dark_mode' => 0,
        ),
        'home' => array(
            'live_tag'        => '',
            'section_one_cat' => '',
            'section_two_cat' => '',
            'section_three_cat'=> '',
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
        'layout' => array(
            'container_width' => isset( $current['layout']['container_width'] ) ? (int) $current['layout']['container_width'] : $defaults['layout']['container_width'],
            'logo_max_h'      => isset( $current['layout']['logo_max_h'] ) ? (int) $current['layout']['logo_max_h'] : $defaults['layout']['logo_max_h'],
            'header_pad_y'    => isset( $current['layout']['header_pad_y'] ) ? (int) $current['layout']['header_pad_y'] : $defaults['layout']['header_pad_y'],
            'archive_cols'    => isset( $current['layout']['archive_cols'] ) ? (int) $current['layout']['archive_cols'] : $defaults['layout']['archive_cols'],
        ),
        'meta' => array(
            'show_author'  => isset( $current['meta']['show_author'] ) ? (int) $current['meta']['show_author'] : $defaults['meta']['show_author'],
            'show_date'    => isset( $current['meta']['show_date'] ) ? (int) $current['meta']['show_date'] : $defaults['meta']['show_date'],
            'show_reading' => isset( $current['meta']['show_reading'] ) ? (int) $current['meta']['show_reading'] : $defaults['meta']['show_reading'],
        ),
        'theme' => array(
            'dark_mode' => isset( $current['theme']['dark_mode'] ) ? (int) $current['theme']['dark_mode'] : $defaults['theme']['dark_mode'],
        ),
        'home' => array(
            'live_tag'        => isset( $current['home']['live_tag'] ) ? (string) $current['home']['live_tag'] : $defaults['home']['live_tag'],
            'section_one_cat' => isset( $current['home']['section_one_cat'] ) ? (string) $current['home']['section_one_cat'] : $defaults['home']['section_one_cat'],
            'section_two_cat' => isset( $current['home']['section_two_cat'] ) ? (string) $current['home']['section_two_cat'] : $defaults['home']['section_two_cat'],
            'section_three_cat'=> isset( $current['home']['section_three_cat'] ) ? (string) $current['home']['section_three_cat'] : $defaults['home']['section_three_cat'],
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

/**
 * Safe frontend optimizations: disable emojis, oEmbed discovery, dashicons for visitors.
 */
function eko_news_disable_frontend_extras() {
    if ( is_admin() ) {
        return;
    }
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}
add_action( 'init', 'eko_news_disable_frontend_extras' );

function eko_news_dequeue_dashicons() {
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
        wp_dequeue_style( 'dashicons' );
    }
}
add_action( 'wp_enqueue_scripts', 'eko_news_dequeue_dashicons', 100 );
