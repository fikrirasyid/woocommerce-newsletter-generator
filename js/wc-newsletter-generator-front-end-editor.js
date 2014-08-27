jQuery(document).ready(function($){
	/**
	 * Toggle edit screen
	 */
	$('body').on( 'click', '.toggle-edit-block', function(e){
		e.preventDefault();

		var trigger = $(this);
		var wrap 	= trigger.parent('.edit-content-block');
		var type 	= wrap.attr('data-type');
		var id 		= wrap.attr('data-id');
		var text 	= wrap.attr('data-text');
		var image 	= wrap.attr('data-image');
		var href 	= wrap.attr('data-href');

		// Toggle UI
		$('#modal-background').velocity({ 'opacity' : '.5' }, { 'display' : 'block' });
		$('#block-selector').velocity('fadeIn');

		// Update edit block parts
		$('.var-html[data-param="id"]').html(id);

		// Store current id as target
		$('#block-selector').attr( 'data-target', id );

		// Editing preparation: Image
		if( 'image' == type ){
			// Put the data on the form
			$('#edit-image-text').val( text );
			$('#edit-image-image').val( image );
			$('#edit-image-href').val( href );			
		}

		// Editing preparation: Text
		if( 'text' == type ){
			var current_text = wrap.find('.the-text').text();

			// Put the text on the box
			$('#edit-text-text').val( current_text );
		}		

		// Editing preparation: Product

		// Display edit screen
		$('#edit-' + type).show();
	});

	/**
	 * Updating mechanism
	 */ 
	// Image
	$('#edit-image').submit(function(e){
		e.preventDefault();

		// Get values
		var target = $('#block-selector').attr('data-target');
		var width = $('.edit-content-block[data-id="'+target+'"] .the-image img').attr('width');
		var height = $('.edit-content-block[data-id="'+target+'"] .the-image img').attr('height');
		var text = $('#edit-image-text').val();
		var image = $('#edit-image-image').val();
		var href = $('#edit-image-href').val();

		var img = '<img src="'+image+'" alt="'+text+'" width="'+width+'" height="'+height+'" />';

		// Update UI
		if( href.length > 0 ){
			$('.edit-content-block[data-id="'+target+'"] .the-image').html('<a href="'+href+'" title="'+text+'">'+img+'</a>');
		} else {
			$('.edit-content-block[data-id="'+target+'"] .the-image').html( img );
		}

		// Close the block editor
		close_edit_ui();

		// Sync to server
	});

	// Text
	$('#edit-text').submit(function(e){
		e.preventDefault();

		// Get values
		var target = $('#block-selector').attr('data-target');
		var text = $('#edit-text-text').val();

		// Update UI
		$('.edit-content-block[data-id="'+target+'"] .the-text').html(text);

		// Close the block editor
		close_edit_ui();

		// Sync to server
	});

	// Product

	/**
	 * Close edit screen
	 */
	$('body').on('click', '#modal-background, #close-selector', function(){
		close_edit_ui();
	});

	$(document).keyup(function(e){
		if ( e.keyCode == 27 && $('#block-selector').is(':visible')){
			close_edit_ui();
		}
	});	

	// Things to do when closing the block editor UI
	function close_edit_ui(){
		// Close UI
		$('#modal-background, #block-selector').velocity('fadeOut');		

		// Close edit form
		$('.edit-block-form').hide();
	}
});