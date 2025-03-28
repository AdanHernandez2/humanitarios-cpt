<?php
/**
 * Archive Template para Personas Perdidas
 */

get_header();

// Configurar query específico para el CPT
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'post_type'      => 'personas_perdidas',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'paged'          => $paged
);

$query = new WP_Query($args);
?>

<div class="humanitarios-archive-container">
    <h1 class="archive-title"><?php post_type_archive_title(); ?></h1>
    
    <?php if ($query->have_posts()) : ?>
        <div class="humanitarios-posts-grid">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php 
                $template_path = HUMANITARIOS_CPT_PATH . 'templates/parts/cards/card-persona.php';
                
                if (file_exists($template_path)) {
                    include($template_path);
                } else {
                    echo '<div class="error">Plantilla de card no encontrada</div>';
                }
                ?>
            <?php endwhile; ?>
        </div>
        
        <!-- Paginación mejorada -->
        <div class="humanitarios-pagination">
            <?php
            $big = 999999999; // Número improbable
            echo paginate_links(array(
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => '?paged=%#%',
                'current'   => max(1, $paged),
                'total'     => $query->max_num_pages,
                'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2"></span>',
                'next_text' => '<span class="dashicons dashicons-arrow-right-alt2"></span>',
                'mid_size'  => 2
            ));
            ?>
        </div>
        
    <?php else : ?>
        <div class="no-results">
            <p><?php _e('No se encontraron personas desaparecidas.', 'humanitarios-cpt'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php
wp_reset_postdata();
get_footer();