<?php
/**
 * Provide a public-facing view for the job listings
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/public/partials
 */
?>

<div class="job-board-listings">
    <?php if ( $job_listings->have_posts() ) : ?>
        <?php while ( $job_listings->have_posts() ) : $job_listings->the_post(); ?>
            <div class="job-listing">
                <h2><?php the_title(); ?></h2>
                <div class="job-meta">
                    <?php
                    $categories = get_the_terms( get_the_ID(), 'job_category' );
                    $skills = get_the_terms( get_the_ID(), 'job_skill' );
                    ?>
                    <?php if ( $categories ) : ?>
                        <p class="job-categories">
                            <strong><?php _e( 'Categories:', 'job-board' ); ?></strong>
                            <?php echo implode( ', ', wp_list_pluck( $categories, 'name' ) ); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ( $skills ) : ?>
                        <p class="job-skills">
                            <strong><?php _e( 'Skills:', 'job-board' ); ?></strong>
                            <?php echo implode( ', ', wp_list_pluck( $skills, 'name' ) ); ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="job-description">
                    <?php the_excerpt(); ?>
                </div>
                <a href="<?php the_permalink(); ?>" class="apply-button"><?php _e( 'Apply Now', 'job-board' ); ?></a>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <p><?php _e( 'No job listings found.', 'job-board' ); ?></p>
    <?php endif; ?>
</div>
<?php wp_reset_postdata(); ?>