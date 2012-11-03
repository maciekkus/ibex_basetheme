<?php
define('IBEX_DEFAULT_EXCERPT_ALLOWED_TAGS','<p><a><strong><b><em><ul><li><ol>');
define('IBEX_EXCERPT_ALLOWED_TAGS','ibex_allowed_tags');

global $base_template_directory;
global $base_template_directory_uri;
global $current_template_directory;
global $current_template_directory_uri;

$base_template_directory = get_template_directory();
$base_template_directory_uri = get_template_directory_uri();
$current_template_directory = get_stylesheet_directory();
$current_template_directory_uri = get_stylesheet_directory_uri();

/* includes */
include ('lib/wordpress.snippets.php');

include ('features/custom-shortcodes.php');
include ('features/snippets-post-images.php');


include ('features/facebook-gplus-loader.php');
include ('features/custom-title-post-page.php');

/* load metaboxes class */
include ('lib/meta-box.class.php');
 
/* load functions for enabling/disabling addons */
include ('addons/addons.inc.php');

add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );

set_post_thumbnail_size( '160', '200', false); 


/* load theme settings class */
if ( file_exists( $base_template_directory.'/lib/theme-settings.class.php' ) ) {
	require_once( $base_template_directory.'/lib/theme-settings.class.php' );
}

/* load css/js  */

/* first: use jquery from cdn
 *  wersja jquery -> domylsnie 1, chyba, że w ustawieniach themes zmienimy
 *  
 */

/* 
 * helpers: register custom styles and scripts in baseibex theme and in childtheme
 * 
 */

function baseibex_enqueue_style($handle,$filename,$deps = Array()) {
	global $base_template_directory;
	global $base_template_directory_uri;
	if ( file_exists($base_template_directory.$filename) ) {
	   	   wp_register_style($handle, $base_template_directory_uri.$filename, $deps, baseibextheme_option('base_style_version',''));
		   wp_enqueue_style($handle);
	}
	return;	
}
function baseibex_enqueue_script($handle,$filename,$deps = Array(),$in_footer = true) {
	global $base_template_directory;
	global $base_template_directory_uri;
	if ( file_exists($base_template_directory.$filename) ) {
		   wp_register_script($handle, $base_template_directory_uri.$filename, $deps, baseibextheme_option('base_style_version',''),$in_footer);
		   wp_enqueue_script($handle);
	}	
	return;	
}
function childtheme_enqueue_style($handle,$filename,$deps = Array()) {
	global $current_template_directory;
	global $current_template_directory_uri;
	if ( file_exists($current_template_directory.$filename) ) {
	   	   wp_register_style($handle, $current_template_directory_uri.$filename, $deps, baseibextheme_option('base_style_version',''));
		   wp_enqueue_style($handle);
	}
	return;	
}
function childtheme_enqueue_script($handle,$filename,$deps = Array(),$in_footer = true) {
	global $current_template_directory;
	global $current_template_directory_uri;
	if ( file_exists($current_template_directory.$filename) ) {
		   wp_register_script($handle, $current_template_directory_uri.$filename, $deps, baseibextheme_option('base_style_version',''),$in_footer);
		   wp_enqueue_script($handle);
	}	
	return;	
}

function baseibex_enqueue() {
	// jquery only for non-admin
	if (!is_admin()) {
        // comment out the next two lines to load the local copy of jQuery
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/'.baseibextheme_option('jquery_version','1').'/jquery.min.js',false,false, true);
        wp_enqueue_script('jquery');

		/* modernizr */
		if (!baseibextheme_option('disable_modernizr_script',false)) {
	    	baseibex_enqueue_script('modernizr', '/js/libs/modernizr-2.6.2.min.js',false,false); // make sure it's loaded in <head> - modernizr requires this? 
		}        
        /* colorbox js */
	    baseibex_enqueue_script('ibex_colorbox', '/js/jquery.colorbox-min.js', Array('jquery'));
    	
	    /* colorbox css */
	    baseibex_enqueue_style('colorbox_style', '/css/colorbox-css/colorbox.'.baseibextheme_option('colorbox_style','black').'.css');
	    
		/* base theme default javascript */
		baseibex_enqueue_script('base_script', '/js/script.js', Array('jquery'));

		baseibex_enqueue_script('plugins_script', '/js/plugins.js', Array('jquery'));
		/* base theme default css */
		if (!baseibextheme_option('disable_base_style',false)) {
	    	baseibex_enqueue_style('base_style', '/css/main.css'); // html5boilerplate
		}

		if (!baseibextheme_option('disable_normalize_style',false)) {
	    	baseibex_enqueue_style('normalize_style', '/css/normalize.min.css'); // normalize
		}

		/* child theme default javascript */
		childtheme_enqueue_script('theme_script', '/js/custom.js', Array('jquery'));
		
		/* child theme default css */
	    childtheme_enqueue_style('theme_style', '/style.css');
    }
}
add_action('wp_loaded', 'baseibex_enqueue',0);
//add_action('wp_enqueue_scripts', 'baseibex_enqueue',0);

