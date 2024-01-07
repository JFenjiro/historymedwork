<?php 
    session_name("hmw-php");
    session_start();

    use core\Model\User;
    use core\Model\Timeline;

    require __DIR__ . "\\Autoloader.php";
    Autoloader::register();

    if (!isset($_SESSION["userid"])) {
        header("Location: ./login.php");
        exit;
    }
    
    include_once __DIR__ . "\\includes\\header.php";
?>
    
    <main>

        <!-- Frise chronologique canvas -->

        <section class="canvas-frise">

            <article>

                <canvas width="3000" height="1000" id="canvas1">
                    Le navigateur ne supporte pas l'élément canvas.
                </canvas>

            </article>

        </section>

    </main>

<?php 
    include_once __DIR__ . "\\includes\\footer.php";
?>

    

