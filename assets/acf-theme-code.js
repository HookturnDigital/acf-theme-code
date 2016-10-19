// jQuery doc ready
(function($) {
	$(document).ready(function() {

		// prevent # linking to top of page
		$( "a.acftc-field__copy" ).click(function( event ) {
			event.preventDefault();
		});

		// ACF 4 - add anchor link to each field object
		$( "div.field" ).each(function( index ) {
			var field_key = $(this).attr("data-id");
			console.log(field_key);
			var data_type = $(this).attr("data-type");
			if ((data_type != 'tab') && (data_type != 'message')) {
  				$(this).find('.row_options').append( '<span>| <a class="acftc-scroll__link" href="#acftc-' + field_key + '">Code</a></span>' );
			}
		});

		// ACF 5 - add anchor link to each field object
		$( ".acf-field-object" ).each(function( index ) {
			var field_key = $(this).attr("data-id");
			var data_type = $(this).attr("data-type");
			if ((data_type != 'tab') && (data_type != 'message')) {
				$(this).find('.row-options').append( '<a class="acftc-scroll__link" href="#acftc-' + field_key + '">Code</a>' );
			}
		});

		// smooth scroll - with offset for title and WP admin bar
		$('a[href*=#]:not([href=#])').click(function() {
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			  var target = $(this.hash);
			  target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			  var target_offset = target.offset().top;
			  var customoffset = 60;

			  if (target.length) {
			    $('html,body').animate({
			      scrollTop: target_offset - customoffset
			    }, 1000);
			    return false;
			  }
			}
		});

	});

 } )( jQuery );

 // Instantiate clipboard

(function(){

	var copyCode = new Clipboard('.acftc-field__copy', {
		target: function(trigger) {
			return trigger.nextElementSibling;
		}

	});

	copyCode.on('success', function(e) {
		//console.info('Action:', e.action);
		//console.info('Text:', e.text);
		//console.info('Trigger:', e.trigger);
		e.clearSelection();
	});

})();
