$(document).ready(function() {


});

function printerPage() {
	 var printContents = document.getElementById('printer').innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

function generateExcel() {
	const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    
	  $.ajax({
        url: BASE_URL_ADMIN + 'amostras/generateExcel',
        type: 'GET',
        dataType: 'json',
        data:data
    })
    .done(function(data) {

        amountTableAge(data.result_table);
        var body = [];
        var idades = [];

       var positivos = [];
       var negativos = [];
       var inconclusivo = [];

        $.each(data.result, function (index, idade) {
            
            positivos.push(idade.Positivo);
            negativos.push(idade.Negativo);
            inconclusivo.push(idade.Inconclusivo);

            idades.push(index);
            
        });

            body.push({
                name: 'Positivo',
                data: positivos
            });

            body.push({
                name: 'Negativos',
                data: negativos
            });

            body.push({
                name: 'Inconclusivo',
                data: inconclusivo
            });

        renderExamesIdade(body, idades)
    });
}