# Humanitarios CPT

Humanitarios CPT es un plugin de WordPress diseñado para reportar personas, mascotas y objectos perdidos. Este plugin permite registrar, gestionar y mostrar anuncios de personas, mascotas y pertenencias desaparecidas, así como notificar a los usuarios sobre actualizaciones importantes.

## Instalación

1. Clona o descarga este repositorio en tu carpeta de plugins de WordPress.
2. Activa el plugin a través del menú "Plugins" en WordPress.
3. Configura las opciones del plugin según tus necesidades.

## Uso

### Custom Post Types

El plugin registra dos tipos de publicaciones personalizadas (CPTs):
- **Personas Perdidas**: Permite reportar personas desaparecidas.
- **Mascotas Perdidas**: Permite reportar mascotas desaparecidas.

## Shortcodes disponibles

| Shortcode | Descripción |
|-----------|-------------|
| `[person_creation_form]` | Formulario para reportar personas perdidas |
| `[pet_creation_form]` | Formulario para reportar mascotas perdidas |
| `[creation_lost_objects_form]` | Formulario para reportar objetos perdidos |
| `[render_creation_found_form]` | Formulario para reportar objetos encontrados |
| `[edit_post_person_form]` | Formulario para editar reportes de personas |
| `[edit_post_pets_form]` | Formulario para editar reportes de mascotas |
| `[edit_post_lost_objects]` | Formulario para editar reportes de objetos perdidos |
| `[edit_post_found]` | Formulario para editar reportes de objetos encontrados |
| `[mostrar_publicaciones]` | Muestra todas las publicaciones (sin paginación) |
| `[filtro_publicaciones]` | Filtro de búsqueda para publicaciones |
| `[filtro_encontrados]` | Filtro de búsqueda para publicaciones de encontrados |
| `[mostrar_publicaciones_usuario]` | Muestra publicaciones del usuario logueado con paginación |
| `[humanitarios_formulario_registro]` | Formulario de registro para nuevos usuarios |

### Formularios

El plugin incluye formularios para la creación y edición de publicaciones de personas y mascotas perdidas, así como formularios de filtrado para buscar anuncios específicos.

### Notificaciones por Correo

El plugin envía notificaciones por correo electrónico en diferentes eventos, como la creación de una nueva publicación, la revisión pendiente y la aprobación de publicaciones.

## Plantillas

El plugin proporciona varias plantillas para personalizar la visualización de los anuncios, incluyendo:
- Plantillas de correo electrónico.
- Vistas individuales para personas y mascotas perdidas.
- Listados generales de personas y mascotas desaparecidas.
- Componentes reutilizables como tarjetas y formularios.

## Contribuir

¡Contribuciones son bienvenidas! Si deseas colaborar en el desarrollo de este plugin, por favor sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una rama para tu feature (`git checkout -b feature/nueva-feature`).
3. Realiza tus cambios.
4. Envía un pull request.

## Licencia

Este proyecto está licenciado bajo la [MIT License](https://opensource.org/licenses/MIT) - consulta el archivo `LICENSE` para más detalles.

