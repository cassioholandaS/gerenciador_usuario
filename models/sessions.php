<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class sessions
{
    private function loginUrl(): string
    {
        $scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        if (in_array(basename($scriptDirectory), ['views', 'controllers', 'models'], true)) {
            $scriptDirectory = dirname($scriptDirectory);
        }

        $applicationDirectory = dirname(dirname($scriptDirectory));
        return rtrim($applicationDirectory, '/') . '/main/views/autenticacao/login.php';
    }

    function autenticar_session()
    {
        if (!isset($_SESSION['email'], $_SESSION['nome'], $_SESSION['id'])) {
            header('Location: ' . $this->loginUrl());
            exit();
        }
    }

    function tempo_session($tempo = 600)
    {
        if (isset($_SESSION['ultimo_acesso']) && time() - $_SESSION['ultimo_acesso'] > $tempo) {
            $this->deslogar();
        }

        $_SESSION['ultimo_acesso'] = time();
        return $_SESSION;
    }

    function deslogar()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        header('Location: ' . $this->loginUrl());
        exit();
    }
}

if (isset($_GET['sair'])) {
    $session = new sessions();
    $session->deslogar();
}