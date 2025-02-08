<?php
// form-handler.php

/**
 *  Función para manejar la subida de imágenes
 */
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

/**
 *  Handler para formulario de personas
 */
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
                set_post_thumbnail($post_id, $attach_id); // Establecer imagen destacada
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
        do_action('humanitarios_send_new_submission_admin_email', $post_id); //para el admin
        do_action('humanitarios_send_post_pending_email', $post_id); // para el autor del reporte

        wp_send_json([
            'status' => 1,
            'message' => 'Reporte enviado para revisión'
        ]);

    } catch (Exception $e) {
        wp_send_json([
            'status' => 0,
            'message' => $e->getMessage()
        ]);
    }
}

/**
 *  Handler para formulario de mascotas (similar al de personas)
 */
add_action('wp_ajax_submit_pet_form', 'handle_pet_form_submission');
function handle_pet_form_submission() {
    try {
        // Verificar nonce
        if (!isset($_POST['humanitarios_nonce']) || !wp_verify_nonce($_POST['humanitarios_nonce'], 'submit_pet_form')) {
            throw new Exception('Nonce de seguridad inválido');
        }

        // Crear nuevo post
        $post_data = array(
            'post_title'   => sanitize_text_field($_POST['nombre_mascota'] ?? 'Mascota sin nombre'),
            'post_content' => '',
            'post_status'  => 'pending',
            'post_author'  => get_current_user_id(),
            'post_type'    => 'mascotas_perdidas'
        );

        $post_id = wp_insert_post($post_data);
        
        if (is_wp_error($post_id)) {
            throw new Exception('Error al crear el reporte');
        }

        // Manejar subida de imagen
        if (!empty($_FILES['foto_mascota'])) {
            $attach_id = humanitarios_handle_file_upload('foto_mascota', $post_id);
            if ($attach_id) {
                update_post_meta($post_id, 'foto_mascota', $attach_id);
                set_post_thumbnail($post_id, $attach_id); // Establecer imagen destacada
            }
        }

        // Mapear y sanitizar campos
        $meta_fields = [
            'tipo_animal'        => 'sanitize_text_field',
            'raza'               => 'sanitize_text_field',
            'color'              => 'sanitize_text_field',
            'tamanio'            => 'sanitize_text_field',
            'edad'               => 'sanitize_text_field',
            'sexo'               => 'sanitize_text_field',
            'identificacion'     => 'sanitize_text_field',
            'fecha_desaparicion' => 'sanitize_text_field',
            'ubicacion'          => 'sanitize_text_field',
            'hora_desaparicion'  => 'sanitize_text_field',
            'recompensa'         => 'sanitize_text_field',
            'telefono'           => 'sanitize_text_field',
            'correo'             => 'sanitize_email'
        ];

        foreach ($meta_fields as $key => $sanitizer) {
            if (isset($_POST[$key])) {
                $value = call_user_func($sanitizer, $_POST[$key]);
                update_post_meta($post_id, $key, $value);
            }
        }

        // Enviar notificaciones
        do_action('humanitarios_send_new_submission_admin_email', $post_id); // para el admin
        do_action('humanitarios_send_post_pending_email', $post_id); //para el autor del reporte

        wp_send_json([
            'status'  => 1,
            'message' => 'Reporte de mascota enviado para revisión'
        ]);

    } catch (Exception $e) {
        wp_send_json([
            'status'  => 0,
            'message' => $e->getMessage()
        ]);
    }
}

/**
 *  Edición de reportes de personas desaparecidas
 */
add_action('wp_ajax_edit_person_form', 'handle_edit_person_form');

function handle_edit_person_form() {
    try {
        if (!is_user_logged_in()) {
            throw new Exception('Debes iniciar sesión para realizar esta acción.');
        }

        global $current_user;
        $post_id = intval($_POST['post_id']);

        // Verificar nonce de seguridad
        if (!isset($_POST['humanitarios_nonce']) || !wp_verify_nonce($_POST['humanitarios_nonce'], 'edit_person_' . $post_id)) {
            throw new Exception('Nonce de seguridad inválido.');
        }

        // Obtener el post
        $post = get_post($post_id);
        if (!$post || $post->post_author != $current_user->ID) {
            throw new Exception('No tienes permisos para editar este reporte.');
        }

        // Actualizar el título del post con el nombre de la persona
        $nombre_completo = sanitize_text_field($_POST['nombre_completo'] ?? 'Anónimo');
        $post_data = [
            'ID'          => $post_id,
            'post_title'  => $nombre_completo,
            'post_status' => 'pending' // Volver a revisión tras edición
        ];
        wp_update_post($post_data);

        // Manejo de imagen de la persona desaparecida
        $image_key = 'foto_persona';

        // Eliminar imagen si el usuario lo solicitó
        if (isset($_POST['remove_foto_persona'])) {
            $old_attach_id = get_post_meta($post_id, $image_key, true);
            if ($old_attach_id) {
                wp_delete_attachment($old_attach_id, true);
                delete_post_meta($post_id, $image_key);
            }
        }

        // Subir nueva imagen si se proporciona
        if (!empty($_FILES[$image_key]['tmp_name'])) {
            $attach_id = humanitarios_handle_file_upload($image_key, $post_id);
            if ($attach_id) {
                update_post_meta($post_id, $image_key, $attach_id);
                set_post_thumbnail($post_id, $attach_id);
            }
        }

        // Lista de metadatos a actualizar
        $meta_fields = [
            'edad'               => 'absint',
            'nacionalidad'       => 'sanitize_text_field',
            'genero'             => 'sanitize_text_field',
            'color_piel'         => 'sanitize_text_field',
            'cabello'            => 'sanitize_text_field',
            'altura'             => 'sanitize_text_field',
            'fecha_desaparicion' => 'sanitize_text_field',
            'ubicacion'          => 'sanitize_text_field',
            'hora_desaparicion'  => 'sanitize_text_field',
            'vestimenta'         => 'sanitize_text_field',
            'enfermedades'       => 'sanitize_text_field',
            'telefono'           => 'sanitize_text_field',
            'correo'             => 'sanitize_email',
            'ubicacion_contacto' => 'sanitize_text_field',
            'calle'              => 'sanitize_text_field'
        ];
        
        // Antes de actualizar, obtén los valores actuales y captura los cambios:
        $updated_fields = [];
        foreach ($meta_fields as $key => $sanitizer) {
            if (isset($_POST[$key])) {
                $old_value = get_post_meta($post_id, $key, true);
                $new_value = call_user_func($sanitizer, $_POST[$key]);
                if ($old_value !== $new_value) {
                    $updated_fields[$key] = [
                        'old' => $old_value,
                        'new' => $new_value,
                    ];
                }
            }
        }

        // Guardar los metadatos
        foreach ($meta_fields as $key => $sanitizer) {
            if (isset($_POST[$key])) {
                update_post_meta($post_id, $key, call_user_func($sanitizer, $_POST[$key]));
            }
        }

        // Disparar el hook, pasando también los campos actualizados
        do_action('humanitarios_post_updated', $post_id, $updated_fields);

        // Responder con éxito y redirigir al reporte editado
        wp_send_json([
            'status'  => 1,
            'message' => 'Actualización de reporte enviada para revisión'
        ]);

    } catch (Exception $e) {
        wp_send_json([
            'status'  => 0,
            'message' => $e->getMessage()
        ]);
    }
}


