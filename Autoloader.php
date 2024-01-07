<?php
    class Autoloader {

        public static function register() {

            spl_autoload_register(function($class) {

                $srcDir = ""; //"core/Model/";

                $file = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";

                if (file_exists($srcDir . $file)) {
                    require $srcDir . $file;
                    return true;
                }
                return false;
            });
        }
    }