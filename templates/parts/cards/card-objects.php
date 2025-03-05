<?php
/**
 * Plantilla para tarjeta de Objetos Perdidos
 * 
 * @var WP_Post $post
 */

// Obtener metadatos específicos
$foto = get_post_thumbnail_id();
$lugar_perdida = get_post_meta(get_the_ID(), 'lugar_perdida', true);
$fecha_perdida = get_post_meta(get_the_ID(), 'fecha_perdida', true);
$descripcion_objeto = get_post_meta(get_the_ID(), 'descripcion_objeto', true);
?>

<div class="card card-lost-object">
    <?php if ($foto) : ?>
        <?php echo wp_get_attachment_image($foto, 'medium', false, array(
            'class' => 'img-cards',
            'loading' => 'lazy',
            'alt' => esc_attr(get_the_title())
        )); ?>
    <?php else : ?>
        <img class="img-cards" src="https://humanitarios.do/wp-content/uploads/2025/02/objeto-perdido-default.jpg" alt="Imagen predeterminada">
    <?php endif; ?>
    
    <div class="card-content">
        <div class="content-title">
            <h2 class="card-title"><?php the_title(); ?></h2>
            <p class="card-subtitle">Objeto Perdido</p>
        </div>
        
        <div class="card-details">
            <?php if ($lugar_perdida) : ?>
                <p>Lugar de pérdida: <strong><?php echo esc_html($lugar_perdida); ?></strong></p>
            <?php endif; ?>
            
            <?php if ($fecha_perdida) : ?>
                <p>Fecha de pérdida: <strong><?php echo esc_html($fecha_perdida); ?></strong></p>
            <?php endif; ?>
            
        </div>
        
        <div class="card-button">
            <a href="<?php the_permalink(); ?>">Ver Detalles</a>
        </div>
    </div>
</div>