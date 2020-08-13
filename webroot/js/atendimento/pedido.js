
$(document).ready(function() {

    generateBarcode();

});


function generateBarcode(){
    var value = '123123123';
    var btype = 'code128';
    var renderer = 'css'

    var settings = {
        output:renderer,
        bgColor: '#FFFFFF',
        color:'#000000',
        barWidth: '5',
        barHeight: '50',
        // moduleSize: $("#moduleSize").val(),
        posX: 0,
        posY: 0,
        // addQuietZone: $("#quietZoneSize").val()
    };
    if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')){
        value = {code:value, rect: true};
    }
    if (renderer == 'canvas'){
        clearCanvas();
        $("#barcodeTarget").hide();
        $("#canvasTarget").show().barcode(value, btype, settings);
    } else {
        $("#canvasTarget").hide();
        $("#barcodeTarget").html("").show().barcode(value, btype, settings);
    }
}

function clearCanvas(){
    var canvas = $('#canvasTarget').get(0);
    var ctx = canvas.getContext('2d');
    ctx.lineWidth = 1;
    ctx.lineCap = 'butt';
    ctx.fillStyle = '#FFFFFF';
    ctx.strokeStyle  = '#000000';
    ctx.clearRect (0, 0, canvas.width, canvas.height);
    ctx.strokeRect (0, 0, canvas.width, canvas.height);
}

