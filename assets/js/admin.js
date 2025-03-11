jQuery(document).ready(function ($) {
  // Añadir imágenes
  $(".humanitarios-add-images").click(function (e) {
    e.preventDefault();
    const frame = wp.media({
      title: "Seleccionar Imágenes",
      multiple: true,
      library: { type: "image" },
    });

    frame.on("select", function () {
      const attachments = frame.state().get("selection").toJSON();
      const galleryGrid = $(this)
        .closest(".humanitarios-gallery-wrapper")
        .find(".humanitarios-gallery-grid");

      attachments.forEach((attachment) => {
        galleryGrid.append(`
                  <li style="position: relative;">
                      <img src="${attachment.sizes.thumbnail.url}">
                      <input type="hidden" name="humanitarios_galeria[]" value="${attachment.id}">
                      <button class="button-link humanitarios-remove-image" style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; padding: 2px 5px;">×</button>
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
    function () {
      $(this).closest("li").remove();
    }
  );
});
