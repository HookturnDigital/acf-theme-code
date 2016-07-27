// jQuery doc ready 
(function($) {
	$(document).ready(function() {
		
		// prevent # linking to top of page 
		$( "a.acftc-field__copy" ).click(function( event ) {
			event.preventDefault();
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
