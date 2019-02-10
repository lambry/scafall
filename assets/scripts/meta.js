/**
 * Meta js.
 *
 * Contains all of the JavaScript to handle meta box.
 */

(function($) {

	var Meta = {
		/*** Get things started ***/
		init: function() {
			// Handle uploads
			$('.kickoff-meta').on('click', '.upload-select', this.selectUpload);
			$('.kickoff-meta').on('click', '.upload-remove', this.removeUpload);
			// Handle repeaters
			$('.kickoff-meta .meta-sortable').sortable({ placeholder: 'repeater-gap' });
			$('.kickoff-meta').on('click', '.repeater-add', this.addRepeater);
			$('.kickoff-meta').on('click', '.repeater-remove', this.removeRepeater);
			// Load color picker
			$('.kickoff-meta .color-picker').wpColorPicker();
		},
		/*** Launch media manager ***/
		selectUpload: function(e) {
			e.preventDefault();
			var upload = $(this).parents('.upload'),
				frame = wp.media({
					multiple: false
				}).open();
			// Load upload into setting
			frame.on('select', function() {
				var file = frame.state().get('selection').toJSON()[0],
					uploadImg = upload.find('.upload-image');
				uploadImg.addClass('show').find('img').attr({
					'src': file.url,
					'alt': file.url
				});
				upload.find('.upload-file').val(file.url);
			});
		},
		/*** Remove upload file ***/
		removeUpload: function() {
			var upload = $(this).parents('.upload');
			upload.find('.upload-image').removeClass('show').addClass('hide');
			upload.find('.upload-file').val('');
		},
		/*** Add new repeater section ***/
		addRepeater: function() {
			var repeaters = $(this).parents('.meta-repeater'),
				repeaterSort = repeaters.find('.repeater').sort(function(a, b) {
					return $(a).data('index') > $(b).data('index');
				}),
				repeater = repeaterSort.last(),
				repeaterLast = repeaters.find('.repeater:last-of-type');
			// Show hidden repeater
			if (repeater.hasClass('hide')) {
				repeater.removeClass('hide');
				return;
			}
			// Clone repeater, filter fields and append
			var newRepeater = repeater.clone();
			newRepeater.find('input, textarea, select').each(function () {
				$this = $(this);
				var oldName = $this.attr('name'),
					newNumber = parseInt(oldName.match('([0-9]+)')[0]) + 1,
					newName = oldName.replace(new RegExp('([0-9]+)'), newNumber);
				$this.attr('name', newName);
				if ($this.is(':text') || $this.is('[type=hidden]') || $this.is('textarea') || $this.is('select')) {
					$this.val('');
				}
				if ($this.is(':radio') || $this.is(':checkbox')) {
					$this.attr('checked', false);
				}
			});
			newRepeater.find('.upload-image').removeClass('show').addClass('hide');
			newRepeater.insertAfter(repeaterLast);
		},
		/*** Remove repeater section ***/
		removeRepeater: function() {
			$this = $(this);
			var repeaters = $this.parents('.meta-repeater').find('.repeater'),
				repeater = $this.parents('.repeater');
			// Remove or hide repeater
			if (repeaters.length > 1) {
				repeater.remove();
			} else {
				repeater.addClass('hide');
				repeater.find('input, textarea, select').each(function() {
					$(this).val('').attr('checked', false);
				});
				repeater.find('.upload-image').removeClass('show').addClass('hide');
			}
		}
	};
	Meta.init();

})(jQuery);
