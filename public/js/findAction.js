function showListe(selecteurSource, route, selecteurDestination) {
    var id = $(selecteurSource).val();
    //$(selecteurCompletOverlay).removeClass('d-none');

    $.ajax({
        method: "POST",
        url: route ,
        data: {'id': id},
        dataType: 'json',
        success: function (json) {
            var result='';
            $.each(json, function (index, value) {
                result += '<li  class=\"list-group-item\">' + value.ref + '-' + value.name +'</li>';
            });
            $(selecteurDestination).html(result);
            // $(selecteurCompletOverlay).addClass('d-none');
        }
    });
}
