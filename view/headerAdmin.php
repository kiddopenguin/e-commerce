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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $cssPath ?? 'styles/style.css' ?>?v=<?= time() ?>">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">
                    <i class="bi bi-shop"></i> E-Commerce Admin
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php">
                                <i class="bi bi-grid"></i> Produtos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../carrinho.php">
                                <i class="bi bi-cart"></i> Carrinho 
                                <?php if ($totalItens > 0) : ?>
                                    <span class="badge bg-danger"><?= $totalItens ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-speedometer2"></i> Admin
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.php"><i class="bi bi-box-seam"></i> Produtos</a></li>
                                <li><a class="dropdown-item" href="pedidos.php"><i class="bi bi-clipboard-check"></i> Pedidos</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <span class="navbar-text me-3">
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'admin'): ?>
                                    <i class="bi bi-shield-check"></i> Administrador
                                <?php endif;?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
        </nav>
    </header>