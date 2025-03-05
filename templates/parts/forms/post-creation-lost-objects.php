<?php
// Seguridad: Bloquear acceso directo
defined('ABSPATH') || exit();

// Redirigir si el usuario no está logueado
if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}
?>

<form id="report-object-form" enctype="multipart/form-data">
    <h2>Reportar Objeto Perdido</h2>

    <!-- Información del Objeto -->
    <fieldset>
        <legend>Información del Objeto</legend>
        
        <!-- Nombre y descripción del objeto -->
        <label for="nombre_objeto">Nombre del objeto</label>
        <input type="text" name="nombre_objeto" placeholder="Ej: Billetera, Teléfono, etc." required>

        <label for="tipo_objeto">Tipo de objeto</label>
        <input type="text" name="tipo_objeto" placeholder="Ej: Electrónico, Ropa, Accesorio, etc." required>

        <label for="descripcion_objeto">Descripción detallada</label>
        <textarea name="descripcion_objeto" placeholder="Describe el objeto (color, tamaño, características únicas, etc.)" required></textarea>

        <!-- Detalles adicionales del objeto -->
        <label for="marca_objeto">Marca (si aplica)</label>
        <input type="text" name="marca_objeto" placeholder="Ej: Samsung, Nike, etc.">

        <label for="modelo_objeto">Modelo (si aplica)</label>
        <input type="text" name="modelo_objeto" placeholder="Ej: Galaxy S21, Air Max 90, etc.">

        <label for="color_objeto">Color</label>
        <input type="text" name="color_objeto" placeholder="Ej: Negro, Rojo, Azul, etc.">

        <label for="estado_objeto">Estado del objeto</label>
        <select name="estado_objeto">
            <option value="nuevo">Nuevo</option>
            <option value="usado">Usado</option>
            <option value="dañado">Dañado</option>
        </select>

        <!-- Ubicación donde se perdió el objeto -->
        <label for="provincia">Provincia donde se perdió</label>
        <select name="provincia" id="provincia" required>
            <option value="">Seleccione una provincia</option>
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

        <label for="lugar_perdida">Lugar específico donde se perdió</label>
        <input type="text" name="lugar_perdida" placeholder="Ej: Parque Central, Calle Principal #123, etc." required>

        <label for="fecha_perdida">Fecha en que se perdió</label>
        <input type="date" name="fecha_perdida" required>

        <!-- Subida de fotografías -->
        <label for="foto_objeto">Subir fotografías (máximo 500 KB cada una)</label>
        <input type="file" name="foto_objeto[]" id="foto_objeto" multiple accept="image/*">
        <div id="image-preview" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;"></div>
    </fieldset>

    <!-- Información de Contacto -->
    <fieldset>
        <legend>Información de Contacto</legend>
        
        <!-- Datos del contacto -->
        <label for="nombre_contacto">Nombre de contacto</label>
        <input type="text" name="nombre_contacto" placeholder="Nombre completo" required>

        <label for="telefono_contacto">Teléfono de contacto</label>
        <input type="tel" name="telefono_contacto" placeholder="Ej: 1-809-555-1234" required>

        <label for="correo_contacto">Correo electrónico</label>
        <input type="email" name="correo_contacto" placeholder="Ej: contacto@example.com">

        <!-- Ubicación del contacto -->
        <label for="provincia_contacto">Provincia de contacto</label>
        <select name="provincia_contacto" id="provincia_contacto" required>
            <option value="">Seleccione una provincia</option>
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

        <label for="direccion_contacto">Dirección de contacto</label>
        <input type="text" name="direccion_contacto" placeholder="Ej: Calle Principal #123, Ciudad" required>
    </fieldset>

    <!-- Aceptación de términos -->
    <label>
        <input type="checkbox" name="acepta_terminos" required>
        Acepto compartir esta información para ayudar en la búsqueda.
    </label>

    <!-- Campos ocultos para procesamiento -->
    <input type="hidden" name="action" value="submit_lost_object_form">
    <input type="hidden" name="humanitarios_nonce" value="<?php echo wp_create_nonce('submit_lost_object_form'); ?>">
    
    <!-- Botón de envío -->
    <button id="submit" type="submit">Enviar Reporte</button>

    <!-- Mensaje de respuesta -->
    <p class="frm-message"></p>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const MAX_INDIVIDUAL_SIZE = 500 * 1024; // 500 KB
    const MAX_TOTAL_SIZE = 10 * 1024 * 1024; // 10 MB
    const MAX_FILES = 10;

    const fileInput = document.getElementById('foto_objeto');
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
    $('#report-object-form').submit(function(e) {
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