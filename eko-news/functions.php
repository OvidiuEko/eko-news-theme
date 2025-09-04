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

