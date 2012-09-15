<article class="gallery-excerpt">
<?php // zajawka galerii ?>
<?php default_post_header();?>
<?php echo minigallery_shortcode(Array('width'=>100,'height'=>80,'count'=>5)); ?>
<?php 
	add_filter('the_content_more_link','ibex_gallery_excerpt_more',200);
	echo ibex_get_the_excerpt();
	remove_filter('the_content_more_link','ibex_gallery_excerpt_more',200);
?>
</article>