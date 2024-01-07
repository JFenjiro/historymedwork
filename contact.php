<?php
    session_name("hmw-php");
    session_start();

    use core\Model\User;
    use core\Model\Message;

    require __DIR__ . "\\Autoloader.php";
    Autoloader::register();

    if (!isset($_SESSION["auth"]) || $_SESSION["auth"] !== true) {
        header("Location: ./login.php");
        exit();
    }

    if (isset($_SESSION["user"]))
        $users = User::findAll();

    if (!empty($_POST["object"]) && !empty($_POST["message"])) {
        if ($_POST["email"] == $_SESSION["email"] && $_POST["pseudo"] == $_SESSION["pseudo"]) {
            require_once __DIR__ . "\\modules\\functions.php";

            $newMessage = new Message();
            $newMessage->object = sanitize($_POST["object"]);
            $newMessage->message = sanitize($_POST["message"]);
            $newMessage->sender = $_POST["id"];

            if ($newMessage->save()) {
                echo "<script>alert('Votre message a bien été pris en compte,<br>Nous vous répondrons dans les meilleurs délais.')</script>";

                // $to = "jfenjirou@gmail.com";
                // $subject = $newMessage->object;
                // $txt = wordwrap($newMessage->message, 70);
                // $headers = "From: " . $_SESSION["email"] . "\r\n";
                // mail($to, $subject, $txt, $headers);
            }
        } else {
            echo "<script>alert('Cet email est inconnu.')</script>";
        }
    }
    
    include_once __DIR__ . "\\includes\\header.php";
?>

    <!-- main page -->

    <main>
        
        <section id="text-files2">

            <!-- Formulaire de contact -->
            <article class="map-contact">

                <form action="" method="post" class="contact-form1">

                    <fieldset>

                        <legend>Contactez moi</legend>

                        <div class="contact-form2">
                            <input type="hidden" name="id" value="<?= $_SESSION["userid"]; ?>">
                        </div>
                        
                        <div class="contact-form2">
                            <label for="pseudo">Votre pseudo *</label>
                            <input type="text" name="pseudo" id="pseudo" placeholder="Pseudo" autocomplete="username" required>
                        </div>

                        <div class="contact-form2">
                            <label for="email">Votre email *</label>
                            <input type="email" name="email" id="email" placeholder="Email" autocomplete="email" required>
                        </div>

                        <div class="contact-form2">
                            <label for="object">Objet du message *</label>
                            <input type="text" name="object" id="object" placeholder="Objet" required>
                        </div>

                        <div class="contact-form2">
                            <label for="message">Votre message *</label>
                            <textarea name="message" id="message" placeholder="Tapez votre message" required></textarea>
                        </div>

                        <?php if (isset($alert)): ?>
                            <p><?= $alert; ?></p>
                        <?php elseif (isset($alert_success)): ?>
                            <p><?= $alert_success; ?></p>
                        <?php endif; ?>

                        <div class="contact-form3">
                            <button type="submit">Envoyer mon message</button>
                        </div>
                    
                    </fieldset>
                </form>

            </article>

            <!-- Image de historymedwork -->
            <article class="img-right">
                <img src="./assets/img/Castlelogo_silver.png" alt="Château">
            </article>

        </section>

    </main>

<?php
    include_once __DIR__ . "\\includes\\footer.php";
?>