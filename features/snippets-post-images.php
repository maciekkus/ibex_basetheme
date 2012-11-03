<?php 
/*
 * Funkcje pomocne w budowaniu stron opartych na wordpress
 */

/*
 * function get_post_mini_image($postId, $count = 1, $width = 80, $height = 80, $alt = '')
 * - zwraca kod html <img src="..."/> pierwszego obrazka z galerii dla danego wpisu  
 * 
 */

require_once(ABSPATH . '/wp-admin/includes/image.php');

function resized_image_filename( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
//	$orig_w=2000;
//	$orig_h=2000;
	$dims = image_resize_dimensions($orig_w, $orig_h, $max_w, $max_h, $crop);
	list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) = $dims;

	// $suffix will be appended to the destination filename, just before the extension
	if ( !$suffix )
		$suffix = "{$max_w}x{$max_h}";
	$info = pathinfo($file);
	$dir = $info['dirname'];
	$ext = $info['extension'];
	$name = basename($file, ".{$ext}");
	if ( !is_null($dest_path) and $_dest_path = realpath($dest_path) )
		$dir = $_dest_path;
	$destfilename = "{$dir}/{$name}-{$suffix}.{$ext}";
	return $destfilename; 
}
function theme_image_resize($file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
	if (!$suffix) {
		echo "zdefinuj suffix dla theme_image_resize";
	}
	$file = str_replace('//','/',$file);
	$newfile = resized_image_filename($file,$max_w,$max_h,$crop,$suffix,$dest_path,$jpeg_quality);
	if (is_object($newfile)) {
		$newfile = '';
	} 
	if (!file_exists($newfile)) {
		$newfile = image_resize($file,$max_w,$max_h,$crop,$suffix,$dest_path,$jpeg_quality);
	}
	if (is_object($newfile)) {
		$newfile = '';
	} 
	$newfile = str_replace(ABSPATH,'',$newfile);
	if ($newfile == '') {
		$newfile = $file;
		$newfile = str_replace(ABSPATH,'',$newfile);
	}
	return '/'.$newfile;
}

/* zwraca kod $content opakowany tagiem $tag */
function ibex_embed_tag($tag,$content,$attr = Array()) {
	$attrs = '';
	foreach ($attr as $attrname => $attrvalue) {
		$attrs .= ' '.$attrname.'="'.$attrvalue.'"';
	}
	return "<$tag $attrs>$content</$tag>"; 
}


function get_post_mini_image($id=null,$count=1,$width=80,$height=80, $alt='',$linktobig=false, $offset = 0) {
		$args = array(
		'post_type' => 'attachment',
		'numberposts' => $count,
		'post_status' => null,
		'post_parent' => $id,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'offset'=> $offset,
		);
		
		$output = ''; 
		$attachments = get_posts($args);
		if ($attachments) {
			foreach($attachments as $thumbimage) {
				if (!$image = wp_get_attachment_image_src($thumbimage->ID, 'large')) {
					$image = wp_get_attachment_image_src($thumbimage->ID, 'full');
				}
				if ($image) {
				    $image[0] = str_replace(get_option('home'),'',$image[0]);
					$gallery_image = theme_image_resize(ABSPATH.$image[0],$width,$height,true,$suffix='minigalery-'.$width);
					$image_tag = '<img src="'.$gallery_image.'" alt="'.$alt.'"/>';
					if ($linktobig) {
						$image_tag = ibex_embed_tag('a',$image_tag,Array('class'=>'colorbox','href'=>$image[0]));
					}
					if ($count>1) {
						$image_tag = ibex_embed_tag('li',$image_tag);
					}
					$output .= $image_tag; 
				}
			}
		}
	return $output;	
}


?>