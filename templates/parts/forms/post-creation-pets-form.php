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
        <label for="foto_mascota">Subir hasta 10 fotografías (máximo 500 KB cada una)</label>
        <input type="file" name="foto_mascota[]" id="foto_mascota" multiple accept="image/*">
        <!-- Contenedor para previsualizar las imágenes -->
        <div id="image-preview" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;"></div>
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
            <option value="macho">Macho</option>
            <option value="hembra">Hembra</option>
        </select>
        <label for="identificacion">¿Tiene collar o identificación?</label>
        <select name="identificacion">
            <option value="si">Sí</option>
            <option value="no">No</option>
            <option value="desconocido">No se sabe</option>
        </select>
    </fieldset>

    <!-- Información de la Desaparición -->
    <fieldset>
        <legend>Información de la Desaparición</legend>
        <label for="fecha_desaparicion">Fecha desaparición</label>
        <input type="date" name="fecha_desaparicion" required>
        <label for="provincia">Provincia</label>
        <select name="provincia" id="provincia" required>
            <option value="Azua">Azua</option>
            <option value="Bahoruco">Bahoruco</option>
            <option value="Barahona">Barahona</option>
            <option value="Dajabón">Dajabón</option>
            <option value="Distrito Nacional">Distrito Nacional</option>
            <option value="Duarte">Duarte</option>
            <option value="El Seibo">El Seibo</option>
            <option value="Elías Piña">Elías Piña</option>
            <option value="Espaillat">Espaillat</option>
            <option value="Hato Mayor">Hato Mayor</option>
            <option value="Hermanas Mirabal">Hermanas Mirabal</option>
            <option value="Independencia">Independencia</option>
            <option value="La Altagracia">La Altagracia</option>
            <option value="La Romana">La Romana</option>
            <option value="La Vega">La Vega</option>
            <option value="María Trinidad Sánchez">María Trinidad Sánchez</option>
            <option value="Monseñor Nouel">Monseñor Nouel</option>
            <option value="Monte Cristi">Monte Cristi</option>
            <option value="Monte Plata">Monte Plata</option>
            <option value="Pedernales">Pedernales</option>
            <option value="Peravia">Peravia</option>
            <option value="Puerto Plata">Puerto Plata</option>
            <option value="Samaná">Samaná</option>
            <option value="San Cristóbal">San Cristóbal</option>
            <option value="San José de Ocoa">San José de Ocoa</option>
            <option value="San Juan">San Juan</option>
            <option value="San Pedro de Macorís">San Pedro de Macorís</option>
            <option value="Sánchez Ramírez">Sánchez Ramírez</option>
            <option value="Santiago">Santiago</option>
            <option value="Santiago Rodríguez">Santiago Rodríguez</option>
            <option value="Santo Domingo">Santo Domingo</option>
            <option value="Valverde">Valverde</option>
        </select>
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
        <label for="nombre_familiar1">Nombre del Familiar 1</label>
        <input type="text" name="nombre_familiar1" placeholder="Nombre del familiar" required>
        <label for="nombre_familiar2">Nombre del Familiar 2</label>
        <input type="text" name="nombre_familiar2" placeholder="Nombre del familiar" required>
        <label for="telefono_1">Teléfono familiar 1</label>
        <input type="tel" name="telefono_1" placeholder="Teléfono familiar o conocido (0-000-000-0000)" required>
        <label for="telefono_2">Teléfono familiar 2</label>
        <input type="tel" name="telefono_2" placeholder="Teléfono familiar o conocido (0-000-000-0000)" required>
        <label for="provincia_contacto">Provincia</label>
        <select name="provincia_contacto" id="provincia_contacto" required>
            <option value="Azua">Azua</option>
            <option value="Bahoruco">Bahoruco</option>
            <option value="Barahona">Barahona</option>
            <option value="Dajabón">Dajabón</option>
            <option value="Distrito Nacional">Distrito Nacional</option>
            <option value="Duarte">Duarte</option>
            <option value="El Seibo">El Seibo</option>
            <option value="Elías Piña">Elías Piña</option>
            <option value="Espaillat">Espaillat</option>
            <option value="Hato Mayor">Hato Mayor</option>
            <option value="Hermanas Mirabal">Hermanas Mirabal</option>
            <option value="Independencia">Independencia</option>
            <option value="La Altagracia">La Altagracia</option>
            <option value="La Romana">La Romana</option>
            <option value="La Vega">La Vega</option>
            <option value="María Trinidad Sánchez">María Trinidad Sánchez</option>
            <option value="Monseñor Nouel">Monseñor Nouel</option>
            <option value="Monte Cristi">Monte Cristi</option>
            <option value="Monte Plata">Monte Plata</option>
            <option value="Pedernales">Pedernales</option>
            <option value="Peravia">Peravia</option>
            <option value="Puerto Plata">Puerto Plata</option>
            <option value="Samaná">Samaná</option>
            <option value="San Cristóbal">San Cristóbal</option>
            <option value="San José de Ocoa">San José de Ocoa</option>
            <option value="San Juan">San Juan</option>
            <option value="San Pedro de Macorís">San Pedro de Macorís</option>
            <option value="Sánchez Ramírez">Sánchez Ramírez</option>
            <option value="Santiago">Santiago</option>
            <option value="Santiago Rodríguez">Santiago Rodríguez</option>
            <option value="Santo Domingo">Santo Domingo</option>
            <option value="Valverde">Valverde</option>
        </select>
        <label for="calle">Calle especifica</label>
        <input type="text" name="calle" placeholder="Ciudad / calle / centro / barrio específico">
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
    
    <button id="submit" type="submit">Enviar Reporte</button>

    <p class="frm-message"></p>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const MAX_INDIVIDUAL_SIZE = 500 * 1024; // 500 KB
    const MAX_TOTAL_SIZE = 10 * 1024 * 1024; // 10 MB
    const MAX_FILES = 10;

    const fileInput = document.getElementById('foto_mascota');
    const previewContainer = document.getElementById('image-preview');
    let allFiles = [];

    fileInput.addEventListener('change', function (event) {
        const newFiles = Array.from(event.target.files);
        let errorMessage = '';
        
        // Validación 1: Tamaño individual
        const oversizedFiles = newFiles.filter(file => file.size > MAX_INDIVIDUAL_SIZE);
        if (oversizedFiles.length > 0) {
            errorMessage = `Estas imágenes superan 500 KB:\n${oversizedFiles.map(f => f.name).join(', ')}\nComprímelas antes de subir.`;
            alert(errorMessage);
            return;
        }

        // Validación 2: Cantidad máxima
        if (allFiles.length + newFiles.length > MAX_FILES) {
            const remaining = MAX_FILES - allFiles.length;
            errorMessage = `Solo puedes agregar ${remaining} imagen(es) más.`;
            alert(errorMessage);
            return;
        }

        // Validación 3: Tamaño total
        const currentTotalSize = allFiles.reduce((sum, file) => sum + file.size, 0);
        const newTotalSize = newFiles.reduce((sum, file) => sum + file.size, 0);
        
        if (currentTotalSize + newTotalSize > MAX_TOTAL_SIZE) {
            const currentMB = (currentTotalSize / 1024 / 1024).toFixed(2);
            const newMB = (newTotalSize / 1024 / 1024).toFixed(2);
            errorMessage = `Tamaño total actual: ${currentMB} MB\nNuevo tamaño: ${newMB} MB\nMáximo permitido: 10 MB`;
            alert(errorMessage);
            return;
        }

        // Si pasa todas las validaciones
        allFiles = [...allFiles, ...newFiles];
        updateFileInput(allFiles);
        updatePreview(allFiles);
        updateSizeStatus(); // Actualizar mensaje de estado
    });

    function updatePreview(files) {
        previewContainer.innerHTML = '';
        
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'image-container';
                
                // Imagen
                const img = document.createElement('img');
                img.src = e.target.result;
                
                // Badge de tamaño
                const sizeBadge = document.createElement('div');
                sizeBadge.className = 'size-badge';
                sizeBadge.textContent = `${(file.size / 1024).toFixed(1)} KB`;
                
                // Botón eliminar
                const removeBtn = document.createElement('button');
                removeBtn.className = 'remove-btn';
                removeBtn.textContent = '×';
                removeBtn.onclick = () => removeImage(index);
                
                imgContainer.appendChild(img);
                imgContainer.appendChild(sizeBadge);
                imgContainer.appendChild(removeBtn);
                previewContainer.appendChild(imgContainer);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeImage(index) {
        allFiles.splice(index, 1);
        updateFileInput(allFiles);
        updatePreview(allFiles);
        updateSizeStatus();
    }

    function updateFileInput(files) {
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }

    function updateSizeStatus() {
        const totalSize = allFiles.reduce((sum, file) => sum + file.size, 0);
        const totalMB = (totalSize / 1024 / 1024).toFixed(2);
        const remainingMB = (MAX_TOTAL_SIZE - totalSize) / 1024 / 1024;
        const remainingFiles = MAX_FILES - allFiles.length;
        
        let statusMessage = `✅ Usado: ${totalMB} MB de 10 MB | 📸 ${allFiles.length}/10 imágenes`;
        
        if (remainingFiles > 0) {
            statusMessage += `\n🔼 Puedes agregar ${remainingFiles} más`;
            if (remainingMB < (remainingFiles * 0.5)) { // Si el espacio restante es menor a 500 KB por imagen
                statusMessage += "\n⚠️ Necesitarás comprimir las imágenes para agregar más";
            }
        }
        
        // Si ya hay imágenes pero no se alcanzó el máximo
        if (allFiles.length > 0 && allFiles.length < MAX_FILES) {
            statusMessage += `\n📏 Máximo permitido por imagen: 500 KB`;
        }
        
        alert(statusMessage); // Puedes cambiar esto por un div en tu HTML si prefieres
    }
});
</script>
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