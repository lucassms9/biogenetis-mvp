
var templeteBarcode =  '<div  style="border: 1px solid;" class="row mb-2"><div class="col-md-1 bar-code_data">DN: {DATA_NASC}</div>'+
'<div class="col-md-10 bar-code-content-center">'+
'<div class="area_barcode">'+
        '<div id="barcodeTarget_{PEDIDO_CODE}" class="barcodeTarget"></div>'+
        '<div style="display: flex;justify-content: center;flex-direction: column;align-items: center;">'+
            '<div>{NOME_PACIENTE}</div>'+
            '<div>{TIPO_EXAME}</div>'+
        '</div>'+
'</div>'+
'</div>'+
'<div class="col-md-1 bar-code_data">{DATA_SISTEMA}</div></div>';


var canPrinter = false;

$(document).ready(function() {

   $.each(pedidos, function (index, pedido) {  generateBarcode(pedido); });
   canPrinter = true
});

function printDiv() {

    if(!canPrinter){
        return alert('Indisponível para gerar etiqueta!');
    }

    var printContents = document.getElementById('printer-code').innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}

function generateBarcode({data_sistema, paciente_data_nasc, paciente_nome,codigo_pedido,tipo_exame}){
    var value = codigo_pedido;
    var btype = 'code128';

    var settings = {
        output:'css',
        bgColor: '#FFFFFF',
        color:'#000000',
        barWidth: '4',
        barHeight: '100',
        fontSize: 20,
    };

    var templete = templeteBarcode.replace('{DATA_NASC}',paciente_data_nasc);
     templete = templete.replace('{NOME_PACIENTE}',paciente_nome);
     templete = templete.replace('{DATA_NASC}',paciente_data_nasc);
     templete = templete.replace('{TIPO_EXAME}',tipo_exame);
     templete = templete.replace('{PEDIDO_CODE}',codigo_pedido);
     templete = templete.replace('{DATA_SISTEMA}',data_sistema);

     $("#content-barcode").append(templete);

    $('#barcodeTarget_'+codigo_pedido).html("").show().barcode(value, btype, settings);



}
