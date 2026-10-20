<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class sessions
{
    function autenticar_session()
    {
        try {
            if (!isset($_SESSION['email']) || !isset($_SESSION['nome']) || !isset($_SESSION['id'])) {

                session_unset();
                session_destroy();
                header('location:../../../main/views/autenticacao/login.php');
                exit();
            }
        } catch (PDOException $e) {
            header('location: ../views/windows/faltal_erro.php');
            exit();
        }
    }

    function tempo_session($tempo = 600)
    {
        try {
            if (isset($_SESSION['ultimo_acesso'])) {

                if (time() - $_SESSION['ultimo_acesso'] > $tempo) {

                    session_unset();
                    session_destroy();
                    header('location:../../../main/views/autenticacao/login.php');
                    exit();
                }
            }
            $_SESSION['ultimo_acesso'] = time();
        } catch (PDOException $e) {
            header('location: ../views/windows/faltal_erro.php');
            exit();
        }
    }

    function deslogar()
    {
        try {
            session_unset();
            session_destroy();
            header('location:../../../main/views/autenticacao/login.php');
            exit();
        } catch (PDOException $e) {
            header('location: ../views/windows/faltal_erro.php');
            exit();
        }
    }
}

if (isset($_GET['sair'])) {

    $session = new sessions();
    $session->deslogar();
}
