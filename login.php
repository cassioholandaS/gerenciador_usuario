<?php
require_once __DIR__ . '/config/connect.php';
require_once __DIR__ . '/models/sessions.php';
require_once __DIR__ . '/models/model.solicitacoes_primeiro_acesso.php';

$session = new sessions();
if (isset($_GET['sair'])) {
    $session->deslogar();
}
if (isset($_GET['cancelar_primeiro_acesso'])) {
    unset($_SESSION['primeiro_acesso_usuario_id'], $_SESSION['primeiro_acesso_email']);
    header('Location: login.php');
    exit();
}

if (isset($_SESSION['id'], $_SESSION['email'], $_SESSION['nome'])) {
    header('Location: index.php');
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errorMessage = '';
$successMessage = '';
$pageMode = isset($_SESSION['primeiro_acesso_usuario_id']) ? 'password' : 'login';
$postedAction = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($submittedToken) || !hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        http_response_code(400);
        $errorMessage = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
    } else {
        try {
            if ($postedAction === 'login') {
                $email = trim((string)($_POST['email'] ?? ''));
                $password = (string)($_POST['senha'] ?? '');
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
                           AND t.tipo IN ('Admin_usuario', 'Dev_usuario')
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
                        $_SESSION['notification_csrf_token'] = bin2hex(random_bytes(32));
                        unset($_SESSION['csrf_token']);
                        header('Location: index.php');
                        exit();
                    }
                }

                $pageMode = 'login';
                $errorMessage = 'Dados de login incorretos. Verifique suas credenciais e tente novamente.';
            } elseif ($postedAction === 'verify_first_access') {
                $email = trim((string)($_POST['email'] ?? ''));
                $cpf = (string)($_POST['cpf'] ?? '');
                $cpfDigits = preg_replace('/\D+/', '', $cpf);

                if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($cpfDigits) !== 11) {
                    $pageMode = 'first_access';
                    $errorMessage = 'Informe um e-mail válido e um CPF com 11 dígitos.';
                } else {
                    $requests = new model_solicitacoes_primeiro_acesso();
                    $user = $requests->validarIdentidade($email, $cpf);
                    if (!$user) {
                        $pageMode = 'first_access';
                        $errorMessage = 'Não foi possível validar os dados ou já existe uma solicitação pendente.';
                    } else {
                        session_regenerate_id(true);
                        $_SESSION['primeiro_acesso_usuario_id'] = $user['id'];
                        $_SESSION['primeiro_acesso_email'] = $user['email'];
                        $pageMode = 'password';
                    }
                }
            } elseif ($postedAction === 'submit_first_access') {
                $userId = (int)($_SESSION['primeiro_acesso_usuario_id'] ?? 0);
                $password = (string)($_POST['senha'] ?? '');
                $passwordConfirmation = (string)($_POST['confirmar_senha'] ?? '');
                $pageMode = 'password';

                if ($userId < 1) {
                    $pageMode = 'first_access';
                    $errorMessage = 'Confirme seu e-mail e CPF para iniciar o primeiro acesso.';
                } elseif (strlen($password) < 6) {
                    $errorMessage = 'A senha deve conter pelo menos 6 caracteres.';
                } elseif (!hash_equals($password, $passwordConfirmation)) {
                    $errorMessage = 'As senhas informadas não coincidem.';
                } else {
                    $requests = new model_solicitacoes_primeiro_acesso();
                    if (!$requests->criarSolicitacao($userId, $password)) {
                        $pageMode = 'first_access';
                        unset($_SESSION['primeiro_acesso_usuario_id'], $_SESSION['primeiro_acesso_email']);
                        $errorMessage = 'A conta não está mais disponível para primeiro acesso.';
                    } else {
                        unset($_SESSION['primeiro_acesso_usuario_id'], $_SESSION['primeiro_acesso_email']);
                        $pageMode = 'login';
                        $successMessage = 'Solicitação enviada para aprovação no Gerenciador de Usuários.';
                    }
                }
            } else {
                http_response_code(400);
                $errorMessage = 'Ação de autenticação inválida.';
            }
        } catch (Throwable $e) {
            error_log('Falha na autenticação do Gerenciador de Usuários: ' . $e->getMessage());
            http_response_code(503);
            $errorMessage = 'Não foi possível validar o acesso. Confira a conexão e a configuração do banco na hospedagem.';
        }
    }
}

