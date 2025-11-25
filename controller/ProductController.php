<?php

require_once __DIR__ . '/../model/Produto.php';

class ProductController
{

    private $produtoModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
    }

    public function cadastrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validação e Sanitização de dados
            $nome = htmlspecialchars(trim($_POST['nome']));
            $preco = floatval($_POST['preco']);
            $estoque = intval($_POST['estoq']);

            // Validação básica
            if (empty($nome) || $preco <= 0 || $estoque < 0) {
                return "Dados inválidos!";
            }

            // Atribuir dados ao Model
            $this->produtoModel->nome = $nome;
            $this->produtoModel->preco = $preco;
            $this->produtoModel->estoque = $estoque;

            // Realizar tentativa de cadastro
            if ($this->produtoModel->create()) {
                return "Produto cadastrado com sucesso!";
            } else {
                return "Erro ao cadastrar produto";
            }
        }
    }

    public function listarTodos()
    {
        $stmt = $this->produtoModel->getAll();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorId($id)
    {
        $stmt = $this->produtoModel->getById($id);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = htmlspecialchars(trim($_POST['nome']));
            $preco = floatval($_POST['preco']);
            $estoque = intval($_POST['estoq']);

            if (empty($nome) || $preco <= 0 || $estoque < 0) {
                return "Dados inválidos!";
            }

            $this->produtoModel->id = $id;
            $this->produtoModel->nome = $nome;
            $this->produtoModel->preco = $preco;
            $this->produtoModel->estoque = $estoque;

            if ($this->produtoModel->update()) {
                return "Produto atualizado com sucesso!";
            }
            return "Erro ao atualizar o produto!";
        }
    }

    public function excluir($id)
    {
        $this->produtoModel->id = $id;
        return $this->produtoModel->delete();
    }
}
