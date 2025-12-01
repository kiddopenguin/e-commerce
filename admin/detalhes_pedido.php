<?php

require_once 'auth.php';
require_once __DIR__ . '/../controller/PedidoController.php';

$pedidoId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($pedidoId <= 0) {
    header('Location: pedidos.php');
    exit;
}

$pedidoCtrl = new PedidoController();
$dados = $pedidoCtrl->detalhes($pedidoId);

if (!$dados || empty($dados['pedido'])) {
    header('Location: pedidos.php');
    exit;
}

$pedido = $dados['pedido'];
$itens = $dados['itens'];

$pageTitle = 'Detalhes do Pedido: #' . $pedido['id'];
$cssPath = '../styles/style.css';
$basePath = '../';

include '../view/headerAdmin.php';

?>

<main>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-2">
                    <i class="bi bi-receipt-cutoff"></i> Pedido #<?= $pedido['id'] ?>
                </h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-calendar-event"></i>
                    <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?>
                </p>
            </div>
            <div>
                <a href="pedidos.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-person-circle"></i> Informações do Cliente</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Nome Completo</label>
                                <p class="mb-0 fw-bold">
                                    <i class="bi bi-person"></i> <?= htmlspecialchars($pedido['nome']) ?>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Telefone</label>
                                <p class="mb-0">
                                    <i class="bi bi-telephone"></i> <?= htmlspecialchars($pedido['telefone']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-truck"></i> Endereço de Entrega</h5>
                    </div>
                    <div class="card-body">
                        <address class="mb-0">
                            <i class="bi bi-geo-alt-fill text-danger"></i>
                            <?= htmlspecialchars($pedido['endereco']) ?><br>
                            <i class="bi bi-building"></i>
                            <?= htmlspecialchars($pedido['cidade']) ?> - <?= htmlspecialchars($pedido['estado']) ?><br>
                            <i class="bi bi-mailbox"></i>
                            CEP: <?= htmlspecialchars($pedido['cep']) ?>
                        </address>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-bag-check"></i> Itens do Pedido</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produto</th>
                                        <th class="text-center">Quantidade</th>
                                        <th class="text-end">Preço Unit.</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($itens)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                Nenhum item encontrado
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($itens as $item): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($item['produto_nome']) ?></strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary"><?= $item['quantidade'] ?></span>
                                                </td>
                                                <td class="text-end">
                                                    R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?>
                                                </td>
                                                <td class="text-end">
                                                    <strong class="text-primary">
                                                        R$ <?= number_format($item['subtotal'], 2, ',', '.') ?>
                                                    </strong>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Status</h5>
                    </div>
                    <div class="card-body text-center">
                        <?php
                        $statusConfig = [
                            'pendente' => ['bg' => 'warning', 'icon' => 'hourglass-split', 'text' => 'Pendente'],
                            'confirmado' => ['bg' => 'info', 'icon' => 'check-circle', 'text' => 'Confirmado'],
                            'enviado' => ['bg' => 'primary', 'icon' => 'truck', 'text' => 'Enviado'],
                            'entregue' => ['bg' => 'success', 'icon' => 'box-seam', 'text' => 'Entregue'],
                            'cancelado' => ['bg' => 'danger', 'icon' => 'x-circle', 'text' => 'Cancelado']
                        ];

                        $status = $pedido['status'];
                        $config = $statusConfig[$status] ?? ['bg' => 'secondary', 'icon' => 'question-circle', 'text' => ucfirst($status)];
                        ?>

                        <i class="bi bi-<?= $config['icon'] ?> fs-1 text-<?= $config['bg'] ?> mb-3"></i>
                        <h4>
                            <span class="badge bg-<?= $config['bg'] ?> fs-5">
                                <?= $config['text'] ?>
                            </span>
                        </h4>

                        <form method="post" action="pedidos.php" class="mt-4">
                            <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Alterar Status</label>
                                <select name="novo_status" class="form-select">
                                    <option value="pendente" <?= $status == 'pendente' ? 'selected' : '' ?>>⏳ Pendente</option>
                                    <option value="confirmado" <?= $status == 'confirmado' ? 'selected' : '' ?>>✅ Confirmado</option>
                                    <option value="enviado" <?= $status == 'enviado' ? 'selected' : '' ?>>🚚 Enviado</option>
                                    <option value="entregue" <?= $status == 'entregue' ? 'selected' : '' ?>>📦 Entregue</option>
                                    <option value="cancelado" <?= $status == 'cancelado' ? 'selected' : '' ?>>❌ Cancelado</option>
                                </select>
                            </div>
                            <button type="submit" name="mudar_status" class="btn btn-primary w-100">
                                <i class="bi bi-check-lg"></i> Atualizar Status
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-cash-stack"></i> Resumo</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal:</span>
                            <span>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Frete:</span>
                            <span class="text-success">Grátis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Total:</h5>
                            <h4 class="mb-0 text-success">
                                <i class="bi bi-currency-dollar"></i>
                                R$ <?= number_format($pedido['total'], 2, ',', '.') ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../view/footer.php'; ?>