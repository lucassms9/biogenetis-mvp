
$(document).ready(function() {

});

function submitGetPaciente(tipo){

    $('#tipofiltro').val(tipo);
   if(tipo === 'new'){
    $('#paciente-nome').val('');
    $('#paciente-cpf').val('');
   }
    $('#getPaciente').submit();
}
