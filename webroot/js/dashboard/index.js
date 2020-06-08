
$(document).ready(function() {

    var campos_data = "#date-init-filter, #date-end-filter"; 
    $( campos_data ).mask('99/99/9999');
    // $( campos_data ).datepicker({ language: 'pt-BR' });  

    var data = {};

     $('#reset-filter').click(function(e) {
        e.preventDefault();
       window.location = window.location.href.split("?")[0];
    }) 
     $('#printer-dash').click(function(e) {
        e.preventDefault();
        printer_dashs();
    })

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    if(urlParams.get('estados_filter')){
        data.estado = urlParams.get('estados_filter')
    }

    if(urlParams.get('date_end_filter')){
        data.date_end = urlParams.get('date_end_filter')
    }

    if(urlParams.get('date_init_filter')){
        data.date_init = urlParams.get('date_init_filter')
    }
    runExamesGlobal(data);
    runExamesUF(data);
    runExamesGener(data);
    runExamesIdade(data);

});

function printer_dashs(argument) {
 var printContents = document.getElementById('printer-dash-to').innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

function filterDash(argument) {
    var date_init = $('#date-init-filter').val();
    var date_end = $('#date-end-filter').val();
    var estado = $('#estados-filter :selected').val();
   
    if(date_init == '' && date_end == '' && estado == ''){
        return alert('Você precisa ao menos preencher um dos campos');
    }
    var data = {
        date_init:date_init,
        date_end:date_end,
        estado:estado
    };


    $("#column_chart_sexo").children("div:first").remove();
    $("#column_chart_idade > div:first").children("div:first").remove();
    $('#pie_chart > div:first').remove();
    $('#column_chart_uf > div:first').remove();
    runExamesGlobal(data);
    runExamesUF(data);
    runExamesGener(data);
    runExamesIdade(data);

}
function amountTableAge(data) {
   console.log(data)
   var total020 = 0;
   var total2140 = 0;
   var total4160 = 0;
   var total6180 = 0;
   var total81 = 0;

   var totalPM = 0
   var totalPF = 0
   var totalNM = 0
   var totalNF = 0
   var totalIM = 0
   var totalIF = 0
   var totalTT = 0

    $.each(data, function (index, idade) {
        if(idade.Positivo.M){
            totalPM += idade.Positivo.M;
        }
        if(idade.Positivo.F){
            totalPF += idade.Positivo.F;
        }
        if(idade.Negativo.M){
            totalNM += idade.Negativo.M;
        }
        if(idade.Negativo.F){
            totalNF += idade.Negativo.F;
        }

        if(idade.Indeterminado.M){
            totalIM += idade.Indeterminado.M;
        }
        if(idade.Indeterminado.F){
            totalIF += idade.Indeterminado.F;
        }

        if(index == '0-20'){
            $('#020pm').text(idade.Positivo.M);
            $('#020pf').text(idade.Positivo.F);
            $('#020nm').text(idade.Negativo.M);
            $('#020nf').text(idade.Negativo.F);
            $('#020im').text(idade.Indeterminado.M);
            $('#020if').text(idade.Indeterminado.F);
            total020 = idade.Positivo.M + idade.Positivo.F + idade.Negativo.M + idade.Negativo.F + idade.Indeterminado.M + idade.Indeterminado.F
            $('#020tu').text(total020);

        }
        if(index == '21-40'){
            $('#2140pm').text(idade.Positivo.M);
            $('#2140pf').text(idade.Positivo.F);
            $('#2140nm').text(idade.Negativo.M);
            $('#2140nf').text(idade.Negativo.F);
            $('#2140im').text(idade.Indeterminado.M);
            $('#2140if').text(idade.Indeterminado.F);
            total2140 = idade.Positivo.M + idade.Positivo.F + idade.Negativo.M + idade.Negativo.F + idade.Indeterminado.M + idade.Indeterminado.F
            $('#2140tu').text(total2140);
       
        }

        if(index == '41-60'){
            $('#4160pm').text(idade.Positivo.M);
            $('#4160pf').text(idade.Positivo.F);
            $('#4160nm').text(idade.Negativo.M);
            $('#4160nf').text(idade.Negativo.F);
            $('#4160im').text(idade.Indeterminado.M);
            $('#4160if').text(idade.Indeterminado.F);
            total4160 = idade.Positivo.M + idade.Positivo.F + idade.Negativo.M + idade.Negativo.F + idade.Indeterminado.M + idade.Indeterminado.F
            $('#4160tu').text(total4160);
           
        }

        if(index == '61-80'){
            $('#6180pm').text(idade.Positivo.M);
            $('#6180pf').text(idade.Positivo.F);
            $('#6180nm').text(idade.Negativo.M);
            $('#6180nf').text(idade.Negativo.F);
            $('#6180im').text(idade.Indeterminado.M);
            $('#6180if').text(idade.Indeterminado.F);
            total6180 = idade.Positivo.M + idade.Positivo.F + idade.Negativo.M + idade.Negativo.F + idade.Indeterminado.M + idade.Indeterminado.F
            $('#6180tu').text(total6180);
            
        }

        if(index == '> 80'){
            $('#81pm').text(idade.Positivo.M);
            $('#81pf').text(idade.Positivo.F);
            $('#81nm').text(idade.Negativo.M);
            $('#81nf').text(idade.Negativo.F);
            $('#81im').text(idade.Indeterminado.M);
            $('#81if').text(idade.Indeterminado.F);
            total81 = idade.Positivo.M + idade.Positivo.F + idade.Negativo.M + idade.Negativo.F + idade.Indeterminado.M + idade.Indeterminado.F
            $('#81tu').text(total81);
        }

     });

    totalTT = totalPM + totalPF + totalNM + totalNF + totalIM + totalIF;

    if(totalTT == 0){
         $('#totalupm').text(0);
        $('#totalupf').text(0);
        $('#totalunm').text(0);
        $('#totalunf').text(0);
        $('#totaluim').text(0);
        $('#totaluif').text(0);
        $('#totalutu').text(0);

        $('#020tp').text('0 %');
        $('#2140tp').text('0 %');
        $('#4160tp').text('0 %');
        $('#81tp').text('0 %');
        $('#6180tp').text('0 %');
        $('#totalutp').text('0 %');

        $('#totalporpm').text('0 %');
        $('#totalporpf').text('0 %');
        $('#totalpornm').text('0 %');
        $('#totalpornf').text('0 %');
        $('#totalporim').text('0 %');
        $('#totalporif').text('0 %');
        $('#totalportp').text('0 %');


        $('#totalmfporpos').text('0 %');
        $('#totalmfporneg').text('0 %');
        $('#totalmfporinc').text('0 %');

        return
    }
    $('#totalupm').text(totalPM);
    $('#totalupf').text(totalPF);
    $('#totalunm').text(totalNM);
    $('#totalunf').text(totalNF);
    $('#totaluim').text(totalIM);
    $('#totaluif').text(totalIF);
    $('#totalutu').text(totalTT);

    $('#020tp').text(((total020 / totalTT) * 100).toFixed(2) + '%' );
    $('#2140tp').text(((total2140 / totalTT) * 100).toFixed(2)  + '%'  );
    $('#4160tp').text(((total4160 / totalTT) * 100).toFixed(2)  + '%'  );
    $('#81tp').text(((total81 / totalTT) * 100).toFixed(2)  + '%'  );
    $('#6180tp').text(((total6180 / totalTT) * 100).toFixed(2)  + '%'  );
    $('#totalutp').text(( ((total020 / totalTT) * 100) + ((total2140 / totalTT) * 100) + ((total4160 / totalTT) * 100) + ((total6180 / totalTT) * 100) + ((total81 / totalTT) * 100) ).toFixed(2) + '%' );

    $('#totalporpm').text(((totalPM / totalTT) * 100).toFixed(2) + '%'  );
    $('#totalporpf').text(((totalPF / totalTT) * 100).toFixed(2) + '%'  );
    $('#totalpornm').text(((totalNM / totalTT) * 100).toFixed(2) + '%'  );
    $('#totalpornf').text(((totalNF / totalTT) * 100).toFixed(2) + '%'  );
    $('#totalporim').text(((totalIM / totalTT) * 100).toFixed(2) + '%'  );
    $('#totalporif').text(((totalIF / totalTT) * 100).toFixed(2) + '%'  );
    $('#totalportp').text(( ((totalPM / totalTT) * 100) + ((totalPF / totalTT) * 100) + ((totalNM / totalTT) * 100) + ((totalNF / totalTT) * 100) + ((totalIM / totalTT) * 100) + ((totalIF / totalTT) * 100) ).toFixed(2) + '%'  );


    $('#totalmfporpos').text( (((totalPM + totalPF) / totalTT) * 100).toFixed(2)  + '%'  );
    $('#totalmfporneg').text( (((totalNM + totalNF) / totalTT) * 100).toFixed(2)  + '%'  );
    $('#totalmfporinc').text( (((totalIM + totalIF) / totalTT) * 100).toFixed(2)  + '%'  );


       var arrJCrossOut = $('.crossOut');
    
    arrJCrossOut.each(function(i){
    
        var jTemp      = $(this),
            nWidth   = jTemp.innerWidth(),
            nHeight  = jTemp.innerHeight(),
            nHyp      = Math.sqrt(nWidth*nWidth + nHeight*nHeight),
            nAnglRad = Math.atan2(nHeight,nWidth),
            nAnglSex = nAnglRad*360/(2*Math.PI), nCatOp, nCatAd, nHyp2
            sDomTemp = '<b class="child" ';
            sDomTemp += 'style="width:'+nHyp+'px;';
            sDomTemp += '-webkit-transform: rotate(-'+nAnglSex+'deg);';
            sDomTemp += '-moz-transform: rotate(-'+nAnglSex+'deg);';
            sDomTemp += '-ms-transform: rotate(-'+nAnglSex+'deg);';
            sDomTemp += '-o-transform: rotate(-'+nAnglSex+'deg);';
            sDomTemp += 'transform: rotate(-'+nAnglSex+'deg);';
            sDomTemp += '-sand-transform: rotate(-'+nAnglSex+'deg);';
            nHyp2     = (nHyp/2);
            nCatOp      = Math.sin(nAnglRad)*nHyp2;
            nCatAd      = Math.sqrt((nHyp2*nHyp2) - (nCatOp*nCatOp));
            sDomTemp += 'margin-top: -'+nCatOp+'px;';
            sDomTemp += 'margin-left: -'+(nHyp2-nCatAd)+'px;';
            sDomTemp += '"></b>';
        
        jTemp.append(sDomTemp);
    });
    

}
function runExamesIdade(data) {

      $.ajax({
        url: BASE_URL_ADMIN + 'dashboard/getExamesByAge',
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
       var Indeterminado = [];

        $.each(data.result, function (index, idade) {
            
            positivos.push(idade.Positivo);
            negativos.push(idade.Negativo);
            Indeterminado.push(idade.Indeterminado);

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
                name: 'Indeterminado',
                data: Indeterminado
            });

        renderExamesIdade(body, idades)
    });

}

function runExamesGener(data) {
    $.ajax({
        url: BASE_URL_ADMIN + 'dashboard/getExamesByGener',
        type: 'GET',
        dataType: 'json',
        data:data
    })
    .done(function(data) {

        var body = [];
        var geners = [];

       var positivos = [];
       var negativos = [];
       var Indeterminado = [];

       // console.log(data)
        $.each(data, function (index, gener) {
            
            positivos.push(gener.Positivo);
            negativos.push(gener.Negativo);
            Indeterminado.push(gener.Indeterminado);

            geners.push(index);
            
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
                name: 'Indeterminado',
                data: Indeterminado
            });


        renderExamesGener(body, geners)
    });
}

function runExamesGlobal(data) {
     $.ajax({
        url: BASE_URL_ADMIN + 'dashboard/getExamesGlobal',
        type: 'GET',
        dataType: 'json',
        data:data
    })
    .done(function(data) {
        pizzaGlobal(data)
    });
}

function pizzaGlobal(dados) {
   // Pie Chart
if ($('#pie_chart').length) {
    var options = {
        chart: {
            height: 300,
            type: 'pie',
        },
        series: [dados.Positivo, dados.Negativo, dados.Indeterminado,],
        labels: ["Positivo", "Negativo", "Indeterminado"],
        colors: ['#f06543', '#3ddc97', '#e4cc37'],
        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center',
            verticalAlign: 'middle',
            floating: false,
            fontSize: '14px',
            offsetX: 0,
            offsetY: -10
        },
        responsive: [{
            breakpoint: 600,
            options: {
                chart: {
                    height: 240
                },
                legend: {
                    show: false
                },
            }
        }]

    }

    var chart = new ApexCharts(
        document.querySelector("#pie_chart"),
        options
    );

    chart.render();
}
}


