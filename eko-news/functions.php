<?php
if (!defined('ABSPATH')) exit;

/**
 * Tema: Eko News
 * Versiune: 1.0.0
 */
define('EKO_VER', '1.0.0');
define('EKO_PATH', trailingslashit(get_template_directory()));
define('EKO_URI',  trailingslashit(get_template_directory_uri()));

/**
 * Include-urile temei
 */
require_once EKO_PATH.'inc/setup.php';
require_once EKO_PATH.'inc/assets.php';
require_once EKO_PATH.'inc/options.php';

/**
 * === Image sizes & utilitare ===
 */

// Dimensiune pentru carduri din grid (thumb 16:9 aproximativ 400x250)
add_action('after_setup_theme', function () {
  // dacă există deja, WordPress o suprascrie cu aceste setări
  add_image_size('news-thumb', 400, 250, true);
}, 20);

/**
 * Timp de citire (aprox. 200 wpm)
 * Exemplu: echo eko_reading_time(); // "3 min"
 */
if ( ! function_exists( 'eko_reading_time' ) ) {
  function eko_reading_time( $post_id = null ) {
    $post = get_post( $post_id ?: get_the_ID() );
    if ( ! $post ) {
      return '';
    }
    $words = str_word_count( wp_strip_all_tags( $post->post_content ) );
    $mins  = max( 1, (int) ceil( $words / 200 ) );
    return $mins . ' min';
  }
}

// Alias pentru compatibilitate cu template-urile anterioare.
if ( ! function_exists( 'eko_news_reading_time' ) ) {
  function eko_news_reading_time( $post_id = null ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
    return eko_reading_time( $post_id );
  }
}
