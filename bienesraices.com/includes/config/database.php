<?php
/* require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "../../../");
$dotenv->load(); */

function conectarDB($db_host, $db_user, $db_pass, $db_name): mysqli
{
    /* $db_host = $_ENV['DB_HOST'];
    $db_user = $_ENV['DB_USER'];
    $db_pass = $_ENV['DB_PASS'];
    $db_name = $_ENV['DB_NAME']; */
    $db = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if (!$db) {
        echo 'Error no se pudo conectar';
        exit;
    }
    return $db;
}
