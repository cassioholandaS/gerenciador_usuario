<?php
require_once(__DIR__ . "/../models/model.admin.php");
//print_r($_POST);

if (
    isset($_POST["sector_id"]) && !empty($_POST["sector_id"]) &&
    isset($_POST["nome"]) && !empty($_POST["nome"])
) {
    $nome = $_POST["nome"];
    $id_setor = $_POST["sector_id"];

    $admin_model = new admin();
    $result = $admin_model->editar_setor($id_setor, $nome);

    switch ($result) {
        case 1:
            header('Location: ../views/setor.php?editado');
            exit();
        case 2:
            header('Location: ../views/setor.php?erro');
            exit();
        case 3:
            header('Location: ../views/setor.php?ja_existe');
            exit();
        default:
            header('Location: ../views/setor.php?falha');
            exit();
    }
} else if (
    isset($_POST["sector_id"]) && empty($_POST["sector_id"]) &&
    isset($_POST["nome"]) && !empty($_POST["nome"])
) {
    $nome = $_POST["nome"];

    $admin_model = new admin();
    $result = $admin_model->criar_setor($nome);

    switch ($result) {
        case 1:
            header('Location: ../views/setor.php?criado');
            exit();
        case 2:
            header('Location: ../views/setor.php?erro');
            exit();
        case 3:
            header('Location: ../views/setor.php?ja_existe');
            exit();
        default:
            header('Location: ../views/setor.php?falha');
            exit();
    }
} else if (
    isset($_POST["sector_id"]) && !empty($_POST["sector_id"]) &&
    !isset($_POST["nome"]) && empty($_POST["nome"])
) {
    $id_setor = $_POST["sector_id"];

    $admin_model = new admin();
    $result = $admin_model->excluir_setor($id_setor);

    switch ($result) {
        case 1:
            header('Location: ../views/setor.php?excluido');
            exit();
        case 2:
            header('Location: ../views/setor.php?erro');
            exit();
        default:
            header('Location: ../views/setor.php?falha');
            exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}
