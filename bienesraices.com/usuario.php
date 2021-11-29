<?php
require 'includes/app.php';
$db = conectarDB();

// Crear email y password
$email = "correo@correo.com";
$password = "123456";

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO usuarios (email, password) VALUES ('${email}', '${passwordHash}');";


exit;
mysqli_query($db, $query);
