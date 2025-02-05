<?php
// Seguridad: Bloquear acceso directo
defined('ABSPATH') || exit;


// Editar creacion de reportes de personas
function render_person_creation_form() {
  ob_start();
  include plugin_dir_path(__FILE__) . '../templates/parts/forms/post-creation-person-form.php'; // Ruta al formulario
  return ob_get_clean();
}
add_shortcode('person_creation_form', 'render_person_creation_form');

//Editar creacion de reportes de mascotas
function render_pet_creation_form() {
  ob_start();
  include plugin_dir_path(__FILE__) . '../templates/parts/forms/post-creation-pets-form.php'; // Ruta al formulario
  return ob_get_clean();
}
add_shortcode('pet_creation_form', 'render_pet_creation_form');

// Editar reportes personas
function render_edit_post_person_form() {
  ob_start();
  include plugin_dir_path(__FILE__) . '../templates/parts/forms/edit-post-person-form.php'; // Ruta al formulario de edición
  return ob_get_clean();
}
add_shortcode('edit_post_person_form', 'render_edit_post_person_form');

// Filtro de reportes
function render_filter_form() {
  ob_start();
  include plugin_dir_path(__FILE__) . '../templates/parts/filters/filter-form.php'; // Ruta al formulario de edición
  return ob_get_clean();
}
add_shortcode('filter_form', 'render_filter_form');

// mostrar post 
// Mostrar post sin paginación
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

 add_shortcode('filtro_publicaciones', 'humanitarios_filtro_shortcode');
 function humanitarios_filtro_shortcode() {
     ob_start();
     
     // Incluir formulario con el JS integrado
     include plugin_dir_path(__FILE__) . '../templates/parts/filters/filter-form.php';
     
     return ob_get_clean();
 }