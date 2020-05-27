var templete = '<div class="input file"><label for="file">Escolha um arquivo</label><input type="file" name="file[]" class="files"></div>';
var qtd_files = 1;

function maisImagens(){

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
	$('#formFiles').submit();

}

Dropzone.autoDiscover = false;

$(document).ready(function(){

    $("#formFiles").dropzone({
        autoProcessQueue: false,
        maxFiles: 2000,
        url: "/admin/exames/import",
        init: function () {

            var myDropzone = this;

            // Update selector to match your button
            $("#buttonSend").click(function (e) {
                e.preventDefault();
                $('#buttonSend').html('Enviando...');
                $('#buttonSend').attr('disabled','disabled');

                myDropzone.processQueue();
            });

            this.on('sending', function(file, xhr, formData) {
                // Append all form inputs to the formData Dropzone will POST
                var data = $('#formFiles').serializeArray();
                $.each(data, function(key, el) {
                    formData.append(el.name, el.value);
                });
            });
        },
        success: function (file, response) {
            $('#buttonSend').html('Enviar');
            $('#buttonSend').attr('disabled',false);

             Swal.fire(
                {
                    title: 'Sucesso',
                    text: 'Arquivos enviados',
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3051d3',
                    cancelButtonColor: "#f46a6a"
                }
            ).then(function (result) {
                location.reload();
            });

            console.log(response);
        }
    });

});



