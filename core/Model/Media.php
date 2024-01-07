<?php
    namespace core\Model;

    use \PDO;
    use \PDOException;

    class Media {
        private int $id;
        private string $title;
        private ?string $textContent;
        private string $mediaUrl;
        private int $type;
        private ?int $redactor;
        private ?array $users;
        private ?array $questions;
        private ?array $maps;
        private ?array $timelines;

        // Constructeur
        public function __construct(int $id = 0, string $title = "", string $textContent = "", string $mediaUrl = "", int $type=0, int $redactor=0) {
            $this->id = $id;
            $this->title = $title;
            $this->textContent = $textContent;
            $this->mediaUrl = $mediaUrl;
            $this->type = $type;
            $this->redactor = $redactor;
            $this->users = array();
            $this->questions = array();
            $this->maps = array();
            $this->timelines = array();
        }

        // Accesseur magique
        public function __get($attribute) {
            return $this->$attribute;
        }
        
        public function __set($attribute,$value) {
            switch ($attribute) {
                case "id":
                    if ($value > 0) {
                        $this->id = $value;
                    } else {
                        $this->id = 0;
                    }
                    break;
                case "textContent":
                case "text_content":
                    $this->textContent = $value;
                    break;
                case "mediaUrl":
                case "media_url":
                    $this->mediaUrl = $value;
                    break;
                default:
                    $this->$attribute = $value;
            } 
        }

        // CRUD : persistance de l'objet en base de données
        /**
         * Enregistrer ou modifier un Media en base de données
         * save = create | update
         * @return bool
         */
        public function save(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($this->id > 0) {
                    $query = $pdo->prepare("CALL media_update(:title, :text_content, :media_url, :type, :id)");

                    $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                    $query->bindParam(":media_type", $this->mediaType, PDO::PARAM_STR);
                    $query->bindParam(":text_content", $this->textContent, PDO::PARAM_STR);
                    $query->bindParam(":media_url", $this->mediaUrl, PDO::PARAM_STR);
                    $query->bindParam(":type", $this->type, PDO::PARAM_INT);
                    $query->bindParam(":id", $this->id, PDO::PARAM_INT);
    
                    return $query->execute();
                } else {
                    $query = $pdo->prepare("CALL media_save(:title, :text_content, :media_url, :type)");

                    $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                    $query->bindParam(":text_content", $this->textContent, PDO::PARAM_STR);
                    $query->bindParam(":media_url", $this->mediaUrl, PDO::PARAM_STR);
                    $query->bindParam(":type", $this->type, PDO::PARAM_INT);
    
                    return $query->execute();
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Modifie les données d'un Media enregistré en base de données
         * @return bool
         */
        public function edit(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL media_update(:title, :text_content, :media_url, :type, :id)");

                $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                $query->bindParam(":text_content", $this->textContent, PDO::PARAM_STR);
                $query->bindParam(":media_url", $this->mediaUrl, PDO::PARAM_STR);
                $query->bindParam(":type", $this->type, PDO::PARAM_INT);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);
    
                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Supprime un Media de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL media_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
    
                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve tous les Medias de la base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL media_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Media");
    
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
         * Retrouve un Media par son id depuis la base de données
         * @param int $id Représente l'identifiant unique du média
         * @return Media Le média auquel l'id correspond
         */
        public static function findById(int $id): ?Media {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL media_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Media");
    
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
         * Retrouve un Media par son titre depuis la base de données
         * @param string $title Représente le titre du média
         * @return array
         */
        public static function findByTitle(string $title): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $title = "%" . $title . "%";
 
                $query = $pdo->prepare("CALL media_select_title(:title)");

                $query->bindParam(":title", $title, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Media");
    
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
         * Retrouve les Medias par leur type depuis la base de données
         * @param int $type
         * @return array
         */
        public static function findByType(int $type): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL media_select_type(:type)");

                $query->bindParam(":type", $type, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Media");
    
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
         * Retrouve les Medias par leur url depuis la base de données
         * @param string $mediaUrl Représente l'url du média
         * @return array
         */
        public static function findByUrl(string $mediaUrl): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL media_select_url(:media_url)");

                $query->bindParam(":media_url", $mediaUrl, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Media");
    
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
         * Retrouve un Media par son contenu texte depuis la base de données
         * @param string $textContent Représente le contenu texte du média
         * @return array
         */
        public static function findByTextContent(string $textContent): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL media_select_content(:text_content)");

                $query->bindParam(":text_content", $textContent, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Media");
    
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
         * Retrouve un Media par son rédacteur texte depuis la base de données
         * @param int $redactor Représente le rédacteur du média
         * @return array
         */
        public static function findByRedactor(int $redactor): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL media_select_redactor(:redactor)");

                $query->bindParam(":redactor", $redactor, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Media");
    
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
         * Liste tous les Users liés à une icône
         */
        public function loadAllUsers() {
            $this->users = User::findByIcon($this->id);
        }

        /**
         * Liste toutes les Questions liées à une icône
         */
        public function loadAllQuestions() {
            $this->questions = Question::findByMedia($this->id);
        }

        /**
         * Liste toutes les Maps liées à une icône
         */
        public function loadAllMaps() {
            $this->maps = Map::findByImage($this->id);
        }

        /**
         * Liste toutes les Timelines liées à une icône
         */
        public function loadAllTimelines() {
            // $this->timelines = Timeline::findByMedia($this->id);
        }
    }