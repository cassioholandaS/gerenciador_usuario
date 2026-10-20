<?php
require_once(__DIR__ . "/../models/model.admin.php");
print_r($_POST);

if (
    isset($_POST["nome"]) && !empty($_POST["nome"])
    && !isset($_POST["cpf"]) && empty($_POST["cpf"])
) {
    $nome = $_POST["nome"];

    $admin_model = new admin();
    $result = $admin_model->criar_tipo_usuario($nome);

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
    isset($_POST["user_id"]) && !empty($_POST["user_id"])
    && !isset($_POST["cpf"]) && empty($_POST["cpf"])
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
    isset($_POST["nome"]) && !empty($_POST["nome"]) &&
    isset($_POST["email"]) && !empty($_POST["email"]) &&
    isset($_POST["cpf"]) && !empty($_POST["cpf"]) &&
    isset($_POST["setor"]) && !empty($_POST["setor"])
) {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
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
    isset($_POST["nome"]) && !empty($_POST["nome"]) &&
    isset($_POST["email"]) && !empty($_POST["email"]) &&
    isset($_POST["cpf"]) && !empty($_POST["cpf"]) &&
    isset($_POST["setor"]) && !empty($_POST["setor"])
) {
    $id_usuario = $_POST["user_id"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $id_setor = $_POST["setor"];

    $admin_model = new admin();
    $result = $admin_model->editar_usuario($id_usuario, $nome, $email, $cpf, $id_setor);

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
} /*else {
    header('Location: ../index.php');
    exit();
}*/