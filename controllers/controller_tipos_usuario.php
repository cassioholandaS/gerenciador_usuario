<?php
require_once(__DIR__ . '/../models/model.admin.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/tipos_usuario.php');
    exit();
}

$nome = isset($_POST['nome']) && is_string($_POST['nome']) ? trim($_POST['nome']) : '';
$nomeLength = function_exists('mb_strlen') ? mb_strlen($nome, 'UTF-8') : strlen($nome);
$sistemaId = isset($_POST['sistema_id']) ? filter_var($_POST['sistema_id'], FILTER_VALIDATE_INT) : false;

if ($nome === '' || $nomeLength > 100 || $sistemaId === false || $sistemaId <= 0) {
    header('Location: ../views/tipos_usuario.php?erro');
    exit();
}

$admin = new admin();
$resultado = $admin->criar_tipo_usuario($nome, (int) $sistemaId);

switch ($resultado) {
    case 1:
        header('Location: ../views/tipos_usuario.php?criado');
        exit();
    case 3:
        header('Location: ../views/tipos_usuario.php?ja_existe');
        exit();
    case 2:
        header('Location: ../views/tipos_usuario.php?erro');
        exit();
    default:
        header('Location: ../views/tipos_usuario.php?falha');
        exit();
}
