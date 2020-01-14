function changeTemplate()
{
    var indType = $('#indicator_indicatortype').val();
alert(indType);
    $('#row_goal').addClass('d-none');
    $('#row_goal').empty();
    $('#row_value').addClass('d-none');
    $('#row_value').empty();
    $('#row_alert_goal').addClass('d-none');
    $('#row_alert_value').addClass('d-none');

    switch (indType) {
        case 'quantitatif':
            $('#row_goal').removeClass('d-none');
            $('#row_value').removeClass('d-none');
            $('#row_alert_goal').removeClass('d-none');
            $('#row_alert_value').removeClass('d-none');
            break;
        case 'qualitatif':
            $('#row_value').removeClass('d-none');
            $('#row_alert_value').removeClass('d-none');
            break;
        case 'qualitatif_palier_5':
            $('#row_value').removeClass('d-none');
            $('#row_alert_value').removeClass('d-none');
            break;
    };
}