<?php
    // Méthode de déconnexion
    if (isset($_GET["logout"]) && $_GET["logout"] == 1) {
        session_destroy();
        $_SESSION = [];
        header("Location: ./");
        exit();
    }

    use core\Model\Media;

    $iconurls = Media::findAll(); 
?>

<!DOCTYPE html>
<html lang="fr">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Api éditeur de texte : tiny-cloud -->
        <script src="https://cdn.tiny.cloud/1/q5k18gtwalnlq6wd01jggicvpwlyfsrg3mqao4a3dqvn645j/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <!-- Api htmx -->
        <script src="https://unpkg.com/htmx.org@1.9.5" integrity="sha384-xcuj3WpfgjlKF+FXhSQFQ0ZNr39ln+hwjN3npfM9VBnUskLolQAcN80McRIVOPuO" crossorigin="anonymous"></script>

        <!-- Liens vers le style et les icônes -->
        <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
        <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-straight/css/uicons-solid-straight.css">
        <link rel="stylesheet" href="./assets/css/main.css">        

        <!-- Meta title -->
        <title>HistoryMedWork - L'espace quiz et outils en histoire médiévale</title>
        <!-- Favicon -->
        <link rel="shortcut icon" href="./assets/img/favicon/favicon.ico" type="image/x-icon">

    </head>

    <body>
    
    <!-- Header avec logo du site et menu principal -->
        <header>

            <!-- Logo de historymedwork -->
            <div class="dropdown-logo">
                <a href="javascript:void(0)" id="btn-scroll">
                    <img src="./assets/img/Logo_body_white.png" alt="Logo HistoryMedWork">
                </a>
                
                <!-- Modale de déconnexion -->
                <?php if (isset($_SESSION["auth"])): ?>
                    <div id="dropdown-disconnect">
                        <span id="close-btn2">&times;</span>
                        <?php 
                            foreach ($iconurls as $iconShow):
                                $iconShow->loadAllUsers(); 
                                foreach ($iconShow->users as $membericon):
                                    if ($_SESSION["usericon"] == $iconShow->id && $_SESSION["userid"] == $membericon->id):        
                        ?>
                            <article id="user-icon<?= $membericon->id; ?>" class="user-icon-2">
                                <div id="profile-icon<?= $membericon->id; ?>" class="profile-icon-2">
                                    <img src="<?= $iconShow->mediaUrl; ?>" alt="Icône utilisateur" id="img-icon">
                                </div>
                            </article>
                        <?php       endif;
                                endforeach;
                            endforeach; 
                        ?>
                        <p>Pseudo : <?= $_SESSION["pseudo"]; ?></p>
                        <a href="?logout=1" id="end-session"><i class="fi fi-sr-power"></i>Se déconnecter</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Navbar -->
            <nav>
                <ul>
                    <li class="dropdown">
                        <a href="./" class="center">
                            <i class="fi fi-sr-dungeon"></i>
                            <div class="dropdown-content">Accueil</div>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="./workspace.php" class="center">
                            <i class="fi fi-sr-fort"></i>
                            <div class="dropdown-content">Espace de Travail</div>
                        </a>  
                    </li>
                    <!-- La page contact n'apparaît qu'après connexion -->
                    <?php if (isset($_SESSION["user"])): ?>
                        <li class="dropdown">
                            <a href="./contact.php" class="center">
                                <i class="fi fi-sr-phone-flip"></i>
                                <div class="dropdown-content">Contact</div>
                            </a>   
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Bouton de clôture du menu en version responsive -->
                <span id="close-btn">
                    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="m6.41 18.29 9.59-9.59 9.59 9.59a2 2 0 0 0 2.82 0 2 2 0 0 0 0-2.83l-11-11a2 2 0 0 0 -2.82 0l-11 11a2 2 0 0 0 2.82 2.83z"/><path d="m3.59 27.54a2 2 0 0 0 2.82 0l9.59-9.54 9.59 9.59a2 2 0 0 0 2.82 0 2 2 0 0 0 0-2.83l-11-11a2 2 0 0 0 -2.82 0l-11 11a2 2 0 0 0 0 2.78z"/></svg>
                </span>
            </nav>

            <!-- Créneaux -->
            <section class="battlements">
                <?php for ($i=1; $i < 34; $i++): ?>
                    <div class="battlement"></div>
                <?php endfor; ?>
            </section>

        </header>

<?php ?>