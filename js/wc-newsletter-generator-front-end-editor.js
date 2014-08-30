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
		$('body').css({ 'overflow' : 'hidden' });

		// Update edit block parts
		$('.var-html[data-param="id"]').html(id);

		// Store current id as target
		$('#block-selector').attr( 'data-target', id );

		// Editing preparation: Image
		if( 'image' == type ){
			// Put the data on the form
			$('#edit-image-preview').attr( 'src', image );
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
		// Nothing happens, just load the picker

		// Display edit screen
		$('#edit-' + type).show();
	});

	/**
	 * Image selection mechanism using WordPress' media uploader
	 */
	$('body').on( 'click', '#edit-image-change-image', function(e){
		e.preventDefault();

		var file_frame;

		// If the media frame already exists, reopen it.
	    if ( file_frame ) {
	      file_frame.open();
	      return;
	    }

	    // Create the media frame.
	    file_frame = wp.media.frames.file_frame = wp.media({
	      title: wcng_params.label_select_image,
	      button: {
	        text: wcng_params.label_select_image,
	      },
	      multiple: false  // Set to true to allow multiple files to be selected
	    });
	 
	    // When an image is selected, run a callback.
	    file_frame.on( 'select', function() {
	      // We set multiple to false so only get one image from the uploader
	      attachment = file_frame.state().get('selection').first().toJSON();
	 
			// Do something with attachment.id and/or attachment.url here
			$('#edit-image-preview').attr( 'src', attachment.url );
			$('#edit-image-image').val( attachment.url );

	    });
	 
	    // Finally, open the modal
	    file_frame.open();
	});

	/**
	 * Updating mechanism
	 */ 
	// Image
	$('#edit-image').submit(function(e){
		e.preventDefault();

		// Get values
		var target 	= $('#block-selector').attr('data-target');
		var width 	= $('.edit-content-block[data-id="'+target+'"] .the-image img').attr('width');
		var height 	= $('.edit-content-block[data-id="'+target+'"] .the-image img').attr('height');
		var text 	= $('#edit-image-text').val();
		var image 	= $('#edit-image-image').val();
		var href 	= $('#edit-image-href').val();

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
		sync_update( target, 'image', { text : text, image : image, link : href } );
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
		sync_update( target, 'text', { text : text } );
	});

	// Product
	$('#block-selector').on( 'click', '.select-this-product', function(e){
		e.preventDefault();

		// Get values
		var button 				= $(this);
		var product 			= button.parents('li');
		var target 				= $('#block-selector').attr('data-target');
		var product_id 			= button.attr('data-product-id');
		var product_img 		= product.find('.image-wrap img').attr('src');
		var product_name 		= product.find('.product-name').html();
		var product_name_only 	= product.find('.product-name a').text();
		var product_price	 	= product.find('.product-price').html();
		var product_permalink 	= product.find('.product-name a').attr('href');

		// Update UI
		$('.edit-content-block[data-id="'+target+'"] .product-image a').attr({ 'href' : product_permalink, 'title' : product_name_only });
		$('.edit-content-block[data-id="'+target+'"] .product-image img').attr('src', product_img);
		$('.edit-content-block[data-id="'+target+'"] .product-name').html(product_name);
		$('.edit-content-block[data-id="'+target+'"] .product-price').html(product_price);

		// Close the block editor
		close_edit_ui();

		// Sync to server
		sync_update( target, 'product', { product_id : product_id } );
	});

	/**
	 * Load more products
	*/
	$('#load-more-products').click(function(e){
	 	e.preventDefault();

	 	var button = $(this);
	 	var paged = button.attr( 'data-paged' );

	 	// Enter loading state
	 	$('#load-more-products').hide();
	 	$('#loading-more-products').show();

	 	// Get more products
	 	$.ajax({
	 		type : 'GET',
	 		url : wcng_params.endpoint,
	 		data : {
	 			method 	: 'get_products',
	 			post_id : wcng_params.post_id,
	 			_n 		: wcng_params._n_get_products,
	 			args 	: {
		 			paged : paged,
	 			}
	 		}
	 	}).done(function(response){
	 		var data = $.parseJSON( response );

	 		if( data == 'all_loaded' ){
	 			// Notify user if all products information has been loaded
	 			alert( wcng_params.label_products_have_been_displayed );

	 			$('#loading-more-products').hide();
	 		} else if( data != false ){
	 			// Append the data to product list
	 			for (var i = 0; i < data.length; i++) {
	 				// Prepare template
	 				product_item = $('#template-product-item').clone().html();

	 				// Append product item to product list
	 				$('#select-product-list').append( product_item );

	 				// Insert data to template
	 				if( data[i].image ){
		 				$('#select-product-list li:last .image-wrap').html('<img src="'+data[i].image+'" alt="'+data[i].title+'" />');
	 				}
	 				$('#select-product-list li:last .product-name a').attr({ 'href' : data[i].permalink, 'title' : data[i].title }).text( data[i].title );
	 				$('#select-product-list li:last .product-price').html(data[i].price);
	 				$('#select-product-list li:last .select-this-product').attr('data-product-id', data[i].id );
	 			};

	 			// +1 to paged data
	 			paged = parseInt( paged ) + 1;	 			
	 			button.attr( 'data-paged', paged );

	 			// End loading state
			 	$('#load-more-products').show();
			 	$('#loading-more-products').hide();
	 		} else {
	 			// Something isn't right
	 			alert( wcng_params.label_error_getting_data );
	 		}
	 	});
	});

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

		// Unlock the position of the window
		$('body').css({ 'overflow' : 'auto' });

		// Close edit form
		$('.edit-block-form').hide();
	}

	// Sync update to server
	function sync_update( block_id, mode, args ){
		// Start loading state
		loading_start( wcng_params.loading_message_update + block_id );

		// Sending data..
		$.ajax({
			url : wcng_params.endpoint,
			type : 'POST',
			data : {
				method 			: 'update',
				_n 				: wcng_params._n_update,
				post_id 	: wcng_params.post_id,
				block_id 		: block_id,
				mode 			: mode,
				args 			: args
			}
		}).done(function(response){
			data = $.parseJSON( response );

			// End loading state
			loading_end( wcng_params.loading_message_update_end );
		});
	}

	// Start loading
	function loading_start( message ){
		$('#loading-indicator p').text( message );
		$('#loading-indicator').velocity( 'fadeIn' );
	}

	// End loading
	function loading_end( message ){
		$('#loading-indicator p').text( message );
		$('#loading-indicator').velocity( 'fadeOut', {
			delay: 1000,
			complete: function(){
				$('#loading-indicator p').text( '' );
			}
		});
	}
});