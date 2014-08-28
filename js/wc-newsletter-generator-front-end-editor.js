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
	$('#block-selector').on( 'click', '.select-this-product', function(e){
		e.preventDefault();

		// Get values
		var button = $(this);
		var product = button.parents('li');
		var target = $('#block-selector').attr('data-target');
		var product_id = button.attr('data-product-id');
		var product_img = product.find('.image-wrap img').attr('src');
		var product_name = product.find('.product-name').html();
		var product_name_only = product.find('.product-name a').text();
		var product_price = product.find('.product-price').html();
		var product_permalink = product.find('.product-name a').attr('href');

		// Update UI
		$('.edit-content-block[data-id="'+target+'"] .product-image a').attr({ 'href' : product_permalink, 'title' : product_name_only });
		$('.edit-content-block[data-id="'+target+'"] .product-image img').attr('src', product_img);
		$('.edit-content-block[data-id="'+target+'"] .product-name').html(product_name);
		$('.edit-content-block[data-id="'+target+'"] .product-price').html(product_price);

		// Close the block editor
		close_edit_ui();

		// Sync to server
	});

	/**
	 * Load more products
	*/
	$('#load-more-products').click(function(e){
	 	e.preventDefault();

	 	var button = $(this);
	 	var paged = button.attr( 'data-paged' );
	 	var nonce = button.attr( 'data-nonce' );
	 	var endpoint = $('#edit-product').attr('action');

	 	// Enter loading state
	 	$('#load-more-products').hide();
	 	$('#loading-more-products').show();

	 	// Get more products
	 	$.ajax({
	 		type : 'GET',
	 		url : endpoint,
	 		data : {
	 			_n : nonce,
	 			paged : paged,
	 			method : 'get_products'
	 		}
	 	}).done(function(response){
	 		var data = $.parseJSON( response );

	 		if( data == 'all_loaded' ){
	 			// Notify user if all products information has been loaded
	 			alert( 'All products have been displayed' );

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
	 			alert( 'Error getting error data. Please try again.');
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
});