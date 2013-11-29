function loadSelect2()
{
    $('select.location.level-1').location();
    $('select.location.level-1').bind('change' , function(event){
        var distEle = $(this);
        var dist = distEle.val();
        $('select.location.level-2' , $(this).parents('.form-wrapper').first()).location({
            level : '2',
            district: dist
        });
    });
    

    $("select#sector").select2({
        placeholder: "Select Anyone"
    });
    
    $("select.form-select").select2({
        placeholder: "Select Anyone"
    });
    
    $(".vocabulary_value").bind('change' , function (event) {
        var vocabulary = $(this);
        var vocab = vocabulary.val();
        var wrapperEle = vocabulary.parents('.form-wrapper').first();
        if(vocab == '' || vocab == 3){
            $('.non_dac_code' , wrapperEle).val('');
            $('select.sector_value' , wrapperEle).parent().css('display' , 'block');
            $('.non_dac_code' , wrapperEle).parent().css('display' , 'none'); 
        } else {
            $('.non_dac_code' , wrapperEle).val('');
            $('div.sector_value .select2-choice span' , wrapperEle).html('');
            $('select.sector_value option:selected' , wrapperEle).removeAttr("selected");
            $('select.sector_value' , wrapperEle).parent().css('display' , 'none');
            $('.non_dac_code' , wrapperEle).parent().css('display' , 'block');
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

function setSelect2Data(id , value)
{
    $('#'+id).select2('val' , value);
}

function addCountryChange(id , map)
{
    $('select#'+id).bind('change' , function(event){
        changeCountry(id , map)
    });
}

function changeCountry(id , map)
{
    $.ajax({
        url: APP_BASEPATH + '/ajax/get-country',
        data : {id : $('select#'+id).val()},
        success: function(data) {
            updateMapData(map , data);
        },
        error: function() {
            alert('sorry some error occured');
        }
    });
}
function updateMapData(map , country)
{
    $.ajax({
            url: 'http://open.mapquestapi.com/nominatim/v1/search.php',
            data : { format : 'json' , q : country , limit : 1},
            parseOnLoad : true,
            success: function(data) {
                if(typeof data !='object'){
                    data = JSON.parse(data);
                }
                var bb = data[0]['boundingbox'];
                map.fitBounds([[bb[0] , bb[3]] , [bb[1] , bb[2]]]);
            },
            error: function(e) {
               alert('sorry could not connect to map');
               console.log(error);
            }
        });
}