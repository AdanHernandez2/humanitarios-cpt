<?php
/**
 * Template Name: Single Persona Desaparecida
 * Template Post Type: personas_perdidas
 */

get_header(); ?>

<div class="container container_single_humanitarios">
  <div class="main-body">
    <div class="row gutters-sm">
      <div class="col-md-4 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-column align-items-center text-center">
              <?php if (has_post_thumbnail()) : ?>
                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="Foto principal" class="main-image">
              <?php endif; ?>
              <h4><?php the_title(); ?></h4>
              <p class="text-secondary mb-1">
                Desaparecido el: <?php echo esc_html(get_post_meta(get_the_ID(), 'fecha_desaparicion', true)); ?>
              </p>
              <p class="text-muted font-size-sm">
                Hora desaparición: <?php echo esc_html(get_post_meta(get_the_ID(), 'hora_desaparicion', true)); ?>
              </p>
              <p class="text-muted font-size-sm">
                Última ubicación: <?php echo esc_html(get_post_meta(get_the_ID(), 'ubicacion', true)); ?>
              </p>
              <p class="text-muted font-size-sm">
                Provincia: <?php echo esc_html(get_post_meta(get_the_ID(), 'provincia', true)); ?>
              </p>
            </div>
          </div>
        </div>
        <div class="card mt-3">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <strong>Edad:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'edad', true)); ?>
            </li>
            <li class="list-group-item">
              <strong>Género:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'genero', true)); ?>
            </li>
            <li class="list-group-item">
              <strong>Nacionalidad:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'nacionalidad', true)); ?>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card mb-3">
          <div class="card-body">
            <div class="mt-3">
              <h5>Galería</h5>
              <div class="d-flex flex-wrap">
                <?php
                $gallery = get_post_meta(get_the_ID(), 'humanitarios_galeria', true);
                if ($gallery) :
                  foreach ($gallery as $image_id) :
                    $image_url = wp_get_attachment_image_url($image_id, 'large'); // Usamos 'large' para el lightbox
                    $thumbnail_url = wp_get_attachment_image_url($image_id, 'thumbnail'); // Usamos 'thumbnail' para la vista previa
                    ?>
                    <a href="<?php echo esc_url($image_url); ?>" data-lightbox="gallery" data-title="Imagen de <?php the_title(); ?>">
                      <img src="<?php echo esc_url($thumbnail_url); ?>" alt="Imagen de galería" class="gallery-image">
                    </a>
                  <?php endforeach;
                endif;
                ?>
              </div>
            </div>
            <hr />
            <div>
              <h5>Características Físicas</h5>
              <ul>
                <li><strong>Color de piel:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'color_piel', true)); ?></li>
                <li><strong>Cabello:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'cabello', true)); ?></li>
                <li><strong>Altura:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'altura', true)); ?></li>
                <li><strong>Vestimenta:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'vestimenta', true)); ?></li>
                <li><strong>Enfermedades:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'enfermedades', true)); ?></li>
              </ul>
            </div>
            <!-- Aquí movemos el bloque de contactos -->
            <div class="card mt-3">
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <strong><?php echo esc_html(get_post_meta(get_the_ID(), 'nombre_familiar1', true)); ?>:</strong>
                  <a href="tel:<?php echo esc_html(get_post_meta(get_the_ID(), 'telefono_1', true)); ?>" class="btn btn-sm btn-success">
                      <i class="fas fa-phone-alt"></i> Llamar
                  </a>
                  <a href="https://wa.me/<?php echo esc_html(get_post_meta(get_the_ID(), 'telefono_1', true)); ?>" class="btn btn-sm btn-success">
                      <i class="fab fa-whatsapp"></i> WhatsApp
                  </a><br />
                  Ubicación: <?php echo esc_html(get_post_meta(get_the_ID(), 'calle', true)); ?><br />
                  Correo electrónico: <?php echo esc_html(get_post_meta(get_the_ID(), 'correo', true)); ?>
                </li>
                <li class="list-group-item">
                  <strong><?php echo esc_html(get_post_meta(get_the_ID(), 'nombre_familiar2', true)); ?>:</strong>
                  <a href="tel:<?php echo esc_html(get_post_meta(get_the_ID(), 'telefono_2', true)); ?>" class="btn btn-sm btn-success">
                      <i class="fas fa-phone-alt"></i> Llamar
                  </a>
                  <a href="https://wa.me/<?php echo esc_html(get_post_meta(get_the_ID(), 'telefono_2', true)); ?>" class="btn btn-sm btn-success">
                      <i class="fab fa-whatsapp"></i> WhatsApp
                  </a><br />
                  Ubicación: <?php echo esc_html(get_post_meta(get_the_ID(), 'calle', true)); ?><br />
                  Correo electrónico: <?php echo esc_html(get_post_meta(get_the_ID(), 'correo', true)); ?>
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
      $edit_url = add_query_arg('edit_post', get_the_ID(), get_permalink(get_page_by_path('editar-reporte-personas'))); // Asegúrate de que 'edit-post-person-form' sea el slug correcto de tu página de edición
      echo '<a href="' . esc_url($edit_url) . '" class="btn btn-primary mb-3">Editar Reporte</a>';
  }
  ?>
</div>


<?php get_footer(); ?>