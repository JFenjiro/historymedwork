<?php
    session_name("hmw-php");
    session_start();
    // error_reporting(0);

    use core\Model\Theme;
    use core\Model\Quiz;
    use core\Model\Question;
    use core\Model\Answer;

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
                                <option value="quiz-<?= $quiz->id; ?>"><?= $quiz->title; ?></option>
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
                <?php if ("quiz-". $quizz->id ."" == $_POST["states-history"] || "quiz-". $quizz->id ."" == $_POST["military-history"] || "quiz-". $quizz->id ."" == $_POST["biographies"]): ?>
                    <form action="./results.php" method="post" class="quiz-1" id="reg-form<?= $quizz->id; ?>" name="reg-form">
                    
                        <legend class="quiz-2"><?= $quizz->title; ?></legend>
                        <legend class="quiz-3"><?= $quizz->description; ?><br>Niveau de difficulté : <?= $quizz->difficultyLevel; ?></legend>
                        <!-- Récupère l'id du quiz -->
                        <input type="hidden" name="quiz-id" value="<?= $quizz->id; ?>">

                        <!-- Affiche la question selon le quiz -->
                        <?php foreach ($quizz->questions as $question): 
                            $question->loadAllAnswers(); ?>
                            <fieldset class="quiz-4">
                                <legend class="quiz-1">Question <?= "" . $question->number . " : " . $question->title . ""; ?></legend>
                                <input type="hidden" name="question-id" value="<?= $question->id; ?>">

                                <!-- Affiche la réponse selon la question -->
                                <?php foreach ($question->answers as $answer):
                                    if ($answer->type == "checkbox") : // Si les input des réponses sont du type checkbox... ?>
                                        <div class="item-1">
                                            <input type="checkbox" name="<?= $answer->nameCode; ?>" id="<?= "q" . $answer->id . "a"; ?>" value="<?= $answer->id; ?>">
                                            <label for="<?= "q" . $answer->id . "a"; ?>"><?= $answer->name; ?></label>
                                        </div>
                                    <?php else: // ...ou du type radio ?>
                                        <div class="item-1">
                                            <input type="radio" name="<?= $answer->nameCode; ?>" id="<?= "q" . $answer->id . "a"; ?>" value="<?= $answer->id; ?>" required>
                                            <label for="<?= "q" . $answer->id . "a"; ?>"><?= $answer->name; ?></label>
                                        </div>
                                    <?php endif;
                                endforeach; ?>

                            </fieldset>
                        <?php endforeach; ?>

                        <div class="item-1">
                            <button type="submit" id="see-score">Voir son score</button>
                        </div>
                        
                    </form>
                <?php endif; ?>

                <?php endforeach; 
            endif; ?>

            

        </section>

    </main>

<?php
    include_once __DIR__ . "\\includes\\footer.php";
?>