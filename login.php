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

<?php if (!empty($msg)): ?>
    <p><?= $msg ?></p>
<?php endif; ?>

<h1>Login</h1>
<form method="post">
    <label for="email">E-mail:</label>
    <input type="email" name="email" required>
    <label for="senha">Senha:</label>
    <input type="password" name="senha" required>
    <button type="submit">Login</button>
</form>