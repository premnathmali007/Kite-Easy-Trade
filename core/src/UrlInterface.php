<?php
namespace Core;
use Medoo\Medoo;

class UrlInterface
{
    /**
     * @var Medoo
     */
    private $connection;

    public function __construct()
    {
        $connection = new Connection();
        $this->connection = $connection->getConnection();
        $this->baseUrl = $this->connection->query("SELECT value FROM core_config_data WHERE path = 'base_url'")->fetchColumn();
    }

    public function getBaseUrl() {
        return $this->baseUrl;
    }
    public function getHomeUrl() {
        return $this->baseUrl;
    }

    public function getShowAnalyticsUrl() {
        return $this->baseUrl . "?show=analytics";
    }

    public function getImportTradebookUrl() {
        return $this->baseUrl . "?show=import_tradebook";
    }

    public function getImportFundStatementUrl() {
        return $this->baseUrl . "?show=import_fund_statement";
    }

    public function getShowTradebookUrl() {
        return $this->baseUrl . "?show=tradebook";
    }
}