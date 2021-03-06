jQuery(document).ready(function($){
	var newsletter_preview 	= $('#newsletter-preview');
	var preview_url 		= newsletter_preview.attr( 'data-preview-url' );
	var endpoint 			= newsletter_preview.attr( 'data-endpoint' );
	var post_id 			= newsletter_preview.attr( 'data-post-id' );
	var nonce 				= newsletter_preview.attr( 'data-nonce' );
	var selected_template 	= $('#wcng_select_template option:selected').val();

	// If this post already have template on page load
	if( '' != selected_template ){
		$('#newsletter-preview').html('<iframe src="'+ preview_url +'" width="100%" height="550"></iframe>');
	}

	// Trigger auto-draft to draft status change when the template is selected
	$('body').on('change', '#wcng_select_template', function(){

		var selected_option = $(this).find('option:selected').val();

		// Only do this if selected isn't empty
		if( selected_option != '' ){
			// Displaying loading preview
			if( $('#newsletter-preview').is(':hidden') ){
				newsletter_preview.slideDown();				
			}

			// Do AJAX call to change the post's status
			$.ajax({
				type : 'POST',
				url : endpoint,
				data : {
		 			_n 				: nonce,
		 			method 			: 'change_template',
		 			post_id 	: post_id,
		 			args 			: { 
		 				template : selected_option, 
		 				post_id : post_id 
		 			}				
				}
			}).done(function( response ){
				var data = $.parseJSON( response );

				if( data != false ){
					$('#newsletter-preview').html('<iframe src="'+ data +'" width="100%" height="550"></iframe>');
				}
			});

		}
	});

	// Load HTML Code
	if( $('#newsletter-markup').length > 0 ){
		var markup = $('#newsletter-markup');
		var markup_permalink = markup.attr( 'data-permalink' );

		$.get( markup_permalink, function( response ){

			// Some web mail service don't load image which is served using relative protocol. Translate relative protocol into http / https
			if( document.location.protocol == 'https:' ){
				response = response.replace( new RegExp( "<img src='//", 'g' ), "<img src='https://" );
				response = response.replace( new RegExp( '<img src="//', 'g' ), '<img src="https://' );
			} else {
				response = response.replace( new RegExp( "<img src='//", 'g' ), "<img src='http://" );
				response = response.replace( new RegExp( '<img src="//', 'g' ), '<img src="http://' );				
			}

			markup.val( response );
		});
	}
});