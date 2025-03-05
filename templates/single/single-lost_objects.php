<?php
/**
 * Template Name: Single Objeto Perdido
 * Template Post Type: lost_objects
 */

get_header(); ?>

<div class="container container_single_humanitarios">
  <div class="main-body">
    <div class="row gutters-sm">
      <!-- Columna izquierda -->
      <div class="col-md-4 mb-3">
        <!-- Tarjeta con información básica del objeto -->
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-column align-items-center text-center">
              <!-- Imagen destacada -->
              <?php if (has_post_thumbnail()) : ?>
                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="Foto principal" class="main-image">
              <?php endif; ?>
              <h4><?php the_title(); ?></h4>
              <p class="text-secondary mb-1">
                Perdido el: <?php echo esc_html(get_post_meta(get_the_ID(), 'fecha_perdida', true)); ?>
              </p>
              <p class="text-muted font-size-sm">
                Lugar de pérdida: <?php echo esc_html(get_post_meta(get_the_ID(), 'lugar_perdida', true)); ?>
              </p>
              <p class="text-muted font-size-sm">
                Provincia: <?php echo esc_html(get_post_meta(get_the_ID(), 'provincia', true)); ?>
              </p>
            </div>
          </div>
        </div>

        <!-- Tarjeta con detalles del objeto -->
        <div class="card mt-3">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <strong>Tipo de objeto:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'tipo_objeto', true)); ?>
            </li>
            <li class="list-group-item">
              <strong>Marca:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'marca_objeto', true)); ?>
            </li>
            <li class="list-group-item">
              <strong>Modelo:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'modelo_objeto', true)); ?>
            </li>
            <li class="list-group-item">
              <strong>Color:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'color_objeto', true)); ?>
            </li>
            <li class="list-group-item">
              <strong>Estado:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'estado_objeto', true)); ?>
            </li>
          </ul>
        </div>
        
      </div>

      <!-- Columna derecha -->
      <div class="col-md-8">
        <div class="card mb-3">
          <div class="card-body">

            <!-- Galería de imágenes -->
            <div class="mt-3">
              <h5>Galería</h5>
              <div class="d-flex flex-wrap">
                <?php
                $gallery = get_post_meta(get_the_ID(), 'humanitarios_galeria', true);
                if ($gallery) :
                  foreach ($gallery as $image_id) :
                    $image_url = wp_get_attachment_image_url($image_id, 'large');
                    $thumbnail_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                    ?>
                    <a href="<?php echo esc_url($image_url); ?>" data-lightbox="gallery" data-title="Imagen de <?php the_title(); ?>">
                      <img src="<?php echo esc_url($thumbnail_url); ?>" alt="Imagen de galería" class="gallery-image">
                    </a>
                  <?php endforeach;
                endif;
                ?>
              </div>
            </div>
            <!-- Descripción del objeto -->
            <div>
              <h5>Descripción detallada</h5>
              <p><?php echo esc_html(get_post_meta(get_the_ID(), 'descripcion_objeto', true)); ?></p>
            </div>
            <!-- Tarjeta con información de contacto -->
            <div class="card mt-3">
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <strong>Contacto:</strong>
                  <a href="tel:<?php echo esc_html(get_post_meta(get_the_ID(), 'telefono_contacto', true)); ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-phone-alt"></i> Llamar
                  </a>
                  <a href="https://wa.me/<?php echo esc_html(get_post_meta(get_the_ID(), 'telefono_contacto', true)); ?>" class="btn btn-sm btn-success">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                  </a><br />
                  <strong>Correo electrónico:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'correo_contacto', true)); ?>
                </li>
                <li class="list-group-item">
                  <strong>Nombre de contacto:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'nombre_contacto', true)); ?>
                </li>
                <li class="list-group-item">
                  <strong>Provincia de contacto:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'provincia_contacto', true)); ?>
                </li>
                <li class="list-group-item">
                  <strong>Dirección de contacto:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'direccion_contacto', true)); ?>
                </li>
              </ul>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

 <!-- Botón de Edición -->
 <?php
  if (current_user_can('edit_post', get_the_ID())) {
      $edit_url = add_query_arg('edit_post', get_the_ID(), get_permalink(get_page_by_path('editar-reporte-objetos'))); // Asegúrate de que 'edit-post-found-form' sea el slug correcto
      echo '<a href="' . esc_url($edit_url) . '" class="btn btn-primary mb-3">Editar Reporte</a>';
  }
  ?>
</div>

<?php get_footer(); ?>