$(document).ready(function() {
    $('.datemask').mask('99/99/9999');
    $('#croqui-tipo-id').change(function (e) {
        createCroqui(this)
    });

    $('#btn-form-filter').click(function(e){
        dispatchFilter();
    });
});

function changeCheckPedido(element){
    var element = $(element);
    var valor = element.val();
    var elementInputsHandle = [];

    var elementInputs = $('.input-croqui');
    console.log(elementInputs)
    if(elementInputs.length === 0)  {
        element.prop('checked', false);
        return alertify.error('você deve escolher um croqui para continuar.');
    }

    elementInputs.each(function(val, element){
        elementInputsHandle.unshift(element);
    });

    if(element.is(":checked")){
        var inputHandle = '';

        $.each(elementInputsHandle, function (index, element) {
            var valInput = $(element).val();
            console.log(valInput)
            if(valInput == ''){
                inputHandle = $(element);
            }
        });
        inputHandle.val(valor);

    }else{
        $.each(elementInputsHandle, function (index, element) {
            var valInput = $(element).val();
            if(valInput == valor){
                inputHandle = $(element);
            }
        });

        inputHandle.val('');
    }

}

function validateForm() {
    // var inputsCroqui = $('.input-croqui');
    // var campo_vazio = false;

    // if(inputsCroqui.length === 0){
    //     return alertify.error('você deve escolher um croqui para continuar.');

    // }

    // $.each(inputsCroqui, function (index, element) {
    //     var value = $(element).val();
    //     if(value){
    //         campo_vazio = true;
    //     }
    // });

    // if(campo_vazio){
    //     return alertify.error('você deve preencher todos campos do croquis.');
    // }

    $('#formCroqui').submit();
}

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

    theads.html('');
    theads.append('<th>#</th>');
    tbodies.html('');

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
            colls += '<td><input class="form-control input-croqui" name="'+name+'"/></td>'
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
