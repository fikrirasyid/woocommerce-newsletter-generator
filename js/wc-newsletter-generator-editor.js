jQuery(document).ready(function($){
	var preview_url 		= $('.preview.button').attr( 'href' );
	var newsletter_preview 	= $('#newsletter-preview');
	var endpoint 			= newsletter_preview.attr( 'data-endpoint' );
	var post_id 			= newsletter_preview.attr( 'data-post-id' );
	var post_status 		= newsletter_preview.attr( 'data-status' );
	var nonce 				= newsletter_preview.attr( 'data-nonce' );

	// Trigger auto-draft to draft status change when the template is selected
	$('body').on('change', '#wcng_select_template', function(){

		var selected_option = $(this).find('option:selected').val();

		// Only do this if the current post status is "auto-draft" (which is made on post-new)
		// And the option selected isn't empty
		if( 'auto-draft' == post_status && selected_option != '' ){
			// Displaying loading preview
			newsletter_preview.slideDown();

			// Do AJAX call to change the post's status
			$.ajax({
				type : 'POST',
				url : endpoint,
				data : {
		 			_n 		: nonce,
		 			method 	: 'change_auto_draft',
		 			args 	: { post_id : post_id }				
				}
			}).done(function( response ){
				var data = $.parseJSON( response );

				console.log( data );
			});

		}
	});

	// $('#newsletter-preview').html('<iframe src="'+ preview_url +'" width="100%" height="550"></iframe>');
});