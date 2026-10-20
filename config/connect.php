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
        $configPath = __DIR__ . '/../../../../.env/config.php';
        $config = is_file($configPath) ? require $configPath : [];
        $local = $config['local'] ?? [];
        if (isset($local['salaberga_users'])) {
            $local = $local['salaberga_users'];
        }

        $host = getenv('STGM_DB_HOST');
        $database = getenv('STGM_DB_DATABASE');
        $user = getenv('STGM_DB_USER');
        $password = getenv('STGM_DB_PASSWORD');

        $connections = [[
            'host' => $host !== false ? $host : ($local['host'] ?? 'localhost'),
            'database' => $database !== false ? $database : ($local['database'] ?? $local['banco'] ?? 'salaberga_users'),
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

        $lastError = null;
        foreach ($connections as $credentials) {
            try {
                $this->connect = new PDO(
                    'mysql:host=' . $credentials['host'] . ';dbname=' . $credentials['database'] . ';charset=utf8mb4',
                    $credentials['user'],
                    $credentials['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
                return;
            } catch (PDOException $e) {
                $lastError = $e;
            }
        }

        error_log('Erro de conexão com banco: ' . $lastError->getMessage());
        throw new RuntimeException('Não foi possível conectar ao banco de dados local.', 0, $lastError);
    }
}
