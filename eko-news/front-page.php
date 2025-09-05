<?php get_header(); ?>
<main class="container" style="margin:24px auto;">
  <?php
  // Lead: ultima postare
  $lead_q = new WP_Query(['posts_per_page'=>1]);
  if ($lead_q->have_posts()): $lead_q->the_post(); ?>
    <section class="lead" aria-label="Lead story">
      <div class="lead-img">
        <a href="<?php the_permalink(); ?>">
          <?php if (has_post_thumbnail()) the_post_thumbnail('large'); ?>
        </a>
      </div>
      <div class="lead-body">
        <a href="<?php the_permalink(); ?>"><h1><?php the_title(); ?></h1></a>
        <p class="meta"><?php echo esc_html( get_the_date() ); ?> &bull; <?php echo esc_html( get_the_author() ); ?></p>
        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
      </div>
    </section>
  <?php endif; wp_reset_postdata(); ?>

  <?php
  // Grid: următoarele 9
  $grid_q = new WP_Query(['posts_per_page'=>9,'offset'=>1]);
  if ($grid_q->have_posts()): ?>
    <section style="margin-top:24px" aria-label="Latest">
      <div class="grid cols-3">
        <?php while ($grid_q->have_posts()): $grid_q->the_post(); ?>
          <article class="card">
            <a class="thumb-16x9" href="<?php the_permalink(); ?>">
              <?php if (has_post_thumbnail()) the_post_thumbnail('news-thumb'); ?>
            </a>
            <div class="card-body">
              <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
              <p class="meta"><?php echo esc_html( get_the_date() ); ?> &bull; <?php echo esc_html( get_the_author() ); ?></p>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
    </section>
  <?php endif; wp_reset_postdata(); ?>
</main>
<?php get_footer(); ?>



