<?php
require_once __DIR__ . '/config/connect.php';
require_once __DIR__ . '/models/sessions.php';

$session = new sessions();
if (isset($_GET['sair'])) {
    $session->deslogar();
}

if (isset($_SESSION['id'], $_SESSION['email'], $_SESSION['nome'])) {
    header('Location: index.php');
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($submittedToken) || !hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        http_response_code(400);
        $errorMessage = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
    } else {
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        try {
            $database = new connect();
            $statement = $database->connection()->prepare(
                'SELECT u.id, u.nome, u.email, u.senha, u.foto_perfil, s.nome AS setor
                 FROM usuarios u
                 LEFT JOIN setores s ON s.id = u.id_setor
                 WHERE u.email = :email
                 LIMIT 1'
            );
            $statement->execute(['email' => $email]);
            $user = $statement->fetch();

            if (
                $user
                && is_string($user['senha'])
                && password_verify($password, $user['senha'])
            ) {
                $permission = $database->connection()->prepare(
                    "SELECT 1
                     FROM permissoes p
                     INNER JOIN tipos_usuarios t ON t.id = p.id_tipos_usuarios
                     INNER JOIN sistemas s ON s.id = p.id_sistemas
                     WHERE p.id_usuarios = :user_id
                       AND t.tipo = 'Admin_usuario'
                       AND s.nome = 'Gerenciador de Usuarios'
                     LIMIT 1"
                );
                $permission->execute(['user_id' => $user['id']]);

                if ($permission->fetchColumn()) {
                    session_regenerate_id(true);
                    $_SESSION['id'] = (int)$user['id'];
                    $_SESSION['nome'] = $user['nome'];
                    $_SESSION['email'] = trim($user['email']);
                    $_SESSION['setor'] = $user['setor'] ?? 'Sistema de Gestão';
                    $_SESSION['foto_perfil'] = $user['foto_perfil'] ?? '';
                    $_SESSION['ultimo_acesso'] = time();
                    unset($_SESSION['csrf_token']);

                    header('Location: index.php');
                    exit();
                }
            }

            $errorMessage = 'E-mail ou senha inválidos, ou a conta não tem acesso de administrador a este módulo.';
        } catch (Throwable $e) {
            error_log('Falha no login local do gerenciador: ' . $e->getMessage());
            http_response_code(503);
            $errorMessage = 'Não foi possível validar o acesso. Confira a conexão local com o banco de dados.';
        }
    }
}

function escapeHtml(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acesso local - Gerenciador de Usuários</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #f4f7f5; color: #1a3c34; font: 16px Arial, sans-serif; }
        main { width: min(100%, 420px); padding: 32px; border: 1px solid #dce6df; border-radius: 16px; background: #fff; box-shadow: 0 12px 32px rgba(26, 60, 52, .1); }
        h1 { margin: 0 0 8px; font-size: 24px; }
        p { margin: 0 0 24px; color: #52645b; line-height: 1.5; }
        label { display: block; margin: 16px 0 6px; font-weight: 600; }
        input { width: 100%; padding: 12px; border: 1px solid #b9c9be; border-radius: 8px; font: inherit; }
        button { width: 100%; margin-top: 22px; padding: 12px; border: 0; border-radius: 8px; background: #005a24; color: #fff; font: inherit; font-weight: 700; cursor: pointer; }
        .error { padding: 12px; border-radius: 8px; background: #fff0f0; color: #8b1e1e; line-height: 1.45; }
        .notice { margin-top: 20px; font-size: 13px; }
    </style>
</head>
<body>
    <main>
        <h1>Gerenciador de Usuários</h1>
        <p>Entre com uma conta que tenha perfil de administrador neste módulo.</p>
        <?php if ($errorMessage !== ''): ?>
            <div class="error" role="alert"><?php echo escapeHtml($errorMessage); ?></div>
        <?php endif; ?>
        <form method="post" action="login.php">
            <input type="hidden" name="csrf_token" value="<?php echo escapeHtml($_SESSION['csrf_token']); ?>">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" autocomplete="username" required>
            <label for="password">Senha</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button type="submit">Entrar</button>
        </form>
        <p class="notice">Acesso local do subsistema; contas sem permissão de administrador são recusadas.</p>
    </main>
</body>
</html>
