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
include $basePath . 'view/header.php';
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
                            <td><?= number_format($item['preco'], 2, ',', '.') ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="produto_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantidade" value="<?= $item['quantidade'] ?>">
                                    <button type="submit" name="atualizar">Atualizar</button>
                                </form>
                            </td>
                            <td><?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="produto_id" value="<?= $item['id'] ?>">
                                    <button type="submit" name="remover">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total:</td>
                        <td>R$ <?= number_format($total, 2, ',', '.') ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div>
                <form method="post"><button type="submit" name="limpar">Limpar</button></form>
                <button onclick="location.href='index.php'">Continuar comprando</button>
                <button onclick="location.href='checkout.php'">Finalizar compra</button>
            </div>

        <?php endif; ?>
    </div>
</main>