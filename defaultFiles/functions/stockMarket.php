<?php
/**
 * Created by PhpStorm.
 * User: danielpfeffer
 * Date: 07.05.18
 * Time: 17:08
 */

$api_key = "8IC1KW4FEJ2EDU3D";
$market = "EUR";
$defaultList = array();
$favList = array();
$symbolsList = array();
$last = array();
$prevLast = array();
$minList = array();
$listToDrawChart = array();
$lastIndex = 0;
$cntFull = 1;

array_push($defaultList, "MSFT");
array_push($defaultList, "AAPL");
array_push($defaultList, "PPLT");
array_push($defaultList, "BTC");
array_push($defaultList, "DJI");
array_push($defaultList, "DAX");

getFavouriteSymbolsFromDatabase();
if (sizeof($favList) == 0) {
    $symbolsList = $defaultList;
} else {
    $symbolsList = $favList;
    $sizeToMerge = 6 - sizeof($favList);
    for ($i = 0; $i < $sizeToMerge; $i++) {
        if (in_array($defaultList[$i], $symbolsList)) {
            $sizeToMerge++;
        } else {
            array_push($symbolsList, $defaultList[$i]);
            //$sizeToMerge++;
        }
    }
}
foreach ($symbolsList as $index) {
    $dataPoints = array();
    $file = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" . $index . "&apikey=" . $api_key;
    $fileCC = "https://www.alphavantage.co/query?function=DIGITAL_CURRENCY_DAILY&symbol=" . $index . "&market=" . $market . "&apikey=" . $api_key;
    if ($file) {
        $json = json_decode(file_get_contents($file));
        $real = $json->{"Time Series (Daily)"};
        if ($real) {
            $startDate = time();
            for ($cnt = 0; $cnt < 100; $cnt++) {
                $date = date("Y-m-d", strtotime("-$cnt day", $startDate));
                $yaxis = $real->{$date}->{"4. close"};
                if ($cnt === 0 || ($yaxis != null && sizeof($last) != $cntFull)) {
                    if ($yaxis != null) {
                        $lastIndex = $cnt;
                        array_push($last, $yaxis);
                    }
                }
                if ($cnt == $lastIndex + 1 && $yaxis != null) {
                    array_push($prevLast, $yaxis);
                } elseif ($yaxis == null) {
                    $lastIndex++;
                }
                if ($yaxis != null && $yaxis != 0) {
                    array_push($dataPoints, array("y" => $yaxis, "label" => $date));
                }
            }
            array_push($listToDrawChart, $dataPoints);
            $min = min($dataPoints);
            $min = $min['y'];
            array_push($minList, $min);
        }
        //https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=MSFT&apikey=8IC1KW4FEJ2EDU3D
    }
    if ($fileCC) {
        $json = json_decode(file_get_contents($fileCC));
        $real = $json->{"Time Series (Digital Currency Daily)"};
        if ($real) {
            $startDate = time();
            for ($cnt = 0; $cnt < 100; $cnt++) {
                $date = date("Y-m-d", strtotime("-$cnt day", $startDate));
                $yaxis = $real->{$date}->{"4a. close (EUR)"};
                if ($cnt === 0 || ($yaxis != null && sizeof($last) != $cntFull)) {
                    if ($yaxis != null) {
                        array_push($last, $yaxis);
                    }
                }
                if ($cnt == $lastIndex + 1 && $yaxis != null) {
                    array_push($prevLast, $yaxis);
                } elseif ($yaxis == null) {
                    ++$lastIndex;
                }
                if ($yaxis != null && $yaxis != 0) {
                    array_push($dataPoints, array("y" => $yaxis, "label" => $date));
                }
            }
            array_push($listToDrawChart, $dataPoints);
            $min = min($dataPoints);
            $min = $min['y'];
            array_push($minList, $min);
        }
        //https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=MSFT&apikey=8IC1KW4FEJ2EDU3D
    }
    $cntFull++;
    $lastIndex = 0;
}
?>
    <!--suppress ALL -->
    <script>
        const everyDataPoint = <?php echo json_encode($listToDrawChart, JSON_NUMERIC_CHECK)?>;
        const everyMinimum = <?php echo json_encode($minList, JSON_NUMERIC_CHECK)?>;
        const everySymbol = <?php echo json_encode($symbolsList)?>;

        function renderChart(id) {
            var oldID
            var min;
            if (everyMinimum[id] > 10) {
                min = everyMinimum[id] - 10;
            } else {
                min = 0;
            }
            let chart = new CanvasJS.Chart("chartContainer", {
                zoomEnabled: true,
                title: {
                    text: "Stock Market of " + everySymbol[id]
                },
                axisY: {
                    title: "€",
                    minimum: min
                },
                axisX: {
                    title: "Date",
                }
                ,
                data: [{
                    type: "line",
                    color: "green",
                    dataPoints: everyDataPoint[id].reverse()
                }
                ]
            });
            chart.render();
        }

        window.onload = function () {
            createIcons();
        }

        function changePic(pic) {
            let defPath = pic.src;
            if (defPath.endsWith("unfav.svg")) {
                pic.src = "../icons/black/fav.svg";
                manageFavourite(0, pic.id.slice(3, pic.id.length));
            } else {
                pic.src = "../icons/black/unfav.svg";
                manageFavourite(1, pic.id.slice(3, pic.id.length));
            }
        }
    </script>
    <script>
        function manageFavourite(mode, symbol) {
            var xml = new XMLHttpRequest();
            //TODO: FAVOURITE MANAGEMENT
            xml.open("GET", "functions/manageFavourite.php?symbol=" + symbol + "&method=" + mode);
            xml.send();
        }
    </script>
    <script src="../css/chartjs/canvasjs.min.js"></script>
