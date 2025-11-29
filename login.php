<?php
session_start();

require_once 'controller/UsuarioController.php';

$contlr = new UsuarioController();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = $contlr->login();
    if (strpos($msg, 'sucesso') !== false) {
        header('Location: index.php');
        exit;
    }
}


?>

<?php
$pageTitle = 'Login';
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
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </h1>
                        
                        <?php if (!empty($msg)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $msg ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> E-mail
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="seu@email.com">
                            </div>
                            
                            <div class="mb-3">
                                <label for="senha" class="form-label">
                                    <i class="bi bi-lock"></i> Senha
                                </label>
                                <input type="password" class="form-control" id="senha" name="senha" required placeholder="Digite sua senha">
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Entrar
                                </button>
                            </div>
                            
                            <div class="text-center">
                                <p class="text-muted">Não tem uma conta?</p>
                                <a href="cadastro.php" class="btn btn-outline-primary">
                                    <i class="bi bi-person-plus"></i> Criar Conta
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