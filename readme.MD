# Plugin for courses and lessons 🚀

Used Wordpress 6.7.1 & php 8.2.10

1. Download ZIP from this repo
2. Install plugin from backend area manually or extract in plugins DIR manually
3. Activate plugin **Courses and lessons**
4. After that go to **Lessons** section in admin bar and make several lessons 4-10 how many you want for testing
5. Go to **Courses** section and make a few Courses, when you start creating a Course you will see at the bottom of the editing page a metabox which will display all available Lessons in the system, dy default it's shows only public Lessons which have status - PUBLISH
6. In the meta box use checkbox to pick which Lessons must be associated with current Course which you make or edit
7. Use shortcode for displaying all Courses with associated Lessons in it [courses_plugin_shortcode]
8. When content part is set you can proceed to checking authentication part of tech task

Notes:
- In a shortcode method I try to use query posts from DB class for better performance but forgot do not "overthink" 😂 in this case we can use default nested loop using get_posts()
- When plugin was finished I have thinking about mechanism which can be added for binding Courses and Lessons via metaboxes when admin is setting Lessons which must be included in the Course this changes also affected on the metabox of each Lesson which have connection between Courses