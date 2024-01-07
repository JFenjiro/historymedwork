<?php
    namespace core\Model;

    use \PDO;
    use \PDOException;
    use \DateTime;
    
    class Timeline {
        private int $id;
        private string $title;
        private ?string $description;
        private ?DateTime $creationDate;
        private int $creator;
        private int $theme;
        private ?array $medias;

        // Constructeur
        public function __construct(int $id=0, string $title="", string $description="", int $creator=0, int $theme=0) {
            $this->id = $id;
            $this->title = $title;
            $this->description = $description;
            $this->creationDate = new DateTime();
            $this->creator = $creator;
            $this->theme = $theme;
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
         * Enregistre ou modifie une Timeline en base de données
         * save = create | update
         * @return bool
         */
        public function save(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($this->id > 0) {
                    $query = $pdo->prepare("CALL timeline_update(:title, :description, :user, :theme, :id)");

                    $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                    $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                    $query->bindParam(":user", $this->user, PDO::PARAM_INT);
                    $query->bindParam(":theme", $this->theme, PDO::PARAM_INT);
                    $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                    return $query->execute();
                } else {
                    $query = $pdo->prepare("CALL timeline_save(:title, :description, :user, :theme)");

                    $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                    $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                    $query->bindParam(":user", $this->user, PDO::PARAM_INT);
                    $query->bindParam(":theme", $this->theme, PDO::PARAM_INT);

                    return $query->execute();
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Modifie les données d'une Timeline enregistrée en base de données
         * @return bool
         */
        public function edit(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL timeline_update(:title, :description, :user, :theme, :id)");

                $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                $query->bindParam(":user", $this->user, PDO::PARAM_INT);
                $query->bindParam(":theme", $this->theme, PDO::PARAM_INT);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Supprime une Timeline de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL timeline_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve toutes les Timeline de la base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL timeline_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Timeline");
                
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
         * Retrouve une Timeline par son id depuis la base de données
         * @param int $id Représente l'identifiant unique de la frise
         * @return Timeline La frise à laquelle l'id correspond
         */
        public static function findById(int $id): ?Timeline {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL timeline_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Timeline");
                
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
         * Retrouve les Timelines par leur titre depuis la base de données
         * @param string $title Représente le titre de la frise
         * @return array
         */
        public static function findByTitle(string $title): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $title = "%" . $title . "%";

                $query = $pdo->prepare("CALL timeline_select_title(:title)");

                $query->bindParam(":title", $title, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Timeline");
                
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
         * Retrouve chaque Timeline par son thème depuis la base de données
         * @param int $theme
         * @return array
         */
        public static function findByTheme(int $theme): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL timeline_select_theme(:theme)");

                $query->bindParam(":theme", $theme, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Timeline");
    
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
         * Retrouve chaque Timeline par son créateur depuis la base de données
         * @param int $creator
         * @return array
         */
        public static function findByCreator(int $creator): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL timeline_select_creator(:creator)");

                $query->bindParam(":creator", $creator, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Timeline");

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
         * Liste tous les médias liés à leur frise chronologique
         */
        public function loadAllMedias() {
            // $this->medias = Media::findByTimeline($this->id);
        }
    }