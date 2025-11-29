<?php

require_once 'auth.php';
require_once __DIR__ . '/../controller/PedidoController.php';

$pedidoCtrl = new PedidoController();
$msg = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mudar_status'])) {
    $pedidoId = intval($_POST['pedido_id']);
    $novoStatus = $_POST['novo_status'];

    $resultado = $pedidoCtrl->alterarStatus($pedidoId, $novoStatus);
    $msg = $resultado;
}

$pedidos = $pedidoCtrl->listarTodos();

$cssPath = '../styles/style.css';
$basePath = '../';
$pageTitle = 'Gerenciar Pedidos';
include '../view/headerAdmin.php';

?>


<main>
    <h1>Todos os pedidos</h1>
    <?php if ($msg): ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
    <table style="margin: auto;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Data</th>
                <th>Total</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td><?= $pedido['id'] ?></td>
                    <td><?= $pedido['nome'] ?></td>
                    <td><?= $pedido['data_pedido'] ?></td>
                    <td><?= $pedido['total'] ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                            <select name="novo_status">
                                <option value="pendente" <?= $pedido['status'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                <option value="confirmado" <?= $pedido['status'] == 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                                <option value="enviado" <?= $pedido['status'] == 'enviado' ? 'selected' : '' ?>>Enviado</option>
                                <option value="entregue" <?= $pedido['status'] == 'entregue' ? 'selected' : '' ?>>Entregue</option>
                                <option value="cancelado" <?= $pedido['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                            <button type="submit" name="mudar_status">Alterar Status</button>
                        </form>
                    </td>
                    <td><button>Ver Detalhes</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include '../view/footer.php'; ?>