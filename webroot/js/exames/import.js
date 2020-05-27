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


function sendFiles() {
	$('#formFiles').submit();

}


$(document).ready(function(){
	maisImagens();
});