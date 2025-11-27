<?php

require_once __DIR__ . '/../config/Database.php';

class PedidoModel
{
    private $conn;
    private $table_pedidos = "tb_pedidos";
    private $table_item_pedidos = "tb_items_pedidos";

    public $id;
    public $usuario_id;
    public $total;
    public $status;
    public $nome;
    public $endereco;
    public $cidade;
    public $estado;
    public $cep;
    public $telefone;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_pedidos . "(usuario_id, total, status, nome, endereco, cidade, estado, cep, telefone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->usuario_id);
        $stmt->bindParam(2, $this->total);
        $stmt->bindParam(3, $this->status);
        $stmt->bindParam(4, $this->nome);
        $stmt->bindParam(5, $this->endereco);
        $stmt->bindParam(6, $this->cidade);
        $stmt->bindParam(7, $this->estado);
        $stmt->bindParam(8, $this->cep);
        $stmt->bindParam(9, $this->telefone);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Retorna o ID do pedido, caso consiga criar o pedido.
        }
        return false;
    }

    public function addItem($pedido_id, $produto_id, $qtd, $preco)
    {
        $subtotal = $qtd * $preco;
        $query = "INSERT INTO " . $this->table_item_pedidos . "(pedido_id, produto_id, quantidade, preco_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $pedido_id);
        $stmt->bindParam(2, $produto_id);
        $stmt->bindParam(3, $qtd);
        $stmt->bindParam(4, $preco);
        $stmt->bindParam(5, $subtotal);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getByUser($user_id)
    {
        $query = "SELECT * FROM " . $this->table_pedidos . " WHERE usuario_id = ? ORDER BY data_pedido DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table_pedidos . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }

    public function getItems($pedido_id)
    {
        $query = "SELECT i.*, p.nome as produto_nome FROM " . $this->table_item_pedidos . " i JOIN tb_produtos p ON i.produto_id = p.id WHERE i.pedido_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $pedido_id);
        $stmt->execute();
        return $stmt;
    }

    public function updateStatus($id, $status)
    {
        $query = "UPDATE " . $this->table_pedidos . " SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $status);
        $stmt->bindParam(2, $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_pedidos . " ORDER BY data_pedido DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
