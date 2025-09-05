<?php
/**
 * Front Page template: Lead + Grid
 *
 * @package EkoNews
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        // Lead post (latest single).
        $lead_q = new WP_Query(
            array(
                'posts_per_page'      => 1,
                'ignore_sticky_posts' => 1,
            )
        );

        if ( $lead_q->have_posts() ) :
            while ( $lead_q->have_posts() ) :
                $lead_q->the_post();
                get_template_part( 'template-parts/card', 'lead' );
            endwhile;
            wp_reset_postdata();
        endif;

        // Next 9 posts in a 3-col grid.
        $grid_q = new WP_Query(
            array(
                'posts_per_page'      => 9,
                'offset'              => 1,
                'ignore_sticky_posts' => 1,
            )
        );

        if ( $grid_q->have_posts() ) : ?>
            <section class="grid grid-3" aria-label="<?php esc_attr_e( 'Latest Posts', 'eko-news' ); ?>" style="margin-top: calc(var(--eko-gap) * 2);">
                <?php
                while ( $grid_q->have_posts() ) :
                    $grid_q->the_post();
                    get_template_part( 'template-parts/card', 'post' );
                endwhile;
                wp_reset_postdata();
                ?>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
