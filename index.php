<?php

// Définitions des chemins
define('FILES_ROOT', rtrim(dirname(__FILE__), '\/'.'/').'/');
define('APP_ROOT', rtrim(dirname($_SERVER['PHP_SELF']), '\/'.'/').'/');

// Fichiers de configuration
include FILES_ROOT.'config/settings.php';

// Gestion des erreurs PHP
if (DEBUG === true) {
    error_reporting(-1);
    ini_set('display_errors', 1);
    header('Cache-Control: max-age=1');
}

// Auto-chargement des classes
spl_autoload_register(function($class) {
    if (file_exists(FILES_ROOT.'vendor/'.str_replace('\\', '/', $class).'.php')) include FILES_ROOT.'vendor/'.str_replace('\\', '/', $class).'.php';
    if (file_exists(FILES_ROOT.'app/'.str_replace('\\', '/', $class).'.php')) include FILES_ROOT.'app/'.str_replace('\\', '/', $class).'.php';
});

// Initialisation de l'application
$app = new Library\Core\App;

// Import des routes
include FILES_ROOT.'config/routes.php';

// Lancement de l'application
$app->run();