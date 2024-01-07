<?php
    namespace core\Model;

    use \PDO;
    use \PDOException;
    use core\Model\Database;

    class Type {
        private int $id;
        private string $name;
        private ?array $medias;

        // Constructeur
        public function __construct(int $id=0, string $name="") {
            $this->id = $id;
            $this->name = $name;
            $this->medias = array();
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
                default:
                    $this->$attribute = $value;
            }
        }

        // CRUD : persistance de l'objet en base de données
        /**
         * Enregistrer ou modifier un Type en base de données
         * save = create | update
         * @return bool
         */
        public function save(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($this->id > 0) {
                    $query = $pdo->prepare("CALL type_update(:name, :id)");

                    $query->bindParam(":name", $this->name, PDO::PARAM_STR);
                    $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                    return $query->execute();
                } else {
                    $query = $pdo->prepare("CALL type_save(:name)");

                    $query->bindParam(":name", $this->name, PDO::PARAM_STR);

                    return $query->execute();
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Modifie les données d'un Type enregistré en base de données
         * @return bool
         */
        public function edit(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL type_update(:name, :id)");

                $query->bindParam(":name", $this->name, PDO::PARAM_STR);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Supprime un Type de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL type_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve tous les Types de la base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL type_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Type");

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
         * Retrouve un Type par son id depuis la base de données
         * @param int $id Représente l'identifiant unique du type
         * @return Type Le type auquel l'id correspond
         */
        public static function findById(int $id): ?Type {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL type_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Type");

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
         * Retrouve un Type par son nom depuis la base de données
         * @param string $name Représente le nom du type
         * @return array
         */
        public static function findByName(string $name): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $name = "%" . $name . "%";

                $query = $pdo->prepare("CALL type_select_name(:name)");

                $query->bindParam(":name", $name, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Type");

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
         * Liste tous les Medias liés à un type
         */
        public function loadAllMedias() {
            $this->medias = Media::findByType($this->id);
        }
    }