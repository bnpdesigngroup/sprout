<?php $social_links = ot_get_option('social_links', array()); if (count($social_links)) : ?>
	<ul class="social-links">
		<?php foreach ($social_links as $link) : ?>
			<?php $link['type'] = str_replace(array('google'), array('google-plus'), strtolower(preg_replace('/.*?(?>\.|\/\/)([^\.]+)\.com.*/i', '$1', $link['url']))); ?>
			<li>
				<a href="<?php echo $link['url']; ?>" title="<?php echo $link['title']; ?>" class="<?php echo $link['type']; ?>"><i class="icon icon-<?php echo $link['type']; ?>"></i></a>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>