<section>
    <h2>
        <?php echo __('Courses:', 'courses_lessons_vs'); ?>
    </h2>

    <?php if (COURSES_WITH_LESSONS) : ?>

        <ul>

            <?php foreach (COURSES_WITH_LESSONS as $course): ?>
                <li>
                    <h4 style="margin-bottom: 1rem;">
                        <?php echo esc_html($course['post_title']); ?>
                    </h4>

                    <?php if ($course['lessons']) : ?>
                        <ul>
                            <?php foreach ($course['lessons'] as $lesson) : ?>
                                <li style="margin-bottom: 1rem;">
                                    <strong style="margin: 0;">
                                        <?php echo esc_html($lesson['post_title']); ?>
                                    </strong>
                                    <div>
                                        <?php if (is_user_logged_in()) : ?>
                                            <p><?php echo esc_html($lesson['post_excerpt']); ?></p>
                                            <a href="<?php echo esc_url($lesson['url']); ?>"
                                               class="wp-block-button__link">
                                                <?php echo __('Check the lesson', 'courses_lessons_vs'); ?>
                                            </a>
                                        <?php else : ?>
                                            <a href="<?php echo wp_login_url(esc_url($lesson['url'])); ?>"
                                               class="wp-block-button__link" target="_blank">
                                                <?php echo __('subscribe/trial', 'courses_lessons_vs'); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </li>

                <hr>

            <?php endforeach;; ?>

        </ul>

    <?php else: ?>

        <div style="padding: 12px 24px; border: 2px solid darkred; background-color: rgba(255, 0,0, 0.1)">
            <?php echo __('Sorry courses not available for now.', 'courses_lessons_vs'); ?>
        </div>

    <?php endif; ?>

</section>