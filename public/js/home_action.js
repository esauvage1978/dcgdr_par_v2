function searchCount(domaine, route, filtre) {
    var selecteurCompletOverlay="#overlay_" + domaine;
    var selecteurCompletBadge="#badge_" + domaine;
    var selecteurAll='#' + domaine;

    $(selecteurCompletOverlay).removeClass('d-none');

    $.ajax({
        method: "POST",
        url: route + '/' + filtre,
        data: {},
        dataType: 'json',
        success: function (json) {

            if (json >  0) {
                $(selecteurCompletBadge).text(json);
            } else {
                $(selecteurAll).remove();
            }
            $(selecteurCompletOverlay).addClass('d-none');
        }
    });
}

function searchCountAxe(axeId, route,) {
    var selecteurCompletOverlay="#overlay_axe_" + axeId;
    var selecteurCompletBadge="#axe_" + axeId;
    $(selecteurCompletOverlay).removeClass('d-none');

    $.ajax({
        method: "POST",
        url: route + '/' + axeId,
        data: {},
        dataType: 'json',
        success: function (json) {

            if (json ===  0) {

                $(selecteurCompletBadge).text(json + ' action');

            } else if (json >  0) {

                $(selecteurCompletBadge).text(json + ' actions');

            }

            $(selecteurCompletOverlay).addClass('d-none');
        }
    });
}