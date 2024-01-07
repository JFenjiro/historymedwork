<?php
    namespace core\Model;

    use \PDO;
    use \PDOException;
    use core\Model\Database;
    
    class Question {
        private int $id;
        private int $number;
        private string $title;
        private int $quiz;
        private ?int $media;
        private ?array $answers;

        // Constructeur
        public function __construct(int $id=0, int $number=0, string $title="", int $quiz=0, int $media=0) {
            $this->id = $id;
            $this->number = $number;
            $this->title = $title;
            $this->quiz = $quiz;
            $this->media = $media;
            $this->answers = array();
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
         * Enregistre ou modifie une Question en base de données
         * save = create | update
         * @return bool
         */
        public function save(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($this->id > 0) {
                    $query = $pdo->prepare("CALL question_update(:number, :title, :quiz, :media, :id)");

                    $query->bindParam(":number", $this->number, PDO::PARAM_INT);
                    $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                    $query->bindParam(":quiz", $this->quiz, PDO::PARAM_INT);
                    $query->bindParam(":media", $this->media, PDO::PARAM_INT);
                    $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                    return $query->execute();
                } else {
                    $query = $pdo->prepare("CALL question_save(:number, :title, :quiz, :media)");

                    $query->bindParam(":number", $this->number, PDO::PARAM_INT);
                    $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                    $query->bindParam(":quiz", $this->quiz, PDO::PARAM_INT);
                    $query->bindParam(":media", $this->media, PDO::PARAM_INT);

                    return $query->execute();
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Modifie les données d'une Question enregistrée en base de données
         * @return bool
         */
        public function edit(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL question_update(:number, :title, :quiz, :media, :id)");

                $query->bindParam(":number", $this->number, PDO::PARAM_INT);
                $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                $query->bindParam(":quiz", $this->quiz, PDO::PARAM_INT);
                $query->bindParam(":media", $this->media, PDO::PARAM_INT);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Supprime une Question de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL question_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve toutes les Questions enregistrées en base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL question_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Question");

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
         * Retrouve une Question par son id depuis la base de données
         * @param int $id Représente l'identifiant unique de la question
         * @return Question La question à laquelle l'id correspond
         */
        public static function findById(int $id): ?Question {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL question_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Question");

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
         * Retrouve les Questions par leur titre depuis la base de données
         * @param string $title Représente le titre de la question
         * @return array
         */
        public function findByTitle(string $title): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $title = "%" . $title . "%";

                $query = $pdo->prepare("CALL question_select_title(:title)");

                $query->bindParam(":title", $title, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Question");

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
         * Retrouve chaque Question par son quiz depuis la base de données
         * @param int $quiz
         * @return array
         */
        public static function findByQuiz(int $quiz): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL question_select_quiz(:quiz)");

                $query->bindParam(":quiz", $quiz, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Question");

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
         * Retrouve une Question par son quiz et son ordre depuis la base de données
         * @param int $quiz
         * @param int $order
         * @return Question
         */
        public static function findOneByQuizAndOrder(int $quiz, int $order): ?Question {
            try {
                $order --;

                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL question_select_quiz_order(:quiz, :start)");

                $query->bindParam(":quiz", $quiz, PDO::PARAM_INT);
                $query->bindParam(":start", $order, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Question");

                if ($query->execute()) {
                    $results = $query->fetch();
                    return $results;
                }
                return new Question();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return null;
            }
        }

        /**
         * Retrouve chaque Question par son média depuis la base de données
         * @param int $media
         * @return array
         */
        public static function findByMedia(int $media): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL question_select_media(:media)");

                $query->bindParam(":media", $media, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Question");

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
         * Liste toutes les Answers liées à une question
         */
        public function loadAllAnswers() {
            $this->answers = Answer::findByQuestion($this->id);
        }
    }