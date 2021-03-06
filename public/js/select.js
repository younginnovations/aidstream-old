function loadSelect2() {
    $('select.location.level-1').location();
    $('select.location.level-1').bind('change', function (event) {
        var distEle = $(this);
        var dist = distEle.val();
        $('select.location.level-2', $(this).parents('.form-wrapper').first()).location({
            level: '2',
            district: dist
        });
    });

    $("select#sector").select2({
        placeholder: "Select one of the following option:"
    });

    $("select.form-select").select2({
        placeholder: "Select one of the following option:"
    });

    $(".vocabulary_value").bind('change', function (event) {
        var vocabulary = $(this);
        var vocab = vocabulary.val();
        var wrapperEle = vocabulary.parents('.form-wrapper').first();
        if (vocab == 8) {
            $('.non_dac_code', wrapperEle).val('text');
            $('.non_dac_code', wrapperEle).parent().css('display', 'none');
            $('div.sector_value .select2-choice span', wrapperEle).html('');
            $('select.sector_value option:selected', wrapperEle).removeAttr("selected");
            $('.sector_value', wrapperEle).val('1');
            $('select.sector_value', wrapperEle).parent().css('display', 'none');
            $('select.dac_three_code', wrapperEle).parent().css('display', 'block');
        } else if (vocab == 3 || vocab == '') {
            $('.non_dac_code', wrapperEle).val('text');
            $('.non_dac_code', wrapperEle).parent().css('display', 'none');
            $('div.dac_three_code .select2-choice span', wrapperEle).html('');
            $('select.dac_three_code option:selected', wrapperEle).removeAttr("selected");
            $('.dac_three_code', wrapperEle).val('1');
            $('select.dac_three_code', wrapperEle).parent().css('display', 'none');
            $('select.sector_value', wrapperEle).parent().css('display', 'block');
        } else {
            $('.non_dac_code', wrapperEle).val();
            $('.non_dac_code', wrapperEle).parent().css('display', 'block');
            $('div.sector_value .select2-choice span', wrapperEle).html('');
            $('select.sector_value option:selected', wrapperEle).removeAttr("selected");
            $('.sector_value', wrapperEle).val('1');
            $('select.sector_value', wrapperEle).parent().css('display', 'none');
            $('div.dac_three_code .select2-choice span', wrapperEle).html('');
            $('select.dac_three_code option:selected', wrapperEle).removeAttr("selected");
            $('.dac_three_code', wrapperEle).val('1');
            $('select.dac_three_code', wrapperEle).parent().css('display', 'none');
        }
    });


    //js for country budget item
    $(".iati_vocab").on('change', function (event) {
        var elem = $(this);
        setupCountryBudgetItemVocab(elem);
        if (elem.val() == '1') {//vocab
            $('.non_iati').val('text');
            $('.iati_value').val('');
        } else {
            $('.iati_value').val('1');
            $('.non_iati').val('');
        }
    });

    function setupCountryBudgetItemVocab(elem) {
        var vocabulary = elem;
        var vocab = vocabulary.val();

        var wrapperEle = $('.element-title-wrapper');
        if (vocab == '1') {
            $('.iati_value').parent().show();
            $('.non_iati').parent().hide();
            $('.non_iati').val('text');
        } else {
            $('.iati_value').parent().hide();
            $('.non_iati').parent().show();
            $('.iati_value').val('1');
        }
    }

    setupCountryBudgetItemVocab($(".iati_vocab"));

    if ($('#funding_org').length != 0) {
        fundingOrgData = null;
        $.ajax({
            url: APP_BASEPATH + '/simplified/ajax/get-funding-orgs',
            success: function (data) {
                orgs = data.split(';');
                $("#funding_org").select2({
                    tags: orgs
                })
            },
            error: function () {
                alert('error');
                $("#funding_org").select2({
                    tags: []
                });
            }
        });
    }
}

function setSelect2Data(id, value) {
    $('#' + id).select2('val', value);
}

$(document).ready(function () {

    $('form input.non_dac_code').each(function () {
        var dis = $(this);
        var main = dis.parent().parent().find('select.sector_value');
        var main_dac_three = dis.parent().parent().find('select.dac_three_code');
        var vocab = dis.parent().parent().find('select.vocabulary_value').val();
        if (dis.val() == 'Null' || dis.val().length <= 1 || dis.val() == null) {
            dis.val('text');
            if (vocab == 3) {
                $(main_dac_three).find('option:last-child').attr("selected", "selected");
            } else if (vocab == 8) {
                $(main).find('option:last-child').attr("selected", "selected");
            }
        } else {
            $(main).find('option:last-child').attr("selected", "selected");
            $(main_dac_three).find('option:last-child').attr("selected", "selected");
        }
    });

    //js for transaction
    var hideElement = $("#transaction div:nth-of-type(10)").nextAll("#transaction div");
    $(hideElement).hide();
    $(".show_advance").click(function () {
        if ($.trim($(this).html()) == "Show Advance Elements") {
            $(hideElement).show("slow");
            $(this).removeClass();
            $(this).html('Hide Advance Elements');
        }
        else {
            $(hideElement).hide("slow");
            $(this).html('Show Advance Elements');
        }
    });

    //js for form input check and leave page alert
    var changeInput = false;
    $("textarea,input").keyup(function(e) {
        var code = e.keyCode || e.which;
        if(code!= 13) { //Ignore Enter keycode
             changeInput = true;
        }      
       
    });

    var formcheck = document.querySelectorAll('form');
    var saveElement = document.getElementById('save-element');
    var saveviewElement = document.getElementById('save_and_view-element');

    if(saveElement) {
        document.querySelector('#save-element').addEventListener("click", function() {
            window.btn_clicked = true;
        });
    }

    if(saveviewElement) {
         document.querySelector('#save_and_view-element').addEventListener("click", function() {
            window.btn_clicked = true;
        });
    }

    window.onbeforeunload = function() {
        if(!window.btn_clicked && changeInput==true) {
            return 'You have unsaved changes.';
        }
    };
});