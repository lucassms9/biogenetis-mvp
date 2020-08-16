
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

    var sintomas_outros = $('#sintoma-outros').is(":checked");
    var sintomas_outros_desc = $('#sintoma_outros_observacao').val();  

    if(sintomas_outros && !sintomas_outros_desc){
        
        alertify.error("você precisa especificar o sintoma.");
         $('#sintoma_outros_observacao').focus();

        return
    }

    var sintomas_febre = $('#clinico-febre').is(":checked");
    var sintomas_febre_desc = $('#clinico_febre_temp').val();


    if(sintomas_febre && !sintomas_febre_desc){
        alertify.error('você precisa especificar a febre.');
        return $('#clinico_febre_temp').focus();
    }

    var clinico_outros = $('#clinico-outros').is(":checked");
    var clinico_outros_desc = $('#clinico_outros_observacao').val();


    if(clinico_outros && !clinico_outros_desc){
        alertify.error('você precisa especificar o sintoma.');
        return $('#clinico_outros_observacao').focus();
    }

    var paciente_anti_inflamatorio = $('input[name="analgesico_antitermico_antiinflamatorio"]:checked');
    if(paciente_anti_inflamatorio.length === 0){
          alertify.error("você precisa informar se o paciente utilizou analgésico, antitérmico ou anti-inflamatório.");
        return $('input[name="analgesico_antitermico_antiinflamatorio"]').focus();
    }

    var paciente_hospitalizado = $('input[name="paciente_hospitalizado"]:checked');
    var paciente_hospitalizado_nome_hospital = $('input[name="paciente_hospitalizado_nome_hospital"]');
    if(paciente_hospitalizado.length === 0){
         alertify.error('você precisa informar se o paciente foi hospitalizado');
        return $('input[name="paciente_hospitalizado"]').focus();
    }

    if(paciente_hospitalizado.val() === 'SIM' && !paciente_hospitalizado_nome_hospital.val()){
         alertify.error('você precisa informar o nome do hospital.');
        return paciente_hospitalizado_nome_hospital.focus();
    }


    var paciente_ventilacao_mecanica = $('input[name="paciente_ventilacao_mecanica"]:checked');
    if(paciente_ventilacao_mecanica.length === 0){
         alertify.error('você precisa informar se o paciente foi submetido a ventilação mecânica');
        return $('input[name="paciente_ventilacao_mecanica"]').focus();
    }

    var paciente_situacao_notificacao = $('input[name="paciente_situacao_notificacao"]:checked');
    if(paciente_situacao_notificacao.length === 0){
         alertify.error('você precisa informar a situação de saúde do paciente no momento da notificação');
        return $('input[name="paciente_situacao_notificacao"]').focus();
    }


    var paciente_historico_viagem_14_dias = $('input[name="paciente_historico_viagem_14_dias"]:checked');
    var paciente_historico_viagem_14_dias_data_chegada = $('input[name="paciente_historico_viagem_14_dias_data_chegada"]');
    if(paciente_historico_viagem_14_dias.length === 0){
         alertify.error('você precisa informar se o paciente tem histórico de viagem para fora do brasil até 14 dias antes do início dos sintomas');
        return $('input[name="paciente_hospitalizado"]').focus();
    }

    if(paciente_hospitalizado.val() === 'SIM' && !paciente_historico_viagem_14_dias_data_chegada.val()){
         alertify.error('você precisa informar data de chegada no brasil.');
        return paciente_historico_viagem_14_dias_data_chegada.focus();
    }



    var paciente_coleta_de_amostra = $('input[name="paciente_coleta_de_amostra"]:checked');
    if(paciente_coleta_de_amostra.length === 0){
         alertify.error('você precisa informar se foi realizada coleta de amostra do paciente');
        return $('input[name="paciente_coleta_de_amostra"]').focus();
    }

    var paciente_his_deslocamento_14_dias = $('input[name="paciente_his_deslocamento_14_dias"]');
    if(!paciente_his_deslocamento_14_dias){
         alertify.error('você precisa informar o histórico de deslocamento nos 14 dias anteriores ao início dos sintomas');
        return $('input[name="paciente_his_deslocamento_14_dias"]').focus();
    }


    var paciente_contato_pessoa_com_suspeita_covid = $('input[name="paciente_contato_pessoa_com_suspeita_covid"]:checked');
    var paciente_contato_pessoa_com_suspeita_covid_local = $('input[name="paciente_contato_pessoa_com_suspeita_covid_local"]:checked');
    if(paciente_contato_pessoa_com_suspeita_covid.length === 0){
         alertify.error('você precisa informar se o paciente teve contato próximo com uma pessoa que seja caso suspeito de novo coronavírus (covid-19)');
        return $('input[name="paciente_contato_pessoa_com_suspeita_covid"]').focus();
    }

    if(paciente_contato_pessoa_com_suspeita_covid.val() === 'SIM' && paciente_contato_pessoa_com_suspeita_covid_local.length === 0){
         alertify.error('você precisa informar o local');
        return $('input[name="paciente_contato_pessoa_com_suspeita_covid_local"]').focus();
    }



    var paciente_contato_pessoa_com_confirmado_covid = $('input[name="paciente_contato_pessoa_com_confirmado_covid"]:checked');
    var paciente_contato_pessoa_com_confirmado_covid_caso_fonte = $('input[name="paciente_contato_pessoa_com_confirmado_covid_caso_fonte"]');
    if(paciente_contato_pessoa_com_confirmado_covid.length === 0){
         alertify.error('você precisa informar se o paciente teve contato próximo com uma pessoa que seja caso confirmado de novo coronavírus (covid-19)');
        return $('input[name="paciente_contato_pessoa_com_confirmado_covid"]').focus();
    }

    if(paciente_contato_pessoa_com_confirmado_covid.val() === 'SIM' && !paciente_contato_pessoa_com_confirmado_covid_caso_fonte.val()){
         alertify.error('você precisa informar o nome do caso fonte.');
        return paciente_contato_pessoa_com_confirmado_covid_caso_fonte.focus();
    }


    var paciente_unidade_saude_14_dias = $('input[name="paciente_unidade_saude_14_dias"]:checked');
    var paciente_unidade_saude_14_dias_local = $('input[name="paciente_unidade_saude_14_dias_local"]');
    if(paciente_unidade_saude_14_dias.length === 0){
         alertify.error('você precisa informar se o paciente esteve em alguma unidade de saúde nos 14 dias antes do início dos sintomas');
        return $('input[name="paciente_unidade_saude_14_dias"]').focus();
    }

    if(paciente_unidade_saude_14_dias.val() === 'SIM' && !paciente_unidade_saude_14_dias_local.val()){
         alertify.error('você precisa informar nome, endereço e contato.');
        return paciente_unidade_saude_14_dias_local.focus();
    }


    var paciente_ocupacao = $('input[name="paciente_ocupacao"]:checked');
    var paciente_ocupacao_outros = $('input[name="paciente_ocupacao_outros"]');
    if(paciente_ocupacao.length === 0){
         alertify.error('você precisa informar a ocupação do paciente');
        return $('input[name="paciente_ocupacao"]').focus();
    }

    if(paciente_ocupacao.val() === 'OUTROS' && !paciente_ocupacao_outros.val()){
         alertify.error('você precisa informar a ocupação.');
        return paciente_ocupacao_outros.focus();
    }


    $('#formnovopaciente').submit();
}