add_filter( 'script_loader_src', 'remove_src_version' );
add_filter( 'style_loader_src', 'remove_src_version' );

function remove_src_version ( $src ) {

  global $wp_version;

  $version_str = '?ver='.$wp_version;
  $version_str_offset = strlen( $src ) - strlen( $version_str );

  if( substr( $src, $version_str_offset ) == $version_str )
    return substr( $src, 0, $version_str_offset );
  else
    return $src;

}


function add_googleplus_publisher() {
	if (baseibextheme_option('gplus_profile_id','')) {
		echo '<link href="https://plus.google.com/'.baseibextheme_option('gplus_profile_id','').'" rel="publisher" />';
	}
}
add_action('wp_head','add_googleplus_publisher');

//define('IBEX_EXCERPT_LENGTH', 'ibex_excerpt_length');
//define('IBEX_DEFAULT_EXCERPT_LENGTH', 310);

remove_shortcode('gallery');
add_shortcode('gallery','minigallery_shortcode');

function ibex_get_post_format() {
	global $post;
	if ($ibex_post_format = get_post_meta($post->ID, '_ibex_post_layout',true)) {
		return $ibex_post_format;
	} else {
		return '';
	}
}

function default_page_header() {
	global $post;
	if ($post_title = get_post_meta($post->ID, '_ibex_post_title',true)) {}
	  else { $post_title = get_the_title(); } 
	
	echo apply_filters('default_page_h1','<header><h1><a href="'.get_permalink().'">'.$post_title.'</a></h1>'.default_page_meta()."</header>");
}

function default_post_header() {
	global $post;
	if ($post_title = get_post_meta($post->ID, '_ibex_post_title',true)) {}
	  else { $post_title = get_the_title(); } 
	
	echo apply_filters('default_post_h1','<header><h1><a href="'.get_permalink().'">'.get_the_title().'</a></h1>'.default_post_meta()."</header>");
}

function default_post_meta() {
	global $post;
	$date = get_the_time('j F Y'); //', godz. '.get_the_time('G:i');
	$html5date = get_the_time('Y-m-j'); //', godz. '.get_the_time('G:i');
	return apply_filters('default_post_meta','<small class="single-post-meta">opublikowane: <time datetime="'.$html5date.'">'.$date.'</time></small>');
}

function default_page_meta() {
	return apply_filters('default_page_meta','');
}

function ibex_more_link($more_link_text) {
	global $post;
	return ' <a href="' . get_permalink() . "\" class=\"more-link\">Czytaj dalej &raquo;</a>";	
}
add_filter('the_content_more_link','ibex_more_link',100);

function ibex_substr($str, $length) {
    $str = html_entity_decode(trim(strip_tags($str)), ENT_QUOTES, 'UTF-8');
    if( mb_strlen($str)>$length )
        $str = rtrim(mb_substr($str, 0, $length), '.').'&hellip;';
    return $str;
}

function blank_caption_shortcode($atts, $content='') {
    return $content;
}

function strip_html_tags( $text, $allowedtags=null ) {
    $text = preg_replace(
        array(
            // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
            '@\<a.*\>\<\/a\>@siu',
        	'@\<strong\>\<\/strong\>@siu',
        // Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ','','',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
        ),
        $text
    );
    return strip_tags( $text, $allowedtags );
}

function ibex_gallery_excerpt_more($txt) {
	global $post;
	return '<a class="more-link" href="'.get_permalink().'">Zobacz wszystkie zdjęcia &raquo;</a>';
}