function escapeHtml(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$firstAccessEmail = (string)($_SESSION['primeiro_acesso_email'] ?? '');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Gerenciador de Usuários - STGM</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: linear-gradient(135deg, #f4f7f5, #e8f4f8); color: #1a3c34; font: 16px Arial, sans-serif; }
        main { width: min(100%, 460px); padding: 32px; border: 1px solid #dce6df; border-radius: 20px; background: #fff; box-shadow: 0 16px 44px rgba(26, 60, 52, .14); }
        h1 { margin: 0 0 8px; font-size: 25px; }
        p { margin: 0 0 22px; color: #52645b; line-height: 1.5; }
        .tabs { display: flex; gap: 8px; padding: 5px; border-radius: 12px; background: #f1f5f2; margin-bottom: 20px; }
        .tabs button { flex: 1; border: 0; border-radius: 9px; background: transparent; color: #52645b; padding: 11px 8px; font: inherit; font-weight: 600; cursor: pointer; }
        .tabs button[aria-selected="true"] { background: #fff; color: #005a24; box-shadow: 0 2px 8px rgba(26, 60, 52, .12); }
        label { display: block; margin: 16px 0 6px; font-weight: 600; }
        input { width: 100%; padding: 12px; border: 1px solid #b9c9be; border-radius: 9px; font: inherit; }
        input:focus { outline: 3px solid rgba(0, 90, 36, .13); border-color: #005a24; }
        .submit { width: 100%; margin-top: 22px; padding: 13px; border: 0; border-radius: 9px; background: #005a24; color: #fff; font: inherit; font-weight: 700; cursor: pointer; }
        .submit:hover { background: #1a3c34; }
        .error, .success { padding: 12px; border-radius: 9px; line-height: 1.45; margin-bottom: 16px; }
        .error { background: #fff0f0; color: #8b1e1e; }
        .success { background: #e9f7ef; color: #155724; }
        .notice { margin: 20px 0 0; font-size: 13px; }
        .hidden { display: none; }
        .back { display: inline-block; margin-top: 16px; color: #005a24; cursor: pointer; font-size: 14px; }
    </style>
</head>
<body>
    <main>
        <h1>Gerenciador de Usuários</h1>
        <p>Acesse sua conta ou solicite o primeiro acesso com os dados já cadastrados.</p>

        <?php if ($errorMessage !== ''): ?>
            <div class="error" role="alert"><?php echo escapeHtml($errorMessage); ?></div>
        <?php endif; ?>
        <?php if ($successMessage !== ''): ?>
            <div class="success" role="status"><?php echo escapeHtml($successMessage); ?></div>
        <?php endif; ?>

        <?php if ($pageMode === 'password'): ?>
            <h2>Primeiro acesso</h2>
            <p>Defina sua senha para enviar a solicitação de aprovação.</p>
            <form method="post" action="login.php" autocomplete="on">
                <input type="hidden" name="csrf_token" value="<?php echo escapeHtml($_SESSION['csrf_token']); ?>">
                <input type="hidden" name="action" value="submit_first_access">
                <label for="firstAccessEmail">E-mail</label>
                <input id="firstAccessEmail" type="email" value="<?php echo escapeHtml($firstAccessEmail); ?>" disabled>
                <label for="firstAccessPassword">Nova senha</label>
                <input id="firstAccessPassword" name="senha" type="password" minlength="6" autocomplete="new-password" required>
                <label for="confirmFirstAccessPassword">Confirmar nova senha</label>
                <input id="confirmFirstAccessPassword" name="confirmar_senha" type="password" minlength="6" autocomplete="new-password" required>
                <button class="submit" type="submit">Enviar para aprovação</button>
            </form>
            <a class="back" href="login.php?cancelar_primeiro_acesso=1">Voltar ao login</a>
        <?php else: ?>
            <div class="tabs" role="tablist" aria-label="Acesso ao sistema">
                <button type="button" id="loginTab" role="tab" aria-selected="<?php echo $pageMode === 'login' ? 'true' : 'false'; ?>" aria-controls="loginPanel">Login</button>
                <button type="button" id="firstAccessTab" role="tab" aria-selected="<?php echo $pageMode === 'first_access' ? 'true' : 'false'; ?>" aria-controls="firstAccessPanel">Primeiro acesso</button>
            </div>

            <section id="loginPanel" role="tabpanel" aria-labelledby="loginTab" class="<?php echo $pageMode === 'login' ? '' : 'hidden'; ?>">
                <form method="post" action="login.php">
                    <input type="hidden" name="csrf_token" value="<?php echo escapeHtml($_SESSION['csrf_token']); ?>">
                    <input type="hidden" name="action" value="login">
                    <label for="loginEmail">E-mail</label>
                    <input id="loginEmail" name="email" type="email" autocomplete="username" required>
                    <label for="loginPassword">Senha</label>
                    <input id="loginPassword" name="senha" type="password" autocomplete="current-password" required>
                    <button class="submit" type="submit">Acessar sistema</button>
                </form>
            </section>

            <section id="firstAccessPanel" role="tabpanel" aria-labelledby="firstAccessTab" class="<?php echo $pageMode === 'first_access' ? '' : 'hidden'; ?>">
                <p>Informe o e-mail e o CPF associados à sua conta para definir a senha do primeiro acesso.</p>
                <form method="post" action="login.php">
                    <input type="hidden" name="csrf_token" value="<?php echo escapeHtml($_SESSION['csrf_token']); ?>">
                    <input type="hidden" name="action" value="verify_first_access">
                    <label for="firstAccessVerifyEmail">E-mail</label>
                    <input id="firstAccessVerifyEmail" name="email" type="email" autocomplete="email" required>
                    <label for="firstAccessCpf">CPF</label>
                    <input id="firstAccessCpf" name="cpf" type="text" inputmode="numeric" autocomplete="off" maxlength="14" placeholder="000.000.000-00" required>
                    <button class="submit" type="submit">Verificar primeiro acesso</button>
                </form>
            </section>
        <?php endif; ?>

        <p class="notice">As solicitações de primeiro acesso são analisadas pelos administradores deste gerenciador.</p>
    </main>
    <script>
        const loginTab = document.getElementById('loginTab');
        const firstAccessTab = document.getElementById('firstAccessTab');
        if (loginTab && firstAccessTab) {
            const setActiveTab = (showFirstAccess) => {
                loginTab.setAttribute('aria-selected', String(!showFirstAccess));
                firstAccessTab.setAttribute('aria-selected', String(showFirstAccess));
                document.getElementById('loginPanel').classList.toggle('hidden', showFirstAccess);
                document.getElementById('firstAccessPanel').classList.toggle('hidden', !showFirstAccess);
            };
            loginTab.addEventListener('click', () => setActiveTab(false));
            firstAccessTab.addEventListener('click', () => setActiveTab(true));
        }
        const cpfInput = document.getElementById('firstAccessCpf');
        if (cpfInput) {
            cpfInput.addEventListener('input', () => {
                const digits = cpfInput.value.replace(/\D/g, '').slice(0, 11);
                cpfInput.value = digits
                    .replace(/(\d{3})(\d)/, '$1.$2')
                    .replace(/(\d{3})(\d)/, '$1.$2')
                    .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            });
        }
    </script>
</body>
</html>