<?php
drawOutput();
?>
    <script>
        function createIcons() {
            const symbolsArray = <?php echo json_encode($symbolsList) ?>;
            let size = symbolsArray.length;
            for (var i = 0; i < size; i++) {
                const node = document.getElementById(symbolsArray[i]);
                const value = node.innerText;
                node.setAttribute("style", value > 0 ? "color: green" : "color: red");
                let img = document.createElement("img");
                img.setAttribute("src", value > 0 ? "../icons/other/trending_up.svg" : "../icons/other/trending_down.svg");
                node.appendChild(img);
            }
        }
    </script>
    <div id="chartContainer" style="height: 370px; width: 90%;"></div>

<?php
function drawOutput()
{
    global $last, $symbolsList, $prevLast, $favList;
    $size = sizeof($last);
    for ($i = 0; $i < $size; $i++) {
        $symbol = $symbolsList[$i];
        $lastY = $last[$i];                 //Last Value of every Symbol
        $lastP = $prevLast[$i];             //Previous Last Value for every Symbol
        $perc = $lastY / $lastP * 100 - 100;//Percentage
        $isFav = in_array($symbol, $favList);
        $imgSrc = $isFav ? "../icons/black/fav.svg" : "../icons/black/unfav.svg";
        printf("<img src=$imgSrc id=\"pic$symbol\" onclick=\"changePic(this)\"/><a onclick='renderChart($i)'>%s: %.2f<a id='$symbol'> %+-.2f</a></a><br>", $symbol, $lastY, $perc);
    }
}

function getFavouriteSymbolsFromDatabase()
{
    global $favList, $conn;

    $user = $_SESSION['user'];

    $sqlFavSymbols = "SELECT stockIndex FROM stocks WHERE stockID IN(SELECT stockID FROM favStock WHERE userID LIKE $user[0]);";
    $res = $conn->query($sqlFavSymbols);//Res = Result
    if ($res) {
        while ($row = $res->fetch_row()) {
            array_push($favList, $row[0]);
        }
    }
}

?>