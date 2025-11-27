<?php

require_once __DIR__ . '/controller/CarrinhoController.php';

$carrinhoCtrl = new CarrinhoController();
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remover'])) {
        $produtoId = intval($_POST['produto_id']);
        $mensagem = $carrinhoCtrl->remover($produtoId);
    } elseif (isset($_POST['atualizar'])) {
        $produtoId = intval($_POST['produto_id']);
        $quantidade = intval($_POST['quantidade']);
        $mensagem = $carrinhoCtrl->atualizarQuantidade($produtoId, $quantidade);
    } elseif (isset($_POST['limpar'])) {
        $mensagem = $carrinhoCtrl->limpar();
    }
}

$dadosCarrinho = $carrinhoCtrl->obterItensDetalhados();
$itens = $dadosCarrinho['itens'];
$total = $dadosCarrinho['total'];

$pageTitle = 'Meu Carrinho';
$cssPath = 'styles/style.css';
$basePath = '';

include 'view/header.php';
?>

<main>
    <div>
        <h1>Meu Carrinho de Compras</h1>
        <?php if ($mensagem): ?>
            <p><?= $mensagem ?></p>
        <?php endif; ?>

        <?php if (empty($itens)): ?>
            <p>Seu carrinho está vazio.</p>
            <a href="index.php"><button>Ir as compras</button></a>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço Unit.</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td><?= $item['nome'] ?></td>
                            <td></td>
                            <td>
                                <form method="post">

                                </form>
                            </td>
                            <td></td>
                            <td>
                                <form method="post">

                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <form method="post"><button type="submit" name="limpar">Limpar</button></form>
                </tbody>
            </table>

        <?php endif; ?>
    </div>
</main>