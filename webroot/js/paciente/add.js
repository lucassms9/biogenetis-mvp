
$(document).ready(function() {
    $('#submitformpaciente').click(function (e) {
        e.preventDefault();
        submitformpaciente()
    });
});

function submitGetPaciente(tipo){

    $('#tipofiltro').val(tipo);
   if(tipo === 'new'){
    $('#paciente-nome').val('');
    $('#paciente-cpf').val('');
   }
    $('#getPaciente').submit();
}

function submitformpaciente() {
    alert('melhorar validcao de campos');
    $('#formnovopaciente').submit();
}
