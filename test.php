<?php
/**
 * Created by PhpStorm.
 * User: Daniel Pfeffer
 * Date: 20.04.18
 */

$api_key = "8IC1KW4FEJ2EDU3D";
$firma = "ATX";
    $file = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $firma . "&apikey=$api_key";
    $dataPoints = array();
    if ($file) {
        $json = json_decode(file_get_contents($file));
        $real = $json->{"Time Series (Daily)"};
        $startDate = time();
        for ($cnt = 0; $cnt < 100; $cnt++) {
            $date = date("Y-m-d", strtotime("-$cnt day", $startDate));
            $yaxis = $real->{$date}->{"1. open"};
            if ($yaxis != null && $yaxis != 0) {
                array_push($dataPoints, array("y" => $yaxis, "label" => $date));
            }
        }
        $min = min($dataPoints);
        $min = $min['y'];

    //https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=MSFT&apikey=8IC1KW4FEJ2EDU3D
}
?>
<script>

    window.onload = function () {

        let chart = new CanvasJS.Chart("chartContainer", {
            zoomEnabled: true,
            title: {
                text: "Stock Market of " +<?php echo "\"$firma\""?>
            },
            axisY: {
                title: "€",
                minimum: <?php echo $min - 10 ?>
            },
            axisX: {
                title: "Date",
            },
            data: [{
                type: "line",
                color: "green",
                dataPoints:  <?php echo json_encode(array_reverse($dataPoints), JSON_NUMERIC_CHECK);?>
            }
            ]
        });
        chart.render();
    }
</script>
<body>
<div id="chartContainer" style="height: 370px; width: 90%;"></div>
<div><a>Add to Favourite</a></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>