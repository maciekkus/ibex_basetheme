<?php get_header();?>
		<section id="content">
		<?php do_action('before_page_article');?>
		<?php
				while (have_posts()) {
		  				the_post(); ?>
					<article>
						<?php default_page_header();?>
						<?php the_content(); ?>
					</article>
					<?php } ?>
		<?php do_action('after_page_article');?>
		</section>
<?php get_sidebar();?>
<?php get_footer(); ?>