<?php
/**
 * Universal fallback template
 *
 * @package EkoNews
 */

get_header();
?>

<main class="container">
  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article <?php post_class( 'card' ); ?>>
        <div class="card-body">
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
        </div>
      </article>
    <?php endwhile; ?>

    <div class="pagination">
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
    <p><?php esc_html_e( 'Nu exista continut.', 'eko-news' ); ?></p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>


