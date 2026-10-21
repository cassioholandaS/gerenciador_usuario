<?php
require_once __DIR__ . '/../config/connect.php';

class model_solicitacoes_primeiro_acesso
{
    private PDO $connection;

    public function __construct()
    {
        $database = new gerenciar_usuarios_connect();
        $this->connection = $database->connection();
    }

    public function validarIdentidade(string $email, string $cpf): ?array
    {
        $statement = $this->connection->prepare(
            'SELECT id, nome, email, cpf, senha
             FROM usuarios
             WHERE email = :email'
        );
        $statement->execute(['email' => trim($email)]);
        $users = $statement->fetchAll();

        foreach ($users as $user) {
            $storedCpf = preg_replace('/\D+/', '', (string)($user['cpf'] ?? ''));
            if (
                hash_equals($storedCpf, preg_replace('/\D+/', '', $cpf))
                && ($user['senha'] === null || $user['senha'] === '')
            ) {
                $pending = $this->connection->prepare(
                    "SELECT 1
                     FROM solicitacoes_primeiro_acesso
                     WHERE id_usuario = :user_id AND status = 'pendente'
                     LIMIT 1"
                );
                $pending->execute(['user_id' => $user['id']]);
                if ($pending->fetchColumn()) {
                    return null;
                }

                return [
                    'id' => (int)$user['id'],
                    'email' => trim((string)$user['email']),
                ];
            }
        }

        return null;
    }

    public function criarSolicitacao(int $userId, string $password): bool
    {
        $this->connection->beginTransaction();
        try {
            $userQuery = $this->connection->prepare(
                'SELECT id, senha FROM usuarios WHERE id = :id FOR UPDATE'
            );
            $userQuery->execute(['id' => $userId]);
            $user = $userQuery->fetch();
            if (!$user || !($user['senha'] === null || $user['senha'] === '')) {
                $this->connection->rollBack();
                return false;
            }

            $pendingQuery = $this->connection->prepare(
                "SELECT id
                 FROM solicitacoes_primeiro_acesso
                 WHERE id_usuario = :user_id AND status = 'pendente'
                 LIMIT 1"
            );
            $pendingQuery->execute(['user_id' => $userId]);
            if ($pendingQuery->fetchColumn()) {
                $this->connection->rollBack();
                return false;
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            if (!is_string($passwordHash)) {
                throw new RuntimeException('Não foi possível proteger a senha solicitada.');
            }

            $insert = $this->connection->prepare(
                "INSERT INTO solicitacoes_primeiro_acesso (id_usuario, senha_hash)
                 VALUES (:user_id, :password_hash)"
            );
            $insert->execute([
                'user_id' => $userId,
                'password_hash' => $passwordHash,
            ]);
            $this->connection->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }
            throw $e;
        }
    }

    public function listarPendentes(): array
    {
        $statement = $this->connection->query(
            "SELECT r.id, u.nome, u.email, u.cpf,
                    DATE_FORMAT(r.data_solicitacao, '%d/%m/%Y %H:%i') AS data_solicitacao
             FROM solicitacoes_primeiro_acesso r
             INNER JOIN usuarios u ON u.id = r.id_usuario
             WHERE r.status = 'pendente'
             ORDER BY r.data_solicitacao ASC, r.id ASC
             LIMIT 100"
        );
        return $statement->fetchAll();
    }

    public function contarPendentes(): int
    {
        return (int)$this->connection->query(
            "SELECT COUNT(*) FROM solicitacoes_primeiro_acesso WHERE status = 'pendente'"
        )->fetchColumn();
    }

    public function decidir(int $requestId, string $decision, int $adminId): array
    {
        if (!in_array($decision, ['aprovar', 'recusar'], true)) {
            return ['sucesso' => false, 'erro' => 'Decisão inválida.'];
        }

        $this->connection->beginTransaction();
        try {
            $requestQuery = $this->connection->prepare(
                "SELECT id, id_usuario, senha_hash
                 FROM solicitacoes_primeiro_acesso
                 WHERE id = :id AND status = 'pendente'
                 FOR UPDATE"
            );
            $requestQuery->execute(['id' => $requestId]);
            $request = $requestQuery->fetch();
            if (!$request) {
                $this->connection->rollBack();
                return ['sucesso' => false, 'erro' => 'A solicitação não está mais pendente.'];
            }

            if ($decision === 'aprovar') {
                $userQuery = $this->connection->prepare(
                    'SELECT senha FROM usuarios WHERE id = :id FOR UPDATE'
                );
                $userQuery->execute(['id' => $request['id_usuario']]);
                $currentPassword = $userQuery->fetchColumn();
                if ($currentPassword === false || !($currentPassword === null || $currentPassword === '')) {
                    $this->connection->rollBack();
                    return ['sucesso' => false, 'erro' => 'A conta não está mais aguardando o primeiro acesso.'];
                }

                $setPassword = $this->connection->prepare(
                    'UPDATE usuarios SET senha = :password_hash WHERE id = :user_id'
                );
                $setPassword->execute([
                    'password_hash' => $request['senha_hash'],
                    'user_id' => $request['id_usuario'],
                ]);
            }

            $status = $decision === 'aprovar' ? 'aprovado' : 'recusado';
            $update = $this->connection->prepare(
                'UPDATE solicitacoes_primeiro_acesso
                 SET status = :status, senha_hash = NULL, id_aprovador = :admin_id,
                     data_decisao = CURRENT_TIMESTAMP
                 WHERE id = :id'
            );
            $update->execute([
                'status' => $status,
                'admin_id' => $adminId,
                'id' => $requestId,
            ]);

            $this->connection->commit();
            return [
                'sucesso' => true,
                'mensagem' => $decision === 'aprovar'
                    ? 'Primeiro acesso aprovado.'
                    : 'Solicitação recusada.',
            ];
        } catch (Throwable $e) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }
            throw $e;
        }
    }
}
