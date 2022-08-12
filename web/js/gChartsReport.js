const form = $("#reportes-id");
const colors = ['#102542', '#F87060', '#B1EFE8', '#96BDC6', '#C0C6C4'];

let chartData;
let rowsData;
let avg, max;
let esComparativo;
let maxCol = 1;

let fechaFin1;
let fechaFin2;
let gerencia1;
let gerencia2;

$(function () {

    $("input[type=radio][name=type]").change(function () {
        if (this.value == "single")
            $("#comparative").addClass("hidden");
        else
            $("#comparative").removeClass("hidden");
    });

    $('#ordenesservicio-os_fkestatus').on('change', function (e) {
        if (this.value == 4)
            $("#motivo1").removeClass("hidden");
        else
            $("#motivo1").addClass("hidden");
    });

    $('#estatus2').on('change', function (e) {
        if (this.value == 4)
            $("#motivo2").removeClass("hidden");
        else
            $("#motivo2").addClass("hidden");
    });

    $("#gerencias-ger_clave, #gerencias-ger_clave2").on("change", function (e) {

        var index = e.target.id == "gerencias-ger_clave2" ? "2" : "";

        $("#user" + index + " option").not(':first').each(function (i, item) {
            $(item).prop("disabled", true);
            $(item).prop("hidden", true);
        });

        $("#user" + index + " option[data-type=" + this.value + "]").each(function (i, item) {
            $(item).prop("disabled", false);
            $(item).prop("hidden", false);
        });

        $("#user" + index + " option").first().prop("selected", true);
    });
});

$("#submitButtonExcel, #submitButtonPdf").click(function (e) {

    $("#" + e.currentTarget.id).html('Generando reporte&hellip; <i class="fas fa-cog fa-spin" style= "font-size: 1.1em"></i>');

    switch(e.currentTarget.id) {
        case "submitButtonExcel":
            document.querySelector("#reportes-id").target = "_self";
            $("#format").val("excel");

            setCookie('downloading', 1, 1);
            form.submit();

            let timerId = setInterval(function() {
                if(getCookie("downloading") == 0) {
                    clearInterval(timerId);
                    $("#submitButtonExcel").html('Generar excel');
                }
            }, 100);
            break;
        default:
            document.querySelector("#reportes-id").target = "_blank";
            $("#format").val("pdf");
            callChartData();
    }
});

function callChartData() {
    $.ajax({
        url: "/reportes/chart",
        method: "post",
        data: form.serialize(),
        asynchronous: false,
        success: function (r) {

            chartData = JSON.parse(r);
            esComparativo = (typeof chartData[2] == "object");
            r = chartData[0];

            //Dando la forma que espera gCharts
            rowsData = Object.keys(r).map(k => Object.keys(r[k]).map(j => r[k][j]));

            //Maximo de la primer serie
            max = rowsData.reduce((acc, item, i) => item[1] >= rowsData[acc][1] ? i : acc, 0);

            if (!esComparativo) {
                maxCol = 1;
            }
            else {
                //maximo de la segunda serie
                max2 = rowsData.reduce((acc, item, i) => item[2] >= rowsData[acc][2] ? i : acc, 0);

                if (rowsData.length != 0 && rowsData[max][1] <= rowsData[max2][2]) {
                    max = max2;
                    maxCol = 2;
                }
            }

            rowsData.forEach(function (item, index) {
                var arr = item[0].split("-");
                var day = parseInt(arr[2]);
                var month = parseInt(arr[1]) - 1;
                var year = parseInt(arr[0]);

                rowsData[index][0] = new Date(year, month, day);

                if (esComparativo) {

                    rowsData[index][3] = rowsData[index][2];

                    var color = colors.shift();
                    colors.push(color);
                    color = `stroke-color: #ff0000; stroke-width: 1; fill-color: ${color}`;
                    rowsData[index][4] = color;
                }

                var color = colors.shift();
                colors.push(color);
                color = `stroke-color: #0000FF; stroke-width: 1; fill-color: ${color}`;
                rowsData[index][2] = color;
            });

            google.charts.load('current', { packages: ['corechart', 'bar'] });
            google.charts.setOnLoadCallback(drawChart);
        }
    });
}

function drawChart() {
    var data = new google.visualization.DataTable();
    var tit = "";

    fechaFin1 = $("#ordenesservicio-os_fechafinalizacion").val() || "HISTÓRICO";
    fechaFin2 = $("#ordenesservicio-os_fechafinalizacion2").val() || "HISTÓRICO";
    user1 = $("#user option:selected").val() != "" ? " [" + $("#user option:selected").text() + "]" : "";
    user2 = $("#user2 option:selected").val() != "" ? " [" + $("#user option:selected").text() + "]" : "";
    gerencia1 = $("#gerencias-ger_clave  option:selected").val() != "" ? $("#gerencias-ger_clave option:selected").text() + user1 : "";
    gerencia2 = $("#gerencias-ger_clave2 option:selected").val() != "" ? $("#gerencias-ger_clave2 option:selected").text() + user2 : "";

    data.addColumn('date', 'Fecha');
    data.addColumn('number', gerencia1 + ' [' + fechaFin1 + ']');
    data.addColumn({
        type: 'string',
        role: 'style'
    });

    if (esComparativo) {
        tit = "Comparacion de periodos (Servicios ejecutados)";
        data.addColumn('number', gerencia2 + ' [' + fechaFin2 + ']');
        data.addColumn({
            type: 'string',
            role: 'style'
        });

    }
    else {
        tit = "Productividad en el periodo " + $("#ordenesservicio-os_fechafinalizacion").val();
    }

    data.addRows(rowsData);

    var options = {
        title: tit,
        legend: { position: 'top', alignment: "center", textStyle: { color: 'gray', fontSize: 16 } },
        titleTextStyle: {
            color: "#424242",
            fontSize: 23,
            bold: true,
            italic: true
        },
        height: 500,
        hAxis: {
            title: 'Días',
            format: 'dd-MM-yy',
            showTextEvery: 2,
            minTextSpacing: 10,
        },
        vAxis: {
            title: 'Cantidad'
        },
        series: {
            0: { pointShape: { type: 'triangle', rotation: 180 }, pointSize: 12, },
            1: { pointShape: { type: 'circle' }, opacity: .4 },
        },
    };

    var chart_div = document.getElementById('chart_div');
    var chart = new google.visualization.ColumnChart(chart_div);

    google.visualization.events.addListener(chart, 'ready', function () {

        chart.setSelection([{ row: max, column: maxCol }]);
        $("#graph").val(chart.getImageURI().substring(22));
        
        form.submit();
        $("#submitButtonPdf").html('Generar PDF');
    });

    chart.draw(data, options);
}
