<?php
// ============================================================
// POLICE ALL STAR PD — One-time Database Setup
// Upload to Replit, open in browser ONCE, then DELETE this file
// ============================================================

$host     = 'mysql-224eff1f-achikp-5496.c.aivencloud.com';
$db_name  = 'defaultdb';
$username = 'avnadmin';
$password = 'AVNS_f9Lm0JvFIN0ETYo-x8i';
$port     = '27178';

try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4",
        $username, $password,
        [PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false]
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $queries = [
        "users" => "CREATE TABLE IF NOT EXISTS users (
            user_id    VARCHAR(32)  NOT NULL PRIMARY KEY,
            user_name  VARCHAR(100) NOT NULL,
            avatar     VARCHAR(255) DEFAULT '',
            created_at DATETIME     DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "duty_logs" => "CREATE TABLE IF NOT EXISTS duty_logs (
            id               INT AUTO_INCREMENT PRIMARY KEY,
            user_id          VARCHAR(32)  NOT NULL,
            user_name        VARCHAR(100) NOT NULL,
            start_time       DATETIME     NOT NULL,
            end_time         DATETIME     DEFAULT NULL,
            duration         INT          DEFAULT 0,
            client_timestamp BIGINT       DEFAULT NULL,
            status           TINYINT(1)   DEFAULT 1,
            created_at       DATETIME     DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "cases" => "CREATE TABLE IF NOT EXISTS cases (
            id                 INT AUTO_INCREMENT PRIMARY KEY,
            case_number        VARCHAR(30)  NOT NULL UNIQUE,
            officer_id         VARCHAR(32)  NOT NULL,
            officer_name       VARCHAR(100) NOT NULL,
            suspect_name       VARCHAR(255) NOT NULL,
            case_type          VARCHAR(20)  DEFAULT 'เคสดำ',
            category           VARCHAR(100) DEFAULT NULL,
            location           VARCHAR(255) DEFAULT '',
            jail_minutes       INT          DEFAULT 0,
            fine_amount        INT          DEFAULT 0,
            items              LONGTEXT     DEFAULT NULL,
            items_text         LONGTEXT     DEFAULT NULL,
            assisting_officers LONGTEXT     DEFAULT NULL,
            discord_sent       TINYINT(1)   DEFAULT 0,
            created_at         DATETIME     DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    ];

    echo "<h2>🚔 POLICE ALL STAR PD — Database Setup</h2>";
    foreach ($queries as $table => $sql) {
        $conn->exec($sql);
        echo "✅ Table <b>{$table}</b> created successfully<br>";
    }
    echo "<br><h3 style='color:green'>✅ Setup complete! Delete this file now.</h3>";

} catch (PDOException $e) {
    echo "<h3 style='color:red'>❌ Error: " . $e->getMessage() . "</h3>";
}
?>
