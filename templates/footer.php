<footer role="contentinfo">
	<div id="inner_footer" class="container">
		<div class="row">
			<?php dynamic_sidebar('sidebar-footer'); ?>
			<?php get_template_part('templates/social-links'); ?>
		</div>
		<p class="copyright">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>