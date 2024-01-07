<?php
    session_name("hmw-php");
    session_start();
    // error_reporting(0);

    use core\Model\User;
    use core\Model\Theme;
    use core\Model\Quiz;
    use core\Model\Question;
    use core\Model\Answer;
    use core\Model\Score;

    require __DIR__ . "\\Autoloader.php";
    Autoloader::register();

    if (!isset($_SESSION["auth"]) || $_SESSION["auth"] !== true) {
        header("Location: ./login.php");
        exit();
    }

    if (isset($_SESSION["user"])) {
        require_once __DIR__ . "\\modules\\variables.php";
        require_once __DIR__ . "\\modules\\functions.php";

        $themes = Theme::findAll();
        $quizzes = Quiz::findAll();
        $questions = Question::findAll();
        $answers = Answer::findAll();
        $scores = Score::findAll();
        $users = User::findAll();
    }

    
    include_once __DIR__ . "\\includes\\header.php";
?>
    
    <!-- main page -->

    <main>

        <section class="quiz-0">

            <!-- Sélecteur de quiz selon le thème : sélecteur latéral -->
            <form action="" method="post" class="select-map2" enctype="multipart/form-data">

                <fieldset>

                    <h2>Choisir son quiz</h2>

                    <!-- Sélectionner tous les thèmes... -->
                    <?php foreach ($themes as $theme): 
                        $theme->loadAllQuizzes(); ?>

                        <label for="<?= $theme->nameCode; ?>"><?= $theme->name; ?></label>
                        
                        <!-- ...Puis les filtrer par le quiz correspondant -->
                        <select name="<?= $theme->nameCode; ?>" id="<?= $theme->nameCode; ?>" class="select-item">
                                <option value="" selected>Choisir son quiz</option>
                            <?php foreach ($theme->quizzes as $quiz): ?>
                                <option value="<?= $quiz->id; ?>"><?= $quiz->title; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <div class="form2">
                            <button type="submit" class="searchmap2"><i class="fi fi-sr-check"></i></button>
                        </div>

                        <?php 
                    endforeach; ?>

                </fieldset>

            </form>
            
            <!-- Présentation de la page initiale -->
            <?php if (empty($_POST)): ?>
                <article id="present-quizzes">
                    <h2>Tous les Quiz de History Med Work</h2>
                    <h3>Bienvenue sur la partie quiz de History Med Work.<br>Venez tester votre culture générale sur l'histoire médiévale !<br>Sélectionnez le quiz qui vous intéresse.</h3>
                </article>
            <?php endif; ?>

            <!-- Quiz sélectionné -->
            <?php if (isset($_POST["states-history"]) || isset($_POST["military-history"]) || isset($_POST["biographies"])):
                foreach ($quizzes as $quizz): 
                    $quizz->loadAllQuestions(); ?>

                <!-- Renvoie le quiz sélectionné dans les select thématiques -->
                <?php if ($quizz->id == $_POST["states-history"] || $quizz->id == $_POST["military-history"] || $quizz->id == $_POST["biographies"]): ?>
                    <form action="" method="post" class="quiz-1" id="reg-form<?= $quizz->id; ?>" name="reg-form">
                    
                        <legend class="quiz-2"><?= $quizz->title; ?></legend>
                        <legend class="quiz-3"><?= $quizz->description; ?><br>Niveau de difficulté : <?= $quizz->difficultyLevel; ?></legend>

                        <!-- Affiche la question selon le quiz -->
                        <?php
                            // if (isset($_POST["states-history"])) {
                            //     $theme = $_POST["states-history"];
                            // } else {
                            //     $theme = null;
                            // }
                            $quizId = 0;

                            if (!$quizId) {
                                $quizId = $_POST["states-history"] ?? null;
                            }
                        
                            if (!$quizId) {
                                $quizId = $_POST["military-history"] ?? null;
                            }
                        
                            if (!$quizId) {
                                $quizId = $_POST["biographies"] ?? null;
                            }

                            $quizSelect = Quiz::findById($quizId);

                            if (!isset($_POST["question"])) {
                                $questionCourante = 1;
                            } else $questionCourante = $_POST["question"];

                            
                            $question = Question::findOneByQuizAndOrder($quizSelect-> id, $questionCourante);
                            $responses = Answer::findByQuestion($question->id);


                            if (isset($_POST["states-history"])) {
                                echo "<input type='hidden' name='states-history' value='$quizId'>";
                            } else if (isset($_POST["military-history"])) {
                                echo "<input type='hidden' name='military-history' value='$quizId'";
                            } else if (isset($_POST["biographies"])) {
                                echo "<input type='hidden' name='biographies' value='$quizId'";
                            }

                            if (isset($_POST["answer"])) {
                                // Afficher la question
                                echo "<legend name='question' id='" .  $question->id . "' value='" .  $questionCourante++ . "'>" . $question->title . "</legend>";
                                // Afficher les réponses de la question
                                foreach ($responses as $response):
                                    echo "<input type='radio' name='answer' id='" . $response->id . "' value='" . $response->id . "'>";
                                    echo "<label for='" . $response->id . "'>" . $response->name . "</label>";
                                endforeach;
                            } elseif (!isset($_POST["answer"])) {
                                echo "<legend name='question' id='" .  $question->id . "' value='" .  $questionCourante++ . "'>" . $question->title . "</legend>";
                                // Afficher les réponses de la question
                                foreach ($responses as $response):
                                    echo "<input type='radio' name='answer' id='" . $response->id . "' value='" . $response->id . "'>";
                                    echo "<label for='" . $response->id . "'>" . $response->name . "</label>";
                                endforeach;
                            }
                        ?>

                        <!-- Fin de la requête -->    

                        <div class="item-1">
                            <?php
                            if (isset($_POST["question"])) {
                                echo "<button type='submit' name='prev-question'>Question précédente</button>";
                                echo "<button type='submit' name='next-question'>Question suivante</button>";
                            } else {
                                echo "<button type='submit' name='next-question'>Question suivante</button>";
                            }
                            ?>
                            <button type="submit" id="see-score">Voir son score</button>
                        </div>

                        <div class="steps">
                            <?php 
                                for ($i=1; $i<=10; $i++): 
                            ?>
                                    <span class="step1"><?= $i; ?></span>
                            <?php 
                                endfor; 
                            ?>
                        </div>
                        
                    </form>
                <?php endif; ?>

                <?php endforeach; 
            endif; ?>

            <?php 
                if (isset($_POST["quiz-id"])): ?>

                    <h2>Résultats</h2>

                    <div id="results"><?= $finalScore; ?></div>

            <?php    
                endif;
            ?>

        </section>

    </main>

