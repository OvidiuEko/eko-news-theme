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

    submit_button( __( 'Save Changes', 'eko-news' ) );

    echo '</form>';

    // Export JSON.
    echo '<h2>' . esc_html__( 'Export JSON', 'eko-news' ) . '</h2>';
    $json = wp_json_encode( $current, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
    echo '<textarea readonly rows="12" style="width:100%;font-family:monospace;">' . esc_textarea( $json ) . '</textarea>';

    echo '</div>';
}

