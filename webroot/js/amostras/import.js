var templete = '<div class="input file"><label for="file">Escolha um arquivo</label><input type="file" name="file[]" class="files"></div>';
var qtd_files = 0;

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
    html += '<td><input class="form-control" name="uf'+qtd_files+'" /></td>';
    html += '<td><input class="form-control" name="idade'+qtd_files+'" /></td>';
    html += '<td><input class="form-control" name="sexo'+qtd_files+'" /></td>';
    html += '</tr>';

    $("#input-files").append( html );

    qtd_files++;

    $('#total-files').val(qtd_files);

}

Dropzone.autoDiscover = false;

$(document).ready(function(){

    $("#formFiles").dropzone({
        maxFiles: 2000,
        url: "/admin/amostras/import",
        success: function (file, response) {
            amountForm(response)
        }
    });

});



