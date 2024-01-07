<?php
    namespace core\Model;

    use \PDO;
    use \PDOException;
    use core\Model\Database;
    
    class Answer {
        private int $id;
        private string $name;
        private string $type;
        private string $nameCode;
        private bool $isValid;
        private ?string $description;
        private int $question;

        // Constructeur
        public function __construct(int $id=0, string $name="", string $type="", string $nameCode="", bool $isValid=false, string $description="", int $question=0) {
            $this->id = $id;
            $this->name = $name;
            $this->type = $type;
            $this->nameCode = $nameCode;
            $this->isValid = $isValid;
            $this->description = $description;
            $this->question = $question;
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
                case "is_valid":
                case "isValid":
                    $this->isValid = $value;
                    break;
                default:
                    $this->$attribute = $value;
            }
        }
    
        // CRUD : persistance de l'objet en base de données
        /**
         * Enregistre ou modifie une Answer en base de données
         * save = create | update
         * @return bool
         */
        public function save(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($this->id > 0) {
                    $query = $pdo->prepare("CALL answer_update(:name, :type, :name_code, :is_valid, :description, :question, :id)");

                    $query->bindParam(":name", $this->name, PDO::PARAM_STR);
                    $query->bindParam(":type", $this->type, PDO::PARAM_STR);
                    $query->bindParam(":name_code", $this->nameCode, PDO::PARAM_STR);
                    $query->bindParam(":is_valid", $this->isValid, PDO::PARAM_BOOL);
                    $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                    $query->bindParam(":question", $this->question, PDO::PARAM_INT);
                    $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                    return $query->execute();
                } else {
                    $query = $pdo->prepare("CALL answer_save(:name, :type, :name_code, :is_valid, :description, :question)");

                    $query->bindParam(":name", $this->name, PDO::PARAM_STR);
                    $query->bindParam(":type", $this->type, PDO::PARAM_STR);
                    $query->bindParam(":name_code", $this->nameCode, PDO::PARAM_STR);
                    $query->bindParam(":is_valid", $this->isValid, PDO::PARAM_BOOL);
                    $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                    $query->bindParam(":question", $this->question, PDO::PARAM_INT);

                    return $query->execute();
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Modifie les données d'une Answer enregistrée en base de données
         * @return bool
         */
        public function edit(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL answer_update(:name, :type, :name_code, :is_valid, :description, :question, :id)");

                $query->bindParam(":name", $this->name, PDO::PARAM_STR);
                $query->bindParam(":type", $this->type, PDO::PARAM_STR);
                $query->bindParam(":name_code", $this->nameCode, PDO::PARAM_STR);
                $query->bindParam(":is_valid", $this->isValid, PDO::PARAM_BOOL);
                $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                $query->bindParam(":question", $this->question, PDO::PARAM_INT);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Supprime une Answer de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL answer_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve toutes les Answers enregistrées en base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL answer_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Answer");

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
         * Retrouve une Answer par son id depuis la base de données
         * @param int $id Représente l'identifiant unique de la réponse
         * @return Answer La réponse à laquelle l'id correspond
         */
        public static function findById(int $id): ?Answer {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL answer_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Answer");

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
         * Retrouve les Answers par leur nom depuis la base de données
         * @param string $name Représente le nom de la réponse
         * @return array
         */
        public static function findByName(string $name): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $name = "%" . $name . "%";

                $query = $pdo->prepare("CALL answer_select_name(:name)");

                $query->bindParam(":name", $name, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Answer");

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
         * Retrouve chaque Answer par sa question depuis la base de données
         * @param int $question
         * @return array
         */
        public static function findByQuestion(int $question): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL answer_select_question(:question)");

                $query->bindParam(":question", $question, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Answer");

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
         * Retrouve chaque Answer par son type depuis la base de données
         * @param string $nameCode
         * @return array
         */
        public static function findByNameCode(string $nameCode): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL answer_select_name_code(:name_code)");

                $query->bindParam(":name_code", $nameCode, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Answer");

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
         * Calcule le nombre de réponses justes
         * @param bool $isValid
         * @return int
         */
        public static function countByValid(bool $isValid): int {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL answer_count_valid(:is_valid)");

                $query->bindParam(":is_valid", $isValid, PDO::PARAM_BOOL);

                if ($query->execute()) {
                    $result = $query->fetch();
                    return $result["count"];
                } else return -1;
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return -1;
            }
        }

        /**
         * Retrouve une Answer par sa question et son ordre depuis la base de données
         * @param int $question
         * @param int $order
         * @return Answer
         */
        public static function findOneByQuestionAndOrder(int $question, int $order): ?Answer {
            try {
                $order --;

                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL answer_select_question_order(:question, :start)");

                $query->bindParam(":question", $question, PDO::PARAM_INT);
                $query->bindParam(":start", $order, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Answer");

                if ($query->execute()) {
                    $result = $query->fetch();
                    return $result;
                }
                return new Answer();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return null;
            }
        }
    }