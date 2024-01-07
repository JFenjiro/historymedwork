<?php
    session_name("hmw-php");
    session_start();

    use core\Model\User;
    use core\Model\Sanitizer;

    require __DIR__ . "\\Autoloader.php";
    Autoloader::register();
    

    // require_once __DIR__ . "\\core\\Model\\User.php";
    if (!empty($_POST)) {
        if (isset($_POST["username"]) && isset($_POST["password"])) {
            require_once __DIR__ . "\\modules\\functions.php";
    
            $options = ["cost" => 12];
            $username = Sanitizer::sanitize($_POST["username"]);
            $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT, $options);
    
            $userLog = new User();
            $userLog->findByPseudo($username);
        }
    }

    require_once __DIR__ . "\\includes\\header.php";
?>
    
    <!-- main page -->

    <main>

        <!-- formulaire connexion -->
        <form action="" method="post" class="form-border">
            <fieldset>
                <legend>Connectez-vous</legend>
                <div class="form">
                    <label for="username">Pseudo</label>
                </div>
                <div class="form">
                    <input type="text" name="username" id="username" autocomplete="username" value="" placeholder="Votre pseudo *" required>
                </div>
                <div class="form">
                    <label for="mdp">Mot de passe</label>
                </div>
                <div class="form">
                    <input type="password" name="password" id="password" autocomplete="current-password" placeholder="Votre mot de passe *" required>
                </div>
                <div class="form">
                    <a href="./send-password-reset.php">Mot de passe oublié ?</a>
                </div>
                <div class="btn">
                    <button type="submit">Me connecter</button>
                </div>
            </fieldset>
        </form>

    </main>

<?php
    require_once __DIR__ . "\\includes\\footer.php";
?>