
$(document).ready(function() {
    
    runExamesGlobal();
    runExamesUF();
    runExamesGener();
    runExamesIdade();

});

function runExamesIdade() {

      $.ajax({
        url: BASE_URL_ADMIN + 'dashboard/getExamesByAge',
        type: 'GET',
        dataType: 'json',
        data:{}
    })
    .done(function(data) {

        var body = [];
        var idades = [];

       var positivos = [];
       var negativos = [];
       var inconclusivo = [];

        $.each(data, function (index, idade) {
            
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

function runExamesGener() {
    $.ajax({
        url: BASE_URL_ADMIN + 'dashboard/getExamesByGener',
        type: 'GET',
        dataType: 'json',
        data:{}
    })
    .done(function(data) {

        var body = [];
        var geners = [];

       var positivos = [];
       var negativos = [];
       var inconclusivo = [];

        $.each(data, function (index, gener) {
            
            positivos.push(gener.Positivo);
            negativos.push(gener.Negativo);
            inconclusivo.push(gener.Inconclusivo);

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
                name: 'Inconclusivo',
                data: inconclusivo
            });


        renderExamesGener(body, geners)
    });
}

function runExamesGlobal() {
     $.ajax({
        url: BASE_URL_ADMIN + 'dashboard/getExamesGlobal',
        type: 'GET',
        dataType: 'json',
        data:{}
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
        series: [dados.Positivo, dados.Negativo, dados.Inconclusivo,],
        labels: ["Positivo", "Negativo", "Inconclusivo"],
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


function runExamesUF() {
    $.ajax({
        url: BASE_URL_ADMIN + 'dashboard/getExamesByUf',
        type: 'GET',
        dataType: 'json',
        data:{}
    })
    .done(function(data) {
       var body = [];
       var ufR = [];

       var positivos = [];
       var negativos = [];
       var inconclusivo = [];

        $.each(data, function (index, uf) {
            
            positivos.push(uf.Positivo);
            negativos.push(uf.Negativo);
            inconclusivo.push(uf.Inconclusivo);

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
                name: 'Inconclusivo',
                data: inconclusivo
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
                text: '$ (thousands)'
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
                text: '$ (thousands)'
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
                text: '$ (thousands)'
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