<?php get_header();?>
		<section id="content">
		<?php do_action('before_single_article');?>
		<?php
				while (have_posts()) {
		  				the_post(); ?>
					<article>
						<?php default_post_header();?>
						<?php do_action('before_single_article_content');?>
						<?php the_content(); ?>
						<?php do_action('after_single_article_content');?>
					</article>
		<?php } ?>
		<?php do_action('after_single_article');?>
		</section>
<?php get_sidebar();?>
<?php get_footer(); ?>