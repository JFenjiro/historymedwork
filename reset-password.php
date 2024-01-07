<?php
    session_name("hmw-php");
    session_start();

    use core\Model\User;

    require __DIR__ . "\\Autoloader.php";
    Autoloader::register();

    require __DIR__ . "\\modules\\variables.php";

    if (!isset($_GET["token"])) {
        die("Token non renseigné.");
    }

    if (isset($_GET["token"])) {

        // $token = $_GET["token"];
        $token = User::findByResetToken($_GET["token"]);
        
        if ($token::tokenExists($token->resetToken, $token->resetTokenExpires)) {
            
            if ($token->resetTokenExpires->format("Y-m-d H:i:s") <= time()) {
                die("Le token a expiré");
            }

            echo "<script>alert('Vous pouvez rentrer votre nouveau mot de passe.')</script>";
        } else {
            die("Token introuvable.");
        }
    }

    if (!empty($_POST)) {
        if (isset($_POST["modify-pwd-email"]) && isset($_POST["modify-pwd-pwd"])) {
            if (strlen($_POST["modify-pwd-pwd"]) > MINIMUM_PASS_LENGTH) {
                if (isset($_POST["modify-pwd-confirm"]) == $_POST["modify-pwd-pwd"]) {

                    $options = ["cost" => 12];
                    $userPwdModify = User::findByEmail($_POST["modify-pwd-email"]);
                    $userPwdModify->hashPass = password_hash($_POST["modify-pwd-pwd"], PASSWORD_DEFAULT, $options); 
    
                    if ($userPwdModify->editHashPass()) {
                        echo "<script>alert('Le mot de passe a été modifié avec succès')</script>";
                        header("Location: ./login.php");
                        exit();
                    }
                } else {
                    echo "<script>alert('Les deux mots de passe ne correspondent pas.')</script>";
                } 
            } else {
                echo "<script>alert('Le mot de passe doit contenir au moins " . MINIMUM_PASS_LENGTH . " caractères.')</script>";
            }
        } else {
            echo "<script>alert('Les informations obligatoires doivent être remplies.')</script>";
        }
    }

    include_once __DIR__ . "\\includes\\header.php";
?>

    <!-- main page -->
    
    <main>

        <form action="" method="post" class="form-border">

            <fieldset>
                <legend>Changer son mot de passe</legend>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token->resetToken); ?>">

                <div class="form">
                    <label for="modify-pwd-email">Confirmation d'email</label>
                    <input type="email" name="modify-pwd-email" id="modify-pwd-email" placeholder="Entrez votre email à nouveau *" autocomplete="email" required>
                </div>

                <div class="form">
                    <label for="modify-pwd-pwd">Entrer son nouveau mot de passe</label>
                    <input type="password" name="modify-pwd-pwd" id="modify-pwd-pwd" placeholder="Votre nouveau mot de passe *" autocomplete="new-password" required>
                </div>

                <div class="form">
                    <label for="modify-pwd-confirm">Confirmer son nouveau mot de passe</label>
                    <input type="password" name="modify-pwd-confirm" placeholder="Confirmez votre nouveau mot de passe *" autocomplete="new-password" required>
                </div>

                <div class="form">
                    <button type="submit">Demander un changement de mot de passe</button>
                </div>

            </fieldset>

        </form>

    </main>


<?php
    include_once __DIR__ . "\\includes\\footer.php";
?>