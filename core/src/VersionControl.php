<?php

namespace Core;

class VersionControl
{
    const NEW_VERSION = "1.0.2";
    /**
     * @var Connection
     */
    private $connection;

    public function __construct()
    {
        $con = new Connection();
        $this->connection = $con->getConnection();
        $this->compareVersion();
    }

    private function getCurrentVersion() {
        $result = $this->connection->query("SELECT value FROM `core_config_data` WHERE path='version'");
        $currentVersion = $result->fetchColumn();
        if (!$currentVersion) {
            $currentVersion = "1.0.0";
            $this->connection->query("INSERT INTO `core_config_data` (`entity_id`, `path`, `value`) VALUES (null, 'version', '".$currentVersion."')");
        }
        return $currentVersion;
    }

    private function updateCurrentVersion($newVersion) {
        $this->connection->query("UPDATE `core_config_data` SET `value` = '" . $newVersion . "' WHERE `core_config_data`.`path` = 'version'");
    }

    public function compareVersion() {
        $currentVersion = $this->getCurrentVersion();
        if (version_compare($currentVersion,self::NEW_VERSION, '<')) {
            $this->createFundStatementTable();
            $this->createSetupTable();
            $this->createSymbolTable();
            $this->createBacktestTradesTable();
            $this->updateCurrentVersion('1.0.1');
        }
        if (version_compare($currentVersion,self::NEW_VERSION, '<')) {
            $this->createDailyTradesTable();
            $this->updateCurrentVersion('1.0.2');
        }
    }

    private function createFundStatementTable() {
        $query = "CREATE TABLE IF NOT EXISTS `kite_practice`.`fund_statement` ( `entity_id` INT NOT NULL , `particulars` VARCHAR(255) NULL , `posting_date` DATE NULL , `cost_center` VARCHAR(255) NULL , `voucher_type` VARCHAR(255) NULL , `debit` DECIMAL(10,4) NULL , `credit` DECIMAL(10,4) NULL , `net_balance` DECIMAL(10,4) NULL , PRIMARY KEY (`entity_id`)) ENGINE = InnoDB";
        $this->connection->query($query);
    }

    private function createSetupTable() {
        $query = "CREATE TABLE IF NOT EXISTS `kite_practice`.`setups` ( `entity_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Entity Id' , `setup_code` VARCHAR(255) NOT NULL COMMENT 'Setup Code' , `setup` VARCHAR(255) NOT NULL COMMENT 'setup' , PRIMARY KEY (`entity_id`)) ENGINE = InnoDB";
        $this->connection->query($query);
        $insertQuery = "INSERT INTO `setups` (`entity_id`, `setup_code`, `setup`) VALUES 
                                                              (NULL, 'ibh_breakout', 'IBH Breakout'), 
                                                              (NULL, 'ibl_breakout', 'IBL Breakout'), 
                                                              (NULL, 'support_breakout', 'Support Breakout'), 
                                                              (NULL, 'resistance_breakout', 'Resistance Breakout'), 
                                                              (NULL, 'triangle', 'Triangle'), 
                                                              (NULL, 'range_breakout', 'Range Breakout'), 
                                                              (NULL, 'inside_candle', 'Inside Candle'), 
                                                              (NULL, 'down_side_gap_fill', 'Down Side Gap Fill'), 
                                                              (NULL, 'up_side_gap_fill', 'Up Side Gap Fill'), 
                                                              (NULL, 'initiative_buyers', 'Initiative Buyers'), 
                                                              (NULL, 'initiative_sellers', 'Initiative Sellers'),
                                                              (NULL, 'm_pattern', 'M Pattern'),
                                                              (NULL, 'w_pattern', 'W Pattren')
                                                              ";
        $this->connection->query($insertQuery);
    }

    private function createSymbolTable() {
        $query = "CREATE TABLE IF NOT EXISTS `kite_practice`.`trade_symbol` ( 
            `entity_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Entity Id' , 
            `symbol` VARCHAR(255) NOT NULL COMMENT 'Symbol' ,
            `lot_size` INT NOT NULL COMMENT 'lot_size', 
            PRIMARY KEY (`entity_id`)) ENGINE = InnoDB";
        $this->connection->query($query);
        $insertQuery = "INSERT INTO `trade_symbol` (`entity_id`, `symbol`, `lot_size`) VALUES (NULL, 'NIFTY', '50'), (NULL, 'BANKNIFTY', '25'), (NULL, 'RELIANCE', '250'), (NULL, 'HDFC', '300'), (NULL, 'TCS', '150'), (NULL, 'TATASTEEL', '425')";
        $this->connection->query($insertQuery);
    }

    public function createBacktestTradesTable() {
        $query = "CREATE TABLE IF NOT EXISTS `kite_practice`.`backtest_trades` ( 
            `entity_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Entity_id' ,
            `date` DATE NOT NULL ,
            `symbol` VARCHAR(255) NOT NULL DEFAULT 'NIFTY' ,
            `trade_type` ENUM('long','short') NOT NULL DEFAULT 'long' ,
            `qty` INT(11) NOT NULL DEFAULT '1' ,
            `lot_size` INT(11) NOT NULL DEFAULT '50',
            `ratio` VARCHAR(255) NOT NULL DEFAULT '1:2',
            `setup` TEXT NOT NULL , `points` INT(11) NOT NULL , 
            `notes` TEXT NOT NULL , 
            PRIMARY KEY (`entity_id`)) ENGINE = InnoDB COMMENT = 'Backtest Trades'";
        $this->connection->query($query);
    }

    public function createDailyTradesTable() {
        $query = "CREATE TABLE IF NOT EXISTS `kite_practice`.`daily_trades` ( 
            `entity_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Entity_id' ,
            `date` DATE NOT NULL ,
            `symbol` VARCHAR(255) NOT NULL DEFAULT 'NIFTY' ,
            `trade_type` ENUM('long','short') NOT NULL DEFAULT 'long' ,
            `qty` INT(11) NOT NULL DEFAULT '1' ,
            `lot_size` INT(11) NOT NULL DEFAULT '50',
            `ratio` VARCHAR(255) NOT NULL DEFAULT '1:2',
            `setup` TEXT NOT NULL , `points` INT(11) NOT NULL , 
            `notes` TEXT NOT NULL , 
            PRIMARY KEY (`entity_id`)) ENGINE = InnoDB COMMENT = 'Daily Trades'";
        $this->connection->query($query);
    }
}