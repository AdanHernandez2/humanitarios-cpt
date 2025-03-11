<?php
defined('ABSPATH') || exit;

/**
 * Registra los metaboxes para todos los CPTs
 */
function humanitarios_add_meta_boxes() {
    $cpts = ['personas_perdidas', 'mascotas_perdidas', 'lost_objects', 'found-form'];
    
    // Metabox de Información Principal
    add_meta_box(
        'humanitarios_info_meta_box',
        'Información Detallada',
        'humanitarios_info_meta_box_callback',
        $cpts,
        'normal',
        'high'
    );

    // Metabox de Galería
    add_meta_box(
        'humanitarios_galeria_meta_box',
        'Galería de Imágenes',
        'humanitarios_galeria_meta_box_callback',
        $cpts,
        'normal',
        'high'
    );

    // Metabox de Estado
    add_meta_box(
        'humanitarios_status_meta_box',
        'Estado del Reporte',
        'humanitarios_status_meta_box_callback',
        $cpts,
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'humanitarios_add_meta_boxes');

/**
 * Callback para el metabox de información
 */
function humanitarios_info_meta_box_callback($post) {
    wp_nonce_field('humanitarios_save_meta_box', 'humanitarios_meta_box_nonce');
    $fields = humanitarios_get_custom_fields($post->post_type);
    
    echo '<div class="humanitarios-metabox-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">';
    
    foreach ($fields as $key => $config) {
        // Saltar campos de tipo file que se manejan en la galería
        if ($config['type'] === 'file') continue;
        
        $current_value = get_post_meta($post->ID, $key, true);
        echo '<div class="humanitarios-metabox-field">';
        
        // Etiqueta
        echo '<label for="'. esc_attr($key) .'" style="display: block; margin-bottom: 5px; font-weight: 600;">'. esc_html($config['label'] ?? $key) .'</label>';
        
        // Campos según tipo
        switch ($config['type'] ?? 'text') {
            case 'select':
                echo '<select name="'. esc_attr($key) .'" id="'. esc_attr($key) .'" style="width: 100%; padding: 5px;">';
                foreach ($config['options'] as $value => $label) {
                    echo '<option value="'. esc_attr($value) .'" '. selected($current_value, $value, false) .'>'. esc_html($label) .'</option>';
                }
                echo '</select>';
                break;
                
            case 'textarea':
                echo '<textarea name="'. esc_attr($key) .'" id="'. esc_attr($key) .'" style="width: 100%; height: 100px;">'. esc_textarea($current_value) .'</textarea>';
                break;
                
            case 'date':
                echo '<input type="date" name="'. esc_attr($key) .'" value="'. esc_attr($current_value) .'" style="width: 100%;">';
                break;
                
            case 'number':
                echo '<input type="number" name="'. esc_attr($key) .'" value="'. esc_attr($current_value) .'" 
                      min="'. ($config['min'] ?? '') .'" max="'. ($config['max'] ?? '') .'" style="width: 100%;">';
                break;
                
            default:
                echo '<input type="text" name="'. esc_attr($key) .'" id="'. esc_attr($key) .'" 
                       value="'. esc_attr($current_value) .'" style="width: 100%;">';
        }
        
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Callback para el metabox de galería
 */
function humanitarios_galeria_meta_box_callback($post) {
    wp_nonce_field('humanitarios_save_gallery', 'humanitarios_gallery_nonce');
    $gallery = get_post_meta($post->ID, 'humanitarios_galeria', true);
    ?>
    <div class="humanitarios-gallery-wrapper">
        <ul class="humanitarios-gallery-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; list-style: none; padding: 0;">
            <?php if ($gallery) : foreach ($gallery as $image_id) : ?>
                <li style="position: relative;">
                    <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                    <input type="hidden" name="humanitarios_galeria[]" value="<?php echo absint($image_id); ?>">
                    <button type="button" class=" humanitarios-remove-image" >×</button>
                </li>
            <?php endforeach; endif; ?>
        </ul>
        <button type="button" class="button humanitarios-add-images" style="margin-top: 15px;">Agregar Imágenes</button>
    </div>
    <?php
}

/**
 * Callback para el metabox de estado
 */
function humanitarios_status_meta_box_callback($post) {
    $current_status = $post->post_status;
    $statuses = [
        'publish' => 'Publicado',
        'pending' => 'En Revisión',
        'draft'   => 'Borrador'
    ];
    
    echo '<select name="post_status" id="post_status" class="widefat">';
    foreach ($statuses as $value => $label) {
        printf(
            '<option value="%s"%s>%s</option>',
            esc_attr($value),
            selected($current_status, $value, false),
            esc_html($label)
        );
    }
    echo '</select>';
}

/**
 * Guarda los datos de los metaboxes
 */
function humanitarios_save_meta_box($post_id) {
    // Verificar nonce primero
    if (!isset($_POST['humanitarios_meta_box_nonce'])) {
        return;
    }
    
    // Validar nonce
    if (!wp_verify_nonce($_POST['humanitarios_meta_box_nonce'], 'humanitarios_save_meta_box')) {
        return;
    }
    
    // Evitar guardado durante autoguardado
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Verificar permisos de usuario
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Guardar campos personalizados
    $fields = humanitarios_get_custom_fields(get_post_type($post_id));
    foreach ($fields as $key => $config) {
        if (isset($_POST[$key])) {
            // Sanitizar según configuración del campo
            $sanitized_value = call_user_func(
                $config['sanitize'] ?? 'sanitize_text_field',
                $_POST[$key]
            );
            update_post_meta($post_id, $key, $sanitized_value);
        }
    }

    // Guardar galería de imágenes
    if (isset($_POST['humanitarios_galeria'])) {
        $gallery = array_map('absint', $_POST['humanitarios_galeria']);
        update_post_meta($post_id, 'humanitarios_galeria', $gallery);
    }
}
add_action('save_post', 'humanitarios_save_meta_box');

