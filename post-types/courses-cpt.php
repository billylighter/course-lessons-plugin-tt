<?php
if (!class_exists('COURSES_CPT')) {
    class COURSES_CPT
    {
        function __construct()
        {
            add_action('init', array($this, 'create_post_type'));
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
            add_action('save_post', array($this, 'save_post'));
        }

        public function create_post_type(): void
        {
            register_post_type('courses',
                array(
                    'label' => __('Courses', 'courses_lessons_vs'),
                    'description' => __('Courses', 'courses_lessons_vs'),
                    'labels' => array(
                        'name' => __('Courses', 'courses_lessons_vs'),
                        'singular_name' => __('Course', 'courses_lessons_vs')
                    ),
                    'public' => true,
                    'supports' => array('title', 'editor', 'thumbnail'),
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
                    'register_meta_box_cb' => array($this, 'add_meta_boxes')
                ));
        }

        public function add_meta_boxes(): void
        {
            add_meta_box(
                'courses_cpt_meta_box',
                __('Lessons which must be included into the course', 'courses_lessons_vs'),
                array($this, 'add_inner_meta_boxes'),
                'courses',
                'advanced',
                'high'
            );
        }

        public function add_inner_meta_boxes($post): void
        {
            require_once(COURSES_PLUGIN_PATH . 'views/courses_cpt_metabox.php');
        }

        public function save_post($post_id)
        {

            if (isset($_POST['course_nonce'])) {
                if (!wp_verify_nonce($_POST['course_nonce'], 'course_nonce')) {
                    return;
                }
            }

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            $checkbox_values = isset($_POST['selectedIDLessons']) ? array_map('sanitize_text_field', $_POST['selectedIDLessons']) : [];
            update_post_meta($post_id, '_selected_id_lessons', $checkbox_values);

        }

    }
}