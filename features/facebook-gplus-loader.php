<?php 

function facebook_init() {
	echo '<div id="fb-root"></div>';
	return;
}

add_action('baseibex-after-body','facebook_init');

function async_fb_gplus_loader() {
	?>
	<script type="text/javascript">
	(function(w, d, s) {
    w.___gcfg = { lang: 'pl' };
    function go(){
        var js, fjs = d.getElementsByTagName(s)[0], load = function(url, id) {
            if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.src = url; js.id = id;
            fjs.parentNode.insertBefore(js, fjs);
        };
   	    load(('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js', 'ga');
        load('//connect.facebook.net/pl_PL/all.js#appId=<?php echo $facebook_app_id; ?>&xfbml=1', 'fbjssdk');
        load('https://apis.google.com/js/plusone.js', 'gplus1js');
    }
    if (w.addEventListener) { w.addEventListener("load", go, false); }
    else if (w.attachEvent) { w.attachEvent("onload",go); }
	}(window, document, 'script'));
	</script>
	<?php
	return;
}
add_action('wp_footer','async_fb_gplus_loader');