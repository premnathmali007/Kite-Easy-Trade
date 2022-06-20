<?php

namespace Core;

class Resource
{
    public function __construct()
    {
        $con = new Connection();
        $this->connection = $con->getConnection();
    }

    public function getSetups() {
        return $this->connection->query("SELECT * FROM `setups`")->fetchAll();
    }

    public function getSymbols() {
        return $this->connection->query("SELECT * FROM `trade_symbol`")->fetchAll();
    }
}