<?php 

function facebook_init() {

echo "<div id=\"fb-root\"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = \"//connect.facebook.net/pl_PL/all.js#xfbml=1&appId=".$facebook_app_id."\";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>";
}

add_action('baseibex-after-body','facebook_init');

?>