function ibex_get_the_excerpt($excerpt_length=null, $allowedtags=null, $strip_html_tags = true) {
    remove_shortcode('caption');
    add_shortcode('caption','blank_caption_shortcode');
    global $id, $post;
    if($allowedtags===null) {
        $allowedtags = get_option(IBEX_EXCERPT_ALLOWED_TAGS, IBEX_DEFAULT_EXCERPT_ALLOWED_TAGS);
    }
    $output = $post->post_excerpt;
    if ($output == '') {
        $output = explode('<!--more-->', $post->post_content);
        $output = wpautop($output[0]);
		$output = do_shortcode($output);
        
        if ( $strip_html_tags ) {
            $output = strip_html_tags($output, $allowedtags);
        }
        if($excerpt_length) {
            $output = ibex_substr($output, $excerpt_length);
        }
        $output = force_balance_tags($output);
        //$output = wpautop($output);
    }
    else if ( $strip_html_tags ) {
        $output = strip_html_tags($output, $allowedtags);
    }
    remove_shortcode('caption');
    add_shortcode('caption','img_caption_shortcode');
	$output .= apply_filters('the_content_more_link','');
    return $output;
}

function ibex_get_image_resized($file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
	// zalozenie że mamy do czynienia z lokalnymi plikami 
	// @todo: jakiś filtr, który doda dodatkową funkcjonalnosc do sciagniecia pliku zanim zostanie wygenerwana miniatura

	if ( !$suffix ) {
		$suffix = "{$max_w}x{$max_h}";
	}
	$info = parse_url($file);
	$file = $info['path']; // pozbywamy się ewentualnej domeny
	
	$file = str_replace('//','/',ABSPATH.$file); // generuję ścieżkę do fizycznego pliku
	 
	$info = pathinfo($file);

	$dir = $info['dirname'];
	$ext = $info['extension'];
	$name = basename($file, ".{$ext}");
	if ( !is_null($dest_path) and $_dest_path = realpath($dest_path) ) {
		$dir = $_dest_path;
	} else {
		$dir = str_replace('//','/',ABSPATH.$dir);
	}
	$resized_file = "{$dir}/{$name}-{$suffix}.{$ext}";
	
	if (is_object($resized_file)) {
		$resized_file = '';
	} 
	
	if (!file_exists($resized_file)) {
		$resized_file = image_resize($file,$max_w,$max_h,$crop,$suffix,$dest_path,$jpeg_quality);
	}	
	
	if (is_object($resized_file)) {
		$resized_file = '';
	} 
	$resized_file = str_replace(ABSPATH,'/',$resized_file);
	if ($resized_file == '') {
		$resized_file = $file;
		$resized_file = str_replace(ABSPATH,'/',$resized_file);
	}
	return $resized_file;
}

function ibex_search_post_for_thumbnail($size, $postId = null) {
    if($postId !== null && is_numeric($postId)) {
        $post = get_post($postId);
    } else {
        global $post;
        $postId = $post->ID;
    }
    $size_tag = '';
    $width = get_option('thumbnail_size_w',120);
    $height = get_option('thumbnail_size_g',120);
    $crop = get_option('thumbnail_crop',0);
	if (is_array($size)) {
		$width = $size[0];
		$height = $size[1];
    	$size_tag = '';
	} else {
		$size_tag = $size;
	}
	
    if ($_width = get_post_meta($post->ID, '_ibex_thumbnail_width','')) {
    	$width = $_width;
        $size_tag = '';
    }
    if ($_height = get_post_meta($post->ID, '_ibex_thumbnail_height','')) {
    	$height = $_height;
        $size_tag = '';
    }

    if ( has_post_thumbnail($postId) ) {
        $post_thumbnail_id = get_post_thumbnail_id( $postId );
        if ($size == 'thumbnail') {
        	$thumb = wp_get_attachment_image_src( $post_thumbnail_id, $size_tag );
        	return $thumb[0];
        } else {
        	$thumb = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
        	return ibex_get_image_resized($thumb[0],$width,$height, $crop );
        }
    }
    
    //$content2 = do_shortcode($post->post_content);
	$content2 = $post->post_content;
    
    $args = array(
        'post_type' => 'attachment',
        'numberposts' => 1,
        'post_status' => null,
        'post_parent' => $postId,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    	'post_mime_type' => 'image'
    );
    $attachments = get_posts($args);
	$post_content = '';
    if ($attachments) {
        foreach($attachments as $image) {
            $post_content .= '<img src="'.$image->guid.'"/>';
        }
    }
    $post_content .= $content2;

    preg_match_all('/<img.+?src=["\']([^"\']+?(\.jpg|\.gif|\.jpeg|\.png))["\']/ims', $post_content, $matches);

    if(count($matches[1])) {
        return ibex_get_image_resized($matches[1][0], $width, $height, $crop );
    }
    return ibex_get_image_resized(ibex_get_video_thumbnail($post->post_content), $width, $height);
}

