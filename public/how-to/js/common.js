$(document).ready(function(){
    $('body').click(function(evt){
        var ele = $(evt.target);
        if(!ele.is('input') && ele.attr('id') != 'login-register-popup' && ele.closest('div').attr('id') != 'login-register-popup'){
            $('#login-form-wrapper').css('display' , 'none');
        }
    })
    
    $('#login-register-popup').click(function(evt){
        evt.preventDefault();
        $('#login-form-wrapper').toggle();
    });

        $('#TOC ul li a').click(function(evt){
                var el = $(this).attr('href');
                var elWrapped = $(el);
                evt.preventDefault();
                scrollToDiv(elWrapped,146);
                
                return false;
        
        });
        
        function scrollToDiv(element,navheight){
        
                var offset = element.offset();
                var offsetTop = offset.top;
                var totalScroll = offsetTop-navheight;
                
                $('body,html').animate({
                                scrollTop: totalScroll
                }, 900);
        
        }

})
