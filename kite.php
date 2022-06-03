<html>
<head>
    <title>Kite Analytics</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
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
$pnlPerDay = [];
$timePerTrade = [];
$analytics = [
    "start_date" => "",
    "end_date" => "",
    "max_profit" => 0,
    "max_loss" => 0,
    "max_day_profit" => 0,
    "max_day_loss" => 0,
    "max_time" => 0,
    "min_time" => 100000,
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
            $tradeExecutionTime = round(abs($date1->getTimestamp() - $date2->getTimestamp()) / 60,2);
            $timePerTrade[]=$tradeExecutionTime;

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
            $pnlPerDay[$order["trade_date"]] = isset($pnlPerDay[$order["trade_date"]]) ? $pnlPerDay[$order["trade_date"]] + $pnl : $pnl;
            if ($pnl>0) {
                if ($pnl > $analytics["max_profit"]){
                    $analytics["max_profit"] = $pnl;
                }
                $analytics["total_wins"] += 1;
            } else {
                if ($pnl < $analytics["max_loss"]){
                    $analytics["max_loss"] = $pnl;
                }
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
                "order_time" => $tradeExecutionTime,
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
    $analytics["max_day_profit"] = max($pnlPerDay);
    $analytics["max_day_loss"] = min($pnlPerDay);
    $analytics["max_time"] = max($timePerTrade);
    $analytics["min_time"] = min($timePerTrade);
}
?>
<div class="container">
    <br><h1 style="text-align: center;">Trade analytics</h1>
    <div class="card-columns">
        <div class="card bg-info">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Start Date : " . $analytics["start_date"] . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-info">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>End Date : " . $analytics["end_date"] . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-info">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Working Days : " . $analytics["working_days"] . "</h4>"; ?></p>
            </div>
        </div>
    </div>
    <div class="card-columns">
        <div class="card bg-info">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Count of trades : " . count($trades) . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-success">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Total wins : " . $analytics["total_wins"] . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-danger">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Total losses : " . $analytics["total_losses"] . "</h4>"; ?></p>
            </div>
        </div>
    </div>
    <div class="card-columns">
        <div class="card bg-info">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Total Long trades : " . $analytics["number_of_long_trades"] . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-success">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Total Long Wins : " . $analytics["long_wins"] . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-danger">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Total Long Losses : " . $analytics["long_losses"] . "</h4>"; ?></p>
            </div>
        </div>
    </div>
    <div class="card-columns">
        <div class="card bg-info">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Total Short trades : " . $analytics["number_of_short_trades"] . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-success">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Total Short Wins : " . $analytics["short_wins"] . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-danger">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Total Short Losses : " . $analytics["short_losses"] . "</h4>"; ?></p>
            </div>
        </div>
    </div>
    <div class="card-columns">
        <div class="card bg-info">
            <div class="card-body text-center">
                <p class="card-text"><h4>Max Profit / Loss For Per Trade</h4></p>
            </div>
        </div>
        <div class="card bg-success">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Max Profit Per Trade : " . $analytics["max_profit"] . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-danger">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Max Loss Per Trade : " . $analytics["max_loss"] . "</h4>"; ?></p>
            </div>
        </div>
    </div>
    <div class="card-columns">
        <div class="card bg-info">
            <div class="card-body text-center">
                <p class="card-text"><h4>Max Profit / Loss For Per Day</h4></p>
            </div>
        </div>
        <div class="card bg-success">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Max Profit Per Day : " . $analytics["max_day_profit"] . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-danger">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Max Loss Per Day : " . $analytics["max_day_loss"] . "</h4>"; ?></p>
            </div>
        </div>
    </div>
    <div class="card-columns">
        <div class="card bg-info">
            <div class="card-body text-center">
                <p class="card-text"><h4>Trade time</h4></p>
            </div>
        </div>
        <div class="card bg-success">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Min Time Taken Trade : " . $analytics["min_time"] . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-danger">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Max Time Taken Trade : " . $analytics["max_time"] . "</h4>"; ?></p>
            </div>
        </div>
    </div>
    <div class="card-columns">
        <div class="card bg-info">
            <div class="card-body text-center">
                <p class="card-text"><h4>Final Analytics : </h4></p>
            </div>
        </div>
        <div class="card bg-info">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Win Rate : " . $analytics["win_rate"] . "</h4>"; ?></p>
            </div>
        </div>
        <div class="card bg-<?php echo $analytics["total_pnl"]>0 ? 'success' : 'danger';?>">
            <div class="card-body text-center">
                <p class="card-text"><?php echo "<h4>Final PNL : " . $analytics["total_pnl"] . "</h4>"; ?></p>
            </div>
        </div>
    </div>
</div>
<br><br>
<div class="center">
  <h1 style="text-align: center;">All Trades</h1>
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

<style>
    .center {
      text-align: center;
      border: 3px solid green;
    }
    .container{
        max-width: none !important;
        width: 100%;
        margin: 5px 5px 5px 5px;
    }
</style>