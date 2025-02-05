<?php
// nuevo-registro.php
?>
<?php include 'email-header.php'; ?>
<table>
    <tr>
        <td><strong>Nuevo Reporte en Espera de Aprobación</strong></td>
    </tr>
    <tr>
        <td>Se ha registrado un nuevo reporte en la plataforma.</td>
    </tr>
    <tr>
        <td><strong>Título:</strong> <?php echo esc_html($post_title); ?></td>
    </tr>
    <tr>
        <td><strong>ID del Reporte:</strong> <?php echo esc_html($post_id); ?></td>
    </tr>
    <tr>
        <td><strong>Autor:</strong> <?php echo esc_html($author_name); ?></td>
    </tr>
    <tr>
        <td><strong>Datos Registrados:</strong></td>
    </tr>
    <?php foreach ($meta_data as $key => $value) : ?>
        <tr>
            <td><strong><?php echo esc_html($key); ?>:</strong> <?php echo esc_html($value); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php include 'email-footer.php'; ?>