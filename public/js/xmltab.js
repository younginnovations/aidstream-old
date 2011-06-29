$(document).ready(function() {
    $('.xmltab .url').addClass('active');
    $('.xmltab .url a').click(urltab);
    $('.xmltab .file a').click(urlfile);

    function urltab() {
        $(this).parent().parent().children('li').removeClass('active');
        $(this).parent('.url').addClass('active');
        $('.form-container .xml-url').show();
        $('.form-container .xml-file').hide();
        $(this).parent().parent().children('li.file').removeClass('active');
        return false;
    }
    
    function urlfile() {
        $(this).parent().parent().children('li').removeClass('active');
        $(this).parent('.file').addClass('active');
        $('.form-container .xml-url').hide();
        $('.form-container .xml-file').show();
        $(this).parent().parent().children('li.url').removeClass('active');
        return false;
    }
});