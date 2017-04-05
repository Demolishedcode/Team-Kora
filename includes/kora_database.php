<?php
// Create token
function genToken () {
  return $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
}

$user    = "root";
$pass    = "";
$host    = "127.0.0.1";
$db      = "team_kora";
$charset = "utf8";

$dsn     = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Connect to the database
$pdo = new PDO($dsn,$user,$pass,$options);

// Set timezone
date_default_timezone_set("Europe/Amsterdam");

//ini_set('display_errors','0');
?>
