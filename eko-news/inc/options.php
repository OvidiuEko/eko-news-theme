<?php
/**
 * Theme options page (Appearance → Eko Theme).
 *
 * @package EkoNews\Inc
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add options page under Appearance.
 */
function eko_news_add_theme_page() {
    add_theme_page(
        __( 'Eko Theme', 'eko-news' ),
        __( 'Eko Theme', 'eko-news' ),
        'manage_options',
        'eko-theme',
        'eko_news_render_theme_page'
    );
}
add_action( 'admin_menu', 'eko_news_add_theme_page' );

/**
 * Validate and sanitize hex color.
 *
 * @param string $color Color string.
 * @param string $fallback Fallback color.
 * @return string
 */
function eko_news_sanitize_color( $color, $fallback ) {
    $color = is_string( $color ) ? trim( $color ) : '';
    $san   = sanitize_hex_color( $color );
    return $san ? $san : $fallback;
}

/**
 * Sanitize URL (http/https only), allow empty.
 *
 * @param string $url URL.
 * @return string
 */
function eko_news_sanitize_url_strict( $url ) {
    $url = is_string( $url ) ? trim( $url ) : '';
    if ( '' === $url ) {
        return '';
    }
    $san = esc_url_raw( $url, array( 'http', 'https' ) );
    return $san ? $san : '';
}

/**
 * Render the options page.
 */
