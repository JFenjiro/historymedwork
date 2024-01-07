<?php
    namespace core\Model;

    use \PDO;
    use \PDOException;
    use \DateTime;

    class Map {
        private int $id;
        private string $title;
        private ?string $description;
        private ?DateTime $creationDate;
        private int $creator;
        private int $region;
        private ?int $image;

        // Concepteur
        public function __construct(int $id=0, string $title="", string $description="", int $creator=0, int $region=0, int $image=0) {
            $this->id = $id;
            $this->title = $title;
            $this->description = $description;
            $this->creationDate = new DateTime();
            $this->creator = $creator;
            $this->region = $region;
            $this->image = $image;
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
                case "creationDate":
                case "creation_date":
                    $this->creationDate = new DateTime($value);
                    break;
                default:
                    $this->$attribute = $value;
            }
        }

        // CRUD : persistance de l'objet en base de données

        /**
         * Enregistre ou modifie une Map en base de données
         * save = create | update
         * @return bool
         */
        public function save(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($this->id > 0) {
                    $query = $pdo->prepare("CALL map_update(:title, :description, :creator, :region, :image, :id)");

                    $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                    $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                    $query->bindParam(":creator", $this->creator, PDO::PARAM_INT);
                    $query->bindParam(":region", $this->region, PDO::PARAM_INT);
                    $query->bindParam(":image", $this->image, PDO::PARAM_INT);
                    $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                    return $query->execute();
                } else {
                    $query = $pdo->prepare("CALL map_save(:title, :description, :creator, :region, :image)");

                    $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                    $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                    $query->bindParam(":creator", $this->creator, PDO::PARAM_INT);
                    $query->bindParam(":region", $this->region, PDO::PARAM_INT);
                    $query->bindParam(":image", $this->image, PDO::PARAM_INT);

                    return $query->execute();
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Modifie les données d'une Map enregistrée en base de données
         * @return bool
         */
        public function edit(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL map_update(:title, :description, :creator, :region, :image, :id)");

                $query->bindParam(":title", $this->title, PDO::PARAM_STR);
                $query->bindParam(":description", $this->description, PDO::PARAM_STR);
                $query->bindParam(":creator", $this->creator, PDO::PARAM_INT);
                $query->bindParam(":region", $this->region, PDO::PARAM_INT);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Supprime une Map de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL map_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve toutes les Maps enregistrées en base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL map_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Map");

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
         * Retrouve une Map par son id depuis la base de données
         * @param int $id Représente l'identifiant unique de la carte
         * @return Map La carte à laquelle l'id correspond
         */
        public static function findById(int $id): ?Map {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL map_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Map");

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
         * Retrouve les Maps par leur titre depuis la base de données
         * @param string $title Représente le titre de la carte
         * @return array
         */
        public static function findByTitle(string $title): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $title = "%" . $title . "%";

                $query = $pdo->prepare("CALL map_select_title(:title)");

                $query->bindParam(":title", $title, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Map");

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
         * Retrouve chaque Map par sa région depuis la base de données
         * @param int $region
         * @return array
         */
        public static function findByRegion(int $region): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL map_select_region(:region)");

                $query->bindParam(":region", $region, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Map");

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
         * Retrouve chaque Map par son créateur depuis la base de données
         * @param int $creator
         * @return array
         */
        public static function findByCreator(int $creator): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL map_select_creator(:creator)");

                $query->bindParam(":creator", $creator, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Map");

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
         * Retrouve chaque Map par son image depuis la base de données
         * @param int $image
         * @return array
         */
        public static function findByImage(int $image): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL map_select_image(:image)");

                $query->bindParam(":image", $image, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Map");

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
    }
