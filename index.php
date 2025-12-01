<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once ($basePath ?? '') . 'controller/ProductController.php';
require_once ($basePath ?? '') . 'controller/CarrinhoController.php';

$controller = new ProductController();
$carrinhoCrtl = new CarrinhoController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_carrinho'])) {
    $produtoId = intval($_POST['produto_id']);
    $_SESSION['mensagem_carrinho'] = $carrinhoCrtl->adicionar($produtoId, 1);
    header('Location: index.php');
    exit;
}

$mensagem = '';
if (isset($_SESSION['mensagem_carrinho'])) {
    $mensagem = $_SESSION['mensagem_carrinho'];
    unset($_SESSION['mensagem_carrinho']);
}

$produtos = $controller->listarTodos();

$pageTitle = 'Produtos';
$cssPath = 'styles/style.css';
$basePath = '';

include 'view/header.php';


?>


<main>
    <div class="container">
        <h1 class="text-center mb-4">Nossos Produtos</h1>
        <?php if ($mensagem): ?>
            <?php
            $isSuccess = strpos($mensagem, 'sucesso') !== false;
            $alertClass = $isSuccess ? 'alert-success' : 'alert-danger';
            $iconClass = $isSuccess ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
            ?>
            <div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
                <i class="bi <?= $iconClass ?> me-2"></i><?= htmlspecialchars($mensagem) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($produtos)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>Nenhum produto disponível no momento.
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($produtos as $produto): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
                                <p class="card-text text-muted">
                                    <i class="bi bi-box-seam"></i> Estoque: <?= $produto['estoque'] ?> unidades
                                </p>
                                <h4 class="text-primary mb-3">
                                    <i class="bi bi-tag-fill"></i> R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                </h4>

                                <?php if ($produto['estoque'] > 0): ?>
                                    <form method="post" class="d-grid">
                                        <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                                        <button type="submit" name="adicionar_carrinho" class="btn btn-primary">
                                            <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-x-circle"></i> Sem Estoque
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'view/footer.php' ?>