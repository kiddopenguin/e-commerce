<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once ($basePath ?? '') . 'controller/ProductController.php';

$controller = new ProductController();
$produtos = $controller->listarTodos();

$pageTitle = 'Produtos';
$cssPath = 'styles/style.css';
$basePath = '';

include 'view/header.php';

?>


<main>
    <h1>Nossos Produtos</h1>
    <article>
        <?php if (empty($produtos)): ?>
            <p>Nenhum produto disponível no momento.</p>
        <?php else: ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="produto">
                    <h2><?= htmlspecialchars($produto['nome']) ?></h2>
                    <p><strong>Preço: <?= number_format($produto['preco'], 2, '.', ',') ?></strong></p>
                    <p><strong>Estoque: <?= $produto['estoque'] ?></strong></p>
                    <button>Adicionar Carrinho</button>
                    <button>Ver Detalhes</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </article>
</main>

<?php include 'view/footer.php' ?>