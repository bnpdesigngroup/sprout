<?php get_template_part('templates/title'); ?>

<?php if (!have_posts()) : ?>
  <div class="box alert gap-bottom">
    <?php _e('Sorry, no results were found.', 'sprout'); ?>
  </div>

  <?php get_search_form(); ?>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', get_post_format()); ?>
<?php endwhile; ?>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php next_posts_link(__('&larr; Older posts', 'sprout')); ?></li>
      <li class="next"><?php previous_posts_link(__('Newer posts &rarr;', 'sprout')); ?></li>
    </ul>
  </nav>
<?php endif; ?>
