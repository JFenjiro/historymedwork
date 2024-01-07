<?php
    session_name("hmw-php");
    session_start();

    use core\Model\User;
    use core\Model\Quiz;
    use core\Model\Question;
    use core\Model\Answer;
    use core\Model\Score;

    require __DIR__ . "\\Autoloader.php";
    Autoloader::register();

    // Compter le score du quiz
    if (isset($_POST["quiz-id"])) {
        $score = 0;
        
        $quizResult = Quiz::findById($_POST["quiz-id"]);

        $questionResults = Question::findByQuiz($quizResult->id);

        foreach ($questionResults as $questionName):
            $answerCurrent = 1;
            $answerResults = Answer::findOneByQuestionAndOrder($questionName->id, $answerCurrent);
            
            $answerName = $_POST[$answerResults->nameCode];
            $answerChoice = Answer::findById($answerName);

            $correctAnswersCount = Answer::countByValid(true);
            
            if ($correctAnswersCount == $answerChoice->isValid) {
                $score++;
            }
        endforeach;
        
        if (count($questionResults) <= 10) {
            $finalScore = "" . $score . " /10";
        } else {
            $finalScore = "" . $score . " /20";
        }  
    }

    if (isset($_POST["final-score"])) {
        $newScore = new Score();
        $newScore->score = $_POST["final-score"];
        $newScore->user = $_POST["user-target"];
        $newScore->quiz = $_POST["quiz-target"];

        if ($newScore->save()) {
            echo "<script>alert('Le score a bien été sauvegardé.')</script>";

            header('Location: ./quiz.php');
            exit();
        } else {
            echo "<script>alert('Le score n'a pas été sauvegardé correctement.')</script>";
        }
    }
    
    include_once __DIR__ . "\\includes\\header.php";
?>

    <main>

        <?php 
            if (isset($_POST["quiz-id"])): 
        ?>

            <form method="post" id="score-display" class="results-form">
                <fieldset class="results-fieldset">

                    <legend>Résultats</legend>
                    <!-- Affichage des résultats -->
                    <div class="form">
                        <div id="results"><?= $finalScore; ?></div>
                        <input type="hidden" name="final-score" id="final-score" value="<?= $score; ?>">
                        <input type="hidden" name="quiz-target" value="<?= $quizResult->id; ?>">
                        <input type="hidden" name="user-target" value="<?= $_SESSION["userid"]; ?>">

                        <!-- Affichage du commentaire -->
                        <?php if (isset($finalScore)): 
                            require_once "./modules/results-commentaries.php"; ?>
                            <p><?= $comments; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form">
                        <div id="desc-results">
                            <h2>Vos réponses</h2>
                            <?php 
                                if ($answerChoice): 
                                    foreach ($questionResults as $questionDesc): 
                                        $answerNow = 1;
                                        $answerRes = Answer::findOneByQuestionAndOrder($questionDesc->id, $answerNow);
                                        
                                        $answerLname = $_POST[$answerRes->nameCode];
                                        $answerDesc = Answer::findById($answerLname);
                                        // var_dump($answerDesc->description);die();
                            ?>
                                <h3>Question <?= $questionDesc->number; ?> : <?= $answerDesc->name; ?></h3>
                                <?php if ($answerDesc->isValid == true): ?>
                                    <p class="green"><?= $answerDesc->description; ?></p>
                                <?php else: ?>
                                    <p class="red"><?= $answerDesc->description; ?></p>
                                <?php endif; ?>
                            <?php 
                                    endforeach; 
                                endif; 
                            ?>
                        </div>
                    </div>
                    <!-- Enregistrer son score -->
                    <div class="form">
                        <button type="submit">Sauvegarder mon score</button>
                        <a href="./quiz.php">Ne pas sauvegarder mon score</a>
                    </div>

                </fieldset>
            </form>
            
            <div id="tsparticles">
                <canvas data-generated="true" style="width: 100% !important; height: 100% !important; position: fixed !important; z-index: 1 !important; top: 0px !important; left: 0px !important; pointer-events: none;" aria-hidden="true" width="1627" height="1182"></canvas>
            </div>
                
        <?php    
            endif;
        ?>
        
    </main>

<?php
    include_once __DIR__ . "\\includes\\footer.php";
?>