<?php
    namespace core\Model;

    use \PDO;

    class Database {
        private PDO $pdo;

        // Accesseur
        public function getPdo() {
            return $this->pdo;
        }
        public function setPdo($pdo) {
            $this->pdo = new PDO($pdo);
        }

        // Méthode de connexion à la base de données
        public function connect(): PDO {
            require_once __DIR__ . "\\..\\config\\config.php";

            $dsn = "mysql:host=" . HOST . ";port=" . PORT . ";dbname=" . DBNAME . ";charset=" . CHARSET . "";
            $this->pdo = new PDO($dsn, DBUSER, DBPASS);

            return $this->pdo;
        }
    }