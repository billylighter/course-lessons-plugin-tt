<?php
/**
 * Plugin Name:       Courses and lessons
 * Description:       Making 2 CPT and verification by auth, adding gutenberg block for this purposes
 * Requires at least: 6.6
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Author:            Volodymyr Selevertov
 * Text Domain:       courses_lessons_vs
 *
 */


if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('COURSES_PLUGIN')) {
    class COURSES_PLUGIN
    {
        function __construct()
        {
            $this->define_constants();

            require_once(COURSES_PLUGIN_PATH . 'post-types/courses-cpt.php');
            require_once(COURSES_PLUGIN_PATH . 'post-types/lessons-cpt.php');
            $COURSES_CPT = new COURSES_CPT();
            $LESSONS_CPT = new LESSONS_CPT();

            require_once(COURSES_PLUGIN_PATH . 'shortcodes/courses-plugin-shortcode.php');
            $Courses_Plugin_Shortcode = new Courses_Plugin_Shortcode();

            add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'), 999);

            add_filter('the_content', array($this, 'restrict_lessons'));
        }

        public function define_constants() : void
        {
            define('COURSES_PLUGIN_PATH', plugin_dir_path(__FILE__));
            define('COURSES_PLUGIN_URL', plugin_dir_url(__FILE__));
            define('COURSES_PLUGIN_VERSION', '1.0,0');
        }

        public static function activate() : void
        {
            update_option('rewrite_rules', '');
        }

        public static function deactivate() : void
        {
            flush_rewrite_rules();
            unregister_post_type('courses');
            unregister_post_type('lessons');
        }

        public static function uninstall() : void
        {
            delete_option( 'courses_lessons_options' );

            $posts = get_posts(
                array(
                    'post_type' => ['courses', 'lessons'],
                    'number_posts'  => -1,
                    'post_status'   => 'any'
                )
            );

            foreach( $posts as $post ){
                wp_delete_post( $post->ID, true );
            }
        }


        public function register_admin_scripts() : void
        {
            global $typenow;
            if ($typenow == 'courses') {
                wp_enqueue_style('courses_lessons_admin_css', COURSES_PLUGIN_PATH . 'assets/css/admin.css', array(), COURSES_PLUGIN_VERSION, 'all');
            }
        }

        public function restrict_lessons($content) {
            if (is_singular('lessons') && !is_user_logged_in()) {
                return '<div style="padding: 12px 24px; border: 2px solid darkred; background-color: rgba(255, 0,0, 0.1)"><p>' . __('This lesson is available for free during a 7-day trial.', 'courses_lessons_vs') . '</p><p><a href="' .    wp_login_url(get_permalink())  .'" class="wp-block-button__link" >' . __('Click here to register', 'courses_lessons_vs') . '</a></p></div>';
            }
            return $content;
        }

    }
}

if (class_exists('COURSES_PLUGIN')) {
    register_activation_hook(__FILE__, array('COURSES_PLUGIN', 'activate'));
    register_deactivation_hook(__FILE__, array('COURSES_PLUGIN', 'deactivate'));
    register_uninstall_hook(__FILE__, array('COURSES_PLUGIN', 'uninstall'));
    $courses_plugin = new COURSES_PLUGIN();
}