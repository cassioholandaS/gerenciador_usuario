<?php
class connect
{
    protected $connect;

    function __construct()
    {
        $this->connect_database();
    }

    function connect_database()
    {
        try {
            $config = require(__DIR__."/../../../../.env/config.php");

            // Tentar primeiro o banco local
            try {
                $host = $config['local']['salaberga_users']['host'];
                $database = $config['local']['salaberga_users']['banco'];
                $user = $config['local']['salaberga_users']['user'];
                $password = $config['local']['salaberga_users']['senha'];

                $this->connect = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8', $user, $password);
                $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Se falhar, tentar o banco da hospedagem
                $host = $config['hospedagem']['salaberga_users']['host'];
                $database = $config['hospedagem']['salaberga_users']['banco'];
                $user = $config['hospedagem']['salaberga_users']['user'];
                $password = $config['hospedagem']['salaberga_users']['senha'];

                $this->connect = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8', $user, $password);
                $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {

            error_log("Erro de conexão com banco: " . $e->getMessage());
            $this->connect = null;
            header('location:../views/windows/desconnect.php');
            exit();
        }
    }
}
