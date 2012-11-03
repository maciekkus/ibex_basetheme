<?php get_header();?>
		<section id="content" class="article-list">
		<?php do_action('before_index_page');?>
			<?php
			if (have_posts()) { 
				while (have_posts()) { 
	  			the_post(); ?>
	  				<?php ob_start("parseCDNimages");?>
					<?php get_template_part( 'content/excerpt', ibex_get_post_format() ); ?>
					<?php ob_end_flush(); ?>
			<?php } } ?>
			<?php if (function_exists('wp_pagenavi')) {
				wp_pagenavi();
			 } ?>
		<?php do_action('after_index_page');?>	 
		</section>
<?php get_sidebar();?>		
<?php get_footer(); ?>