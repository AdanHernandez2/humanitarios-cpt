<?php
/**
 * Template: Formulario de filtros con AJAX para Encontrados
 */
?>
<div class="humanitarios-filtro-container">
    <form id="humanitarios-filtro-form" method="GET">
        <div class="encontrados-filtro-form">
                <div class="filtro-group">
                    <label><?php _e('Tipo de Encontrado:', 'humanitarios-cpt'); ?></label>
                    <select name="tipo_encontrado" id="filtro-tipo-encontrado">
                        <option value=""><?php _e('Todos', 'humanitarios-cpt'); ?></option>
                        <option value="persona"><?php _e('Persona', 'humanitarios-cpt'); ?></option>
                        <option value="mascota"><?php _e('Mascota', 'humanitarios-cpt'); ?></option>
                        <option value="objeto"><?php _e('Objeto', 'humanitarios-cpt'); ?></option>
                    </select>
                </div>

                <div class="filtro-group">
                    <label><?php _e('Ubicación:', 'humanitarios-cpt'); ?></label>
                    <input type="text" name="ubicacion" placeholder="<?php _e('Ej: Santo Domingo', 'humanitarios-cpt'); ?>">
                </div>

                <div class="filtro-group">
                    <label><?php _e('Fecha encontrado:', 'humanitarios-cpt'); ?></label>
                    <input type="date" name="fecha_encontrado">
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

    // Verificar que los elementos existan
    if (!form || !resultadosContainer || !loadMoreContainer) {
        console.error('Error: No se encontraron los elementos del DOM necesarios.');
        return;
    }

    let currentPage = 1;
    let isLoading = false;

    const ajaxConfig = {
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        nonce: '<?php echo wp_create_nonce('humanitarios_filter_encontrados_nonce'); ?>'
    };

    function cargarResultados(page = 1, append = false) {
        if (isLoading) return;
        isLoading = true;

        const formData = new FormData(form);
        formData.append('action', 'humanitarios_filter_encontrados');
        formData.append('security', ajaxConfig.nonce);
        formData.append('page', page);

        if (!append) {
            resultadosContainer.innerHTML = '<div class="loading-spinner"></div>';
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

            const parser = new DOMParser();
            const doc = parser.parseFromString(data.data, 'text/html');
            const postsHTML = doc.body.innerHTML;

            if (append) {
                resultadosContainer.insertAdjacentHTML('beforeend', postsHTML);
            } else {
                resultadosContainer.innerHTML = postsHTML;
            }

            // Manejar paginación
            const newLoadMore = doc.querySelector('.load-more-container');
            if (newLoadMore) {
                loadMoreContainer.innerHTML = newLoadMore.innerHTML;
            }
        })
        .catch(error => {
            resultadosContainer.innerHTML = `<div class="error">${error.message}</div>`;
        })
        .finally(() => {
            isLoading = false;
        });
    }

    form.addEventListener('submit', e => {
        e.preventDefault();
        cargarResultados(1);
    });

    form.addEventListener('input', () => {
        cargarResultados(1);
    });

    document.querySelector('.reset-filtros')?.addEventListener('click', () => {
        form.reset();
        cargarResultados(1);
    });

    cargarResultados(1);
});
</script>