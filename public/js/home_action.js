function searchCount(domaine, route, filtre) {
    var selecteurCompletOverlay="#overlay_" + domaine;
    var selecteurCompletBadge="#badge_" + domaine;
    $(selecteurCompletOverlay).removeClass('d-none');

    $.ajax({
        method: "POST",
        url: route + '/' + filtre,
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