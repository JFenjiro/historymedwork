<?php
    namespace core\Model;

    use \PDO;
    use \PDOException;
    use core\Model\Database;

    class Region {
        private int $id;
        private string $name;
        private ?string $description;
        private string $nameCode;
        private ?array $maps;

        // Constructeur
        public function __construct(int $id=0, string $name="", string $description="", string $nameCode="") {
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
            $this->nameCode = $nameCode;
            $this->maps = array();
        }

        // Accesseur magique
        public function __get($attribute) {
            return $this->$attribute;
        }
        
        public function __set($attribute, $value) {
            switch ($attribute) {
                case "id":
                    if ($value > 0) {
                        $this->id = $value;
                    } else {
                        $this->id = 0;
                    }
                    break;
                case "name_code":
                case "nameCode":
                    $this->nameCode = $value;
                    break;
                default:
                    $this->$attribute = $value;
            }
        }

        // CRUD : persistance de l'objet en base de données
        /**
         * Enregistre ou modifie une Region en base de données 
         * save = create | update
         * @return bool
         */
        public function save(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($this->id > 0) {
                    $query = $pdo->prepare("CALL region_update(:name, :description, :name_code, :id)");

                    $query->bindParam(":name", $this->name, PDO::PARAM_STR);
                    $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                    $query->bindParam(":name_code", $this->nameCode, PDO::PARAM_STR);
                    $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                    return $query->execute();
                } else {
                    $query = $pdo->prepare("CALL region_save(:name, :description, :name_code)");

                    $query->bindParam(":name", $this->name, PDO::PARAM_STR);
                    $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                    $query->bindParam(":name_code", $this->nameCode, PDO::PARAM_STR);
    
                    return $query->execute();
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            } 
        }

        /**
         * Modifie les données d'une Region enregistrée en base de données
         * @return bool
         */
        public function edit(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL region_update(:name, :description, :name_code, :id)");

                $query->bindParam(":name", $this->name, PDO::PARAM_STR);
                $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                $query->bindParam(":name_code", $this->nameCode, PDO::PARAM_STR);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Supprime une Region de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL region_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve toutes les Regions enregistrées en base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL region_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Region");

                if ($query->execute()) {
                    $results = $query->fetchAll();
                    return $results;
                }
                return null;
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return null;
            }
        }

        /**
         * Retrouve une Region par son id depuis la base de données
         * @param int $id Représente l'identifiant unique de la région
         * @return Region La région à laquelle l'id correspond
         */
        public static function findById(int $id): ?Region {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL region_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Region");

                if ($query->execute()) {
                    $result = $query->fetch();
                    return $result;
                }
                return null;
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return null;
            }
        }

        /**
         * Retrouve les Regions par leur nom depuis la base de données
         * @param string $name Représente le nom de la région
         * @return array
         */
        public static function findByName(string $name): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $name = "%" . $name . "%";

                $query = $pdo->prepare("CALL region_select_name(:name)");

                $query->bindParam(":name", $name, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Region");

                if ($query->execute()) {
                    $results = $query->fetchAll();
                    return $results;
                }
                return null;
            } catch (PDOException $e) {
                echo "ERROR : " . $e->getMessage();
                return null;
            }
        }

        /**
         * Liste toutes les Maps liées à une région
         */
        public function loadAllMaps() {
            $this->maps = Map::findByRegion($this->id);
        }
    }