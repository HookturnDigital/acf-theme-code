// i18n functions
const { __ } = wp.i18n; // Support for these functions can also be added: _x, _n, sprintf eg. `const { __, sprintf } = wp.i18n;`

(function($) {
	$(document).ready(function() {

		let $window = $(window);

		// i18n strings
		let i18nStrCopyAll = __('Copy all to clipboard', 'acf-theme-code');
		let i18nStrCode = __('Code', 'acf-theme-code');

		// prevent # linking to top of page
		$( "a.acftc-field__copy" ).click(function( event ) {
			event.preventDefault();
		});

		// add the copy all button
		$( "#acftc-meta-box .inside").prepend('<a href="#" class="acftc-copy-all acf-js-tooltip" title="' + i18nStrCopyAll + '"></a>');
		$( "#acftc-meta-box .toggle-indicator").hide();

		// Add anchor link to each field object 
		var fields = $('#acf-field-group-fields .acf-field-object')

			// exclude nested fields
			.filter( function() {
				return $(this).parentsUntil('#acf-field-group-fields', '.acf-field-object').length === 0;
			});

		fields.each(function( index ) {

			var field_key = $(this).attr("data-id");
			var data_type = $(this).attr("data-type");

			// no code is generated for tab and message fields
			if ( ( data_type != 'tab' ) && ( data_type != 'message') ) {

				$(this).find( '.row-options' ).eq( 0 ).append( '<a class="acftc-scroll__link" href="#acftc-' + field_key + '">' + i18nStrCode + '</a>' );

			}

		});

		// Scroll to the field code
		$('a.acftc-scroll__link').click(function(event) {

			// prevent default
			event.preventDefault();

			let wpAdminBar = $('#wpadminbar');
			let acfAdminToolbar = $('.acf-admin-toolbar');
			let acfHeaderBar = $('.acf-headerbar');
			let scrollOffset = 44;
			
			// find the location that's selected
			var location = $( "#acftc-group-option option:selected" ).val();

			// if there is nothing selected
			if( location == null ) {
				var location = 'acftc-meta-box .inside';
			}

			// find the field that we want to scroll to (from the hash)
			var hash = $(this).attr("href");

			// define a target field
			var target = $('#' + location + ' ' + hash);

			// find the offset from the top of our target field
			var targetOffset = target.offset().top;

			// WP admin bar: Include scroll offset if present and fixed or sticky
			if ( wpAdminBar.length && ( wpAdminBar.css('position') === 'fixed' || wpAdminBar.css('position') === 'sticky' ) ) {
				scrollOffset += wpAdminBar.outerHeight();
			}

			// ACF admin toolbar: Include scroll offset if present and fixed or sticky
			if ( acfAdminToolbar.length && ( acfAdminToolbar.css('position') === 'fixed' || acfAdminToolbar.css('position') === 'sticky' ) ) {
				scrollOffset += acfAdminToolbar.outerHeight();
			}
			
			// ACF header bar: Include scroll offset if present and fixed or sticky
			if ( acfHeaderBar.length && ( acfHeaderBar.css('position') === 'fixed' || acfHeaderBar.css('position') === 'sticky' ) ) {
				scrollOffset += acfHeaderBar.outerHeight();
			}

			// Scroll to the target field
			$('html,body').animate({
				scrollTop: targetOffset - scrollOffset
			}, 1000);

			return false;

		});

		
		// Locations select
		
		// add active to the first location
		$('#acftc-group-0').addClass('location-wrap--active');
		
		// On toggle of the location
		$( "#acftc-group-option" ).change(function( event ) {
			
			// get the selected value
			var activediv = $(this).val();
			
			// hide all the divs
			$('.location-wrap').slideUp();
			
			// remove the active class from all the divs
			$('.location-wrap').removeClass('location-wrap--active');
			
			// slide down the one we want
			$('#' + activediv ).slideDown();
			
			// add the active class to the active div
			$('#' + activediv ).addClass('location-wrap--active');
			
		});
		
	});

	var copyField = new Clipboard('.acftc-field__copy', {
		target: function(trigger) {
			return trigger.nextElementSibling;
		}
	});

	copyField.on('success', function(e) {
		e.clearSelection();
	});

	// copy all
	var copyAllFields = new Clipboard('.acftc-copy-all', {
		text: function(trigger) {
			var $allCodeBlocks = $('#acftc-meta-box .location-wrap--active .acftc-field-code pre');
			return $allCodeBlocks.text();
		}
	});

	copyAllFields.on('success', function(e) {
		e.clearSelection();
	});

} )( jQuery );
