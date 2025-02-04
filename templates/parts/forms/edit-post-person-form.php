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
if (!$post || $post->post_author != get_current_user_id() || $post->post_type !== 'personas_perdidas') {
    wp_redirect(home_url());
    exit;
}

// Obtener metadatos existentes
$meta = get_post_meta($post_id);
$foto_persona = get_the_post_thumbnail_url($post_id, 'large') ?: 'https://humanitarios.do/wp-content/uploads/2025/02/desaparecidosimg.jpg';
?>

<div class="edit-reporte-container">
    <h2>Editar Reporte de Persona Desaparecida</h2>
    
    <form id="edit-person-form" enctype="multipart/form-data">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <input type="hidden" name="action" value="edit_person_form">
        <?php wp_nonce_field('edit_person_' . $post_id, 'humanitarios_nonce'); ?>

        <!-- Información Básica -->
        <fieldset>
            <legend>Información Básica</legend>
            <input type="text" name="nombre_completo" value="<?php echo esc_attr($post->post_title); ?>" placeholder="Nombre completo o apodo">
            <input type="number" name="edad" value="<?php echo esc_attr($meta['edad'][0] ?? ''); ?>" required>
            <input type="text" name="nacionalidad" value="<?php echo esc_attr($meta['nacionalidad'][0] ?? ''); ?>" placeholder="Nacionalidad">
            <select name="genero">
                <option value="Hombre" <?php selected($meta['genero'][0] ?? '', 'Hombre'); ?>>Hombre</option>
                <option value="Mujer" <?php selected($meta['genero'][0] ?? '', 'Mujer'); ?>>Mujer</option>
                <option value="No especificado" <?php selected($meta['genero'][0] ?? '', 'No especificado'); ?>>No especificado</option>
            </select>

            <label>Foto Actual:</label>
            <div class="current-image">
                <img src="<?php echo esc_url($foto_persona); ?>" height="100">
                <label><input type="checkbox" name="remove_foto_persona"> Eliminar foto</label>
            </div>
            <input type="file" name="foto_persona">
        </fieldset>

        <!-- Características Físicas -->
        <fieldset>
            <legend>Características Físicas</legend>
            <input type="text" name="color_piel" value="<?php echo esc_attr($meta['color_piel'][0] ?? ''); ?>" placeholder="Color de piel">
            <input type="text" name="cabello" value="<?php echo esc_attr($meta['cabello'][0] ?? ''); ?>" placeholder="Color y tipo de cabello">
            <select name="altura">
                <option value="1.50-1.65" <?php selected($meta['altura'][0] ?? '', '1.50-1.65'); ?>>1,50 - 1,65 m</option>
                <option value="1.65-1.75" <?php selected($meta['altura'][0] ?? '', '1.65-1.75'); ?>>1,65 - 1,75 m</option>
                <option value="1.75+" <?php selected($meta['altura'][0] ?? '', '1.75+'); ?>>1,75 m o más</option>
            </select>
        </fieldset>

        <!-- Información de la Desaparición -->
        <fieldset>
            <legend>Información de la Desaparición</legend>
            <input type="date" name="fecha_desaparicion" value="<?php echo esc_attr($meta['fecha_desaparicion'][0] ?? ''); ?>" required>
            <input type="text" name="ubicacion" value="<?php echo esc_attr($meta['ubicacion'][0] ?? ''); ?>" required>
            <input type="time" name="hora_desaparicion" value="<?php echo esc_attr($meta['hora_desaparicion'][0] ?? ''); ?>" placeholder="Hora aproximada">
        </fieldset>

        <!-- Particularidades -->
        <fieldset>
            <legend>Particularidades</legend>
            <input type="text" name="vestimenta" value="<?php echo esc_attr($meta['vestimenta'][0] ?? ''); ?>" placeholder="Vestimenta">
            <input type="text" name="enfermedades" value="<?php echo esc_attr($meta['enfermedades'][0] ?? ''); ?>" placeholder="Enfermedad o condición médica">
        </fieldset>

        <!-- Contacto -->
        <fieldset>
            <legend>Información de Contacto</legend>
            <input type="tel" name="telefono" value="<?php echo esc_attr($meta['telefono'][0] ?? ''); ?>" placeholder="Teléfono familiar" required>
            <input type="email" name="correo" value="<?php echo esc_attr($meta['correo'][0] ?? ''); ?>" placeholder="Correo electrónico">
            <input type="text" name="ubicacion_contacto" value="<?php echo esc_attr($meta['ubicacion_contacto'][0] ?? ''); ?>" placeholder="Ubicación contacto" required>
            <input type="text" name="calle" value="<?php echo esc_attr($meta['calle'][0] ?? ''); ?>" placeholder="Calle / barrio específico">
        </fieldset>

        <button type="submit">Actualizar Reporte</button>

        <p class="frm-message"></p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('#edit-person-form').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('action', 'edit_person_form');
        $('.frm-message').show().removeClass(['error', 'success']).text('Enviando...');
        $('#submit').prop('disabled', true);
        
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
                    $('#report-person-form')[0].reset();
                }
            },
            error: function() {
                $('.frm-message').addClass('error').text('Error al enviar el formulario.');
            },
            complete: function() {
                $('#submit').prop('disabled', false);
            }
        });
    });
});
</script>

<?php
get_footer();
?>
