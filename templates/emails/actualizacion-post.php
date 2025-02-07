<?php
// actualizacion-post.php
?>
<?php include 'email-header.php'; ?>
<table width="100%" cellpadding="10" cellspacing="0" border="0" style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
    <tr>
        <td style="font-weight: bold; font-size: 16px;">Actualización de Reporte</td>
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
        <td><strong>Autor:</strong> <?php echo esc_html($post_author); ?></td>
    </tr>
    <tr>
        <td style="padding-top: 10px;"><strong>Cambios Realizados:</strong></td>
    </tr>
    <?php if ( ! empty( $updated_fields ) ) : ?>
        <?php foreach ($updated_fields as $key => $values) : ?>
            <tr>
                <td><strong><?php echo esc_html($key); ?>:</strong> Antes: <?php echo esc_html($values['old']); ?> → Ahora: <?php echo esc_html($values['new']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td>No se detectaron cambios.</td>
        </tr>
    <?php endif; ?>
</table>
<?php include 'email-footer.php'; ?>