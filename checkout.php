<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'controller/CarrinhoController.php';
require_once 'controller/PedidoController.php';

$carrinhoCtrl = new CarrinhoController();
$pedidoCtrl = new PedidoController();

$dados = $carrinhoCtrl->obterItensDetalhados();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $pedidoCtrl->finalizar();
    if (is_numeric($resultado)) {
        header('Location: confirmacao.php?pedido=' . $resultado);
        exit;
    }
    $msg = $resultado;
}

$pageTitle = 'Finalizar Compra';
$cssPath = 'styles/style.css';
$basePath = '';

include 'view/header.php';

?>

<?php if ($msg): ?>
    <p> <?= $msg ?> </p>
<?php endif; ?>

<main>
    <h2>Resumo do Pedido</h2>
    <?php if (empty($dados['itens'])): ?>
        <p>O carrinho está vazio!</p>
        <button><a href="#">Voltar as compras!</a></button>
    <?php else: ?>
        <table style="margin:auto;">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados['itens'] as $item): ?>
                    <tr>
                        <td><?= $item['nome'] ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total:</td>
                    <td>R$ <?= $dados['total'] ?></td>
                </tr>
            </tfoot>
        </table>
        <hr>
        <div class="formulario-end">
            <h2>Cadastro de Entrega</h2>
            <form method="post">
                <input type="hidden" name="user_nome" value="<?= $_SESSION['user_nome'] ?>">
                <label for="endereco">Endereço:</label>
                <input type="text" name="endereco" required>
                <label for="cidade">Cidade:</label>
                <input type="text" name="cidade" required>
                <label for="estado">Estado:</label>
                <input type="text" name="estado" required>
                <label for="cep">CEP:</label>
                <input type="text" name="cep" required>
                <label for="telefone">Telefone:</label>
                <input type="tel" name="telefone" required>
                <button type="submit">Confirmar Pedido</button>
            </form>
        </div>
    <?php endif; ?>
</main>

<?php include 'view/footer.php'; ?>