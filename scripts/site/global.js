galleryController = (function($) {
	var win, doc, nope, nopeLink,
	gallery;

	function onDocumentReady() {
		win = $(window);
		doc = $(document);
		nope = $('.nope');
		nopeLink = $('.nope a');
		gallery = $('.gallery');

		nope.on('click', onNopeClick);
		animateNope();

		initializeGallery();
	}

	function animateNope() {
		nope.stop().velocity({
			top: '50%',
			left: '50%',
			translateX: ['-50%','-100vh'],
			translateY: ['-50%', '-50%'],
			translateZ: 0
		}, 4000, 'easeOutSine');
	}

	function onNopeClick() {
		window.history.back();
		return false;
	}

	function initializeGallery() {
		if (gallery.length > 0) {

			options = {
				delegate: 'a',
				type: 'image',
				gallery: {
					enabled: true
				},
				preload: [1,3],
				removalDelay: 300,
				mainClass: 'mfp-fade'
			};

			gallery.each(function() {
				$(this).magnificPopup(options);
			});			
		}
	}

	$(onDocumentReady);


})(jQuery);