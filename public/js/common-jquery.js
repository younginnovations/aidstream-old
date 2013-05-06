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
})