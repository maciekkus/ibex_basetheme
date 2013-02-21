<?php
add_filter( 'cmb_meta_boxes', 'basetheme_default_post_meta_boxes' , 1);

function basetheme_default_post_meta_boxes( array $meta_boxes ) {

    // Start with an underscore to hide fields from custom fields list
    $prefix = '_ibex_';

    $meta_boxes['default-post-data'] = array(
        'id' => 'post-data',
        'title' => 'Dodatkowe pola',
        'pages' => array('post','page'),
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true,
        'fields' => array(
            array(
                'name' => 'Alternatywny tytuł:',
                'desc' => 'Wypełnij pole jeśli wyświetlany tytuł (np. w menu, na stronie głównej) ma być inny niż tytuł wpisu/strony',
                'id' => $prefix . 'post_title',
                'type' => 'text',
                'std' => ''
            ),
        )
    );

    global $custom_post_types;
    if (is_array($custom_post_types)) {
        $meta_boxes['default-post-layout'] = array(
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

    return $meta_boxes;
}
