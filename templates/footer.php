<footer role="contentinfo">
	<div class="container">
		<div class="row">
			<div>
				<?php dynamic_sidebar('sidebar-footer'); ?>
			</div>
			<?php get_template_part('templates/social-links'); ?>
			<p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>