<?php
defined('ABSPATH') || exit;

function humanitarios_register_cpts() {
    $cpts = [
        'personas_perdidas' => [
            'label'         => 'Personas Perdidas',
            'icon'          => 'dashicons-admin-users',
            'supports'      => ['title', 'author', 'thumbnail', 'custom-fields'],
            'status_config' => true
        ],
        'mascotas_perdidas' => [
            'label'         => 'Mascotas Perdidas',
            'icon'          => 'dashicons-pets',
            'supports'      => ['title', 'author', 'thumbnail', 'custom-fields'],
            'status_config' => true
        ],
        'lost_objects' => [
            'label'         => 'Objetos Perdidos',
            'icon'          => 'dashicons-search',
            'supports'      => ['title', 'author', 'thumbnail', 'custom-fields'],
            'status_config' => true
        ],
        'found-form' => [
            'label'         => 'Encontrados',
            'icon'          => 'dashicons-megaphone',
            'supports'      => ['title', 'author', 'thumbnail', 'custom-fields'],
            'status_config' => true
        ]
    ];

    foreach ($cpts as $slug => $config) {
        $args = [
            'labels'              => [
                'name'               => $config['label'],
                'singular_name'      => $config['label'],
                'add_new_item'       => 'Agregar Nueva ' . $config['label'],
                'edit_item'          => 'Editar ' . $config['label'],
                'new_item'           => 'Nueva ' . $config['label'],
                'view_item'          => 'Ver ' . $config['label'],
                'search_items'       => 'Buscar ' . $config['label'],
                'not_found'          => 'No se encontraron ' . $config['label'],
                'not_found_in_trash' => 'No se encontraron ' . $config['label'] . ' en la papelera'
            ],
            'public'              => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_rest'        => true,
            'menu_position'       => 5,
            'menu_icon'           => $config['icon'],
            'capability_type'     => 'post',
            'supports'            => $config['supports'],
            'rewrite'             => ['slug' => sanitize_title($config['label'])],
        ];

        register_post_type($slug, $args);
    }

    // Registrar estado inicial para nuevos posts
    add_filter('wp_insert_post_data', 'humanitarios_default_post_status', 10, 2);
}
add_action('init', 'humanitarios_register_cpts');

function humanitarios_default_post_status($data, $postarr) {
    if (
        in_array($data['post_type'], ['personas_perdidas', 'mascotas_perdidas', 'lost_objects', 'found-form']) && // Cambiado de 'encontrados' a 'found-form'
        $data['post_status'] == 'auto-draft'
    ) {
        $data['post_status'] = 'pending';
    }
    return $data;
}