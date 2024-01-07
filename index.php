<?php
    session_name("hmw-php");
    session_start();
    
    require __DIR__ . "\\Autoloader.php";
    Autoloader::register();

    include_once __DIR__ . "\\includes\\header.php";
?>

    <!-- main page -->

    <main>

        <section class="container">

            <article class="center-right">

                <!-- Présentation -->
                <h1>History Med Work</h1>

                <div class="text-right">
                    <p>Bienvenue dans HistoryMedWork, un espace de travail et de partage pour tous les passionnés d'histoire médiévale !</p>
                    <p>Venez vous familiariser avec le site.</p>
                    <h2>Mon Espace de Travail</h2>
                    <p>Un espace de travail pour tous les passionnés d'histoire médiévale.</p>
                </div>

                <!-- Boutons de connexion et d'inscription -->
                <div class="flex-column">
                    <a href="./login.php">
                        <button type="button" name="connexion">Connexion</button>
                    </a>

                    <a href="./register.php">
                        <button type="button" name="inscription">Inscription</button>
                    </a>
                </div>

            </article>

        </section>

        <hr>

        <section id="text-files2">

            <article class="map-contact">
                
                <!-- Présentation des outils -->
                <div class="present">
                    <h2>Pourquoi utiliser History Med Work ?</h2>
                    <p>Si vous aimez l'histoire médiévale et vous divertir en travaillant, ce site est fait pour vous.</p>

                    <div class="present">
                        <h2>Ce que propose History Med Work</h2>
                        <ul>
                            <li>Un espace de travail personnalisé</li>
                            <a href="./workspace.php"><img src="./assets/img/screenshots/workspace.png" alt="Espace de travail virtuel"></a>
                        </ul>
                        <ul>
                            <li>Des quiz d'histoire médiévale</li>
                            <a href="./quiz.php"><img src="./assets/img/screenshots/quiz.png" alt="quiz d'histoire médiévale"></a>
                        </ul>
                        <ul>
                            <li>Des frises chronologiques personnalisables</li>
                            <a href="./timeline.php"><img src="./assets/img/screenshots/frise.png" alt="frise chronologique"></a>
                        </ul>
                        <ul>
                            <li>Des fonds de cartes historiques pour chaque aire du globe</li>
                            <a href="./map.php"><img src="./assets/img/screenshots/map.png" alt="fonds de carte"></a>
                        </ul>
                    </div>
                    
                </div>

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