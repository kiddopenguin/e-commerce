<?php

require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/Produto.php';
require_once __DIR__ . '/CarrinhoController.php';

class PedidoController
{
    private $model;
    private $carrinho;

    public function __construct()
    {
        $this->model = new PedidoModel();
        $this->carrinho = new CarrinhoController();
    }

    public function finalizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                return "Você precisa estar logado para finalizar!";
            }
            $dados = $this->carrinho->obterItensDetalhados();

            if (empty($_SESSION['carrinho'])) {
                return "Seu carrinho está vazio!";
            }


            $nome = htmlspecialchars(trim($_POST['user_nome']));
            $endereco = htmlspecialchars(trim($_POST['endereco']));
            $cidade = htmlspecialchars(trim($_POST['cidade']));
            $estado = strtoupper(trim($_POST['estado']));
            $cep = preg_replace('/[^0-9]/', '', $_POST['cep']);
            $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);
            $forma_pag = htmlspecialchars(trim($_POST['forma_pag']));

            if (empty($nome) || empty($endereco) || empty($cidade) || empty($forma_pag)) {
                return "Preencha todos os campos!";
            }

            $formasPagamentoPermitidas = ['pix', 'credito', 'debito', 'paypal'];
            if (!in_array($forma_pag, $formasPagamentoPermitidas)) {
                return "Forma de pagamento inválida!";
            }

            if (strlen($estado) != 2) {
                return "Estado inválido!";
            }

            if (strlen($cep) != 8) {
                return "CEP inválido!";
            }

            $this->model->usuario_id = $_SESSION['user_id'];
            $this->model->total = $dados['total'];
            $this->model->status = 'pendente';
            $this->model->nome = $nome;
            $this->model->endereco = $endereco;
            $this->model->cidade = $cidade;
            $this->model->estado = $estado;
            $this->model->cep = $cep;
            $this->model->telefone = $telefone;
            $this->model->forma_pag = $forma_pag;

            $produtoModel = new ProdutoModel();

            foreach ($dados['itens'] as $item) {
                $produtoAtual = $produtoModel->getById($item['id']);
                $prod = $produtoAtual->fetch(PDO::FETCH_ASSOC);

                if (!$prod) {
                    return "Erro: Produto '{$item['nome']}' não encontrado!";
                }

                if ($item['quantidade'] > $prod['estoque']) {
                    return "Estoque insuficiente para '{$item['nome']}'. Disponível: {$prod['estoque']} unidade(s). Por favor, ajuste seu carrinho.";
                }
            }

            $pedido_id = $this->model->create();

            if (!$pedido_id) {
                return "Erro ao criar pedido!";
            }


            foreach ($dados['itens'] as $item) {
                $this->model->addItem(
                    $pedido_id,
                    $item['id'],
                    $item['quantidade'],
                    $item['preco']
                );

                $produto = $produtoModel->getById($item['id']);
                $prod = $produto->fetch(PDO::FETCH_ASSOC);

                $novoEstoque = $prod['estoque'] - $item['quantidade'];

                $produtoModel->id = $item['id'];
                $produtoModel->estoque = $novoEstoque;
                $produtoModel->updateEstoque();
            }

            $this->carrinho->limpar();

            return $pedido_id;
        }
    }

    public function listarPorUsuario($user_id)
    {
        $stmt = $this->model->getByUser($user_id);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function detalhes($pedido_id)
    {
        $pedido = $this->model->getById($pedido_id);
        $itens = $this->model->getItems($pedido_id);

        return [
            'pedido' => $pedido->fetch(PDO::FETCH_ASSOC),
            'itens' => $itens->fetchAll(PDO::FETCH_ASSOC)
        ];
    }

    public function listarTodos()
    {
        $stmt = $this->model->getAll();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function alterarStatus($id, $status)
    {
        $statusPermitidos = ['pendente', 'confirmado', 'enviado', 'entregue', 'cancelado'];

        if (!in_array($status, $statusPermitidos)) {
            return "Status inválido!";
        }

        if ($this->model->updateStatus($id, $status)) {
            return "Status atualizado!";
        }
        return "Erro ao atualizar status!";
    }
}
