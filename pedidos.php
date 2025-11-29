<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'controller/PedidoController.php';

$pedidoCtrl = new PedidoController();

$pedidos = $pedidoCtrl->listarPorUsuario($_SESSION['user_id']);

$pageTitle = 'Meus Pedidos';
$cssPath = 'styles/style.css';
$basePath = '';
include 'view/header.php';

?>

<main>
    <h1>Meus Pedidos</h1>
    <?php if (empty($pedidos)): ?>
        <p>Você ainda não tem nenhum pedido.</p>
    <?php else: ?>
        <table style="margin: auto;">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Data</th>
                    <th>Total</th>
                    <th>Status</th>
                    <?php if ($_SESSION['user_type'] === 'admin'): ?>
                        <th>Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td>#<?= $pedido['id'] ?></td>
                        <td><?= date('d/m/Y', strtotime($pedido['data_pedido'])) ?></td>
                        <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                        <td><?= ucfirst($pedido['status']) ?></td>
                        <?php if ($_SESSION['user_type'] === 'admin'): ?>
                            <td><button>Ver detalhes</button></td>
                        <?php endif; ?>


                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
<?php include 'view/footer.php'; ?>