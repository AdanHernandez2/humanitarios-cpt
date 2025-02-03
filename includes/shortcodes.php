<?php
// Seguridad: Bloquear acceso directo
defined('ABSPATH') || exit;


// shortcodes.php
function render_person_creation_form() {
  ob_start();
  include plugin_dir_path(__FILE__) . '../templates/parts/forms/post-creation-person-form.php'; // Ruta al formulario
  return ob_get_clean();
}
add_shortcode('person_creation_form', 'render_person_creation_form');

function render_pet_creation_form() {
  ob_start();
  include plugin_dir_path(__FILE__) . '../templates/parts/forms/post-creation-pets-form.php'; // Ruta al formulario
  return ob_get_clean();
}
add_shortcode('pet_creation_form', 'render_pet_creation_form');

// shortcodes.php
function render_edit_post_form() {
  ob_start();
  include plugin_dir_path(__FILE__) . '../templates/parts/forms/edit-post-form.php'; // Ruta al formulario de edición
  return ob_get_clean();
}
add_shortcode('edit_post_form', 'render_edit_post_form');


