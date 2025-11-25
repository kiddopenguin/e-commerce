<?php

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

include '../view/header.php';
?>

<main>
    <div class="form-produto">
        <h1>Cadastrar Novo Produto</h1>
        <?php if ($mensagem): ?>
            <p><?= $mensagem ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome">

            <label for="preco">Preço:</label>
            <input type="number" name="preco">

            <label for="estoq">Estoque:</label>
            <input type="number" name="estoq">

            <button type="submit">Cadastrar</button>
            <button style="margin-bottom: 10px;">Cancelar</button>
        </form>
    </div>
</main>


<?php include '../view/footer.php' ?>