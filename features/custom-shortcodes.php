<?php 
/*
 * Przydatne shortcode's ogólnego zastosowania
 */

/* [removebr] usuwa <br/> z określonego obszaru kodu html */
add_shortcode('removebr','removebr');
/* [przerwa] */
add_shortcode('przerwa','przerwa_shortcode');
/* [odstep] */
add_shortcode('odstep','odstep_shortcode');
/* [email] */
add_shortcode('email','email_shortcode');
/* [minigallery] */
add_shortcode('minigallery','minigallery_shortcode');
/* 
 *  Example usage: [htmltag tag="iframe" width="500px" src="http://yourdomain.com"/]
 */
add_shortcode('htmltag','html_shortcode');
/* [sekcja typ=""] */
add_shortcode('sekcja','sekcja_shortcode');

add_shortcode('submenu','submenu_shortcode');

add_shortcode('include','include_shortcode');

function include_shortcode($atts,$content = null) {
	ob_start();
	if ($atts['file']!='') {
		get_template_part( 'shortcode', $atts['file'] );
	}
	$result = ob_get_contents();
	ob_end_clean();
	return $result; 
}

function submenu_shortcode( $atts, $content = null, $code='' ) {
	global $post;	
	$parent_id = $atts['parent'];
	if (!$parent_id) {
    	$parent_id = $post->ID; // if there is no parent, treat this page as parent
    }
    if ($atts['parent'] == 'x') {
    	$parent_id = $post->post_parent; // if there is no parent, treat this page as parent
    }   
    $args = array(
	'post_type' => 'page',
	'numberposts' => -1,
	'post_status' => 'publish',
	'post_parent' => $parent_id,
	'orderby' => 'menu_order',
	'order' => 'ASC'
	); 
	$subpages = get_posts($args);
	if ($subpages) {
		$output .= '<ul class="subpages_menu">';
		if ($parent_id == $post->ID) {
			$permalink = '';
		} else {
			$permalink = get_permalink($parent_id);
		}
		$parent_page_title = $atts['title'];
		if (!$parent_page_title) { $parent_page_title = get_the_title($parent_id);}
		if ($permalink) {
			$output .= ibex_embed_tag('li',ibex_embed_tag('a',$parent_page_title,Array('href'=>$permalink)));
		} else {
			$output .= ibex_embed_tag('li',$parent_page_title,Array('class'=>'current'));
		}
		foreach ($subpages as $subpage) {
			$permalink = get_permalink($subpage->ID);
			if (is_page($subpage->ID)) {
				$output .= ibex_embed_tag('li',get_the_title($subpage->ID),Array('class'=>'current'));
			} else {
				$output .= ibex_embed_tag('li',ibex_embed_tag('a',get_the_title($subpage->ID),Array('href'=>$permalink)));
			} 	
		}
		$output .= '</ul>';
	}
	return $output;
}


function sekcja_shortcode( $atts, $content = null, $code='' ) {
	$output = '<div class="'.$atts['typ'].'">'.$content.'</div>';
	return $output;
}

function html_shortcode( $atts, $content = null, $code='' ) {
	$htmltag = $atts['tag'];
	if ($htmltag) {
		$output= "<".$htmltag;
		foreach ($atts as $attribute=>$value) {
			if ($attribute != 'tag') {
				$output = $output . " " . $attribute . '="'. $value . '"';
			}
		}
		if ($content == null) {
			if ($htmltag == 'iframe') {
				$output = $output . '></'.$htmltag.'>';
			} else {
				$output = $output . '/>';
			}
		} else {
			$output = $output . '>'. $content . '</' .$htmltag . '>';
		}
		return $output;
	} else {
		return;
	}
}




function removebr($attr,$content = null) {
  return str_replace('<br />','',$content);
}


function przerwa_shortcode( $attr, $content = null ) {
	return '<div class="div-break" style="clear:both;display: block; "></div>';
}

function odstep_shortcode( $attr, $content = null ) {
	return '<div style="clear:both;display: block; "><br/></div>';
}

