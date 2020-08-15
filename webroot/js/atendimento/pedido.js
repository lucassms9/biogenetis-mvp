
var templeteBarcode =  '<div class="col-md-1 bar-code_data">DN: {DATA_NASC}</div>'+
                        '<div class="col-md-10 bar-code-content-center">'+
                        '<div class="area_barcode">'+
                                '<div id="barcodeTarget" class="barcodeTarget"></div>'+
                                '<div style="display: flex;justify-content: center;flex-direction: column;align-items: center;">'+
                                    '<div>{NOME_PACIENTE}</div>'+
                                    '<div>{TIPO_EXAME}</div>'+
                                '</div>'+
                        '</div>'+
                        '</div>'+
                        '<div class="col-md-1 bar-code_data">{DATA_SISTEMA}</div>';


var canPrinter = false;

$(document).ready(function() {

    checkBarCode();
    $('#entrada-exame-id').change(function (e) {
        handlePaymentMethod(this)
    });
});

function checkBarCode(){
    const pedido_id = $('#pedido-id').val();

    $.ajax({
        url: BASE_URL_ADMIN + 'pedidos/checkBarCode/'+pedido_id,
        type: 'GET',
        dataType: 'json',
    })
    .done(function(data) {
        console.log(data)
        if(data.error)
            return $("#content-barcode").html('<p style="padding: 10px;">Etiqueta indisponível.</p>');

            const {barcode} = data;

            generateBarcode(barcode);
            canPrinter = true;

    });



}

function handlePaymentMethod(element){
    const value = $(element).children("option:selected").val();
    if(value){
        $.ajax({
            url: BASE_URL_ADMIN + 'entradaExames/getExame/'+value,
            type: 'GET',
            dataType: 'json',
        })
        .done(function(data) {
            console.log(data)
            if(data.error)
                return alert('Houver algum erro.');

            const value = String(data.entrada_exame.valor_laboratorio_conveniado.toLocaleString('pt-br', {minimumFractionDigits: 2}));

            const tipo_exame = data.entrada_exame.tipo_exame;

            $('#valor-laboratorio-conveniado').val(String(value));
            $('#tipo-exame').val(tipo_exame);

        });

    }
   console.log(value)
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
     templete = templete.replace('{DATA_SISTEMA}',data_sistema);

     $("#content-barcode").html(templete);

    $("#barcodeTarget").html("").show().barcode(value, btype, settings);

}


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
