<?php
    namespace core\Model;

    use \PDO;
    use \PDOException;
    use \DateTime;
    use core\Model\Database;

    class Quiz {
        private int $id;
        private string $title;
        private ?string $description;
        private int $difficultyLevel;
        private ?DateTime $creationDate;
        private int $theme;
        private ?array $questions;
        private ?array $scores;

        // Constructeur
        public function __construct(int $id=0, string $title="", string $description="", int $difficultyLevel=0, int $theme=0) {
            $this->id = $id;
            $this->title = $title;
            $this->description = $description;
            $this->difficultyLevel = $difficultyLevel;
            $this->creationDate = new DateTime();
            $this->theme = $theme;
            $this->questions = array();
            $this->scores = array();
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
                case "difficulty_level":
                case "difficultyLevel":
                    $this->difficultyLevel = $value;
                    break;
                case "creation_date":
                case "creationDate":
                    $this->creationDate = new DateTime($value);
                    break;
                default:
                    $this->$attribute = $value;
            }
        }

        // CRUD : persistance de l'objet en base de données

        /**
         * Enregistre ou modifier un Quiz en base de données
         * save = create | update
         * @return bool
         */
        public function save(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($this->id > 0) {
                    $query = $pdo->prepare("CALL quiz_update(:title, :description, :difficulty_level, :theme, :id)");

                    $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                    $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                    $query->bindParam(":difficulty_level", $this->difficultyLevel, PDO::PARAM_INT);
                    $query->bindParam(":theme", $this->theme, PDO::PARAM_INT);
                    $query->bindParam(":id", $this->id, PDO::PARAM_INT);
    
                    return $query->execute();
                } else {
                    $query = $pdo->prepare("CALL quiz_save(:title, :description, :difficulty_level, :theme)");

                    $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                    $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                    $query->bindParam(":difficulty_level", $this->difficultyLevel, PDO::PARAM_INT);
                    $query->bindParam(":theme", $this->theme, PDO::PARAM_INT);
    
                    return $query->execute();
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Modifie les données d'un Quiz enregistré en base de données
         * @return bool
         */
        public function edit(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL quiz_update(:title, :description, :difficulty_level, :theme, :id");

                $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                $query->bindParam(":difficulty_level", $this->difficultyLevel, PDO::PARAM_STR);
                $query->bindParam(":theme", $this->theme, PDO::PARAM_INT);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            } 
        }

        /**
         * Supprime un Quiz de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL quiz_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                
                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve tous les Quiz enregistrés en base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL quiz_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Quiz");
    
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
         * Retrouve un Quiz par son id depuis la base de données
         * @param int $id Représente l'identifiant unique du quiz
         * @return Quiz Le quiz auquel l'id correspond
         */
        public static function findById(int $id): ?Quiz {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
                $query = $pdo->prepare("CALL quiz_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Quiz");
    
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
         * Retrouve les Quiz parleur titre depuis la base de données
         * @param string $title Représente le titre du quiz
         * @return array
         */
        public static function findByTitle(string $title): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $title = "%" . $title . "%";
    
                $query = $pdo->prepare("CALL quiz_select_title(:title)");

                $query->bindParam(":title", $title, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Quiz");
    
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
         * Retrouve chaque Quiz par son thème depuis la base de données
         * @param int $theme
         * @return array
         */
        public static function findByTheme(int $theme): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL quiz_select_theme(:theme)");

                $query->bindParam(":theme", $theme, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Quiz");
    
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
         * Liste toutes les Questions par leur quiz
         */
        public function loadAllQuestions() {
            $this->questions = Question::findByQuiz($this->id);
        }

        /**
         * Liste toutes les Scores par leur quiz
         */
        public function loadAllScores() {
            $this->scores = Score::findByQuiz($this->id);
        }
    }