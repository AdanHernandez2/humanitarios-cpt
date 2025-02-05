<?php
get_header();
while (have_posts()) : the_post();
    $meta = get_post_meta(get_the_ID());
    $foto_mascota = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: 'https://humanitarios.do/wp-content/uploads/2025/02/mascotaperdida.jpg';
?>
    <div class="reporte-container">
        <header>
            <h1><?php the_title(); ?></h1>
        </header>

        <section class="reporte-seccion">
            <div class="inf-basica">
                <div class="info-desaparicion">
                    <h2>Información Básica</h2>
                    <ul>
                        <li><strong>Tipo:</strong> <?php echo esc_html($meta['tipo_animal'][0] ?? ''); ?></li>
                        <li><strong>Raza:</strong> <?php echo esc_html($meta['raza'][0] ?? 'No especificado'); ?></li>
                        <li><strong>Color:</strong> <?php echo esc_html($meta['color'][0] ?? 'No especificado'); ?></li>
                        <li><strong>Tamaño:</strong> <?php echo esc_html($meta['tamanio'][0] ?? 'No especificado'); ?></li>
                        <li><strong>Edad:</strong> <?php echo esc_html($meta['edad'][0] ?? 'No especificado'); ?></li>
                        <li><strong>Sexo:</strong> <?php echo esc_html($meta['sexo'][0] ?? 'No especificado'); ?></li>
                        <li><strong>Identificación:</strong> <?php echo esc_html($meta['identificacion'][0] ?? 'No se sabe'); ?></li>
                    </ul>

                    <section class="reporte-seccion">
                        <h2>Desaparición</h2>
                        <ul>
                            <li><strong>Fecha:</strong> <?php echo date_i18n('j F Y', strtotime($meta['fecha_desaparicion'][0] ?? '')); ?></li>
                            <li><strong>Última ubicación:</strong> <?php echo esc_html($meta['ubicacion'][0] ?? ''); ?></li>
                            <li><strong>Hora aproximada:</strong> <?php echo esc_html($meta['hora_desaparicion'][0] ?? ''); ?></li>
                            <li><strong>Recompensa:</strong> <?php echo esc_html($meta['recompensa'][0] ?? 'No aplica'); ?></li>
                        </ul>
                    </section>
                </div>
                <div class="foto-persona">
                    <img src="<?php echo esc_url($foto_mascota); ?>" alt="Foto de la mascota">
                </div>
            </div>
        </section>

        <section class="reporte-seccion">
            <h2>Contacto</h2>
            <ul>
                <li><strong>Teléfono:</strong> <?php echo esc_html($meta['telefono'][0] ?? 'No disponible'); ?></li>
                <li><strong>Correo:</strong> <?php echo esc_html($meta['correo'][0] ?? 'No disponible'); ?></li>
            </ul>
        </section>

        <?php if (is_user_logged_in() && get_current_user_id() == get_the_author_meta('ID')) : ?>
            <div class="owner-actions">
                <a href="<?php echo add_query_arg('edit_post', get_the_ID(), home_url('/editar-reporte-mascotas')); ?>" class="button">
                    Editar Reporte
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php
endwhile;
get_footer();
