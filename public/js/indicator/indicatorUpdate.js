function changeTemplate()
{
    var indType = $('#indicator_indicatortype').val();
    $('#row_goal').addClass('d-none');
    $("#indicator_goal").prop('required',false);
    $('#row_value').addClass('d-none');
    $('#row_alert_goal').addClass('d-none');
    $('#row_alert_value').addClass('d-none');

    switch (indType) {
        case 'quantitatif':
            $('#row_goal').removeClass('d-none');
            $('#row_value').removeClass('d-none');
            $('#row_alert_goal').removeClass('d-none');
            $('#row_alert_value').removeClass('d-none');
            if ($('#indicator_value').val()=="") {
                $('#indicator_value').val('0');
            }
            break;
        case 'quantitatif_goal':
            $('#row_goal').removeClass('d-none');
            $("#indicator_goal").prop('required',true);
            $('#row_value').removeClass('d-none');
            $('#row_alert_goal').removeClass('d-none');
            $('#row_alert_value').removeClass('d-none');
            if ($('#indicator_value').val()=="") {
                $('#indicator_value').val('0');
            }
            break;
        case 'qualitatif':
            $('#row_value').removeClass('d-none');
            $('#row_alert_value').removeClass('d-none');
            $('#indicator_goal').val('100');
            $('#indicator_value').val('0');
            break;
        case 'qualitatif_palier_5':
            $('#row_value').removeClass('d-none');
            $('#row_alert_value').removeClass('d-none');
            $('#indicator_goal').val('100');
            $('#indicator_value').val('0');
            break;
        case 'qualitatif_palier_25':
            $('#row_value').removeClass('d-none');
            $('#row_alert_value').removeClass('d-none');
            $('#indicator_goal').val('100');
            $('#indicator_value').val('0');
            break;
    }
}