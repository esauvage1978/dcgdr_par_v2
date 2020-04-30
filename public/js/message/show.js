$(function () {
    $('.hide').hide();
    $(".showOver")
        .mouseenter(function () {
            var span = '#' + $(this).attr('id') + '_span';
            $(span).fadeIn("slow", "linear");
        })
        .mouseleave(function () {
            var span = '#' + $(this).attr('id') + '_span';
            $(span).fadeOut("slow", "linear");
        })
});