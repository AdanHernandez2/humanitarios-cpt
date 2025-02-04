<?php
/**
 * Plantilla para tarjeta de Persona Desaparecida
 * 
 * @var WP_Post $post
 */

// Obtener metadatos específicos
$foto = get_post_thumbnail_id();
$hora_visto = get_post_meta(get_the_ID(), 'hora_desaparicion', true);
$ubicacion = get_post_meta(get_the_ID(), 'ubicacion', true);
?>

<div class="card card-personas">
    <?php if($foto) : ?>
        <?php echo wp_get_attachment_image($foto, 'medium', false, array(
            'class' => 'img-cards',
            'loading' => 'lazy',
            'alt' => esc_attr(get_the_title())
        )); ?>
    <?php else : ?>
        <img class="img-cards" src="https://humanitarios.do/wp-content/uploads/2025/02/desaparecidosimg.jpg" alt="Imagen predeterminada">
    <?php endif; ?>
    
    <div class="card-content">
        <div class="content-title">
            <h2 class="card-title"><?php the_title(); ?></h2>
            <p class="card-subtitle">Desaparecido</p>
        </div>
        
        <div class="card-details">
            <?php if($hora_visto) : ?>
                <p>Última hora visto: <strong><?php echo esc_html($hora_visto); ?></strong></p>
            <?php endif; ?>
            
            <?php if($ubicacion) : ?>
                <p>Última ubicación: <strong><?php echo esc_html($ubicacion); ?></strong></p>
            <?php endif; ?>
        </div>
        
        <div class="card-button">
            <a href="<?php the_permalink(); ?>">Ver Perfil</a>
        </div>
    </div>
</div>