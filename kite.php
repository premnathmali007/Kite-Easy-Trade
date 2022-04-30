<html>
<head>
  <title>Kite Analytics</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<?php
$servername = "localhost";
$username = "prem";
$password = "prem";
$dbname = "kite_practice";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM tradebook_real";
$result = $conn->query($sql);
$orders = [];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $row["checksum"] = false;
        $orders[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();

//build jurnal
$trades = [];
$analytics = [
    "start_date" => "",
    "end_date" => "",
    "number_of_long_trades" => 0,
    "number_of_short_trades" => 0,
    "long_wins" => 0,
    "long_losses" => 0,
    "short_wins" => 0,
    "short_losses" => 0,
    "working_days" => 0,
    "total_wins" => 0,
    "total_losses" => 0,
    "total_pnl" => 0,
];
$currentDate = null;
foreach ($orders as $i => $order) {

    if ($orders[$i]["checksum"]){
        continue;
    }
    for($j=$i+1; $j<count($orders); $j++) {

        if ($orders[$j]["isin"] == $order["isin"] && $orders[$j]["quantity"] == $order["quantity"] && $orders[$j]["trade_date"] == $order["trade_date"]){
            //set order checksum
            $orders[$j]["checksum"] = true;
            $orders[$i]["checksum"] = true;

            //calculate trade time
            $date1 = new DateTime($order["order_execution_time"]);
            $date2 = new DateTime($orders[$j]["order_execution_time"]);
            $tradeExecutionTime = abs($date1->getTimestamp() - $date2->getTimestamp()) / 60;

            //get buy and sell price
            $buyPrice = $order["trade_type"] == "buy" ? $order["price"] : $orders[$j]["price"];
            $sellPrice = $order["trade_type"] == "sell" ? $order["price"] : $orders[$j]["price"];

            //Calculate pnl
            $qty = $order["quantity"];
            $pnl = $buyPrice < $sellPrice ? ($sellPrice - $buyPrice) * $qty : ($sellPrice - $buyPrice) * $qty;
			$pnl = round($pnl,2);
            //calculate $analytics
            $tradeType = $order["trade_type"] == "buy" ? "Long" : "Short";
            $analytics["total_pnl"] = $analytics["total_pnl"] + $pnl;
            if($tradeType == "Long") {
                $analytics["number_of_long_trades"]++;
                $pnl > 0 ? $analytics["long_wins"]++ : $analytics["long_losses"]++;
            } else {
                $analytics["number_of_short_trades"]++;
                $pnl > 0 ? $analytics["short_wins"]++ : $analytics["short_losses"]++;
            }
            if($currentDate && $currentDate != $order["trade_date"]) {
                $analytics["working_days"]++;
                $currentDate = $order["trade_date"];
            }
            if(!$currentDate){
                //first day
                $currentDate=$order["trade_date"];
                $analytics["working_days"]++;
            }

            if ($pnl>0) {
                $analytics["total_wins"] += 1;
            } else {
                $analytics["total_losses"] += 1;
            }

            $trades[] = [
                "trade_id" => $order["entity_id"],
                "symbol" => $order["symbol"],
                "isin" => $order["isin"],
                "trade_date" => $order["trade_date"],
//                "exchange" => $order["exchange"],
//                "segment" => $order["segment"],
//                "series" => $order["series"],
                "trade_type" => $tradeType,
//                "order_details" => [[$order["order_id"], $order["order_execution_time"]], [$orders[$j]["order_id"], $orders[$j]["order_execution_time"]]],
                "order_execution_time" => $order["order_execution_time"],
                "order_time" => round($tradeExecutionTime),
                "quantity" => $order["quantity"],
                "buy_price" => $buyPrice,
                "sell_price" => $sellPrice,
                "pnl" => $pnl
            ];
            break;
        }
    }
}

if (count($trades)>0) {
    $analytics["start_date"] = $trades[0]["trade_date"];
    $analytics["end_date"] = $trades[count($trades)-1]["trade_date"];
    $analytics["win_rate"] = $analytics["total_wins"] / count($trades) * 100;
}
?>
<h1>Trade analytics</h1>
<?php
echo "<h4>Count of orders : " . count($orders) . "</h4>";
echo "<h4>Count of trades : " . count($trades) . "</h4>";
echo "<h4>Count of trades should be : " . count($orders)/2 . "</h4>";
    foreach ($analytics as $key => $value) {
        echo "<h4>" . ucwords(str_replace("_", " ", $key)) . " : " . $value . "</h4>";
    }
?>
<div class="center">
  <h3 style="text-align: center;">All Trades</h3>
</div>

<table  class="table table-bordered">
    <thead>
    <?php
    $columns = array_keys($trades[0]);
    echo "<tr>";
    foreach ($columns as $column) {
        echo "<th>";
        echo ucwords(str_replace("_", " ", $column));
        echo "</th>";
    }
    echo "</tr>";
    ?>
    </thead>
    <tbody>
    <?php
    foreach ($trades as $trade) {
		$trClass = $trade["pnl"]>0 ? "success" : "danger"; 
        echo '<tr class="' . $trClass . '" >';  		
        foreach ($trade as $key => $value) {
            echo "<td>";
            echo $value;
            echo "</td>";
        }
        echo "</tr>";
    }
    ?>
    </tbody>
</table>
</body>
</html>

<styles>
.center {
  text-align: center;
  border: 3px solid green;
}
</styles>