<?php
    require_once __DIR__ ."\\..\\core\\Model\\Media.php";
    require_once __DIR__ ."\\..\\core\\Model\\Type.php";

    if (!empty($_POST) || !empty($_FILES)) {
        $uploadedTempFile = $_FILES["pj"]["tmp_name"];
        $uploadDir = "uploads/";

        // Si le fichier uploads n'existe pas déjà, le créer
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir);
        }

        if (is_uploaded_file($uploadedTempFile)) {
            if ($_FILES["pj"]["size"] < 2000000) {
                $mime_type = mime_content_type($uploadedTempFile);

                // Tableau contenant les types de fichier autorisés
                $allowed_mime_types = ["image/png", "image/jpeg", "application/pdf"];
    
                // Notre type est-il dans ce tableau ?
                if (in_array($mime_type, $allowed_mime_types)) {
                    $targetFile = $uploadDir . basename($_FILES["pj"]["name"]);
                    move_uploaded_file($uploadedTempFile, $targetFile);

                    $media = new Media();
                    $media->title = $_POST["title"];
                    $media->textContent = $_POST["text_content"];
                    $media->mediaUrl = $_POST["media_url"] ;
                    $media->type = $_POST["type"];
                    $media->pj = $targetFile;

                    $media->save();
                } else {
                    $alert = "Type de fichier non autorisé";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Essai Upload Files</title>
    </head>

    <body>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="media-title">Titre</label>
            <input type="text" name="media-title" id="media-title" placeholder="Titre du média"><br>
            <input type="file" name="pj" id="pj" accept="image/png, image/jpeg, application/pdf"><br>

            <button>Envoyer le fichier</button>
        </form>
        
    </body>

</html>