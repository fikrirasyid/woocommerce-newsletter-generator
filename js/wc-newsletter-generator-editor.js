jQuery(document).ready(function($){
	var preview_url = $('.preview.button').attr('href');

	$('#newsletter-preview').html('<iframe src="'+ preview_url +'" width="100%" height="550"></iframe>');
});