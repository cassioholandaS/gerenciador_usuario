<?php
require_once __DIR__ . '/../models/sessions.php';
require_once __DIR__ . '/../config/connect.php';
require_once __DIR__ . '/../models/model.solicitacoes_primeiro_acesso.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function notificationResponse(int $status, array $body): void
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

if (
    !isset($_SESSION['id'], $_SESSION['email'], $_SESSION['nome'])
    || (isset($_SESSION['ultimo_acesso']) && time() - (int)$_SESSION['ultimo_acesso'] > 600)
) {
    notificationResponse(401, ['sucesso' => false, 'erro' => 'Sessão expirada. Entre novamente.']);
}

try {
    $database = new gerenciar_usuarios_connect();
    $permissionQuery = $database->connection()->prepare(
        "SELECT 1
         FROM permissoes p
         INNER JOIN tipos_usuarios t ON t.id = p.id_tipos_usuarios
         INNER JOIN sistemas s ON s.id = p.id_sistemas
         WHERE p.id_usuarios = :user_id
           AND t.tipo IN ('Admin_usuario', 'Dev_usuario')
           AND s.nome = 'Gerenciador de Usuarios'
         LIMIT 1"
    );
    $permissionQuery->execute(['user_id' => (int)$_SESSION['id']]);
    if (!$permissionQuery->fetchColumn()) {
        notificationResponse(403, ['sucesso' => false, 'erro' => 'Você não tem permissão para gerenciar solicitações.']);
    }

    $requests = new model_solicitacoes_primeiro_acesso();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (($_GET['detalhes'] ?? '') === '1') {
            $pending = $requests->listarPendentes();
            notificationResponse(200, [
                'sucesso' => true,
                'quantidade' => $requests->contarPendentes(),
                'solicitacoes' => $pending,
                'csrf_token' => $_SESSION['notification_csrf_token'] ?? '',
            ]);
        }

        notificationResponse(200, [
            'sucesso' => true,
            'quantidade' => $requests->contarPendentes(),
        ]);
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: GET, POST');
        notificationResponse(405, ['sucesso' => false, 'erro' => 'Método não permitido.']);
    }

    $payload = json_decode(file_get_contents('php://input'), true);
    if (!is_array($payload)) {
        notificationResponse(400, ['sucesso' => false, 'erro' => 'Requisição inválida.']);
    }

    $csrfToken = $_SESSION['notification_csrf_token'] ?? '';
    if (
        $csrfToken === ''
        || !isset($payload['csrf_token'])
        || !is_string($payload['csrf_token'])
        || !hash_equals($csrfToken, $payload['csrf_token'])
    ) {
        notificationResponse(403, ['sucesso' => false, 'erro' => 'A sessão expirou. Atualize a página e tente novamente.']);
    }

    $requestId = filter_var($payload['id'] ?? null, FILTER_VALIDATE_INT);
    if ($requestId === false || $requestId < 1) {
        notificationResponse(400, ['sucesso' => false, 'erro' => 'Solicitação inválida.']);
    }

    $result = $requests->decidir(
        (int)$requestId,
        (string)($payload['decisao'] ?? ''),
        (int)$_SESSION['id']
    );

    notificationResponse($result['sucesso'] ? 200 : 409, $result);
} catch (Throwable $e) {
    error_log('Erro ao gerenciar solicitações de primeiro acesso: ' . $e->getMessage());
    notificationResponse(503, [
        'sucesso' => false,
        'erro' => 'Não foi possível consultar ou atualizar as solicitações locais.',
    ]);
}
