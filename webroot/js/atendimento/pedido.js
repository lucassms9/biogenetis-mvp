var templeteBarcode =
    '<div class="col-md-1 bar-code_data">DN: {DATA_NASC}</div>' +
    '<div class="col-md-10 bar-code-content-center">' +
    '<div class="area_barcode">' +
    '<div id="barcodeTarget" class="barcodeTarget"></div>' +
    '<div style="display: flex;justify-content: center;flex-direction: column;align-items: center;">' +
    "<div>{NOME_PACIENTE}</div>" +
    "<div>{TIPO_EXAME}</div>" +
    "</div>" +
    "</div>" +
    "</div>" +
    '<div class="col-md-1 bar-code_data">{DATA_SISTEMA}</div>';

var canPrinter = false;

$(document).ready(function () {
    $(".money").mask("000.000.000.000.000,00", { reverse: true });
    checkBarCode();
    $("#entrada-exame-id").change(function (e) {
        handlePaymentMethod(this);
    });
    getCroqui();
});

function checkBarCode() {
    const pedido_id = $("#pedido-id").val();

    $.ajax({
        url: BASE_URL_ADMIN + "pedidos/checkBarCode/" + pedido_id,
        type: "GET",
        dataType: "json",
    }).done(function (data) {
        console.log(data);
        if (data.error)
            return $("#content-barcode").html(
                '<p style="padding: 10px;">Etiqueta indisponível.</p>'
            );

        const { barcode } = data;

        generateBarcode(barcode);
        canPrinter = true;
    });
}

function handlePaymentMethod(element) {
    const value = $(element).children("option:selected").val();
    if (value) {
        $.ajax({
            url: BASE_URL_ADMIN + "entradaExames/getExame/" + value,
            type: "GET",
            dataType: "json",
        }).done(function (data) {
            console.log(data);
            if (data.error) return alert("Houver algum erro.");

            const value = String(
                data.entrada_exame.valor_laboratorio_conveniado.toLocaleString(
                    "pt-br",
                    { minimumFractionDigits: 2 }
                )
            );

            const tipo_exame = data.entrada_exame.tipo_exame;

            $("#valor-laboratorio-conveniado").val(String(value));
            $("#tipo-exame").val(tipo_exame);
        });
    }
    console.log(value);
}

function generateBarcode({
    data_sistema,
    paciente_data_nasc,
    paciente_nome,
    codigo_pedido,
    tipo_exame,
    nome_exame,
}) {
    var value = codigo_pedido;
    var btype = "code128";

    var settings = {
        output: "css",
        bgColor: "#FFFFFF",
        color: "#000000",
        barWidth: "4",
        barHeight: "100",
        fontSize: 20,
    };

    var templete = templeteBarcode.replace("{DATA_NASC}", paciente_data_nasc);
    templete = templete.replace("{NOME_PACIENTE}", paciente_nome);
    templete = templete.replace("{DATA_NASC}", paciente_data_nasc);
    templete = templete.replace("{TIPO_EXAME}", nome_exame);
    templete = templete.replace("{DATA_SISTEMA}", data_sistema);

    $("#content-barcode").html(templete);

    $("#barcodeTarget").html("").show().barcode(value, btype, settings);
}

function printDiv() {
    if (!canPrinter) {
        return alert("Indisponível para gerar etiqueta!");
    }

    var printContents = document.getElementById("printer-code").innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}

function printerPage() {
    var printContents = document.getElementById("printer-laudo").innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}

function getCroqui() {
    const pedido_id = $("#pedido-id").val();

    if (pedido_id) {
        $.ajax({
            url: BASE_URL_ADMIN + "pedidos/getPedido/" + pedido_id,
            type: "GET",
            dataType: "json",
        }).done(function (data) {
            console.log(data);
            if (data.error) return alert("Houver algum erro.");
            if (data.pedido_croqui) createTable(data);
        });
    }
}

function createTable(data) {
    const croqui = data.pedido_croqui.croqui_tipo;
    const croquiValores = data.pedido_croqui.pedido_croqui_valores;

    var theads = $("#theads");
    var tbodies = $("#tbodies");
    var words = [];
    var rows = [];

    for (let indexH = 0; indexH < croqui.colunas; indexH++) {
        if (words.length === 0) {
            words.push("A");
        } else {
            const lastWord = words[words.length - 1];
            const newWord = nextChar(lastWord);
            words.push(newWord);
        }
    }

    for (let indexB = 0; indexB < croqui.linhas; indexB++) {
        if (rows.length === 0) {
            rows.push(1);
        } else {
            const lastRow = rows[rows.length - 1];
            const newRow = lastRow + 1;
            rows.push(newRow);
        }
    }

    $.each(words, function (index, word) {
        theads.append("<th>" + word + "</th>");
    });

    $.each(rows, function (index, row) {
        var colls = "";

        $.each(words, function (index, word) {
            var codigo = word + "" + row;
            const value = croquiValores.find(
                (value) => value.coluna_linha === codigo
            );
            colls += "<td>" + value.conteudo + "</td>";
        });

        tbodies.append(
            "<tr>" + '<th scope="row">' + row + "</th>" + colls + "</tr>"
        );
    });
}

function nextChar(c) {
    return String.fromCharCode(c.charCodeAt(0) + 1);
}
