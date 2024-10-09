<?php
/**
 * Register custom post types for the plugin.
 *
 * @since      1.0.0
 *
 * @package    Job_Board
 * @subpackage Job_Board/includes
 */

class Job_Board_Post_Types {

    public function register_post_types() {
        add_action('init', array($this, 'register_job_titles_post_type'));
        add_action('init', array($this, 'register_job_application_post_type'));
    }

    /**
     * Register the "Job Titles" custom post type.
     */
    public function register_job_titles_post_type() {
        $labels = array(
            'name'                  => _x( 'Job Titles', 'Post Type General Name', 'job-board' ),
            'singular_name'         => _x( 'Job Title', 'Post Type Singular Name', 'job-board' ),
            'menu_name'             => __( 'Job Titles', 'job-board' ),
            'name_admin_bar'        => __( 'Job Title', 'job-board' ),
            'archives'              => __( 'Job Title Archives', 'job-board' ),
            'attributes'            => __( 'Job Title Attributes', 'job-board' ),
            'parent_item_colon'     => __( 'Parent Job Title:', 'job-board' ),
            'all_items'             => __( 'All Job Titles', 'job-board' ),
            'add_new_item'          => __( 'Add New Job Title', 'job-board' ),
            'add_new'               => __( 'Add New', 'job-board' ),
            'new_item'              => __( 'New Job Title', 'job-board' ),
            'edit_item'             => __( 'Edit Job Title', 'job-board' ),
            'update_item'           => __( 'Update Job Title', 'job-board' ),
            'view_item'             => __( 'View Job Title', 'job-board' ),
            'view_items'            => __( 'View Job Titles', 'job-board' ),
            'search_items'          => __( 'Search Job Title', 'job-board' ),
            'not_found'             => __( 'Not found', 'job-board' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'job-board' ),
            'featured_image'        => __( 'Featured Image', 'job-board' ),
            'set_featured_image'    => __( 'Set featured image', 'job-board' ),
            'remove_featured_image' => __( 'Remove featured image', 'job-board' ),
            'use_featured_image'    => __( 'Use as featured image', 'job-board' ),
            'insert_into_item'      => __( 'Insert into job title', 'job-board' ),
            'uploaded_to_this_item' => __( 'Uploaded to this job title', 'job-board' ),
            'items_list'            => __( 'Job Titles list', 'job-board' ),
            'items_list_navigation' => __( 'Job Titles list navigation', 'job-board' ),
            'filter_items_list'     => __( 'Filter job titles list', 'job-board' ),
        );
        $args = array(
            'label'                 => __( 'Job Title', 'job-board' ),
            'description'           => __( 'Job Titles for the Job Board', 'job-board' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'custom-fields' ),
            'taxonomies'            => array( 'job_category', 'job_skill' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-businessperson',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'job-titles', 'with_front' => false),
        );
        register_post_type( 'job_titles', $args );

        // Register custom taxonomy for job categories
        $this->register_job_category_taxonomy();

        // Register custom taxonomy for job skills
        $this->register_job_skills_taxonomy();
    }

    /**
     * Register the "Job Category" taxonomy.
     */
    private function register_job_category_taxonomy() {
        $labels = array(
            'name'                       => _x( 'Job Categories', 'Taxonomy General Name', 'job-board' ),
            'singular_name'              => _x( 'Job Category', 'Taxonomy Singular Name', 'job-board' ),
            'menu_name'                  => __( 'Job Categories', 'job-board' ),
            'all_items'                  => __( 'All Job Categories', 'job-board' ),
            'parent_item'                => __( 'Parent Job Category', 'job-board' ),
            'parent_item_colon'          => __( 'Parent Job Category:', 'job-board' ),
            'new_item_name'              => __( 'New Job Category Name', 'job-board' ),
            'add_new_item'               => __( 'Add New Job Category', 'job-board' ),
            'edit_item'                  => __( 'Edit Job Category', 'job-board' ),
            'update_item'                => __( 'Update Job Category', 'job-board' ),
            'view_item'                  => __( 'View Job Category', 'job-board' ),
            'separate_items_with_commas' => __( 'Separate job categories with commas', 'job-board' ),
            'add_or_remove_items'        => __( 'Add or remove job categories', 'job-board' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'job-board' ),
            'popular_items'              => __( 'Popular Job Categories', 'job-board' ),
            'search_items'               => __( 'Search Job Categories', 'job-board' ),
            'not_found'                  => __( 'Not Found', 'job-board' ),
            'no_terms'                   => __( 'No job categories', 'job-board' ),
            'items_list'                 => __( 'Job Categories list', 'job-board' ),
            'items_list_navigation'      => __( 'Job Categories list navigation', 'job-board' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        register_taxonomy( 'job_category', array( 'job_titles' ), $args );
    }

    /**
     * Register the "Job Skill" taxonomy.
     */
    private function register_job_skills_taxonomy() {
        $labels = array(
            'name'                       => _x( 'Job Skills', 'Taxonomy General Name', 'job-board' ),
            'singular_name'              => _x( 'Job Skill', 'Taxonomy Singular Name', 'job-board' ),
            'menu_name'                  => __( 'Job Skills', 'job-board' ),
            'all_items'                  => __( 'All Job Skills', 'job-board' ),
            'parent_item'                => __( 'Parent Job Skill', 'job-board' ),
            'parent_item_colon'          => __( 'Parent Job Skill:', 'job-board' ),
            'new_item_name'              => __( 'New Job Skill Name', 'job-board' ),
            'add_new_item'               => __( 'Add New Job Skill', 'job-board' ),
            'edit_item'                  => __( 'Edit Job Skill', 'job-board' ),
            'update_item'                => __( 'Update Job Skill', 'job-board' ),
            'view_item'                  => __( 'View Job Skill', 'job-board' ),
            'separate_items_with_commas' => __( 'Separate job skills with commas', 'job-board' ),
            'add_or_remove_items'        => __( 'Add or remove job skills', 'job-board' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'job-board' ),
            'popular_items'              => __( 'Popular Job Skills', 'job-board' ),
            'search_items'               => __( 'Search Job Skills', 'job-board' ),
            'not_found'                  => __( 'Not Found', 'job-board' ),
            'no_terms'                   => __( 'No job skills', 'job-board' ),
            'items_list'                 => __( 'Job Skills list', 'job-board' ),
            'items_list_navigation'      => __( 'Job Skills list navigation', 'job-board' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        register_taxonomy( 'job_skill', array( 'job_titles' ), $args );
    }

    public function register_job_application_post_type() {
        $labels = array(
            'name'                  => _x( 'Job Applications', 'Post Type General Name', 'job-board' ),
            'singular_name'         => _x( 'Job Application', 'Post Type Singular Name', 'job-board' ),
            'menu_name'             => __( 'Job Applications', 'job-board' ),
            'name_admin_bar'        => __( 'Job Application', 'job-board' ),
            'archives'              => __( 'Job Application Archives', 'job-board' ),
            'attributes'            => __( 'Job Application Attributes', 'job-board' ),
            'parent_item_colon'     => __( 'Parent Job Application:', 'job-board' ),
            'all_items'             => __( 'All Job Applications', 'job-board' ),
            'add_new_item'          => __( 'Add New Job Application', 'job-board' ),
            'add_new'               => __( 'Add New', 'job-board' ),
            'new_item'              => __( 'New Job Application', 'job-board' ),
            'edit_item'             => __( 'Edit Job Application', 'job-board' ),
            'update_item'           => __( 'Update Job Application', 'job-board' ),
            'view_item'             => __( 'View Job Application', 'job-board' ),
            'view_items'            => __( 'View Job Applications', 'job-board' ),
            'search_items'          => __( 'Search Job Application', 'job-board' ),
            'not_found'             => __( 'Not found', 'job-board' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'job-board' ),
            'featured_image'        => __( 'Featured Image', 'job-board' ),
            'set_featured_image'    => __( 'Set featured image', 'job-board' ),
            'remove_featured_image' => __( 'Remove featured image', 'job-board' ),
            'use_featured_image'    => __( 'Use as featured image', 'job-board' ),
            'insert_into_item'      => __( 'Insert into job application', 'job-board' ),
            'uploaded_to_this_item' => __( 'Uploaded to this job application', 'job-board' ),
            'items_list'            => __( 'Job Applications list', 'job-board' ),
            'items_list_navigation' => __( 'Job Applications list navigation', 'job-board' ),
            'filter_items_list'     => __( 'Filter job applications list', 'job-board' ),
        );
        $args = array(
            'label'                 => __( 'Job Application', 'job-board' ),
            'description'           => __( 'Job Applications submitted through the Job Board', 'job-board' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'custom-fields' ), // Add support for custom-fields
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 6,
            'menu_icon'             => 'dashicons-clipboard',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'post',
            'map_meta_cap'          => true,
        );
        register_post_type( 'job_application', $args );
    }

    /**
     * Flush rewrite rules.
     */
    public static function flush_rewrite_rules() {
        $instance = new self();
        $instance->register_job_titles_post_type();
        $instance->register_job_application_post_type();
        flush_rewrite_rules();
    }
}