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
        <label for="nombre_mascota">Nombre de la mascota</label>
        <input type="text" name="nombre_mascota" placeholder="Nombre de la mascota">
        <label for="foto_mascota">Subir una foto de la mascota</label>
        <input type="file" name="foto_mascota">
        <label for="tipo_animal">Tipo de animal</label>
        <select name="tipo_animal">
            <option value="Perro">Perro</option>
            <option value="Gato">Gato</option>
            <option value="Otro">Otro</option>
        </select>
        <label for="raza">Raza</label>
        <input type="text" name="raza" placeholder="Raza (opcional)">
        <label for="color">Color</label>
        <input type="text" name="color" placeholder="Color (opcional)">
        <label for="tamanio">Tamaño aproximado</label>
        <select name="tamanio">
            <option value="Pequeño">Pequeño</option>
            <option value="Mediano">Mediano</option>
            <option value="Grande">Grande</option>
        </select>
        <label for="edad">Edad aproximada</label>
        <select name="edad">
            <option value="Infancia">Infancia</option>
            <option value="Juventud">Juventud</option>
            <option value="Adultez">Adultez</option>
        </select>
        <label for="sexo">Sexo de la mascota</label>
        <select name="sexo">
            <option value="Macho">Macho</option>
            <option value="Hembra">Hembra</option>
        </select>
        <label for="identificacion">¿Tiene collar o identificación?</label>
        <select name="identificacion">
            <option value="Sí">Sí</option>
            <option value="No">No</option>
            <option value="No se sabe">No se sabe</option>
        </select>
    </fieldset>

    <!-- Información de la Desaparición -->
    <fieldset>
        <legend>Información de la Desaparición</legend>
        <label for="fecha_desaparicion">Fecha desaparición</label>
        <input type="date" name="fecha_desaparicion" required>
        <label for="ubicacion">Última ubicación conocida</label>
        <input type="text" name="ubicacion" placeholder="Última ubicación conocida (ciudad, barrio, referencia)" required>
        <label for="hora_desaparicion">Hora aproximada de desaparición</label>
        <input type="time" name="hora_desaparicion" placeholder="Hora aproximada (opcional)">
        <label for="recompensa">Recompensa ofrecida</label>
        <input type="text" name="recompensa" placeholder="Recompensa ofrecida (si aplica)">
    </fieldset>

    <!-- Información de Contacto -->
    <fieldset>
        <legend>Información de Familiar o Conocido</legend>
        <label for="telefono">Teléfono</label>
        <input type="tel" name="telefono" placeholder="Teléfono familiar o conocido (0-000-000-0000)" required>
        <label for="correo">Correo electrónico</label>
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
                document.querySelector('.frm-message').scrollIntoView({ behavior: 'smooth', block: 'end' });
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