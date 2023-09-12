<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Tráfico mensual</title>
<!-- referencia a jQuery y a Highcharts -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="http://code.highcharts.com/highcharts.js"></script>
</head>
    <body>
        <!-- div que contendrá la gráfica lineal -->
        <div id="linea" style="width: 50%; height: 350px; margin: 0 auto;float:left;"></div>
        <!-- div que contendrá la gráfica circular -->
        <div id="pie" style="width: 50%; height: 350px; margin: 0 auto;float:left;"></div>
         
        <div style="border-top:1px solid #CDCDCD;margin:10px;padding:0;clear:both;"></div>
 
        <!-- div que contendrá la gráfica a tiempo real -->
        <div id="tiempoReal" style="height: 400px; margin: 0 auto;"></div>
    </body>
</html>
<script>
var ano = <?php echo $ano;?>;
var porcentaje = <?php echo $porcentaje;?>;
var presupuesto = <?php echo $presupuesto;?>;
$('#pie').highcharts({
    chart: {
        type: 'pie', // tipo de gráfica circular
        borderWidth: 0
    },
    title: {
        text: 'PRESUPUESTO MENSUAL', // título
    },
    subtitle: {
            text: 'AÑO: ' +ano,
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    series: [{
        name: 'Informacion mensual',
        data: [ // configuración de cada pedazo de la gráfica
            {
                name: 'Presupueso',
                y: presupuesto,
                sliced: true,
                selected: true
            },
            ['porcentaje',       14.0],
            ['Bing.com',     15.0]
        ]
    }]
});
</script>

</body>
</html>

