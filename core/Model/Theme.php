<?php
    namespace core\Model;

    use \PDO;
    use \PDOException;
    use core\Model\Database;
     
    class Theme {
        private int $id;
        private string $name;
        private ?string $description;
        private string $nameCode;
        private ?array $quizzes;
        private ?array $timelines;

        // Constructeur
        public function __construct(int $id=0, string $name="", string $description="", string $nameCode="") {
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
            $this->nameCode = $nameCode;
            $this->quizzes = array();
            $this->timelines = array();
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
         * Enregistre ou modifie un Theme en base de données
         * save = create | update
         * @return bool
         */
        public function save(): bool {
            try {
                if ($this->id > 0) {
                    $db = new Database();
                    $pdo= $db->connect();
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                    $query = $pdo->prepare("CALL theme_update(:name, :description, :name_code, :id)");

                    $query->bindParam(":name", $this->name, PDO::PARAM_STR);
                    $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                    $query->bindParam(":name_code", $this->nameCode, PDO::PARAM_STR);
                    $query->bindParam(":id", $this->id, PDO::PARAM_INT);
    
                    return $query->execute();
                } else {
                    $db = new Database();
                    $pdo= $db->connect();
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                    $query = $pdo->prepare("CALL theme_save(:name, :description, :name_code)");

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
         * Modifie les données d'un Theme enregistré en base de données
         * @return bool
         */
        public function edit(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL theme_update(:name, :description, :name_code, :id)");

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
         * Supprime un Theme de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL theme_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
    
                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve tous les Themes enregistrés en base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL theme_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Theme");
    
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
         * Retrouve un Theme par son id depuis la base de données
         * @param int $id Représente l'identifiant unique du thème
         * @return Theme Le thème auquel l'id correspond
         */
        public function findById(int $id): ?Theme {
            try {
                $db = new Database();
                $pdo = $db-> connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL theme_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Theme");

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
         * Retrouve les Themes par leur nom depuis la base de données
         * @param string $name Représente le nom du thème
         * @return array
         */
        public function findByName(string $name): ?array {
            try {
                $db = new Database();
                $pdo = $db-> connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $name = "%" . $name . "%";

                $query = $pdo->prepare("CALL theme_select_name(:name)");

                $query->bindParam(":name", $name, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Theme");
    
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
         * Liste tous les Quiz liés à leur thème
         */
        public function loadAllQuizzes() {
            $this->quizzes = Quiz::findByTheme($this->id);
        }

        /**
         * Liste toutes les Timelines liées à leur thème
         */
        public function loadAllTimelines() {
            $this->timelines = Timeline::findByTheme($this->id);
        }
    }