<?php

class Database
{
    private $host = 'localhost';
    private $db = 'e-commerce';
    private $user = 'root';
    private $pass = '';
    private $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db,
                $this->user,
                $this->pass
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exp) {
            echo "Erro de conexão: " . $exp->getMessage();
        }

        return $this->conn;
    }
}
