<?php 
    session_name("hmw-php");
    session_start();

    use core\Model\User;
    use core\Model\Media;
    use core\Model\Score;

    require __DIR__ . "\\Autoloader.php";
    Autoloader::register();
    
    if (!isset($_SESSION["auth"]) || $_SESSION["auth"] !== true) {
        header("Location: ./login.php");
        exit();
    }

    if (isset($_SESSION["user"])) {
        $user = unserialize($_SESSION["user"]);
        $alert_success = "Vous êtes connecté " . $_SESSION["pseudo"] . "";

        require_once __DIR__ . "\\modules\\variables.php";

        $members = User::findAll();
        $iconurls = Media::findAll(); 
        $icons = Media::findByType(4);
        $scores = Score::findAll();

        if (!empty($_POST)) {
            // Méthode pour changer d'icône
            if (isset($_POST["usericon"])) {
                require_once __DIR__ ."\\core\\Model\\User.php";

                $userIconChoice = User::findById($_POST["id"]);
                $userIconChoice->icon = $_POST["usericon"];

                if ($userIconChoice->editIcon()) {
                    echo "<script>alert('L'icône a été modifiée avec succès.')</scripVous>";
                    $_SESSION["usericon"] = $userIconChoice->icon;
                    
                    header("Location: ./workspace.php");
                    exit();
                }
            }

            // Méthode pour changer de pseudo
            if (isset($_POST["new-pseudo"])) {
                // require_once __DIR__ . "\\core\\Model\\User.php";
                require_once __DIR__ . "\\modules\\functions.php";

                $userPseudoChange = User::findById($_POST["id-pseudo-change"]);
                $userPseudoChange->pseudo = sanitize($_POST["new-pseudo"]);

                if ($userPseudoChange->editPseudo()) {
                    echo "<script>alert('Le pseudo a été changé avec succès.')</script>";
                    $_SESSION["pseudo"] = $userPseudoChange->pseudo;

                    header("Location: ./workspace.php");
                    exit();
                }
            }

            // Méthode pour changer de mot de passe
            if (isset($_POST["new-pwd"])) {
                if (strlen($_POST["new-pwd"]) > MINIMUM_PASS_LENGTH) {
                    if (isset($_POST["confirm-new-pwd"]) && $_POST["new-pwd"] == $_POST["confirm-new-pwd"]) {
                        require_once __DIR__ . "\\core\\Model\\User.php";
                
                        $options = ["cost" => 12];
                        $userPwdChange = User::findById($_POST["id-pwd-change"]);
                        $userPwdChange->hashPass = password_hash($_POST["new-pwd"], PASSWORD_DEFAULT, $options);

                        if ($userPwdChange->editHashPass()) {
                            echo "<script>alert('Le mot de passe a été changé avec succès.')</script>";

                            session_destroy();
                            $_SESSION = [];
                            header("Location: ./login.php");
                            exit();
                        }
                    } else {
                        echo "<script>alert('Les deux mots de passe saisis ne correspondent pas !')</script>";
                    }
                } else {
                    echo "<script>alert('Le mot de passe doit contenir au moins " . (MINIMUM_PASS_LENGTH + 1) . " caractères.')</script>";
                }
            }

            // Méthode pour supprimer son compte
            if (isset($_POST["email-confirmation"])) {
                if ($_POST["email-confirmation"] == $_SESSION["email"]) {
                    require_once __DIR__ . "\\core\\Model\\User.php";
                    require_once __DIR__ . "\\modules\\functions.php";

                    $userDeleteAccount = User::findById($_POST["id-delete-account"]);
                    if ($userDeleteAccount->delete($_POST["id-delete-account"])) {
                        $_SESSION = [];
                        header("Location: ./");
                        exit();
                    }
                } else {
                    echo "<script>alert('L\'email ne correspond pas.');</script>";
                }
            }

            // Méthodes pour l'éditeur de texte
            if (isset($_POST["load-text"])) {
                // Méthode pour charger son texte
                require_once __DIR__ . "\\core\\Model\\Media.php";

                // $userText = Media::findById();
                // $userText->textContent = sanitize($_POST[""])

                // $userText->findByTextContent();

            } else if (isset($_POST["save-text"])) {
                // Méthode pour sauvegarder son texte
                require_once __DIR__ . "\\core\\Model\\Media.php";

                $mediaSaveText = Media::findById($_SESSION["userid"]);
                $mediaSaveText->textContent = $_POST["redactor"];

            } else if (isset($_POST["exportPdf"])) {
                // Méthode pour exporter en pdf
                require_once __DIR__ . "\\core\\Model\\Media.php";

            }
        }
        

    } else {
        $alert = "Les informations sont erronées";
    }

    include_once __DIR__ . "\\includes\\header.php";
