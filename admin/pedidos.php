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
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-clipboard-check"></i> Gerenciar Pedidos</h1>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
        
        <?php if ($msg): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= $msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="bi bi-hash"></i> ID</th>
                                <th><i class="bi bi-person"></i> Cliente</th>
                                <th><i class="bi bi-calendar"></i> Data</th>
                                <th><i class="bi bi-currency-dollar"></i> Total</th>
                                <th><i class="bi bi-gear"></i> Alterar Status</th>
                                <th class="text-center"><i class="bi bi-eye"></i> Detalhes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pedidos)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                        <p class="text-muted">Nenhum pedido encontrado.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pedidos as $pedido): ?>
                                    <tr>
                                        <td><strong>#<?= $pedido['id'] ?></strong></td>
                                        <td><?= htmlspecialchars($pedido['nome']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                                        <td><span class="badge bg-success">R$ <?= number_format($pedido['total'], 2, ',', '.') ?></span></td>
                                        <td>
                                            <form method="post" class="d-flex gap-2 align-items-center">
                                                <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                                <select name="novo_status" class="form-select form-select-sm" style="min-width: 130px;">
                                                    <option value="pendente" <?= $pedido['status'] == 'pendente' ? 'selected' : '' ?>>⏳ Pendente</option>
                                                    <option value="confirmado" <?= $pedido['status'] == 'confirmado' ? 'selected' : '' ?>>✅ Confirmado</option>
                                                    <option value="enviado" <?= $pedido['status'] == 'enviado' ? 'selected' : '' ?>>🚚 Enviado</option>
                                                    <option value="entregue" <?= $pedido['status'] == 'entregue' ? 'selected' : '' ?>>📦 Entregue</option>
                                                    <option value="cancelado" <?= $pedido['status'] == 'cancelado' ? 'selected' : '' ?>>❌ Cancelado</option>
                                                </select>
                                                <button type="submit" name="mudar_status" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-info" onclick="alert('Funcionalidade em desenvolvimento')">
                                                <i class="bi bi-eye"></i> Ver
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Dica:</strong> Altere o status do pedido usando o menu dropdown e clique no botão de confirmação.
            </div>
        </div>
    </div>
</main>
<?php include '../view/footer.php'; ?>