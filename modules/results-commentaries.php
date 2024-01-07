<?php
    if (count($questionResults) <= 10){
        switch($score) {
            case 0:
                $comments = "Chevalier, vous avez failli à votre mission !";
                break;
            case 1:
            case 2:
            case 3: 
            case 4:
            case 5:
                $comments = "Ne vous découragez pas. Lorsqu'un chevalier tombe de cheval, il remonte en selle.";
                break;
            case 6:
            case 7:
            case 8:
            case 9:
                $comments = "Vous connaissez votre art, chevalier. Continuez ainsi.";
                break;
            case 10:
                $comments = "Félicitations ! À ce niveau, vous pouvez défier des chevaliers vétérans.";
                break;
            default:
                $comments = "Où est donc votre noblesse ???";
                break;
            }
    } else {
        switch($score) {
            case 0:
                $comments = "Chevalier, vous avez failli à votre mission !";
                break;
            case 1:
            case 2:
            case 3: 
            case 4:
            case 5:
                $comments = "Ceci est fort regrettable. L'entraînement est la clé, chevalier.";
                break;
            case 6:
            case 7:
            case 8:
            case 9:
                $comments = "Ne vous découragez pas. Lorsqu'un chevalier tombe de cheval, il remonte en selle.";
                break;
            case 10:
            case 11:
            case 12:
                $comments = "Vous pouvez vous targuer de pas mal de connaissances chevalier mais ne prenez pas la grosse tête.";
                break;
            case 13:
            case 14:
            case 15:
                $comments = "Vous connaissez votre domaine, chevalier. Continuez ainsi.";
                break;
            case 16:
            case 17:
                $comments = "Ceci montre l'étendue de votre implication chevalier, vous êtes sur la voie de l'excellence.";
                break;
            case 18:
            case 19:
                $comments = "Félicitations ! À ce niveau, vous pouvez défier des chevaliers vétérans.";
                break;
            case 20:
                $comments = "Impressionnant ! Vous êtes la fine fleur de la chevalerie.";
                break;
            default:
                $comments = "Où est donc votre noblesse ???";
                break;
            }
    }
