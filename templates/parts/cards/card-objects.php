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
        <?php echo wp_get_attachment_image(
            $foto, 
            'medium', 
            false, 
            [
                'class' => 'img-cards',
                'loading' => 'lazy',
                'alt' => esc_attr(get_the_title())
            ]
        ); ?>
    <?php else : ?>
        <img class="img-cards" 
            src="<?php echo esc_url('https://humanitarios.do/wp-content/uploads/2025/02/objeto-perdido-default.jpg'); ?>" 
            alt="<?php esc_attr_e('Imagen predeterminada', 'humanitarios-cpt'); ?>">
    <?php endif; ?>
    
    <div class="card-content">
        <div class="content-title">
            <h2 class="card-title"><?php echo wp_kses_post(get_the_title()); ?></h2>
            <p class="card-subtitle"><?php esc_html_e('Objeto Perdido', 'humanitarios-cpt'); ?></p>
        </div>
        
        <div class="card-details">
            <?php if ($lugar_perdida) : ?>
                <p><?php esc_html_e('Lugar de pérdida:', 'humanitarios-cpt'); ?> 
                <strong><?php echo wp_kses_post($lugar_perdida); ?></strong></p>
            <?php endif; ?>
            
            <?php if ($fecha_perdida) : ?>
                <p><?php esc_html_e('Fecha de pérdida:', 'humanitarios-cpt'); ?> 
                <strong><?php echo date_i18n(get_option('date_format'), strtotime($fecha_perdida)); ?></strong></p>
            <?php endif; ?>
        </div>
        
        <div class="card-button">
            <a href="<?php the_permalink(); ?>"><?php esc_html_e('Ver Detalles', 'humanitarios-cpt'); ?></a>
        </div>
    </div>
</div>