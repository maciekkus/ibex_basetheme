<?php
global $meta_boxes;
if (!isset($meta_boxes)) {
	$meta_boxes = array();
}
$meta_boxes[] = array(
    'id' => 'post-data',
    'title' => 'Dodatkowe pola',
    'pages' => array('post','page'),
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => '<b>Alternatywny tytuł:</b>',
            'desc' => 'Wypełnij pole jeśli wyświetlany tytuł ma być inny niż tytuł wpisu/strony',
            'id' => '_ibex_post_title',
            'type' => 'textarea',
            'std' => ''
        ),
    )
);

global $custom_post_types;
if (is_array($custom_post_types)) {       						  
	$meta_boxes[] = array(
	    'id' => 'post-layout',
	    'title' => 'Rodzaj wpisu',
	    'pages' => array('post'),
	    'context' => 'side',
	    'priority' => 'high',
	    'fields' => array(
	        array(
	            'name' => '<b>Wpis typu:</b>',
	            'desc' => 'Wybierz sposób wyświetlania wpisu',
	            'id' => '_ibex_post_layout',
	            'type' => 'select',
	            'std' => '',
	            'options' => $custom_post_types
	        ),
	    )
	);
}