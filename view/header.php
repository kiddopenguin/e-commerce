<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../controller/CarrinhoController.php';

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
            <?php if (!isset($_SESSION['user_id'])): ?>
                <span style="color: white;">Olá Visitante!</span>
            <?php else: ?>
                <span style="color: white;">Olá <?= $_SESSION['user_nome'] ?>!</span>
            <?php endif; ?>
            <a href="<?= $basePath ?>index.php">Produtos</a>
            <a href="<?= $basePath ?>carrinho.php">Carrinho <?php if ($totalItens > 0) : ?>(<?= $totalItens ?>)<?php endif; ?></a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= $basePath ?>logout.php">Logout</a>
            <?php else: ?>
                <a href="<?= $basePath ?>login.php">Login</a>
                <a href="<?= $basePath ?>cadastro.php">Cadastrar</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                <a href="<?= $basePath ?>admin/index.php">Dashboard</a>
            <?php endif; ?>
        </nav>
    </header>