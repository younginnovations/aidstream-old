$(document).ready(function() {
    var header = $('#header').height();
    //alert($(document).height());
    var sheight = document.body.scrollHeight;
    //alert(sheight);
    $(window).resize(function() {
        //topPosition();
    });
    //console.log(window);
    
    $('a[href=#top]').click(function() {
                $('html, body').animate({scrollTop:0}, 'slow');
                //alert('works');
                return false;
    })
    
    $(document).scroll(function() {
        if($(this).scrollTop() >= header) {
            $('#backtotop').show().animate({right: '0'}, 'fast');
        }
        else {
            $('#backtotop').animate({right: '-110'}, 'fast');
        }
    });
});