<?php
namespace Core;

use Medoo\Medoo;
use \Core\Connection;
class ImportTradebook
{
    const TBL_TRADEBOOK = "tradebook";
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

    private function updateRealTradebook() {
        $this->connection->delete(self::TBL_TRADEBOOK_REAL, ["entity_id[>]" => 0]);
        $query = "INSERT INTO tradebook_real SELECT entity_id, symbol, isin, trade_date, exchange, segment, series, trade_type, SUM(quantity) AS quantity, SUM(price)/COUNT(*) AS price, trade_id, order_id, order_execution_time FROM `tradebook` GROUP BY order_id ORDER BY entity_id";
        $this->connection->query($query);
    }

    /**
     * @return int
     */
    private function getLastTradebookEntityId() {
        $query = "SELECT entity_id FROM " . self::TBL_TRADEBOOK . " ORDER BY entity_id DESC LIMIT 1";
        $result = $this->connection->query($query)->fetchColumn();
        return $result ? (int)$result : 0;
    }

    private function resetTradebookByOrderIds($orderIds) {
        $this->connection->delete(self::TBL_TRADEBOOK, ["order_id"=>$orderIds]);
    }

    /**
     * @return array
     */
    public function readCsvFile() {
        $filename=$_FILES["fileToUpload"]["tmp_name"];
        if($_FILES["fileToUpload"]["size"] > 0)
        {
            $tradeBook = [];
            $columns = [];
            $file = fopen($filename, "r");
            $i = $this->getLastTradebookEntityId() + 1;
            while (($csvData = fgetcsv($file, 100000, ",")) !== FALSE)
            {
                if(empty($columns)){
                    $columns = $csvData;
                    $columns[]="entity_id";
                    continue;
                }
                $this->orderIds[] = $csvData[10];
                $csvData[]=$i;
                $i++;
                $tradeBook[] = array_combine($columns, $csvData);
            }
            fclose($file);
        }
        array_shift($tradeBook);
        return $tradeBook;
    }

    public function execute() {
        $tradebook = $this->readCsvFile();
        $this->importTradebook($tradebook);
    }

    private function importTradebook($tradebook) {
        $this->resetTradebookByOrderIds($this->orderIds);
        $this->connection->insert(self::TBL_TRADEBOOK, $tradebook);
        $this->updateRealTradebook();
    }
}