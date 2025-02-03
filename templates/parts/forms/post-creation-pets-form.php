<?php
// Seguridad: Bloquear acceso directo
defined('ABSPATH') || exit();

// Redirigir si el usuario no está logueado
if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}
?>

<form id="report-pet-form" enctype="multipart/form-data">
    <h2>Reportar Mascota Desaparecida</h2>

    <!-- Información Básica -->
    <fieldset>
        <legend>Información Básica</legend>
        <input type="text" name="nombre_mascota" placeholder="Nombre de la mascota (opcional)">
        <input type="file" name="foto_mascota">
        <select name="tipo_animal">
            <option value="Perro">Perro</option>
            <option value="Gato">Gato</option>
            <option value="Otro">Otro</option>
        </select>
        <input type="text" name="raza" placeholder="Raza (opcional)">
        <input type="text" name="color" placeholder="Color (opcional)">
        <select name="tamanio">
            <option value="Pequeño">Pequeño</option>
            <option value="Mediano">Mediano</option>
            <option value="Grande">Grande</option>
        </select>
        <select name="edad">
            <option value="Infancia">Infancia</option>
            <option value="Juventud">Juventud</option>
            <option value="Adultez">Adultez</option>
        </select>
        <select name="sexo">
            <option value="Macho">Macho</option>
            <option value="Hembra">Hembra</option>
        </select>
        <select name="identificacion">
            <option value="Sí">Sí</option>
            <option value="No">No</option>
            <option value="No se sabe">No se sabe</option>
        </select>
    </fieldset>

    <!-- Información de la Desaparición -->
    <fieldset>
        <legend>Información de la Desaparición</legend>
        <input type="date" name="fecha_desaparicion" required>
        <input type="text" name="ubicacion" placeholder="Última ubicación conocida (ciudad, barrio, referencia)" required>
        <input type="time" name="hora_desaparicion" placeholder="Hora aproximada (opcional)">
        <input type="text" name="recompensa" placeholder="Recompensa ofrecida (si aplica)">
    </fieldset>

    <!-- Información de Contacto -->
    <fieldset>
        <legend>Información de Familiar o Conocido</legend>
        <input type="tel" name="telefono" placeholder="Teléfono familiar o conocido (0-000-000-0000)" required>
        <input type="email" name="correo" placeholder="Correo electrónico (opcional)">
    </fieldset>

    <!-- Aceptación de términos -->
    <label>
        <input type="checkbox" name="acepta_terminos" required>
        Acepto compartir esta información para ayudar en la búsqueda.
    </label>

    <input type="hidden" name="action" value="submit_pet_form">
    <input type="hidden" name="humanitarios_nonce" value="<?php echo wp_create_nonce('submit_pet_form'); ?>">
    
    <button type="submit">Enviar Reporte</button>

    <p class="frm-message"></p>
</form>

<script>
jQuery(document).ready(function($) {
    $('#report-pet-form').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);
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
                    $('#report-pet-form')[0].reset();
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