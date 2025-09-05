<?php
/**
 * Search results template
 *
 * @package EkoNews
 */

get_header();
$branding = function_exists( 'eko_news_get_branding' ) ? eko_news_get_branding() : array();
$show_author  = ! empty( $branding['meta']['show_author'] );
$show_date    = ! empty( $branding['meta']['show_date'] );
$show_reading = ! empty( $branding['meta']['show_reading'] );
?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="search-header" style="margin: calc(var(--eko-gap) * 1.5) 0;">
            <h1>
                <?php
                printf(
                    /* translators: %s: search query */
                    esc_html__( 'Search results for: %s', 'eko-news' ),
                    '<span class="meta">' . esc_html( get_search_query() ) . '</span>'
                );
                ?>
            </h1>
        </header>

        <?php if ( have_posts() ) : ?>
            <section class="grid cols-archive">
                <?php while ( have_posts() ) : the_post();
                    $is_analysis = ( has_term( 'analysis','category' ) || has_term( 'analysis','post_tag' ) ) ? ' is-analysis' : '';
                    ?>
                    <article class="card<?php echo esc_attr( $is_analysis ); ?>">
                        <a class="thumb-16x9" href="<?php the_permalink(); ?>">
                            <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'news-thumb' ); ?>
                        </a>
                        <div class="card-body">
                            <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                            <?php if ( $show_author || $show_date || ( $show_reading && function_exists('eko_reading_time') ) ) : ?>
                                <p class="meta">
                                    <?php
                                    $meta = array();
                                    if ( $show_author ) { $meta[] = esc_html( get_the_author() ); }
                                    if ( $show_date ) { $meta[] = esc_html( get_the_date() ); }
                                    if ( $show_reading && function_exists('eko_reading_time') ) { $meta[] = esc_html( eko_reading_time() ); }
                                    echo implode(' • ', $meta);
                                    ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
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
            <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
