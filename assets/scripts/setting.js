/**
 * Setting js.
 *
 * JavaScript for the setting pages.
 */

(function($) {

	var Setting = {
		init: function() {
			var wrapper = $('.kickoff-setting');
			// Handle upload
			wrapper.on('click', '.upload-select', this.selectUpload);
			// Handle removing upload
			wrapper.on('click', '.upload-remove', this.removeUpload);
			// Load color picker
			$('.color-picker').wpColorPicker();
		},
		/** Launch media manager **/
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
					src: file.url,
					alt: file.url
				});
				upload.find('.upload-file').val(file.url);
			});
		},
		/** Remove upload file **/
		removeUpload: function() {
			var upload = $(this).parents('.upload');
			upload.find('.upload-image').removeClass('show').addClass('hide');
			upload.find('.upload-file').val('');
		}
	};
	Setting.init();

})(jQuery);
