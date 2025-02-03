<?php
/**
 * Plugin Name: Humanitarios CPT
 * Description: Plugin para gestionar publicaciones de personas y mascotas perdidas, con formularios frontend y notificaciones.
 * Version: 1.0.0
 * Author: Humanitarios
 * Text Domain: humanitarios.d
 */

// Seguridad: Bloquear acceso directo
defined('ABSPATH') || exit;

// Incluir archivos necesarios
require_once plugin_dir_path(__FILE__) . 'includes/custom-post-types.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'includes/form-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/filters.php';
require_once plugin_dir_path(__FILE__) . 'includes/email-notifications.php';

// Registrar CPTs al activar el plugin
register_activation_hook(__FILE__, 'humanitarios_create_cpts');

// Cargar traducciones
add_action('plugins_loaded', function() {
    load_plugin_textdomain('humanitarios', false, dirname(plugin_basename(__FILE__)) . '/languages/');
});

// Encolar estilos y scripts
add_action('wp_enqueue_scripts', 'humanitarios_enqueue_assets');
function humanitarios_enqueue_assets() {
    // CSS
    wp_enqueue_style(
        'humanitarios-frontend',
        plugins_url('/assets/css/frontend.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'assets/css/frontend.css')
    );

    // JS
    wp_enqueue_script(
        'humanitarios-frontend',
        plugins_url('/assets/js/frontend.js', __FILE__),
        ['jquery'],
        filemtime(plugin_dir_path(__FILE__) . 'assets/js/frontend.js'),
        true
    );

    // Localizar scripts
    wp_localize_script('humanitarios-frontend', 'humanitariosData', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('humanitarios_nonce')
    ]);
}

// Sobreescribir plantillas
add_filter('template_include', 'humanitarios_custom_templates');
function humanitarios_custom_templates($template) {
    // Singles
    if (is_singular('personas_perdidas')) {
        return plugin_dir_path(__FILE__) . 'templates/single/single-personas_perdidas.php';
    }

    if (is_singular('mascotas_perdidas')) {
        return plugin_dir_path(__FILE__) . 'templates/single/single-mascotas_perdidas.php';
    }

    // Archives
    if (is_post_type_archive('personas_perdidas')) {
        return plugin_dir_path(__FILE__) . 'templates/archive/archive-personas_perdidas.php';
    }

    if (is_post_type_archive('mascotas_perdidas')) {
        return plugin_dir_path(__FILE__) . 'templates/archive/archive-mascotas_perdidas.php';
    }

    return $template;
}

// Shortcode de ejemplo
add_shortcode('formulario_registro', 'humanitarios_registration_form_shortcode');
