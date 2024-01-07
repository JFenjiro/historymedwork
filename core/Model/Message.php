<?php
    namespace core\Model;

    use \PDO;
    use \PDOException;
    use \DateTime;

    class Message {
        private int $id;
        private string $object;
        private string $message;
        private ?DateTime $creationDate;
        private int $sender;
        private ?int $recipient;

        // Constructeur
        public function __construct(int $id=0, string $object="", string $message="", int $sender=0, int $recipient=0) {
            $this->id = $id;
            $this->object = $object;
            $this->message = $message;
            $this->creationDate = new Datetime();
            $this->sender = $sender;
            $this->recipient = $recipient;
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
         * Enregistrer ou modifier un Message en base de données
         * save = create | update
         * @return bool
         */
        public function save(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($this->id > 0) {
                    $query = $pdo->prepare("CALL message_update(:object, :message, :sender, :id)");

                    $query->bindParam(":object", $this->object, PDO::PARAM_STR);
                    $query->bindParam(":message", $this->message, PDO::PARAM_STR);
                    $query->bindParam(":sender", $this->sender, PDO::PARAM_INT);
                    $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                    return $query->execute();
                } else {
                    $query = $pdo->prepare("CALL message_save(:object, :message, :sender)");

                    $query->bindParam(":object", $this->object, PDO::PARAM_STR);
                    $query->bindParam(":message", $this->message, PDO::PARAM_STR);
                    $query->bindParam(":sender", $this->sender, PDO::PARAM_INT);

                    return $query->execute();
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Modifie les données d'un Message enregistré en base de données
         * @return bool
         */
        public function edit(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL message_update(:object, :message, :sender, :id)");

                $query->bindParam(":object", $this->object, PDO::PARAM_STR);
                $query->bindParam(":message", $this->message, PDO::PARAM_STR);
                $query->bindParam(":sender", $this->sender, PDO::PARAM_INT);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Supprime un Message de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL message_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve tous les Messages enregistrés en base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL message_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Message");

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
         * Retrouve un Message par son id depuis la base de données
         * @param int $id Représente l'identifiant unique du message
         * @return Message Le message auquel l'id correspond
         */
        public static function findById(int $id): ?Message {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL message_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Message");

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
         * Retrouve tous les Messages envoyés par leur expéditeur 
         * depuis la base de données
         * @param int $sender
         * @return array
         */
        public static function findBySender(int $sender): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL message_select_sender(:sender)");

                $query->bindParam(":sender", $sender, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\Message");

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