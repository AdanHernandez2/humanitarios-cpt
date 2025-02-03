<?php
// Seguridad: Bloquear acceso directo
defined('ABSPATH') || exit;


function humanitarios_send_new_submission_email($post_id) {
    $post = get_post($post_id);
    $author = get_user_by('id', $post->post_author);
    
    // Cargar template
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/emails/nuevo-registro.php';
    $message = ob_get_clean();
    
    wp_mail(
        $author->user_email,
        'Nuevo Reporte Registrado - En Revisión',
        $message,
        ['Content-Type: text/html; charset=UTF-8']
    );
}

// Notificación de aprobación cuando la publicación cambia a 'publish'
function humanitarios_notify_approved_post($post_id) {
    $post = get_post($post_id);
    
    // Solo enviar notificación si el post ha sido aprobado (cambio a 'publish')
    if ($post->post_status === 'publish') {
        // Cargar el template
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/emails/post-aprobado.php';
        $message = ob_get_clean();
        
        $author = get_user_by('id', $post->post_author);
        $subject = 'Tu publicación ha sido aprobada';
        
        humanitarios_send_email($author->user_email, $subject, $message);
    }
}
add_action('publish_post', 'humanitarios_notify_approved_post');

// Enviar correo al administrador cuando un post esté pendiente
function humanitarios_send_post_pending_email($post_id) {
    $post = get_post($post_id);
    if ($post->post_status === 'pending') {
        // Cargar template
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/emails/post-pendiente.php';
        $message = ob_get_clean();
        
        $admin_email = get_option('admin_email');
        $subject = 'Nuevo reporte pendiente';

        humanitarios_send_email($admin_email, $subject, $message);
    }
}
add_action('pending_to_publish', 'humanitarios_send_post_pending_email');

// Notificación al autor cuando el reporte es actualizado
function humanitarios_send_update_post_email($post_id) {
    $post = get_post($post_id);
    
    // Cargar template
    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/emails/actualizacion-post.php';
    $message = ob_get_clean();
    
    $author = get_user_by('id', $post->post_author);
    $subject = 'Tu reporte ha sido actualizado';

    humanitarios_send_email($author->user_email, $subject, $message);
}
add_action('humanitarios_post_updated', 'humanitarios_send_update_post_email');
