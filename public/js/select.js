$(document).ready(function() {
    $("#sector").select2({
        placeholder: "Select Anyone"
    });
    
    $(".form-select").select2({
        placeholder: "Select Anyone"
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
});