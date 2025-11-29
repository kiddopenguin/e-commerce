<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pedido_id = isset($_GET['pedido']) ? intval($_GET['pedido']) : 0;
if ($pedido_id <= 0) {
    header('Location: index.php');
    exit;
}

require_once 'controller/PedidoController.php';

$pedidoCtrl = new PedidoController();

$dados = $pedidoCtrl->detalhes($pedido_id);

if ($dados['pedido']['usuario_id'] != $_SESSION['user_id']) {
    header('Location: index.php');
    exit;
}

$pageTitle = 'Pedido Confirmado';
$cssPath = 'styles/style.css';
$basePath = '';
include 'view/header.php';

?>

<main>
    <h1>Pedido Confirmado!</h1>
    <p>Pedido #<?= $pedido_id ?> realizado com sucesso!</p>
    <br>
    <h2>Detalhes do Pedido</h2>
    <p>Status: <?= ucfirst($dados['pedido']['status']); ?></p>
    <p>Total: R$ <?= number_format($dados['pedido']['total'], 2, ',', '.'); ?></p>
    <p>Data do pedido: <?= date('d/m/Y H:i', strtotime($dados['pedido']['data_pedido'])) ?></p>
    <br>
    <h3>Detalhes da Entrega:</h3>
    <p><?= $dados['pedido']['nome'] ?></p>
    <p><?= $dados['pedido']['endereco'] ?></p>
    <p><?= $dados['pedido']['cidade'] ?>/<?= $dados['pedido']['estado'] ?> </p>
    <p><?= $dados['pedido']['cep'] ?></p>
    <p><?= $dados['pedido']['telefone'] ?></p>
    <br>
    <h3>Items:</h3>
    <table style="margin: auto;">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dados['itens'] as $item): ?>
                <tr>
                    <td><?= $item['produto_nome'] ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <button onclick="location.href='index.php'">Continuar Comprando</button>
    <button onclick="location.href='pedidos.php'">Meus pedidos</button>
</main>
<?php include 'view/footer.php'; ?>