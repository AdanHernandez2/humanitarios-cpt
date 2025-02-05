<?php
defined('ABSPATH') || exit;

add_action('wp_ajax_humanitarios_filter_posts', 'humanitarios_filter_posts');
add_action('wp_ajax_nopriv_humanitarios_filter_posts', 'humanitarios_filter_posts');

function humanitarios_filter_posts() {
    // Verificar nonce
    check_ajax_referer('humanitarios_filter_nonce', 'security');

    // Parámetros base
    $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $posts_per_page = 6;
    
    // Validar post_type
    $post_type = '';
    if (!empty($_POST['post_type'])) {
        $allowed_types = ['personas_perdidas', 'mascotas_perdidas'];
        $post_type = in_array($_POST['post_type'], $allowed_types) 
            ? sanitize_text_field($_POST['post_type']) 
            : '';
    }

    $args = [
        'post_type'      => $post_type ? [$post_type] : ['personas_perdidas', 'mascotas_perdidas'],
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

    // Ubicación
    if (!empty($_POST['ubicacion'])) {
        $meta_query[] = [
            'key' => 'ubicacion',
            'value' => sanitize_text_field($_POST['ubicacion']),
            'compare' => 'LIKE'
        ];
    }

    // Rango de fechas
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
    if ($post_type === 'personas_perdidas') {
        // Edad
        if (!empty($_POST['edad_min']) || !empty($_POST['edad_max'])) {
            $edad_query = ['key' => 'edad'];
            
            if (!empty($_POST['edad_min']) && !empty($_POST['edad_max'])) {
                $edad_query['value'] = [
                    absint($_POST['edad_min']),
                    absint($_POST['edad_max'])
                ];
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

        // Género
        if (!empty($_POST['genero'])) {
            $meta_query[] = [
                'key' => 'genero',
                'value' => sanitize_text_field($_POST['genero'])
            ];
        }
    }

    if ($post_type === 'mascotas_perdidas') {
        // Tipo de animal
        if (!empty($_POST['tipo_animal'])) {
            $meta_query[] = [
                'key' => 'tipo_animal',
                'value' => sanitize_text_field($_POST['tipo_animal'])
            ];
        }

        // Tamaño
        if (!empty($_POST['tamanio'])) {
            $meta_query[] = [
                'key' => 'tamanio',
                'value' => sanitize_text_field($_POST['tamanio'])
            ];
        }
    }

    if (!empty($meta_query)) {
        $args['meta_query'] = $meta_query;
    }

    // Ejecutar query
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $template_file = (get_post_type() === 'personas_perdidas') 
                ? 'card-persona.php' 
                : 'card-mascota.php';
            
            $template_path = plugin_dir_path(__FILE__) . "../templates/parts/cards/{$template_file}";
            
            if (file_exists($template_path)) {
                include $template_path;
            }
        }
    
        // Paginación: Mueve el botón "Cargar más" al final después de los nuevos posts cargados
        if ($query->max_num_pages > $page) {
            echo '<button class="load-more" data-page="' . ($page + 1) . '">'
                . __('Cargar más resultados', 'humanitarios-cpt') . 
                '</button>';
        }
    } else {
        echo '<div class="no-results">' . __('No se encontraron publicaciones', 'humanitarios-cpt') . '</div>';
    }

    wp_reset_postdata();
    echo ob_get_clean();
    wp_die();
}