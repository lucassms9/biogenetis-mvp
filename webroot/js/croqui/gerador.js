$(document).ready(function() {

    $('#croqui-tipo-id').change(function (e) {
        createCroqui(this)
    });

});

function createCroqui(element){
    const value = $(element).children("option:selected").val();
    if(value){
        $.ajax({
            url: BASE_URL_ADMIN + 'croquis/getCroqui/'+value,
            type: 'GET',
            dataType: 'json',
        })
        .done(function(data) {
            if(data.error)
                return alert('Houver algum erro.');

            if(data.croqui.linhas && data.croqui.colunas)
                console.log(data.croqui.colunas)
                console.log(data.croqui.linhas)
                createTable(data);
        });

    }
}

function createTable(data) {
    const croqui = data.croqui

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
            var name = word+''+row
            colls += '<td><input class="form-control" name="'+name+'"/></td>'
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
