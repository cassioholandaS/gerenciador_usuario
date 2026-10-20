<?php
class connect
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

        $hosted = $config['hospedagem']['salaberga_users']
            ?? $config['host']['salaberga_users']
            ?? $config['host']
            ?? [];
        if (!is_array($hosted)) {
            $hosted = [];
        }

        $hosted['host'] = getenv('DB_HOST') ?: ($hosted['host'] ?? '');
        $hosted['database'] = getenv('DB_NAME') ?: ($hosted['database'] ?? $hosted['banco'] ?? '');
        $hosted['user'] = getenv('DB_USER') ?: ($hosted['user'] ?? '');
        $hosted['password'] = getenv('DB_PASSWORD') !== false
            ? getenv('DB_PASSWORD')
            : ($hosted['password'] ?? $hosted['senha'] ?? '');

        $connections = [[
            'host' => $host !== false ? $host : ($local['host'] ?? 'localhost'),
            'database' => $database !== false ? $database : ($local['database'] ?? $local['banco'] ?? 'u750204740_users'),
            'user' => $user !== false ? $user : ($local['user'] ?? 'root'),
            'password' => $password !== false ? $password : ($local['password'] ?? $local['senha'] ?? ''),
        ]];

        if (isset($config['hospedagem']['salaberga_users'])) {
            $connections[] = [
                'host' => $config['hospedagem']['salaberga_users']['host'],
                'database' => $config['hospedagem']['salaberga_users']['banco'],
                'user' => $config['hospedagem']['salaberga_users']['user'],
                'password' => $config['hospedagem']['salaberga_users']['senha'],
            ];
        }

        try {
            $this->connect = new PDO(
                'mysql:host=' . $hosted['host'] . ';dbname=' . $hosted['database'] . ';charset=utf8mb4',
                $hosted['user'],
                $hosted['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4, time_zone = '-03:00'",
                ]
            );
        } catch (PDOException $e) {
            error_log('Erro de conexão com o banco do Gerenciador de Usuários: ' . $e->getMessage());
            throw new RuntimeException('Não foi possível conectar ao banco de dados do Gerenciador de Usuários.', 0, $e);
        }
    }
}
