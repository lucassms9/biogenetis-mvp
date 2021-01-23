$(document).ready(function() {
    $('#user-type-id').change(function (e) {
        e.preventDefault();
        fieldsDiagnosticos();
    });
    fieldsDiagnosticos();
});

function fieldsDiagnosticos() {
    const value = $('#user-type-id').children("option:selected").val();

    if(value == 3){
        $('#showInputs').show();
    }else{
        $('#showInputs').hide();
    }
}
