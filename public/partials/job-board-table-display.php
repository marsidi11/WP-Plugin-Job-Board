<?php
/**
 * Provide a public-facing view for the job board table
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/public/partials
 */
?>

<div class="job-board-table-wrapper">
    <?php if ( $job_titles->have_posts() ) : ?>
        <?php if ( $atts['show_skills_filter'] ) : ?>
            <div class="job-board-filter">
                <label for="skills-filter"><?php _e( 'Filter by skills:', 'job-board' ); ?></label>
                <select id="skills-filter" multiple>
                    <?php
                    $skills = get_terms( array(
                        'taxonomy' => 'job_skill',
                        'hide_empty' => false,
                    ) );
                    foreach ( $skills as $skill ) :
                        echo '<option value="' . esc_attr( $skill->slug ) . '">' . esc_html( $skill->name ) . '</option>';
                    endforeach;
                    ?>
                </select>
            </div>
        <?php endif; ?>
        <table class="job-board-table">
            <thead>
                <tr>
                    <th><?php _e( 'Job Title', 'job-board' ); ?></th>
                    <th><?php _e( 'Skills Required', 'job-board' ); ?></th>
                    <th><?php _e( 'Category', 'job-board' ); ?></th>
                    <th><?php _e( 'Posted Date', 'job-board' ); ?></th>
                    <th><?php _e( 'Action', 'job-board' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ( $job_titles->have_posts() ) : 
                    $job_titles->the_post(); 
                    $skills = get_the_terms( get_the_ID(), 'job_skill' );
                    $categories = get_the_terms( get_the_ID(), 'job_category' );
                    $skill_classes = '';
                    $skill_names = array();
                    if ( $skills && ! is_wp_error( $skills ) ) {
                        foreach ( $skills as $skill ) {
                            $skill_classes .= ' skill-' . $skill->slug;
                            $skill_names[] = $skill->name;
                        }
                    }
                ?>
                    <tr class="job-title-row<?php echo esc_attr( $skill_classes ); ?>">
                        <td><?php the_title(); ?></td>
                        <td><?php echo esc_html( implode(', ', $skill_names) ); ?></td>
                        <td><?php echo $categories ? esc_html( $categories[0]->name ) : ''; ?></td>
                        <td><?php echo get_the_date(); ?></td>
                        <td><a href="<?php the_permalink(); ?>" class="job-apply-button"><?php _e( 'Apply', 'job-board' ); ?></a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p><?php _e( 'No job titles found.', 'job-board' ); ?></p>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
</div>

<script>
jQuery(document).ready(function($) {
    $('#skills-filter').select2({
        placeholder: '<?php _e( 'Select skills to filter', 'job-board' ); ?>',
        allowClear: true
    }).on('change', function() {
        var selectedSkills = $(this).val();
        if (selectedSkills && selectedSkills.length > 0) {
            $('.job-title-row').hide();
            selectedSkills.forEach(function(skill) {
                $('.job-title-row.skill-' + skill).show();
            });
        } else {
            $('.job-title-row').show();
        }
    });
});
</script>