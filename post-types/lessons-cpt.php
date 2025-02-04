<?php
if (!class_exists('LESSONS_CPT')) {
    class LESSONS_CPT
    {
        function __construct()
        {
            add_action('init', array($this, 'create_post_type'));
        }

        public function create_post_type(): void
        {
            register_post_type('lessons',
                array(
                    'label' => __('Lessons', 'courses_lessons_vs'),
                    'description' => __('Lessons', 'courses_lessons_vs'),
                    'labels' => array(
                        'name' => __('Lessons', 'courses_lessons_vs'),
                        'singular_name' => __('Lesson', 'courses_lessons_vs')
                    ),
                    'public' => true,
                    'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
                    'hierarchical' => false,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'menu_position' => 5,
                    'show_in_admin_bar' => true,
                    'show_in_nav_menus' => true,
                    'can_export' => true,
                    'has_archive' => false,
                    'exclude_from_search' => false,
                    'publicly_queryable' => true,
                    'show_in_rest' => true,
                    'menu_icon' => 'dashicons-format-gallery',
                ));
        }

    }
}