
var idIncrement = 0;
var createProgrees = 0;
var endpoints = [];
var statusGet = ['Positivo', 'Negativo', 'Indeterminado','Restante'];
var order = 0;

var templete = '<div id="{ID_ENPOINT}" style="margin-top: 15px;" class="row">'+
					'<div class="col-md-5">'+
						'<div class="input select">'+
							'<label for="url-encad">URL encadeamento</label>'+
							'<select isValidate="validate" name="url_encad[]" class="form-control" id="url-encad-{ID_ENPOINT}">'+
								'<option value="">Escolha</option>'+
								'{OPTIONS_ENDPOINT}'+
							'</select>'+
						'</div>'+
					'</div>'+
                    '<div class="col-md-2">'+
						'<div class="input select">'+
							'<label for="regra">Regra</label>'+
							'<select isValidate="validate" name="regra[]" class="form-control combo-rule" id="regra-{ID_ENPOINT}">'+
								'<option value="">Escolha</option>'+
								'{OPTIONS_STATUS}'+
							'</select>'+
						'</div>'+
                    '</div>'+
                    '<div class="col-md-2">'+
						'<div class="input text">'+
							'<label for="ordem">Ordem</label>'+
							'<input isValidate="validate" type="text" name="ordem[]" value="{VALUE_ORDEM}" class="form-control" id="ordem-{ID_ENPOINT}">'+
						'</div>'+
					'</div>'+
					 '<div style="margin-top: 30px;" class="col-md-3">'+
                        '<div class="buttons">'+
                            '<button style="margin-right: 10px;" class="btn btn-primary btn-rounded waves-effect waves-light btn-remove" onclick="removeEnpoint({ID_ENPOINT})" type="button">Remover</button>'+
                        '</div>'+
                    '</div>'+
                '</div>';


$(document).ready(function(){
	 $.ajax({
        url: BASE_URL_ADMIN + 'origens/origensApi',
        type: 'GET',
        dataType: 'json',
    })
    .done(function(data) {
        endpoints = data
    });

    order = nextOrder
});

function saveEncadMain(argument) {
	var endpoint_parent = $('#endpoint_parent').val();
	var regra_main = $( "#regra-main option:selected" ).text();


	$.ajax({
        url: BASE_URL_ADMIN + 'origens/saveEncademantoMain/'+endpoint_parent+'/'+regra_main,
        type: 'GET',
        dataType: 'json',
    })
    .done(function(data) {

		console.log(data)

    });

}

function saveDataEncads() {

	 /*
    validacoes
    */
     var validate = true;
     var restante = true;

    $("#formSaveEncads").each(function(){
	 //<-- Should return all input elements in that specific form.

	 console.log($(this).find(':input'));
    $(this).find(':input').each(function(val, element){

        if($(element).val() == 'Restante'){
        	restante = false
        }

        if($(element).attr('isValidate') && $(element).attr('isValidate') == 'validate'){
            if($(element).val() == ''){
                validate = false;
            }
        }

        });
    });

    if(!validate){
         Swal.fire({
                title: "Atenção",
                text: "Você precisa preencher todos os campos!",
                icon: "warning",
                confirmButtonColor: "#004ba7",
                confirmButtonText: "Entendi"
              });

        return;
    }

    if(restante){
    	Swal.fire({
                title: "Atenção",
                text: "O último endpoint precisa ter a regra Restante",
                icon: "warning",
                confirmButtonColor: "#004ba7",
                confirmButtonText: "Entendi"
              });

        return;
    }

	$('#formSaveEncads').submit();
}

function saveEnpoint(id) {
	var endpoint_parent = $('#endpoint_parent').val();
	var instancia = $('#instancia-'+id).val();
	var url_encadeamento = $('#url-encad-'+id).val();
	var regra = $('#regra-'+id).val();
	var ordem = $('#ordem-'+id).val();

	if(endpoint_parent == '' || url_encadeamento == '' || regra == '' || ordem == ''){
		return alert('Preencha todos os campos');
	}

	var dados = {
		endpoint_parent: endpoint_parent,
		url_encadeamento: url_encadeamento,
		regra: regra,
		ordem: ordem,
		instancia:instancia
	};

	$.ajax({
        url: BASE_URL_ADMIN + 'origens/saveEncademanto',
        type: 'GET',
        dataType: 'json',
        data:dados
    })
    .done(function(data) {
		console.log(data)
		if(!data.error){
			if(data.newItem){
				$('#instancia-'+id).val(data.encad.id)
				$('#'+id).remove();
				getAllEncads(endpoint_parent);
			}
			createProgrees = 0

		}


    });

	console.log(endpoint_parent)
}

