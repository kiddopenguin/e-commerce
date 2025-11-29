<?php
require_once 'auth.php';
require_once '../controller/ProductController.php';
$controller = new ProductController();
$produtos = $controller->listarTodos();

$pageTitle = 'Painel Administrativo';
$cssPath = '../styles/style.css';
$basePath = '../';

include $basePath . 'view/headerAdmin.php';
?>

<main>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-box-seam"></i> Gerenciar Produtos</h1>
            <a href="cadastrar.php" class="btn btn-success btn-lg">
                <i class="bi bi-plus-circle"></i> Novo Produto
            </a>
        </div>
        
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="bi bi-hash"></i> ID</th>
                                <th><i class="bi bi-box"></i> Nome</th>
                                <th><i class="bi bi-tag"></i> Preço</th>
                                <th><i class="bi bi-stack"></i> Estoque</th>
                                <th class="text-center"><i class="bi bi-gear"></i> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($produtos)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                        <p class="text-muted">Nenhum produto cadastrado.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($produtos as $produto): ?>
                                    <tr>
                                        <td><strong><?= $produto['id'] ?></strong></td>
                                        <td><?= $produto['nome'] ?></td>
                                        <td><span class="badge bg-primary">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span></td>
                                        <td>
                                            <?php if ($produto['estoque'] > 10): ?>
                                                <span class="badge bg-success"><?= $produto['estoque'] ?> un.</span>
                                            <?php elseif ($produto['estoque'] > 0): ?>
                                                <span class="badge bg-warning"><?= $produto['estoque'] ?> un.</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Esgotado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="editar.php?id=<?= $produto['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i> Editar
                                                </a>
                                                <a href="excluir.php?id=<?= $produto['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja realmente excluir este produto?')">
                                                    <i class="bi bi-trash"></i> Remover
                                                </a>
                                            </div>
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
</main>

<?php include '../view/footer.php' ?>