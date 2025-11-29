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
    <div class="container">
        <h1 class="text-center mb-4">
            <i class="bi bi-cart"></i> Meu Carrinho de Compras
        </h1>
        
        <?php if ($mensagem): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i><?= $mensagem ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($itens)): ?>
            <div class="text-center">
                <div class="alert alert-warning">
                    <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
                    <h4>Seu carrinho está vazio</h4>
                    <p>Adicione produtos para começar suas compras!</p>
                </div>
                <a href="index.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-shop"></i> Ir às Compras
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="bi bi-box"></i> Produto</th>
                            <th><i class="bi bi-tag"></i> Preço Unit.</th>
                            <th><i class="bi bi-hash"></i> Quantidade</th>
                            <th><i class="bi bi-calculator"></i> Subtotal</th>
                            <th><i class="bi bi-gear"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens as $item): ?>
                            <tr>
                                <td><strong><?= $item['nome'] ?></strong></td>
                                <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                <td>
                                    <form method="post" class="d-flex align-items-center gap-2">
                                        <input type="hidden" name="produto_id" value="<?= $item['id'] ?>">
                                        <input type="number" name="quantidade" value="<?= $item['quantidade'] ?>" class="form-control form-control-sm" style="width: 80px;" min="1">
                                        <button type="submit" name="atualizar" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                    </form>
                                </td>
                                <td><strong class="text-primary">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></strong></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="produto_id" value="<?= $item['id'] ?>">
                                        <button type="submit" name="remover" class="btn btn-sm btn-danger" onclick="return confirm('Remover item do carrinho?')">
                                            <i class="bi bi-trash"></i> Remover
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td colspan="2"><h4 class="text-success mb-0"><i class="bi bi-currency-dollar"></i> R$ <?= number_format($total, 2, ',', '.') ?></h4></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="d-flex flex-wrap gap-3 justify-content-between mt-4">
                <form method="post">
                    <button type="submit" name="limpar" class="btn btn-outline-danger" onclick="return confirm('Limpar todo o carrinho?')">
                        <i class="bi bi-trash"></i> Limpar Carrinho
                    </button>
                </form>
                
                <div class="d-flex gap-2">
                    <button onclick="location.href='index.php'" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Continuar Comprando
                    </button>
                    <button onclick="location.href='checkout.php'" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle"></i> Finalizar Compra
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>