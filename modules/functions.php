<?php
    /**
     * Fonction qui nettoie les entrées utilisateur pour éviter 
     * les espaces inutiles en extrémité de chaîne et les failles de type XSS
     * @param string $input Entrée utilisateur à nettoyer
     * @return string L'entrée utilisateur nettoyée.
     */
    function sanitize(string $input): string {
        if (isset($input) == null) {
            return "";
        }

        $output = trim($input);
        $output = strip_tags($output);
        return $output;
    }