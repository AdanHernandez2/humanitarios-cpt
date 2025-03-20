<?php
// Seguridad: Bloquear acceso directo
defined('ABSPATH') || exit();

// Redirigir si el usuario no está logueado
if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}
?>

<form id="report-encontrado-form" enctype="multipart/form-data">
    <h2>Reportar Encontrado</h2>

    <!-- Información del Encontrado -->
    <fieldset>
        <legend>Información del Encontrado</legend>
        <label for="tipo_encontrado">Tipo</label>
        <select name="tipo_encontrado" id="tipo_encontrado" required>
            <option value="" disabled selected>Seleccionar tipo</option>
            <option value="persona">Persona</option>
            <option value="mascota">Mascota</option>
            <option value="objeto">Objeto</option>
        </select>

        <!-- Campos específicos para Persona -->
        <div id="persona_fields" class="tipo_fields" style="display: none;">
            <label for="nombre_persona">Nombre de la persona</label>
            <input type="text" name="nombre_persona" placeholder="Nombre completo">

            <label for="edad_persona">Edad aproximada</label>
            <select name="edad_persona" id="edad_persona" required>
                <option value="" disabled selected>Seleccione un rango de edad</option>
                <option value="0-5">0 - 5 años</option>
                <option value="6-10">6 - 10 años</option>
                <option value="11-15">11 - 15 años</option>
                <option value="16-20">16 - 20 años</option>
                <option value="21-25">21 - 25 años</option>
                <option value="26-30">26 - 30 años</option>
                <option value="31-35">31 - 35 años</option>
                <option value="36-40">36 - 40 años</option>
                <option value="41-45">41 - 45 años</option>
                <option value="46-50">46 - 50 años</option>
                <option value="51-55">51 - 55 años</option>
                <option value="56-60">56 - 60 años</option>
                <option value="61-65">61 - 65 años</option>
                <option value="66-70">66 - 70 años</option>
                <option value="71+">71 años o más</option>
            </select>

            <label for="genero_persona">Género</label>
            <select name="genero_persona">
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="otro">Otro</option>
            </select>
        </div>

        <!-- Campos específicos para Mascota -->
        <div id="mascota_fields" class="tipo_fields" style="display: none;">
            <label for="tipo_mascota">Tipo de mascota</label>
            <select name="tipo_mascota">
                <option value="perro">Perro</option>
                <option value="gato">Gato</option>
                <option value="otro">Otro</option>
            </select>

            <label for="sexo_mascota">Sexo de la mascota</label>
            <select name="sexo_mascota">
                <option value="macho">Macho</option>
                <option value="hembra">Hembra</option>
            </select>

            <label for="raza_mascota">Raza</label>
            <input type="text" name="raza_mascota" placeholder="Raza de la mascota">

            <label for="color_mascota">Color</label>
            <input type="text" name="color_mascota" placeholder="Color de la mascota">

            <label for="identificacion_mascota">¿Tiene collar o identificación?</label>
            <select name="identificacion_mascota">
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>
        </div>

        <!-- Campos específicos para Objeto -->
        <div id="objeto_fields" class="tipo_fields" style="display: none;">
            <label for="tipo_objeto">Tipo de objeto</label>
            <input type="text" name="tipo_objeto" placeholder="Ej: Billetera, Teléfono, etc.">

            <label for="marca_objeto">Marca</label>
            <input type="text" name="marca_objeto" placeholder="Marca del objeto">

            <label for="modelo_objeto">Modelo</label>
            <input type="text" name="modelo_objeto" placeholder="Modelo del objeto">
        </div>

        <!-- Campos comunes a todos los tipos -->
        <label for="descripcion_encontrado">Descripción</label>
        <textarea name="descripcion_encontrado" placeholder="Describe lo encontrado"></textarea>

        <!-- Provincia y dirección del encontrado -->
        <label for="provincia_encontrado">Provincia</label>
        <select name="provincia_encontrado" id="provincia_encontrado" required>
            <option value="">Seleccione una provincia</option>
            <option value="Distrito Nacional">Distrito Nacional</option>
            <option value="Santo Domingo">Santo Domingo</option>
            <option value="Santiago">Santiago</option>
            <option value="La Vega">La Vega</option>
            <option value="San Cristóbal">San Cristóbal</option>
            <option value="La Altagracia">La Altagracia</option>
            <option value="Puerto Plata">Puerto Plata</option>
            <option value="Duarte">Duarte</option>
            <option value="Espaillat">Espaillat</option>
            <option value="San Pedro de Macorís">San Pedro de Macorís</option>
            <option value="La Romana">La Romana</option>
            <option value="Azua">Azua</option>
            <option value="San Juan">San Juan</option>
            <option value="Barahona">Barahona</option>
            <option value="Valverde">Valverde</option>
            <option value="Peravia">Peravia</option>
            <option value="María Trinidad Sánchez">María Trinidad Sánchez</option>
            <option value="El Seibo">El Seibo</option>
            <option value="Hato Mayor">Hato Mayor</option>
            <option value="Monte Plata">Monte Plata</option>
            <option value="Samaná">Samaná</option>
            <option value="Pedernales">Pedernales</option>
            <option value="Independencia">Independencia</option>
            <option value="Dajabón">Dajabón</option>
            <option value="Monte Cristi">Monte Cristi</option>
            <option value="Sánchez Ramírez">Sánchez Ramírez</option>
            <option value="Monseñor Nouel">Monseñor Nouel</option>
            <option value="Hermanas Mirabal">Hermanas Mirabal</option>
            <option value="Santiago Rodríguez">Santiago Rodríguez</option>
            <option value="San José de Ocoa">San José de Ocoa</option>
            <option value="Elías Piña">Elías Piña</option>
        </select>

        <label for="direccion_encontrado">Dirección exacta</label>
        <input type="text" name="direccion_encontrado" placeholder="Ej: Calle Principal #123, Ciudad" required>

        <label for="fecha_encontrado">Fecha en que se encontró</label>
        <input type="date" name="fecha_encontrado" required>

        <label for="foto_encontrado">Subir fotografías (máximo 500 KB cada una)</label>
        <input type="file" name="foto_encontrado[]" id="foto_encontrado" multiple accept="image/*">
        <!-- Contenedor para previsualizar las imágenes -->
        <div id="image-preview" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;"></div>
    </fieldset>

    <!-- Información de Contacto -->
    <fieldset>
        <legend>Información de Contacto</legend>
        <label for="nombre_contacto">Nombre de contacto</label>
        <input type="text" name="nombre_contacto" placeholder="Nombre completo" required>

        <label for="telefono_contacto">Teléfono de contacto</label>
        <input type="tel" name="telefono_contacto" placeholder="Ej: 1-809-555-1234" required>

        <label for="correo_contacto">Correo electrónico</label>
        <input type="email" name="correo_contacto" placeholder="Ej: contacto@example.com">

        <!-- Provincia y dirección del contacto -->
        <label for="provincia_contacto">Provincia</label>
        <select name="provincia_contacto" id="provincia_contacto" required>
            <option value="">Seleccione una provincia</option>
            <option value="Distrito Nacional">Distrito Nacional</option>
            <option value="Santo Domingo">Santo Domingo</option>
            <option value="Santiago">Santiago</option>
            <option value="La Vega">La Vega</option>
            <option value="San Cristóbal">San Cristóbal</option>
            <option value="La Altagracia">La Altagracia</option>
            <option value="Puerto Plata">Puerto Plata</option>
            <option value="Duarte">Duarte</option>
            <option value="Espaillat">Espaillat</option>
            <option value="San Pedro de Macorís">San Pedro de Macorís</option>
            <option value="La Romana">La Romana</option>
            <option value="Azua">Azua</option>
            <option value="San Juan">San Juan</option>
            <option value="Barahona">Barahona</option>
            <option value="Valverde">Valverde</option>
            <option value="Peravia">Peravia</option>
            <option value="María Trinidad Sánchez">María Trinidad Sánchez</option>
            <option value="El Seibo">El Seibo</option>
            <option value="Hato Mayor">Hato Mayor</option>
            <option value="Monte Plata">Monte Plata</option>
            <option value="Samaná">Samaná</option>
            <option value="Pedernales">Pedernales</option>
            <option value="Independencia">Independencia</option>
            <option value="Dajabón">Dajabón</option>
            <option value="Monte Cristi">Monte Cristi</option>
            <option value="Sánchez Ramírez">Sánchez Ramírez</option>
            <option value="Monseñor Nouel">Monseñor Nouel</option>
            <option value="Hermanas Mirabal">Hermanas Mirabal</option>
            <option value="Santiago Rodríguez">Santiago Rodríguez</option>
            <option value="San José de Ocoa">San José de Ocoa</option>
            <option value="Elías Piña">Elías Piña</option>
        </select>

        <label for="direccion_contacto">Dirección de contacto</label>
        <input type="text" name="direccion_contacto" placeholder="Ej: Calle Principal #123, Ciudad" required>
    </fieldset>

    <!-- Aceptación de términos -->
    <label>
        <input type="checkbox" name="acepta_terminos" required>
        Acepto compartir esta información para ayudar en la búsqueda.
    </label>

    <input type="hidden" name="action" value="submit_found_form">
    <input type="hidden" name="humanitarios_nonce" value="<?php echo wp_create_nonce('submit_found_form'); ?>">
    
    <button id="submit" type="submit">Enviar Reporte</button>

    <p class="frm-message"></p>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoEncontrado = document.getElementById('tipo_encontrado');
    const personaFields = document.getElementById('persona_fields');
    const mascotaFields = document.getElementById('mascota_fields');
    const objetoFields = document.getElementById('objeto_fields');

    tipoEncontrado.addEventListener('change', function() {
        // Ocultar todos los campos específicos
        personaFields.style.display = 'none';
        mascotaFields.style.display = 'none';
        objetoFields.style.display = 'none';

        // Mostrar los campos correspondientes al tipo seleccionado
        if (tipoEncontrado.value === 'persona') {
            personaFields.style.display = 'block';
        } else if (tipoEncontrado.value === 'mascota') {
            mascotaFields.style.display = 'block';
        } else if (tipoEncontrado.value === 'objeto') {
            objetoFields.style.display = 'block';
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const MAX_INDIVIDUAL_SIZE = 500 * 1024; // 500 KB
    const MAX_TOTAL_SIZE = 10 * 1024 * 1024; // 10 MB
    const MAX_FILES = 10;

    const fileInput = document.getElementById('foto_encontrado');
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
    $('#report-encontrado-form').submit(function(e) {
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