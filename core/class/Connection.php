<?php
use Medoo\Medoo;

class Connection
{
    public function getConnection() {
        // Connect the database.
        $database = new Medoo([
            'type' => 'mysql',
            'host' => 'localhost',
            'database' => 'name',
            'username' => 'your_username',
            'password' => 'your_password'
        ]);

// Enjoy
        $database->insert('account', [
            'user_name' => 'foo',
            'email' => 'foo@bar.com'
        ]);

        $data = $database->select('account', [
            'user_name',
            'email'
        ], [
            'user_id' => 50
        ]);

        echo json_encode($data);
    }
}