<?php
/**
 * Eko News theme bootstrap.
 *
 * @package EkoNews
 */

defined( 'ABSPATH' ) || exit;

// Theme constants.
if ( ! defined( 'EKO_NEWS_VERSION' ) ) {
    define( 'EKO_NEWS_VERSION', '1.0.0' );
}
if ( ! defined( 'EKO_NEWS_DIR' ) ) {
    define( 'EKO_NEWS_DIR', get_template_directory() );
}
if ( ! defined( 'EKO_NEWS_URI' ) ) {
    define( 'EKO_NEWS_URI', get_template_directory_uri() );
}

// Includes.
require_once EKO_NEWS_DIR . '/inc/setup.php';
require_once EKO_NEWS_DIR . '/inc/assets.php';
require_once EKO_NEWS_DIR . '/inc/options.php';

/**
 * Estimate reading time for a post.
 *
 * @param int|WP_Post|null $post Post object or ID. Defaults to current post.
 * @param int              $wpm  Words per minute (default 200).
 * @return string Formatted minutes string, e.g. "3 min".
 */
function eko_news_reading_time( $post = null, $wpm = 200 ) {
    $post    = get_post( $post );
    $content = '';

    if ( $post ) {
        $content = $post->post_content;
    } elseif ( have_posts() ) {
        $content = get_the_content( null, false );
    }

    $text   = wp_strip_all_tags( (string) $content );
    $words  = str_word_count( $text );
    $wpm    = (int) max( 1, $wpm );
    $minutes = (int) max( 1, ceil( $words / $wpm ) );

    return sprintf( __( '%s min', 'eko-news' ), number_format_i18n( $minutes ) );
}

/**
 * Register additional image size if missing.
 */
function eko_news_register_image_sizes() {
    global $_wp_additional_image_sizes;
    $has = ( isset( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes['news-thumb'] ) );
    if ( ! $has ) {
        add_image_size( 'news-thumb', 400, 250, true );
    }
}
add_action( 'after_setup_theme', 'eko_news_register_image_sizes' );
