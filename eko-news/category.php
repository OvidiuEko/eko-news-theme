<?php
/**
 * Category archive template (with description)
 *
 * @package EkoNews
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="archive-header" style="margin: calc(var(--eko-gap) * 1.5) 0;">
            <h1><?php single_cat_title(); ?></h1>
            <?php
            $desc = category_description();
            if ( $desc ) {
                echo '<p class="meta">' . wp_kses_post( $desc ) . '</p>';
            }
            ?>
        </header>

        <?php if ( have_posts() ) : ?>
            <section class="grid grid-3">
                <?php while ( have_posts() ) : the_post();
                    get_template_part( 'template-parts/card', 'post' );
                endwhile; ?>
            </section>
            <div class="container" style="margin-top: calc(var(--eko-gap) * 2);">
                <?php
                the_posts_pagination(
                    array(
                        'prev_text' => __( 'Previous', 'eko-news' ),
                        'next_text' => __( 'Next', 'eko-news' ),
                    )
                );
                ?>
            </div>
        <?php else : ?>
            <p><?php esc_html_e( 'Nothing found.', 'eko-news' ); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();

