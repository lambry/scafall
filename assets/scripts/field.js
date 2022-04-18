/**
 * Field functionality.
 */

(function ($) {
	const scafall = $(".scafall");

	// Selecting upload
	scafall.on("click", ".scafall-upload-select", function (e) {
		e.preventDefault();

		const upload = $(this).parents(".scafall-upload");
		const media = wp.media({ multiple: false }).open();

		// Save and preview upload
		media.on("select", () => {
			const file = media.state().get("selection").toJSON()[0];
			const preview = upload.find(".scafall-upload-preview");

			if (file.type === "image") {
				preview.find(".scafall-upload-file").removeClass("scafall-show");

				preview
					.find(".scafall-upload-image")
					.attr({ alt: file.alt || file.title, src: file.sizes.thumbnail.url })
					.addClass("scafall-show");
			} else {
				preview.find(".scafall-upload-image").removeClass("scafall-show");
				preview.find(".scafall-upload-file").html(file.url).addClass("scafall-show");
			}

			preview.addClass("scafall-show");
			upload.find(".scafall-upload-id").val(file.id);
		});
	});

	// Removing upload
	scafall.on("click", ".scafall-upload-remove", function () {
		const upload = $(this).parents(".scafall-upload");

		upload.find(".scafall-upload-id").val("");
		upload.find(".scafall-show").removeClass("scafall-show");
	});

	// Init colour pickers
	scafall.find(".scafall-colour-picker").wpColorPicker();
})(jQuery);
