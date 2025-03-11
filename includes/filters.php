<?php
defined('ABSPATH') || exit;

add_action('wp_ajax_humanitarios_filter_posts', 'humanitarios_filter_posts');
add_action('wp_ajax_nopriv_humanitarios_filter_posts', 'humanitarios_filter_posts');

function humanitarios_filter_posts() {
    // Verificar nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'humanitarios_filter_nonce')) {
        wp_send_json_error('Nonce verification failed');
        wp_die();
    }

    // Parámetros base
    $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $posts_per_page = 6;
    
    // Validar post_type
    $post_type = '';
    $allowed_types = ['personas_perdidas', 'mascotas_perdidas', 'lost_objects'];
    
    if (!empty($_POST['post_type']) && in_array($_POST['post_type'], $allowed_types)) {
        $post_type = sanitize_text_field($_POST['post_type']);
    }

    // Configurar argumentos de la query
    $args = [
        'post_type'      => $post_type ? [$post_type] : $allowed_types,
        'posts_per_page' => $posts_per_page,
        'paged'          => $page,
        'post_status'    => 'publish'
    ];

    // Búsqueda por texto
    if (!empty($_POST['s'])) {
        $args['s'] = sanitize_text_field($_POST['s']);
    }

    // Construir meta_query
    $meta_query = [];

    // Filtro común: Ubicación
    if (!empty($_POST['ubicacion'])) {
        $meta_query[] = [
            'key' => 'ubicacion',
            'value' => sanitize_text_field($_POST['ubicacion']),
            'compare' => 'LIKE'
        ];
    }

    // Filtro común: Rango de fechas
    if (!empty($_POST['fecha_desde']) || !empty($_POST['fecha_hasta'])) {
        $date_query = [];
        
        if (!empty($_POST['fecha_desde'])) {
            $date_query['after'] = sanitize_text_field($_POST['fecha_desde']);
        }
        
        if (!empty($_POST['fecha_hasta'])) {
            $date_query['before'] = sanitize_text_field($_POST['fecha_hasta']);
        }
        
        $date_query['inclusive'] = true;
        $args['date_query'] = [$date_query];
    }

    // Filtros específicos por tipo
    switch($post_type) {
        case 'personas_perdidas':
            // Filtro de edad
            if (!empty($_POST['edad_min']) || !empty($_POST['edad_max'])) {
                $edad_query = ['key' => 'edad'];
                
                if (!empty($_POST['edad_min']) && !empty($_POST['edad_max'])) {
                    $edad_query['value'] = [absint($_POST['edad_min']), absint($_POST['edad_max'])];
                    $edad_query['compare'] = 'BETWEEN';
                } elseif (!empty($_POST['edad_min'])) {
                    $edad_query['value'] = absint($_POST['edad_min']);
                    $edad_query['compare'] = '>=';
                } else {
                    $edad_query['value'] = absint($_POST['edad_max']);
                    $edad_query['compare'] = '<=';
                }
                
                $meta_query[] = $edad_query;
            }

            // Filtro de género
            if (!empty($_POST['genero'])) {
                $meta_query[] = [
                    'key' => 'genero',
                    'value' => sanitize_text_field($_POST['genero'])
                ];
            }
            break;

        case 'mascotas_perdidas':
            // Filtro tipo de animal
            if (!empty($_POST['tipo_animal'])) {
                $meta_query[] = [
                    'key' => 'tipo_animal',
                    'value' => sanitize_text_field($_POST['tipo_animal'])
                ];
            }

            // Filtro tamaño
            if (!empty($_POST['tamanio'])) {
                $meta_query[] = [
                    'key' => 'tamanio',
                    'value' => sanitize_text_field($_POST['tamanio'])
                ];
            }
            break;

        case 'lost_objects':
            // Filtro estado del objeto
            if (!empty($_POST['estado_objeto'])) {
                $meta_query[] = [
                    'key' => 'estado_objeto',
                    'value' => sanitize_text_field($_POST['estado_objeto'])
                ];
            }
            break;
    }

    // Añadir meta_query a los argumentos si hay filtros
    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    // Ejecutar la consulta
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Determinar template según post type
            $template_mapping = [
                'personas_perdidas' => 'card-persona.php',
                'mascotas_perdidas' => 'card-mascota.php',
                'lost_objects'      => 'card-objects.php'
            ];
            
            $template_file = $template_mapping[get_post_type()] ?? 'card-default.php';
            $template_path = plugin_dir_path(__FILE__) . "../templates/parts/cards/{$template_file}";
            
            if (file_exists($template_path)) {
                include $template_path;
            }
        }

        // Paginación
        if ($query->max_num_pages > $page) {
            echo '<button class="load-more" data-page="' . ($page + 1) . '">'
                . __('Cargar más resultados', 'humanitarios-cpt') . 
                '</button>';
        }
    } else {
        echo '<div class="no-results">' . __('No se encontraron publicaciones', 'humanitarios-cpt') . '</div>';
    }

    wp_reset_postdata();
    $output = ob_get_clean();

    // Limpiar buffers residuales
    while (ob_get_level() > 0) ob_end_clean();

    // Enviar respuesta sin escapes adicionales
    wp_send_json_success($output);
    wp_die();
}
// Registrar acciones AJAX para usuarios logueados y no logueados
add_action('wp_ajax_humanitarios_filter_encontrados', 'humanitarios_filter_encontrados');
add_action('wp_ajax_nopriv_humanitarios_filter_encontrados', 'humanitarios_filter_encontrados');

