<?php

namespace Core;

use Medoo\Medoo;

class Analytics
{
    const TBL_TRADEBOOK_REAL = "tradebook_real";
    /**
     * @var Medoo
     */
    private $connection;

    public function __construct()
    {
        $connection = new Connection();
        $this->connection = $connection->getConnection();
    }

    public function getAnalytics() {
        $from = isset($_POST["from"]) ? $_POST["from"] : false;
        $to = isset($_POST["to"]) ? $_POST["to"] : false;

        $sql = "SELECT * FROM " . self::TBL_TRADEBOOK_REAL;
        $where = "";
        if ($from) {
            $where = " WHERE trade_date >= '$from'";
        }
        if ($to) {
            if ($from) {
                $where .= " AND trade_date <= '$to'";
            } else {
                $where = " WHERE trade_date <= '$to'";
            }
        }
        $sql .= $where . " ORDER BY order_execution_time ASC";
        $result = $this->connection->query($sql);
        $rows = $result->fetchAll();
        $orders = [];
        foreach ($rows as $row) {
            $row["checksum"] = false;
            $orders[] = $row;
        }
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
                    $date1 = new \DateTime($order["order_execution_time"]);
                    $date2 = new \DateTime($orders[$j]["order_execution_time"]);
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
                        "trade_type" => $tradeType,
                        "order_execution_time" => $order["order_execution_time"],
                        "quantity" => $order["quantity"],
                        "buy_price" => $buyPrice,
                        "sell_price" => $sellPrice,
                        "pnl" => $pnl
                    ];
                    break;
                }
            }
        }
        $analytics['trades'] = $trades;
        $analytics['pnlPerDay'] = $pnlPerDay;
        $analytics['timePerTrade'] = $timePerTrade;
        if (count($trades)) {
            $analytics["start_date"] = $trades[0]["trade_date"];
            $analytics["end_date"] = $trades[count($trades)-1]["trade_date"];
            $analytics["win_rate"] = $analytics["total_wins"] / count($trades) * 100;
            $analytics["max_day_profit"] = max($analytics['pnlPerDay']);
            $analytics["max_day_loss"] = min($analytics['pnlPerDay']);
            $analytics["max_time"] = max($analytics['timePerTrade']);
            $analytics["min_time"] = min($analytics['timePerTrade']);
        }
        return $analytics;
    }

}