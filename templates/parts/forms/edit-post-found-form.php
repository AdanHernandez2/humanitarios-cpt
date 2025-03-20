<?php
get_header();

$post_id = intval($_GET['edit_post']);
$post = get_post($post_id);

if (!$post || $post->post_author != get_current_user_id() || $post->post_type !== 'found-form') {
    wp_redirect(home_url());
    exit;
}

$meta = get_post_meta($post_id);
$fotos_encontrado = get_post_meta($post_id, 'humanitarios_galeria', true);
?>

<div class="edit-reporte-container">
    <h2>Editar Objeto/Persona Encontrada</h2>
    
    <form id="edit-found-form" enctype="multipart/form-data">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <input type="hidden" name="action" value="edit_report">
        <?php wp_nonce_field('edit_found-form_' . $post_id, 'humanitarios_nonce'); ?>

        <!-- Tipo de Encontrado -->
        <fieldset>
            <legend>Tipo</legend>
            <select name="tipo_encontrado" id="tipo_encontrado" required>
                <option value="objeto" <?php selected($meta['tipo_encontrado'][0] ?? '', 'objeto'); ?>>Objeto</option>
                <option value="persona" <?php selected($meta['tipo_encontrado'][0] ?? '', 'persona'); ?>>Persona</option>
                <option value="mascota" <?php selected($meta['tipo_encontrado'][0] ?? '', 'mascota'); ?>>Mascota</option>
            </select>
        </fieldset>

        <!-- Campos específicos para Persona -->
        <div id="persona_fields" class="tipo_fields" style="display: <?php echo ($meta['tipo_encontrado'][0] ?? '') === 'persona' ? 'block' : 'none'; ?>;">
            <label for="nombre_persona">Nombre de la persona</label>
            <input type="text" name="nombre_persona" value="<?php echo esc_attr($post->post_title); ?>" placeholder="Nombre completo">

            <label for="edad_persona">Edad aproximada</label>
            <select name="edad_persona" id="edad_persona" required>
                <option value="" disabled <?php echo empty($meta['edad_persona'][0]) ? 'selected' : ''; ?>>Seleccione un rango de edad</option>
                <option value="0-5" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '0-5' ? 'selected' : ''; ?>>0 - 5 años</option>
                <option value="6-10" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '6-10' ? 'selected' : ''; ?>>6 - 10 años</option>
                <option value="11-15" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '11-15' ? 'selected' : ''; ?>>11 - 15 años</option>
                <option value="16-20" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '16-20' ? 'selected' : ''; ?>>16 - 20 años</option>
                <option value="21-25" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '21-25' ? 'selected' : ''; ?>>21 - 25 años</option>
                <option value="26-30" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '26-30' ? 'selected' : ''; ?>>26 - 30 años</option>
                <option value="31-35" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '31-35' ? 'selected' : ''; ?>>31 - 35 años</option>
                <option value="36-40" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '36-40' ? 'selected' : ''; ?>>36 - 40 años</option>
                <option value="41-45" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '41-45' ? 'selected' : ''; ?>>41 - 45 años</option>
                <option value="46-50" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '46-50' ? 'selected' : ''; ?>>46 - 50 años</option>
                <option value="51-55" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '51-55' ? 'selected' : ''; ?>>51 - 55 años</option>
                <option value="56-60" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '56-60' ? 'selected' : ''; ?>>56 - 60 años</option>
                <option value="61-65" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '61-65' ? 'selected' : ''; ?>>61 - 65 años</option>
                <option value="66-70" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '66-70' ? 'selected' : ''; ?>>66 - 70 años</option>
                <option value="71+" <?php echo esc_attr($meta['edad_persona'][0] ?? '') === '71+' ? 'selected' : ''; ?>>71 años o más</option>
            </select>

            <label for="genero_persona">Género</label>
            <select name="genero_persona">
                <option value="masculino" <?php selected($meta['genero_persona'][0] ?? '', 'masculino'); ?>>Masculino</option>
                <option value="femenino" <?php selected($meta['genero_persona'][0] ?? '', 'femenino'); ?>>Femenino</option>
                <option value="otro" <?php selected($meta['genero_persona'][0] ?? '', 'otro'); ?>>Otro</option>
            </select>
        </div>

        <!-- Campos específicos para Mascota -->
        <div id="mascota_fields" class="tipo_fields" style="display: <?php echo ($meta['tipo_encontrado'][0] ?? '') === 'mascota' ? 'block' : 'none'; ?>;">
            <label for="tipo_mascota">Tipo de mascota</label>
            <input type="text" name="tipo_mascota" value="<?php echo esc_attr($meta['tipo_mascota'][0] ?? ''); ?>" placeholder="Ej: Perro, Gato, etc.">

            <label for="raza_mascota">Raza</label>
            <input type="text" name="raza_mascota" value="<?php echo esc_attr($meta['raza_mascota'][0] ?? ''); ?>" placeholder="Raza de la mascota">

            <label for="color_mascota">Color</label>
            <input type="text" name="color_mascota" value="<?php echo esc_attr($meta['color_mascota'][0] ?? ''); ?>" placeholder="Color de la mascota">
        </div>

        <!-- Campos específicos para Objeto -->
        <div id="objeto_fields" class="tipo_fields" style="display: <?php echo ($meta['tipo_encontrado'][0] ?? '') === 'objeto' ? 'block' : 'none'; ?>;">
            <label for="tipo_objeto">Tipo de objeto</label>
            <input type="text" name="tipo_objeto" value="<?php echo esc_attr($meta['tipo_objeto'][0] ?? ''); ?>" placeholder="Ej: Billetera, Teléfono, etc.">

            <label for="marca_objeto">Marca</label>
            <input type="text" name="marca_objeto" value="<?php echo esc_attr($meta['marca_objeto'][0] ?? ''); ?>" placeholder="Marca del objeto">

            <label for="modelo_objeto">Modelo</label>
            <input type="text" name="modelo_objeto" value="<?php echo esc_attr($meta['modelo_objeto'][0] ?? ''); ?>" placeholder="Modelo del objeto">
        </div>

        <!-- Campos comunes -->
        <fieldset>
            <legend>Descripción</legend>
            <textarea name="descripcion_encontrado" required><?php echo esc_textarea($meta['descripcion_encontrado'][0] ?? ''); ?></textarea>
            
            <label for="direccion_encontrado">Dirección exacta</label>
            <input type="text" name="direccion_encontrado" value="<?php echo esc_attr($meta['direccion_encontrado'][0] ?? ''); ?>" required>
            
            <label for="fecha_encontrado">Fecha</label>
            <input type="date" name="fecha_encontrado" value="<?php echo esc_attr($meta['fecha_encontrado'][0] ?? ''); ?>" required>
        </fieldset>

        <!-- Información de Contacto -->
        <fieldset>
            <legend>Contacto</legend>
            <input type="text" name="nombre_contacto" value="<?php echo esc_attr($meta['nombre_contacto'][0] ?? ''); ?>" placeholder="Nombre" required>
            <input type="tel" name="telefono_contacto" value="<?php echo esc_attr($meta['telefono_contacto'][0] ?? ''); ?>" placeholder="Teléfono" required>
            <input type="email" name="correo_contacto" value="<?php echo esc_attr($meta['correo_contacto'][0] ?? ''); ?>" placeholder="Correo">
        </fieldset>

        <!-- Imágenes -->
        <fieldset>
            <legend>Fotografías</legend>
            <div class="current-images">
                <?php 
                $gallery_images = is_array($fotos_encontrado) ? $fotos_encontrado : [];
                foreach ($gallery_images as $image_id) :
                    $image_url = wp_get_attachment_url($image_id); ?>
                    <div class="image-item">
                        <img src="<?php echo esc_url($image_url); ?>" width="200">
                        <label>
                            <input type="checkbox" name="remove_images[]" value="<?php echo $image_id; ?>">
                            Eliminar
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <input type="file" name="foto_encontrado[]" id="foto_encontrado" multiple accept="image/*">
            <div id="image-preview"></div>
        </fieldset>

        <button type="submit">Actualizar Reporte</button>
        <p class="frm-message"></p>
    </form>
</div>

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
    const removeImageCheckboxes = document.querySelectorAll('input[name="remove_images[]"]');

    removeImageCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const imageItem = this.closest('.image-item');
            if (this.checked) {
                imageItem.style.opacity = '0.5'; // Marcar visualmente la imagen para eliminar
            } else {
                imageItem.style.opacity = '1'; // Restaurar la opacidad
            }
        });
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
    $('#edit-found-form').submit(function(e) {
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
                    $('#edit-person-form')[0].reset();
                }
            },
            error: function(xhr, status, error) {
                // Mostrar errores de conexión o del servidor
                $('.frm-message').addClass('error').text('Error en la conexión: ' + error);
            },
            complete: function() {
                $('#submit').prop('disabled', false);
            }
        });
    });
});
</script>
