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
        if (vocab == '' || vocab == 3 || vocab == 8) {
            $('.non_dac_code', wrapperEle).val('text');
            $('select.sector_value', wrapperEle).parent().css('display', 'block');
            $('.non_dac_code', wrapperEle).parent().css('display', 'none');
        } else {
            $('.non_dac_code', wrapperEle).val();
            $('div.sector_value .select2-choice span', wrapperEle).html('');
            $('select.sector_value option:selected', wrapperEle).removeAttr("selected");
            $('.sector_value', wrapperEle).val('1');
            $('select.sector_value', wrapperEle).parent().css('display', 'none');
            $('.non_dac_code', wrapperEle).parent().css('display', 'block');
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

$(document).ready(function(){
    $('form input.non_dac_code').each(function(){
        var dis = $(this);
        var main = dis.parent().parent().find('select.sector_value');
        if(dis.val() == 'Null' || dis.val().length <= 1 || dis.val() == null) {
            dis.val('text');
        } else {
            $(main).find('option:last-child').attr("selected", "selected");
        }
    });

    //js for transaction
    var hideElement = $("#transaction div:nth-of-type(10)").nextAll("#transaction div");
    $(hideElement).hide();
    $(".show_advance").click(function(){
        if($.trim($(this).html()) == "Show Advance Elements" ) {
            $(hideElement).show("slow");
            $(this).removeClass(); 
            $(this).html('Hide Advance Elements');
        }
        else {
            $(hideElement).hide("slow");
            $(this).html('Show Advance Elements');
        }       
    });  

});