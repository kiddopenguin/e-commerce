<?php


require_once ($basePath ?? '') . 'controller/ProductController.php';
require_once ($basePath ?? '') . 'controller/CarrinhoController.php';

$controller = new ProductController();
$carrinhoCrtl = new CarrinhoController();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_carrinho'])) {
    $produtoId = intval($_POST['produto_id']);
    $mensagem = $carrinhoCrtl->adicionar($produtoId, 1);
}

$produtos = $controller->listarTodos();

$pageTitle = 'Produtos';
$cssPath = 'styles/style.css';
$basePath = '';
$mensagem = '';

include 'view/header.php';

?>


<main>
    <h1>Nossos Produtos</h1>
    <?php if ($mensagem): ?>
        <p><?= $mensagem ?></p>
    <?php endif; ?>

    <article>
        <?php if (empty($produtos)): ?>
            <p>Nenhum produto disponível no momento.</p>
        <?php else: ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="produto">
                    <h2><?= htmlspecialchars($produto['nome']) ?></h2>
                    <p><strong>Preço:</strong> R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                    <p><strong>Estoque:</strong> <?= $produto['estoque'] ?> unidades</p>

                    <?php if ($produto['estoque'] > 0): ?>
                        <form style="display: inline;" method="post">
                            <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                            <button type="submit" name="adicionar_carrinho">Adicionar ao carrinho</button>
                        </form>
                    <?php else: ?>
                        <button disabled="disabled">Sem Estoque</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </article>
</main>

<?php include 'view/footer.php' ?>