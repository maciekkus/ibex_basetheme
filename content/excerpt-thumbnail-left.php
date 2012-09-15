<article class="thumbnail-left">
<?php // miniatura z lewej ?>
<?php default_post_header();?>
<a href="<?php the_permalink(); ?>"><img class="alignleft" src="<? echo ibex_search_post_for_thumbnail('thumbnail');?>"/></a>
<?php 
	echo ibex_get_the_excerpt();
?>
</article>