<?php
get_header();

$post_id = intval($_GET['edit_post']);
$post = get_post($post_id);

// Verificar si el post existe y si el usuario actual es el autor
if (!$post || $post->post_author != get_current_user_id() || $post->post_type !== 'lost_objects') {
    wp_redirect(home_url());
    exit;
}

$meta = get_post_meta($post_id);
$fotos_objeto = get_post_meta($post_id, 'humanitarios_galeria', true);
$featured_image = get_the_post_thumbnail_url($post_id, 'large');
?>

<div class="edit-reporte-container">
    <h2>Editar Objeto Perdido</h2>
    
    <form id="edit-lost-object-form" enctype="multipart/form-data">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <input type="hidden" name="action" value="edit_report">
        <?php wp_nonce_field('edit_lost_object_' . $post_id, 'humanitarios_nonce'); ?>

        <!-- Información del Objeto -->
        <fieldset>
            <legend>Detalles del Objeto</legend>
            
            <label for="nombre_objeto">Nombre del objeto</label>
            <input type="text" name="nombre_objeto" value="<?php echo esc_attr($meta['nombre_objeto'][0] ?? ''); ?>" required>
            
            <label for="tipo_objeto">Tipo de objeto</label>
            <input type="text" name="tipo_objeto" value="<?php echo esc_attr($meta['tipo_objeto'][0] ?? ''); ?>" required>
            
            <label for="descripcion_objeto">Descripción detallada</label>
            <textarea name="descripcion_objeto" required><?php echo esc_textarea($meta['descripcion_objeto'][0] ?? ''); ?></textarea>
            
            <label for="marca_objeto">Marca</label>
            <input type="text" name="marca_objeto" value="<?php echo esc_attr($meta['marca_objeto'][0] ?? ''); ?>">
            
            <label for="modelo_objeto">Modelo</label>
            <input type="text" name="modelo_objeto" value="<?php echo esc_attr($meta['modelo_objeto'][0] ?? ''); ?>">
            
            <label for="color_objeto">Color</label>
            <input type="text" name="color_objeto" value="<?php echo esc_attr($meta['color_objeto'][0] ?? ''); ?>">
            
            <label for="estado_objeto">Estado del objeto</label>
            <select name="estado_objeto">
                <option value="nuevo" <?php selected($meta['estado_objeto'][0] ?? '', 'nuevo'); ?>>Nuevo</option>
                <option value="usado" <?php selected($meta['estado_objeto'][0] ?? '', 'usado'); ?>>Usado</option>
                <option value="dañado" <?php selected($meta['estado_objeto'][0] ?? '', 'dañado'); ?>>Dañado</option>
            </select>
        </fieldset>

        <!-- Ubicación de Pérdida -->
        <fieldset>
            <legend>Ubicación de Pérdida</legend>
            
            <label for="provincia_perdida">Provincia</label>
            <select name="provincia_perdida" id="provincia_perdida" required>
                <option value="Azua" <?php selected($meta['provincia_perdida'][0] ?? '', 'Azua'); ?>>Azua</option>
                <option value="Bahoruco" <?php selected($meta['provincia_perdida'][0] ?? '', 'Bahoruco'); ?>>Bahoruco</option>
                <option value="Barahona" <?php selected($meta['provincia_perdida'][0] ?? '', 'Barahona'); ?>>Barahona</option>
                <option value="Dajabón" <?php selected($meta['provincia_perdida'][0] ?? '', 'Dajabón'); ?>>Dajabón</option>
                <option value="Distrito Nacional" <?php selected($meta['provincia_perdida'][0] ?? '', 'Distrito Nacional'); ?>>Distrito Nacional</option>
                <option value="Duarte" <?php selected($meta['provincia_perdida'][0] ?? '', 'Duarte'); ?>>Duarte</option>
                <option value="El Seibo" <?php selected($meta['provincia_perdida'][0] ?? '', 'El Seibo'); ?>>El Seibo</option>
                <option value="Elías Piña" <?php selected($meta['provincia_perdida'][0] ?? '', 'Elías Piña'); ?>>Elías Piña</option>
                <option value="Espaillat" <?php selected($meta['provincia_perdida'][0] ?? '', 'Espaillat'); ?>>Espaillat</option>
                <option value="Hato Mayor" <?php selected($meta['provincia_perdida'][0] ?? '', 'Hato Mayor'); ?>>Hato Mayor</option>
                <option value="Hermanas Mirabal" <?php selected($meta['provincia_perdida'][0] ?? '', 'Hermanas Mirabal'); ?>>Hermanas Mirabal</option>
                <option value="Independencia" <?php selected($meta['provincia_perdida'][0] ?? '', 'Independencia'); ?>>Independencia</option>
                <option value="La Altagracia" <?php selected($meta['provincia_perdida'][0] ?? '', 'La Altagracia'); ?>>La Altagracia</option>
                <option value="La Romana" <?php selected($meta['provincia_perdida'][0] ?? '', 'La Romana'); ?>>La Romana</option>
                <option value="La Vega" <?php selected($meta['provincia_perdida'][0] ?? '', 'La Vega'); ?>>La Vega</option>
                <option value="María Trinidad Sánchez" <?php selected($meta['provincia_perdida'][0] ?? '', 'María Trinidad Sánchez'); ?>>María Trinidad Sánchez</option>
                <option value="Monseñor Nouel" <?php selected($meta['provincia_perdida'][0] ?? '', 'Monseñor Nouel'); ?>>Monseñor Nouel</option>
                <option value="Monte Cristi" <?php selected($meta['provincia_perdida'][0] ?? '', 'Monte Cristi'); ?>>Monte Cristi</option>
                <option value="Monte Plata" <?php selected($meta['provincia_perdida'][0] ?? '', 'Monte Plata'); ?>>Monte Plata</option>
                <option value="Pedernales" <?php selected($meta['provincia_perdida'][0] ?? '', 'Pedernales'); ?>>Pedernales</option>
                <option value="Peravia" <?php selected($meta['provincia_perdida'][0] ?? '', 'Peravia'); ?>>Peravia</option>
                <option value="Puerto Plata" <?php selected($meta['provincia_perdida'][0] ?? '', 'Puerto Plata'); ?>>Puerto Plata</option>
                <option value="Samaná" <?php selected($meta['provincia_perdida'][0] ?? '', 'Samaná'); ?>>Samaná</option>
                <option value="San Cristóbal" <?php selected($meta['provincia_perdida'][0] ?? '', 'San Cristóbal'); ?>>San Cristóbal</option>
                <option value="San José de Ocoa" <?php selected($meta['provincia_perdida'][0] ?? '', 'San José de Ocoa'); ?>>San José de Ocoa</option>
                <option value="San Juan" <?php selected($meta['provincia_perdida'][0] ?? '', 'San Juan'); ?>>San Juan</option>
                <option value="San Pedro de Macorís" <?php selected($meta['provincia_perdida'][0] ?? '', 'San Pedro de Macorís'); ?>>San Pedro de Macorís</option>
                <option value="Sánchez Ramírez" <?php selected($meta['provincia_perdida'][0] ?? '', 'Sánchez Ramírez'); ?>>Sánchez Ramírez</option>
                <option value="Santiago" <?php selected($meta['provincia_perdida'][0] ?? '', 'Santiago'); ?>>Santiago</option>
                <option value="Santiago Rodríguez" <?php selected($meta['provincia_perdida'][0] ?? '', 'Santiago Rodríguez'); ?>>Santiago Rodríguez</option>
                <option value="Santo Domingo" <?php selected($meta['provincia_perdida'][0] ?? '', 'Santo Domingo'); ?>>Santo Domingo</option>
                <option value="Valverde" <?php selected($meta['provincia_perdida'][0] ?? '', 'Valverde'); ?>>Valverde</option>
            </select>

            <label for="lugar_perdida">Lugar específico</label>
            <input type="text" name="lugar_perdida" value="<?php echo esc_attr($meta['lugar_perdida'][0] ?? ''); ?>" required>
            
            <label for="fecha_perdida">Fecha de pérdida</label>
            <input type="date" name="fecha_perdida" value="<?php echo esc_attr($meta['fecha_perdida'][0] ?? ''); ?>" required>
        </fieldset>

        <!-- Imágenes -->
        <fieldset>
            <legend>Imágenes del Objeto</legend>
            <div class="current-images">
                <?php if ($featured_image) : ?>
                    <div class="image-item">
                        <img src="<?php echo esc_url($featured_image); ?>" width="300">
                        <label>
                            <input type="checkbox" name="remove_featured_image" value="1">
                            Eliminar imagen destacada
                        </label>
                    </div>
                <?php endif;
                
                if (is_array($fotos_objeto)) :
                    foreach ($fotos_objeto as $image_id) :
                        $image_url = wp_get_attachment_url($image_id); ?>
                        <div class="image-item">
                            <img src="<?php echo esc_url($image_url); ?>" width="200">
                            <label>
                                <input type="checkbox" name="remove_images[]" value="<?php echo $image_id; ?>">
                                Eliminar
                            </label>
                        </div>
                    <?php endforeach;
                endif; ?>
            </div>
            
            <label for="foto_objeto">Nuevas fotos (máx. 10 imágenes, 500KB c/u)</label>
            <input type="file" name="foto_objeto[]" id="foto_objeto" multiple accept="image/*">
            <div id="image-preview"></div>
        </fieldset>

        <!-- Información de Contacto -->
        <fieldset>
            <legend>Datos de Contacto</legend>
            
            <label for="nombre_contacto">Nombre</label>
            <input type="text" name="nombre_contacto" value="<?php echo esc_attr($meta['nombre_contacto'][0] ?? ''); ?>" required>
            
            <label for="telefono_contacto">Teléfono</label>
            <input type="tel" name="telefono_contacto" value="<?php echo esc_attr($meta['telefono_contacto'][0] ?? ''); ?>" required>
            
            <label for="correo_contacto">Correo</label>
            <input type="email" name="correo_contacto" value="<?php echo esc_attr($meta['correo_contacto'][0] ?? ''); ?>">
            
            <label for="provincia_contacto">Provincia de contacto</label>
            <select name="provincia_contacto" id="provincia_contacto" required>
                <option value="Azua" <?php selected($meta['provincia_contacto'][0] ?? '', 'Azua'); ?>>Azua</option>
                <option value="Bahoruco" <?php selected($meta['provincia_contacto'][0] ?? '', 'Bahoruco'); ?>>Bahoruco</option>
                <option value="Barahona" <?php selected($meta['provincia_contacto'][0] ?? '', 'Barahona'); ?>>Barahona</option>
                <option value="Dajabón" <?php selected($meta['provincia_contacto'][0] ?? '', 'Dajabón'); ?>>Dajabón</option>
                <option value="Distrito Nacional" <?php selected($meta['provincia_contacto'][0] ?? '', 'Distrito Nacional'); ?>>Distrito Nacional</option>
                <option value="Duarte" <?php selected($meta['provincia_contacto'][0] ?? '', 'Duarte'); ?>>Duarte</option>
                <option value="El Seibo" <?php selected($meta['provincia_contacto'][0] ?? '', 'El Seibo'); ?>>El Seibo</option>
                <option value="Elías Piña" <?php selected($meta['provincia_contacto'][0] ?? '', 'Elías Piña'); ?>>Elías Piña</option>
                <option value="Espaillat" <?php selected($meta['provincia_contacto'][0] ?? '', 'Espaillat'); ?>>Espaillat</option>
                <option value="Hato Mayor" <?php selected($meta['provincia_contacto'][0] ?? '', 'Hato Mayor'); ?>>Hato Mayor</option>
                <option value="Hermanas Mirabal" <?php selected($meta['provincia_contacto'][0] ?? '', 'Hermanas Mirabal'); ?>>Hermanas Mirabal</option>
                <option value="Independencia" <?php selected($meta['provincia_contacto'][0] ?? '', 'Independencia'); ?>>Independencia</option>
                <option value="La Altagracia" <?php selected($meta['provincia_contacto'][0] ?? '', 'La Altagracia'); ?>>La Altagracia</option>
                <option value="La Romana" <?php selected($meta['provincia_contacto'][0] ?? '', 'La Romana'); ?>>La Romana</option>
                <option value="La Vega" <?php selected($meta['provincia_contacto'][0] ?? '', 'La Vega'); ?>>La Vega</option>
                <option value="María Trinidad Sánchez" <?php selected($meta['provincia_contacto'][0] ?? '', 'María Trinidad Sánchez'); ?>>María Trinidad Sánchez</option>
                <option value="Monseñor Nouel" <?php selected($meta['provincia_contacto'][0] ?? '', 'Monseñor Nouel'); ?>>Monseñor Nouel</option>
                <option value="Monte Cristi" <?php selected($meta['provincia_contacto'][0] ?? '', 'Monte Cristi'); ?>>Monte Cristi</option>
                <option value="Monte Plata" <?php selected($meta['provincia_contacto'][0] ?? '', 'Monte Plata'); ?>>Monte Plata</option>
                <option value="Pedernales" <?php selected($meta['provincia_contacto'][0] ?? '', 'Pedernales'); ?>>Pedernales</option>
                <option value="Peravia" <?php selected($meta['provincia_contacto'][0] ?? '', 'Peravia'); ?>>Peravia</option>
                <option value="Puerto Plata" <?php selected($meta['provincia_contacto'][0] ?? '', 'Puerto Plata'); ?>>Puerto Plata</option>
                <option value="Samaná" <?php selected($meta['provincia_contacto'][0] ?? '', 'Samaná'); ?>>Samaná</option>
                <option value="San Cristóbal" <?php selected($meta['provincia_contacto'][0] ?? '', 'San Cristóbal'); ?>>San Cristóbal</option>
                <option value="San José de Ocoa" <?php selected($meta['provincia_contacto'][0] ?? '', 'San José de Ocoa'); ?>>San José de Ocoa</option>
                <option value="San Juan" <?php selected($meta['provincia_contacto'][0] ?? '', 'San Juan'); ?>>San Juan</option>
                <option value="San Pedro de Macorís" <?php selected($meta['provincia_contacto'][0] ?? '', 'San Pedro de Macorís'); ?>>San Pedro de Macorís</option>
                <option value="Sánchez Ramírez" <?php selected($meta['provincia_contacto'][0] ?? '', 'Sánchez Ramírez'); ?>>Sánchez Ramírez</option>
                <option value="Santiago" <?php selected($meta['provincia_contacto'][0] ?? '', 'Santiago'); ?>>Santiago</option>
                <option value="Santiago Rodríguez" <?php selected($meta['provincia_contacto'][0] ?? '', 'Santiago Rodríguez'); ?>>Santiago Rodríguez</option>
                <option value="Santo Domingo" <?php selected($meta['provincia_contacto'][0] ?? '', 'Santo Domingo'); ?>>Santo Domingo</option>
                <option value="Valverde" <?php selected($meta['provincia_contacto'][0] ?? '', 'Valverde'); ?>>Valverde</option>
            </select>

            <label for="direccion_contacto">Dirección de contacto</label>
            <input type="text" name="direccion_contacto" value="<?php echo esc_attr($meta['direccion_contacto'][0] ?? ''); ?>" required>
        </fieldset>

        <button type="submit">Actualizar Reporte</button>
        <p class="frm-message"></p>
    </form>
</div>

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
    $('#edit-lost-object-form').submit(function(e) {
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
            dataType: 'json', // Asegurar parseo correcto de la respuesta
            beforeSend: function() {
                $('.frm-message').text('Enviando...');
            },
            success: function(response) {
                if(response.redirect) {
                    window.location.href = response.redirect; // Redirigir si es exitoso
                } else {
                    const noticeClass = response.success ? 'success' : 'error';
                    $('.frm-message').removeClass(['error', 'success'])
                                     .addClass(noticeClass)
                                     .text(response.data.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Error en la conexión';
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMessage = response.data.message;
                } catch(e) {}
                $('.frm-message').addClass('error').text(errorMessage);
            },
            complete: function() {
                $('#submit').prop('disabled', false);
            }
        });
    });
});
</script>