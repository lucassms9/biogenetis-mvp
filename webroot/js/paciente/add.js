function getCitiesViagem(uf){
    // return console.log(uf)

    if(!uf) return false;

    $.ajax({
        url: BASE_URL_ADMIN + 'pacientes/getCities/'+uf,
        type: 'GET',
        dataType: 'json',
    })
    .done(function(data) {
        $('#viagem-brasil-cidade').html('<option value="">Escolha</option>');

        $('#viagem-brasil-cidade').append('')
        $.each(data, function (index, city) {
            console.log(city)

            var handle = '<option value="'+city.id+'">'+city.nome+'</option>'

            $('#viagem-brasil-cidade').append(handle)
        });


    });

}
function getCitiesUnidade(uf){
    // return console.log(uf)

    if(!uf) return false;

    $.ajax({
        url: BASE_URL_ADMIN + 'pacientes/getCities/'+uf,
        type: 'GET',
        dataType: 'json',
    })
    .done(function(data) {
        $('#paciente-unidade-saude-14-dias-cidade').html('<option value="">Escolha</option>');

        $('#paciente-unidade-saude-14-dias-cidade').append('')
        $.each(data, function (index, city) {
            var handle = '<option value="'+city.id+'">'+city.nome+'</option>'
            $('#paciente-unidade-saude-14-dias-cidade').append(handle)
        });


    });

}

function checkCpf(cpf){
    $.ajax({
        url: BASE_URL_ADMIN + 'pacientes/getCpf/'+ cpf,
        type: 'GET',
        dataType: 'json',
    })
    .done(function(data) {
        console.log(data);
        if(data != false){
           console.log(data);
           let vData = JSON.parse(data);
           let dataNascimento = vData.data_nascimento.split('-');
           try{

            let strDataNasicmento = dataNascimento[2] + '/' + dataNascimento[1] + '/' +dataNascimento[0];
            $("#data_nascimento").val(strDataNasicmento);
           }catch(e){

           }
           $("#nome_paciente").val(vData.nome);
           $("#rg_paciente").val(vData.rg);
           $("#sexo_paciente").val(vData.sexo);
           $("#email_paciente").val(vData.email);
           $("#celular_paciente").val(vData.celular);
           $("#telefone_paciente").val(vData.telefone);
           $("#cep_paciente").val(vData.cep);

           
           $("#endereco_paciente").val(vData.endereco);
           $("#bairro_paciente").val(vData.bairro);
           $("#cidade_paciente").val(vData.cidade);
           $("#uf_paciente").val(vData.uf);
           $("#nome_da_mae_paciente").val(vData.nome_da_mae);
           $("#nacionalidade_paciente").val(vData.nacionalidade);
           $("#pais_residencia_paciente").val(vData.pais_residencia);



                  
           
        }else{
        }
    });
}


function validateBirth(e){
    let vvalue =e;
    let error = false;
    vvalue = vvalue.split('/');
    if(vvalue[0] < 1 || vvalue[0] > 30){
        console.log(vvalue[0]);
         error = true;
    }
    if(vvalue[1] < 1 || vvalue[1] > 12){
        console.log(vvalue[1]);
        error = true;
    }
    let thisyear = new Date().getFullYear()
    if(vvalue[2] < 1 || vvalue[2] > thisyear){
        console.log(vvalue[2]);
        error = true;
    }
    if(  error == true){
        $("#data_nascimento").val('');
        alertify.error('Data de nascimento inválida.');
        return false;
    }else{
        return true;
    }
}
function mphone(v) {
    var r = v.replace(/\D/g, "");
    r = r.replace(/^0/, "");
    if (r.length > 10) {
      r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
    } else if (r.length > 5) {
      r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
    } else if (r.length > 2) {
      r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
    } else {
      r = r.replace(/^(\d*)/, "($1");
    }
    console.log(r);
    return r;
  }


