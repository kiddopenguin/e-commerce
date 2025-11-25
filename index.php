<?php

require_once 'controller/ProductController.php';

$controller = new ProductController();
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = $controller->cadastrar();
}

// Buscar todos os produtos

$produtos = $controller->listarTodos();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <header>
        <nav>
            <a href="#">Produtos</a>
            <a href="#">Carrinho</a>
            <a href="#">Login</a>
        </nav>
    </header>
    <main>
        <div class="form-produto">
            <h1>Cadastrar Produto</h1>
            <form method="post">
                <label for="nome">Nome:</label>
                <input type="text" name="nome">
                <label for="preco">Preço:</label>
                <input type="number" name="preco">
                <label for="estoque">Estoque:</label>
                <input type="number" name="estoq">
                <button type="submit">Enviar</button>
                <?php if ($mensagem) : ?>
                    <p style="color: green; font-weight: bold;"><?= $mensagem ?></p>
                <?php endif; ?>
            </form>
        </div>
        <hr>
        <article>
            <h1>Produtos Cadastrados</h1>
            <?php if (empty($produtos)): ?>
                <p>Nenhum produto cadastrado ainda.</p>
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
</body>

</html>