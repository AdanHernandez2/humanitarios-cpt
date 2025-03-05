<?php
// form-handler.php

function humanitarios_handle_file_upload($files, $post_id) {
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $attachments = [];
    
    foreach ($files['name'] as $key => $value) {
        if (!empty($files['name'][$key])) {
            $file = [
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error'    => $files['error'][$key],
                'size'     => $files['size'][$key]
            ];

            $_FILES = ["upload_file_{$key}" => $file];
            $attachment_id = media_handle_upload("upload_file_{$key}", $post_id);

            if (!is_wp_error($attachment_id)) {
                $attachments[] = $attachment_id;
            } else {
                error_log('Error subiendo archivo: ' . $attachment_id->get_error_message());
            }
        }
    }
    
    return $attachments;
}
/**
 * Handler unificado para creación de reportes
 */
function humanitarios_handle_submission($post_type, $nonce_action, $title_field) {
    try {
        // Verificación básica
        if (!wp_verify_nonce($_POST['humanitarios_nonce'], $nonce_action)) {
            throw new Exception(esc_html__('Nonce inválido', 'humanitarios'));
        }

        // Crear post
        $post_title = isset($_POST[$title_field]) 
            ? sanitize_text_field($_POST[$title_field]) 
            : ($post_type === 'personas_perdidas' 
                ? __('Anónimo', 'humanitarios') 
                : __('Mascota sin nombre', 'humanitarios')
            );

        $post_data = [
            'post_title'   => $post_title,
            'post_content' => '',
            'post_status'  => 'pending',
            'post_author'  => get_current_user_id(),
            'post_type'    => $post_type
        ];

        $post_id = wp_insert_post($post_data);
        if (is_wp_error($post_id)) {
            throw new Exception(esc_html__('Error al crear el reporte', 'humanitarios'));
        }

        // Determinar campo de archivo según post type
        $file_field = '';
        switch ($post_type) {
            case 'personas_perdidas':
                $file_field = 'foto_persona';
                break;
            case 'mascotas_perdidas':
                $file_field = 'foto_mascota';
                break;
            case 'lost_objects':
                $file_field = 'foto_objeto';
                break;
            case 'found-form':
                $file_field = 'foto_encontrado';
                break;
        }

        // Manejar imágenes usando el campo específico
        if (!empty($_FILES[$file_field]['name'][0])) {
            $attachments = humanitarios_handle_file_upload($_FILES[$file_field], $post_id);
            if (!empty($attachments)) {
                update_post_meta($post_id, 'humanitarios_galeria', $attachments);
                set_post_thumbnail($post_id, $attachments[0]);
                
                // Debugging
                error_log('Imágenes subidas para ' . $post_type . ': ' . print_r($attachments, true));
            }
        }

        // Tratamiento especial para found-form
        if ($post_type === 'found-form') {
            // ... (mantener lógica existente para found-form)
        }

        // Campos comunes usando helper
        $meta_fields = humanitarios_get_custom_fields($post_type);
        foreach ($meta_fields as $key => $config) {
            // Excluir campos específicos ya procesados y campos de archivo
            if ($key === $file_field || ($post_type === 'found-form' && in_array($key, ['nombre_persona', 'tipo_mascota', 'tipo_objeto']))) {
                continue;
            }

            if (isset($_POST[$key])) {
                $sanitizer = $config['sanitize'] ?? 'sanitize_text_field';
                $value = call_user_func($sanitizer, $_POST[$key]);
                update_post_meta($post_id, $key, $value);
            }
        }

        // Notificaciones
        do_action('humanitarios_send_new_submission_admin_email', $post_id);
        do_action('humanitarios_send_post_pending_email', $post_id);

        return [
            'status'  => 1,
            'message' => esc_html__('Reporte enviado para revisión', 'humanitarios'),
            'post_id' => $post_id
        ];

    } catch (Exception $e) {
        error_log('Error en humanitarios_handle_submission: ' . $e->getMessage());
        return [
            'status'  => 0,
            'message' => $e->getMessage()
        ];
    }
}

/**
 * Handlers específicos para cada tipo
 */
add_action('wp_ajax_submit_person_form', function() {
    $response = humanitarios_handle_submission(
        'personas_perdidas',
        'submit_person_form',
        'nombre_completo'
    );
    wp_send_json($response);
});

add_action('wp_ajax_submit_pet_form', function() {
    $response = humanitarios_handle_submission(
        'mascotas_perdidas',
        'submit_pet_form',
        'nombre_mascota'
    );
    wp_send_json($response);
});

// Handler para objetos perdidos
add_action('wp_ajax_submit_lost_object_form', function() {
    $response = humanitarios_handle_submission(
        'lost_objects',
        'submit_lost_object_form',
        'nombre_objeto'
    );
    wp_send_json($response);
});

