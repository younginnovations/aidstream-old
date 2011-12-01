$(document).ready(function() {
	$(".nav a").click(function() {
		var id = $(this).attr('href');
		$('html,body').stop().animate({scrollTop: $(id).offset().top}, 1000);
		return false;
	});
})
