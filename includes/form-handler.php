<?php
// form-handler.php

// Función para manejar la subida de imágenes
function humanitarios_handle_file_upload($file_key, $post_id) {
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    
    $uploadedfile = $_FILES[$file_key];
    $upload_overrides = array('test_form' => false);
    
    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
    
    if ($movefile && !isset($movefile['error'])) {
        $filetype = wp_check_filetype(basename($movefile['file']), null);
        
        $attachment = array(
            'post_mime_type' => $filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($movefile['file'])),
            'post_content' => '',
            'post_status' => 'inherit',
            'post_parent' => $post_id
        );
        
        $attach_id = wp_insert_attachment($attachment, $movefile['file'], $post_id);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $movefile['file']);
        wp_update_attachment_metadata($attach_id, $attach_data);
        
        return $attach_id;
    }
    
    return false;
}

// Handler para formulario de personas
add_action('wp_ajax_submit_person_form', 'handle_person_form_submission');
function handle_person_form_submission() {
    try {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['humanitarios_nonce'], 'submit_person_form')) {
            throw new Exception('Nonce de seguridad inválido');
        }

        // Crear nuevo post
        $post_data = array(
            'post_title'   => sanitize_text_field($_POST['nombre_completo'] ?? 'Anónimo'),
            'post_content' => '',
            'post_status'  => 'pending',
            'post_author'  => get_current_user_id(),
            'post_type'    => 'personas_perdidas'
        );

        $post_id = wp_insert_post($post_data);
        
        if (is_wp_error($post_id)) {
            throw new Exception('Error al crear el reporte');
        }

        // Manejar subida de imagen
        if (!empty($_FILES['foto_persona'])) {
            $attach_id = humanitarios_handle_file_upload('foto_persona', $post_id);
            if ($attach_id) {
                update_post_meta($post_id, 'foto_persona', $attach_id);
            }
        }

        // Mapear y sanitizar campos
        $meta_fields = [
            'edad' => 'absint',
            'nacionalidad' => 'sanitize_text_field',
            'genero' => 'sanitize_text_field',
            'color_piel' => 'sanitize_text_field',
            'cabello' => 'sanitize_text_field',
            'altura' => 'sanitize_text_field',
            'fecha_desaparicion' => 'sanitize_text_field',
            'ubicacion' => 'sanitize_text_field',
            'hora_desaparicion' => 'sanitize_text_field',
            'vestimenta' => 'sanitize_text_field',
            'enfermedades' => 'sanitize_text_field',
            'telefono' => 'sanitize_text_field',
            'correo' => 'sanitize_email',
            'ubicacion_contacto' => 'sanitize_text_field',
            'calle' => 'sanitize_text_field'
        ];

        foreach ($meta_fields as $key => $sanitizer) {
            if (isset($_POST[$key])) {
                $value = call_user_func($sanitizer, $_POST[$key]);
                update_post_meta($post_id, $key, $value);
            }
        }

        // Enviar notificaciones
        do_action('humanitarios_new_submission', $post_id);

        wp_send_json_success([
            'message' => 'Reporte enviado para revisión',
            'redirect' => get_permalink($post_id)
        ]);

    } catch (Exception $e) {
        wp_send_json_error($e->getMessage());
    }
}

// Handler para formulario de mascotas (similar al de personas)
add_action('wp_ajax_submit_pet_form', 'handle_pet_form_submission');
function handle_pet_form_submission() {
    // ... (estructura similar al de personas con campos específicos de mascotas)
}

// Handler para edición de posts
add_action('wp_ajax_edit_post_form', 'handle_edit_post_form');
function handle_edit_post_form() {
    try {
        global $current_user;
        
        // Verificar nonce
        if (!wp_verify_nonce($_POST['humanitarios_nonce'], 'edit_post_' . $_POST['post_id'])) {
            throw new Exception('Nonce de seguridad inválido');
        }

        $post_id = intval($_POST['post_id']);
        $post_type = sanitize_text_field($_POST['post_type']);
        
        // Verificar autoría
        if (get_post_field('post_author', $post_id) != $current_user->ID) {
            throw new Exception('No tienes permisos para editar este reporte');
        }

        // Actualizar datos básicos del post
        $post_data = [
            'ID' => $post_id,
            'post_title' => $post_type === 'personas_perdidas' 
                ? sanitize_text_field($_POST['nombre_completo'] ?? 'Anónimo')
                : sanitize_text_field($_POST['nombre_mascota'] ?? 'Mascota sin nombre'),
            'post_status' => 'pending' // Volver a revisión tras edición
        ];
        
        wp_update_post($post_data);

        // Manejar imágenes
        $image_key = ($post_type === 'personas_perdidas') ? 'foto_persona' : 'foto_mascota';
        
        // Eliminar imagen si se seleccionó
        if (isset($_POST["remove_$image_key"])) {
            $old_attach_id = get_post_meta($post_id, $image_key, true);
            if ($old_attach_id) {
                wp_delete_attachment($old_attach_id, true);
                delete_post_meta($post_id, $image_key);
            }
        }

        // Subir nueva imagen
        if (!empty($_FILES[$image_key]['tmp_name'])) {
            $attach_id = humanitarios_handle_file_upload($image_key, $post_id);
            if ($attach_id) {
                update_post_meta($post_id, $image_key, $attach_id);
            }
        }

        // Actualizar metadatos
        $meta_fields = [
            // Campos comunes
            'fecha_desaparicion' => 'sanitize_text_field',
            'ubicacion' => 'sanitize_text_field',
            'telefono' => 'sanitize_text_field',
            
            // Campos específicos
            ($post_type === 'personas_perdidas') ? [
                'edad' => 'absint',
                'genero' => 'sanitize_text_field',
                'color_piel' => 'sanitize_text_field',
                // ... otros campos de persona
            ] : [
                'tipo_animal' => 'sanitize_text_field',
                'raza' => 'sanitize_text_field',
                'tamanio' => 'sanitize_text_field',
                // ... otros campos de mascota
            ]
        ];

        foreach ($meta_fields as $key => $sanitizer) {
            if (isset($_POST[$key])) {
                $value = call_user_func($sanitizer, $_POST[$key]);
                update_post_meta($post_id, $key, $value);
            }
        }

        // Notificar actualización
        do_action('humanitarios_post_updated', $post_id);

        wp_send_json_success([
            'message' => 'Reporte actualizado correctamente',
            'redirect' => get_permalink($post_id)
        ]);

    } catch (Exception $e) {
        wp_send_json_error($e->getMessage());
    }
}

// Helpers adicionales
function humanitarios_sanitize_array($data, $sanitizer = 'sanitize_text_field') {
    return array_map($sanitizer, (array)$data);
}

function humanitarios_validate_required($fields, $required_fields) {
    foreach ($required_fields as $field) {
        if (empty($fields[$field])) {
            throw new Exception("El campo $field es requerido");
        }
    }
}
