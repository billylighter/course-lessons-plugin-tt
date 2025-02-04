<?php

if (!class_exists('Courses_Plugin_Shortcode')) {
    class Courses_Plugin_Shortcode
    {
        public function __construct()
        {
            add_shortcode('courses_plugin_shortcode', array($this, 'add_shortcode'));
        }

        public function get_courses_with_lessons(): array
        {
            global $wpdb;

            $courses = $wpdb->get_results("
    SELECT p.ID, p.post_title, pm.meta_value AS lesson_ids
    FROM {$wpdb->posts} p
    LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_selected_id_lessons'
    WHERE p.post_type = 'courses' AND p.post_status = 'publish'
", ARRAY_A);

            $lesson_ids = [];
            foreach ($courses as &$course) {
                $course['lesson_ids'] = !empty($course['lesson_ids']) ? maybe_unserialize($course['lesson_ids']) : [];
                if (is_array($course['lesson_ids'])) {
                    $lesson_ids = array_merge($lesson_ids, $course['lesson_ids']);
                }
            }

            $lesson_ids = array_unique($lesson_ids);

            $lessons = [];
            if (!empty($lesson_ids)) {
                $placeholders = implode(',', array_fill(0, count($lesson_ids), '%d'));

                $lesson_data = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT ID, post_title, post_name, post_excerpt FROM {$wpdb->posts} 
             WHERE ID IN ($placeholders) AND post_type = 'lessons' AND post_status = 'publish'",
                        $lesson_ids
                    ),
                    ARRAY_A
                );

                foreach ($lesson_data as $lesson) {
                    $lessons[$lesson['ID']] = [
                        'ID' => $lesson['ID'],
                        'post_title' => $lesson['post_title'],
                        'post_excerpt' => $lesson['post_excerpt'], // Add the excerpt
                        'url' => get_home_url() . "/lessons/" . $lesson['post_name'] . "/"
                    ];
                }
            }

            foreach ($courses as &$course) {
                $course['lessons'] = [];
                $course['url'] = get_permalink($course['ID']); // Add course URL

                foreach ($course['lesson_ids'] as $lesson_id) {
                    if (isset($lessons[$lesson_id])) {
                        $course['lessons'][] = $lessons[$lesson_id];
                    }
                }
            }

            foreach ($courses as &$course) {
                unset($course['lesson_ids']);
            }

            unset($course);

            return $courses;
        }

        public function add_shortcode($atts = array(), $content = null, $tag = '')
        {

            $atts = array_change_key_case((array)$atts, CASE_LOWER);

            extract(shortcode_atts(
                array(
                    'id' => '',
                    'orderby' => 'date'
                ),
                $atts,
                $tag
            ));

            if (!empty($id)) {
                $id = array_map('absint', explode(',', $id));
            }

            define('COURSES_WITH_LESSONS', $this->get_courses_with_lessons());

            ob_start();
            require(COURSES_PLUGIN_PATH . 'views/courses-shortcode.php');

            return ob_get_clean();
        }
    }
}
