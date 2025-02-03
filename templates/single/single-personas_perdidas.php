<?php
get_header();

while (have_posts()) : the_post();
    $meta = get_post_meta(get_the_ID());
?>
    <article class="humanitarios-single">
        <header>
            <h1><?php the_title(); ?></h1>
            <?php if (has_post_thumbnail()) : ?>
                <div class="featured-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="meta-content">
            <div class="personal-info">
                <h3>Información Personal</h3>
                <ul>
                    <?php if (!empty($meta['edad'][0])) : ?>
                        <li><strong>Edad:</strong> <?php echo esc_html($meta['edad'][0]); ?></li>
                    <?php endif; ?>
                    <!-- Repetir para otros campos -->
                </ul>
            </div>

            <div class="disappearance-info">
                <h3>Detalles de la Desaparición</h3>
                <ul>
                    <?php if (!empty($meta['fecha_desaparicion'][0])) : ?>
                        <li><strong>Fecha:</strong> <?php echo date_i18n('j F Y', strtotime($meta['fecha_desaparicion'][0])); ?></li>
                    <?php endif; ?>
                    <!-- Repetir para otros campos -->
                </ul>
            </div>
        </div>

        <?php if (is_user_logged_in() && get_current_user_id() == get_the_author_meta('ID')) : ?>
            <div class="owner-actions">
                <a href="<?php echo add_query_arg('edit_post', get_the_ID(), home_url('/editar-reporte')); ?>" class="button">
                    Editar Reporte
                </a>
            </div>
        <?php endif; ?>
    </article>
<?php
endwhile;
get_footer();