<?php
class gerenciar_usuarios_connect
{
    protected $connect;

    public function connection(): PDO
    {
        if (!$this->connect instanceof PDO) {
            throw new RuntimeException('A conexão com o banco de dados não está disponível.');
        }

        return $this->connect;
    }

    function __construct()
    {
        $this->connect_database();
    }

    function connect_database()
    {
        date_default_timezone_set('America/Sao_Paulo');

        $configPath = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . '.env' . DIRECTORY_SEPARATOR . 'config.php';
        $config = is_file($configPath) ? require $configPath : [];
        if (!is_array($config)) {
            throw new RuntimeException('A configuração do banco de dados é inválida.');
        }

        $local = $config['local']['salaberga_users'] ?? [];
        $hosted = $config['hospedagem']['salaberga_users'] ?? [];
        if (!is_array($local)) {
            $local = [];
        }
        if (!is_array($hosted)) {
            $hosted = [];
        }

        $local = [
            'host' => getenv('DB_HOST') ?: ($local['host'] ?? 'localhost'),
            'database' => getenv('DB_NAME') ?: ($local['banco'] ?? $local['database'] ?? 'salaberga_users'),
            'user' => getenv('DB_USER') ?: ($local['user'] ?? 'root'),
            'password' => getenv('DB_PASSWORD') !== false
                ? getenv('DB_PASSWORD')
                : ($local['senha'] ?? $local['password'] ?? ''),
        ];

        $connections = [$local];
        if ($hosted !== []) {
            $connections[] = [
                'host' => $hosted['host'] ?? '',
                'database' => $hosted['banco'] ?? $hosted['database'] ?? '',
                'user' => $hosted['user'] ?? '',
                'password' => $hosted['senha'] ?? $hosted['password'] ?? '',
            ];
        }

        $lastException = null;
        foreach ($connections as $connection) {
            if ($connection['host'] === '' || $connection['database'] === '' || $connection['user'] === '') {
                continue;
            }

            try {
                $this->connect = new PDO(
                    'mysql:host=' . $connection['host'] . ';dbname=' . $connection['database'] . ';charset=utf8mb4',
                    $connection['user'],
                    $connection['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4, time_zone = '-03:00'",
                    ]
                );
                return;
            } catch (PDOException $e) {
                $lastException = $e;
                error_log('Falha ao conectar ao banco do Gerenciador de Usuários: ' . $e->getMessage());
            }
        }

        throw new RuntimeException(
            'Não foi possível conectar ao banco de dados do Gerenciador de Usuários.',
            0,
            $lastException
        );
    }
}
