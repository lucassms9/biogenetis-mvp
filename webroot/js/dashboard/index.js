// Pie Chart
if ($('#pie_chart').length) {
    var options = {
        chart: {
            height: 320,
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