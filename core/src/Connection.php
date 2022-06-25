<?php
namespace Core;

use Medoo\Medoo;

class Connection
{
    public function getConnection()
    {
        // Connect the database.
        return new Medoo([
            'type' => 'mysql',
            'host' => 'localhost',
            'database' => 'kite_practice',
            'username' => 'prem',
            'password' => 'prem'
        ]);
    }

    public function getResult($rows) {
        $result=[];
        foreach ($rows as $row){
            $currentRow = [];
            foreach ($row as $key => $value) {
                if (is_string($key)){
                    $currentRow[$key] = $value;
                }
            }
            $result[] = $currentRow;
        }
        return $result;
    }
}