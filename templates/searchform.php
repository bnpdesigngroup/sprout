<form role="search" method="get" class="search-form form-inline" action="<?php echo home_url('/'); ?>">
	<div class="row">
		<div class="three-fourths-mobile">
			<input type="search" value="" name="s" class="search-field form-control" placeholder="<?php _e('Search', 'sprout'); ?> <?php bloginfo('name'); ?>">
		</div>
		<div class="one-fourth-mobile">
			<button type="submit" class="search-submit suffix" title="<?php _e('Search', 'sprout'); ?>"><i class="icon icon-search"></i></button>
		</div>
	</div>
</form>
