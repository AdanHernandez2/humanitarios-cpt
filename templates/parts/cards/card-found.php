<?php
/**
 * Plantilla para tarjeta de Encontrados (Mascota, Objeto o Persona)
 * 
 * @var WP_Post $post
 */

// Obtener metadatos específicos
$foto = get_post_thumbnail_id();
$tipo_encontrado = get_post_meta(get_the_ID(), 'tipo_encontrado', true);
$lugar_encontrado = get_post_meta(get_the_ID(), 'provincia_encontrado', true);
$fecha_encontrado = get_post_meta(get_the_ID(), 'fecha_encontrado', true);

// Definir el subtítulo dinámico
$subtitulo = 'Encontrado';
if ($tipo_encontrado === 'mascota') {
    $subtitulo = 'Mascota Encontrada';
} elseif ($tipo_encontrado === 'objeto') {
    $subtitulo = 'Objeto Encontrado';
} elseif ($tipo_encontrado === 'persona') {
    $subtitulo = 'Persona Encontrada';
}
?>

<div class="card card-found">
    <?php if ($foto) : ?>
        <?php echo wp_get_attachment_image($foto, 'medium', false, array(
            'class' => 'img-cards',
            'loading' => 'lazy',
            'alt' => esc_attr(get_the_title())
        )); ?>
    <?php else : ?>
        <img class="img-cards" src="https://humanitarios.do/wp-content/uploads/2025/02/encontrado-default.jpg" alt="Imagen predeterminada">
    <?php endif; ?>
    
    <div class="card-content">
        <div class="content-title">
            <h2 class="card-title"><?php the_title(); ?></h2>
            <p class="card-subtitle"><?php echo esc_html($subtitulo); ?></p>
        </div>
        
        <div class="card-details">
            <?php if ($lugar_encontrado) : ?>
                <p>Lugar encontrado: <strong><?php echo esc_html($lugar_encontrado); ?></strong></p>
            <?php endif; ?>
            
            <?php if ($fecha_encontrado) : ?>
                <p>Fecha encontrado: <strong><?php echo esc_html($fecha_encontrado); ?></strong></p>
            <?php endif; ?>
        </div>
        
        <div class="card-button">
            <a href="<?php the_permalink(); ?>">Ver Detalles</a>
        </div>
    </div>
</div>