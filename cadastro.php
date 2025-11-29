<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'controller/UsuarioController.php';

$contlr = new UsuarioController();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = $contlr->cadastrar();
    if (strpos($msg, 'sucesso') !== false) {
        header('Location: login.php');
        exit;
    }
}

?>

<?php
$pageTitle = 'Cadastro';
$cssPath = 'styles/style.css';
$basePath = '';
include 'view/header.php';
?>

<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h1 class="text-center mb-4">
                            <i class="bi bi-person-plus"></i> Novo Cadastro
                        </h1>
                        
                        <?php if (!empty($msg)): ?>
                            <div class="alert <?= strpos($msg, 'sucesso') !== false ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
                                <i class="bi bi-<?= strpos($msg, 'sucesso') !== false ? 'check-circle' : 'exclamation-triangle' ?>-fill me-2"></i><?= $msg ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="nome" class="form-label">
                                    <i class="bi bi-person"></i> Nome Completo
                                </label>
                                <input type="text" class="form-control" id="nome" name="name_user" required placeholder="Seu nome">
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> E-mail
                                </label>
                                <input type="email" class="form-control" id="email" name="email_user" required placeholder="seu@email.com">
                            </div>
                            
                            <div class="mb-3">
                                <label for="senha" class="form-label">
                                    <i class="bi bi-lock"></i> Senha
                                </label>
                                <input type="password" class="form-control" id="senha" name="user_pssw" required placeholder="Mínimo 6 caracteres">
                            </div>
                            
                            <div class="mb-3">
                                <label for="conf_senha" class="form-label">
                                    <i class="bi bi-lock-fill"></i> Confirmar Senha
                                </label>
                                <input type="password" class="form-control" id="conf_senha" name="user_conf_pssw" required placeholder="Digite a senha novamente">
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Cadastrar
                                </button>
                            </div>
                            
                            <div class="text-center">
                                <p class="text-muted">Já tem uma conta?</p>
                                <a href="login.php" class="btn btn-outline-primary">
                                    <i class="bi bi-box-arrow-in-right"></i> Fazer Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'view/footer.php'; ?>