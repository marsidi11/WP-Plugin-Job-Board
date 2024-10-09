<?php
/**
 * Handle custom fields for the plugin.
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/includes
 */

class Job_Board_Custom_Fields {

    /**
     * Initialize the custom fields.
     */
    public function init() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
    }

    /**
     * Add custom meta boxes.
     */
    public function add_meta_boxes() {
        add_meta_box(
            'job_title_skills',
            __( 'Skills', 'job-board' ),
            array( $this, 'render_skills_meta_box' ),
            'job_titles',
            'normal',
            'default'
        );
    }

    /**
     * Render the skills meta box.
     */
    public function render_skills_meta_box( $post ) {
        wp_nonce_field( 'job_title_skills_meta_box', 'job_title_skills_meta_box_nonce' );

        $selected_skills = get_post_meta( $post->ID, '_job_title_skills', true );
        $all_skills = get_terms( array(
            'taxonomy' => 'job_skill',
            'hide_empty' => false,
        ) );

        ?>
        <p>
            <label for="job_title_skills"><?php _e( 'Select required skills:', 'job-board' ); ?></label><br>
            <select name="job_title_skills[]" id="job_title_skills" multiple="multiple" style="width: 100%; max-width: 400px;">
                <?php foreach ( $all_skills as $skill ) : ?>
                    <option value="<?php echo esc_attr( $skill->term_id ); ?>" <?php selected( in_array( $skill->term_id, (array) $selected_skills ), true ); ?>>
                        <?php echo esc_html( $skill->name ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <script>
            jQuery(document).ready(function($) {
                $('#job_title_skills').select2({
                    placeholder: '<?php _e( 'Select skills', 'job-board' ); ?>',
                    allowClear: true,
                    tags: true,
                    tokenSeparators: [',', ' '],
                    createTag: function (params) {
                        return {
                            id: params.term,
                            text: params.term,
                            newOption: true
                        }
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Save the meta box data.
     */
    public function save_meta_boxes( $post_id ) {
        if ( ! isset( $_POST['job_title_skills_meta_box_nonce'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['job_title_skills_meta_box_nonce'], 'job_title_skills_meta_box' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( isset( $_POST['job_title_skills'] ) ) {
            $skills = array_map( 'intval', $_POST['job_title_skills'] );
            update_post_meta( $post_id, '_job_title_skills', $skills );

            // Add new skills as terms
            foreach ( $skills as $skill_id ) {
                if ( !is_numeric( $skill_id ) ) {
                    $term = wp_insert_term( $skill_id, 'job_skill' );
                    if ( !is_wp_error( $term ) ) {
                        $skills[] = $term['term_id'];
                    }
                }
            }

            wp_set_object_terms( $post_id, $skills, 'job_skill' );
        } else {
            delete_post_meta( $post_id, '_job_title_skills' );
            wp_set_object_terms( $post_id, array(), 'job_skill' );
        }
    }
}