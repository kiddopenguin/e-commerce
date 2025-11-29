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
    <div class="container">
        <div class="text-center mb-4">
            <div class="alert alert-success shadow" role="alert">
                <i class="bi bi-check-circle-fill fs-1 d-block mb-3"></i>
                <h1 class="alert-heading">Pedido Confirmado!</h1>
                <p class="mb-0">Pedido <strong>#<?= $pedido_id ?></strong> realizado com sucesso!</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Detalhes do Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <p class="text-muted mb-1"><i class="bi bi-info-circle"></i> Status</p>
                                <span class="badge bg-<?= $dados['pedido']['status'] == 'pendente' ? 'warning' : ($dados['pedido']['status'] == 'confirmado' ? 'info' : 'success') ?> fs-6">
                                    <?= ucfirst($dados['pedido']['status']); ?>
                                </span>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1"><i class="bi bi-currency-dollar"></i> Total</p>
                                <h5 class="text-success">R$ <?= number_format($dados['pedido']['total'], 2, ',', '.'); ?></h5>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1"><i class="bi bi-calendar"></i> Data do Pedido</p>
                                <p class="mb-0"><?= date('d/m/Y H:i', strtotime($dados['pedido']['data_pedido'])) ?></p>
                            </div>
                        </div>

                        <hr>

                        <h6 class="text-muted mb-3"><i class="bi bi-truck"></i> Detalhes da Entrega</h6>
                        <address>
                            <strong><i class="bi bi-person"></i> <?= $dados['pedido']['nome'] ?></strong><br>
                            <i class="bi bi-geo-alt"></i> <?= $dados['pedido']['endereco'] ?><br>
                            <i class="bi bi-map"></i> <?= $dados['pedido']['cidade'] ?>/<?= $dados['pedido']['estado'] ?><br>
                            <i class="bi bi-mailbox"></i> CEP: <?= $dados['pedido']['cep'] ?><br>
                            <i class="bi bi-telephone"></i> <?= $dados['pedido']['telefone'] ?>
                        </address>

                        <hr>

                        <h6 class="text-muted mb-3"><i class="bi bi-bag"></i> Itens do Pedido</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produto</th>
                                        <th class="text-center">Quantidade</th>
                                        <th class="text-end">Preço Unit.</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dados['itens'] as $item): ?>
                                        <tr>
                                            <td><?= $item['produto_nome'] ?></td>
                                            <td class="text-center"><span class="badge bg-secondary"><?= $item['quantidade'] ?></span></td>
                                            <td class="text-end">R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                                            <td class="text-end"><strong>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-center mb-4">
                    <button onclick="location.href='index.php'" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Continuar Comprando
                    </button>
                    <button onclick="location.href='pedidos.php'" class="btn btn-primary">
                        <i class="bi bi-list-check"></i> Meus Pedidos
                    </button>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <i class="bi bi-truck fs-1 text-primary mb-3"></i>
                        <h5>Status de Entrega</h5>
                        <p class="text-muted">Acompanhe seu pedido na página "Meus Pedidos"</p>
                        <div class="alert alert-info">
                            <small><i class="bi bi-info-circle"></i> Você receberá atualizações sobre o status do seu pedido</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'view/footer.php'; ?>