$(document).ready(function() {
    $('.activity-subheader .expand-collapse-all').click(fixHeader);
    
    function fixHeader() {
        var headerHeight = $(this).parent('.activity-subheader').height();
        //console.log(headerHeight);
        var fixedHeight = ($('#header').height())+($('#navigation').height())+($('.page-content .form-container').height())+($('.page-content .general-info').height())+($('.page-content .table-header').height());
        //console.log(fixedHeight);
        //calculate the no of preceeding siblings
        var siblingCount = $(this).parent('.activity-subheader').parent().prevAll();
        //console.log(siblingCount.length);
        var finalScrollHeight = 0;
        //console.log(siblingCount.length);
        if(siblingCount.length > 0) {
            siblingCount.each(function(index, element) {
                if($(siblingCount[index]).children('.activity-subheader').children('.expand-collapse-all').text().trim() == 'Collapse All') {
                    var temp1 = $(siblingCount[index]).children('.activity').height();
                    //console.log($(siblingCount[index]).children('.activity').height());
                    finalScrollHeight = finalScrollHeight + temp1;
                }
            });
            //console.log(finalScrollHeight);
            finalScrollHeight = finalScrollHeight + fixedHeight + (headerHeight*(siblingCount.length));
        }
        else {
            finalScrollHeight = fixedHeight + (2*headerHeight);
        }
        //console.log(finalScrollHeight);
        //Clone the header and prepend to activity-title
        var header;
        if($(this).text().trim() == 'Collapse All') {
        header = $(this).parent('.activity-subheader').clone().prependTo($(this).parent('.activity-subheader').parent());
        header.wrap('<div class="header-wrapper" />');
                $('.header-wrapper').hide();
        $('.header-wrapper').css({'display' : 'none',
                                 'position' : 'fixed',
                   'top' : '0px',
                   'left' : '0px',
                   'width' : '100%'});
        header.css({'background-color' : '#fff',
                   'border' : '1px solid #B5B5B5',
                   'border-bottom' : '1px solid #ccc',
                   'padding' : '10px 0 10px 5px',
                    'margin' : '0px auto',
                   'width' : '933px'});
        //console.log($(this).parent().siblings('.activity').height());
        }
        else {
            $(this).parent().siblings('.header-wrapper').remove();
            //console.log($(this).parent().siblings('.activity').height());
        }
        //height of the expanded activity div
        var activityHeight
        if ($(this).parent().parent().children('.activity').is(":visible")) {
         activityHeight = $(this).parent().parent().children('.activity');
        }
        //console.log(activityHeight);
        //console.log(activityHeight);
        //var maxHeight = finalScrollHeight + activityHeight;
        //console.log(maxHeight);
        //console.log(finalScrollHeight);
        
        //clone the tite of each row
        $(document).scroll(function() {
		console.log(activityHeight.height());
		console.log(finalScrollHeight + activityHeight.height());
            if(($(this).scrollTop() >= finalScrollHeight) &&  ($(this).scrollTop() <= (finalScrollHeight + activityHeight.height()))){
                $('.header-wrapper').show();
                	//Make the buttons of sticky header functional
                //console.log($(".header-wrapper"));
            }
            
            else {
            $('.header-wrapper').hide();
                //header.hide();
            }
        });

        //to make the links of cloned sticky header work
        $(".header-wrapper .activity-subheader .expand-collapse").click(function() {
		$(this).parent().parent().siblings(".activity-subheader").children(".expand-collapse").click();
                $(this).parent().parent('.header-wrapper').remove();
        });
        
        $(".header-wrapper .activity-subheader .expand-collapse-all").click(function() {
		$(this).parent().parent().siblings(".activity-subheader").children(".expand-collapse-all").click();
        });
        
        $(".header-wrapper .activity-subheader .expand-collapse-title").click(function() {
		$(this).parent().parent().parent().parent().siblings(".expand-collapse").click();
        });
        
        //remove header wrapper when minimize
        $(".activity-subheader .expand-collapse").click(function() {
            $(this).parent().siblings('.header-wrapper').remove();
        });
    }

});
