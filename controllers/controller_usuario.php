<?php
require_once(__DIR__ . "/../models/model.admin.php");

if (
    isset($_POST["nome"], $_POST["sistema_id"])
    && is_string($_POST["nome"])
    && trim($_POST["nome"]) !== ''
    && filter_var($_POST["sistema_id"], FILTER_VALIDATE_INT)
    && (!isset($_POST["cpf"]) || empty(trim((string)$_POST["cpf"])))
) {
    $nome = trim($_POST["nome"]);
    $id_sistema = (int) $_POST["sistema_id"];

    $admin_model = new admin();
    $result = $admin_model->criar_tipo_usuario($nome, $id_sistema);

    switch ($result) {
        case 1:
            header('Location: ../views/usuario.php?tipo_criado');
            exit();
        case 2:
            header('Location: ../views/usuario.php?tipo_erro');
            exit();
        case 3:
            header('Location: ../views/usuario.php?tipo_ja_existe');
            exit();
        default:
            header('Location: ../views/usuario.php?tipo_falha');
            exit();
    }
} else if (
    isset($_POST["nome"]) && is_string($_POST["nome"]) && trim($_POST["nome"]) !== ''
    && (!isset($_POST["cpf"]) || empty(trim((string)$_POST["cpf"])))
) {
    header('Location: ../views/usuario.php?tipo_erro');
    exit();
} else if (
    isset($_POST["user_id"]) && !empty($_POST["user_id"])
    && (!isset($_POST["cpf"]) || empty(trim((string)$_POST["cpf"])))
) {
    $id_usuario = $_POST["user_id"];

    $admin_model = new admin();
    $result = $admin_model->excluir_usuario($id_usuario);

    switch ($result) {
        case 1:
            header('Location: ../views/usuario.php?excluido');
            exit();
        case 2:
            header('Location: ../views/usuario.php?erro');
            exit();
        case 3:
            header('Location: ../views/usuario.php?ja_existe');
            exit();
        default:
            header('Location: ../views/usuario.php?falha');
            exit();
    }
} else if (
    empty($_POST["user_id"]) &&
    isset($_POST["nome"]) && !empty(trim($_POST["nome"])) &&
    isset($_POST["email"]) && !empty(trim($_POST["email"])) &&
    isset($_POST["cpf"]) && !empty(trim((string)$_POST["cpf"])) &&
    isset($_POST["setor"]) && !empty($_POST["setor"])
) {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $cpf = trim((string)$_POST["cpf"]);
    $id_setor = $_POST["setor"];
    $admin_model = new admin();
    $result = $admin_model->criar_usuario($nome, $email, $cpf, $id_setor);

    switch ($result) {
        case 1:
            header('Location: ../views/usuario.php?criado');
            exit();
        case 2:
            header('Location: ../views/usuario.php?erro');
            exit();
        case 3:
            header('Location: ../views/usuario.php?ja_existe');
            exit();
        default:
            header('Location: ../views/usuario.php?falha');
            exit();
    }
} else if (
    isset($_POST["user_id"]) && !empty($_POST["user_id"]) &&
    isset($_POST["nome"]) && !empty(trim($_POST["nome"])) &&
    isset($_POST["email"]) && !empty(trim($_POST["email"])) &&
    isset($_POST["cpf"]) && !empty(trim((string)$_POST["cpf"])) &&
    isset($_POST["setor"]) && !empty($_POST["setor"])
) {
    $id_usuario = $_POST["user_id"];
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $cpf = trim((string)$_POST["cpf"]);
    $id_setor = $_POST["setor"];
    $senha = isset($_POST["senha"]) ? (string)$_POST["senha"] : null;

    $admin_model = new admin();
    $result = $admin_model->editar_usuario($id_usuario, $nome, $email, $cpf, $id_setor, $senha);

    switch ($result) {
        case 1:
            header('Location: ../views/usuario.php?editado');
            exit();
        case 2:
            header('Location: ../views/usuario.php?erro');
            exit();
        case 3:
            header('Location: ../views/usuario.php?ja_existe');
            exit();
        default:
            header('Location: ../views/usuario.php?falha');
            exit();
    }
} else {
    header('Location: ../views/usuario.php?falha');
    exit();
}