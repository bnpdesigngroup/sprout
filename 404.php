<?php get_template_part('templates/title'); ?>

<div class="alert alert-warning">
  <?php _e('Sorry, but the page you were trying to view does not exist.', 'sprout'); ?>
</div>

<p><?php _e('It looks like this was the result of either:', 'sprout'); ?></p>
<ul>
  <li><?php _e('a mistyped address', 'sprout'); ?></li>
  <li><?php _e('an out-of-date link', 'sprout'); ?></li>
</ul>

<?php get_search_form(); ?>
