jQuery(document).ready(function ($) {
  // Galería de imágenes
  $(".humanitarios-add-images").on("click", function (e) {
    e.preventDefault();

    const frame = wp.media({
      title: "Seleccionar Imágenes",
      multiple: true,
      library: { type: "image" },
    });

    frame.on("select", function () {
      const attachments = frame.state().get("selection").toArray();
      const galleryList = $(this)
        .closest(".humanitarios-gallery-wrapper")
        .find(".humanitarios-gallery-grid");

      attachments.forEach((attachment) => {
        const thumb =
          attachment.attributes.sizes?.thumbnail?.url ||
          attachment.attributes.url;
        galleryList.append(`
                  <li class="humanitarios-gallery-item">
                      <img src="${thumb}" alt="">
                      <input type="hidden" name="humanitarios_galeria[]" value="${attachment.id}">
                      <button type="button" class="humanitarios-remove-image">&times;</button>
                  </li>
              `);
      });
    });

    frame.open();
  });

  // Eliminar imágenes
  $(".humanitarios-gallery-grid").on(
    "click",
    ".humanitarios-remove-image",
    function (e) {
      e.preventDefault();
      $(this).closest(".humanitarios-gallery-item").remove();
    }
  );
});
