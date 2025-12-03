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
require_once 'controller/ProductController.php';

$carrinhoCtrl = new CarrinhoController();
$pedidoCtrl = new PedidoController();
$productCtrl = new ProductController();

$dados = $carrinhoCtrl->obterItensDetalhados();
$msg = '';
$temEstq = true;

foreach ($dados['itens'] as $item) {
    $produto = $productCtrl->listarPorId($item['id']);
    if ($item['quantidade'] > $produto['estoque']) {
        $msg = "Estoque insuficiente para {$item['nome']}. Disponível: {$produto['estoque']}";
        $temEstq = false;
        break;
    }
}

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

<main>
    <div class="container">
        <h1 class="text-center mb-4">
            <i class="bi bi-check-circle"></i> Finalizar Compra
        </h1>

        <?php if ($msg): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($dados['itens'])): ?>
            <div class="text-center">
                <div class="alert alert-warning">
                    <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
                    <h4>O carrinho está vazio!</h4>
                </div>
                <a href="index.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-left"></i> Voltar às Compras
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-7 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-truck"></i> Dados de Entrega</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <input type="hidden" name="user_nome" value="<?= $_SESSION['user_nome'] ?>">

                                <div class="mb-3">
                                    <label for="endereco" class="form-label">
                                        <i class="bi bi-geo-alt"></i> Endereço Completo
                                    </label>
                                    <input type="text" class="form-control" id="endereco" name="endereco" required placeholder="Rua, número, complemento">
                                </div>

                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label for="cidade" class="form-label">
                                            <i class="bi bi-building"></i> Cidade
                                        </label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" required placeholder="Sua cidade">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="estado" class="form-label">
                                            <i class="bi bi-map"></i> Estado
                                        </label>
                                        <select name="estado" class="form-select" required>
                                            <option value="" disabled selected>Selecione o estado</option>
                                            <option value="AC">Acre</option>
                                            <option value="AL">Alagoas</option>
                                            <option value="AP">Amapá</option>
                                            <option value="AM">Amazonas</option>
                                            <option value="BA">Bahia</option>
                                            <option value="CE">Ceará</option>
                                            <option value="DF">Distrito Federal</option>
                                            <option value="ES">Espírito Santo</option>
                                            <option value="GO">Goiás</option>
                                            <option value="MA">Maranhão</option>
                                            <option value="MT">Mato Grosso</option>
                                            <option value="MS">Mato Grosso do Sul</option>
                                            <option value="MG">Minas Gerais</option>
                                            <option value="PA">Pará</option>
                                            <option value="PB">Paraíba</option>
                                            <option value="PR">Paraná</option>
                                            <option value="PE">Pernambuco</option>
                                            <option value="PI">Piauí</option>
                                            <option value="RJ">Rio de Janeiro</option>
                                            <option value="RN">Rio Grande do Norte</option>
                                            <option value="RS">Rio Grande do Sul</option>
                                            <option value="RO">Rondônia</option>
                                            <option value="RR">Roraima</option>
                                            <option value="SC">Santa Catarina</option>
                                            <option value="SP">São Paulo</option>
                                            <option value="SE">Sergipe</option>
                                            <option value="TO">Tocantins</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cep" class="form-label">
                                            <i class="bi bi-mailbox"></i> CEP
                                        </label>
                                        <input type="text" class="form-control" id="cep" name="cep" required placeholder="00000-000">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="telefone" class="form-label">
                                            <i class="bi bi-telephone"></i> Telefone
                                        </label>
                                        <input type="tel" class="form-control" id="telefone" name="telefone" required placeholder="(00) 00000-0000">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="forma_pag" class="form-label">
                                        <i class="bi bi-credit-card"></i> Forma de Pagamento
                                    </label>
                                    <select name="forma_pag" id="forma_pag" class="form-select" required>
                                        <option value="" disabled selected>Selecione a forma de pagamento</option>
                                        <option value="pix">💰 PIX</option>
                                        <option value="credito">💳 Cartão de Crédito</option>
                                        <option value="debito">💳 Cartão de Débito</option>
                                        <option value="paypal">🅿️ PayPal</option>
                                    </select>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg" <?= !$temEstq ? 'disabled' : ''; ?>>
                                        <i class="bi bi-check-circle"></i> Confirmar Pedido
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="bi bi-receipt"></i> Resumo do Pedido</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th class="text-center">Qtd</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($dados['itens'] as $item): ?>
                                            <tr>
                                                <td><?= $item['nome'] ?></td>
                                                <td class="text-center"><?= $item['quantidade'] ?></td>
                                                <td class="text-end">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Total:</h5>
                                <h3 class="text-success mb-0">R$ <?= number_format($dados['total'], 2, ',', '.') ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'view/footer.php'; ?>