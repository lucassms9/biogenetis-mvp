// Pie Chart
if ($('#pie_chart').length) {
    var options = {
        chart: {
            height: 300,
            type: 'pie',
        },
        series: [44, 55, 41,],
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
        series: [{
            name: 'Net Profit',
            data: [46, 57, 59, 54, 62, 58, 64, 60, 66]
        }, {
            name: 'Revenue',
            data: [74, 83, 102, 97, 86, 106, 93, 114, 94]
        }, {
            name: 'Free Cash Flow',
            data: [37, 42, 38, 26, 47, 50, 54, 55, 43]
        }],
        colors: ['#eff2f7', '#3051d3','#00a7e1'],
        xaxis: {
            categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
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
                    return "$ " + val + " thousands"
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
        series: [{
            name: 'Net Profit',
            data: [46, 57, 59, 54, 62, 58, 64, 60, 66]
        }, {
            name: 'Revenue',
            data: [74, 83, 102, 97, 86, 106, 93, 114, 94]
        }, {
            name: 'Free Cash Flow',
            data: [37, 42, 38, 26, 47, 50, 54, 55, 43]
        }],
        colors: ['#eff2f7', '#3051d3','#00a7e1'],
        xaxis: {
            categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
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
                    return "$ " + val + " thousands"
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
        series: [{
            name: 'Net Profit',
            data: [46, 57, 59, 54, 62, 58, 64, 60, 66]
        }, {
            name: 'Revenue',
            data: [74, 83, 102, 97, 86, 106, 93, 114, 94]
        }, {
            name: 'Free Cash Flow',
            data: [37, 42, 38, 26, 47, 50, 54, 55, 43]
        }],
        colors: ['#eff2f7', '#3051d3','#00a7e1'],
        xaxis: {
            categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
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
                    return "$ " + val + " thousands"
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

