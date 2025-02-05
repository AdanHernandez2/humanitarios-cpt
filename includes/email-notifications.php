<?php
// Seguridad: Bloquear acceso directo
defined('ABSPATH') || exit;

/**
 *   Notificación de nuevo registro al correo principal de la web
 */
function humanitarios_send_new_submission_admin_email($post_id) {
    $post = get_post($post_id);

    if (!$post) {
        error_log('❌ Error: No se encontró el post con ID ' . $post_id);
        return;
    }

    // Obtener el correo principal de la web
    $admin_email = get_option('admin_email');
    if (empty($admin_email)) {
        error_log('❌ Error: No se encontró el correo del administrador.');
        return;
    }

    // Asunto del correo
    $subject = 'Nuevo reporte registrado - En revisión';

    // Definir variables para la plantilla
    $post_title = get_the_title($post_id);
    $post_author = get_the_author_meta('display_name', $post->post_author);
    $meta_data = get_post_meta($post_id);

    // Cargar plantilla de email
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/emails/nuevo-registro.php';
    $message = ob_get_clean();

    // Enviar email al correo principal
    $sent = wp_mail(
        $admin_email,
        $subject,
        $message,
        ['Content-Type: text/html; charset=UTF-8']
    );

    if ($sent) {
        error_log('✅ Correo enviado al admin: ' . $admin_email);
    } else {
        error_log('❌ Error: No se pudo enviar el correo al admin.');
    }
}
add_action('humanitarios_send_new_submission_admin_email', 'humanitarios_send_new_submission_admin_email');

/**
 *   Enviar correo al autor cuando un post esté pendiente
 */
function humanitarios_send_post_pending_email($post_id) {
    $post = get_post($post_id);
    if (!$post || $post->post_status !== 'pending') {
        return;
    }

    // Definir variables para la plantilla
    $post_title = get_the_title($post_id);
    $post_author = get_the_author_meta('display_name', $post->post_author);

    // Obtener el email del autor
    $author = get_user_by('id', $post->post_author);
    if (!$author || empty($author->user_email)) {
        error_log('❌ Error: No se encontró el correo del autor.');
        return;
    }

    $subject = 'Tu publicación está pendiente de aprobación';

    // Cargar plantilla
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/emails/post-pendiente.php';
    $message = ob_get_clean();

    // Enviar email al autor
    $sent = wp_mail(
        $author->user_email,
        $subject,
        $message,
        ['Content-Type: text/html; charset=UTF-8']
    );

    if ($sent) {
        error_log('✅ Correo enviado al autor: ' . $author->user_email);
    } else {
        error_log('❌ Error: No se pudo enviar el correo al autor.');
    }
}
add_action('humanitarios_send_post_pending_email', 'humanitarios_send_post_pending_email');


/**
 *  Notificación al admin cuando el reporte es actualizado
 */
function humanitarios_send_update_post_email($post_id) {
    $post = get_post($post_id);
    
    if (!$post) {
        error_log('❌ Error: No se encontró el post con ID ' . $post_id);
        return;
    }

    // Obtener el correo principal de la web
    $admin_email = get_option('admin_email');
    if (empty($admin_email)) {
        error_log('❌ Error: No se encontró el correo del administrador.');
        return;
    }

    // Definir variables para la plantilla
    $post_title = get_the_title($post_id);
    $post_author = get_the_author_meta('display_name', $post->post_author);
    $meta_data = get_post_meta($post_id);

    // Asunto del correo
    $subject = 'Reporte actualizado pendiente de aprobación';

    // Cargar plantilla de email
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/emails/actualizacion-post.php';
    $message = ob_get_clean();

    // Enviar email al admin
    $sent = wp_mail(
        $admin_email,
        $subject,
        $message,
        ['Content-Type: text/html; charset=UTF-8']
    );

    if ($sent) {
        error_log('✅ Correo de actualización enviado al admin: ' . $admin_email);
    } else {
        error_log('❌ Error: No se pudo enviar el correo de actualización al admin.');
    }
}
add_action('humanitarios_post_updated', 'humanitarios_send_update_post_email');

/**
 * Enviar correo cuando un post pasa de "pending" a "publish"
 */
function humanitarios_send_post_approved_email($new_status, $old_status, $post) {
    // Validar que el post pertenece a nuestros CPTs
    if (!in_array($post->post_type, ['personas_perdidas', 'mascotas_perdidas'])) {
        return;
    }

    // Verificar que el estado anterior era "pending" y ahora es "publish"
    if ($old_status !== 'pending' || $new_status !== 'publish') {
        return;
    }

    // Obtener información del autor
    $author = get_user_by('id', $post->post_author);
    if (!$author || empty($author->user_email)) {
        error_log('❌ Error: No se encontró el correo del autor.');
        return;
    }

    // Definir variables para la plantilla
    $post_title = get_the_title($post->ID);
    $post_url = get_permalink($post->ID);

    // Cargar la plantilla de correo
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/emails/post-aprobado.php';
    $message = ob_get_clean();

    // Enviar correo
    $sent = wp_mail(
        $author->user_email,
        'Tu publicación ha sido aprobada',
        $message,
        ['Content-Type: text/html; charset=UTF-8']
    );

    if ($sent) {
        error_log('✅ Correo de aprobación enviado a ' . $author->user_email);
    } else {
        error_log('❌ Error al enviar el correo de aprobación.');
    }
}

// Hook corregido con 3 parámetros
add_action('transition_post_status', 'humanitarios_send_post_approved_email', 10, 3);