function eko_news_render_theme_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $updated  = false;
    $defaults = eko_news_default_branding();
    $current  = eko_news_get_branding();

    if ( isset( $_POST['eko_branding_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['eko_branding_nonce'] ) ), 'eko_branding_save' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
        // Brand.
        $brand_name  = isset( $_POST['brand_name'] ) ? sanitize_text_field( wp_unslash( $_POST['brand_name'] ) ) : $defaults['brand']['name']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $logo_url    = isset( $_POST['logo_url'] ) ? eko_news_sanitize_url_strict( wp_unslash( $_POST['logo_url'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $favicon_url = isset( $_POST['favicon_url'] ) ? eko_news_sanitize_url_strict( wp_unslash( $_POST['favicon_url'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        // Colors.
        $primary   = isset( $_POST['color_primary'] ) ? eko_news_sanitize_color( wp_unslash( $_POST['color_primary'] ), $defaults['colors']['primary'] ) : $defaults['colors']['primary']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $secondary = isset( $_POST['color_secondary'] ) ? eko_news_sanitize_color( wp_unslash( $_POST['color_secondary'] ), $defaults['colors']['secondary'] ) : $defaults['colors']['secondary']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $accent    = isset( $_POST['color_accent'] ) ? eko_news_sanitize_color( wp_unslash( $_POST['color_accent'] ), $defaults['colors']['accent'] ) : $defaults['colors']['accent']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $text      = isset( $_POST['color_text'] ) ? eko_news_sanitize_color( wp_unslash( $_POST['color_text'] ), $defaults['colors']['text'] ) : $defaults['colors']['text']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $bg        = isset( $_POST['color_bg'] ) ? eko_news_sanitize_color( wp_unslash( $_POST['color_bg'] ), $defaults['colors']['background'] ) : $defaults['colors']['background']; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        // Images.
        $hero_default = isset( $_POST['image_hero_default'] ) ? eko_news_sanitize_url_strict( wp_unslash( $_POST['image_hero_default'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $placeholder  = isset( $_POST['image_placeholder'] ) ? eko_news_sanitize_url_strict( wp_unslash( $_POST['image_placeholder'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $pattern      = isset( $_POST['image_pattern'] ) ? eko_news_sanitize_url_strict( wp_unslash( $_POST['image_pattern'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        // Layout.
        $container_width = isset( $_POST['layout_container_width'] ) ? (int) wp_unslash( $_POST['layout_container_width'] ) : $defaults['layout']['container_width']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $container_width = max( 600, min( 1920, $container_width ) );
        $logo_max_h      = isset( $_POST['layout_logo_max_h'] ) ? (int) wp_unslash( $_POST['layout_logo_max_h'] ) : $defaults['layout']['logo_max_h']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $logo_max_h      = max( 16, min( 160, $logo_max_h ) );
        $header_pad_y    = isset( $_POST['layout_header_pad_y'] ) ? (int) wp_unslash( $_POST['layout_header_pad_y'] ) : $defaults['layout']['header_pad_y']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $header_pad_y    = max( 0, min( 48, $header_pad_y ) );
        $archive_cols    = isset( $_POST['layout_archive_cols'] ) ? (int) wp_unslash( $_POST['layout_archive_cols'] ) : $defaults['layout']['archive_cols']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $archive_cols    = max( 1, min( 4, $archive_cols ) );

        // Meta toggles.
        $show_author  = isset( $_POST['meta_show_author'] ) ? 1 : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $show_date    = isset( $_POST['meta_show_date'] ) ? 1 : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $show_reading = isset( $_POST['meta_show_reading'] ) ? 1 : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        // Theme.
        $dark_mode = isset( $_POST['theme_dark_mode'] ) ? 1 : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        // Home.
        $home_live_tag     = isset( $_POST['home_live_tag'] ) ? sanitize_title( wp_unslash( $_POST['home_live_tag'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $home_sec_one_cat  = isset( $_POST['home_section_one_cat'] ) ? sanitize_title( wp_unslash( $_POST['home_section_one_cat'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $home_sec_two_cat  = isset( $_POST['home_section_two_cat'] ) ? sanitize_title( wp_unslash( $_POST['home_section_two_cat'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $home_sec_three_cat= isset( $_POST['home_section_three_cat'] ) ? sanitize_title( wp_unslash( $_POST['home_section_three_cat'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

        $new = array(
            'brand'  => array(
                'name'        => $brand_name,
                'logo_url'    => $logo_url,
                'favicon_url' => $favicon_url,
            ),
            'colors' => array(
                'primary'    => $primary,
                'secondary'  => $secondary,
                'accent'     => $accent,
                'text'       => $text,
                'background' => $bg,
            ),
            'images' => array(
                'hero_default' => $hero_default,
                'placeholder'  => $placeholder,
                'pattern'      => $pattern,
            ),
            'layout' => array(
                'container_width' => $container_width,
                'logo_max_h'      => $logo_max_h,
                'header_pad_y'    => $header_pad_y,
                'archive_cols'    => $archive_cols,
            ),
            'meta' => array(
                'show_author'  => $show_author,
                'show_date'    => $show_date,
                'show_reading' => $show_reading,
            ),
            'theme' => array(
                'dark_mode' => $dark_mode,
            ),
            'home' => array(
                'live_tag'        => $home_live_tag,
                'section_one_cat' => $home_sec_one_cat,
                'section_two_cat' => $home_sec_two_cat,
                'section_three_cat'=> $home_sec_three_cat,
            ),
        );

        update_option( 'eko_branding', $new );
        $current = eko_news_get_branding();
        $updated = true;
    }

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__( 'Eko Theme', 'eko-news' ) . '</h1>';

    if ( $updated ) {
        echo '<div id="message" class="updated notice is-dismissible"><p>' . esc_html__( 'Salvat.', 'eko-news' ) . '</p></div>';
    }

    echo '<form method="post" action="">';
    wp_nonce_field( 'eko_branding_save', 'eko_branding_nonce' );

    // Brand section.
    echo '<h2>' . esc_html__( 'Brand', 'eko-news' ) . '</h2>';
    echo '<table class="form-table" role="presentation"><tbody>';

    echo '<tr><th scope="row"><label for="brand_name">' . esc_html__( 'Name', 'eko-news' ) . '</label></th><td>';
    echo '<input type="text" id="brand_name" name="brand_name" class="regular-text" value="' . esc_attr( $current['brand']['name'] ) . '" />';
    echo '</td></tr>';

    echo '<tr><th scope="row"><label for="logo_url">' . esc_html__( 'Logo URL', 'eko-news' ) . '</label></th><td>';
    echo '<input type="url" id="logo_url" name="logo_url" class="regular-text code" value="' . esc_attr( $current['brand']['logo_url'] ) . '" placeholder="https://..." />';
    echo '</td></tr>';

    echo '<tr><th scope="row"><label for="favicon_url">' . esc_html__( 'Favicon URL', 'eko-news' ) . '</label></th><td>';
    echo '<input type="url" id="favicon_url" name="favicon_url" class="regular-text code" value="' . esc_attr( $current['brand']['favicon_url'] ) . '" placeholder="https://..." />';
    echo '</td></tr>';

    echo '</tbody></table>';

    // Colors section.
    echo '<h2>' . esc_html__( 'Colors', 'eko-news' ) . '</h2>';
    echo '<table class="form-table" role="presentation"><tbody>';

    echo '<tr><th scope="row"><label for="color_primary">' . esc_html__( 'Primary', 'eko-news' ) . '</label></th><td>';
    echo '<input type="text" id="color_primary" name="color_primary" class="regular-text code" value="' . esc_attr( $current['colors']['primary'] ) . '" placeholder="#0a7cff" />';
    echo '<p class="description">#RGB / #RRGGBB</p>';
    echo '</td></tr>';

    echo '<tr><th scope="row"><label for="color_secondary">' . esc_html__( 'Secondary', 'eko-news' ) . '</label></th><td>';
    echo '<input type="text" id="color_secondary" name="color_secondary" class="regular-text code" value="' . esc_attr( $current['colors']['secondary'] ) . '" placeholder="#00b894" />';
    echo '</td></tr>';

    echo '<tr><th scope="row"><label for="color_accent">' . esc_html__( 'Accent', 'eko-news' ) . '</label></th><td>';
    echo '<input type="text" id="color_accent" name="color_accent" class="regular-text code" value="' . esc_attr( $current['colors']['accent'] ) . '" placeholder="#ffc107" />';
    echo '</td></tr>';

    echo '<tr><th scope="row"><label for="color_text">' . esc_html__( 'Text', 'eko-news' ) . '</label></th><td>';
    echo '<input type="text" id="color_text" name="color_text" class="regular-text code" value="' . esc_attr( $current['colors']['text'] ) . '" placeholder="#222222" />';
    echo '</td></tr>';

    echo '<tr><th scope="row"><label for="color_bg">' . esc_html__( 'Background', 'eko-news' ) . '</label></th><td>';
    echo '<input type="text" id="color_bg" name="color_bg" class="regular-text code" value="' . esc_attr( $current['colors']['background'] ) . '" placeholder="#ffffff" />';
    echo '</td></tr>';

    echo '</tbody></table>';

    // Images section.
    echo '<h2>' . esc_html__( 'Images', 'eko-news' ) . '</h2>';
    echo '<table class="form-table" role="presentation"><tbody>';

    echo '<tr><th scope="row"><label for="image_hero_default">' . esc_html__( 'Hero Default URL', 'eko-news' ) . '</label></th><td>';
    echo '<input type="url" id="image_hero_default" name="image_hero_default" class="regular-text code" value="' . esc_attr( $current['images']['hero_default'] ) . '" placeholder="https://..." />';
    echo '</td></tr>';

    echo '<tr><th scope="row"><label for="image_placeholder">' . esc_html__( 'Placeholder URL', 'eko-news' ) . '</label></th><td>';
    echo '<input type="url" id="image_placeholder" name="image_placeholder" class="regular-text code" value="' . esc_attr( $current['images']['placeholder'] ) . '" placeholder="https://..." />';
    echo '</td></tr>';

    echo '<tr><th scope="row"><label for="image_pattern">' . esc_html__( 'Pattern URL', 'eko-news' ) . '</label></th><td>';
    echo '<input type="url" id="image_pattern" name="image_pattern" class="regular-text code" value="' . esc_attr( $current['images']['pattern'] ) . '" placeholder="https://..." />';
    echo '</td></tr>';

    echo '</tbody></table>';

    // Layout section.
    echo '<h2>' . esc_html__( 'Layout', 'eko-news' ) . '</h2>';
    echo '<table class="form-table" role="presentation"><tbody>';
    echo '<tr><th scope="row"><label for="layout_container_width">' . esc_html__( 'Container width (px)', 'eko-news' ) . '</label></th><td>';
    echo '<input type="number" id="layout_container_width" name="layout_container_width" class="small-text" min="600" max="1920" value="' . esc_attr( (int) $current['layout']['container_width'] ) . '" />';
    echo '<p class="description">' . esc_html__( 'Max page width in pixels', 'eko-news' ) . '</p>';
    echo '</td></tr>';
    echo '<tr><th scope="row"><label for="layout_logo_max_h">' . esc_html__( 'Logo max height (px)', 'eko-news' ) . '</label></th><td>';
    echo '<input type="number" id="layout_logo_max_h" name="layout_logo_max_h" class="small-text" min="16" max="160" value="' . esc_attr( (int) $current['layout']['logo_max_h'] ) . '" />';
    echo '</td></tr>';
    echo '<tr><th scope="row"><label for="layout_header_pad_y">' . esc_html__( 'Header vertical padding (px)', 'eko-news' ) . '</label></th><td>';
    echo '<input type="number" id="layout_header_pad_y" name="layout_header_pad_y" class="small-text" min="0" max="48" value="' . esc_attr( (int) $current['layout']['header_pad_y'] ) . '" />';
    echo '</td></tr>';
    echo '<tr><th scope="row"><label for="layout_archive_cols">' . esc_html__( 'Archive columns', 'eko-news' ) . '</label></th><td>';
    echo '<input type="number" id="layout_archive_cols" name="layout_archive_cols" class="small-text" min="1" max="4" value="' . esc_attr( (int) $current['layout']['archive_cols'] ) . '" />';
    echo '</td></tr>';
    echo '</tbody></table>';

    // Meta section.
    echo '<h2>' . esc_html__( 'Meta', 'eko-news' ) . '</h2>';
    echo '<table class="form-table" role="presentation"><tbody>';
    echo '<tr><th scope="row">' . esc_html__( 'Show author', 'eko-news' ) . '</th><td>';
    echo '<label><input type="checkbox" name="meta_show_author" ' . checked( ! empty( $current['meta']['show_author'] ), true, false ) . ' /> ' . esc_html__( 'Display author name', 'eko-news' ) . '</label>';
    echo '</td></tr>';
    echo '<tr><th scope="row">' . esc_html__( 'Show date', 'eko-news' ) . '</th><td>';
    echo '<label><input type="checkbox" name="meta_show_date" ' . checked( ! empty( $current['meta']['show_date'] ), true, false ) . ' /> ' . esc_html__( 'Display publish date', 'eko-news' ) . '</label>';
    echo '</td></tr>';
    echo '<tr><th scope="row">' . esc_html__( 'Show reading time', 'eko-news' ) . '</th><td>';
    echo '<label><input type="checkbox" name="meta_show_reading" ' . checked( ! empty( $current['meta']['show_reading'] ), true, false ) . ' /> ' . esc_html__( 'Display reading time', 'eko-news' ) . '</label>';
    echo '</td></tr>';
    echo '</tbody></table>';

    // Theme section.
    echo '<h2>' . esc_html__( 'Theme', 'eko-news' ) . '</h2>';
    echo '<table class="form-table" role="presentation"><tbody>';
    echo '<tr><th scope="row">' . esc_html__( 'Dark mode', 'eko-news' ) . '</th><td>';
    echo '<label><input type="checkbox" name="theme_dark_mode" ' . checked( ! empty( $current['theme']['dark_mode'] ), true, false ) . ' /> ' . esc_html__( 'Enable dark colors on frontend', 'eko-news' ) . '</label>';
    echo '</td></tr>';
    echo '</tbody></table>';

    // Homepage section.
    echo '<h2>' . esc_html__( 'Homepage', 'eko-news' ) . '</h2>';
    echo '<table class="form-table" role="presentation"><tbody>';
    echo '<tr><th scope="row"><label for="home_live_tag">' . esc_html__( 'Live tag slug', 'eko-news' ) . '</label></th><td>';
    echo '<input type="text" id="home_live_tag" name="home_live_tag" class="regular-text" value="' . esc_attr( $current['home']['live_tag'] ) . '" placeholder="live" />';
    echo '<p class="description">' . esc_html__( 'Tag slug for Live list', 'eko-news' ) . '</p>';
    echo '</td></tr>';
    echo '<tr><th scope="row"><label for="home_section_one_cat">' . esc_html__( 'Section one category (slug)', 'eko-news' ) . '</label></th><td>';
    echo '<input type="text" id="home_section_one_cat" name="home_section_one_cat" class="regular-text" value="' . esc_attr( $current['home']['section_one_cat'] ) . '" placeholder="features" />';
    echo '</td></tr>';
    echo '<tr><th scope="row"><label for="home_section_two_cat">' . esc_html__( 'Section two category (slug)', 'eko-news' ) . '</label></th><td>';
    echo '<input type="text" id="home_section_two_cat" name="home_section_two_cat" class="regular-text" value="' . esc_attr( $current['home']['section_two_cat'] ) . '" />';
    echo '</td></tr>';
    echo '<tr><th scope="row"><label for="home_section_three_cat">' . esc_html__( 'Section three category (slug)', 'eko-news' ) . '</label></th><td>';
    echo '<input type="text" id="home_section_three_cat" name="home_section_three_cat" class="regular-text" value="' . esc_attr( $current['home']['section_three_cat'] ) . '" />';
    echo '</td></tr>';
    echo '</tbody></table>';

    submit_button( __( 'Save Changes', 'eko-news' ) );

    echo '</form>';

    // Export JSON.
    echo '<h2>' . esc_html__( 'Export JSON', 'eko-news' ) . '</h2>';
    $json = wp_json_encode( $current, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
    echo '<textarea readonly rows="12" style="width:100%;font-family:monospace;">' . esc_textarea( $json ) . '</textarea>';

    echo '</div>';
}
