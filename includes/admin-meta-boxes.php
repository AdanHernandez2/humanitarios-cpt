<?php
defined('ABSPATH') || exit;

/**
 * Registra los metaboxes
 */
function humanitarios_add_meta_boxes() {
    add_meta_box(
        'humanitarios_info_meta_box',
        'Información Detallada',
        'humanitarios_info_meta_box_callback',
        ['personas_perdidas', 'mascotas_perdidas'],
        'normal',
        'high'
    );

    add_meta_box(
        'humanitarios_galeria_meta_box',
        'Galería de Imágenes',
        'humanitarios_galeria_meta_box_callback',
        ['personas_perdidas', 'mascotas_perdidas'],
        'normal',
        'high'
    );

    add_meta_box(
        'humanitarios_status_meta_box',
        'Estado del Reporte',
        'humanitarios_status_meta_box_callback',
        ['personas_perdidas', 'mascotas_perdidas'],
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
    
    // Obtener los campos personalizados desde el helper
    $fields = humanitarios_get_custom_fields($post->post_type);
    
    echo '<div class="humanitarios-metabox-grid">';
    foreach ($fields as $key => $config) {
        $current_value = get_post_meta($post->ID, $key, true);
        echo '<div class="humanitarios-metabox-field">';
        echo '<label for="'. esc_attr($key) .'">'. esc_html($config['label']) .'</label>';
        
        $attributes = '';
        if (!empty($config['required'])) {
            $attributes .= ' required';
        }
        if (!empty($config['placeholder'])) {
            $attributes .= ' placeholder="'. esc_attr($config['placeholder']) .'"';
        }

        switch ($config['type']) {
            case 'textarea':
                echo '<textarea id="'. esc_attr($key) .'" name="'. esc_attr($key) .'" 
                      class="widefat"'. $attributes .'>'. esc_textarea($current_value) .'</textarea>';
                break;
                
            case 'select':
                echo '<select id="'. esc_attr($key) .'" name="'. esc_attr($key) .'" 
                       class="widefat"'. $attributes .'>';
                if (!empty($config['placeholder'])) {
                    echo '<option value="">'. esc_html($config['placeholder']) .'</option>';
                }
                foreach ($config['options'] as $value => $label) {
                    echo '<option value="'. esc_attr($value) .'" '. selected($current_value, $value, false) .'>'. esc_html($label) .'</option>';
                }
                echo '</select>';
                break;
                
            case 'date':
                echo '<input type="date" id="'. esc_attr($key) .'" name="'. esc_attr($key) .'" 
                       value="'. esc_attr($current_value) .'" class="widefat"'. $attributes .'>';
                break;
                
            case 'time':
                echo '<input type="time" id="'. esc_attr($key) .'" name="'. esc_attr($key) .'" 
                       value="'. esc_attr($current_value) .'" class="widefat"'. $attributes .'>';
                break;
                
            case 'email':
                echo '<input type="email" id="'. esc_attr($key) .'" name="'. esc_attr($key) .'" 
                       value="'. esc_attr($current_value) .'" class="widefat"'. $attributes .'>';
                break;
                
            case 'number':
                echo '<input type="number" id="'. esc_attr($key) .'" name="'. esc_attr($key) .'" 
                       value="'. esc_attr($current_value) .'" class="small-text"
                       min="'. ($config['min'] ?? '') .'" max="'. ($config['max'] ?? '') .'"'. $attributes .'>';
                break;
                
            case 'tel':
                echo '<input type="tel" id="'. esc_attr($key) .'" name="'. esc_attr($key) .'" 
                       value="'. esc_attr($current_value) .'" class="widefat"'. $attributes .'>';
                break;
                
            default:
                echo '<input type="text" id="'. esc_attr($key) .'" name="'. esc_attr($key) .'" 
                       value="'. esc_attr($current_value) .'" class="widefat"'. $attributes .'>';
        }
        
        if (!empty($config['description'])) {
            echo '<p class="description">'. esc_html($config['description']) .'</p>';
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
        <ul class="humanitarios-gallery-grid">
            <?php if ($gallery) : foreach ($gallery as $image_id) : 
                $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                ?>
                <li class="humanitarios-gallery-item">
                    <img src="<?php echo esc_url($image_url); ?>" alt="">
                    <input type="hidden" name="humanitarios_galeria[]" value="<?php echo absint($image_id); ?>">
                    <button type="button" class="humanitarios-remove-image">&times;</button>
                </li>
            <?php endforeach; endif; ?>
        </ul>
        <button type="button" class="button humanitarios-add-images">Agregar Imágenes</button>
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
    // Verificar nonce principal
    if (!isset($_POST['humanitarios_meta_box_nonce']) || 
        !wp_verify_nonce($_POST['humanitarios_meta_box_nonce'], 'humanitarios_save_meta_box')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Guardar campos principales
    $fields = humanitarios_get_custom_fields(get_post_type($post_id));
    foreach ($fields as $key => $config) {
        if (isset($_POST[$key])) {
            $sanitized_value = call_user_func(
                $config['sanitize'] ?? 'sanitize_text_field', 
                $_POST[$key]
            );
            update_post_meta($post_id, $key, $sanitized_value);
        }
    }

    // Guardar galería
    if (isset($_POST['humanitarios_galeria']) && wp_verify_nonce($_POST['humanitarios_gallery_nonce'], 'humanitarios_save_gallery')) {
        $gallery = array_map('absint', $_POST['humanitarios_galeria']);
        update_post_meta($post_id, 'humanitarios_galeria', $gallery);
    } else {
        delete_post_meta($post_id, 'humanitarios_galeria');
    }
}
add_action('save_post', 'humanitarios_save_meta_box');

