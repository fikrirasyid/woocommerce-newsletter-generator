jQuery(document).ready(function($){
	var preview_url 		= $('.preview.button').attr( 'href' );
	var newsletter_preview 	= $('#newsletter-preview');
	var endpoint 			= newsletter_preview.attr( 'data-endpoint' );
	var post_id 			= newsletter_preview.attr( 'data-post-id' );
	var nonce 				= newsletter_preview.attr( 'data-nonce' );

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
		 			newsletter_id 	: post_id,
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
});