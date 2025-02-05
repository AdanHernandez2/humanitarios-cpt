<?php include 'email-header.php'; ?>

<p>Hola,</p>

<p>Tu reporte "<strong><?php echo esc_html($post_title); ?></strong>" ha sido aprobado y ahora está publicado en nuestro sitio web.</p>

<p>Puedes verlo en el siguiente enlace:</p>
<p><a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_url(get_permalink($post_id)); ?></a></p>

<p>Gracias por tu contribución a <a href="https://humanitarios.do/">Humanitarios</a>.</p>

<?php include 'email-footer.php'; ?>
