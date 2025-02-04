<?php
get_header();
while (have_posts()) : the_post();
    $meta = get_post_meta(get_the_ID());
    $foto_persona = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: 'https://humanitarios.do/wp-content/uploads/2025/02/desaparecidosimg.jpg';
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
                        <li><strong>Nombre/Apodo:</strong> <?php the_title(); ?></li>
                        <li><strong>Edad:</strong> <?php echo esc_html($meta['edad'][0] ?? 'No especificado'); ?></li>
                        <li><strong>Nacionalidad:</strong> <?php echo esc_html($meta['nacionalidad'][0] ?? 'No especificado'); ?></li>
                        <li><strong>Género:</strong> <?php echo esc_html($meta['genero'][0] ?? 'No especificado'); ?></li>
                    </ul>

                    <section class="reporte-seccion">
                        <h2>Desaparición</h2>
                        <ul>
                            <li><strong>Fecha:</strong> <?php echo date_i18n('j F Y', strtotime($meta['fecha_desaparicion'][0] ?? '')); ?></li>
                            <li><strong>Última ubicación:</strong> <?php echo esc_html($meta['ubicacion'][0] ?? ''); ?></li>
                            <li><strong>Hora aproximada:</strong> <?php echo esc_html($meta['hora_desaparicion'][0] ?? ''); ?></li>
                        </ul>
                    </section>
                </div>
                <div class="foto-persona">
                    <img src="<?php echo esc_url($foto_persona); ?>" alt="Foto de la Persona">
                </div>
            </div>
        </section>

        <section class="reporte-seccion">
            <h2>Características Físicas</h2>
            <ul>
                <li><strong>Color de piel:</strong> <?php echo esc_html($meta['color_piel'][0] ?? 'No especificado'); ?></li>
                <li><strong>Cabello:</strong> <?php echo esc_html($meta['cabello'][0] ?? 'No especificado'); ?></li>
                <li><strong>Altura:</strong> <?php echo esc_html($meta['altura'][0] ?? 'No especificado'); ?></li>
            </ul>
        </section>

        <div class="reporte-contacto">
            <section class="reporte-seccion">
                <h2>Particularidades</h2>
                <ul>
                    <li><strong>Vestimenta:</strong> <?php echo esc_html($meta['vestimenta'][0] ?? 'No especificado'); ?></li>
                    <li><strong>Condición médica:</strong> <?php echo esc_html($meta['enfermedades'][0] ?? 'No especificado'); ?></li>
                </ul>
            </section>

            <section class="reporte-seccion">
                <h2>Contacto</h2>
                <ul>
                    <li><strong>Teléfono:</strong> <?php echo esc_html($meta['telefono'][0] ?? 'No disponible'); ?></li>
                    <li><strong>Correo:</strong> <?php echo esc_html($meta['correo'][0] ?? 'No disponible'); ?></li>
                    <li><strong>Ubicación contacto:</strong> <?php echo esc_html($meta['ubicacion_contacto'][0] ?? 'No especificado'); ?></li>
                    <li><strong>Calle/Barrio:</strong> <?php echo esc_html($meta['calle'][0] ?? 'No especificado'); ?></li>
                </ul>
            </section>
        </div>

        <?php if (is_user_logged_in() && get_current_user_id() == get_the_author_meta('ID')) : ?>
            <div class="owner-actions">
                <a href="<?php echo add_query_arg('edit_post', get_the_ID(), home_url('/editar-reporte-personas')); ?>" class="button">
                    Editar Reporte
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php
endwhile;
get_footer();
