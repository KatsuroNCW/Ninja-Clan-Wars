$(document).ready(function(){
	$('.page-up').click(function() {
		$("html, body").animate({ scrollTop: 0 }, 600);
	});

	$('.confirmation').click(function() {
		$(this).fadeOut('slow');
	});

	$('.error').click(function() {
		$(this).fadeOut('slow');
	});

	$('.topic-nav').click(function() {
    	$(this).children('.topic-menu').toggleClass('topic-menu--active');
    });

    $('.info-box__item').click(function() {
    	$(this).toggleClass('info-box__item--focus');
    });
});