<?php global $sprout; ?>

<?php get_template_part('templates/head'); ?>

<body <?php body_class(); ?>>

  <!--[if lt IE 9]><div class="message alert square align-center" role="alert"><?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sprout'); ?></div><![endif]-->
  
  <?php $notice = ot_get_option('notice', null); if ($notice): ?>
   
    <div class="message info square align-center fadeInDown"><?php echo $notice ?></div>
  
  <?php endif; ?>

  <?php
    do_action('get_header');
    get_template_part('templates/header');
  ?>

  <div role="document">
    <div id="inner_content" class="container">
      <div class="row guttered">
        <main class="main <?php echo $sprout->layout->get_main_class(); ?>" role="main">
          <?php include $sprout->templates->get_main_template(); ?>
        </main><!-- /.main -->

        <?php if ($sprout->layout->has_sidebar()) : ?>
          <aside class="sidebar <?php echo $sprout->layout->get_sidebar_class() ?>" role="complementary">
            <?php get_template_part('templates/sidebar'); ?>
          </aside><!-- /.sidebar -->
        <?php endif; ?>
      </div><!-- /.row -->
    </div><!-- /.container -->
  </div><!-- /[role="document"] -->

  <?php get_template_part('templates/footer'); ?>

</body>
</html>
