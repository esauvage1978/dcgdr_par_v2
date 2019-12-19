function showListe(selecteurSource, route, selecteurDestination) {
    var id = $(selecteurSource).val();

    $.ajax({
        method: "POST",
        url: route ,
        data: {'id': id},
        dataType: 'json',
        success: function (json) {
            var result='';
            $.each(json, function (index, value) {
                result += '<div class=\"card bg-light\"><div class=\"card-header text-muted border-bottom-0\">' + value.ref + '-' + value.name +'</div></div>';
            });
            $(selecteurDestination).html(result);
        }
    });
}
