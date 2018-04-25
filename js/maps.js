$(document).ready(function(){
	$('#shiso_button').click(function() {
		$('#shiso').fadeIn('slow');
	});

	$('#shiso').click(function() {
		$(this).fadeOut('slow');
	});
});