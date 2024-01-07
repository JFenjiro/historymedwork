<?php
    use core\Model\User;

    require __DIR__ . "\\Autoloader.php";
    Autoloader::register();

    if (!empty($_POST)) {
        if (isset($_POST["email-token"]) && $_POST["email-token"] != "") {
            require_once "./modules/functions.php";

            $token = bin2hex(random_bytes(16));
            $userPwdChange = User::findByEmail($_POST["email-token"]);
            $userPwdChange->resetToken = $token;

            if ($userPwdChange->saveToken()) {
                require "./modules/mailer.php";

                if ($mail) {
                    $mail->isHTML(true);  
                    //Recipients
                    $mail->setFrom("historymedwork@gmail.com");
                    $mail->addReplyTo("noreply@historymedwork.com");
                    $mail->addAddress($userPwdChange->email);  //Name is optional
                    $mail->Subject = "Demande de reinitialisation du mot de passe";
                    $mail->Body = <<<END
                        Si vous voulez changer votre mot de passe, cliquez <a href="http://pc.jounayd.f/projets-perso/hmw-historymedwork/src/reset-password?token=$token">ici</a>.<br>
                        Si vous n'êtes pas à l'origine de la demande, veuillez ignorer ce message.<br><br>

                        History Med Work
                    END;
                }
                $mail->send();
                $alert_success =  "Le message de réinitialisation du mot de passe a été envoyé sur votre boîte mail.";
            }
        } else {
            $alert = "Les informations obligatoires n'ont pas été renseignées.";
        }
    }

    include_once __DIR__ . "\\includes\\header.php";
?>

    <!-- main page -->

    <main>
        <?php if (isset($alert)): ?>
            <div class="alert"><?= $alert; ?></div>
        <?php elseif (isset($alert_success)): ?>
            <div class="alert-success"><?= $alert_success; ?></div>
        <?php endif; ?>

        <form action="" method="post" class="form-border">
            <fieldset>
                <legend>Mot de passe oublié</legend>

                <div class="form">
                    <label for="email-token">Confirmation d'email</label>
                    <input type="email" name="email-token" id="email-token" placeholder="Confirmez votre email pour recevoir le lien *" autocomplete="email" required>
                </div>

                <div class="btn">
                    <button type="submit">Demander la réinitialisation du mot de passe</button>
                </div>

            </fieldset>
        </form>

    </main>


<?php
    include_once __DIR__ . "\\includes\\footer.php";
?>