function humanitarios_filter_encontrados() {
    try {
        // Verificar nonce
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'humanitarios_filter_encontrados_nonce')) {
            throw new Exception(__('Error de seguridad', 'humanitarios-cpt'));
        }

        // Parámetros base
        $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
        $posts_per_page = 6;
        $args = [
            'post_type'      => 'found-form',
            'posts_per_page' => $posts_per_page,
            'paged'          => $page,
            'post_status'    => 'publish'
        ];

        // Construir meta_query
        $meta_query = [];

        // Filtro: Tipo de encontrado
        if (!empty($_POST['tipo_encontrado'])) {
            $meta_query[] = [
                'key' => 'tipo_encontrado',
                'value' => sanitize_text_field($_POST['tipo_encontrado'])
            ];
        }

        // Filtro: Ubicación
        if (!empty($_POST['ubicacion'])) {
            $meta_query[] = [
                'key' => 'provincia_encontrado',
                'value' => sanitize_text_field($_POST['ubicacion']),
                'compare' => 'LIKE'
            ];
        }

        // Filtro: Fecha encontrado
        if (!empty($_POST['fecha_encontrado'])) {
            $meta_query[] = [
                'key' => 'fecha_encontrado',
                'value' => sanitize_text_field($_POST['fecha_encontrado']),
                'compare' => '>='
            ];
        }

        // Aplicar meta_query si hay filtros
        if (!empty($meta_query)) {
            $args['meta_query'] = $meta_query;
        }

        // Ejecutar la consulta
        $query = new WP_Query($args);
        
        ob_start();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                include plugin_dir_path(__FILE__) . '../templates/parts/cards/card-found.php';
            }

            // Paginación
            if ($query->max_num_pages > $page) {
                echo '<button class="load-more" data-page="' . ($page + 1) . '">'
                    . __('Cargar más resultados', 'humanitarios-cpt') . 
                    '</button>';
            }
        } else {
            echo '<div class="no-results">' . __('No se encontraron publicaciones', 'humanitarios-cpt') . '</div>';
        }

        wp_reset_postdata();
        $output = ob_get_clean();

        // Limpiar buffers residuales
        while (ob_get_level() > 0) ob_end_clean();

        // Enviar respuesta
        wp_send_json_success($output);

    } catch (Exception $e) {
        wp_send_json_error($e->getMessage());
    }
    
    wp_die();
}