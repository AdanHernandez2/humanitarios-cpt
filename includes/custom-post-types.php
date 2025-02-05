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

        // Configuración de estado inicial
        if ($config['status_config']) {
            $args['register_meta_box_cb'] = 'humanitarios_status_meta_box';
        }

        register_post_type($slug, $args);
    }

    // Registrar estado inicial para nuevos posts
    add_filter('wp_insert_post_data', 'humanitarios_default_post_status', 10, 2);
}
add_action('init', 'humanitarios_register_cpts');


function humanitarios_default_post_status($data, $postarr) {
    if (
        in_array($data['post_type'], ['personas_perdidas', 'mascotas_perdidas']) &&
        $data['post_status'] == 'auto-draft'
    ) {
        $data['post_status'] = 'pending';
    }
    return $data;
}

function humanitarios_status_meta_box($post) {
    add_meta_box(
        'humanitarios-status',
        'Estado del Reporte',
        'humanitarios_status_meta_box_callback',
        null,
        'side',
        'high'
    );
}

function humanitarios_status_meta_box_callback($post) {
    $current_status = $post->post_status;
    $statuses = [
        'publish' => 'Publicado',
        'pending' => 'En Revisión',
        'draft'   => 'Borrador'
    ];
    
    echo '<select name="post_status" id="post_status">';
    foreach ($statuses as $value => $label) {
        printf(
            '<option value="%s"%s>%s</option>',
            $value,
            selected($current_status, $value, false),
            $label
        );
    }
    echo '</select>';
}
