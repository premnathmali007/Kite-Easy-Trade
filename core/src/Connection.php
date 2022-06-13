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
}