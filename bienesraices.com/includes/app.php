<?php
require 'funciones.php';
require 'config/database.php';
require __DIR__ . '/../vendor/autoload.php';

// Carga variables de entorno
/* $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "../../");
$dotenv->load(); */
// Conectar db
/* $db_host = $_ENV['DB_HOST'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_name = $_ENV['DB_NAME']; */

$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASS');
$db_name = getenv('DB_NAME');

$db = conectarDB($db_host, $db_user, $db_pass, $db_name);

use App\ActiveRecord;

ActiveRecord::setDB($db);
