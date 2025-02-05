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

    // Acción para manejar el cambio de estado
    add_action('transition_post_status', 'humanitarios_notify_post_status_change', 10, 3);
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

/**
 * Enviar correo al autor cuando un post cambie de pendiente a publicado
 */
function humanitarios_notify_post_status_change($post_id) {
    $post = get_post($post_id);
    if (!$post) {
        return;
    }

    // Obtener el autor
    $author = get_user_by('id', $post->post_author);
    if (!$author || empty($author->user_email)) {
        error_log('❌ Error: No se encontró el correo del autor.');
        return;
    }

    $subject = '';
    $message = '';

    // Verificar el estado del post y definir el asunto y mensaje
    if ($post->post_status === 'pending') {
        // El post está pendiente
        $subject = 'Tu publicación está pendiente de aprobación';
        
        // Cargar plantilla para post pendiente
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/emails/post-pendiente.php';
        $message = ob_get_clean();

    } elseif ($post->post_status === 'publish') {
        // El post fue aprobado (cambio a 'publish')
        $subject = 'Tu publicación ha sido aprobada';
        
        // Cargar plantilla para post aprobado
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/emails/post-aprobado.php';
        $message = ob_get_clean();
    }

    // Si el mensaje ha sido definido, enviamos el correo
    if (!empty($message)) {
        // Enviar email al autor
        $sent = wp_mail(
            $author->user_email,
            $subject,
            $message,
            ['Content-Type: text/html; charset=UTF-8']
        );

        if ($sent) {
            error_log('✅ Correo enviado al autor: ' . $author->user_email);
        } else {
            error_log('❌ Error: No se pudo enviar el correo al autor.');
        }
    }
}

