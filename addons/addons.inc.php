<?php

function enable_nivoslider($default_theme = false) {
  baseibex_enqueue_style('nivo','/addons/sliders/nivo-slider/nivo-slider.css');
  baseibex_enqueue_script('nivojs','/addons/sliders/nivo-slider/jquery.nivo.slider.js',Array('jquery'));
  if ($default_theme) {
  	baseibex_enqueue_style('nivo-default-theme','/addons/sliders/nivo-slider/themes/default/default.css');
  }
  return;	
}

function enable_carouFredSel() {
  baseibex_enqueue_script('carouFredSel','/addons/sliders/carousel/jquery.carouFredSel-6.1.0-packed.js',Array('jquery'));
  return;	
}