
function searchCount(domaine, route, filtre) {
    $("#overlay_" + domaine).removeClass('d-none');

    $.ajax({
        method: "POST",
        url: route + '/' + filtre,
        data: {},
        dataType: 'json',
        success: function (json) {
            $("#overlay_" + domaine).addClass('d-none');
            if (json >  0) {
                $("#badge_" + domaine).text(json);
            } else {
                $('#' + domaine).remove();
            }

        }
    });
}

function searchCountAxe(axeId, route) {
    $("#overlay_axe_" + axeId).removeClass('d-none');

    $.ajax({
        method: "POST",
        url: route + '/' + axeId,
        data: {},
        dataType: 'json',
        success: function (json) {
            $("#overlay_axe_" + axeId).addClass('d-none');
            if (json ===  0) {
                $("#axe_" + axeId).text(json + ' action');
            } else if (json >  0) {
                $("#axe_" + axeId).text(json + ' actions');
            }
        }
    });
}

