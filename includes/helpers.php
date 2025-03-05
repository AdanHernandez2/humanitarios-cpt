<?php
/**
 * Helpers del Plugin Humanitarios CPT
 * 
 * @package Humanitarios-CPT
 */

defined('ABSPATH') || exit;

if (!function_exists('humanitarios_get_custom_fields')) {
    /**
     * Obtiene la configuración de campos personalizados para un post type
     */
    function humanitarios_get_custom_fields($post_type) {
        $fields = [
            'personas_perdidas' => [
                'foto_persona' => [ 
                    'type' => 'file',
                    'sanitize' => 'sanitize_text_field'
                ],
                'edad' => [
                    'label' => 'Edad',
                    'type' => 'number',
                    'sanitize' => 'absint',
                    'description' => 'Edad en años',
                    'min' => 0,
                    'max' => 120
                ],
                'nacionalidad' => [
                    'label' => 'Nacionalidad',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'placeholder' => 'Ej: Dominicana'
                ],
                'genero' => [
                    'label' => 'Género',
                    'type' => 'select',
                    'options' => [
                        'masculino' => 'Masculino',
                        'femenino' => 'Femenino',
                        'otro' => 'Otro'
                    ],
                    'sanitize' => 'sanitize_text_field'
                ],
                'color_piel' => [
                    'label' => 'Color de piel',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'placeholder' => 'Ej: Moreno claro'
                ],
                'cabello' => [
                    'label' => 'Cabello',
                    'type' => 'select',
                    'options' => [
                        'negro' => 'Negro',
                        'castaño' => 'Castaño',
                        'rubio' => 'Rubio',
                        'rojo' => 'Rojo',
                        'gris' => 'Gris',
                        'calvo' => 'Calvo'
                    ],
                    'sanitize' => 'sanitize_text_field'
                ],
                'altura' => [
                    'label' => 'Altura aproximada',
                    'type' => 'select',
                    'options' => [
                        '1.45-1.50' => '1,45 - 1,50 m',
                        '1.50-1.55' => '1,50 - 1,55 m',
                        '1.55-1.60' => '1,55 - 1,60 m',
                        '1.60-1.65' => '1,60 - 1,65 m',
                        '1.65-1.70' => '1,65 - 1,70 m',
                        '1.70-1.75' => '1,70 - 1,75 m',
                        '1.75-1.80' => '1,75 - 1,80 m',
                        '1.80-1.85' => '1,80 - 1,85 m',
                        '1.85+' => '1,85 m o más',
                    ],
                    'sanitize' => 'sanitize_text_field',
                ],
                'fecha_desaparicion' => [
                    'label' => 'Fecha de desaparición',
                    'type' => 'date',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'provincia' => [
                    'label' => 'Provincia',
                    'type' => 'select',
                    'options' => humanitarios_get_provincias(),
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'ubicacion' => [
                    'label' => 'Última ubicación conocida',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'placeholder' => 'Ej: Parque Central de Santiago',
                    'required' => true
                ],
                'hora_desaparicion' => [
                    'label' => 'Hora aproximada',
                    'type' => 'time',
                    'sanitize' => 'sanitize_text_field'
                ],
                'vestimenta' => [
                    'label' => 'Vestimenta',
                    'type' => 'textarea',
                    'sanitize' => 'sanitize_textarea_field',
                    'placeholder' => 'Descripción detallada de la vestimenta'
                ],
                'enfermedades' => [
                    'label' => 'Enfermedades o condiciones',
                    'type' => 'textarea',
                    'sanitize' => 'sanitize_textarea_field',
                    'placeholder' => 'Ej: Diabetes, usa lentes, etc.'
                ],
                'nombre_familiar1' => [
                    'label' => 'Nombre del Familiar 1',
                    'type' => 'text',
                    'placeholder' => 'Nombre completo del familiar',
                    'required' => true,
                    'sanitize' => 'sanitize_text_field',
                ],
                'nombre_familiar2' => [
                    'label' => 'Nombre del Familiar 2',
                    'type' => 'text',
                    'placeholder' => 'Nombre completo del familiar',
                    'sanitize' => 'sanitize_text_field',
                ],
                'telefono_1' => [
                    'label' => 'Teléfono familiar 1',
                    'type' => 'tel',
                    'placeholder' => 'Formato: 1-809-555-1234',
                    'required' => true,
                    'sanitize' => 'sanitize_text_field',
                ],
                'telefono_2' => [
                    'label' => 'Teléfono familiar 2',
                    'type' => 'tel',
                    'placeholder' => 'Formato: 1-809-555-1234',
                    'sanitize' => 'sanitize_text_field',
                ],
                'correo' => [
                    'label' => 'Correo electrónico',
                    'type' => 'email',
                    'placeholder' => 'Ej: contacto@familia.com',
                    'sanitize' => 'sanitize_email'
                ],
                'provincia_contacto' => [
                    'label' => 'Provincia de contacto',
                    'type' => 'select',
                    'options' => humanitarios_get_provincias(),
                    'sanitize' => 'sanitize_text_field',
                ],
                'calle' => [
                    'label' => 'Dirección específica',
                    'type' => 'text',
                    'placeholder' => 'Calle, sector, referencia',
                    'sanitize' => 'sanitize_text_field'
                ],
            ],
            'mascotas_perdidas' => [
                'foto_mascota' => [ 
                    'type' => 'file',
                    'sanitize' => 'sanitize_text_field'
                ],
                'tipo_animal' => [
                    'label' => 'Tipo de animal',
                    'type' => 'select',
                    'options' => [
                        'perro' => 'Perro',
                        'gato' => 'Gato',
                        'otro' => 'Otro'
                    ],
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'raza' => [
                    'label' => 'Raza',
                    'type' => 'text',
                    'placeholder' => 'Ej: Pastor Alemán',
                    'sanitize' => 'sanitize_text_field'
                ],
                'color' => [
                    'label' => 'Color',
                    'type' => 'text',
                    'placeholder' => 'Ej: Negro con manchas blancas',
                    'sanitize' => 'sanitize_text_field'
                ],
                'tamanio' => [
                    'label' => 'Tamaño',
                    'type' => 'select',
                    'options' => [
                        'pequeno' => 'Pequeño',
                        'mediano' => 'Mediano',
                        'grande' => 'Grande'
                    ],
                    'sanitize' => 'sanitize_text_field'
                ],
                'edad' => [
                    'label' => 'Edad aproximada',
                    'type' => 'select',
                    'options' => [
                        'infancia' => 'Infancia',
                        'juventud' => 'Juventud',
                        'adultez' => 'Adultez'
                    ],
                    'sanitize' => 'sanitize_text_field'
                ],
                'sexo' => [
                    'label' => 'Sexo',
                    'type' => 'select',
                    'options' => [
                        'macho' => 'Macho',
                        'hembra' => 'Hembra'
                    ],
                    'sanitize' => 'sanitize_text_field'
                ],
                'identificacion' => [
                    'label' => 'Identificación',
                    'type' => 'select',
                    'options' => [
                        'si' => 'Sí',
                        'no' => 'No',
                        'desconocido' => 'No se sabe'
                    ],
                    'sanitize' => 'sanitize_text_field'
                ],
                'fecha_desaparicion' => [
                    'label' => 'Fecha de desaparición',
                    'type' => 'date',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'provincia' => [
                    'label' => 'Provincia',
                    'type' => 'select',
                    'options' => humanitarios_get_provincias(),
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'ubicacion' => [
                    'label' => 'Última ubicación conocida',
                    'type' => 'text',
                    'placeholder' => 'Ej: Cerca del parque Colón',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'hora_desaparicion' => [
                    'label' => 'Hora aproximada',
                    'type' => 'time',
                    'sanitize' => 'sanitize_text_field'
                ],
                'recompensa' => [
                    'label' => 'Recompensa ofrecida',
                    'type' => 'text',
                    'placeholder' => 'Ej: RD$ 5,000',
                    'sanitize' => 'sanitize_text_field'
                ],
                'nombre_familiar1' => [
                    'label' => 'Nombre del Dueño 1',
                    'type' => 'text',
                    'placeholder' => 'Nombre completo',
                    'required' => true,
                    'sanitize' => 'sanitize_text_field',
                ],
                'nombre_familiar2' => [
                    'label' => 'Nombre del Dueño 2',
                    'type' => 'text',
                    'placeholder' => 'Nombre completo',
                    'sanitize' => 'sanitize_text_field',
                ],
                'telefono_1' => [
                    'label' => 'Teléfono 1',
                    'type' => 'tel',
                    'placeholder' => 'Formato: 1-809-555-1234',
                    'required' => true,
                    'sanitize' => 'sanitize_text_field',
                ],
                'telefono_2' => [
                    'label' => 'Teléfono 2',
                    'type' => 'tel',
                    'placeholder' => 'Formato: 1-809-555-1234',
                    'sanitize' => 'sanitize_text_field',
                ],
                'provincia_contacto' => [
                    'label' => 'Provincia de contacto',
                    'type' => 'select',
                    'options' => humanitarios_get_provincias(),
                    'sanitize' => 'sanitize_text_field',
                ],
                'calle' => [
                    'label' => 'Dirección específica',
                    'type' => 'text',
                    'placeholder' => 'Calle, sector, referencia',
                    'sanitize' => 'sanitize_text_field'
                ],
                'correo' => [
                    'label' => 'Correo electrónico',
                    'type' => 'email',
                    'placeholder' => 'Ej: contacto@dueno.com',
                    'sanitize' => 'sanitize_email'
                ],
            ],
            'lost_objects' => [
                'foto_objeto' => [ 
                    'type' => 'file',
                    'sanitize' => 'sanitize_text_field'
                ],
                'nombre_objeto' => [
                    'label' => 'Nombre del objeto',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'descripcion_objeto' => [ 
                    'label' => 'Descripción detallada',
                    'type' => 'textarea',
                    'sanitize' => 'sanitize_textarea_field',
                    'required' => true
                ],
                'tipo_objeto' => [
                    'label' => 'Tipo de objeto',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'marca_objeto' => [
                    'label' => 'Marca',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field'
                ],
                'modelo_objeto' => [
                    'label' => 'Modelo',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field'
                ],
                'color_objeto' => [
                    'label' => 'Color',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field'
                ],
                'estado_objeto' => [
                    'label' => 'Estado',
                    'type' => 'select',
                    'options' => [
                        'nuevo' => 'Nuevo',
                        'usado' => 'Usado',
                        'dañado' => 'Dañado'
                    ],
                    'sanitize' => 'sanitize_text_field'
                ],
                'lugar_perdida' => [
                    'label' => 'Lugar de pérdida',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'fecha_perdida' => [
                    'label' => 'Fecha de pérdida',
                    'type' => 'date',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'provincia' => [
                    'label' => 'Provincia',
                    'type' => 'select',
                    'options' => humanitarios_get_provincias(),
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'nombre_contacto' => [
                    'label' => 'Nombre de contacto',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'telefono_contacto' => [
                    'label' => 'Teléfono de contacto',
                    'type' => 'tel',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'correo_contacto' => [
                    'label' => 'Correo electrónico',
                    'type' => 'email',
                    'sanitize' => 'sanitize_email'
                ],
                'provincia_contacto' => [
                    'label' => 'Provincia de contacto',
                    'type' => 'select',
                    'options' => humanitarios_get_provincias(),
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'direccion_contacto' => [
                    'label' => 'Dirección de contacto',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
            ],
            'found-form' => [
                'foto_encontrado' => [ 
                    'type' => 'file',
                    'sanitize' => 'sanitize_text_field'
                ],
                'tipo_encontrado' => [
                    'label' => 'Tipo de encontrado',
                    'type' => 'select',
                    'options' => [
                        'persona' => 'Persona',
                        'mascota' => 'Mascota',
                        'objeto' => 'Objeto'
                    ],
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                // Campos para personas encontradas
                'nombre_persona' => [
                    'label' => 'Nombre de la persona',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => false
                ],
                'edad_persona' => [
                    'label' => 'Edad aproximada',
                    'type' => 'number',
                    'sanitize' => 'absint',
                    'required' => false
                ],
                'genero_persona' => [
                    'label' => 'Género',
                    'type' => 'select',
                    'options' => [
                        'masculino' => 'Masculino',
                        'femenino' => 'Femenino',
                        'otro' => 'Otro'
                    ],
                    'sanitize' => 'sanitize_text_field',
                    'required' => false
                ],
                // Campos para mascotas encontradas
                'tipo_mascota' => [
                    'label' => 'Tipo de mascota',
                    'type' => 'select',
                    'options' => [
                        'perro' => 'Perro',
                        'gato' => 'Gato',
                        'otro' => 'Otro'
                    ]
                ],
                'sexo_mascota' => [
                    'label' => 'Sexo de la mascota',
                    'type' => 'select',
                    'options' => [
                        'macho' => 'Macho',
                        'hembra' => 'Hembra'
                    ]
                ],
                'raza_mascota' => [
                    'label' => 'Raza',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => false
                ],
                'color_mascota' => [
                    'label' => 'Color',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => false
                ],
                'identificacion_mascota' => [
                    'label' => '¿Tiene collar o identificación?',
                    'type' => 'select',
                    'options' => [
                        'si' => 'Si',
                        'no' => 'No'
                    ]
                ],
                // Campos para objetos encontrados
                'tipo_objeto' => [
                    'label' => 'Tipo de objeto',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => false
                ],
                'marca_objeto' => [
                    'label' => 'Marca',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => false
                ],
                'modelo_objeto' => [
                    'label' => 'Modelo',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => false
                ],
                // Campos comunes
                'descripcion_encontrado' => [
                    'label' => 'Descripción',
                    'type' => 'textarea',
                    'sanitize' => 'sanitize_textarea_field',
                    'required' => true
                ],
                'fecha_encontrado' => [
                    'label' => 'Fecha encontrado',
                    'type' => 'date',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'provincia_encontrado' => [
                    'label' => 'Provincia',
                    'type' => 'select',
                    'options' => humanitarios_get_provincias(),
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'direccion_encontrado' => [
                    'label' => 'Lugar encontrado',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'nombre_contacto' => [
                    'label' => 'Nombre de contacto',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'telefono_contacto' => [
                    'label' => 'Teléfono de contacto',
                    'type' => 'tel',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'correo_contacto' => [
                    'label' => 'Correo electrónico',
                    'type' => 'email',
                    'sanitize' => 'sanitize_email'
                ],
                'provincia_contacto' => [
                    'label' => 'Provincia de contacto',
                    'type' => 'select',
                    'options' => humanitarios_get_provincias(),
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
                'direccion_contacto' => [
                    'label' => 'Dirección de contacto',
                    'type' => 'text',
                    'sanitize' => 'sanitize_text_field',
                    'required' => true
                ],
            ],
        ];

        return $fields[$post_type] ?? [];
    }
}

if (!function_exists('humanitarios_get_provincias')) {
    /**
     * Obtiene el listado de provincias para los selects
     */
    function humanitarios_get_provincias() {
        return [
            'Azua' => 'Azua',
            'Bahoruco' => 'Bahoruco',
            'Barahona' => 'Barahona',
            'Dajabón' => 'Dajabón',
            'Distrito Nacional' => 'Distrito Nacional',
            'Duarte' => 'Duarte',
            'El Seibo' => 'El Seibo',
            'Elías Piña' => 'Elías Piña',
            'Espaillat' => 'Espaillat',
            'Hato Mayor' => 'Hato Mayor',
            'Hermanas Mirabal' => 'Hermanas Mirabal',
            'Independencia' => 'Independencia',
            'La Altagracia' => 'La Altagracia',
            'La Romana' => 'La Romana',
            'La Vega' => 'La Vega',
            'María Trinidad Sánchez' => 'María Trinidad Sánchez',
            'Monseñor Nouel' => 'Monseñor Nouel',
            'Monte Cristi' => 'Monte Cristi',
            'Monte Plata' => 'Monte Plata',
            'Pedernales' => 'Pedernales',
            'Peravia' => 'Peravia',
            'Puerto Plata' => 'Puerto Plata',
            'Samaná' => 'Samaná',
            'San Cristóbal' => 'San Cristóbal',
            'San José de Ocoa' => 'San José de Ocoa',
            'San Juan' => 'San Juan',
            'San Pedro de Macorís' => 'San Pedro de Macorís',
            'Sánchez Ramírez' => 'Sánchez Ramírez',
            'Santiago' => 'Santiago',
            'Santiago Rodríguez' => 'Santiago Rodríguez',
            'Santo Domingo' => 'Santo Domingo',
            'Valverde' => 'Valverde',
        ];
    }
}

if (!function_exists('humanitarios_admin_assets')) {
    /**
     * Registra los assets del admin
     */
    function humanitarios_admin_assets($hook) {
        if (!in_array($hook, ['post.php', 'post-new.php'])) return;

        wp_enqueue_media();
        
        // CSS
        wp_enqueue_style(
            'humanitarios-admin',
            plugins_url('../assets/css/admin.css', __FILE__),
            [],
            filemtime(plugin_dir_path(__FILE__) . '../assets/css/admin.css')
        );

        // JS
        wp_enqueue_script(
            'humanitarios-admin',
            plugins_url('../assets/js/admin.js', __FILE__),
            ['jquery', 'wp-i18n'],
            filemtime(plugin_dir_path(__FILE__) . '../assets/js/admin.js'),
            true
        );
    }
    add_action('admin_enqueue_scripts', 'humanitarios_admin_assets');
}

if (!function_exists('humanitarios_frontend_assets')) {
    /**
     * Registra los assets del frontend
     */
    function humanitarios_frontend_assets() {
        // CSS
        wp_enqueue_style(
            'humanitarios-frontend',
            plugins_url('assets/css/frontend.css', HUMANITARIOS_CPT_FILE),
            [],
            filemtime(plugin_dir_path(HUMANITARIOS_CPT_FILE) . 'assets/css/frontend.css')
        );

        // JS
        wp_enqueue_script(
            'humanitarios-frontend',
            plugins_url('assets/js/frontend.js', HUMANITARIOS_CPT_FILE),
            ['jquery'],
            filemtime(plugin_dir_path(HUMANITARIOS_CPT_FILE) . 'assets/js/frontend.js'),
            true
        );
    }
    add_action('wp_enqueue_scripts', 'humanitarios_frontend_assets');
}