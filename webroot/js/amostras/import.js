var templete = '<div class="input file"><label for="file">Escolha um arquivo</label><input type="file" name="file[]" class="files"></div>';
var qtd_files = 0;

var options_sexos = '<option value="">Escolha</option>'+
                        '<option value="M">M</option>'+
                        '<option value="F">F</option>';

var options_uf = '<option value="">Escolha</option>'+
'<option value="AC">Acre</option>'+
 '<option value="AL">Alagoas</option>'+
 '<option value="AM">Amapá</option>'+
 '<option value="AP">Amazonas</option>'+
 '<option value="BA">Bahia</option>'+
 '<option value="CE">Ceará</option>'+
 '<option value="DF">Distrito Federal</option>'+
 '<option value="ES">Espírito Santo</option>'+
 '<option value="GO">Goiás</option>'+
 '<option value="MA">Maranhão</option>'+
 '<option value="MG">Minas Gerais</option>'+
 '<option value="MS">Mato Grosso do Sul</option>'+
 '<option value="MT">Mato Grosso</option>'+
 '<option value="PA">Pará</option>'+
 '<option value="PB">Paraíba</option>'+
 '<option value="PE">Pernambuco</option>'+
 '<option value="PI">Piauí</option>'+
 '<option value="PR">Paraná</option>'+
 '<option value="RJ">Rio de Janeiro</option>'+
 '<option value="RN">Rio Grande do Norte</option>'+
 '<option value="RO">Rondônia</option>'+
 '<option value="RR">Roraima</option>'+
 '<option value="RS">Rio Grande do Sul</option>'+
 '<option value="SC">Santa Catarina</option>'+
 '<option value="SE">Sergipe</option>'+
 '<option value="SP">São Paulo</option>'+
 '<option value="TO">Tocantins</option>';






function addInput(){

    var html  = '';

     html += '<div class="input file">';
     html += '<label for="file">Arquivo [' + qtd_files + '] *</label>';
     html += '<input type="file" name="file[]" class="files" onchange="maisImagens()">';
     html += '</div>';
     html += '</div>';

    $("#files-input").append( html );

    qtd_files++;

}


function submitForm() {

    /*
    validacoes
    */
     var validate = true;

    $("#sendData").each(function(){

       
    //<-- Should return all input elements in that specific form.

    $(this).find(':input').each(function(val, element){
        console.log($(element).attr('isValidate'))
        console.log($(element).val())

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
                text: "Você precisa preencher todos os campos!",
                icon: "warning",
                confirmButtonColor: "#004ba7",
                confirmButtonText: "Entendi"
              });

        return
    }

    // return  

    Swal.fire({
    title: 'Enviado Dados',
    html: 'Por favor aguarde, estamos processando os dados',
    timer: '',
    onBeforeOpen:function () {
        Swal.showLoading()
       
    },
    onClose: function () {
     
    }
    }).then(function (result) {
    if (
        // Read more about handling dismissals
        result.dismiss === Swal.DismissReason.timer
    ) {
        console.log('I was closed by the timer')
    }
    })

	$('#sendData').submit();
}

function amountForm(file) {
    file = JSON.parse(file);
   console.log(file)

    var html  = '';

    html += '<tr>';
    html += '<th scope="row">'+file.amostra_id+' ';
    html += '<input class="form-control" type="hidden" name="amostraid'+qtd_files+' " value="'+file.amostra_id+'" />';
    html += '</th>';
    html += '<td><select isValidate="validate" name="uf'+qtd_files+'" class="form-control">'+options_uf+'</select></td>';
    html += '<td><input isValidate="validate" class="form-control" name="idade'+qtd_files+'" /></td>';
    html += '<td><select isValidate="validate" name="sexo'+qtd_files+'" class="form-control">'+options_sexos+'</select></td>';
    html += '</tr>';

    $("#input-files").append( html );

    qtd_files++;

    $('#total-files').val(qtd_files);

}

Dropzone.autoDiscover = false;
Dropzone.prototype.defaultOptions.dictRemoveFile = "Remover Arquivo";
Dropzone.prototype.defaultOptions.dictDefaultMessage = "Drop files here to upload";
Dropzone.prototype.defaultOptions.dictFallbackMessage = "Your browser does not support drag'n'drop file uploads.";
Dropzone.prototype.defaultOptions.dictFallbackText = "Please use the fallback form below to upload your files like in the olden days.";
Dropzone.prototype.defaultOptions.dictFileTooBig = "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.";
Dropzone.prototype.defaultOptions.dictInvalidFileType = "You can't upload files of this type.";
Dropzone.prototype.defaultOptions.dictResponseError = "Server responded with {{statusCode}} code.";
Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar upload";
Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = "Are you sure you want to cancel this upload?";
Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "You can not upload any more files.";

$(document).ready(function(){

    $("#formFiles").dropzone({
        maxFiles: 2000,
        addRemoveLinks: true,
        url: "/admin/amostras/import",
        success: function (file, response) {
            amountForm(response)
        },
        error: function(file, message) {
            console.log(message)
            $(file.previewElement).addClass("dz-error").find('.dz-error-message').text(message.message);
        }
    });

});



