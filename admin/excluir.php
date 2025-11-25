<?php

require_once '../controller/ProductController.php';


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $controller = new ProductController();
    $controller->excluir($id);
}

header('Location: index.php');
exit;
