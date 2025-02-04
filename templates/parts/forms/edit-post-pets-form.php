<?php
get_header();

// Verificar si el usuario está logueado y si se pasó un ID de post válido
if (!is_user_logged_in() || !isset($_GET['edit_post'])) {
    wp_redirect(home_url());
    exit;
}

$post_id = intval($_GET['edit_post']);
$post = get_post($post_id);

// Verificar si el post existe y si el usuario actual es el autor
if (!$post || $post->post_author != get_current_user_id() || $post->post_type !== 'mascotas_perdidas') {
    wp_redirect(home_url());
    exit;
}

// Obtener metadatos existentes
$meta = get_post_meta($post_id);
$foto_mascota = get_the_post_thumbnail_url($post_id, 'large') ?: 'https://humanitarios.do/wp-content/uploads/2025/02/desaparecidosimg.jpg';
?>

<div class="edit-reporte-container">
    <h2>Editar Reporte de Mascota Desaparecida</h2>

    <form id="edit-pet-form" enctype="multipart/form-data">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <input type="hidden" name="action" value="edit_pet_form">
        <?php wp_nonce_field('edit_pet_' . $post_id, 'humanitarios_nonce'); ?>

        <!-- Información Básica -->
        <fieldset>
            <legend>Información Básica</legend>
            <input type="text" name="nombre_mascota" value="<?php echo esc_attr($meta['nombre_mascota'][0] ?? ''); ?>" placeholder="Nombre de la mascota (opcional)">
            <label>Foto Actual:</label>
            <div class="current-image">
                <img src="<?php echo esc_url(get_the_post_thumbnail_url($post_id, 'large') ?: 'https://humanitarios.do/wp-content/uploads/2025/02/desaparecidosimg.jpg'); ?>" height="100">
                <label><input type="checkbox" name="remove_foto_mascota"> Eliminar foto</label>
            </div>
            <input type="file" name="foto_mascota">
            <select name="tipo_animal">
                <option value="Perro" <?php selected($meta['tipo_animal'][0] ?? '', 'Perro'); ?>>Perro</option>
                <option value="Gato" <?php selected($meta['tipo_animal'][0] ?? '', 'Gato'); ?>>Gato</option>
                <option value="Otro" <?php selected($meta['tipo_animal'][0] ?? '', 'Otro'); ?>>Otro</option>
            </select>
            <input type="text" name="raza" value="<?php echo esc_attr($meta['raza'][0] ?? ''); ?>" placeholder="Raza (opcional)">
            <input type="text" name="color" value="<?php echo esc_attr($meta['color'][0] ?? ''); ?>" placeholder="Color (opcional)">
            <select name="tamanio">
                <option value="Pequeño" <?php selected($meta['tamanio'][0] ?? '', 'Pequeño'); ?>>Pequeño</option>
                <option value="Mediano" <?php selected($meta['tamanio'][0] ?? '', 'Mediano'); ?>>Mediano</option>
                <option value="Grande" <?php selected($meta['tamanio'][0] ?? '', 'Grande'); ?>>Grande</option>
            </select>
            <select name="edad">
                <option value="Infancia" <?php selected($meta['edad'][0] ?? '', 'Infancia'); ?>>Infancia</option>
                <option value="Juventud" <?php selected($meta['edad'][0] ?? '', 'Juventud'); ?>>Juventud</option>
                <option value="Adultez" <?php selected($meta['edad'][0] ?? '', 'Adultez'); ?>>Adultez</option>
            </select>
            <select name="sexo">
                <option value="Macho" <?php selected($meta['sexo'][0] ?? '', 'Macho'); ?>>Macho</option>
                <option value="Hembra" <?php selected($meta['sexo'][0] ?? '', 'Hembra'); ?>>Hembra</option>
            </select>
            <select name="identificacion">
                <option value="Sí" <?php selected($meta['identificacion'][0] ?? '', 'Sí'); ?>>Sí</option>
                <option value="No" <?php selected($meta['identificacion'][0] ?? '', 'No'); ?>>No</option>
                <option value="No se sabe" <?php selected($meta['identificacion'][0] ?? '', 'No se sabe'); ?>>No se sabe</option>
            </select>
        </fieldset>

        <!-- Información de la Desaparición -->
        <fieldset>
            <legend>Información de la Desaparición</legend>
            <input type="date" name="fecha_desaparicion" value="<?php echo esc_attr($meta['fecha_desaparicion'][0] ?? ''); ?>" required>
            <input type="text" name="ubicacion" value="<?php echo esc_attr($meta['ubicacion'][0] ?? ''); ?>" required>
            <input type="time" name="hora_desaparicion" value="<?php echo esc_attr($meta['hora_desaparicion'][0] ?? ''); ?>" placeholder="Hora aproximada">
            <input type="text" name="recompensa" value="<?php echo esc_attr($meta['recompensa'][0] ?? ''); ?>" placeholder="Recompensa ofrecida (si aplica)">
        </fieldset>

        <!-- Información de Contacto -->
        <fieldset>
            <legend>Información de Contacto</legend>
            <input type="tel" name="telefono" value="<?php echo esc_attr($meta['telefono'][0] ?? ''); ?>" placeholder="Teléfono familiar o conocido" required>
            <input type="email" name="correo" value="<?php echo esc_attr($meta['correo'][0] ?? ''); ?>" placeholder="Correo electrónico (opcional)">
        </fieldset>

        <button type="submit">Actualizar Reporte</button>

        <p class="frm-message"></p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('#edit-pet-form').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('action', 'edit_pet_form');
        $('.frm-message').show().removeClass(['error', 'success']).text('Enviando...');
        $('button[type="submit"]').prop('disabled', true);
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.frm-message').text('Enviando...');
            },
            success: function(response) {
                const noticeClass = response.status === 1 ? 'success' : 'error';
                $('.frm-message').removeClass(['error', 'success']).addClass(noticeClass).text(response.message);

                if (response.status === 1) {
                    $('#edit-pet-form')[0].reset();
                }
            },
            error: function() {
                $('.frm-message').addClass('error').text('Error al enviar el formulario.');
            },
            complete: function() {
                $('button[type="submit"]').prop('disabled', false);
            }
        });
    });
});
</script>
