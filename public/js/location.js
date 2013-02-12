( function($) {    
    var divisions = '';
    var districtProperties = '';
    $.fn.location = function (params){
        var defaults = {
            'country' : 'np',
            'district'  : '',
            'level' : 1
        };
        var settings = $.extend( defaults , params );
        
        return this.each( function () {
            if(settings.level == 1){
                fetch_country_divisions($(this));
            } else if(settings.level == 2){
                populate_district_divisions($(this));
            }
        });

        
        function fetch_country_divisions(ele)
        {
            if(divisions == ''){
                $.ajax({
                    url: "http://www.developmentcheck.org/geotag/"+settings.country+"/divisions/3",
                    dataType:'jsonp',
                    success: function(result) {
                        divisions = result;
                        populate_districts(ele);
                    }
                });
            } else {
                populate_districts(ele);
            }
        }
        
        function populate_districts(ele)
        {
            var options = '';
            var childLevel = settings.level + 1;
            var childDivision = $('select.location.level-'+childLevel , ele.parents('.form-wrapper').first());
            var eleValue = ele.val();

            ele.html('');
            ele.append($("<option>").attr('value','').text('Select Anyone'));
            $.each(divisions , function(key , division) {
                ele.append($("<option>").attr('value',division.name).text(division.name));
            });
            ele.val(eleValue);
            if(eleValue != ''){
                settings.district = eleValue;
                populate_district_divisions(childDivision);
            }
        }
        
        // Populate vdcs
        function populate_district_divisions(ele)
        {
            var elementValues = ele.val();
            if(divisions.length > 0){
                $.each(divisions , function(key , division) {                    
                    if(division.name == settings.district){
                        populate_parents(ele);// populate adm1 and adm2
                        populate_coordinates(ele);// populate coordinates
                        var locs = new Array();
                        ele.html('');
                        $.each(division.divisions.data , function(key , vdc) {
                            ele.append($("<option>").attr('value',vdc.name).text(vdc.name));
                        });
                    }
                });
                ele.val(elementValues);
            } else {
                alert('sorry divisions not found');
            }
        }
        
        function populate_parents(ele)
        {
            if(settings.district != ''){
                $.ajax({
                    url: "http://www.developmentcheck.org/geotag/"+settings.country+"/divisions/3/"+settings.district,
                    dataType:'jsonp',
                    success: function(result) {
                        district =  result;
                        $.each(result.parents , function(key , parent) {
                            if(parent.administrative_level == 1){
                                $('input.adm1' , ele.parents('.form-wrapper').first()).val(parent.division_name)
                            } else if (parent.administrative_level == 2){
                                $('input.adm2' , ele.parents('.form-wrapper').first()).val(parent.division_name)
                            }
                        });
                    }
                });
            }
        }
        
        function populate_coordinates(ele)
        {
            if(settings.district != ''){
                $.ajax({
                    url: "http://www.developmentcheck.org/geotag/"+settings.country+"/latlong/"+settings.district,
                    dataType:'jsonp',
                    success: function(result) {
                        $('input.latitude' , ele.parents('.form-wrapper').first()).val(result.lat);
                        $('input.longitude' , ele.parents('.form-wrapper').first()).val(result.lng);
                    }
                });
            }
        }
    }
    
})(jQuery);