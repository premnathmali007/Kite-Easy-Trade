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
        $this->baseUrl = $this->connection->select("core_config_data",[],["value"],["path","base_url"]);
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

    public function getShowTradebookUrl() {
        return $this->baseUrl . "?show=tradebook";
    }
}