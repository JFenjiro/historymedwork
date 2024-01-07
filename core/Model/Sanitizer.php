<?php
    namespace core\Model;

    class Sanitizer {
        private string $input;

        public function getInput() {
            return $this->input;
        }
        public function setInput(string $input) {
            $this->input = $input;
        }

        /**
         * Fonction qui nettoie les entrées utilisateur pour éviter 
         * les espaces inutiles en extrêmité de chaîne et les failles de type XSS
         * @param string $input Entrée utilisateur à nettoyer
         * @return string L'entrée utilisateur nettoyée.
         */
        public static function sanitize(string $input): string {
            if (isset($input) == null) {
                return "";
            }

            $output = trim($input);
            $output = strip_tags($output);
            return $output;
        }
    }