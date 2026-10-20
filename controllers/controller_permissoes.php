<?php
require_once(__DIR__ . "/../models/model.admin.php");
require_once(__DIR__ . "/../models/model.select.php");

// Handle AJAX request for getting permissions
if (isset($_GET['action']) && $_GET['action'] === 'get_permissions' && isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id'];
    
    $select = new select();
    $permissions = $select->listarPermissoesUsuario($user_id);
    
    if ($permissions === false) {
        $permissions = [];
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'permissions' => $permissions
    ]);
    exit();
}

// Handle AJAX request for getting user types for a specific system
if (isset($_GET['action']) && $_GET['action'] === 'get_user_types' && isset($_GET['sistema_id'])) {
    $sistema_id = (int)$_GET['sistema_id'];
    
    $select = new select();
    $user_types = $select->select_tipo_usuario_sistema($sistema_id);
    
    if ($user_types === false) {
        $user_types = [];
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'user_types' => $user_types
    ]);
    exit();
}

//print_r($_POST);

if (
    (!isset($_POST["perm_id"]) || empty($_POST["perm_id"])) &&
    isset($_POST["sistema"]) && !empty($_POST["sistema"]) &&
    isset($_POST["user_id"]) && !empty($_POST["user_id"]) &&
    isset($_POST["tipo_permissao"]) && !empty($_POST["tipo_permissao"])
) {
    $id_tipo_usuario = $_POST["tipo_permissao"];
    $id_sistema = $_POST["sistema"];
    $id_usuairo = $_POST["user_id"];

    $admin_model = new admin();
    $result = $admin_model->adicionar_permissao($id_usuairo, $id_tipo_usuario, $id_sistema);

    switch ($result) {
        case 1:
            header('Location: ../views/permissoes.php?user_id=' . $id_usuairo . '&criado');
            exit();
        case 2:
            header('Location: ../views/permissoes.php?user_id=' . $id_usuairo . '&erro');
            exit();
        case 3:
            header('Location: ../views/permissoes.php?user_id=' . $id_usuairo . '&ja_existe');
            exit();
        default:
            header('Location: ../views/permissoes.php?user_id=' . $id_usuairo . '&falha');
            exit();
    }
} else if (
    isset($_POST["perm_id"]) && !empty($_POST["perm_id"]) &&
    isset($_POST["user_id"]) && !empty($_POST["user_id"]) &&
    (!isset($_POST["sistema"]) || empty($_POST["sistema"])) &&
    (!isset($_POST["tipo_permissao"]) || empty($_POST["tipo_permissao"]))
) {
    $perm_id = $_POST["perm_id"];
    $id_usuairo = $_POST["user_id"];

    $admin_model = new admin();
    $result = $admin_model->excluir_permissao($perm_id);

    switch ($result) {
        case 1:
            header('Location: ../views/permissoes.php?user_id=' . $id_usuairo . '&excluido');
            exit();
        case 2:
            header('Location: ../views/permissoes.php?user_id=' . $id_usuairo . '&erro');
            exit();
        default:
            header('Location: ../views/permissoes.php?user_id=' . $id_usuairo . '&falha');
            exit();
    }
} else if (
    isset($_POST["perm_id"]) && !empty($_POST["perm_id"]) &&
    isset($_POST["sistema"]) && !empty($_POST["sistema"]) &&
    isset($_POST["user_id"]) && !empty($_POST["user_id"]) &&
    isset($_POST["tipo_permissao"]) && !empty($_POST["tipo_permissao"])
) {
    $perm_id = $_POST["perm_id"];
    $id_tipo_usuario = $_POST["tipo_permissao"];
    $id_sistema = $_POST["sistema"];
    $id_usuairo = $_POST["user_id"];

    $admin_model = new admin();
    $result = $admin_model->editar_permissao($perm_id, $id_usuairo, $id_tipo_usuario, $id_sistema);

    switch ($result) {
        case 1:
            header('Location: ../views/permissoes.php?user_id=' . $id_usuairo . '&editado');
            exit();
        case 2:
            header('Location: ../views/permissoes.php?user_id=' . $id_usuairo . '&erro');
            exit();
        case 3:
            header('Location: ../views/permissoes.php?user_id=' . $id_usuairo . '&ja_existe');
            exit();
        default:
            header('Location: ../views/permissoes.php?user_id=' . $id_usuairo . '&falha');
            exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}