?>

    <!-- main page -->

    <main>
        
        <!-- Boutons de choix de la section -->
        <div class="tabs">
            <button type="button" id="first-tab" class="tablinks">Espace de travail</button>
            <button type="button" id="second-tab" class="tablinks">Informations personnelles</button>
        </div>

        <!-- Section workspace -->
        <section id="work-space" class="tabcontent">

            <section id="menu-workspace">

                <!-- Sélection des outils -->
                <article class="menu-creation">

                    <?php if (isset($_SESSION["auth"])): ?>
                        <h2>Bienvenue sur votre espace de travail <?= $_SESSION["pseudo"]; ?> !</h2>
                    <?php endif; ?>

                    <ul>

                        <li>
                            <a href="./timeline.php">
                                <button type="button" name="frise-chrono">Créer ma frise chronologique</button>
                            </a>
                        </li>

                        <li>
                            <a href="./map.php">
                                <button type="button" name="cartes">Créer ma carte historique</button>
                            </a>
                        </li>

                        <li>
                            <a href="./quiz.php">
                                <button type="button" name="quiz">Quiz d'histoire médiévale</button>
                            </a>
                        </li>

                    </ul>

                </article>

            </section>

            <hr>
            
            <section id="text-files">

                <!-- Fichiers créés par l'utilisateur -->
                <article class="produced-files">

                    <?php foreach ($iconurls as $textFile):
                        $textFile->loadAllUsers();
                        foreach ($textFile->users as $memberFile):
                            if ($memberFile->icon == $_SESSION["usericon"]):
                    ?>
                    
                        <div class="files-produced">
                            <button type="button">
                                <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEuMSIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zOnN2Z2pzPSJodHRwOi8vc3ZnanMuY29tL3N2Z2pzIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTIiIHhtbDpzcGFjZT0icHJlc2VydmUiIGNsYXNzPSIiPjxnPjxwYXRoIGQ9Ik0zMzAgMTUwYy0xOS4yOTkgMC0zNS0xNS43MDEtMzUtMzVWMEgxMTZDODUuNjczIDAgNjEgMjQuNjczIDYxIDU1djQwMmMwIDMwLjMyNyAyNC42NzMgNTUgNTUgNTVoMjgwYzMwLjMyNyAwIDU1LTI0LjY3MyA1NS01NVYxNTB6TTE0MyAzNjBoNzIuNzJjOC4yODQgMCAxNSA2LjcxNiAxNSAxNXMtNi43MTYgMTUtMTUgMTVIMTQzYy04LjI4NCAwLTE1LTYuNzE2LTE1LTE1czYuNzE2LTE1IDE1LTE1em0tMTUtNjVjMC04LjI4NCA2LjcxNi0xNSAxNS0xNWgyMjBjOC4yODQgMCAxNSA2LjcxNiAxNSAxNXMtNi43MTYgMTUtMTUgMTVIMTQzYy04LjI4NCAwLTE1LTYuNzE2LTE1LTE1em0yMzUtOTVjOC4yODQgMCAxNSA2LjcxNiAxNSAxNXMtNi43MTYgMTUtMTUgMTVIMTQzYy04LjI4NCAwLTE1LTYuNzE2LTE1LTE1czYuNzE2LTE1IDE1LTE1eiIgZmlsbD0iI2U1ZTVlNSIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgb3BhY2l0eT0iMSIgY2xhc3M9IiI+PC9wYXRoPjxwYXRoIGQ9Ik0zMjUgMTE1YzAgMi43NTcgMi4yNDMgNSA1IDVoMTE0LjMxNGE1NC44NjYgNTQuODY2IDAgMCAwLTEwLjUxNS0xMy43MzJsLTk2LjQyMy05MS4yMjJhNTUuMTM3IDU1LjEzNyAwIDAgMC0xMi4zNzUtOC44MjVWMTE1eiIgZmlsbD0iI2U1ZTVlNSIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgb3BhY2l0eT0iMSIgY2xhc3M9IiI+PC9wYXRoPjwvZz48L3N2Zz4=" alt="Icône fichier" />
                                <p><?= $textFile->title; ?></p>
                            </button>
                        </div>

                    <?php endif; 
                        endforeach; 
                    endforeach; ?>

                </article>

                <!-- Editeur de texte -->
                <article class="text-editor">
                    <form action="" method="post" enctype="multipart/form-data">
                        <textarea name="redactor" id="redactor" required></textarea>
                        <div class="flex-end">
                            <button type="button" name="load-text" data-target="#modal5-">Charger son texte</button>
                            <button type="button" name="save-text">Sauvegarder son texte</button>
                            <button type="button" name="export-pdf">Exporter en pdf</button>
                        </div>
                    </form>
                </article>

            </section>
        </section>
        
        <!-- Section utilisateur -->
        <section id="user-info" class="tabcontent">

            <!-- Icône utilisateur et sélection de celle-ci -->
            <?php 
                foreach ($iconurls as $iconurl):
                    $iconurl->loadAllUsers(); 
                    foreach ($iconurl->users as $membericon):
                        if ($_SESSION["usericon"] == $iconurl->id && $_SESSION["userid"] == $membericon->id):        
            ?>
                <article id="user-icon<?= $membericon->id; ?>" class="user-icon">
                    <div id="profile-icon<?= $membericon->id; ?>" class="profile-icon">
                        <a href="javascript:void(0)" class="user-choice" data-target="#modal-<?= $membericon->id; ?>">
                            <img src="<?= $iconurl->mediaUrl; ?>" alt="Icône utilisateur">
                        </a>
                    </div>
                </article>
            <?php       endif;
                    endforeach;
                endforeach; 
            ?>

            <!-- Tableau d'informations de compte de chaque utilisateur -->
            <?php foreach($members as $memberInfo):
                if ($_SESSION["userid"] == $memberInfo->id): ?>
                    <article id="user-table">

                        <table>
                            <tr>
                                <th>Pseudo</th>
                                <td><?= $_SESSION["pseudo"]; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= $_SESSION["email"]; ?></td>
                            </tr>
                            <tr>
                                <th>Date de création du compte</th>
                                <td>
                                    <?= $memberInfo->creationDate->format("d F Y"); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Modifier mon pseudo</th>
                                <td>
                                    <a href="javascript:void(0)" data-target="#modal2-<?= $memberInfo->id; ?>" class="user-choice">Modifier</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Modifier mon mot de passe</th>
                                <td>
                                    <a href="javascript:void(0)" data-target="#modal3-<?= $memberInfo->id; ?>" class="user-choice">Modifier</a>
                                </td>
                            </tr>
                            <?php if ($_SESSION["auth"] == true): ?>
                                <tr>
                                    <th>Supprimer mon compte</th>
                                    <td>
                                        <a href="javascript:void(0)" data-target="#modal4-<?= $memberInfo->id; ?>" class="user-choice">Supprimer mon compte</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>

                    </article>

                    <hr>

                    <!-- Affichage des scores aux quiz -->
                    <?php if ($memberInfo): ?>
                        <article id="user-scores">
                            <h2>Mes scores</h2>

                            <table>
                                <?php foreach ($members as $userScore):
                                    $userScore->loadAllScores(); 
                                    foreach ($userScore->scores as $score):
                                        if ($_SESSION["userid"] == $score->user): ?>
                                            <tr>
                                                <th>
                                                    Quiz n°<?= $score->quiz; ?>
                                                </th>
                                                <?php if ($score->quiz == 1): ?>
                                                    <td>
                                                        <?= $score->score; ?> / 10
                                                    </td>
                                                    <?php else: ?>
                                                    <td>
                                                        <?= $score->score; ?> / 20
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                <?php endif;
                                    endforeach; 
                                endforeach; ?>
                            </table>

                        </article>
                    <?php endif;?>

                <?php endif; 
            endforeach;; ?>

            <!-- Formulaire de sélection de l'icône -->
            <?php foreach ($members as $member): ?>

                <form action="" method="post" class="chose-icon" id="modal-<?= $member->id; ?>">
                    <div class="flex-start">
                        <span class="close-btn3" data-target="#modal-<?= $member->id; ?>">&times;</span>
                    </div>

                    <legend>Choisir son icône</legend>
                    <input type="hidden" name="id" value="<?= $member->id; ?>">
                    <select name="usericon" id="usericon<?= $member->id; ?>" class="usericon">
                        <?php foreach ($icons as $icon):?>
                            <option value="<?= $icon->id; ?>"><?= $icon->title; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">Valider</button>
                </form>

            <?php endforeach; ?>

            <!-- Formulaire de changement de pseudo -->
            <?php foreach ($members as $memberInfoChange): ?>

                <form action="" method="post" class="chose-icon" id="modal2-<?= $memberInfoChange->id; ?>">

                    <fieldset>
                        <legend>Modifier mon pseudo</legend>
                        <span class="close-btn3" data-target="#modal2-<?= $memberInfoChange->id; ?>">&times;</span>
                        
                        <input type="hidden" name="id-pseudo-change" value="<?= $memberInfoChange->id; ?>">

                        <div class="form">
                            <label for="">Nouveau pseudo</label>
                            <input type="text" name="new-pseudo" id="new-pseudo<?= $memberInfoChange->id; ?>" class="input-style" placeholder="Nouveau pseudo*" autocomplete="username" required>
                        </div>
                        
                        <div class="btn">
                            <button type="submit">Modifier mes informations</button>
                        </div>
                    </fieldset>
                        
                </form>

            <?php endforeach; ?>

            <!-- Formulaire de demande de changement du mot de passe -->
            <?php foreach ($members as $memberPwd): ?>

                <form action="" method="post" class="chose-icon" id="modal3-<?= $memberPwd->id; ?>">

                    <fieldset>
                        <legend>Modifier mon mot de passe</legend>
                        
                        <span class="close-btn3" data-target="#modal3-<?= $memberPwd->id; ?>">&times;</span>
                        
                        
                        <input type="hidden" name="id-pwd-change" value="<?= $memberPwd->id; ?>">
                        <div class="form">
                            <label for="username">Nouveau mot de passe</label>
                            <input type="password" name="new-pwd" id="new-pwd<?= $memberPwd->id; ?>" class="input-style" placeholder="Mon nouveau mot de passe" autocomplete="new-password" required>
                        </div>
                        <div class="form">
                            <label for="username">Confirmer le nouveau mot de passe</label>
                            <input type="password" name="confirm-new-pwd" id="confirm-new-pwd<?= $memberPwd->id; ?>" class="input-style" placeholder="Confirmer mon nouveau mot de passe" autocomplete="new-password" required>
                        </div>

                        <div class="btn">
                            <button type="submit">Modifier mon mot de passe</button>
                        </div>
                    </fieldset>

                </form>

            <?php endforeach; ?>

            <!-- Formulaire de suppression de son compte utilisateur -->
            <?php foreach ($members as $memberDeleteAccount): ?>

                <form action="" method="post" class="chose-icon" id="modal4-<?= $memberDeleteAccount->id; ?>">

                    <fieldset>
                        <legend>Supprimer mon compte</legend>
                        
                        <span class="close-btn3" data-target="#modal4-<?= $memberDeleteAccount->id; ?>">&times;</span>
                        
                        
                        <input type="hidden" name="id-delete-account" value="<?= $memberDeleteAccount->id; ?>">
                        <div class="form">
                            <label for="username">Confirmer son email</label>
                            <input type="email" name="email-confirmation" id="email-confirmation<?= $memberDeleteAccount->id; ?>" class="input-style" placeholder="Confirmer son email" required>
                        </div>

                        <div class="btn">
                            <button type="submit">Confirmer la suppression de mon compte</button>
                        </div>
                    </fieldset>

                </form>

            <?php endforeach; ?>

        </section>

    </main>

<?php 
    include_once __DIR__ . "\\includes\\footer.php";
?>