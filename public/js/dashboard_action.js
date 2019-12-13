function searchCount(state, route) {
    var selecteurCompletOverlay="#overlay_" + state;
    var selecteurCompletBadge="#badge_" + state;
    $(selecteurCompletOverlay).removeClass('d-none');

    $.ajax({
        method: "POST",
        url: route + '/' + state,
        data: {},
        dataType: 'json',
        success: function (json) {

            if (json >  0) {
                $(selecteurCompletBadge).text(json);

            }
            $(selecteurCompletOverlay).addClass('d-none');
        }
    });
}