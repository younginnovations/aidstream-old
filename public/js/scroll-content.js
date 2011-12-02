$(document).ready(function() {
	$(".nav a").click(function() {
		//removeActive();
		//$(this).addClass('active');
		var id = $(this).attr('href');
		$('html,body').stop().animate({scrollTop: $(id).offset().top}, 300,'swing');
		return false;
	});

	var what = $('#What-is-AidStream').height();
	var how = what + $('#How-to-use-AidStream').height();

	$(document).scroll(function() {
        if(($(this).scrollTop() >= what) && ($(this).scrollTop() < how)) {
			removeActive();
           $("#how-link").addClass('active');
        }
	else if($(this).scrollTop() >= how) {
		removeActive();
           $("#contact-link").addClass('active');
        }
	else{
		removeActive();
           $("#what-link").addClass('active');
	}
    });
})

function removeActive()
{
		$(".nav li").each(function(index, element) {
			$(element).children('a').removeClass('active');
		});
}
