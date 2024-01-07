<?php
    namespace core\Model;

    use \PDO;
    use \PDOException;
    use \DateTime;
    use core\Model\Database;
    
    class Score {
        private int $id;
        private int $score;
        private ?DateTime $submissionDate;
        private int $user;
        private int $quiz;

        // Constructeur
        public function __construct (int $id=0, int $score=0, int $user=0, int $quiz=0) {
            $this->id = $id;
            $this->score = $score;
            $this->submissionDate = new DateTime();
            $this->user = $user;
            $this->quiz = $quiz;
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
                case "submissionDate":
                case "submission_date":
                    $this->submissionDate = new DateTime($value);
                    break;
                default:
                    $this->$attribute = $value;
                    break;
            }
        }

        // CRUD : persistance de l'objet en base de données

        /**
         * Enregistre ou modifie un Score en base de données 
         * save = create | update
         * @return bool
         */
        public function save(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (Score::scoreExists($pdo, $this->user, $this->quiz)) {
                    $query = $pdo->prepare("CALL score_update(:score, :user, :quiz)");

                    $query->bindParam(":score", $this->score, PDO::PARAM_INT);
                    $query->bindParam(":user", $this->user, PDO::PARAM_INT);
                    $query->bindParam(":quiz", $this->quiz, PDO::PARAM_INT);

                    return ($query->execute());
                } else {
                    $query = $pdo->prepare("CALL score_save(:score, :user, :quiz)");

                    $query->bindParam(":score", $this->score, PDO::PARAM_INT);
                    $query->bindParam(":user", $this->user, PDO::PARAM_INT);
                    $query->bindParam(":quiz", $this->quiz, PDO::PARAM_INT);

                    return ($query->execute());
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Supprime un Score de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL score_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);

                return ($query->execute());
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve tous les Scores enregistrés en base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL score_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Score");

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
         * Retrouve un Score par son id depuis la base de données
         * @param int $id Représente l'identifiant unique du score
         * @return Score Le score auquel l'id correspond
         */
        public static function findById(int $id): ?Score {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL score_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Score");

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
         * Retrouve chaque Score par son utilisateur depuis la base de données
         * @param int $user
         * @return array
         */
        public static function findByUser(int $user): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL score_select_user(:user)");

                $query->bindParam(":user", $user, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Score");

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
         * Retrouve chaque Score par son quiz depuis la base de données
         * @param int $quiz
         * @return array
         */
        public static function findByQuiz(int $quiz): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL score_select_quiz(:quiz)");

                $query->bindParam(":quiz", $quiz, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Score");

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
         * Vérifie que le score existe déjà
         * @param PDO $pdo Récupère la connexion existante
         * @param int $user Récupère l'id de l'utilisateur
         * @param int $quiz Récupère le quiz du score
         * @return bool Renvoie un booléen
         */
        public static function scoreExists(PDO $pdo, int $user, int $quiz): bool {
            try {
                // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL score_exists(:user, :quiz)");

                $query->bindParam(":user", $user, PDO::PARAM_INT);
                $query->bindParam(":quiz", $quiz, PDO::PARAM_INT);
                
                if ($query->execute()) {
                    if ($query->rowCount() > 0) {
                        return true;
                    } else return false;
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }
    }