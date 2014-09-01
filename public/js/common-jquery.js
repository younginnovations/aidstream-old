$(document).ready(function () {
    $('body').click(function (evt) {
        var ele = $(evt.target);
        if (!ele.is('input') && ele.attr('id') != 'login-register-popup' && ele.closest('div').attr('id') != 'login-register-popup') {
            $('#login-form-wrapper').css('display', 'none');
        }
    })

    $('#login-register-popup').click(function (evt) {
        evt.preventDefault();
        $('#login-form-wrapper').toggle();
    });

    $('#tabs li a:not(:first)').addClass('inactive');
    $('.tabs-container').hide();
    $('.tabs-container:first').show();

    $('#tabs li a').click(function () {
        var t = $(this).attr('id');
        if ($(this).hasClass('inactive')) { //this is the start of our condition 
            $('#tabs li a').addClass('inactive');
            $(this).removeClass('inactive');

            $('.tabs-container').hide();
            $('#' + t + 'C').show();
        }
    });

    // Reporting Org Change Detection
    var oldData = $("#fieldset-reporting_org_info :input[value!='']").serialize();
    if (oldData != '') {
        $('.defaults-forms form').one('submit', function (event) {
            var newData = $('#fieldset-reporting_org_info :input').serialize();
            if (oldData != newData) {
                event.preventDefault();
                var confirmDialog = confirm('You have changed the reporting organisation information. Your changes are saved in the settings. Do you want to update your activities and publish them?');
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

    // Select all xml files for validation
    $('.selectallactivity').click(function () {
        if ($(this).is(':checked')) {
            $('#activity-xml input').attr('checked', true);
        } else {
            $('#activity-xml input').attr('checked', false);
        }
    });

    $('.selectallorganisation').click(function () {
        if ($(this).is(':checked')) {
            $('#organisation-xml input').attr('checked', true);
        } else {
            $('#organisation-xml input').attr('checked', false);
        }
    });

    $('.selectalltransaction').click(function(){
        if ($(this).is(':checked')) {
            $('.activity-list-table input').attr('checked', true);
        } else {
            $('.activity-list-table input').attr('checked', false);
        }
    })
    
    //$('#contain-body').css('min-height', $(window).height() - 170 + 'px');

    // List Organisation Live Filter
    $('#organisation-input').livefilter({selector: '#list-org tbody tr'});

    // Demo Info Display
    $(window).scroll(function(e){ 
        $el = $('#demo-info-display'); 
        if ($(this).scrollTop() > 102 && $el.css('position') != 'fixed') { 
            $('#demo-info-display').css({'position': 'fixed', 'top': '0px'}); 
        } else if ($(this).scrollTop() < 102 && $el.css('position') == 'fixed') { 
            $('#demo-info-display').css({'position': 'static', 'top': '0px'}); 
        }
    });
});