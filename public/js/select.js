$(document).ready(function() {
    $("#sector").select2({
        placeholder: "Select Anyone"
    });
    
    $(".form-select").select2({
        placeholder: "Select Anyone"
    });
    
    $("#funding_org").select2({
        tags :['test' , 'one' , 'two']
    })
});