function ibex_get_video_thumbnail($url) {
    if (preg_match('%youtube\.com\/(?:watch\?v=|v\/|vi\/)([^\?&"\[\]]+)%ims', $url, $match)) {
        return 'http://img.youtube.com/vi/'.$match[1].'/0.jpg';
    }
}
global $CDN_CLOUDFRONT;
$CDN_CLOUDFRONT = baseibextheme_option('cdn_domain',false);

function ibex_base_cdn_process($match) {
	global $CDN_CLOUDFRONT;
	return '"http://'.$CDN_CLOUDFRONT.'/'.(str_replace('/','/',$match[2])).'"';
}

function ibex_base_url_to_regexp($url) {
//	return '/\"('.str_replace('/','\/',$url).')(.+?\.(jpg)+?)\"/i';
	return '/\"('.str_replace('/','\/',$url).')(.+?\.(jpg|gif|png)+?)\"/i';
}
function ibex_base_parseCDNImages($text) {
	global $CDN_CLOUDFRONT;
	if ($CDN_CLOUDFRONT) {
		$urls_to_rewrite = Array('/');
		$matches = array_map('ibex_base_url_to_regexp',$urls_to_rewrite);
	 	return preg_replace_callback($matches,'ibex_base_cdn_process',$text);
	} else {
	   return $text;
	}
}

// add_filter('the_content','ibex_base_parseCDNImages',2000);
 
/* MINIFY - EXTERNAL */
/* required: /min - with latest minify
 * config - look at firephp warnings to include symlinks dir to minify
 * bwp-minify plugin (modified with hooks)
 */ 

define ('STATIC_PATH','static/');
function ibex_bwp_cache_minify_url($url) {
	$url_hash = md5($url);
	$minify_dir = ABSPATH . STATIC_PATH;
	if (WP_DEBUG) {
		require_once('FirePHP/fb.php');
		$firephp = FirePHP::getInstance(true);
		$firephp->info($url_hash,$url);
	}
	
	if (strpos($url, 'static/') !== FALSE) {
		return $url;
	}
	if (strpos($url, '.css')) {
		$ext = '.css';
	} else if (strpos($url, '.js')) {
		$ext = '.js';
	} 
	else {
		$ext = '';
	}
	 
	if (!file_exists($minify_dir.$url_hash.$ext)) {
		if (substr($url, 0,7) != 'http://') {
			$url = get_bloginfo('url') . $url;
		} 
		$response = wp_remote_get($url);
		if (WP_DEBUG) {
			$firephp->info(wp_remote_retrieve_response_code($response),$url);
		}
		if (wp_remote_retrieve_response_code($response) < 400) { 
			$data =  wp_remote_retrieve_body($response);
			if ($data) {
				file_put_contents($minify_dir.$url_hash.$ext,$data);
				file_put_contents($minify_dir.$url_hash.$ext.'.gz', gzencode( $data,9));
				$url = '/'.STATIC_PATH.$url_hash.$ext;
			}
		} 
	} else {
		$url = '/'.STATIC_PATH.$url_hash.$ext;
	}
	
	return $url;
}

function ibex_bwp_get_minify_tag($return, $string, $type, $media = '')
{
	global $bwp_minify;
	if (empty($string))
		return '';

	switch ($type)
	{
		case 'script':
			$return  = "<script type='text/javascript' src='" . ibex_bwp_cache_minify_url($bwp_minify->get_minify_src($string)) . "'></script>\n";
		break;
		
		case 'style':
		default:			
			$return = "<link rel='stylesheet' type='text/css' media='all' href='" . ibex_bwp_cache_minify_url($bwp_minify->get_minify_src($string)) . "' />\n";
		break;

		case 'media':
			$return = "<link rel='stylesheet' type='text/css' media='$media' href='" . ibex_bwp_cache_minify_url($bwp_minify->get_minify_src($string)) . "' />\n";
		break;
	}

	return $return;
}

if (!is_admin()) {
	add_filter('bwp_get_minify_tag','ibex_bwp_get_minify_tag',1,4);
}

/* this snippet moves scripts from <head> to <footer> */
/*
function before_head_scripts() {
	global $bwp_minify;
	$bwp_minify->footer_scripts = array_merge($bwp_minify->header_scripts,$bwp_minify->footer_scripts[0]);
	$bwp_minify->header_scripts = Array();
}
add_action('bwp_minify_before_header_scripts','before_head_scripts');
 */
 
?>