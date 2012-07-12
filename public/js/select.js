function loadSelect2()
{
    $("#sector").select2({
        placeholder: "Select Anyone"
    });
    
    $("select.form-select").select2({
        placeholder: "Select Anyone"
    });
    
    // added for sector vocabulary in sector edit page.
    $(".vocabulary_value").bind('change' , function () {
        var vocab = $('.vocabulary_value').val();
        if(vocab == '' || vocab == 3){
            $('.non_dac_code').attr('value' , '');
            $('.sector_value').parent().css('display' , 'block');
            $('.non_dac_code').parent().css('display' , 'none'); 
        } else {
            $('.sector_value').attr('value' , '');
            $('.sector_value').parent().css('display' , 'none');
            $('.non_dac_code').parent().css('display' , 'block');
        }
    });
    
    if($('#funding_org').length != 0){
        fundingOrgData = null;
        $.ajax({
            url: APP_BASEPATH + '/simplified/ajax/get-funding-orgs',
            success: function(data) {
                orgs = data.split(';');
                $("#funding_org").select2({
                    tags : orgs
                })
            },
            error: function() {
                alert('error');
                $("#funding_org").select2({
                    tags : []
                });
            }
        });
    }
}