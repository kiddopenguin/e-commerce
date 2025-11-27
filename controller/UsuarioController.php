<?php

require_once __DIR__ . '/../model/Usuario.php';

class UsuarioController
{
    private $model;

    public function __construct()
    {
        $this->model = new UsuarioModel();
    }

    public function cadastrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recebe dados POST
            $nomeUser = htmlspecialchars(trim($_POST['name_user']));
            $emailUser = htmlspecialchars(trim($_POST['email_user']));
            $senhaUser = $_POST['user_pssw'];
            $confSenhaUser = $_POST['user_conf_pssw'];

            // Valida campos vazios
            if (empty($nomeUser) || empty($emailUser) || empty($senhaUser)) {
                return "Preencha todos os campos!";
            }

            // Validações E=mail

            if (!filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {
                return "E-mail inválido!";
            }

            if ($this->model->emailExists($emailUser)) {
                return "E-mail já está em uso!";
            }

            // Validação de senha

            if (strlen($senhaUser) < 8) {
                return "Sua senha deve ter pelo menos 8 digitos!";
            }

            if ($senhaUser !== $confSenhaUser) {
                return "A senha e confirmação da senha estão diferentes!";
            }

            // Hash de senha

            $senhaUser = password_hash($senhaUser, PASSWORD_DEFAULT);

            // Carregando os dados para o model

            $this->model->nome = $nomeUser;
            $this->model->email = $emailUser;
            $this->model->senha = $senhaUser;

            if ($this->model->create()) {
                return "Usuário criado com sucesso!";
            } else {
                return "Erro ao criar usuário!";
            }
        }
    }

    public function login()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $senha = $_POST['senha'];

            // Validar dados vazios
            if (empty($email) || empty($senha)) {
                return "Preencha todos os campos!";
            }

            // Busca por e-mail no banco e pega usuário
            $stmt = $this->model->getByEmail($email);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Confirma existência de usuário
            if (!$user) {
                return "E-mail ou senha inválidos!";
            }

            // Verifica senha e cria sessão
            if (password_verify($senha, $user['senha'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nome'] = $user['nome'];
                $_SESSION['user_type'] = $user['user_type'];

                return "Login realizado com sucesso!";
            } else {
                return "Email ou senha inválidos!";
            }
        }
    }

    public function logout()
    {
        // Verifica se a sessão existe
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Limpa as variáveis de sessão
        $_SESSION = [];

        // Destrói a sessão (limpa/logout)
        session_destroy();

        // Encaminha para index
        header('Location: index.php');
        exit;
    }

    public function isLogado()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    // public function isAdmin()
    // {
    //     return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
    // }
}
