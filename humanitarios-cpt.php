<?php
/**
 * Plugin Name: Humanitarios CPT
 * Description: Plugin para gestionar publicaciones de personas y mascotas perdidas, con formularios frontend y notificaciones.
 * Version: 3.0.5
 * Author: Humanitarios
 * Text Domain: humanitarios
 * Domain Path: /languages
 */

// Bloquear acceso directo
defined('ABSPATH') || exit;

// Definir constantes esenciales
define('HUMANITARIOS_CPT_FILE', __FILE__);
define('HUMANITARIOS_CPT_PATH', plugin_dir_path(HUMANITARIOS_CPT_FILE));
define('HUMANITARIOS_CPT_URL', plugin_dir_url(HUMANITARIOS_CPT_FILE));

// Cargar dependencias
$includes = [
    'includes/helpers.php',          // Funciones compartidas
    'includes/custom-post-types.php', // CPTs
    'includes/shortcodes.php',       // Shortcodes
    'includes/form-handler.php',     // Manejo de formularios
    'includes/admin-meta-boxes.php', // Metaboxes admin
    'includes/filters.php',          // Filtros y hooks
    'includes/email-notifications.php' // Emails
];

foreach ($includes as $file) {
    if (file_exists(HUMANITARIOS_CPT_PATH . $file)) {
        require_once HUMANITARIOS_CPT_PATH . $file;
    }
}

// ========================
// ACTIVACIÓN DEL PLUGIN
// ========================
register_activation_hook(HUMANITARIOS_CPT_FILE, function() {
    // Registrar CPTs
    if (function_exists('humanitarios_register_cpts')) {
        humanitarios_register_cpts();
    }
    
    // Forzar actualización de reglas de rewrite
    flush_rewrite_rules();
});

// ========================
// DESACTIVACIÓN DEL PLUGIN
// ========================
register_deactivation_hook(HUMANITARIOS_CPT_FILE, function() {
    // Limpiar reglas de rewrite
    flush_rewrite_rules();
});

// ========================
# TRADUCCIONES
// ========================
add_action('plugins_loaded', function() {
    load_plugin_textdomain(
        'humanitarios',
        false,
        dirname(plugin_basename(HUMANITARIOS_CPT_FILE)) . '/languages/'
    );
});

// ========================
# SOBREESCRITURA DE PLANTILLAS
// ========================
add_filter('template_include', function($template) {
    $custom_templates = [
        'personas_perdidas' => [
            'single' => 'single-personas_perdidas.php',
            'archive' => 'archive-personas_perdidas.php'
        ],
        'mascotas_perdidas' => [
            'single' => 'single-mascotas_perdidas.php',
            'archive' => 'archive-mascotas_perdidas.php'
        ],
        'lost_objects' => [
            'single' => 'single-lost_objects.php',
            'archive' => 'archive-lost_objects.php'
        ],
        'found-form' => [
            'single' => 'single-found-form.php',
            'archive' => 'archive-found.php'
        ]
    ];

    foreach ($custom_templates as $post_type => $templates) {
        // Plantillas individuales
        if (is_singular($post_type)) {
            $new_template = HUMANITARIOS_CPT_PATH . "templates/single/{$templates['single']}";
            if (file_exists($new_template)) {
                return $new_template;
            }
        }
        
        // Plantillas de archivo
        if (is_post_type_archive($post_type)) {
            $new_template = HUMANITARIOS_CPT_PATH . "templates/archive/{$templates['archive']}";
            if (file_exists($new_template)) {
                return $new_template;
            }
        }
    }

    return $template;
});

function humanitarios_admin_scripts($hook) {
    global $post_type;

    $cpts = ['personas_perdidas', 'mascotas_perdidas', 'lost_objects', 'found-form'];

    if (!in_array($hook, ['post.php', 'post-new.php']) || !in_array($post_type, $cpts)) {
        return;
    }

    wp_enqueue_media();
    
    wp_enqueue_script(
        'humanitarios-admin',
        plugins_url('assets/js/admin.js', __FILE__),
        ['jquery'],
        filemtime(plugin_dir_path(__FILE__) . 'assets/js/admin.js'),
        true
    );

    wp_enqueue_style(
        'humanitarios-admin',
        plugins_url('assets/css/admin.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'assets/css/admin.css')
    );
}
add_action('admin_enqueue_scripts', 'humanitarios_admin_scripts');


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

function humanitarios_enqueue_lightbox() {
    // Verificar si es un single post de los CPT
    if (is_singular(array('personas_perdidas', 'mascotas_perdidas','lost_objects','found-form'))) {
        // Encolar Lightbox CSS
        wp_enqueue_style(
            'lightbox-css',
            'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css',
            array(),
            '2.11.3'
        );

        // Encolar Lightbox JS
        wp_enqueue_script(
            'lightbox-js',
            'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js',
            array('jquery'),
            '2.11.3',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'humanitarios_enqueue_lightbox');