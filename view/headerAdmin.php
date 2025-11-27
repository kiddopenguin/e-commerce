<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../controller/CarrinhoController.php';

$carrinhoCrtl = new CarrinhoController();
$totalItens = $carrinhoCrtl->contarItems();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'E-commerce' ?></title>
    <link rel="stylesheet" href="<?= $cssPath ?? 'styles/style.css' ?>">
</head>

<body>
    <header>
        <nav>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'admin'): ?>
                <span style="color: white;">Bem vindo Administrador</span>
            <?php endif;?>
            <a href="../index.php">Produtos</a>
            <a href="../carrinho.php">Carrinho <?php if ($totalItens > 0) : ?>(<?= $totalItens ?>)<?php endif; ?></a>
            <a href="../logout.php">Logout</a>
        </nav>
    </header>