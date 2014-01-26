<?php global $sprout ?>
<header role="banner">
	<div class="container">
		<a class="brand" href="<?php echo home_url() ?>" title="<?php bloginfo('name') ?>">
		<?php 
			$logo = ot_get_option('logo', null); 
			$retina_logo = ot_get_option('retina_logo', null);
			if ($logo):
				if ($retina_logo): ?>
					<img src="<?php echo $retina_logo ?>" class="high-res" role="logo"/>
				<?php endif; ?>
				<img src="<?php echo $logo ?>" role="logo"/>
			<?php else:
				bloginfo('name');
			endif; ?>
		</a>
		
		<?php $tagline = $sprout->options->get_option('header_tagline', null); if ($tagline): ?>
			<div class="tagline" role="complementary">
				<?php echo do_shortcode($tagline); ?>
			</div>
		<?php endif; ?>

		<?php $contact = $sprout->options->get_option('header_contact', null); if ($contact): ?>
			<div class="contact" role="complementary">
				<?php echo do_shortcode($contact); ?>
			</div>
		<?php endif; ?>

		<nav class="nav large-tablet" role="navigation">
			<?php if (has_nav_menu('primary_navigation')):
					wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => ''));
			endif; ?>
		</nav>
	</div>
</header>