function removeEnpoint(id) {

	order--;
	$('#'+id).remove();

	var index = $(".btn-remove").length - 1;

	var element = $(".btn-remove")[index];

	$(element).show();

}

function getAllEncads(id_parent) {
	 $.ajax({
        url: BASE_URL_ADMIN + 'origens/allEncads/'+id_parent,
        type: 'GET',
        dataType: 'json',
    })
    .done(function(data) {
    	$('#content_inputs_cadastros').html('');
		$.each(data, function (index, endpointSaved) {


			var handleOptions = '';
			$.each(endpoints, function (index, endpoint) {
				var selected = '';
				if(endpoint.id == endpointSaved.origem_encadeamento_id){
					selected = 'selected="selected"'
				}
				handleOptions += '<option '+selected+' value="'+endpoint.id+'">'+endpoint.name+'</option>'
			});


			var handleOptionsStatus = '';
			statusGet.forEach(function (item, indice, array) {
			  var selected2 = '';
			  if(endpointSaved.regra == item){
					selected2 = 'selected="selected"'
				}
				handleOptionsStatus += '<option '+selected2+' value="'+item+'">'+item+'</option>'

			});

			var html_templete = templete.replace(/{ID_ENPOINT}/g, idIncrement);
			html_templete = html_templete.replace(/{OPTIONS_ENDPOINT}/g, handleOptions);
			html_templete = html_templete.replace(/{OPTIONS_STATUS}/g, handleOptionsStatus);
			html_templete = html_templete.replace('{VALUE_INSTANCIA}', endpointSaved.id);
			html_templete = html_templete.replace('{VALUE_ORDEM}', endpointSaved.ordem);

			$('#content_inputs_cadastros').append(html_templete);

			idIncrement++;
		});

    });
}

function addEnpoint() {

	if(endpoints.length === 0){
		 Swal.fire({
                title: "Aguarde",
                text: "Fazendo o carregamento dos dados.",
                icon: "warning",
                confirmButtonColor: "#004ba7",
                confirmButtonText: "Entendi"
              });
    	 return

	}

		 /*
    validacoes
    */
     var validate = true;
     var restante = true;

    $("#formSaveEncads").each(function(){
	 //<-- Should return all input elements in that specific form.

	 console.log($(this).find(':input'))
    $(this).find(':input').each(function(val, element){
        console.log($(element).attr('isValidate'))
        console.log($(element).val())

        if($(element).val() == 'Restante'){
        	restante = false
        }

        if($(element).attr('isValidate') && $(element).attr('isValidate') == 'validate'){
            if($(element).val() == ''){
                validate = false;
            }
        }
        })
    });


    if(!validate){
    	 Swal.fire({
                title: "Atenção",
                text: "Cadastre ou remova o endpoint em criação para continuar.",
                icon: "warning",
                confirmButtonColor: "#004ba7",
                confirmButtonText: "Entendi"
              });
    	 return

    }

	if(!restante){
		 Swal.fire({
	                title: "Atenção",
	                text: "O último endpoit cadastrado como restante, favor alterar para outro regra.",
	                icon: "warning",
	                confirmButtonColor: "#004ba7",
	                confirmButtonText: "Entendi"
	              });
	    	 return

	}

	$(".combo-rule option[value='Restante']").remove();

	$('.btn-remove').hide();

	var endpoint_parent = $('#endpoint_parent').val();

	var handleOptions = '';
	$.each(endpoints, function (index, endpoint) {
		handleOptions += '<option value="'+endpoint.id+'">'+endpoint.name+'</option>'
	});


	var handleOptionsStatus = '';

	statusGet.forEach(function (item, indice, array) {
		handleOptionsStatus += '<option value="'+item+'">'+item+'</option>'
	});

	var html_templete = templete.replace(/{ID_ENPOINT}/g, idIncrement);
		html_templete = html_templete.replace(/{OPTIONS_ENDPOINT}/g, handleOptions);
		html_templete = html_templete.replace(/{OPTIONS_STATUS}/g, handleOptionsStatus);
		html_templete = html_templete.replace('{VALUE_ORDEM}', order);

	$('#content_inputs').append(html_templete);
	idIncrement++;
	order++;

}
