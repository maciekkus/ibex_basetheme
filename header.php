<?php get_template_part( 'head' ); ?>
<body <?php body_class();?>>
	<?php do_action('baseibex-after-body'); ?>
	<?php get_template_part( 'custom', 'before-container' ); ?>
	<div class="container">
		<?php get_template_part( 'custom', 'container-inner-top' ); ?>
		<?php get_template_part( 'custom', 'header' ); ?>
		<?php get_template_part( 'custom', 'nav' ); ?>