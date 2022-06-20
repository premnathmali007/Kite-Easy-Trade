<?php

namespace Core;

use Medoo\Medoo;

class DailyTrade
{
    const TBL_DAILY_TRADES = "daily_trades";
    /**
     * @var Medoo
     */
    private $connection;

    public function __construct()
    {
        $connection = new Connection();
        $this->connection = $connection->getConnection();
    }

    private function getLotSize($symbol) {
        $result = $this->connection->query("SELECT `lot_size`  FROM `trade_symbol` WHERE `symbol` = '" . $symbol . "'");
        $lotSize = $result->fetchColumn();
        return (int)$lotSize;
    }

    public function addDailyTrade() {
        $result = [
            "status" => 1,
            "message" => "",
            "current_trade" => $_POST
        ];
        try {
            if (!isset($_POST["setup"])) {
                throw new \Exception("Please select the setup");
            }
            $symbol = $_POST["symbol"];
            $date = $_POST["date"];
            $lotSize = $this->getLotSize($_POST["symbol"]);
            $qty = $_POST["lots"] * $this->getLotSize($_POST["symbol"]);
            $tradeType = $_POST["trade_type"];
            $points = $_POST["points"];
            $ratio = isset($_POST["custom_ratio"]) && $_POST["custom_ratio"] ? $_POST["custom_ratio"] : $_POST["ratio"];
            $setup = implode(",", $_POST["setup"]);
            $notes = isset($_POST["notes"]) ? $_POST["notes"] : "";
            $insertTrade = sprintf("INSERT INTO `daily_trades` 
                (`entity_id`, `date`, `symbol`, `trade_type`, `qty`, `lot_size`, `ratio`, `setup`, `points`, `notes`) 
                VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                $date, $symbol, $tradeType, $qty, $lotSize, $ratio, $setup, $points, $notes
            );
            $this->connection->query($insertTrade);
        } catch (\Exception $e) {
            $result["status"] = 0;
            $result["message"] = $e->getMessage();
        }
        return $result;
    }

    public function getDailyTrades() {
        $from = isset($_POST["from"]) ? $_POST["from"] : false;
        $to = isset($_POST["to"]) ? $_POST["to"] : false;

        $sql = "SELECT * FROM " . self::TBL_DAILY_TRADES;
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
        $sql .= $where . " ORDER BY entity_id DESC";
        $result = $this->connection->query($sql);
        $rows = $result->fetchAll();
        $trades=[];
        foreach ($rows as $row){
            $currentRow = [];
            foreach ($row as $key => $value) {
                if (is_string($key)){
                    $currentRow[$key] = $value;
                }
            }
            $trades[] = $currentRow;
        }
        return $trades;
    }
}