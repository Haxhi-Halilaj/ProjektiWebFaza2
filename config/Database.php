<?php

namespace Config;

use mysqli;

class Database
{
    private $host = 'localhost';
    private $db_name = 'db';
    private $db_user = 'root';
    private $db_password = '';
    private $conn;

    public function connect()
    {
        $this->conn = new mysqli($this->host, $this->db_user, $this->db_password, $this->db_name);

        if ($this->conn->connect_error) {
            die('Connection Error: ' . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
        return $this->conn;
    }

    public function getConnection()
    {
        if ($this->conn === null) {
            $this->connect();
        }
        return $this->conn;
    }
}
