<?php
get_header();
$branding = function_exists( 'eko_news_get_branding' ) ? eko_news_get_branding() : array();
$show_author  = ! empty( $branding['meta']['show_author'] );
$show_date    = ! empty( $branding['meta']['show_date'] );
$show_reading = ! empty( $branding['meta']['show_reading'] );
$live_tag     = isset( $branding['home']['live_tag'] ) ? sanitize_title( $branding['home']['live_tag'] ) : '';
$sec1         = isset( $branding['home']['section_one_cat'] ) ? sanitize_title( $branding['home']['section_one_cat'] ) : '';
$sec2         = isset( $branding['home']['section_two_cat'] ) ? sanitize_title( $branding['home']['section_two_cat'] ) : '';
$sec3         = isset( $branding['home']['section_three_cat'] ) ? sanitize_title( $branding['home']['section_three_cat'] ) : '';
?>

<main class="container" style="margin:24px auto;">
  <?php
  // Lead: cea mai nouă postare.
  $lead_q = new WP_Query( array( 'posts_per_page' => 1, 'ignore_sticky_posts' => 1 ) );
  if ( $lead_q->have_posts() ) : $lead_q->the_post(); ?>
    <section class="lead" aria-label="Lead story">
      <div class="lead-img">
        <a href="<?php the_permalink(); ?>">
          <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } ?>
        </a>
      </div>
      <div class="lead-body">
        <a href="<?php the_permalink(); ?>"><h1><?php the_title(); ?></h1></a>
        <?php if ( $show_author || $show_date || ( $show_reading && function_exists( 'eko_reading_time' ) ) ) : ?>
          <p class="meta">
            <?php
            $meta_parts = array();
            if ( $show_author ) { $meta_parts[] = esc_html( get_the_author() ); }
            if ( $show_date ) { $meta_parts[] = esc_html( get_the_date() ); }
            if ( $show_reading && function_exists( 'eko_reading_time' ) ) { $meta_parts[] = esc_html( eko_reading_time() ); }
            echo implode( ' • ', $meta_parts );
            ?>
          </p>
        <?php endif; ?>
        <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
      </div>
    </section>
  <?php endif; wp_reset_postdata(); ?>

  <?php if ( $live_tag ) :
    $live_q = new WP_Query( array( 'posts_per_page' => 6, 'tag' => $live_tag, 'ignore_sticky_posts' => 1 ) );
    if ( $live_q->have_posts() ) : ?>
      <section class="live" aria-label="Live" style="margin-top:24px;">
        <h2><?php esc_html_e( 'Live', 'eko-news' ); ?></h2>
        <ul>
          <?php while ( $live_q->have_posts() ) : $live_q->the_post(); ?>
            <li>
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              <?php if ( $show_date ) : ?>
                <span class="meta"> — <?php echo esc_html( get_the_time() ); ?></span>
              <?php endif; ?>
            </li>
          <?php endwhile; ?>
        </ul>
      </section>
    <?php endif; wp_reset_postdata(); endif; ?>

  <?php
  // Secțiuni din categorii.
  $sections = array( $sec1, $sec2, $sec3 );
  foreach ( $sections as $slug ) {
    if ( ! $slug ) { continue; }
    $cat = get_category_by_slug( $slug );
    if ( ! $cat ) { continue; }
    $sec_q = new WP_Query( array( 'posts_per_page' => 9, 'cat' => (int) $cat->term_id, 'ignore_sticky_posts' => 1 ) );
    if ( $sec_q->have_posts() ) : ?>
      <section style="margin-top:24px" aria-label="<?php echo esc_attr( $cat->name ); ?>">
        <h2><?php echo esc_html( $cat->name ); ?></h2>
        <div class="grid cols-archive">
          <?php while ( $sec_q->have_posts() ) : $sec_q->the_post();
            $is_analysis = ( has_term( 'analysis', 'category' ) || has_term( 'analysis', 'post_tag' ) ) ? ' is-analysis' : '';
            ?>
            <article class="card<?php echo esc_attr( $is_analysis ); ?>">
              <a class="thumb-16x9" href="<?php the_permalink(); ?>">
                <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'news-thumb' ); } ?>
              </a>
              <div class="card-body">
                <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
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
              </div>
            </article>
          <?php endwhile; ?>
        </div>
      </section>
    <?php endif; wp_reset_postdata();
  }
  ?>
</main>
<?php get_footer(); ?>

