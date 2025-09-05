<?php
/**
 * Single post template
 *
 * @package EkoNews
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-article' ); ?>>
                <header class="entry-header" style="margin: calc(var(--eko-gap) * 1.5) 0;">
                    <h1 class="entry-title" style="margin: 0 0 var(--eko-gap); color: var(--eko-text);">
                        <?php echo esc_html( get_the_title() ); ?>
                    </h1>
                    <p class="meta">
                        <?php
                        printf(
                            /* translators: 1: author, 2: date, 3: reading time */
                            esc_html__( 'By %1$s on %2$s • %3$s', 'eko-news' ),
                            esc_html( get_the_author() ),
                            esc_html( get_the_date() ),
                            esc_html( function_exists( 'eko_news_reading_time' ) ? eko_news_reading_time() : '' )
                        );
                        ?>
                    </p>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="ratio-16x9" style="border-radius: var(--eko-radius);">
                        <?php the_post_thumbnail( 'full', array( 'class' => 'img-fluid' ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content" style="margin-top: calc(var(--eko-gap) * 1.5);">
                    <?php
                    the_content();
                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'eko-news' ),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div>

                <nav class="post-navigation" style="margin-top: calc(var(--eko-gap) * 2);" aria-label="<?php esc_attr_e( 'Post', 'eko-news' ); ?>">
                    <?php
                    the_post_navigation(
                        array(
                            'prev_text' => '<span class="meta" style="display:block;">' . esc_html__( 'Previous', 'eko-news' ) . '</span>%title',
                            'next_text' => '<span class="meta" style="display:block;">' . esc_html__( 'Next', 'eko-news' ) . '</span>%title',
                        )
                    );
                    ?>
                </nav>

                <?php
                // Recommended posts: last 4 from same categories.
                $cats = wp_get_post_categories( get_the_ID(), array( 'fields' => 'ids' ) );
                if ( ! empty( $cats ) ) {
                    $rel = new WP_Query(
                        array(
                            'posts_per_page'      => 4,
                            'post__not_in'        => array( get_the_ID() ),
                            'ignore_sticky_posts' => 1,
                            'category__in'        => $cats,
                        )
                    );
                    if ( $rel->have_posts() ) : ?>
                        <section style="margin-top: calc(var(--eko-gap) * 2);">
                            <h2 style="margin-bottom: var(--eko-gap);"><?php esc_html_e( 'Recommended articles', 'eko-news' ); ?></h2>
                            <div class="grid grid-3">
                                <?php while ( $rel->have_posts() ) : $rel->the_post();
                                    get_template_part( 'template-parts/card', 'post' );
                                endwhile; wp_reset_postdata(); ?>
                            </div>
                        </section>
                    <?php endif; wp_reset_postdata();
                }
                ?>
            </article>
        <?php endwhile; endif; ?>
    </div>
</main>

<?php
get_footer();

