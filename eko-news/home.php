<?php get_header(); ?>
<main class="container" style="margin:24px auto;">
  <h1><?php the_archive_title(); ?></h1>
  <div class="grid cols-3" style="margin-top:16px">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <article class="card">
        <a class="thumb-16x9" href="<?php the_permalink(); ?>">
          <?php if (has_post_thumbnail()) the_post_thumbnail('news-thumb'); ?>
        </a>
        <div class="card-body">
          <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
          <p class="meta"><?php echo esc_html( get_the_date() ); ?> &bull; <?php echo esc_html( get_the_author() ); ?></p>
        </div>
      </article>
    <?php endwhile; endif; ?>
  </div>
  <div class="pagination">
    <?php the_posts_pagination( array( 'prev_text' => __( 'Previous', 'eko-news' ), 'next_text' => __( 'Next', 'eko-news' ) ) ); ?>
  </div>
</main>
<?php get_footer(); ?>




