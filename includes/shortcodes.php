<?php
// Seguridad: Bloquear acceso directo
defined('ABSPATH') || exit;


/**
 *  Creacion de reportes de personas
 */
function render_person_creation_form() {
  ob_start();
  include plugin_dir_path(__FILE__) . '../templates/parts/forms/post-creation-person-form.php'; // Ruta al formulario
  return ob_get_clean();
}
add_shortcode('person_creation_form', 'render_person_creation_form');

/**
 *  Creacion de reportes de mascotas
 */
function render_pet_creation_form() {
  ob_start();
  include plugin_dir_path(__FILE__) . '../templates/parts/forms/post-creation-pets-form.php'; // Ruta al formulario
  return ob_get_clean();
}
add_shortcode('pet_creation_form', 'render_pet_creation_form');

/**
 *  Editar reportes personas
 */
function render_edit_post_person_form() {
  ob_start();
  include plugin_dir_path(__FILE__) . '../templates/parts/forms/edit-post-person-form.php'; // Ruta al formulario de edición
  return ob_get_clean();
}
add_shortcode('edit_post_person_form', 'render_edit_post_person_form');

/**
 *  Editar reportes personas
 */
function render_edit_post_pets_form() {
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/parts/forms/edit-post-pets-form.php'; // Ruta al formulario de edición
    return ob_get_clean();
  }
  add_shortcode('edit_post_pets_form', 'render_edit_post_pets_form');

/**
 *  Mostrar post sin paginación
 */
function humanitarios_combined_posts_shortcode($atts) {
    ob_start();
    
    // Configurar argumentos sin paginación
    $args = shortcode_atts(array(
        'posts_per_page' => 6, // Obtener todos los posts
        'post_status'    => 'publish',
        'orderby'        => 'date'
    ), $atts);

    $args['post_type'] = ['personas_perdidas', 'mascotas_perdidas'];
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) :
        echo '<div class="humanitarios-posts-grid">';
        
        while ($query->have_posts()) : $query->the_post();
            $post_type = get_post_type();
            
            // Construir ruta correcta
            $template_file = ($post_type == 'personas_perdidas') 
                ? 'card-persona.php' 
                : 'card-mascota.php';
            
            $template_path = plugin_dir_path(dirname(__FILE__)) . 'templates/parts/cards/' . $template_file;

            // Verificar existencia del archivo
            if (file_exists($template_path)) {
                include($template_path);
            } else {
                echo '<div class="error">Plantilla no encontrada: ' . esc_html($template_file) . '</div>';
            }
            
        endwhile;
        
        echo '</div>';
        
        wp_reset_postdata();
    else :
        echo '<p class="no-results">No se encontraron publicaciones.</p>';
    endif;
    
    return ob_get_clean();
}
add_shortcode('mostrar_publicaciones', 'humanitarios_combined_posts_shortcode');

/**
 *  Filtro de busqueda
 */

function humanitarios_filtro_shortcode() {
     ob_start();
     
     // Incluir formulario con el JS integrado
     include plugin_dir_path(__FILE__) . '../templates/parts/filters/filter-form.php';
     
     return ob_get_clean();
 }
 add_shortcode('filtro_publicaciones', 'humanitarios_filtro_shortcode');
 

 /**
 *  Shortcode para mostrar las publicaciones del usuario logueado con paginación
 */
function humanitarios_user_posts_shortcode($atts) {
    // Si el usuario no está logueado, mostramos un mensaje
    if ( ! is_user_logged_in() ) {
        return '<p class="no-results">' . __('Debes iniciar sesión para ver tus publicaciones.', 'humanitarios-cpt') . '</p>';
    }
    
    ob_start();
    
    // Configurar argumentos básicos (se pueden sobrescribir mediante atributos en el shortcode)
    $args = shortcode_atts(array(
        'posts_per_page' => 6, // Número de posts a mostrar por página
        'post_status'    => 'publish',
        'orderby'        => 'date'
    ), $atts);

    // Especificar los tipos de post a mostrar
    $args['post_type'] = array('personas_perdidas', 'mascotas_perdidas');
    
    // Limitar la consulta al usuario que está logueado
    $args['author'] = get_current_user_id();

    // Agregar la paginación: detectar la página actual
    $paged = max( 1, get_query_var('paged'), get_query_var('page') );
    $args['paged'] = $paged;
    
    $query = new WP_Query($args);
    
    if ( $query->have_posts() ) :
        echo '<div class="humanitarios-posts-grid">';
        
        while ( $query->have_posts() ) : $query->the_post();
            $post_type = get_post_type();
            
            // Seleccionar la plantilla adecuada según el tipo de post
            $template_file = ($post_type == 'personas_perdidas') 
                ? 'card-persona.php' 
                : 'card-mascota.php';
            
            $template_path = plugin_dir_path(dirname(__FILE__)) . 'templates/parts/cards/' . $template_file;

            // Verificar la existencia del archivo de plantilla
            if ( file_exists($template_path) ) {
                include($template_path);
            } else {
                echo '<div class="error">Plantilla no encontrada: ' . esc_html($template_file) . '</div>';
            }
            
        endwhile;
        
        echo '</div>'; // Cierre de la grilla
        
        // Generar los enlaces de paginación
        $big = 999999999; // número poco probable para reemplazar
        $pagination = paginate_links( array(
            'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'    => '?paged=%#%',
            'current'   => $paged,
            'total'     => $query->max_num_pages,
            'prev_text' => __('« Anterior', 'humanitarios-cpt'),
            'next_text' => __('Siguiente »', 'humanitarios-cpt'),
        ) );
        
        if ( $pagination ) {
            echo '<div class="pagination">' . $pagination . '</div>';
        }
        
        wp_reset_postdata();
    else :
        echo '<p class="no-results">' . __('No se encontraron publicaciones.', 'humanitarios-cpt') . '</p>';
    endif;
    
    return ob_get_clean();
}
add_shortcode('mostrar_publicaciones_usuario', 'humanitarios_user_posts_shortcode');

function humanitarios_registration_form_shortcode() {
    // Verificar si el usuario ya está logueado
    if (is_user_logged_in()) {
        return __('Ya estás registrado.', 'workreap');
    }
    
    // Buffer con verificación de archivo
    ob_start();
    $template_path = plugin_dir_path(dirname(__FILE__)) . 'templates/parts/forms/registration-form.php';
    
    if (file_exists($template_path)) {
        include $template_path;
    } else {
        echo __('Error: Plantilla no encontrada', 'workreap');
    }
    
    return ob_get_clean();
}
add_shortcode('humanitarios_formulario_registro', 'humanitarios_registration_form_shortcode');