$(document).ready(function() {
    getCroqui();
});

function getCroqui(){
    const croqui_pedido_id = $('#croqui-pedido-id').val();

    if(croqui_pedido_id){
        $.ajax({
            url: BASE_URL_ADMIN + 'pedidos/getCroquiPedido/'+croqui_pedido_id,
            type: 'GET',
            dataType: 'json',
        })
        .done(function(data) {
            console.log(data)
            if(data.error)
                return alert('Houver algum erro.');
            if(data)
                createTable(data);
        });

    }
}

function createTable(data) {
    const croqui = data.croqui_tipo;
    const croquiValores =  data.pedido_croqui_valores;

    var theads = $('#theads');
    var tbodies = $('#tbodies');
    var words = [];
    var rows = [];

    for (let indexH = 0; indexH < croqui.colunas; indexH++) {
        if(words.length === 0){
            words.push('A');
        }else{
            const lastWord = words[words.length-1];
            const newWord = nextChar(lastWord);
            words.push(newWord);
        }
    }

    for (let indexB = 0; indexB < croqui.linhas; indexB++) {
        if(rows.length === 0){
            rows.push(1);
        }else{
            const lastRow = rows[rows.length-1];
            const newRow = lastRow+1
            rows.push(newRow);
        }
    }

    $.each(words, function (index, word) {
        theads.append("<th>"+word+"</th>");
    });

    $.each(rows, function (index, row) {

        var colls = ''

        $.each(words, function (index, word) {
            var codigo = word+''+row;
            const value = croquiValores.find(value => value.coluna_linha === codigo)
            colls += '<td>'+value.conteudo+'</td>'
        });

        tbodies.append(
            '<tr>'+
                '<th scope="row">'+row+'</th>'+colls+
            '</tr>'
            );

    });

}

function nextChar(c) {
    return String.fromCharCode(c.charCodeAt(0) + 1);
}
