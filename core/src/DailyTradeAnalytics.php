<?php

namespace Core;

use Medoo\Medoo;

class DailyTradeAnalytics extends TradeAnalytics
{
    const TBL_DAILY_TRADES = "daily_trades";
    const TBL_BACKTEST_TRADES = "backtest_trades";
    /**
     * @var Connection
     */
    private $con;
    /**
     * @var Medoo
     */
    private $connection;

    public function __construct()
    {
        $connection = new Connection();
        $this->con = $connection;
        $this->connection = $connection->getConnection();
    }

    public function getDailyTradeAnalytics() {
        $from = isset($_POST["from"]) ? $_POST["from"] : false;
        $to = isset($_POST["to"]) ? $_POST["to"] : false;
        $qty = isset($_POST["qty"]) ? $_POST["qty"] : 50;
        $type = isset($_GET["type"]) ? $_GET["type"] : "daily_trades";
        $sql = "SELECT * FROM ";
        if ($type=="daily_trades") {
            $sql = $sql . self::TBL_DAILY_TRADES;
        } else {
            $sql = $sql . self::TBL_BACKTEST_TRADES;
        }
        $where = "";
        if ($from) {
            $where = " WHERE date >= '$from'";
        }
        if ($to) {
            if ($from) {
                $where .= " AND date <= '$to'";
            } else {
                $where = " WHERE date <= '$to'";
            }
        }
        $sql .= $where . " ORDER BY date DESC";
        $result = $this->connection->query($sql);
        $rows = $result->fetchAll();
        $trades = $this->con->getResult($rows);
        $result = $this->prepareAnalytics($trades, $qty);
        $analytics["analysis"] = $result["analysis"];
        $analytics["all_trades"] = $result["all_trades"];
        $analytics["start_date"] = $from;
        $analytics["end_date"] = $to;
        $analytics["qty"] = $qty;
        $analytics["type"] = $type;
        return $analytics;
    }
}