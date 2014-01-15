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

    // Reporting Org Change Detection
    var oldData = $("#fieldset-reporting_org_info :input[value!='']").serialize();
    if (oldData != '') {
        $('.defaults-forms form').one('submit', function(event){
            var newData = $('#fieldset-reporting_org_info :input').serialize();
            if (oldData != newData) {
                event.preventDefault();
                var confirmDialog = confirm('It seems you have changed your Reporting Org Information. Are you sure you want to continue?');
                if (confirmDialog == true) {
                    $(this).attr("action", "settings?btn=ok") 
                    $(this).submit();
                } else { 
                    $(this).submit();
                }
            } else {
                $(this).submit();
            } 
        });
    }

});