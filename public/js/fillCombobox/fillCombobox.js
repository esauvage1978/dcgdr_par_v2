
function fillComboboxChained(selecteurSource, selecteurDestination, route, appelEnCascade, addReference, selectedId="") {
        var id = $(selecteurSource).val();
    if (id == null) return;

    $(selecteurDestination).empty();

    $.ajax({
        method: "POST",
        url: route,
        data: {'id': id, 'enable': 'all'},
        dataType: 'json',
        success: function (json) {
            var selected='';
            $.each(json, function (index, value) {
                if(selectedId === value.id ) {
                    selected='selected';
                } else {
                    selected='';
                }
                $(selecteurDestination).append('<option ' + selected + ' value="' + value.id + '">' +
                    (addReference ? value.ref + ' - ' : '')
                    + value.name + '</option>');
            });
            if (appelEnCascade) {
                $(selecteurDestination).change();
            }
        }
    });
}

function fillCombobox(selecteur, route, appelEnCascade, selectedId="") {

    $(selecteur).empty();
    $.ajax({
        method: "POST",
        url: route,
        data: {'enable': 'all', 'archiving': 'all'},
        dataType: 'json',
        success: function (json) {
            var selected='';
            $.each(json, function (index, value) {
                if(selectedId === value.id ) {
                    selected='selected';
                } else {
                    selected='';
                }
                $(selecteur).append('<option ' + selected + ' value="' + value.id + '">'
                    + value.name + '</option>');
            });
            if (appelEnCascade) {
                $(selecteur).change();
            }
        }
    });
}

