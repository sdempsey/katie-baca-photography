galleryController = (function($) {
	var win, doc, nope, nopeLink,
	gallery, galleryLink, image;

	function onDocumentReady() {
		win = $(window);
		doc = $(document);
		nope = $('.nope');
		nopeLink = $('.nope a');
		gallery = $('.gallery');
		galleryLink = $('.gallery a');
		image = $('.gallery img');

		nope.on('click', onNopeClick);
		animateNope();

		image.on('mouseenter', function() {
			$(this).stop().velocity({
				scale: [1.1, 1],
				rotateZ: [360, 0]
			}, 500, 'ease');			
		});
		galleryLink.on('mouseleave', function() {
			image.stop().velocity({
				scale: 1,
				rotateZ: 0
			}, 500, 'ease');			
		});

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

	function onMouseLeave() {
		image.stop().velocity({
			scale: 1,
			rotate: 0
		}, 600);
	}

	function onMouseEnter() {
		image.stop().velocity({
			scale: [1.1, 1],
			rotate: [360, 0]
		}, 600);
	}

	$(onDocumentReady);


})(jQuery);