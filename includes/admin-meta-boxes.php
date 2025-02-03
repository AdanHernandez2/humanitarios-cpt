<?php
defined('ABSPATH') || exit;

function humanitarios_add_meta_boxes() {
  add_meta_box(
      'humanitarios_meta_box',
      'Información Adicional',
      'humanitarios_meta_box_callback',
      ['personas_perdidas', 'mascotas_perdidas'],
      'normal',
      'high'
  );
}
add_action('add_meta_boxes', 'humanitarios_add_meta_boxes');

function humanitarios_meta_box_callback($post) {
  wp_nonce_field('humanitarios_save_meta_box', 'humanitarios_meta_box_nonce');

  // Definir los campos personalizados
  $fields = [
      'personas_perdidas' => [
          'edad' => 'Edad',
          'nacionalidad' => 'Nacionalidad',
          'genero' => 'Género',
          'color_piel' => 'Color de piel',
          'cabello' => 'Cabello',
          'altura' => 'Altura',
          'fecha_desaparicion' => 'Fecha de desaparición',
          'ubicacion' => 'Ubicación',
          'hora_desaparicion' => 'Hora de desaparición',
          'vestimenta' => 'Vestimenta',
          'enfermedades' => 'Enfermedades',
          'telefono' => 'Teléfono',
          'correo' => 'Correo electrónico',
          'ubicacion_contacto' => 'Ubicación de contacto',
          'calle' => 'Calle'
      ],
      'mascotas_perdidas' => [
          'nombre_mascota' => 'Nombre',
          'tipo_animal' => 'Tipo de Animal',
          'raza' => 'Raza',
          'color' => 'Color',
          'tamanio' => 'Tamaño',
          'edad' => 'Edad',
          'sexo' => 'Sexo',
          'identificacion' => 'Identificación',
          'fecha_desaparicion' => 'Fecha de desaparición',
          'ubicacion' => 'Ubicación',
          'hora_desaparicion' => 'Hora de desaparición',
          'recompensa' => 'Recompensa',
          'telefono' => 'Teléfono',
          'correo' => 'Correo electrónico'
      ]
  ];

  $post_type = get_post_type($post);
  if (!isset($fields[$post_type])) return;

  echo '<table class="form-table">';
  foreach ($fields[$post_type] as $key => $label) {
      $value = get_post_meta($post->ID, $key, true);
      echo '<tr>';
      echo '<th><label for="'. esc_attr($key) .'">'. esc_html($label) .'</label></th>';
      echo '<td><input type="text" id="'. esc_attr($key) .'" name="'. esc_attr($key) .'" value="'. esc_attr($value) .'" class="regular-text"></td>';
      echo '</tr>';
  }
  echo '</table>';
}


function humanitarios_save_meta_box($post_id) {
  // Verificar nonce
  if (!isset($_POST['humanitarios_meta_box_nonce']) || !wp_verify_nonce($_POST['humanitarios_meta_box_nonce'], 'humanitarios_save_meta_box')) {
      return;
  }

  // Evitar autoguardado
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
  }

  // Verificar permisos
  if (!current_user_can('edit_post', $post_id)) {
      return;
  }

  // Definir los campos personalizados
  $fields = [
      'edad', 'nacionalidad', 'genero', 'color_piel', 'cabello', 'altura',
      'fecha_desaparicion', 'ubicacion', 'hora_desaparicion', 'vestimenta',
      'enfermedades', 'telefono', 'correo', 'ubicacion_contacto', 'calle',
      'nombre_mascota', 'tipo_animal', 'raza', 'color', 'tamanio', 'edad',
      'sexo', 'identificacion', 'recompensa'
  ];

  foreach ($fields as $field) {
      if (isset($_POST[$field])) {
          update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
      }
  }
}
add_action('save_post', 'humanitarios_save_meta_box');


function humanitarios_custom_columns($columns) {
  $columns['ubicacion'] = 'Ubicación';
  $columns['telefono'] = 'Teléfono';
  return $columns;
}
add_filter('manage_personas_perdidas_posts_columns', 'humanitarios_custom_columns');
add_filter('manage_mascotas_perdidas_posts_columns', 'humanitarios_custom_columns');

function humanitarios_custom_columns_content($column, $post_id) {
  if (in_array($column, ['ubicacion', 'telefono'])) {
      echo esc_html(get_post_meta($post_id, $column, true));
  }
}
add_action('manage_personas_perdidas_posts_custom_column', 'humanitarios_custom_columns_content', 10, 2);
add_action('manage_mascotas_perdidas_posts_custom_column', 'humanitarios_custom_columns_content', 10, 2);