function runExamesUF(data) {
    $.ajax({
        url: BASE_URL_ADMIN + 'dashboard/getExamesByUf',
        type: 'GET',
        dataType: 'json',
        data:data
    })
    .done(function(data) {
       var body = [];
       var ufR = [];

       var positivos = [];
       var negativos = [];
       var Indeterminado = [];

        $.each(data, function (index, uf) {
            
            positivos.push(uf.Positivo);
            negativos.push(uf.Negativo);
            Indeterminado.push(uf.Indeterminado);

            ufR.push(index);
            
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
                name: 'Indeterminado',
                data: Indeterminado
            });

        renderExamesUF(body,ufR)
    });
}


function renderExamesUF(body, ufs) {
   // Column chart
if ($('#column_chart_uf').length) {
    var options = {
        chart: {
            height: 270,
            type: 'bar',
            toolbar: {
                show: false,
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: body,
        colors: ['#f06543', '#3ddc97', '#e4cc37'],
        xaxis: {
            categories: ufs,
        },
        yaxis: {
            title: {
                text: '(Total)'
            }
        },
        grid: {
            borderColor: '#f1f1f1',
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val;
                }
            }
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#column_chart_uf"),
        options
    );

    chart.render();
}

}



function renderExamesGener(body, gener) {
    
/*
grafico por sexo
*/


// Column chart
if ($('#column_chart_sexo').length) {
    var options = {
        chart: {
            height: 270,
            type: 'bar',
            toolbar: {
                show: false,
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: body,
        colors: ['#f06543', '#3ddc97', '#e4cc37'],
        xaxis: {
            categories: gener,
        },
        yaxis: {
            title: {
                text: '(Total)'
            }
        },
        grid: {
            borderColor: '#f1f1f1',
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val; 
                }
            }
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#column_chart_sexo"),
        options
    );

    chart.render();
}

}




function renderExamesIdade(body, idades) {
   
/*
grafico por faixa etaria 20 anos
*/


// Column chart
if ($('#column_chart_idade').length) {
    var options = {
        chart: {
            height: 270,
            type: 'bar',
            toolbar: {
                show: false,
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: body,
        colors: ['#f06543', '#3ddc97', '#e4cc37'],
        xaxis: {
            categories: idades,
        },
        yaxis: {
            title: {
                text: '(Total)'
            }
        },
        grid: {
            borderColor: '#f1f1f1',
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val;
                }
            }
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#column_chart_idade"),
        options
    );

    chart.render();
}

}