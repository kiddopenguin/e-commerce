<?php

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
    <div class="form-produto">
        <h1>Editar Produto</h1>

        <?php if ($mensagem): ?>
            <p><?= $mensagem ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>">

            <label for="preco">Preço:</label>
            <input type="number" name="preco" value="<?= $produto['preco'] ?>">

            <label for="estoq">Estoque:</label>
            <input type="number" name="estoq" value="<?= $produto['estoque'] ?>">

            <button type="submit">Editar</button>
            <a href="index.php"><button style="margin-bottom: 10px;">Cancelar</button></a>
        </form>
    </div>
</main>