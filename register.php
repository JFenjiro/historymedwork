<?php
    session_name("hmw-php");
    session_start();

    use core\Model\User;
    use core\Model\Sanitizer;

    require __DIR__ . "\\Autoloader.php";
    Autoloader::register();

    require_once "./modules/variables.php";

    if(!empty($_POST)) {
        if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])) {
            if (strlen($_POST["password"]) > MINIMUM_PASS_LENGTH) {
                if (isset($_POST["confirm-password"]) && $_POST["password"] == $_POST["confirm-password"]) {

                    /* Méthode POO */
                    require_once __DIR__ ."\\modules\\functions.php";

                    $options = ["cost" => 12];
                    $newUser = new User();
                    $newUser->pseudo = Sanitizer::sanitize($_POST["username"]);
                    $newUser->email = Sanitizer::sanitize($_POST["email"]);
                    $newUser->hashPass = password_hash($_POST["password"], PASSWORD_DEFAULT, $options);

                    if ($newUser->save()) {
                        echo "<script>alert('Merci beaucoup de votre intérêt !<br>Bienvenue dans HistoryMedWork !')</script>";

                        $_SESSION = [];
                        header("Location: ./workspace.php");
                        exit();
                    } else {
                        echo "<script>alert('Désolé,<br>Nous n'avons pas pu traiter votre demande.')</script>";
                    }
                } else {
                    echo "<script>alert('Les deux mots de passe saisis ne correspondent pas !')</script>";
                }
            } else {
                echo "<script>alert('Le mot de passe doit contenir au moins " . (MINIMUM_PASS_LENGTH + 1) . " caractères.')</script>";
            }

        } else {
            echo "<script>alert('Veuillez renseigner les champs obligatoires pour continuer.')</script>";
        }
    }

    require_once "./includes/header.php";
?>

    <main>

    <!-- formulaire inscription -->
        <form action="" method="post" class="form-border">

            <fieldset>
                <legend>Inscrivez-vous</legend>
                <!-- Pseudo -->
                <div class="form">
                    <label for="pseudo1">Entrez un pseudo *</label>
                </div>
                <div class="form">
                    <input type="text" name="username" id="username" placeholder="Choisissez votre pseudo *" autocomplete="username" autofocus required>
                    <div id="message1"></div>
                </div>
                <!-- Email -->
                <div class="form">
                    <label for="email">Entrez un email *</label>
                </div>
                <div class="form">
                    <input type="email" name="email" id="email" placeholder="Entrez votre email *" autocomplete="email" required>
                </div>
                <!-- Mot de passe -->
                <div class="form">
                    <label for="password">Entrez un mot de passe *</label>
                </div>
                <div class="form">
                    <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe *" autocomplete="new-password" required>
                </div>
                <!-- Confirmation mot de passe -->
                <div class="form">
                    <label for="confirm-password">Confirmez votre mot de passe *</label>
                </div>
                <div class="form">
                    <input type="password" name="confirm-password" id="confirm-password" placeholder="Entrez à nouveau votre mot de passe *" autocomplete="new-password" required>
                </div>
                <!-- Bouton inscription -->
                <div class="btn">
                    <button type="submit">M'inscrire</button>
                </div>
            </fieldset>

        </form>

    </main>

<?php
    require_once "./modules/functions.php";
    require_once "./includes/footer.php";
?>
