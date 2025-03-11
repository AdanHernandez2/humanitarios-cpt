<?php
/**
 * Template: Formulario de filtros con AJAX
 */
?>
<div class="humanitarios-filtro-container">
    <form id="humanitarios-filtro-form" method="GET">
        <div class="bloque-1">
            <div class="filtro-group">
                <label><?php _e('Tipo de Búsqueda:', 'humanitarios-cpt'); ?></label>
                <select name="post_type" id="filtro-post-type">
                    <option value=""><?php _e('Todos', 'humanitarios-cpt'); ?></option>
                    <option value="personas_perdidas" <?php selected($_GET['post_type'] ?? '', 'personas_perdidas'); ?>>
                        <?php _e('Personas', 'humanitarios-cpt'); ?>
                    </option>
                    <option value="mascotas_perdidas" <?php selected($_GET['post_type'] ?? '', 'mascotas_perdidas'); ?>>
                        <?php _e('Mascotas', 'humanitarios-cpt'); ?>
                    </option>
                    <option value="lost_objects" <?php selected($_GET['post_type'] ?? '', 'lost_objects'); ?>>
                        <?php _e('Objetos Perdidos', 'humanitarios-cpt'); ?>
                    </option>
                </select>
            </div>
            <div class="filtro-group">
                <label><?php _e('Palabras clave:', 'humanitarios-cpt'); ?></label>
                <input type="text" name="s" value="<?php echo esc_attr($_GET['s'] ?? ''); ?>">
            </div>

            <div class="filtro-group">
                <label><?php _e('Ubicación:', 'humanitarios-cpt'); ?></label>
                <input type="text" name="ubicacion" value="<?php echo esc_attr($_GET['ubicacion'] ?? ''); ?>">
            </div>
        </div>

        <div class="bloque-2">
            <div class="filtro-group fecha-group">
                <label><?php _e('Rango de fechas:', 'humanitarios-cpt'); ?></label>
                <input type="date" name="fecha_desde" value="<?php echo esc_attr($_GET['fecha_desde'] ?? ''); ?>">
                <span><?php _e('a', 'humanitarios-cpt'); ?></span>
                <input type="date" name="fecha_hasta" value="<?php echo esc_attr($_GET['fecha_hasta'] ?? ''); ?>">
            </div>

            <div class="filtro-group persona-fields" style="display: <?php echo ($_GET['post_type'] ?? '') === 'personas_perdidas' ? 'block' : 'none'; ?>">
                <div class="subbloque-1">
                    <div class="filtro-subgroup">
                        <label><?php _e('Edad aproximada:', 'humanitarios-cpt'); ?></label>
                        <input type="number" name="edad_min" placeholder="<?php _e('Mínima', 'humanitarios-cpt'); ?>" 
                            value="<?php echo esc_attr($_GET['edad_min'] ?? ''); ?>">
                        <input type="number" name="edad_max" placeholder="<?php _e('Máxima', 'humanitarios-cpt'); ?>"
                            value="<?php echo esc_attr($_GET['edad_max'] ?? ''); ?>">
                    </div>
                    
                    <div class="filtro-subgroup">
                        <label><?php _e('Género:', 'humanitarios-cpt'); ?></label>
                        <select name="genero">
                            <option value=""><?php _e('Todos', 'humanitarios-cpt'); ?></option>
                            <option value="Hombre" <?php selected($_GET['genero'] ?? '', 'Hombre'); ?>>
                                <?php _e('Hombre', 'humanitarios-cpt'); ?>
                            </option>
                            <option value="Mujer" <?php selected($_GET['genero'] ?? '', 'Mujer'); ?>>
                                <?php _e('Mujer', 'humanitarios-cpt'); ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="filtro-group mascota-fields" style="display: <?php echo ($_GET['post_type'] ?? '') === 'mascotas_perdidas' ? 'block' : 'none'; ?>">
                <div class="subbloque-2">
                    <div class="filtro-subgroup">
                        <label><?php _e('Tipo de animal:', 'humanitarios-cpt'); ?></label>
                        <select name="tipo_animal">
                            <option value=""><?php _e('Todos', 'humanitarios-cpt'); ?></option>
                            <option value="Perro" <?php selected($_GET['tipo_animal'] ?? '', 'Perro'); ?>>
                                <?php _e('Perro', 'humanitarios-cpt'); ?>
                            </option>
                            <option value="Gato" <?php selected($_GET['tipo_animal'] ?? '', 'Gato'); ?>>
                                <?php _e('Gato', 'humanitarios-cpt'); ?>
                            </option>
                            <option value="Otro" <?php selected($_GET['tipo_animal'] ?? '', 'Otro'); ?>>
                                <?php _e('Otro', 'humanitarios-cpt'); ?>
                            </option>
                        </select>
                    </div>

                    <div class="filtro-subgroup">
                        <label><?php _e('Tamaño:', 'humanitarios-cpt'); ?></label>
                        <select name="tamanio">
                            <option value=""><?php _e('Todos', 'humanitarios-cpt'); ?></option>
                            <option value="Pequeño" <?php selected($_GET['tamanio'] ?? '', 'Pequeño'); ?>>
                                <?php _e('Pequeño', 'humanitarios-cpt'); ?>
                            </option>
                            <option value="Mediano" <?php selected($_GET['tamanio'] ?? '', 'Mediano'); ?>>
                                <?php _e('Mediano', 'humanitarios-cpt'); ?>
                            </option>
                            <option value="Grande" <?php selected($_GET['tamanio'] ?? '', 'Grande'); ?>>
                                <?php _e('Grande', 'humanitarios-cpt'); ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="filtro-group objeto-fields" style="display: <?php echo ($_GET['post_type'] ?? '') === 'lost_objects' ? 'block' : 'none'; ?>">
                <div class="subbloque-3">
                    <div class="filtro-subgroup">
                        <label><?php _e('Estado:', 'humanitarios-cpt'); ?></label>
                        <select name="estado_objeto">
                            <option value=""><?php _e('Todos', 'humanitarios-cpt'); ?></option>
                            <option value="Nuevo" <?php selected($_GET['estado_objeto'] ?? '', 'Nuevo'); ?>>
                                <?php _e('Nuevo', 'humanitarios-cpt'); ?>
                            </option>
                            <option value="Usado" <?php selected($_GET['estado_objeto'] ?? '', 'Usado'); ?>>
                                <?php _e('Usado', 'humanitarios-cpt'); ?>
                            </option>
                            <option value="Dañado" <?php selected($_GET['estado_objeto'] ?? '', 'Dañado'); ?>>
                                <?php _e('Dañado', 'humanitarios-cpt'); ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit"><?php _e('Buscar', 'humanitarios-cpt'); ?></button>
        <button type="button" class="reset-filtros"><?php _e('Limpiar Filtros', 'humanitarios-cpt'); ?></button>
    </form>

    <div class="humanitarios-posts-grid"></div>
    <div class="load-more-container"></div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('humanitarios-filtro-form');
    const resultadosContainer = document.querySelector('.humanitarios-posts-grid');
    const loadMoreContainer = document.querySelector('.load-more-container');
    let currentPage = 1;
    let isLoading = false;

    const ajaxConfig = {
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        nonce: '<?php echo wp_create_nonce('humanitarios_filter_nonce'); ?>'
    };

    // Función para actualizar la visibilidad de los campos según el tipo de búsqueda
    function actualizarCampos() {
        const tipo = document.getElementById('filtro-post-type').value;
        document.querySelector('.persona-fields').style.display = 
            tipo === 'personas_perdidas' ? 'block' : 'none';
        document.querySelector('.mascota-fields').style.display = 
            tipo === 'mascotas_perdidas' ? 'block' : 'none';
        document.querySelector('.objeto-fields').style.display = 
            tipo === 'lost_objects' ? 'block' : 'none';
    }

    // Función para cargar resultados mediante AJAX
    function cargarResultados(page = 1, append = false) {
        if (isLoading) return;
        
        isLoading = true;
        currentPage = page;

        const formData = new FormData(form);
        formData.append('action', 'humanitarios_filter_posts');
        formData.append('security', ajaxConfig.nonce);
        formData.append('page', page);

        if (!append) {
            resultadosContainer.innerHTML = '<div class="loading"><?php _e("Buscando...", "humanitarios-cpt"); ?></div>';
        }

        fetch(ajaxConfig.url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.data);
            }

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.data;

            const newLoadMore = tempDiv.querySelector('.load-more');
            if (newLoadMore) {
                newLoadMore.addEventListener('click', function() {
                    newLoadMore.disabled = true;
                    cargarResultados(currentPage + 1, true);
                });
                newLoadMore.remove();
            }

            const postsHTML = tempDiv.innerHTML;

            if (append) {
                resultadosContainer.innerHTML += postsHTML;
            } else {
                resultadosContainer.innerHTML = postsHTML;
            }
            
            loadMoreContainer.innerHTML = '';
            if (newLoadMore) {
                loadMoreContainer.appendChild(newLoadMore);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultadosContainer.innerHTML = '<div class="error"><?php _e("Error al cargar resultados", "humanitarios-cpt"); ?></div>';
        })
        .finally(() => {
            isLoading = false;
        });
    }

    // Evento change para actualizar campos dinámicos
    document.getElementById('filtro-post-type').addEventListener('change', actualizarCampos);
    
    // Evento submit del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        cargarResultados(1);
    });

    // Evento click para resetear filtros
    document.querySelector('.reset-filtros').addEventListener('click', function() {
        form.reset();
        cargarResultados(1);
    });

    // Inicializar campos y cargar resultados al cargar la página
    actualizarCampos();
    cargarResultados(1);
});
</script>