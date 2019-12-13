function create_deployement(selecteurSource, route) {
    var selecteurComplet="#" + selecteurSource;
    var selecteurCompletOverlay="#overlay_" + selecteurSource;
    var selecteurCompletHead="#head_" + selecteurSource;
    $(selecteurCompletOverlay).removeClass('d-none');
    $( selecteurCompletHead ).fadeOut(4000);
    var res=selecteurSource.split('_');
    var indicator_id=res[0];
    var deployement_id=res[1];
    $.ajax({
        method: "POST",
        url: route,
        data: {'indicator_id': indicator_id, 'deployement_id': deployement_id},
        dataType: 'json',
        success: function (json) {

            if (json) {
                $(selecteurComplet).removeClass('card-warning').addClass('card-success');
            } else {
                $(selecteurComplet).removeClass('card-success').addClass('card-warning');
            }
            $( selecteurCompletHead ).fadeIn();
            $(selecteurCompletOverlay).addClass('d-none');
        }
    });
}