# Humanitarios CPT

**Humanitarios CPT** es un plugin para WordPress pensado para ayudar en situaciones difíciles. Su objetivo es conectar a la comunidad para que personas, mascotas u objetos perdidos vuelvan a casa lo antes posible. 

El sistema funciona en dos direcciones: permite a quienes han perdido algo hacer un reporte detallado, y a quienes encuentran algo en la calle publicarlo para que el dueño pueda localizarlo.

## 📌 ¿Cómo se organiza la información?

El plugin usa **4 tipos de publicaciones (CPT)** para separar bien cada caso:

* **Personas:** Reportes específicos de personas desaparecidas.
* **Mascotas:** Para publicar animales que se han escapado o perdido.
* **Objetos:** Para reportar pertenencias extraviadas (documentos, llaves, carteras, etc.).
* **Encontrados:** Este es el CPT clave para el hallazgo. Aquí es donde alguien publica si vio o encontró a una persona, mascota u objeto en la calle, facilitando el cruce de datos con los reportes de pérdida.

---

## 🛠 Shortcodes

Para que el plugin funcione en tu web, solo tienes que pegar estos códigos en tus páginas:

### 📄 Si perdiste algo (Reportar extravío)
* `[person_creation_form]`: Formulario para reportar una persona desaparecida.
* `[pet_creation_form]`: Formulario para reportar una mascota perdida.
* `[creation_lost_objects_form]`: Formulario para reportar un objeto perdido.

### 🔍 Si encontraste algo (Reportar hallazgo)
* `[render_creation_found_form]`: Formulario único para avisar que encontraste a una persona, mascota u objeto en la vía pública.

### ⚙️ Gestión y Edición
* `[edit_post_person_form]`, `[edit_post_pets_form]`, `[edit_post_lost_objects]`: Para editar tus reportes de cosas perdidas.
* `[edit_post_found]`: Para editar reportes de cosas que encontraste.

### 📱 Listados y Búsquedas
* `[mostrar_publicaciones]`: Galería general con todos los reportes de la web.
* `[filtro_publicaciones]`: El buscador para localizar reportes de desaparecidos.
* `[filtro_encontrados]`: El buscador para localizar reportes de hallazgos en la calle.
* `[mostrar_publicaciones_usuario]`: Panel privado para que cada usuario gestione sus propios avisos.

### 👤 Usuarios
* `[humanitarios_formulario_registro]`: Formulario para que la gente se registre directamente en el sitio.

---

## 📧 Correos automáticos
El plugin ya viene configurado para enviar emails cuando alguien crea un reporte, cuando el administrador lo está revisando y cuando finalmente se publica. Así todos están al tanto del proceso.

## 🚀 Instalación
1.  Mete la carpeta del plugin en `/wp-content/plugins/`.
2.  Actívalo desde el panel de WordPress.
3.  Crea las páginas que necesites y pega los shortcodes anteriores.

---
*Hecho para ayudar a que lo perdido regrese a casa.*
