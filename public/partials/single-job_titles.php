<?php
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();

                    // Display job categories
                    $categories = get_the_terms( get_the_ID(), 'job_category' );
                    if ( $categories && ! is_wp_error( $categories ) ) {
                        echo '<p><strong>' . __( 'Job Categories:', 'job-board' ) . '</strong> ';
                        echo esc_html( implode( ', ', wp_list_pluck( $categories, 'name' ) ) );
                        echo '</p>';
                    }

                    // Display job skills
                    $skills = get_the_terms( get_the_ID(), 'job_skill' );
                    if ( $skills && ! is_wp_error( $skills ) ) {
                        echo '<p><strong>' . __( 'Required Skills:', 'job-board' ) . '</strong> ';
                        echo esc_html( implode( ', ', wp_list_pluck( $skills, 'name' ) ) );
                        echo '</p>';
                    }
                    ?>
                </div>

                <div class="job-application-form">
                    <h2><?php _e( 'Apply for this job', 'job-board' ); ?></h2>
                    <?php echo do_shortcode( '[job_board_form]' ); ?>
                </div>
            </article>
            <?php
        endwhile;
        ?>
    </main>
</div>

<?php
get_footer();