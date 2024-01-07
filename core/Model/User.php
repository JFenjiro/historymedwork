<?php
    namespace core\Model;

    use \DateTime;
    use \PDO;
    use \PDOException;

    class User {
        private int $id;
        private string $pseudo;
        private string $email;
        private string $hashPass;
        private ?DateTime $creationDate;
        private bool $isAdmin;
        private ?int $icon;
        private ?string $resetToken;
        private ?DateTime $resetTokenExpires;
        private ?array $messages;
        private ?array $scores;
        private ?array $timelines;
        private ?array $maps;
        private ?array $medias;

        // Constructeur
        public function __construct($id=0, $pseudo="", $email="", $hashPass="", $isAdmin=false, $icon=0) {
            $this->id = $id;
            $this->pseudo = $pseudo;
            $this->email = $email;
            $this->hashPass = $hashPass;
            $this->creationDate = new DateTime();
            $this->isAdmin = $isAdmin;
            $this->icon = $icon;
            $this->resetToken = "";
            $this->resetTokenExpires = new DateTime();
            $this->messages = array();
            $this->scores = array();
            $this->timelines = array();
            $this->maps = array();
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
                case "hashPass":
                case "hash_pass":
                    $this->hashPass = $value;
                    break;
                case "creationDate":
                case "creation_date":
                    $this->creationDate = new DateTime($value);
                    break;
                case "isAdmin":
                case "is_admin":
                    $this->isAdmin = $value;
                    break;
                case "resetToken":
                case "reset_token":
                    $this->resetToken = $value;
                    break;
                case "resetTokenExpires":
                case "reset_token_expires":
                    $this->resetTokenExpires = new DateTime($value);
                    break;
                default:
                    $this->$attribute = $value;
            }
        }

        // CRUD : persistance de l'objet en base de données
        /**
         * Inscrit ou modifie un User en base de données 
         * save = create | update
         * @return bool
         */ 
        public function save(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                if ($this->id > 0) {
                    $query = $pdo->prepare("CALL user_update(:pseudo, :email, :hash_pass, :is_admin, :id)");

                    $query->bindParam(":pseudo", $this->pseudo, PDO::PARAM_STR);
                    $query->bindParam(":email", $this->email, PDO::PARAM_STR);
                    $query->bindParam(":hash_pass", $this->hashPass, PDO::PARAM_STR);
                    $query->bindParam(":is_admin", $this->isAdmin, PDO::PARAM_BOOL);
                    $query->bindParam(":id", $this->id, PDO::PARAM_INT);
    
                    return $query->execute();
                } else {
                    $query = $pdo->prepare("CALL user_save(:pseudo, :email, :hash_pass, :is_admin)");

                    $query->bindParam(":pseudo", $this->pseudo, PDO::PARAM_STR);
                    $query->bindParam(":email", $this->email, PDO::PARAM_STR);
                    $query->bindParam(":hash_pass", $this->hashPass, PDO::PARAM_STR);
                    $query->bindParam(":is_admin", $this->isAdmin, PDO::PARAM_BOOL);
    
                    return $query->execute();
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Modifie le pseudo d'un User enregistré en base de données
         * @return bool
         */
        public function editPseudo(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL user_update_pseudo(:pseudo, :id)");

                $query->bindParam(":pseudo", $this->pseudo, PDO::PARAM_STR);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Modifie le mot de passe d'un User enregistré en base de données
         * @return bool
         */
        public function editHashPass(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL user_update_hash(:hash_pass, :id)");

                $query->bindParam(":hash_pass", $this->hashPass, PDO::PARAM_STR);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Supprime un profil User de la base de données
         * @param int $id
         * @return bool
         */
        public function delete(int $id): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL user_delete(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
    
                return $query->execute();
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve tous les Users enregistrés en base de données
         * @return array
         */
        public static function findAll(): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL user_select_all()");

                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\User");
    
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
         * Retrouve un User par son id depuis la base de données
         * @param int $id Représente l'identifiant unique de l'Utilisateur
         * @return User L'utilisateur auquel l'id correspond
         */
        public static function findById(int $id): ?User {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL user_select_id(:id)");

                $query->bindParam(":id", $id, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\User");
    
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
         * Retrouve un User par son pseudo : 
         * Méthode de connexion via l'application
         * @param string $pseudo Représente le pseudo de l'utilisateur
         * @return array retrouve l'utilisateur attendu
         */
        public function findByPseudo(string $pseudo): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL user_select_login(:pseudo)");

                $query->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    
                if ($query->execute()) {
                    $results = $query->fetchAll();

                    if (count($results) == 1) {
                        if (password_verify($_POST["password"], $results[0]["hash_pass"])) {
                            session_name("hmw-php");
                            session_start();
                            
                            $logUser = new User(
                                $results[0]["id"], 
                                $results[0]["pseudo"], 
                                $results[0]["email"], 
                                $results[0]["hash_pass"], 
                                $results[0]["is_admin"], 
                                $results[0]["icon"]
                            );

                            $_SESSION["userid"] = $logUser->id;
                            $_SESSION["pseudo"] = $logUser->pseudo;
                            $_SESSION["email"] = $logUser->email;
                            $_SESSION["hash"] = $logUser->hashPass;
                            $_SESSION["admin"] = $logUser->isAdmin;
                            $_SESSION["usericon"] = $logUser->icon; 
                            $_SESSION["user"] = serialize($logUser);
                            $_SESSION["auth"] = true;
    
                            header("Location: ./workspace.php");
                            exit();
                        } else {
                            echo "<script>alert('Les informations saisies sont erronées.')</script>";
                        }
                    } else {
                        echo "<script>alert('L'utilisateur n'existe pas.')</script>";
                    }
                }
                return null;
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return null;
            }
        }

        /**
         * Retrouve un User par son email depuis la base de données
         * @param string $email Représente l'email de l'utilisateur
         * @return User retrouve l'utilisateur attendu
         */
        public static function findByEmail(string $email): ?User {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $query = $pdo->prepare("CALL user_select_email(:email)");

                $query->bindParam(":email", $email, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\User");
    
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
         * Retrouve chaque User par son icône depuis la base de données
         * @param int $icon
         * @return array
         */
        public static function findByIcon(int $icon): ?array {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
                $query = $pdo->prepare("CALL user_select_icon(:icon)");

                $query->bindParam(":icon", $icon, PDO::PARAM_INT);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\User");
    
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
         * Modifie l'icône d'un User de la base de données
         * @return bool
         */
        public function editIcon(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $pdo->prepare("CALL user_update_icon(:icon, :id)");

                $query->bindParam(":icon", $this->icon, PDO::PARAM_INT);
                $query->bindParam(":id", $this->id, PDO::PARAM_INT);

                return $query->execute();
            } catch (PDOException $e) {
                echo 'Error : ' . $e->getMessage();
                return false;
            }
        }

        /**
         * Liste tous les Messages liés à un expéditeur
         */
        public function loadAllMessages() {
            $this->messages = Message::findBySender($this->id);
        }

        /**
         * Liste tous les Score liés à un utilisateur
         */
        public function loadAllScores() {
            $this->scores = Score::findByUser($this->id);
        }

        /**
         * Liste toutes les Timelines liées à un utilisateur
         */
        public function loadAllTimelines() {
            $this->timelines = Timeline::findByCreator($this->id);
        }

        /**
         * Liste toutes les Maps liées à un utilisateur
         */
        public function loadAllMaps() {
            $this->maps = Map::findByCreator($this->id);
        }

        /**
         * Liste tous les Medias liés à un utilisateur
         */
        public function loadAllMedias() {
            $this->medias = Media::findByRedactor($this->id);
        }

        /**
         * Sauvegarde le token d'un User de la base de données
         * Modifie un champs utilisateur de la base de données
         * @return bool enregistre un token en base de données
         */ 
        public function saveToken(): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                date_default_timezone_set("Europe/Paris");

                $sql = "UPDATE hmw_users SET reset_token = :reset_token, reset_token_expires = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 2 HOUR) WHERE email = :email;";

                $query = $pdo->prepare($sql);
                $query->bindParam(":reset_token", $this->resetToken, PDO::PARAM_STR);
                $query->bindParam(":email", $this->email, PDO::PARAM_STR);

                if ($query->execute()) {
                    if ($query->rowCount() == 1) {
                        return true;
                    } else return false;
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Vérifie l'existence d'un token chez un utilisateur
         * enregistré en base de données
         */
        public static function tokenExists(string $resetToken, DateTime $resetTokenExpires): bool {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                date_default_timezone_set("Europe/Paris");

                $sql = "SELECT * FROM hmw_users WHERE hmw_users.reset_token = :reset_token AND hmw_users.reset_token_expires <= :reset_token_expires;";
                $query = $pdo->prepare($sql);

                $query->bindParam(":reset_token", $resetToken, PDO::PARAM_STR);
                $query->bindValue(":reset_token_expires", $resetTokenExpires->format("Y-m-d H:i:s"), PDO::PARAM_STR);
                
                if ($query->execute()) {
                    if ($query->rowCount() == 1) {
                        return true;
                    } else return false;
                }
            } catch (PDOException $e) {
                echo "Error : " . $e->getMessage();
                return false;
            }
        }

        /**
         * Retrouve un User par son token
         * @param string $resetToken Représente le token de l'utilisateur
         * @return User retrouve l'utilisateur attendu
         */
        public static function findByResetToken(string $resetToken): ?User {
            try {
                $db = new Database();
                $pdo = $db->connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "SELECT * FROM hmw_users WHERE hmw_users.reset_token = :reset_token;";
                $query = $pdo->prepare($sql);

                $query->bindParam(":reset_token", $resetToken, PDO::PARAM_STR);
                $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "core\Model\User");
                
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
    }


        