<?php
/**
 * 404 Not Found template
 *
 * @package EkoNews
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container" style="margin: calc(var(--eko-gap) * 2) auto;">
        <h1><?php esc_html_e( 'Page not found', 'eko-news' ); ?></h1>
        <p class="meta"><?php esc_html_e( 'Sorry, we couldn’t find what you’re looking for.', 'eko-news' ); ?></p>

        <?php get_search_form(); ?>

        <section style="margin-top: calc(var(--eko-gap) * 2);">
            <h2><?php esc_html_e( 'Browse popular categories', 'eko-news' ); ?></h2>
            <ul>
                <?php
                wp_list_categories(
                    array(
                        'orderby'    => 'count',
                        'order'      => 'DESC',
                        'title_li'   => '',
                        'number'     => 6,
                        'hide_empty' => true,
                        'depth'      => 1,
                    )
                );
                ?>
            </ul>
        </section>
    </div>
</main>

<?php
get_footer();

