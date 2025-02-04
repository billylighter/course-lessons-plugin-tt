<?php
$meta = get_post_meta($post->ID);
$_selected_id_lessons = get_post_meta($post->ID, '_selected_id_lessons', true) ?: [];
$lessons = get_posts([
    'numberposts' => -1,
    'category' => 0,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_type' => 'lessons',
    'suppress_filters' => true,
]);

?>

<table class="form-table">
    <input type="hidden" name="course_nonce" value="<?php echo wp_create_nonce('course_nonce'); ?>"/>

    <tr>

        <?php if ($lessons) : ?>

            <th>
                <label for="course_lessons">
                    <?php echo __('Choose lessons which must be included in this course:', 'courses_lessons_vs'); ?>
                </label>
            </th>

            <td>
                <fieldset class="lessons-meta">

                    <?php foreach ($lessons as $lesson) :
                        setup_postdata($lesson);
                        $checked = in_array($lesson->ID, $_selected_id_lessons) ? 'checked' : ''; ?>

                        <div class="lessons-meta__item">
                            <input type="checkbox"
                                   id="<?php echo $lesson->ID; ?>"
                                   name="selectedIDLessons[]"
                                   value="<?php echo $lesson->ID; ?>"
                                <?php echo $checked; ?>
                            />
                            <label for="<?php echo $lesson->ID; ?>">
                                <?php echo get_the_title($lesson); ?>
                            </label>
                        </div>

                    <?php endforeach; ?>

                </fieldset>
            </td>

        <?php else: ?>

            <th style="padding: 12px 24px; border: 3px solid red;">
                <p class="error">
                    <?php echo __('Lessons are not exists.', 'courses_lessons_vs'); ?>
                </p>
            </th>

            <td>
            </td>

        <?php endif; ?>

    </tr>

</table>