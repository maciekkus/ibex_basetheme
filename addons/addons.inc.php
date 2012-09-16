<?php

function enable_nivoslider($default_theme = false) {
  baseibex_enqueue_style('nivo','/addons/sliders/nivo-slider/nivo-slider.css');
  baseibex_enqueue_script('nivojs','/addons/sliders/nivo-slider/jquery.nivo.slider.js',Array('jquery'));
  if ($default_theme) {
  	baseibex_enqueue_style('nivo-default-theme','/addons/sliders/nivo-slider/themes/default/default.css');
  }
  return;	
}
