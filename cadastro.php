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

<?php if (!empty($msg)): ?>
    <p><?= $msg ?></p>
<?php endif; ?>

<h1>Novo Cadastro</h1>
<form method="post">
    <label for="nome">Nome:</label>
    <input type="text" name="name_user" required>
    <label for="email">Email:</label>
    <input type="email" name="email_user" required>
    <label for="senha">Senha:</label>
    <input type="password" name="user_pssw" required>
    <label for="conf_senha">Confirmar Senha:</label>
    <input type="password" name="user_conf_pssw" required>
    <button type="submit">Cadastrar</button>
</form>