function minigallery_shortcode($attr,$content = null) {
	global $post;
	global $ibex_minigallery_defaults;
	extract(shortcode_atts(array(
		'id'=> $post->ID,
	    'count' => -1,
		), $attr));
	$output = '';	
	if (!$width) {
		if (isset($ibex_minigallery_defaults['width'])) {
			$width = $ibex_minigallery_defaults['width'];
		} else {
			if (isset($attr['width'])) {
				$width = $attr['width'];
			} else {
				$width=65;
			}
		} 
	}
	if (!$height) { 
		if (isset($ibex_minigallery_defaults['height'])) {
			$height = $ibex_minigallery_defaults['height'];
		} else { 
			if (isset($attr['height'])) {
				$height = $attr['height'];
			} else {
				$height=43;
			}
		} 
	}
	$args = array(
		'post_type' => 'attachment',
		'numberposts' => $count,
		'post_status' => null,
		'post_parent' => $id,
		'orderby' => 'menu_order',
		'order' => 'ASC'
	); 
	$attachments = get_posts($args);
	if ($attachments) {
	$output = '<div class="minigallery">';
	
	if (isset($attr['preview'])) {
		$preview_size = explode('x',$attr['preview']);
		if (is_array($preview_size)) {
			$preview_w = $preview_size[0];
			$preview_h = $preview_size[1];
			$output .= '<div class="preview">';
			$output .= '</div>';
		}
	}
	
	
		foreach($attachments as $thumbimage) {
			if (!$image = wp_get_attachment_image_src($thumbimage->ID, 'large')) {
				$image = wp_get_attachment_image_src($thumbimage->ID, 'full');
			}
			$alt = '';
			$title = '';
			if ($image) {
				$gallery_image = theme_image_resize(ABSPATH.$image[0],$width,$height,true,$suffix='mini-'.$width.'x'.$height);
				if (isset($preview_w)) {
					$data_preview = 'data-preview="'.theme_image_resize(ABSPATH.$image[0],$preview_w,$preview_h,true,$suffix='mini-'.$preview_w.'x'.$preview_h).'" ';
				} else {
					$data_preview = '';
				}
				$alt = trim(strip_tags( get_post_meta($thumbimage->ID, '_wp_attachment_image_alt', true) ));
				$title = trim(strip_tags( $thumbimage->post_title ));
				if (isset($attr['link'])) {
					$link = $attr['link'];
					$class = "nocolorbox";
				} else {
					$link = $image[0];
					$class = "colorbox";
				}
				if (isset($attr['target'])) {
					$target = 'target="'.$attr['target'].'"';
				} else {
					$target = '';
				}
				$output .= '<a '.$target.' class="'.$class.'" rel="minigallery-'.$id.'" width="'.$width.'" height="'.$height.' "rel="minigaleria" title="'.$title.'" href="'.$link.'"><img '.$data_preview.'src="'.$gallery_image.'" alt="'.$alt.'"/></a>'; 
			}
		}
	$output .= '</div>';
	}
	return $output;	
}


function email_shortcode($attr,$content = null) {
	return mailMe($content,'','');
}
function mailme($email, $name='', $params='') { 
    $encMail = encString($email); 
    if(!$name) $name=$email; 
    return '<a href="mailto:'.$encMail.'" '.$params.'>'.$name.'</a>'; 
} 
function encString ($orgStr) { 
    $encStr = ""; 
    $nowStr = ""; 
    $rndNum = -1; 

    $orgLen = strlen($orgStr); 
    for ( $i = 0; $i < $orgLen; $i++) { 
        $encMod = rand(1,2); 
        switch ($encMod) { 
        case 1: // Decimal 
            $nowStr = "&#" . ord($orgStr[$i]) . ";"; 
            break; 
        case 2: // Hexadecimal 
            $nowStr = "&#x" . dechex(ord($orgStr[$i])) . ";"; 
            break; 
        } 
        $encStr .= $nowStr; 
    } 
    return $encStr; 
} 


?>