<?php
    include_once __DIR__ . "\\includes\\footer.php";
?>

<?php

//  if (isset($_POST["states-history"]) || isset($_POST["military-history"]) || isset($_POST["biographies"])):

    // theme selectionné

    // if (isset($_POST["states-history"])) {
    //     $theme = $_POST["states-history"];
    // } else {
    //     $theme = null;
    // }

    // if (!$quizId) {
    //     $quizId = $_POST["states-history"] ?? null;
    // }

    // if (!$quizId) {
    //     $quizId = $_POST["military-history"] ?? null;
    // }

    // if (!$quizId) {
    //     $quizId = $_POST["biographies"] ?? null;
    // }

    // // les quiz du thème selectionné
    //     $quiz = Quiz::findById($quizId);

    //     if (!isset($_POST["question"])) {
    //         $questionCourante = 1;
    //     } else $questionCourante = $_POST["question"];

        
    //     $question = Question::findOneByQuizAndOrder($quiz-> id, $questionCourante);
    //     $reponses = Answer::findByQuestion($question-> id);


    //     if (isset($_POST["states-history"])) {
    //         echo "<input type='hidden' name='states-history' value='$quizId'>";
    //     } else if (isset($_POST["military-history"])) {
    //         echo "<input type='hidden' name='military-history' value='$quizId'";
    //     } else if (isset($_POST["biographies"])) {
    //         echo "<input type='hidden' name='biographies' value='$quizId'";
    //     }

    //     // Afficher la question
    //         echo "<input type='radio' name='question' value='" .  $questionCourante++ . "'>";

        // Afficher les réponses de la question

    // endif;
?>