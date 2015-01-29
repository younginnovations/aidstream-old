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
        if (vocab == '' || vocab == 3) {
            $('.non_dac_code', wrapperEle).val('');
            $('select.sector_value', wrapperEle).parent().css('display', 'block');
            $('.non_dac_code', wrapperEle).parent().css('display', 'none');
        } else {
            $('.non_dac_code', wrapperEle).val('');
            $('div.sector_value .select2-choice span', wrapperEle).html('');
            $('select.sector_value option:selected', wrapperEle).removeAttr("selected");
            $('select.sector_value', wrapperEle).parent().css('display', 'none');
            $('.non_dac_code', wrapperEle).parent().css('display', 'block');
        }
    });

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