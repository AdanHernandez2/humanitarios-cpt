<?php
// Redirigir si no está logueado o no es el autor del post
global $post;

if (!is_user_logged_in() || !isset($_GET['edit_post'])) {
    wp_redirect(home_url());
    exit;
}

$post_id = intval($_GET['edit_post']);
$post = get_post($post_id);

if (!$post || $post->post_author != get_current_user_id()) {
    wp_redirect(home_url());
    exit;
}

// Determinar tipo de post
$is_persona = ($post->post_type === 'personas_perdidas');
$is_mascota = ($post->post_type === 'mascotas_perdidas');

// Obtener metadatos existentes
$meta = get_post_meta($post_id);
?>

<form id="edit-post-form" enctype="multipart/form-data">
    <h2>Editar <?php echo $is_persona ? 'Persona' : 'Mascota'; ?> Desaparecida</h2>

    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <input type="hidden" name="action" value="edit_post_form">
    <input type="hidden" name="post_type" value="<?php echo $post->post_type; ?>">
    <?php wp_nonce_field('edit_post_' . $post_id, 'humanitarios_nonce'); ?>

    <?php if ($is_persona): ?>
        <!-- Campos para Personas -->
        <fieldset>
            <legend>Información Básica</legend>
            <input type="text" name="nombre_completo" value="<?php echo esc_attr($meta['nombre_completo'][0] ?? ''); ?>" placeholder="Nombre completo o apodo">
            <input type="number" name="edad" value="<?php echo esc_attr($meta['edad'][0] ?? ''); ?>" required>
            
            <?php if (!empty($meta['foto_persona'][0])): ?>
                <div class="current-image">
                    <img src="<?php echo wp_get_attachment_url($meta['foto_persona'][0]); ?>" height="100">
                    <label><input type="checkbox" name="remove_foto_persona"> Eliminar foto</label>
                </div>
            <?php endif; ?>
            <input type="file" name="foto_persona">
            
            <!-- Resto de campos para personas... -->
        </fieldset>

    <?php elseif ($is_mascota): ?>
        <!-- Campos para Mascotas -->
        <fieldset>
            <legend>Información Básica</legend>
            <input type="text" name="nombre_mascota" value="<?php echo esc_attr($meta['nombre_mascota'][0] ?? ''); ?>" placeholder="Nombre de la mascota">
            
            <?php if (!empty($meta['foto_mascota'][0])): ?>
                <div class="current-image">
                    <img src="<?php echo wp_get_attachment_url($meta['foto_mascota'][0]); ?>" height="100">
                    <label><input type="checkbox" name="remove_foto_mascota"> Eliminar foto</label>
                </div>
            <?php endif; ?>
            <input type="file" name="foto_mascota">
            
            <!-- Resto de campos para mascotas... -->
        </fieldset>
    <?php endif; ?>

    <!-- Campos comunes -->
    <fieldset>
        <legend>Información de la Desaparición</legend>
        <input type="date" name="fecha_desaparicion" value="<?php echo esc_attr($meta['fecha_desaparicion'][0] ?? ''); ?>" required>
        <input type="text" name="ubicacion" value="<?php echo esc_attr($meta['ubicacion'][0] ?? ''); ?>" required>
    </fieldset>

    <button type="submit">Actualizar Reporte</button>
</form>

<script>
jQuery(document).ready(function($) {
    $('#edit-post-form').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('action', 'edit_post_form');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    window.location.href = response.data.redirect;
                } else {
                    alert(response.data.message);
                }
            }
        });
    });
});
</script>