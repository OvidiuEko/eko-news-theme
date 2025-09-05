<?php
/**
 * Lead card template (featured post)
 *
 * @package EkoNews
 */

if ( ! isset( $args ) ) {
    $args = array(); // phpcs:ignore
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card card-lead' ); ?>>
    <a href="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
        <div class="ratio-16x9">
            <?php if ( has_post_thumbnail() ) :
                the_post_thumbnail( 'full', array( 'class' => 'img-fluid' ) );
            endif; ?>
        </div>
    </a>
    <div class="card-body">
        <div class="meta" style="margin-bottom: 6px;">
            <?php
            $cats = get_the_category();
            if ( ! empty( $cats ) ) {
                $c = $cats[0];
                echo '<span class="badge"><a href="' . esc_url( get_category_link( $c ) ) . '">' . esc_html( $c->name ) . '</a></span>';
            }
            ?>
        </div>
        <h2 class="entry-title" style="margin-bottom: 6px;">
            <a href="<?php echo esc_url( get_permalink() ); ?>" style="color: var(--eko-text);">
                <?php echo esc_html( get_the_title() ); ?>
            </a>
        </h2>
        <p class="meta" style="margin-bottom: var(--eko-gap);">
            <?php echo esc_html( get_the_date() ); ?> • <?php echo esc_html( function_exists( 'eko_news_reading_time' ) ? eko_news_reading_time() : '' ); ?>
        </p>
        <p>
            <?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?>
        </p>
    </div>
</article>

