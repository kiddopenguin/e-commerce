<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class CarrinhoController
{
    public function __construct()
    {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    public function adicionar($produtoId, $quantidade)
    {
        $produtoId = intval($produtoId);
        $quantidade = intval($quantidade);

        if ($produtoId <= 0 || $quantidade <= 0) {
            return "Dados inválidos!";
        }

        require_once __DIR__ . '/ProductController.php';
        $produtoCtrl = new ProductController();
        $produto = $produtoCtrl->listarPorId($produtoId);

        if (!$produto) {
            return "Produto não encontrado!";
        }

        $quantidadeAtual = isset($_SESSION['carrinho'][$produtoId]) ? $_SESSION['carrinho'][$produtoId] : 0;
        $quantidadeTotal = $quantidadeAtual + $quantidade;

        if ($quantidadeTotal > $produto['estoque']) {
            $disponiveis = $produto['estoque'] - $quantidadeAtual;
            if ($disponiveis <= 0) {
                return "Produto sem estoque disponível!";
            }
            return "Estoque insuficiente! Você já tem {$quantidadeAtual} no carrinho. Disponível para adicionar: {$disponiveis} unidade(s).";
        }

        if (isset($_SESSION['carrinho'][$produtoId])) {
            $_SESSION['carrinho'][$produtoId] += $quantidade;
        } else {
            $_SESSION['carrinho'][$produtoId] = $quantidade;
        }

        return "Produto adicionado ao carrinho com sucesso!";
    }

    public function remover($produtoId)
    {
        $produtoId = intval($produtoId);

        if (isset($_SESSION['carrinho'][$produtoId])) {
            unset($_SESSION['carrinho'][$produtoId]);
            return "Produto removido do carrinho!";
        }

        return "Produto não encontrado no carrinho!";
    }

    public function atualizarQuantidade($produtoId, $quantidade)
    {
        $produtoId = intval($produtoId);
        $quantidade = intval($quantidade);

        if ($quantidade <= 0) {
            return $this->remover($produtoId);
        }

        if (isset($_SESSION['carrinho'][$produtoId])) {
            require_once __DIR__ . '/ProductController.php';
            $produtoCtrl = new ProductController();
            $produto = $produtoCtrl->listarPorId($produtoId);

            if (!$produto) {
                return "Produto não encontrado!";
            }

            if ($quantidade > $produto['estoque']) {
                return "Estoque insuficiente! Disponível: {$produto['estoque']} unidade(s).";
            }

            $_SESSION['carrinho'][$produtoId] = $quantidade;
            return "Quantidade atualizada!";
        }

        return "Produto não encontrado no carrinho!";
    }

    public function obterItens()
    {
        return $_SESSION['carrinho'] ?? [];
    }

    public function obterItensDetalhados()
    {
        require_once 'ProductController.php';

        $controller = new ProductController();
        $itens = [];
        $total = 0;

        foreach ($this->obterItens() as $produtoId => $quantidade) {
            $produto = $controller->listarPorId($produtoId);

            if ($produto) {
                $subtotal = $produto['preco'] * $quantidade;
                $itens[] = [
                    'id' => $produto['id'],
                    'nome' => $produto['nome'],
                    'preco' => $produto['preco'],
                    'quantidade' => $quantidade,
                    'subtotal' => $subtotal
                ];
                $total += $subtotal;
            }
        }

        return [
            'itens' => $itens,
            'total' => $total
        ];
    }

    public function contarItems()
    {
        $total = 0;
        foreach ($this->obterItens() as $quantidade) {
            $total += $quantidade;
        }
        return $total;
    }

    public function limpar()
    {
        $_SESSION['carrinho'] = [];
        return "Carrinho esvaziado!";
    }
}
