$(document).ready(function(){
        
    $('.element-title').click(function(){
        $that = $(this);
        $(this).toggleClass('dropdown');
                
        if ($that.siblings('.highlight').css('display') == 'none') {
            $that.siblings('.highlight').css('display','block');					
            $that.parent().siblings().find('.element-title').removeClass('dropdown');
            $that.parent().siblings().find('.element-title').siblings('.highlight').css('display','none')
        }                
        else {
            $(this).siblings('.highlight').hide();
        }           
	
    });

    $('.arrow li span.down-arrow').click(function(){
        $that = $(this); 
        $(this).closest('div').siblings().find('li span').removeClass('dropdown-level');
        $(this).toggleClass('dropdown-level');
        if ($that.siblings('ul').css('display') == 'none') {
            $that.siblings('ul').show();
            $that.closest('div').siblings().find('li ul').hide();
        }
        else {
            $(this).siblings('ul').hide();
        }		
    });   
    
    $('.arrow li span.transaction-down-arrow ').click(function(){
        $that = $(this);      
        $(this).closest('div').siblings().find('span.transaction-down-arrow ').removeClass('dropdown-level');
        $(this).toggleClass('dropdown-level');
        if ($that.siblings('#view-activity-transaction').css('display') == 'none') {
            // Used to load transaction element through ajax    
            // Get activity id
            var id = $that.find('#get-activityId').html();
            // Get index of an array
            var index = $that.find('#get-index').html();
            $that.siblings('#view-activity-transaction').load(APP_BASEPATH +'/wep/transaction?id=' + id + '&index=' + index);
             
            $that.siblings('#view-activity-transaction').css('display','');
            $that.closest('div').siblings().find('#view-activity-transaction').css('display','none');
        }
        else {
            $(this).siblings('#view-activity-transaction').hide();
        }		
    });   
});
