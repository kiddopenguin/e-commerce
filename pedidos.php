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
    <div class="container">
        <h1 class="text-center mb-4">
            <i class="bi bi-list-check"></i> Meus Pedidos
        </h1>
        
        <?php if (empty($pedidos)): ?>
            <div class="text-center">
                <div class="alert alert-info">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <h4>Você ainda não tem nenhum pedido</h4>
                    <p>Comece a comprar agora!</p>
                </div>
                <a href="index.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-shop"></i> Ir às Compras
                </a>
            </div>
        <?php else: ?>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="bi bi-hash"></i> Pedido</th>
                                    <th><i class="bi bi-calendar"></i> Data</th>
                                    <th><i class="bi bi-currency-dollar"></i> Total</th>
                                    <th><i class="bi bi-info-circle"></i> Status</th>
                                    <th class="text-center"><i class="bi bi-eye"></i> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidos as $pedido): ?>
                                    <tr>
                                        <td><strong>#<?= $pedido['id'] ?></strong></td>
                                        <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                                        <td><span class="badge bg-success fs-6">R$ <?= number_format($pedido['total'], 2, ',', '.') ?></span></td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'pendente' => 'warning',
                                                'confirmado' => 'info',
                                                'enviado' => 'primary',
                                                'entregue' => 'success',
                                                'cancelado' => 'danger'
                                            ];
                                            $class = $statusClass[$pedido['status']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $class ?>"><?= ucfirst($pedido['status']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <a href="confirmacao.php?pedido=<?= $pedido['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Ver Detalhes
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Voltar às Compras
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php include 'view/footer.php'; ?>