<?php
// Seguridad: Bloquear acceso directo
defined('ABSPATH') || exit();

// Redirigir si el usuario no está logueado
if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}
?>

<form id="report-person-form" enctype="multipart/form-data">
    <h2>Reportar Persona Desaparecida</h2>

    <!-- Información Básica -->
    <fieldset>
        <legend>Información Básica</legend>
        <input type="text" name="nombre_completo" placeholder="Nombre completo o apodo (opcional)">
        <input type="file" name="foto_persona">
        <input type="number" name="edad" placeholder="Edad aproximada" required>
        <input type="text" name="nacionalidad" placeholder="Nacionalidad (opcional)">
        <select name="genero">
            <option value="Hombre">Hombre</option>
            <option value="Mujer">Mujer</option>
            <option value="No especificado">No especificado</option>
        </select>
    </fieldset>

    <!-- Características Físicas -->
    <fieldset>
        <legend>Características Físicas</legend>
        <input type="text" name="color_piel" placeholder="Color de piel (opcional)">
        <input type="text" name="cabello" placeholder="Color y tipo de cabello (opcional)">
        <select name="altura">
            <option value="1.50-1.65">1,50 - 1,65 m</option>
            <option value="1.65-1.75">1,65 - 1,75 m</option>
            <option value="1.75+">1,75 m o más</option>
        </select>
    </fieldset>

    <!-- Información de la Desaparición -->
    <fieldset>
        <legend>Información de la Desaparición</legend>
        <input type="date" name="fecha_desaparicion" required>
        <input type="text" name="ubicacion" placeholder="Última ubicación conocida (ciudad, barrio, referencia)" required>
        <input type="time" name="hora_desaparicion" placeholder="Hora aproximada (opcional)">
    </fieldset>

    <!-- Características y Particularidades -->
    <fieldset>
        <legend>Características y Particularidades</legend>
        <input type="text" name="vestimenta" placeholder="Vestimenta al momento de la desaparición">
        <input type="text" name="enfermedades" placeholder="Enfermedad o condición médica">
    </fieldset>

    <!-- Información de Contacto -->
    <fieldset>
        <legend>Información de Familiar o Conocido</legend>
        <input type="tel" name="telefono" placeholder="Teléfono familiar o conocido (0-000-000-0000)" required>
        <input type="email" name="correo" placeholder="Correo electrónico (opcional)">
        <input type="text" name="ubicacion_contacto" placeholder="Ubicación (ciudad, barrio, referencia específica)" required>
        <input type="text" name="calle" placeholder="Calle / centro / barrio específico">
    </fieldset>

    <!-- Aceptación de términos -->
    <label>
        <input type="checkbox" name="acepta_terminos" required>
        Acepto compartir esta información para ayudar en la búsqueda.
    </label>

    <input type="hidden" name="action" value="submit_person_form">
    <input type="hidden" name="humanitarios_nonce" value="<?php echo wp_create_nonce('submit_person_form'); ?>">
    
    <button type="submit">Enviar Reporte</button>

    <p class="frm-message"></p>
</form>

<script>
jQuery(document).ready(function($) {
    $('#report-person-form').submit(function(e) {
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