function mask(o, f) {
  setTimeout(function() {
      console.log(o.value);
    var v = mphone(o.value);
    if (v != o.value) {
      o.value = v;
    }
  }, 1);
}
$(document).ready(function() {
    $('#celular_paciente').keyup(function(e){
        var vthis = $(this);
        setTimeout(function() {
            var vp = mphone(vthis.val());
            if (vp != vthis.val()) {
                vthis.val(vp);
              }
        },1);
    });

    
    $('#telefone_paciente').keyup(function(e){
        var vthis = $(this);
        setTimeout(function() {
            var vp = mphone(vthis.val());
            if (vp != vthis.val()) {
                vthis.val(vp);
              }
        },1);
    });
    $("#data_nascimento").mask("00/00/0000");
    $("#uf_paciente").mask("AA");

    $("#data_nascimento").blur(function (e){
        validateBirth($(this).val());
    });
    $('#viagem-brasil-estado').change(function (e) {
        e.preventDefault();
        getCitiesViagem(this.value);
    });

    $('#paciente-unidade-saude-14-dias-estado').change(function (e) {
        e.preventDefault();
        getCitiesUnidade(this.value);
    });

    $('#submitformpaciente').click(function (e) {
        e.preventDefault();
        submitformpaciente()
    });

    $('.cpf').keydown(function(e){
        if($(this).val().length > 13
            && e.keyCode !== 46
            && e.keyCode !== 8 
            && e.keyCode !== 9 ){
            $(this).val(mCPF(this.value));
            e.preventDefault();
            return;
        }else{
            $(this).val(mCPF(this.value));
        }
    });
    $('.cpf').blur(function(e){
        let vCPF = $(this).val();
        if(vCPF.length > 0 && !isValidCPF(vCPF)){
            alertify.error('CPF Inválido');
        }else{
            console.log($(this).attr('data-value'));
            if($(this).attr('data-value') == 'new'){
                checkCpf(vCPF);
            }
        }
    });
});
function mCPF(cpf){
    cpf=cpf.replace(/\D/g,"")
    cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
    cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
    cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
    return cpf
}
function isValidCPF(cpf) {
    if (typeof cpf !== "string") return false
    cpf = cpf.replace(/[\s.-]*/igm, '')
    if (
        !cpf ||
        cpf.length != 11 ||
        cpf == "00000000000" ||
        cpf == "11111111111" ||
        cpf == "22222222222" ||
        cpf == "33333333333" ||
        cpf == "44444444444" ||
        cpf == "55555555555" ||
        cpf == "66666666666" ||
        cpf == "77777777777" ||
        cpf == "88888888888" ||
        cpf == "99999999999"
    ) {
        return false
    }
    var soma = 0
    var resto
    for (var i = 1; i <= 9; i++)
        soma = soma + parseInt(cpf.substring(i-1, i)) * (11 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11))  resto = 0
    if (resto != parseInt(cpf.substring(9, 10)) ) return false
    soma = 0
    for (var i = 1; i <= 10; i++)
        soma = soma + parseInt(cpf.substring(i-1, i)) * (12 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11))  resto = 0
    if (resto != parseInt(cpf.substring(10, 11) ) ) return false
    return true
}

function submitGetPaciente(tipo){
    $('#tipofiltro').val(tipo);
   if(tipo === 'new'){
        $('#paciente-nome').val('');
        $('#paciente-cpf').val('');
   }else{
       let vNOME = $('#paciente-nome').val();
       let vCPF = $('#paciente-cpf').val()
       if(vNOME.length == 0 &&  vCPF.length == 0){
            return;
       }else if(vCPF.length > 0 && !isValidCPF(vCPF)){
            alertify.error('CPF Inválido');
            return;
       }
   }
   $('#getPaciente').submit();
}