/**
 *  Edición de reportes de mascotas desaparecidas
 */
add_action('wp_ajax_edit_pet_form', 'handle_edit_pet_form');

function handle_edit_pet_form() {
    try {
        if (!is_user_logged_in()) {
            throw new Exception('Debes iniciar sesión para realizar esta acción.');
        }

        global $current_user;
        $post_id = intval($_POST['post_id']);

        // Verificar nonce de seguridad
        if (!isset($_POST['humanitarios_nonce']) || !wp_verify_nonce($_POST['humanitarios_nonce'], 'edit_pet_' . $post_id)) {
            throw new Exception('Nonce de seguridad inválido.');
        }

        // Obtener el post
        $post = get_post($post_id);
        if (!$post || $post->post_author != $current_user->ID) {
            throw new Exception('No tienes permisos para editar este reporte.');
        }

        // Actualizar el título del post con el nombre de la mascota (o mantener el actual si está vacío)
        $nombre_mascota = sanitize_text_field($_POST['nombre_mascota'] ?? '');
        $post_data = [
            'ID'          => $post_id,
            'post_title'  => !empty($nombre_mascota) ? $nombre_mascota : $post->post_title,
            'post_status' => 'pending' // Volver a revisión tras edición
        ];
        wp_update_post($post_data);

        // Manejo de imagen de la mascota desaparecida
        $image_key = 'foto_mascota';

        // Eliminar imagen si el usuario lo solicitó
        if (isset($_POST['remove_foto_mascota'])) {
            $old_attach_id = get_post_meta($post_id, $image_key, true);
            if ($old_attach_id) {
                wp_delete_attachment($old_attach_id, true);
                delete_post_meta($post_id, $image_key);
            }
        }

        // Subir nueva imagen si se proporciona
        if (!empty($_FILES[$image_key]['tmp_name'])) {
            $attach_id = humanitarios_handle_file_upload($image_key, $post_id);
            if ($attach_id) {
                update_post_meta($post_id, $image_key, $attach_id);
                set_post_thumbnail($post_id, $attach_id);
            }
        }

        // Lista de metadatos a actualizar
        $meta_fields = [
            'tipo_animal'        => 'sanitize_text_field',
            'raza'               => 'sanitize_text_field',
            'color'              => 'sanitize_text_field',
            'tamanio'            => 'sanitize_text_field',
            'edad'               => 'sanitize_text_field',
            'sexo'               => 'sanitize_text_field',
            'identificacion'     => 'sanitize_text_field',
            'fecha_desaparicion' => 'sanitize_text_field',
            'ubicacion'          => 'sanitize_text_field',
            'hora_desaparicion'  => 'sanitize_text_field',
            'recompensa'         => 'sanitize_text_field',
            'telefono'           => 'sanitize_text_field',
            'correo'             => 'sanitize_email'
        ];

        // Antes de actualizar, obtén los valores actuales y captura los cambios:
        $updated_fields = [];
        foreach ($meta_fields as $key => $sanitizer) {
            if (isset($_POST[$key])) {
                $old_value = get_post_meta($post_id, $key, true);
                $new_value = call_user_func($sanitizer, $_POST[$key]);
                if ($old_value !== $new_value) {
                    $updated_fields[$key] = [
                        'old' => $old_value,
                        'new' => $new_value,
                    ];
                }
            }
        }

        // Guardar los metadatos
        foreach ($meta_fields as $key => $sanitizer) {
            if (isset($_POST[$key])) {
                update_post_meta($post_id, $key, call_user_func($sanitizer, $_POST[$key]));
            }
        }

        // Disparar el hook, pasando también los campos actualizados
        do_action('humanitarios_post_updated', $post_id, $updated_fields);

        // Responder con éxito y redirigir al reporte editado
        wp_send_json([
            'status'  => 1,
            'message' => 'Actualización de reporte enviada para revisión'
        ]);

    } catch (Exception $e) {
        wp_send_json([
            'status'  => 0,
            'message' => $e->getMessage()
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
