<?php

require_once __DIR__ . '/../config/Database.php';

class UsuarioModel
{
    private $conn;
    private $table_name = "tb_usuarios";

    public $id;
    public $nome;
    public $email;
    public $senha;
    public $user_type;
    public $creation_date;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . "(nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);


        $stmt->bindParam(1, $this->nome);
        $stmt->bindParam(2, $this->email);
        $stmt->bindParam(3, $this->senha);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getByEmail($email)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt;
    }

    public function emailExists($email)
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