function submitformpaciente() {

    var cpf = $('#cpf').val();
    if(!isValidCPF(cpf)){
        $('#cpf').focus();
        alertify.error("CPF Inválido");
        return
    }

    var nome_paciente = $('#nome_paciente').val();
    if(nome_paciente.length < 3){
        $('#nome_paciente').focus();
        alertify.error('Você precisa informar o nome do paciente');
        return $('#nome_paciente').focus();
    }


    
    var rg_paciente = $('#rg_paciente').val();
    if(rg_paciente.length < 3){
        $('#rg_paciente').focus();
        alertify.error('Você precisa informar o RG');
        return $('#rg_paciente').focus();
    }

    var sexo_paciente = $('#sexo_paciente').val();
    if(sexo_paciente.length < 1){
        $('#sexo_paciente').focus();
        alertify.error('Você precisa informar o sexo');
        return $('#sexo_paciente').focus();
    }
/*
    
    var email_paciente = $('#email_paciente').val();
    if(email_paciente.length < 3){
        $('#email_paciente').focus();
        alertify.error('Você precisa informar o email');
        return $('#email_paciente').focus();
    }
*/

    
    var celular_paciente = $('#celular_paciente').val();
    if(celular_paciente.length < 3){
        $('#celular_paciente').focus();
        alertify.error('Você precisa informar o celular');
        return $('#celular_paciente').focus();
    }
    
    
    var telefone_paciente = $('#telefone_paciente').val();
    if(telefone_paciente.length < 3){
        $('#telefone_paciente').focus();
        alertify.error('Você precisa informar o telefone');
        return $('#telefone_paciente').focus();
    }
    
    
    var cep_paciente = $('#cep_paciente').val();
    if(cep_paciente.length < 3){
        $('#cep_paciente').focus();
        alertify.error('Você precisa informar o cep');
        return $('#cep_paciente').focus();
    }
    

    
    var endereco_paciente = $('#endereco_paciente').val();
    if(endereco_paciente.length < 3){
        $('#endereco_paciente').focus();
        alertify.error('Você precisa informar o endereço');
        return $('#endereco_paciente').focus();
    }
    


    
    var bairro_paciente = $('#bairro_paciente').val();
    if(bairro_paciente.length < 3){
        $('#bairro_paciente').focus();
        alertify.error('Você precisa informar o bairro');
        return $('#bairro_paciente').focus();
    }
    

    
    var cidade_paciente = $('#cidade_paciente').val();
    if(cidade_paciente.length < 3){
        $('#cidade_paciente').focus();
        alertify.error('Você precisa informar o cidade');
        return $('#cidade_paciente').focus();
    }
    

    var uf_paciente = $('#uf_paciente').val();
    if(uf_paciente.length < 2){
        $('#uf_paciente').focus();
        alertify.error('Você precisa informar o UF');
        return $('#uf_paciente').focus();
    }
    

    var nome_da_mae_paciente = $('#nome_da_mae_paciente').val();
    if(nome_da_mae_paciente.length < 3){
        $('#nome_da_mae_paciente').focus();
        alertify.error('Você precisa informar o nome da mãe');
        return $('#nome_da_mae_paciente').focus();
    }
    
    var pais_residencia_paciente = $('#pais_residencia_paciente').val();
    if(pais_residencia_paciente.length < 3){
        $('#pais_residencia_paciente').focus();
        alertify.error('Você precisa informar o pais de residencia');
        return $('#pais_residencia_paciente').focus();
    }
    var nacionalidade_paciente = $('#nacionalidade_paciente').val();
    if(nacionalidade_paciente.length < 3){
        $('#nacionalidade_paciente').focus();
        alertify.error('Você precisa informar a nacionalidade');
        return $('#nacionalidade_paciente').focus();
    }
    
    var clinico_outros = $('#clinico-outros').is(":checked");
    var clinico_outros_desc = $('#clinico_outros_observacao').val();

    if(clinico_outros && !clinico_outros_desc){
        alertify.error('você precisa especificar o sintoma.');
        return $('#clinico_outros_observacao').focus();
    }





    var viagem_brasil = $('input[name="viagem_brasil"]:checked');
    var viagem_brasil_estado = $('#viagem-brasil-estado option:selected').val();

    if(viagem_brasil.length > 0 && !viagem_brasil_estado){
         alertify.error('você precisa informar o estado e cidade da viagem no brasil do paciente');
        return $('input[name="viagem_brasil_estado"]').focus();
    }

    var viagem_exterior = $('input[name="viagem_exterior"]:checked');
    var viagem_exteriorobs_pais = $('input[name="viagem_exteriorobs_pais"]').val();
    if(viagem_exterior.length > 0 && !viagem_exteriorobs_pais){
         alertify.error('você precisa informar o país da viagem no exterior do paciente');
        return $('input[name="viagem_brasil_estado"]').focus();
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

    if(paciente_unidade_saude_14_dias.val() === 'SIM'){

    var paciente_unidade_saude_14_dias_local = $('input[name="paciente_unidade_saude_14_dias_local"]');

    if(!paciente_unidade_saude_14_dias_local.val()){
        alertify.error('você precisa informar o nome da unidade de saúde');
        return paciente_unidade_saude_14_dias_local.focus();
   }



    var paciente_unidade_saude_14_dias_estado = $('#paciente-unidade-saude-14-dias-estado option:selected').val();

    var paciente_unidade_saude_14_dias_cidade = $('#paciente-unidade-saude-14-dias-cidade option:selected').val();

    if(!paciente_unidade_saude_14_dias_estado || !paciente_unidade_saude_14_dias_cidade){
         alertify.error('você precisa informar o estado e cidade da unidade de saúde');
        return $('#paciente-unidade-saude-14-dias-estado').focus();
    }

    }


    $('#formnovopaciente').submit();
}
