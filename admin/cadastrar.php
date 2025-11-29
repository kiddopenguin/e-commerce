<?php

require_once 'auth.php';
require_once '../controller/ProductController.php';


$controller = new ProductController();
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = $controller->cadastrar();
    if (strpos($mensagem, 'sucesso') !== false) {
        header('Location: index.php');
        exit;
    }
}

$pageTitle = 'Cadastrar Produto';
$cssPath = '../styles/style.css';
$basePath = '../';

include $basePath . 'view/header.php';
?>

<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Cadastrar Novo Produto</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($mensagem): ?>
                            <div class="alert alert-<?= strpos($mensagem, 'sucesso') !== false ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                                <i class="bi bi-<?= strpos($mensagem, 'sucesso') !== false ? 'check-circle' : 'exclamation-triangle' ?>-fill me-2"></i><?= $mensagem ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="nome" class="form-label">
                                    <i class="bi bi-box"></i> Nome do Produto
                                </label>
                                <input type="text" class="form-control" id="nome" name="nome" required placeholder="Digite o nome do produto">
                            </div>

                            <div class="mb-3">
                                <label for="preco" class="form-label">
                                    <i class="bi bi-tag"></i> Preço (R$)
                                </label>
                                <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="0" required placeholder="0.00">
                            </div>

                            <div class="mb-3">
                                <label for="estoq" class="form-label">
                                    <i class="bi bi-stack"></i> Quantidade em Estoque
                                </label>
                                <input type="number" class="form-control" id="estoq" name="estoq" min="0" required placeholder="0">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle"></i> Cadastrar Produto
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<?php include '../view/footer.php' ?>