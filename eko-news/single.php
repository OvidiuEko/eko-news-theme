<?php
get_header();
$branding = function_exists( 'eko_news_get_branding' ) ? eko_news_get_branding() : array();
$show_author  = ! empty( $branding['meta']['show_author'] );
$show_date    = ! empty( $branding['meta']['show_date'] );
$show_reading = ! empty( $branding['meta']['show_reading'] );
?>
<main class="container" style="margin:24px auto;max-width:900px;">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php $is_analysis = ( has_term( 'analysis','category' ) || has_term( 'analysis','post_tag' ) ) ? ' is-analysis' : ''; ?>
    <article class="<?php echo esc_attr( trim( $is_analysis ) ); ?>">
      <h1><?php the_title(); ?></h1>
      <?php if ( $show_author || $show_date || ( $show_reading && function_exists( 'eko_reading_time' ) ) ) : ?>
        <p class="meta">
          <?php
            $meta = array();
            if ( $show_author ) { $meta[] = esc_html( get_the_author() ); }
            if ( $show_date ) { $meta[] = esc_html( get_the_date() ); }
            if ( $show_reading && function_exists( 'eko_reading_time' ) ) { $meta[] = esc_html( eko_reading_time() ); }
            echo implode( ' • ', $meta );
          ?>
        </p>
      <?php endif; ?>
      <?php if ( has_post_thumbnail() ) : ?>
        <div class="thumb-16x9" style="margin:12px 0 16px"><?php the_post_thumbnail( 'large' ); ?></div>
      <?php endif; ?>
      <div class="entry-content"><?php the_content(); ?></div>
    </article>

    <nav class="pagination" style="justify-content:space-between">
      <div><?php previous_post_link( '%link', '&larr; ' . esc_html__( 'Previous', 'eko-news' ) ); ?></div>
      <div><?php next_post_link( '%link', esc_html__( 'Next', 'eko-news' ) . ' &rarr;' ); ?></div>
    </nav>

    <?php
    // Recommended posts: last 4 from same primary category.
    $cats = get_the_category();
    if ( ! empty( $cats ) ) {
        $primary = $cats[0];
        $rel = new WP_Query( array(
            'posts_per_page'      => 4,
            'post__not_in'        => array( get_the_ID() ),
            'ignore_sticky_posts' => 1,
            'cat'                 => (int) $primary->term_id,
        ) );
        if ( $rel->have_posts() ) : ?>
            <section style="margin-top: calc(var(--eko-gap) * 2);">
              <h2 style="margin-bottom: var(--eko-gap);"><?php esc_html_e( 'Recommended articles', 'eko-news' ); ?></h2>
              <div class="grid" style="grid-template-columns: repeat(4,1fr); gap: var(--eko-gap);">
                <?php while ( $rel->have_posts() ) : $rel->the_post();
                  $is_a = ( has_term( 'analysis','category' ) || has_term( 'analysis','post_tag' ) ) ? ' is-analysis' : '';
                  ?>
                  <article class="card<?php echo esc_attr( $is_a ); ?>">
                    <a class="thumb-16x9" href="<?php the_permalink(); ?>">
                      <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'news-thumb' ); ?>
                    </a>
                    <div class="card-body">
                      <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                      <?php if ( $show_author || $show_date ) : ?>
                        <p class="meta">
                          <?php
                            $m = array();
                            if ( $show_author ) { $m[] = esc_html( get_the_author() ); }
                            if ( $show_date ) { $m[] = esc_html( get_the_date() ); }
                            echo implode( ' • ', $m );
                          ?>
                        </p>
                      <?php endif; ?>
                    </div>
                  </article>
                <?php endwhile; wp_reset_postdata(); ?>
              </div>
            </section>
        <?php endif; wp_reset_postdata();
    }
    ?>
  <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>

