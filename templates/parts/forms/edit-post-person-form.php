<?php
get_header();

$post_id = intval($_GET['edit_post']);
$post = get_post($post_id);

// Verificar si el post existe y si el usuario actual es el autor
if ( ! $post || $post->post_author != get_current_user_id() || $post->post_type !== 'personas_perdidas' ) {
    wp_redirect(home_url());
    exit;
}

// Obtener metadatos existentes
$meta = get_post_meta($post_id);
$fotos_persona = get_post_meta($post_id, 'foto_persona', true); // Se espera un arreglo de IDs de imagen
// Si no hay múltiples imágenes, se usa la imagen destacada como fallback
if ( !is_array($fotos_persona) || empty($fotos_persona) ) {
    $foto_persona = get_the_post_thumbnail_url($post_id, 'large') ?: 'https://humanitarios.do/wp-content/uploads/2025/02/desaparecidosimg.jpg';
}
?>

<div class="edit-reporte-container">
    <h2>Editar Reporte de Persona Desaparecida</h2>
    
    <form id="edit-person-form" enctype="multipart/form-data">
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <input type="hidden" name="action" value="edit_report">
    <?php wp_nonce_field('edit_personas_perdidas_' . $post_id, 'humanitarios_nonce'); ?>

        <!-- Información Básica -->
        <fieldset>
            <legend>Información Básica</legend>
            <label for="nombre_completo">Nombre completo</label>
            <input type="text" name="nombre_completo" value="<?php echo esc_attr($post->post_title); ?>" placeholder="Nombre completo o apodo">
            <label for="edad">Edad aproximada</label>
            <input type="number" name="edad" value="<?php echo esc_attr($meta['edad'][0] ?? ''); ?>" required>
            <label for="nacionalidad">Nacionalidad</label>
            <input type="text" name="nacionalidad" value="<?php echo esc_attr($meta['nacionalidad'][0] ?? ''); ?>" placeholder="Nacionalidad">
            <label for="genero">Género</label>
            <select name="genero">
                <option value="Hombre" <?php selected($meta['genero'][0] ?? '', 'Hombre'); ?>>Masculino</option>
                <option value="Mujer" <?php selected($meta['genero'][0] ?? '', 'Mujer'); ?>>Femenino</option>
                <option value="No especificado" <?php selected($meta['genero'][0] ?? '', 'No especificado'); ?>>No especificado</option>
            </select>
            <!-- Dentro del formulario -->
            <div class="current-images">
                <?php
                // Obtener la imagen destacada
                $featured_image_id = get_post_thumbnail_id($post_id);
                $featured_image_url = $featured_image_id ? wp_get_attachment_url($featured_image_id) : '';

                // Obtener la galería de imágenes
                $gallery_images = get_post_meta($post_id, 'humanitarios_galeria', true);
                $gallery_images = is_array($gallery_images) ? $gallery_images : [];

                // Mostrar la imagen destacada
                if ($featured_image_url) : ?>
                    <div class="image-item">
                        <img src="<?php echo esc_url($featured_image_url); ?>" alt="Imagen destacada" width="300">
                        <label>
                            <input type="checkbox" name="remove_images[]" value="<?php echo esc_attr($featured_image_id); ?>">
                            Eliminar imagen destacada
                        </label>
                    </div>
                <?php endif;

                // Mostrar las imágenes de la galería
                foreach ($gallery_images as $image_id) :
                    $image_url = wp_get_attachment_url($image_id);
                    if ($image_url) : ?>
                        <div class="image-item">
                            <img src="<?php echo esc_url($image_url); ?>" alt="Imagen de galería" width="200">
                            <label>
                                <input type="checkbox" name="remove_images[]" value="<?php echo esc_attr($image_id); ?>">
                                Eliminar
                            </label>
                        </div>
                    <?php endif;
                endforeach; ?>
            </div>


            <label for="foto_persona">Subir hasta 10 fotografías (máximo 500 KB cada una)</label>
            <input type="file" name="foto_persona[]" id="foto_persona" multiple accept="image/*">
            <!-- Contenedor para previsualizar las imágenes -->
            <div id="image-preview" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;"></div>
        </fieldset>

        <!-- Características Físicas -->
        <fieldset>
            <legend>Características Físicas</legend>
            <label for="color_piel">Color de piel</label>
            <input type="text" name="color_piel" value="<?php echo esc_attr($meta['color_piel'][0] ?? ''); ?>" placeholder="Color de piel">
            <label for="cabello">Color y tipo de cabello</label>
            <input type="text" name="cabello" value="<?php echo esc_attr($meta['cabello'][0] ?? ''); ?>" placeholder="Color y tipo de cabello">
            <label for="altura">Altura aproximada</label>
            <select name="altura">
                <option value="1.45-1.50" <?php selected($meta['altura'][0] ?? '', '1.45-1.50'); ?>>1,45 - 1,50 m</option>
                <option value="1.50-1.55" <?php selected($meta['altura'][0] ?? '', '1.50-1.55'); ?>>1,50 - 1,55 m</option>
                <option value="1.55-1.60" <?php selected($meta['altura'][0] ?? '', '1.55-1.60'); ?>>1,55 - 1,60 m</option>
                <option value="1.60-1.65" <?php selected($meta['altura'][0] ?? '', '1.60-1.65'); ?>>1,60 - 1,65 m</option>
                <option value="1.65-1.70" <?php selected($meta['altura'][0] ?? '', '1.65-1.70'); ?>>1,65 - 1,70 m</option>
                <option value="1.70-1.75" <?php selected($meta['altura'][0] ?? '', '1.70-1.75'); ?>>1,70 - 1,75 m</option>
                <option value="1.75-1.80" <?php selected($meta['altura'][0] ?? '', '1.75-1.80'); ?>>1,75 - 1,80 m</option>
                <option value="1.80-1.85" <?php selected($meta['altura'][0] ?? '', '1.80-1.85'); ?>>1,80 - 1,85 m</option>
                <option value="1.85+" <?php selected($meta['altura'][0] ?? '', '1.85+'); ?>>1,85 m o más</option>
            </select>
        </fieldset>

        <!-- Información de la Desaparición -->
        <fieldset>
            <legend>Información de la Desaparición</legend>
            <label for="fecha_desaparicion">Fecha de desaparición</label>
            <input type="date" name="fecha_desaparicion" value="<?php echo esc_attr($meta['fecha_desaparicion'][0] ?? ''); ?>" required>
            <label for="provincia">Provincia</label>
            <select name="provincia" id="provincia" required>
                <option value="Azua" <?php selected($meta['provincia'][0] ?? '', 'Azua'); ?>>Azua</option>
                <option value="Bahoruco" <?php selected($meta['provincia'][0] ?? '', 'Bahoruco'); ?>>Bahoruco</option>
                <option value="Barahona" <?php selected($meta['provincia'][0] ?? '', 'Barahona'); ?>>Barahona</option>
                <option value="Dajabón" <?php selected($meta['provincia'][0] ?? '', 'Dajabón'); ?>>Dajabón</option>
                <option value="Distrito Nacional" <?php selected($meta['provincia'][0] ?? '', 'Distrito Nacional'); ?>>Distrito Nacional</option>
                <option value="Duarte" <?php selected($meta['provincia'][0] ?? '', 'Duarte'); ?>>Duarte</option>
                <option value="El Seibo" <?php selected($meta['provincia'][0] ?? '', 'El Seibo'); ?>>El Seibo</option>
                <option value="Elías Piña" <?php selected($meta['provincia'][0] ?? '', 'Elías Piña'); ?>>Elías Piña</option>
                <option value="Espaillat" <?php selected($meta['provincia'][0] ?? '', 'Espaillat'); ?>>Espaillat</option>
                <option value="Hato Mayor" <?php selected($meta['provincia'][0] ?? '', 'Hato Mayor'); ?>>Hato Mayor</option>
                <option value="Hermanas Mirabal" <?php selected($meta['provincia'][0] ?? '', 'Hermanas Mirabal'); ?>>Hermanas Mirabal</option>
                <option value="Independencia" <?php selected($meta['provincia'][0] ?? '', 'Independencia'); ?>>Independencia</option>
                <option value="La Altagracia" <?php selected($meta['provincia'][0] ?? '', 'La Altagracia'); ?>>La Altagracia</option>
                <option value="La Romana" <?php selected($meta['provincia'][0] ?? '', 'La Romana'); ?>>La Romana</option>
                <option value="La Vega" <?php selected($meta['provincia'][0] ?? '', 'La Vega'); ?>>La Vega</option>
                <option value="María Trinidad Sánchez" <?php selected($meta['provincia'][0] ?? '', 'María Trinidad Sánchez'); ?>>María Trinidad Sánchez</option>
                <option value="Monseñor Nouel" <?php selected($meta['provincia'][0] ?? '', 'Monseñor Nouel'); ?>>Monseñor Nouel</option>
                <option value="Monte Cristi" <?php selected($meta['provincia'][0] ?? '', 'Monte Cristi'); ?>>Monte Cristi</option>
                <option value="Monte Plata" <?php selected($meta['provincia'][0] ?? '', 'Monte Plata'); ?>>Monte Plata</option>
                <option value="Pedernales" <?php selected($meta['provincia'][0] ?? '', 'Pedernales'); ?>>Pedernales</option>
                <option value="Peravia" <?php selected($meta['provincia'][0] ?? '', 'Peravia'); ?>>Peravia</option>
                <option value="Puerto Plata" <?php selected($meta['provincia'][0] ?? '', 'Puerto Plata'); ?>>Puerto Plata</option>
                <option value="Samaná" <?php selected($meta['provincia'][0] ?? '', 'Samaná'); ?>>Samaná</option>
                <option value="San Cristóbal" <?php selected($meta['provincia'][0] ?? '', 'San Cristóbal'); ?>>San Cristóbal</option>
                <option value="San José de Ocoa" <?php selected($meta['provincia'][0] ?? '', 'San José de Ocoa'); ?>>San José de Ocoa</option>
                <option value="San Juan" <?php selected($meta['provincia'][0] ?? '', 'San Juan'); ?>>San Juan</option>
                <option value="San Pedro de Macorís" <?php selected($meta['provincia'][0] ?? '', 'San Pedro de Macorís'); ?>>San Pedro de Macorís</option>
                <option value="Sánchez Ramírez" <?php selected($meta['provincia'][0] ?? '', 'Sánchez Ramírez'); ?>>Sánchez Ramírez</option>
                <option value="Santiago" <?php selected($meta['provincia'][0] ?? '', 'Santiago'); ?>>Santiago</option>
                <option value="Santiago Rodríguez" <?php selected($meta['provincia'][0] ?? '', 'Santiago Rodríguez'); ?>>Santiago Rodríguez</option>
                <option value="Santo Domingo" <?php selected($meta['provincia'][0] ?? '', 'Santo Domingo'); ?>>Santo Domingo</option>
                <option value="Valverde" <?php selected($meta['provincia'][0] ?? '', 'Valverde'); ?>>Valverde</option>
            </select>
            <label for="ubicacion">Última ubicación</label>
            <input type="text" name="ubicacion" value="<?php echo esc_attr($meta['ubicacion'][0] ?? ''); ?>" placeholder="Última ubicación especifica (ciudad, barrio, referencia)" required>
            <label for="hora_desaparicion">Hora aproximada</label>
            <input type="time" name="hora_desaparicion" value="<?php echo esc_attr($meta['hora_desaparicion'][0] ?? ''); ?>" placeholder="Hora aproximada">
        </fieldset>

        <!-- Características y Particularidades -->
        <fieldset>
            <legend>Características y Particularidades</legend>
            <label for="vestimenta">Vestimenta al momento de desaparición</label>
            <input type="text" name="vestimenta" value="<?php echo esc_attr($meta['vestimenta'][0] ?? ''); ?>" placeholder="Ejemplo: camisa roja y jeans">
            <label for="enfermedades">Enfermedad o condición médica</label>
            <input type="text" name="enfermedades" value="<?php echo esc_attr($meta['enfermedades'][0] ?? ''); ?>" placeholder="Ejemplo: necesita medicación, alzheimer, problemas de movilidad">
        </fieldset>

        <!-- Información de Contacto -->
        <fieldset>
            <legend>Información de Familiar o Conocido</legend>
            <label for="nombre_familiar1">Nombre del Familiar 1</label>
            <input type="text" name="nombre_familiar1" value="<?php echo esc_attr($meta['nombre_familiar1'][0] ?? ''); ?>" placeholder="Nombre del familiar" required>
            <label for="nombre_familiar2">Nombre del Familiar 2</label>
            <input type="text" name="nombre_familiar2" value="<?php echo esc_attr($meta['nombre_familiar2'][0] ?? ''); ?>" placeholder="Nombre del familiar" required>
            <label for="telefono_1">Teléfono familiar 1</label>
            <input type="tel" name="telefono_1" value="<?php echo esc_attr($meta['telefono_1'][0] ?? ''); ?>" placeholder="Teléfono familiar o conocido (0-000-000-0000)" required>
            <label for="telefono_2">Teléfono familiar 2</label>
            <input type="tel" name="telefono_2" value="<?php echo esc_attr($meta['telefono_2'][0] ?? ''); ?>" placeholder="Teléfono familiar o conocido (0-000-000-0000)" required>
            <label for="correo">Correo electrónico</label>
            <input type="email" name="correo" value="<?php echo esc_attr($meta['correo'][0] ?? ''); ?>" placeholder="Correo electrónico (opcional)">
            <label for="provincia_contacto">Provincia</label>
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
            <label for="calle">Calle especifica</label>
            <input type="text" name="calle" value="<?php echo esc_attr($meta['calle'][0] ?? ''); ?>" placeholder="Ciudad / calle / centro / barrio específico">
        </fieldset>

        <!-- Aceptación de términos -->
        <label>
            <input type="checkbox" name="acepta_terminos" required>
            Acepto compartir esta información para ayudar en la búsqueda.
        </label>

        <button id="submit" type="submit">Actualizar Reporte</button>

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

    const fileInput = document.getElementById('foto_persona');
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
    $('#edit-person-form, #edit-pet-form').submit(function(e) {
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
