<?php
$host     = 'mysql-224eff1f-achikp-5496.c.aivencloud.com';
$db_name  = 'defaultdb';
$username = 'avnadmin';
$password = 'AVNS_f9Lm0JvFIN0ETYo-x8i';
$port     = '27178';

try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4",
        $username, $password,
        [
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::MYSQL_ATTR_SSL_CA => true,
        ]
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->exec("SET NAMES utf8mb4");
} catch(PDOException $e) {
    die("Connection Error: " . $e->getMessage());
}
?>
