<?php
require_once 'auth.php';
require_once '../controller/ProductController.php';
$controller = new ProductController();
$mensagem = '';

$id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// ID inválido
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$produto = $controller->listarPorId($id);

// Produto não existe
if (!$produto) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = $controller->editar($id);
    if (strpos($mensagem, 'sucesso')) {
        header('Location: index.php');
        exit;
    }
    // Se houver erros, recarrega produto original
    $produto = $controller->listarPorId($id);
}

$pageTitle = 'Editar Produto';
$cssPath = '../styles/style.css';

include '../view/header.php';


?>

<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Produto</h4>
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
                                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="preco" class="form-label">
                                    <i class="bi bi-tag"></i> Preço (R$)
                                </label>
                                <input type="number" class="form-control" id="preco" name="preco" value="<?= $produto['preco'] ?>" step="0.01" min="0" required>
                            </div>

                            <div class="mb-3">
                                <label for="estoq" class="form-label">
                                    <i class="bi bi-stack"></i> Quantidade em Estoque
                                </label>
                                <input type="number" class="form-control" id="estoq" name="estoq" value="<?= $produto['estoque'] ?>" min="0" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Salvar Alterações
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