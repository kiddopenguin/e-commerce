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
    <h1>Gerenciar Produtos</h1>
    <a href="cadastrar.php"><button>+ Novo Produto</button></a>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #333; color: white;">
                <th style="padding: 10px; border: 1px solid #ddd;">ID</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Nome</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Preço</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Estoque</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($produtos)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Nenhum produto cadastrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= $produto['id'] ?></td>
                        <td><?= $produto['nome'] ?></td>
                        <td>R$ <?= $produto['preco'] ?></td>
                        <td><?= $produto['estoque'] ?></td>
                        <td>
                            <a href="editar.php?id=<?= $produto['id'] ?>"><button>Editar</button></a>
                            <a href="excluir.php?id=<?= $produto['id'] ?>" onclick="return confirm('Deseja realmente excluir?')"><button>Remover</button></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include '../view/footer.php' ?>