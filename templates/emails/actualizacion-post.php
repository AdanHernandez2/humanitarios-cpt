<?php
// actualizacion-post.php
?>
<?php include 'email-header.php'; ?>
<table>
    <tr>
        <td><strong>Actualización de Reporte</strong></td>
    </tr>
    <tr>
        <td>Se han realizado cambios en un reporte.</td>
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
        <td><strong>Cambios Realizados:</strong></td>
    </tr>
    <?php foreach ($updated_fields as $key => $values) : ?>
        <tr>
            <td><strong><?php echo esc_html($key); ?>:</strong> Antes: <?php echo esc_html($values['old']); ?> → Ahora: <?php echo esc_html($values['new']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php include 'email-footer.php'; ?>