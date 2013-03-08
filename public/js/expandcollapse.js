$(document).ready(function(){
        
    $('.element-title').click(function(){
        $that = $(this);
        $(this).toggleClass('dropdown');
                
        if ($that.siblings('.highlight').css('display') == 'none') {
            $that.siblings('.highlight').css('display','block');	
        }                
        else {
            $(this).siblings('.highlight').hide();
        }           
	
    });

    $('.arrow li span.down-arrow').click(function(){
        $that = $(this); 
        $(this).toggleClass('dropdown-level');
        if ($that.siblings('ul').css('display') == 'none') {
            $that.siblings('ul').show();
        }
        else {
            $(this).siblings('ul').hide();
        }		
    });  
    
    $('.arrow li span.ajaxElement-down-arrow ').click(function(){
        $that = $(this);     
        $(this).toggleClass('dropdown-level');
        if ($that.siblings('#view-activity-ajaxElement').css('display') == 'none') {
            // Used to load transaction element through ajax    
            // Get activity id
            var id = $that.find('#get-elementId').html();
            // Get index of an array
            var index = $that.find('#get-index').html();
            // Get class name 
            var className = $that.find('#get-className').html();
            $that.siblings('#view-activity-ajaxElement').load(APP_BASEPATH +'/ajax/element?id=' + id + '&index=' + index + '&className=' + className);
             
            $that.siblings('#view-activity-ajaxElement').css('display','');
        }
        else {
            $(this).siblings('#view-activity-ajaxElement').hide();
        }		
    });
});