// Handler para objetos/personas/mascotas encontradas
add_action('wp_ajax_submit_found_form', function() {
    $response = humanitarios_handle_submission(
        'found-form',
        'submit_found_form',
        'descripcion_encontrado'
    );
    wp_send_json($response);
});

/**
 * Handler para edición de reportes (genérico)
 */
add_action('wp_ajax_edit_report', 'handle_edit_report');
function handle_edit_report() {
    try {
        // Validación básica
        if (!isset($_POST['post_id'])) {
            throw new Exception('ID del post no proporcionado');
        }

        $post_id = intval($_POST['post_id']);
        $post = get_post($post_id);

        if (!$post) {
            throw new Exception('El post no existe');
        }

        if (!is_user_logged_in()) {
            throw new Exception('Debes iniciar sesión para editar este reporte');
        }

        if ($post->post_author != get_current_user_id()) {
            throw new Exception('No tienes permisos para editar este reporte');
        }

        // Determinar el tipo de post y el nonce correspondiente
        $post_type = $post->post_type;
        if (!in_array($post_type, ['personas_perdidas', 'mascotas_perdidas', 'lost_objects', 'found-form'])) {
            throw new Exception('Tipo de post no válido');
        }

        $nonce_action = 'edit_' . $post_type . '_' . $post_id;

        if (!wp_verify_nonce($_POST['humanitarios_nonce'], $nonce_action)) {
            throw new Exception('Token de seguridad inválido. Recarga la página e intenta de nuevo.');
        }

        // Actualizar el título del post
        $title_field = '';
        if ($post_type === 'personas_perdidas') {
            $title_field = 'nombre_completo';
        } elseif ($post_type === 'mascotas_perdidas') {
            $title_field = 'nombre_mascota';
        } elseif ($post_type === 'lost_objects') {
            $title_field = 'nombre_objeto';
        } elseif ($post_type === 'found-form') {
            $title_field = 'descripcion_encontrado';
        }

        if (!isset($_POST[$title_field])) {
            throw new Exception('El campo de título es requerido');
        }

        wp_update_post([
            'ID' => $post_id,
            'post_title' => sanitize_text_field($_POST[$title_field]),
            'post_status' => 'pending'
        ]);

        // Manejo de la imagen destacada y la galería
        $featured_image_id = get_post_thumbnail_id($post_id);
        $gallery_images = get_post_meta($post_id, 'humanitarios_galeria', true) ?: [];

        // Eliminar imágenes seleccionadas
        if (!empty($_POST['remove_images'])) {
            foreach ($_POST['remove_images'] as $image_id) {
                $image_id = intval($image_id);

                // Si la imagen es la destacada, eliminarla como destacada
                if ($image_id == $featured_image_id) {
                    delete_post_thumbnail($post_id);
                    $featured_image_id = null;
                }

                // Eliminar la imagen de la galería si está presente
                if (($key = array_search($image_id, $gallery_images)) !== false) {
                    unset($gallery_images[$key]);
                }

                // Eliminar la imagen de la biblioteca de medios
                wp_delete_attachment($image_id, true);
            }

            // Actualizar la galería en la base de datos
            update_post_meta($post_id, 'humanitarios_galeria', array_values($gallery_images));
        }

        // Subir nuevas imágenes a la galería
        $file_field = '';
        if ($post_type === 'personas_perdidas') {
            $file_field = 'foto_persona';
        } elseif ($post_type === 'mascotas_perdidas') {
            $file_field = 'foto_mascota';
        } elseif ($post_type === 'lost_objects') {
            $file_field = 'foto_objeto';
        } elseif ($post_type === 'found-form') {
            $file_field = 'foto_encontrado';
        }

        if (!empty($_FILES[$file_field]['name'][0])) {
            $new_images = humanitarios_handle_file_upload($_FILES[$file_field], $post_id);
            $gallery_images = array_merge($gallery_images, $new_images);
            update_post_meta($post_id, 'humanitarios_galeria', $gallery_images);
        }

        // Subir una nueva imagen destacada si se proporciona
        if (!empty($_FILES['featured_image']['name'])) {
            $new_featured_image_id = humanitarios_handle_file_upload($_FILES['featured_image'], $post_id);
            if (!is_wp_error($new_featured_image_id)) {
                set_post_thumbnail($post_id, $new_featured_image_id[0]);
            }
        }

        // Actualizar campos personalizados
        $meta_fields = humanitarios_get_custom_fields($post_type);
        foreach ($meta_fields as $key => $config) {
            if (isset($_POST[$key])) {
                $sanitizer = $config['sanitize'] ?? 'sanitize_text_field';
                $value = call_user_func($sanitizer, $_POST[$key]);
                update_post_meta($post_id, $key, $value);
            }
        }

        // Respuesta exitosa
        wp_send_json([
            'status' => 1,
            'message' => 'Reporte actualizado correctamente',
            'redirect' => get_permalink($post_id)
        ]);

    } catch (Exception $e) {
        // Respuesta de error con mensaje descriptivo
        wp_send_json([
            'status' => 0,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

/**
 *  Maneja el envío del formulario de registro de usuarios.
 */
function humanitarios_handle_registration_form() {
    // Comprobar si se envió el formulario de registro
    if ( isset( $_POST['humanitarios_register'] ) ) {

        $errors = array();

        // Verificar el nonce de seguridad
        if ( ! isset( $_POST['registration_nonce'] ) || ! wp_verify_nonce( $_POST['registration_nonce'], 'humanitarios_registration_nonce' ) ) {
            $errors[] = __( 'La solicitud no es válida. Por favor, inténtalo de nuevo.', 'workreap' );
        }

        // Sanitizar y validar los campos recibidos
        $first_name   = isset( $_POST['user_registration']['first_name'] ) ? sanitize_text_field( $_POST['user_registration']['first_name'] ) : '';
        $last_name    = isset( $_POST['user_registration']['last_name'] ) ? sanitize_text_field( $_POST['user_registration']['last_name'] ) : '';
        $email        = isset( $_POST['user_registration']['email'] ) ? sanitize_email( $_POST['user_registration']['email'] ) : '';
        $password     = isset( $_POST['user_registration']['password'] ) ? $_POST['user_registration']['password'] : ''; // La contraseña se trata sin sanitizar para no afectar caracteres especiales
        $tipo_usuario = isset( $_POST['user_registration']['tipo_usuario'] ) ? sanitize_text_field( $_POST['user_registration']['tipo_usuario'] ) : '';
        $accept_terms = isset( $_POST['user_registration']['accept_terms'] ) ? $_POST['user_registration']['accept_terms'] : '';

        if ( empty( $first_name ) ) {
            $errors[] = __( 'El nombre es obligatorio.', 'workreap' );
        }
        if ( empty( $last_name ) ) {
            $errors[] = __( 'El apellido es obligatorio.', 'workreap' );
        }
        if ( empty( $email ) || ! is_email( $email ) ) {
            $errors[] = __( 'El correo es inválido.', 'workreap' );
        } elseif ( email_exists( $email ) ) {
            $errors[] = __( 'El correo ya está registrado.', 'workreap' );
        }
        if ( empty( $password ) || strlen( $password ) < 6 ) {
            $errors[] = __( 'La contraseña debe tener al menos 6 caracteres.', 'workreap' );
        }
        if ( empty( $tipo_usuario ) ) {
            $errors[] = __( 'Debes seleccionar el tipo de usuario.', 'workreap' );
        }
        if ( empty( $accept_terms ) ) {
            $errors[] = __( 'Debes aceptar los términos y condiciones.', 'workreap' );
        }

        // Si existen errores, se guardan en un transient y se redirige a la misma página
        if ( ! empty( $errors ) ) {
            set_transient( 'registration_errors', $errors, 30 );
            wp_redirect( wp_get_referer() );
            exit;
        }

        // Configurar los datos para crear el usuario.
        // Se usará el correo como user_login y el rol será siempre "subscriber"
        $userdata = array(
            'user_login' => $email,
            'user_email' => $email,
            'user_pass'  => $password,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'role'       => 'subscriber', // Rol por defecto
        );

        // Crear el usuario
        $user_id = wp_insert_user( $userdata );

        if ( is_wp_error( $user_id ) ) {
            // Si ocurre algún error al crear el usuario, se guarda el mensaje de error
            $errors[] = $user_id->get_error_message();
            set_transient( 'registration_errors', $errors, 30 );
            wp_redirect( wp_get_referer() );
            exit;
        } else {
            // Guardar meta adicional:
            // - Guardar el valor del formulario en "tipo_usuario"
            // - Registrar el valor "employers" en "_user_type" por defecto
            update_user_meta( $user_id, 'tipo_usuario', $tipo_usuario );
            update_user_meta( $user_id, '_user_type', 'employers' );

            // Iniciar sesión automáticamente al usuario
            wp_set_current_user( $user_id );
            wp_set_auth_cookie( $user_id );

            // Redirigir al usuario al dashboard
            wp_redirect( home_url( '/user-dashboard/' ) );
            exit;
        }
    }
}
add_action( 'init', 'humanitarios_handle_registration_form' );

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
