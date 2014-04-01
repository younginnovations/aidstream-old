$(document).ready(function () {
    //Trigger click expand-collpse when title is clicked
    $(".expand-collapse-title").click(function () {
        $(this).parent().parent().parent().parent().siblings(".expand-collapse").click();
    });

    $(".expand-collapse").click(function () {
        if ($(this).attr("class") == "expand-collapse expand-all") {
            $(this).siblings(".expand-collapse-all").text("Collapse All");
        }
        if ($(this).text().trim() == '+') {
            $(this).text('-');

            $(this).parent().siblings().slideDown("slow");
        } else {
            if ($(this).attr("class") == "expand-collapse expand-all") {
                $(this).siblings(".expand-collapse-all").text("Expand All");
                var a = $(this).parent().siblings(".activity").find(".expand-collapse");

                a.each(function (index, element) {
                    var expand = $(a[index]).siblings().children().attr("class");
                    $(a[index]).text("+");

                    $(a[index]).parent().siblings().slideUp("slow");
                });
                $(this).parent().siblings(".activity").slideUp("slow");
                $(this).siblings(".expand-collapse").text("+");
                $(this).text('+');
            } else {
                $(this).text('+');

                $(this).parent().siblings().slideUp("slow");
            }
        }

    });

    $(".expand-collapse-all").click(function (event) {
        if ($(this).text().trim() == 'Expand All') {
            $(this).text('Collapse All');
            $(this).siblings(".expand-collapse").text("-");
            var a = $(this).parent().siblings(".activity").find(".expand-collapse");
            $(this).parent().siblings(".activity").slideDown("slow");
            a.each(function (index, element) {
                $(a[index]).text('-');
                $(a[index]).parent().siblings().slideDown("slow");
            });
        } else {
            $(this).text('Expand All');
            var a = $(this).parent().siblings(".activity").find(".expand-collapse");

            a.each(function (index, element) {
                var expand = $(a[index]).siblings().children().attr("class");
                $(a[index]).text("+");
                $(a[index]).parent().siblings().slideUp("slow");
            });
            $(this).parent().siblings(".activity").slideUp("slow");
            $(this).siblings(".expand-collapse").text("+");
        }
        return null;
    });
    /*$('.xmltab .url').addClass('active');
    $('.xmltab .url a').click(urltab);
    $('.xmltab .file a').click(urlfile);

    function urltab() {
        $(this).parent().parent().children('li').removeClass('active');
        $(this).parent('.url').addClass('active');
        $('.form-container .xml-url').show();
        $('.form-container .xml-file').hide();
        $(this).parent().siblings('file').removeClass('active');
        return false;
    }

    function urlfile() {
        $(this).parent().parent().children('li').removeClass('active');
        $(this).parent('.file').addClass('active');
        $('.form-container .xml-url').hide();
        $('.form-container .xml-file').show();
        $(this).parent().siblings('url').removeClass('active');
        return false;
    }

*/
});