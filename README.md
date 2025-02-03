# Humanitarios CPT

Humanitarios CPT es un plugin de WordPress diseñado para reportar personas y mascotas perdidas. Este plugin permite registrar, gestionar y mostrar anuncios de personas y mascotas desaparecidas, así como notificar a los usuarios sobre actualizaciones importantes.

## Estructura del plugin

humanitarios-cpt/
├── humanitarios-cpt.php              → Archivo principal del plugin
├── includes/                         → Lógica funcional
│   ├── custom-post-types.php         → Registro de CPTs
│   ├── form-handler.php              → Procesamiento de formularios
│   ├── email-notifications.php       → Envío de correos
│   ├── shortcodes.php                → Shortcodes
│   ├── filters.php                   → Filtros y hooks
│
├── templates/                        → Plantillas visuales
│   └── emails/                   ← Nueva carpeta para emails
│       ├── email-header.php      ← Cabecera reutilizable
│       ├── email-footer.php      ← Pie de email
│       ├── nuevo-registro.php    ← Email de bienvenida
│       ├── post-pendiente.php    ← Notificación de publicación en revisión
│       ├── post-aprobado.php     ← Aviso de publicación aprobada
│       └── actualizacion-post.php← Notificación de actualización
│   ├── single/                       → Vistas individuales (perfiles)
│   │   ├── single-personas_perdidas.php
│   │   └── single-mascotas_perdidas.php
│   ├── archive/                      → Listados generales
│   │   ├── archive-personas_perdidas.php
│   │   └── archive-mascotas_perdidas.php
│   └── parts/                        → Componentes reutilizables
│       ├── cards/                    → Diseño de tarjetas
│       │   ├── card-persona.php
│       │   └── card-mascota.php
│       ├── filters/                  → Formularios de filtrado
│       │   └── filter-form.php
│       └── forms/                    → Formularios de creación/edición
│           ├── edit-post-form.php
│           ├── post-creation-person-form.php
│           └── post-creation-pets-form.php
├── assets/                           → Recursos estáticos
│   ├── css/
│   │   └── frontend.css             → Estilos para cards/filtros/formularios
│   └── js/
│       └── frontend.js              → Scripts para interacciones


## Instalación

1. Clona o descarga este repositorio en tu carpeta de plugins de WordPress.
2. Activa el plugin a través del menú "Plugins" en WordPress.
3. Configura las opciones del plugin según tus necesidades.

## Uso

### Custom Post Types

El plugin registra dos tipos de publicaciones personalizadas (CPTs):
- **Personas Perdidas**: Permite reportar personas desaparecidas.
- **Mascotas Perdidas**: Permite reportar mascotas desaparecidas.

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

