<?php
namespace Core;

use Medoo\Medoo;
use \Core\Connection;
class ImportFundStatement
{
    const TBL_TRADEBOOK = "tradebook";
    const TBL_TRADEBOOK_REAL = "tradebook_real";
    const TBL_FUND_STATEMENT = "fund_statement";
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
    private function getLastEntityId() {
        $query = "SELECT entity_id FROM " . self::TBL_FUND_STATEMENT . " ORDER BY entity_id DESC LIMIT 1";
        $result = $this->connection->query($query)->fetchColumn();
        return $result ? (int)$result : 0;
    }

    private function resetLedgerByparticulars($particulars) {
        $this->connection->delete(self::TBL_FUND_STATEMENT, ["particulars"=>$particulars]);
    }

    /**
     * @return array
     */
    public function readCsvFile() {
        $filename=$_FILES["fileToUpload"]["tmp_name"];
        if($_FILES["fileToUpload"]["size"] > 0)
        {
            $skipParticulars = [
                "Opening Balance",
                "Funds added",
                "Closing Balance",
                "Securities Transaction",
                "Funds transferred",
            ];
            $ledger = [];
            $columns = [];
            $file = fopen($filename, "r");
            $i = $this->getLastEntityId() + 1;
            while (($csvData = fgetcsv($file, 100000, ",")) !== FALSE)
            {
                if(empty($columns)){
                    $columns = $csvData;
                    $columns[]="entity_id";
                    continue;
                }
                $skipParticular = false;
                foreach ($skipParticulars as $particular) {
                    if (strpos($csvData[0], $particular) !== false) {
                        $skipParticular = true;
                    }
                }
                if ($skipParticular) {
                    continue;
                }
                $this->particulars[] = $csvData[0];
                $csvData[]=$i;
                $i++;
                $ledger[] = array_combine($columns, $csvData);
            }
            fclose($file);
        }
        return $ledger;
    }

    public function execute() {
        $ledger = $this->readCsvFile();
        $this->importLedger($ledger);
        return true;
    }

    private function importLedger($ledger) {
        $this->resetLedgerByparticulars($this->particulars);
        $this->connection->insert(self::TBL_FUND_STATEMENT, $ledger);
    }
}