<?php include 'email-header.php'; ?>

<p>Hola,</p>

<p>Tu reporte "<strong><?php echo esc_html($post_title); ?></strong>" ha sido recibido y está pendiente de revisión.</p>

<p>Detalles del reporte:</p>
<ul>
    <li><strong>ID del Reporte:</strong> <?php echo esc_html($post_id); ?></li>
    <li><strong>Autor:</strong> <?php echo esc_html($post_author); ?></li>
</ul>

<p>Recibirás una notificación cuando el reporte sea aprobado.</p>

<p>Gracias por contribuir a <a href="https://humanitarios.do/">Humanitarios</a>.</p>

<?php include 'email-footer.php'; ?>
